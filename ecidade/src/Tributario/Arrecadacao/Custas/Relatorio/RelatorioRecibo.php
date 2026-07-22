<?php

namespace ECidade\Tributario\Arrecadacao\Custas\Relatorio;

use ECidade\Tributario\Arrecadacao\ModeloImpressao\Repository\CobrancaRegistrada;
use Exception;
use Instituicao;
use LancamentoTaxaDiversosRepository;
use Recibo;
use regraEmissao;
use ECidade\Tributario\Caixa\Enum\ArretipoEnum;
use CgmBase;
use ECidade\Tributario\Issqn\Model\Issbase;
use ECidade\Tributario\Cadastro\Model\Iptubase;
use db_impcarne;
use DateTime;
use db_utils;

class RelatorioRecibo
{
    /**
     * @param Recibo[] $recibos
     * @return string
     * @throws Exception
     */
    public function imprimir(array $recibos)
    {
        if (empty($recibos)) {
            throw new Exception('Não há recibos para a impressão!');
        }

        $regraEmissao = new regraEmissao(
            $recibos[0]->getTipoDebito(),
            $recibos[0]->getCadTipoMod(),
            $recibos[0]->getCodigoInstituicao(),
            date("Y-m-d", db_getsession("DB_datausu")),
            db_getsession('DB_ip'),
            true,
            false,
            $recibos[0]->getMinNumpar(),
            $recibos[0]->getMaxNumpar()
        );

        $pdfEmissao = $regraEmissao->getObjPdf();
        foreach ($recibos as $index => $recibo) {
            $pdfEmissao->parcel = $index;
            $pdfEmissao = $this->printOne($regraEmissao, $recibo, $pdfEmissao);

            $pdfEmissao->imprime();
        }

        $sNomeArquivo = "Recibo" . date('dmYGis') . ".pdf";
        $pdfEmissao->objpdf->output('tmp/' . $sNomeArquivo, false, true);

        unset($_SESSION["DB_obsrecibo"]);

        return ECIDADE_REQUEST_PATH . 'tmp/' . $sNomeArquivo;
    }

    public function printOne(regraEmissao $oRegraEmissao, Recibo $oRecibo, db_impcarne $pdfEmissao)
    {
        $dataAtual = date('d/m/Y', db_getsession('DB_datausu'));
        $sIdentificacao = "";
        $instituicao = new Instituicao($oRecibo->getCodigoInstituicao());
        $oConvenio = $oRecibo->getConvenio();
        $cgmExibido = $oRecibo->getCgmExibicao();
        $identificacao = $oRecibo->getIdentificacao();
        $dataVencimento = new DateTime($oRecibo->getDataVencimento());
        $dataVencimentoFormatada = $dataVencimento->format('d/m/Y');

        $pdfEmissao->pqllocal = '';
        $pdfEmissao->partilhaDtPaga = '';
        $pdfEmissao->sMensagemContribuinte = '';
        $pdfEmissao->sMensagemCaixa = '';
        $pdfEmissao->partilhaTipoLancamento = '';

        if ($oRegraEmissao->isCobranca()) {
            $pdfEmissao->agencia_cedente = $oRecibo->getConvenio()->getAgenciaCedente();
            $pdfEmissao->carteira = $oRecibo->getConvenio()->getCarteira();
            $pdfEmissao->nosso_numero = $oRecibo->getConvenio()->getNossoNumero();
        }

        $pdfEmissao->tipo_convenio = $oRecibo->getConvenio()->getTipoConvenio();
        $pdfEmissao->codigoConvenio = $oRegraEmissao->getConvenio();

        $pdfEmissao->uf_config = $instituicao->getUf();
        $pdfEmissao->modelo = 2;
        // Imagem do logo nao utiliza a constante ECIDADE_PATH
        $pdfEmissao->logo = $instituicao->getImagemLogo();
        $pdfEmissao->dtparapag = $dataVencimentoFormatada;

        $historico = "\n{$oRecibo->getHistorico(3)}\n{$oRecibo->getArretipo()->getMsgrecibo()}";

        if ($oRegraEmissao->getCadTipoConvenio() == 6) {
            $pdfEmissao->sCedenteBoleto = $oRegraEmissao->getNomeConvenio();
            $pdfEmissao->sTituloInstrucoes = 'TEXTO DE RESPONSABILIDADE DO CEDENTE';
        }

        $pdfEmissao->prefeitura = $instituicao->getDescricao();
        if ($oRegraEmissao->getCadTipoConvenio() == 1) {
            $pdfEmissao->prefeitura = $oRegraEmissao->getNomeConvenio();
        }

        $pdfEmissao->enderpref = $instituicao->getLogradouro();
        $pdfEmissao->numeropref = $instituicao->getNumero();
        $pdfEmissao->municpref = $instituicao->getMunicipio();
        $pdfEmissao->telefpref = $instituicao->getTelefone();
        $pdfEmissao->cgcpref = $instituicao->getCNPJ();
        $pdfEmissao->emailpref = $instituicao->getEmail();
        $pdfEmissao->nome = "{$cgmExibido->getCodigo()} - {$cgmExibido->getNomeCompleto()}";
        $pdfEmissao->ender = "{$cgmExibido->getLogradouro()}, {$cgmExibido->getNumero()}"
            . "{$cgmExibido->getComplemento()}"
            . (strlen($cgmExibido->getBairro()) > 0 ? "/" : "") . $cgmExibido->getBairro();
        $pdfEmissao->munic = $cgmExibido->getMunicipio();
        $pdfEmissao->cep = $cgmExibido->getCep();
        $pdfEmissao->cgccpf = $cgmExibido->isJuridico() ? $cgmExibido->getCNPJ() : $cgmExibido->getCPF();
        $pdfEmissao->ufcgm = $cgmExibido->getUf();
        $pdfEmissao->ip = db_getsession("DB_ip");
        $pdfEmissao->tipolograd = 'Logradouro:';
        $pdfEmissao->pretipolograd = 'Logradouro:';
        $pdfEmissao->nomepri = $cgmExibido->getLogradouro();
        $pdfEmissao->nomepriimo = $cgmExibido->getLogradouro();
        $pdfEmissao->prenomepri = $cgmExibido->getLogradouro();
        $pdfEmissao->tipocompl = 'Número:';
        $pdfEmissao->pretipocompl = 'Número:';
        $pdfEmissao->nrpri = $cgmExibido->getNumero();
        $pdfEmissao->prenrpri = $cgmExibido->getNumero();
        $pdfEmissao->complpri = $cgmExibido->getComplemento();
        $pdfEmissao->precomplpri = $cgmExibido->getComplemento();
        $pdfEmissao->tipobairro = 'Bairro:';
        $pdfEmissao->pretipobairro = 'Bairro:';
        $pdfEmissao->bairropri = $cgmExibido->getBairro();
        $pdfEmissao->prebairropri = $cgmExibido->getBairro();
        $pdfEmissao->bairrocontri = $cgmExibido->getBairro();
        $pdfEmissao->dtvenc = db_formatar($oRecibo->getDataVencimento(), "d");
        $pdfEmissao->datacalc = db_formatar($oRecibo->getDataVencimento(), "d");
        $pdfEmissao->predatacalc = db_formatar($oRecibo->getDataVencimento(), "d");
        $pdfEmissao->taxabanc = db_formatar($oRecibo->getArretipo()->getTxban(), 'f');
        $pdfEmissao->receita = 'k00_receit';
        $pdfEmissao->valor = 'valor';
        $pdfEmissao->receitared = 'codreduz';
        $pdfEmissao->dreceita = 'k02_descr';
        $pdfEmissao->ddreceita = 'k02_drecei';

        /** @todo Mover este código para algum lugar */
        db_inicio_transacao();
        $sqlObs = "insert into recibopagahist values ({$oRecibo->getNumpreRecibo()},'" . addslashes($historico) . "')";
        db_query($sqlObs);
        db_fim_transacao();

        /** @todo Ver se o critério para o filtro foi matrícula ou cgm */
        $historico .= LancamentoTaxaDiversosRepository::getObservacoesTaxas(
            $cgmExibido->getCodigo(),
            $oRecibo->getNumpreRecibo()
        );

        $pdfEmissao->historico = $historico;
        $pdfEmissao->histparcel = @$histparcela;

        $pdfEmissao->valororigem = db_formatar($oRecibo->getValorOrigem(), 'f');
        $pdfEmissao->valtotal = db_formatar($oRecibo->getTotalRecibo(), 'f');
        $pdfEmissao->linhadigitavel = $oConvenio->getLinhaDigitavel();
        $pdfEmissao->linha_digitavel = $oConvenio->getLinhaDigitavel();
        $pdfEmissao->codigobarras = $oConvenio->getCodigoBarra();
        $pdfEmissao->codigo_barras = $oConvenio->getCodigoBarra();
        $pdfEmissao->texto = db_getsession('DB_login') . ' - ' . date("d-m-Y - H-i") . '   ' . db_getsession('DB_base');

        $pdfEmissao->descr3_1 = $cgmExibido->getCodigo() . "-" . trim($cgmExibido->getNome());
        $pdfEmissao->descr3_2 = "{$cgmExibido->getLogradouro()}, "
            . "{$cgmExibido->getNumero()} {$cgmExibido->getComplemento()}"
            . (strlen($cgmExibido->getBairro()) > 0 ? "/" : "") . $cgmExibido->getBairro();
        $pdfEmissao->predescr3_1 = trim($cgmExibido->getNome());
        $pdfEmissao->predescr3_2 = "{$cgmExibido->getLogradouro()}, {$cgmExibido->getNumero()}"
            . " {$cgmExibido->getComplemento()} "
            . (strlen($instituicao->getBairro()) > 0 ? "/" : "")
            . $instituicao->getBairro();
        $pdfEmissao->premunic = $cgmExibido->getMunicipio();

        $pdfEmissao->precep = $cgmExibido->getCep();
        $pdfEmissao->precgccpf = ($cgmExibido->isJuridico()) ? $cgmExibido->getCNPJ() : $cgmExibido->getCpf();
        $pdfEmissao->cgccpfcomprador = ($cgmExibido->isJuridico()) ? $cgmExibido->getCNPJ() : $cgmExibido->getCpf();

        $pdfEmissao->titulo5 = "";
        $pdfEmissao->descr5 = "";
        foreach ($oRecibo->getReceitasCustas() as $custa) {
            $pdfEmissao->arraycodreceitas[] = $custa->iReceita;
            $pdfEmissao->arrayreduzreceitas[] = '';
            $pdfEmissao->arraydescrreceitas[] = $custa->taxa->getDescricao();
            $pdfEmissao->arrayvalreceitas[] = $custa->nValorReceita;
            $pdfEmissao->arraycodhist[] = $custa->iHistorico;
            $pdfEmissao->arraycodtipo[] = $custa->taxa->isAplicaJuroMulta();
        }

        $pdfEmissao->descr4_1 = $historico;
        $pdfEmissao->historicoparcela = $historico;
        $pdfEmissao->prehistoricoparcela = $historico;
        $pdfEmissao->descr4_2 = "";
        $pdfEmissao->predescr4_2 = "";
        $pdfEmissao->descr12_2 = "";
        $pdfEmissao->descr6 = $dataVencimentoFormatada;
        $pdfEmissao->descr7 = db_formatar($oRecibo->getTotalRecibo(), 'f');  // qtd de URM ou valor
        $pdfEmissao->descr9 = str_pad($oRecibo->getNumpreRecibo() . "000", 11, 0, STR_PAD_LEFT);
        $pdfEmissao->predescr6 = $dataVencimentoFormatada;  // Data de Vencimento
        $pdfEmissao->predescr7 = db_formatar($oRecibo->getTotalRecibo(), 'f');  // qtd de URM ou valor
        $pdfEmissao->predescr9 = str_pad(
            $oRecibo->getNumpreRecibo() . "000",
            11,
            0,
            STR_PAD_LEFT
        ); // cod. de arrecadação

        $sSqlMsgCarne = "select k03_msgbanco from numpref where k03_anousu = " . db_getsession('DB_anousu');
        $rsMsgcarne = db_query($sSqlMsgCarne);
        $msgBanco = '';
        if (pg_num_rows($rsMsgcarne)) {
            $result = pg_fetch_array($rsMsgcarne);
            $msgBanco = $result['k03_msgbanco'];
        }

        $pdfEmissao->descr16_1 = substr($msgBanco, 0, 50);
        $pdfEmissao->descr16_2 = substr($msgBanco, 50, 50);
        $pdfEmissao->descr16_3 = substr($msgBanco, 100, 50);
        $pdfEmissao->predescr16_1 = substr($msgBanco, 0, 50);
        $pdfEmissao->predescr16_2 = substr($msgBanco, 50, 50);
        $pdfEmissao->predescr16_3 = substr($msgBanco, 100, 50);

        $pdfEmissao->descr11_1 = $cgmExibido->getCodigo() . "-" . $cgmExibido->getNome();
        $pdfEmissao->descr11_2 = "";
        if ($cgmExibido->getLogradouro() != "") {
            $pdfEmissao->descr11_2 = trim($cgmExibido->getLogradouro())
                . ", " . trim($cgmExibido->getNumero())
                . '  ' . trim($cgmExibido->getComplemento());
        }
        $pdfEmissao->descr11_3 = $cgmExibido->getMunicipio();
        $pdfEmissao->uf = $cgmExibido->getUf();

        $k00_mensagemdesconto = "\n";
        $k00_mensagemdesconto .= "DESCONTO CONCEDIDO REFERENTE ";


        $msgDesconto = "DESCONTO CONCEDIDO REFERENTE";
        $sqlmensagemdesconto = "select distinct
                               k99_desconto,
                               k40_descr
                          from db_reciboweb
                         inner join cadtipoparc on cadtipoparc.k40_codigo = k99_desconto
                         where k99_numpre_n = {$oRecibo->getNumpreRecibo()}";

        $resultmensagemdesconto = db_query($sqlmensagemdesconto);
        $mensagens = db_utils::makeCollectionFromRecord($resultmensagemdesconto, function ($registro) {
            return explode('#', $registro->k40_descr)[0];
        });
        // Valida se existe mensagem de desconto
        if (empty($mensagens)) {
            $msgDesconto = "";
        } else {
            $msgDesconto .= ' ' . implode(' - ', $mensagens);
        }

        $pdfEmissao->descr12_1 = "{$historico}\n\n{$msgDesconto}";

        $pdfEmissao->descr14 = $dataVencimentoFormatada;
        $pdfEmissao->descr10 = $oRecibo->getParcelaAtual() . " / " . $oRecibo->getQuantidadeParcelas();
        $pdfEmissao->tipo_exerc = $oRecibo->getTipoDebito() . " / " . $dataVencimento->format('Y');
        $pdfEmissao->k03_tipo = $oRecibo->getArretipo()->getTipo();
        $pdfEmissao->tipo_debito = $oRecibo->getTipoDebito();
        $pdfEmissao->especie = "R$";

        $pdfEmissao->data_processamento = $dataAtual;
        $pdfEmissao->loteamento = false;

        // verifica se é ficha e busca o codigo do banco
        if ($oRegraEmissao->isCobranca()) {
            $cldb_bancos = new \cl_db_bancos;
            $rsConsultaBanco = $cldb_bancos->sql_record(
                $cldb_bancos->sql_query_file($oRecibo->getConvenio()->getCodBanco())
            );
            $oBanco = \db_utils::fieldsMemory($rsConsultaBanco, 0);
            $pdfEmissao->numbanco = $oBanco->db90_codban . "-" . $oBanco->db90_digban;
            $pdfEmissao->banco = $oBanco->db90_abrev;

            try {
                $pdfEmissao->imagemlogo = ECIDADE_PATH . $oRecibo->getConvenio()->getImagemBanco();
            } catch (Exception $eExeption) {
                db_redireciona("db_erros.php?fechar=true&db_erro=[15] - " . $eExeption->getMessage());
            }

            if (in_array($oRecibo->getArretipo()->getTipo(), array(18, 12, 13))) {
                $pdfEmissao->aExercValor = CobrancaRegistrada::getDebitosRecibo(
                    $oRecibo->getNumpreRecibo(),
                    db_getsession("DB_instit")
                );

                $pdfEmissao->nTaxaBancaria = $oRecibo->getArretipo()->getTxban();
                $pdfEmissao->valor_cobrado = $oRecibo->getTotalRecibo();

                $pdfEmissao->msgcontribuinte = $oRecibo->getArretipo()->getMsgparc();
                $pdfEmissao->msgbanco = $oRecibo->getArretipo()->getMsgparc2();
                $pdfEmissao->msgrecibo = $oRecibo->getArretipo()->getMsgrecibo();

                if ($oRecibo->getArretipo()->getTipo() == 13) {
                    $pdfEmissao->sHistoricoIniciaisParcelamento = $oRecibo->filterHistorico(ArretipoEnum::INICIAL_FORO);
                }
            }
        }

        if ($identificacao instanceof CgmBase) {
            $tipo = 'Numcgm:';
            $cgm = $identificacao->getCodigo();

            $pdfEmissao->tipoinscr = $tipo;
            $pdfEmissao->titulo8 = $tipo;
            $pdfEmissao->pretitulo8 = $tipo;
            $pdfEmissao->nrinscr = $cgm;
            $pdfEmissao->descr8 = $cgm;
            $pdfEmissao->predescr8 = $cgm;
            $sIdentificacao = $tipo . ' ' . $cgm;
        } elseif ($identificacao instanceof Issbase) {
            $tipo = 'Inscrição:';
            $inscricao = $identificacao->getInscr();
            $empresa = $identificacao->withEmpresa()->getEmpresa();

            // Mostrar o endereço da empresa
            $pdfEmissao->ender = "{$empresa->getLogradouro()}, {$empresa->getNumero()}"
            . "{$empresa->getComplemento()}"
            . (strlen($empresa->getBairro()) > 0 ? "/" : "") . $empresa->getBairro();
            $pdfEmissao->nomepri = $empresa->getLogradouro();
            $pdfEmissao->nomepriimo = $empresa->getLogradouro();
            $pdfEmissao->prenomepri = $empresa->getLogradouro();
            $pdfEmissao->nrpri = $empresa->getNumero();
            $pdfEmissao->prenrpri = $empresa->getNumero();
            $pdfEmissao->complpri = $empresa->getComplemento();
            $pdfEmissao->precomplpri = $empresa->getComplemento();
            $pdfEmissao->bairropri = $empresa->getBairro();
            $pdfEmissao->prebairropri = $empresa->getBairro();
            $pdfEmissao->bairrocontri = $empresa->getBairro();

            $pdfEmissao->tipoinscr = $tipo;
            $pdfEmissao->titulo8 = $tipo;
            $pdfEmissao->pretitulo8 = $tipo;
            $pdfEmissao->nrinscr = $inscricao;
            $pdfEmissao->descr8 = $inscricao;
            $pdfEmissao->predescr8 = $inscricao;
            $pdfEmissao->identifica_dados = 'Alvará';
            $sIdentificacao = $tipo . ' ' . $inscricao;
        } elseif ($identificacao instanceof Iptubase) {
            $lote = $identificacao->withLote()->getLote();
            $setores = $lote->withSetorLoc()->getSetorLocs();

            $tipo = 'Matrícula:';
            $identificaTipo = 'Imóvel';
            $matric = "{$identificacao->getMatric()} " .
            "SQL: {$lote->getCodigoSetor()}.{$lote->getQuadra()}.{$lote->getLote()}";

            $pdfEmissao->tipoinscr = $tipo;
            $pdfEmissao->titulo8 = $tipo;
            $pdfEmissao->pretitulo8 = $tipo;
            $pdfEmissao->nrinscr = $matric;
            $pdfEmissao->descr8 = $matric;
            $pdfEmissao->predescr8 = $matric;
            $pdfEmissao->identifica_dados = $identificaTipo;

            $pdfEmissao = $this->getEnderecoMatricula($identificacao->getMatric(), $pdfEmissao);
            $pdfEmissao->refant = $identificacao->withIptuAnterior()->getIptuAnterior();

            if (!empty($setores)) {
                $setor = $setores[0];

                $pqlLocal = "PQL: {$setor->getCodigoProprio()}-{$setor->getDescricao()}/" .
                            "{$setor->getQuadra()}/{$setor->getLote()}";

                $pdfEmissao->pqllocal = $pqlLocal;
                $pdfEmissao->pql_localizacao = $pqlLocal;
            }
            $sIdentificacao = $tipo . ' ' . $matric;
        } else {
            throw new Exception('Não foi possível identificar o destinatário para o recibo!');
        }


        if (!empty($oRecibo->getProcessosForo())) {
            $pdfEmissao->msgcontribuinte .= "\nProcesso(s) do foro: " . implode(', ', $oRecibo->getProcessosForo());
        }

        $pdfEmissao->descr12_1 = "\n" . $sIdentificacao . $pdfEmissao->descr12_1;
        $pdfEmissao->numnov_recibo = $oRecibo->getNumpreRecibo();
        if (($instituicao->getCodigoCliente() == 4 || $instituicao->getCodigoCliente() == 7107)
            && $pdfEmissao->impmodelo == 2
        ) {
            $pdfEmissao->lUtilizaModeloDefault = false;
        }
        return $pdfEmissao;
    }

    /**
     * TODO
     * Refatorar sql nao deveria aparecer nesta classe
     * @param integer $matricula
     * @param imp_carne $pdf
     * @return imp_carne
     */
    private function getEnderecoMatricula($matricula, $pdf)
    {

        $sql= "
            select
                z01_ender,
                nomepri as j43_ender,
                j39_compl as j43_compl,
                j39_numero,
                case
                    when j13_descr is not null and j13_descr != ''
                        then j13_descr
                    else ''
                end as j13_descr,
                j34_setor||'.'||j34_quadra||'.'||j34_lote as sql
            from
                proprietario
            where
                j01_matric = {$matricula}
            limit 1";
        $rs   = db_query($sql);

        if (pg_numrows($rs) > 0) {
            $dados =  \db_utils::fieldsMemory($rs, 0);
            $pdf->nomepri = $dados->z01_ender;
            $pdf->prenomepri = $dados->j43_ender;
            $pdf->nrpri = $dados->j39_numero;
            $pdf->prenrpri = $dados->j39_numero;
            $pdf->complpri = empty($dados->j43_compl) ? '' : $dados->j43_compl;
            $pdf->precomplpri = empty($dados->j43_compl) ? '' : $dados->j43_compl;
            $pdf->bairropri = $dados->j13_descr;
            $pdf->nomepriimo = $dados->j43_ender;
        }

        return $pdf;
    }
}
