<?php

namespace ECidade\Patrimonial\Material\Services;

use Almoxarifado;
use App\Domain\Patrimonial\Material\Models\LancamentoMovimentacao;
use BusinessException;
use cl_matestoqueini;
use cl_matestoqueinil;
use cl_matestoqueinill;
use cl_matestoqueinimei;
use DBDate;
use DBException;
use Exception;
use Instituicao;
use materialEstoque;
use MaterialEstoqueAlmoxarifado;
use MaterialEstoqueItem;
use MaterialEstoqueMovimentacao;
use ParameterException;
use ParametroIntegracaoPatrimonial;
use stdClass;
use TipoMovimentacaoEstoque;

class EntradaManualService
{
    /**
     * @throws DBException
     * @throws ParameterException
     * @throws Exception
     */
    public function cancelarEntradaManual($m80_codigo, $observacao)
    {
        $clmatestoqueini = new cl_matestoqueini;
        $clmatestoqueinil = new cl_matestoqueinil;
        $clmatestoqueinill = new cl_matestoqueinill;
        $clmatestoqueinimei = new cl_matestoqueinimei;

        $lancamentoEntradaManual = LancamentoMovimentacao::find($m80_codigo);

        if (is_null($lancamentoEntradaManual)) {
            throw new Exception("Erro ao buscar registros da Entrada Manual!");
        }

        $materialEstoqueItem = $lancamentoEntradaManual->movimentacoes->first()->estoqueItem;
        $matestoqueitem = new \MaterialEstoqueItem($materialEstoqueItem->m71_codlanc);

        $materialEstoque = $matestoqueitem->getEstoque();
        $oMaterial    = $materialEstoque->getMaterial();

        /**
         * Validar e efetuar ajuste
         */
        $saldoAtual = $this->buscarSaldoAtual($materialEstoque);
        $quantidadeFinal = $saldoAtual->quantidade - $matestoqueitem->getQuantidade();

        $valorFinal = $saldoAtual->valor - $matestoqueitem->getValor();
        if ($quantidadeFinal == 0 && $valorFinal != 0) {
            $this->efetuarLancamentoAjuste($matestoqueitem, $valorFinal, $oMaterial);
        } elseif ($quantidadeFinal > 0 && $valorFinal <= 0) {
            $precoMedioAtual = $saldoAtual->valor/$saldoAtual->quantidade;
            $valorAdicionar = $quantidadeFinal*$precoMedioAtual;
            $this->efetuarLancamentoAjuste($matestoqueitem, $valorFinal, $oMaterial, $valorAdicionar);
        }

        /**
         * ver acima
         */

        MaterialEstoque::bloqueioMovimentacaoItem($oMaterial->getCodigo(), $materialEstoque->getCodigoDepartamento());
        if ($materialEstoqueItem->m71_quantatend != 0) {
            throw new Exception("Lançamento já atendido.\\n\\nCancelamento não efetuado.");
        }

        $clmatestoqueinil->m86_matestoqueini = $lancamentoEntradaManual->m80_codigo;
        $clmatestoqueinil->incluir(null);
        $vaipromatestoqueinill = $clmatestoqueinil->m86_codigo;
        if ($clmatestoqueinil->erro_status == 0) {
            throw new Exception($clmatestoqueinil->erro_msg);
        }

        $this->validaDataMovimentacao($lancamentoEntradaManual);
        $codigoTipo = $this->buscarTipoMovimentoInverso($lancamentoEntradaManual);

        $clmatestoqueini->m80_login = db_getsession("DB_id_usuario");
        $clmatestoqueini->m80_data = date("Y-m-d", db_getsession("DB_datausu"));
        $clmatestoqueini->m80_hora = date('H:i:s');
        $clmatestoqueini->m80_obs = $observacao;
        $clmatestoqueini->m80_codtipo = $codigoTipo;
        $clmatestoqueini->m80_coddepto = db_getsession("DB_coddepto");
        $clmatestoqueini->incluir(null);
        $matestoqueininovo = $clmatestoqueini->m80_codigo;

        if ($clmatestoqueini->erro_status == 0) {
            throw new Exception($clmatestoqueini->erro_msg);
        }

        $clmatestoqueinill->m87_matestoqueini = $matestoqueininovo;
        $clmatestoqueinill->m87_matestoqueinil = $vaipromatestoqueinill;
        $clmatestoqueinill->incluir($vaipromatestoqueinill);
        if ($clmatestoqueinill->erro_status == 0) {
            throw new Exception($clmatestoqueinill->erro_msg);
        }

        $matestoqueitem->setQuantidadeAtendida($matestoqueitem->getQuantidade());
        $matestoqueitem->salvar();

        $clmatestoqueinimei->m82_matestoqueitem = $materialEstoqueItem->m71_codlanc;
        $clmatestoqueinimei->m82_matestoqueini = $matestoqueininovo;
        $clmatestoqueinimei->m82_quant = $matestoqueitem->getQuantidade();
        $clmatestoqueinimei->incluir(null);
        if ($clmatestoqueinimei->erro_status == 0) {
            throw new Exception($clmatestoqueinimei->erro_msg);
        }

        $oInstituicao = new Instituicao(db_getsession("DB_instit"));
        $dtAtual = date("Y-m-d", db_getsession("DB_datausu"));
        $oDataAtual = new DBDate($dtAtual);

        /**
         * Efetua os Lancamentos Contabeis de entrada no estoque
         */
        if (USE_PCASP && (ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataAtual, $oInstituicao))) {
            $oDadosEntrada = new stdClass();

            $oMaterialEstoque = new materialEstoque($oMaterial->getCodigo());
            $oDadosEntrada->iMovimentoEstoque = $clmatestoqueinimei->m82_codigo;
            $oDadosEntrada->sObservacaoHistorico = $observacao;
            $oDadosEntrada->nValorLancamento = $matestoqueitem->getValor();
            $oDadosEntrada->iContaPCASP = $oMaterialEstoque->getGrupo()->getConta();
            $oDadosEntrada->iCodigoMaterial = $oMaterial->getCodigo();
            $oAlmoxarifado = new \Almoxarifado(db_getsession('DB_coddepto'));

            $oAlmoxarifado->saidaManual($oDadosEntrada);
        }
    }

    /**
     * @throws Exception
     */
    private function validaDataMovimentacao(LancamentoMovimentacao $lancamentoEntradaManual)
    {
        $timestampSessao = strtotime(date('Y-m-d', db_getsession('DB_datausu')) . " " . date('H:i:s'));
        $dataHoraMovimentacao = $lancamentoEntradaManual->m80_data . ' ' . $lancamentoEntradaManual->m80_hora;
        $timestampMovimentacao = strtotime($dataHoraMovimentacao);
        if ($timestampSessao < $timestampMovimentacao) {
            throw new Exception(
                'Data e hora atual deve ser posterior a data e hora do registro, cancelamento abortado!'
            );
        }
    }

    /**
     * @throws Exception
     */
    private function buscarTipoMovimentoInverso(LancamentoMovimentacao $lancamentoEntradaManual)
    {
        switch ($lancamentoEntradaManual->m80_codtipo) {
            case TipoMovimentacaoEstoque::IMPLANTACAO:
            case TipoMovimentacaoEstoque::IMPLANTACAO_ALTERADA:
                return TipoMovimentacaoEstoque::IMPLANTACAO_CANCELADA;
            case TipoMovimentacaoEstoque::ENTRADA_MANUAL:
            case TipoMovimentacaoEstoque::ENTRADA_MANUAL_ALTERADA:
                return TipoMovimentacaoEstoque::ENTRADA_MANUAL_CANCELADA;
            default:
                throw new Exception("Tipo de lançamento inválido para esse cancelamento!");
        }
    }

    /**
     * @throws Exception
     */
    private function buscarSaldoAtual(MaterialEstoqueAlmoxarifado $materialEstoque)
    {
        $clmatestoqueinimei = new cl_matestoqueinimei;
        $sqlSaldos = $clmatestoqueinimei->sql_query(
            null,
            'sum(case when m81_tipo = 1
                                then m82_quant*m89_valorunitario
                            when m81_tipo = 2
                                then (m82_quant*m89_valorunitario)*-1 end) as valor,
                    sum(case when m81_tipo = 1
                                then m82_quant
                            when m81_tipo = 2
                                then m82_quant*-1 end) as quantidade',
            null,
            "m71_codmatestoque = {$materialEstoque->getCodigo()}"
        );
        $rsSaldos = db_query($sqlSaldos);
        if (!$rsSaldos) {
            throw new Exception("Erro ao verificar saldo do estoque.");
        }

        return pg_fetch_object($rsSaldos);
    }

    /**
     * @throws BusinessException
     * @throws DBException
     * @throws ParameterException
     * @throws Exception
     */
    private function efetuarLancamentoAjuste(
        \MaterialEstoqueItem $oEstoqueItem,
        $valorFinal,
        \MaterialAlmoxarifado $oMaterial,
        $valorAdicionar = 0
    ) {
        $oDataAtual = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
        $oEstoqueMovimentoAjusteEntrada = new MaterialEstoqueMovimentacao();
        $oEstoqueMovimentoAjusteEntrada->setData($oDataAtual);
        $oEstoqueMovimentoAjusteEntrada->setHora(date('H:i:s'));
        $oEstoqueMovimentoAjusteEntrada->setCodigoDepartamento(db_getsession("DB_coddepto"));
        $oEstoqueMovimentoAjusteEntrada->setCodigoUsuario(db_getsession("DB_id_usuario"));
        $oEstoqueMovimentoAjusteEntrada->setMovimento(
            new TipoMovimentacaoEstoque(TipoMovimentacaoEstoque::AJUSTE_ESTOQUE_ENTRADA)
        );
        $oEstoqueMovimentoAjusteEntrada->setObservacao("Ajuste de estoque.");
        $oEstoqueMovimentoAjusteEntrada->salvar();

        $matestoqueinimeiAjusteEntrada = MaterialEstoqueItem::vincularMovimentacaoComItem(
            $oEstoqueItem,
            $oEstoqueMovimentoAjusteEntrada,
            1
        );

        $oEstoqueMovimentoAjusteSaida = new MaterialEstoqueMovimentacao();
        $oEstoqueMovimentoAjusteSaida->setData($oDataAtual);
        $oEstoqueMovimentoAjusteSaida->setHora(date('H:i:s'));
        $oEstoqueMovimentoAjusteSaida->setCodigoDepartamento(db_getsession("DB_coddepto"));
        $oEstoqueMovimentoAjusteSaida->setCodigoUsuario(db_getsession("DB_id_usuario"));
        $oEstoqueMovimentoAjusteSaida->setMovimento(
            new TipoMovimentacaoEstoque(TipoMovimentacaoEstoque::AJUSTE_ESTOQUE_SAIDA)
        );
        $oEstoqueMovimentoAjusteSaida->setObservacao("Ajuste de estoque.");
        $oEstoqueMovimentoAjusteSaida->salvar();

        $matestoqueinimeiAjusteSaida = MaterialEstoqueItem::vincularMovimentacaoComItem(
            $oEstoqueItem,
            $oEstoqueMovimentoAjusteSaida,
            1
        );

        $oDadosEntrada = new stdClass();
        $oDadosEntrada->sObservacaoHistorico = sprintf(
            '%s %s',
            'Ajuste correspondente a correção de distorções meramente contábeis,',
            'causadas pela variação de preços.'
        );
        $oDadosEntrada->nValorLancamento = abs($valorFinal) + $valorAdicionar;
        if ($oMaterial->getGrupo() == null) {
            throw new Exception("Erro ao buscar Grupo do Material");
        }
        $oDadosEntrada->iContaPCASP = $oMaterial->getGrupo()->getConta();
        $oDadosEntrada->iCodigoMaterial = $oMaterial->getCodigo();

        $oAlmoxarifado = new Almoxarifado(db_getsession('DB_coddepto'));

        $oDataImplantacao = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
        $oInstituicao     = new Instituicao(db_getsession('DB_instit'));
        if (USE_PCASP && ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataImplantacao, $oInstituicao)) {
            if ($valorFinal < 0) {
                $oDadosEntrada->iMovimentoEstoque = $matestoqueinimeiAjusteEntrada;
                $oAlmoxarifado->entradaManual($oDadosEntrada);
            } else {
                $oDadosEntrada->iMovimentoEstoque = $matestoqueinimeiAjusteSaida;
                $oAlmoxarifado->saidaManual($oDadosEntrada);
            }
        }
    }
}
