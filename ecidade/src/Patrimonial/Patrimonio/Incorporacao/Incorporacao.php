<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Patrimonial\Patrimonio\Incorporacao;

use Bem;
use DBDate;
use ECidade\Patrimonial\Material\Estoque\Movimentacao\EntradaManual;
use ECidade\Patrimonial\Patrimonio\Incorporacao\Repository\MaterialIncorporadoRepository;
use ECidade\Patrimonial\Patrimonio\Incorporacao\Repository\MaterialPendenteIncorporacaoRepository;
use Inventario;
use InventarioBem;
use MaterialEstoqueAlmoxarifado;
use UsuarioSistema;

class Incorporacao
{
    /**
     * @var Bem
     */
    private $bem;

    /**
     * @var MaterialIncorporadoRepository
     */
    private $materiaisIncorporado;

    /**
     * @var boolean
     */
    private $reavaliar;

    /**
     * @var DBDate
     */
    private $data;

    /**
     * @var UsuarioSistema
     */
    private $usuario;


    /**
     * @return Bem
     */
    public function getBem()
    {
        return $this->bem;
    }

    /**
     * 707 - inclusao de material
     * 709 - inclusao de serviço
     * 708 - estorno de material
     * 710 - estorno de serviço
     * @var array
     */
    private $documentos = array(
        'inclusao' => array(707, 709),
        'estorno' => array(708, 710),
    );

    /**
     * @param Bem $bem
     * @return Incorporacao
     */
    public function setBem(Bem $bem)
    {
        $this->bem = $bem;

        return $this;
    }

    /**
     * @param MaterialIncorporadoRepository $materiaisIncorporar
     * @return Incorporacao
     */
    public function setMateriais(MaterialIncorporadoRepository $materiaisIncorporar)
    {
        $this->materiaisIncorporado = $materiaisIncorporar;
        return $this;
    }


    /**
     * @param DBDate $data
     * @return Incorporacao
     */
    public function setData(DBDate $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param $reavaliar
     */
    public function setReavaliar($reavaliar)
    {
        $this->reavaliar = $reavaliar;
    }

    /**
     * @param UsuarioSistema $usuario
     */
    public function usuario(UsuarioSistema $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @throws \Exception
     */
    public function incorporar()
    {
        $this->materiaisIncorporado->persist();

        if ($this->reavaliar) {
            $this->reavaliar($this->calculaValorReavaliacao());
        }

        $this->baixarMaterialEstoque();

        $this->efetuarLancamentos();
    }

    /**
     * @throws \ParameterException
     * @throws \Exception
     */
    public function cancelar()
    {
        if (!$this->podeCancelar()) {
            $msg = "Não é possivel desprocessar incorporação de bens pois já foi processada a depreciação da competência";
            $msg .= " dos itens selecionados.\nPara cancelar a depreciação acesse:\n";
            $msg .= "DB:PATRIMONIAL > Patrimônio > Procedimentos > Processamento da Depreciação > Desprocessamento";
            throw new \Exception($msg);
        }

        $this->devolverItensEstoque();

        $valorReavaliacao = $this->calculaValorReavaliacao();
        if ($valorReavaliacao > 0) {
            $this->reavaliar($valorReavaliacao * (-1));
        }

        $this->efetuarLancamentos(true);
        $this->inativarMateriaisIncorporados();
    }

    /**
     * @throws \Exception
     */
    private function baixarMaterialEstoque()
    {
        // buscar o depto da entrada
        foreach ($this->materiaisIncorporado->get() as $item) {
            $material = new \materialEstoque($item->getMaterialPendenteIncorporado()->getCodigoMaterial());

            \MaterialEstoque::bloqueioMovimentacaoItem(
                $item->getMaterialPendenteIncorporado()->getCodigoMaterial(),
                $item->getMaterialPendenteIncorporado()->getCodigoDepartamento()
            );
            $material->setCodDepto($item->getMaterialPendenteIncorporado()->getCodigoDepartamento());
            $msg = "Incoporado ao bem {$this->bem->getIdentificacao()} - {$this->bem->getDescricao()}";
            $material->saidaMaterial($item->getQuantidade(), $msg);
        }
    }

    private function calculaValorReavaliacao()
    {
        $valorReavaliacao = 0;
        foreach ($this->materiaisIncorporado->get() as $bemIncorporado) {

            if ($bemIncorporado->isReavaliar()) {
                $valorReavaliacao += ($bemIncorporado->getQuantidade() * $bemIncorporado->getMaterialPendenteIncorporado()->getValorUnitario());
            }
        }
        return $valorReavaliacao;
    }

    /**
     * @param $valorReavaliacao pode ser um valor negativo para efetuar uma reavaliação para menos no valor
     * @throws \BusinessException
     * @throws \DBException
     */
    private function reavaliar($valorReavaliacao)
    {
        $inventario = new Inventario();
        $bem = $this->getBem();
        $inventarioBem = new InventarioBem();
        $inventarioBem->setBem($bem);
        $inventarioBem->setValorDepreciavel((($bem->getValorAtual() + ($valorReavaliacao)) - $bem->getValorResidual()));
        $inventarioBem->setValorResidual($bem->getValorResidual());
        $inventarioBem->setVidaUtil($bem->getVidaUtil());
        $inventario->reavaliarBem($inventarioBem);
    }

    /**
     * Realiza o lançamento contabil do item
     * @param bool $estorno
     * @throws \BusinessException
     * @throws \Exception
     */
    private function efetuarLancamentos($estorno = false)
    {
        $documentos = $this->documentos['inclusao'];
        if ($estorno) {
            $documentos = $this->documentos['estorno'];
        }

        foreach ($this->materiaisIncorporado->get() as $material) {

            $documento = $documentos[0];
            if ($material->getMaterialPendenteIncorporado()->isServico()) {
                $documento = $documentos[1];
            }
            $descricaoMaterial = $material->getMaterialPendenteIncorporado()->getDescricao();
            $observacao = "incorporação do item {$descricaoMaterial} ao bem {$this->bem->getIdentificacao()} - {$this->bem->getDescricao()}.";
            if ($estorno) {
                $observacao = "estorno da {$observacao}";
            }
            $lancamentoAuxiliar = new \LancamentoAuxiliarBem();
            $lancamentoAuxiliar->setBem($this->bem);
            $lancamentoAuxiliar->setEstorno($estorno);
            $lancamentoAuxiliar->setObservacaoHistorico(ucfirst($observacao));
            $lancamentoAuxiliar->setValorTotal($material->getValorTotal());

            $eventoContabil = new \EventoContabil($documento, $this->data->getAno(), $this->bem->getInstituicao());
            $eventoContabil->executaLancamento($lancamentoAuxiliar, $this->data->getDate());
        }

    }

    /**
     * Devolve o item para o estoque e recria o material pendente
     * @throws \ParameterException
     * @throws \Exception
     */
    private function devolverItensEstoque()
    {
        $devolucaoMaterialPendente = new MaterialPendenteIncorporacaoRepository();
        foreach ($this->materiaisIncorporado->get() as $itemIncorporado) {
            $mensagem = "Devolução da incorporação do bem {$this->bem->getIdentificacao()} - {$this->bem->getDescricao()}.";

            $material = new \MaterialAlmoxarifado($itemIncorporado->getMaterialPendenteIncorporado()->getCodigoMaterial());
            $departamento = \DBDepartamentoRepository::getPorCodigo($itemIncorporado->getMaterialPendenteIncorporado()->getCodigoDepartamento());

            $materialEstoque = MaterialEstoqueAlmoxarifado::getEstoquePorMaterialDepartamento($material, $departamento);
            $devolucaoEstoque = new EntradaManual();
            $devolucaoEstoque->setMaterial($materialEstoque);
            $devolucaoEstoque->setUsuario($this->usuario);
            $devolucaoEstoque->setValorUnitario($itemIncorporado->getMaterialPendenteIncorporado()->getValorUnitario());
            $devolucaoEstoque->setQuantidade($itemIncorporado->getQuantidade());
            $devolucaoEstoque->setData($this->data);
            $devolucaoEstoque->setObservacao($mensagem);
            $devolucaoEstoque->salvar();

            $novoVinculoEstoque = $devolucaoEstoque->getCodigoMatEstoqueIniMei();
            $novo = clone $itemIncorporado->getMaterialPendenteIncorporado();
            $novo->setVinculoEstoque($novoVinculoEstoque);
            $devolucaoMaterialPendente->add($novo);
        }

        $devolucaoMaterialPendente->persiste();
    }

    /**
     * inativa os materiais incorporados ao bem
     * @throws \Exception
     */
    private function inativarMateriaisIncorporados()
    {
        foreach ($this->materiaisIncorporado->get() as $itemIncorporado) {
            $itemIncorporado->setAtivo(false);
        }

        $this->materiaisIncorporado->persist();
    }

    /**
     * Valida se pode realizar o cancelamento da incorporação.
     * Só é possivel cancelar a incorporação de bem se:
     * - O bem incorporado não foi reavaliado no momento da incorporação;
     * - Caso tenha sido reavaliado na incorporação:
     * -- Se não foi realizada escrituração contábil;
     * -- Se não haver depreciação processada no mês seguinte
     *
     * @return bool
     * @throws \Exception
     */
    private function podeCancelar()
    {
        $maiorReavaliacao = $this->identificaDataUltimaReavaliacao();
        if (is_null($maiorReavaliacao)) {
            return true;
        }

        $ano = $maiorReavaliacao->getAno();
        $mes = $maiorReavaliacao->getMes();
        $where = array(
            "t78_ano       = {$ano}",
            "t78_instit    = {$mes}",
            "t78_mes       = {$this->bem->getInstituicao()}",
            "t78_estornado is false",
        );
        // verifica se ja foi escriturado na contabilidade no mês
        $daoContabilidade = new \cl_bensdepreciacaolancamento();
        $rs = db_query($daoContabilidade->sql_query_file(null, 1, null, implode(' and ', $where)));
        if (!$rs) {
            throw new \Exception("Erro ao verificar se possui escrituração contábil.");
        }
        // se já foi processado, não pode cancelar
        if (pg_num_rows($rs) > 0) {
            return false;
        }

        // para validar o historico de depreciação, sempre validamos o próximo mes
        if ($mes == 12) {
            $ano ++;
            $mes = 1;
        }

        $where = array(
            "benshistoricocalculo.t57_ano >= {$ano}",
            "benshistoricocalculo.t57_mes > {$mes}",
            "benshistoricocalculo.t57_ativo is true",
            "benshistoricocalculo.t57_processado is true",
        );
        $daoHistorico = new \cl_benshistoricocalculo();
        $rsHistorico = db_query($daoHistorico->sql_query_file(null, '*', null, implode(' and ', $where)));
        if (!$rsHistorico) {
            throw new \Exception("Erro ao validar se possui processamento da depreciação." . $daoHistorico->erro_msg);
        }

        if (pg_num_rows($rsHistorico) == 0) {
            return true;
        }

        return false;
    }



    /**
     * Valida se todos materiais selcionados (que foram reavaliados) são da mesma competência
     * e retorna a data da maior reavaliação
     *
     * Retorna null se não haver materiais reavaliados entre os selecionados
     * @return DBDate|null
     * @throws \Exception
     */
    private function identificaDataUltimaReavaliacao()
    {
        $competenciasSelecionadas = array(); // para validar se foi selecionado mais de um mês
        $maiorReavaliacao = null;
        foreach ($this->materiaisIncorporado->get() as $material) {
            $competenciaSelecionada = $this->montaCompetencia($material->getData());

            if ($material->isReavaliar()) {
                if (empty($maiorReavaliacao)) {
                    $maiorReavaliacao = $material->getData();
                    $competenciasSelecionadas[] = $competenciaSelecionada;
                }
                // dentre os materiais incorporados selecionados, guarda a competencia da incorporação
                if (!in_array($this->montaCompetencia($material->getData()), $competenciasSelecionadas)) {
                    $competenciasSelecionadas[] = $this->montaCompetencia($material->getData());
                }

                if ($maiorReavaliacao->getTimeStamp() < $material->getData()->getTimeStamp()) {
                    $maiorReavaliacao = $material->getData();
                }
            }

        }

        if (count($competenciasSelecionadas) > 1) {
            throw new \Exception("Não é possível estornar materiais/serviços de competências diferentes.\nVeja a data de Incorporação.");
        }

        return $maiorReavaliacao;
    }

    private function montaCompetencia(DBDate $data)
    {
        return $data->getMes() . '/' . $data->getAno();
    }


}