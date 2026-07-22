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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification('libs/db_utils.php'));

use ECidade\Saude\Laboratorio\Exame\Resultado\ImpressaoMatricialLote;
use ECidade\Saude\Laboratorio\Model\Responsavel;
use ECidade\Saude\Laboratorio\Repository\Responsavel as ResponsavelRepository;

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

try {
    $oGet = db_utils::postMemory($_GET);
    $datas = explode(',', $oGet->datas);

    $dataInicio = $datas[0];
    $dataFim = $datas[1];
    $laboratorio = $oGet->laboratorio;
    $setor = $oGet->labsetor;
    $exame = $oGet->exame;
    $modeloImpressao = $oGet->iAtributo;

    if ($modeloImpressao == 2) {
        $impressaoMatricialLote = new ImpressaoMatricialLote();
    }

    $join  = " JOIN lab_requiitem on la21_i_requisicao = la22_i_codigo";
    $join .= " JOIN lab_setorexame on la09_i_codigo = la21_i_setorexame";
    $join .= " JOIN lab_labsetor on la09_i_labsetor = la24_i_codigo";

    $where = " WHERE la22_d_data BETWEEN '$dataInicio' AND '$dataFim'";
    $orderBy = " ORDER BY la22_i_codigo";
    $groupBy = " GROUP BY la22_i_codigo";

    $sql  = "SELECT lab_requisicao.* FROM lab_requisicao       ";
    $sql .= $join;
    $sql .= $where;
    $sql .= $groupBy;
    $sql .= $orderBy;

    $rs = db_query($sql);

    if ($rs == false) {
        throw new DBException('Erro ao realizar consulta.');
    }

    if (pg_num_rows($rs) == 0) {
        throw new DBException('Nenhum exame para os filtros selecionados.');
    }

    $dados = array();

    while ($objRequisicao = pg_fetch_object($rs)) {
        $requisicao = $objRequisicao->la22_i_codigo;

        $oDadosEstrutura                 = new stdClass();
        $oDadosEstrutura->iLarguraPadrao = 192;
        $oDadosEstrutura->iAlturaPadrao  = 5;
        $oDadosEstrutura->aSetor         = array();
        $oDadosEstrutura->aExames        = array();
        $oDadosEstrutura->iRequisicao    = $requisicao;
        $oDadosEstrutura->sData          = ''  ;

        $oDadosEstrutura->oSolicitante          = new stdClass();
        $oDadosEstrutura->oSolicitante->iCodigo = '';
        $oDadosEstrutura->oSolicitante->sNome   = '';
        $oDadosEstrutura->oSolicitante->sSexo   = '';
        $oDadosEstrutura->oSolicitante->iIdade  = '';

        $oRequisicaoLaboratorial                = new RequisicaoLaboratorial($requisicao);
        $oDadosEstrutura->oSolicitante->sMedico = $oRequisicaoLaboratorial->getMedico();

        /**
         * Array com atributos que possuem valor para impressão.
         * Ao buscar os dados, caso encontre o registro, incrementa o array
         */
        $aAtributosSelecionaveis  = array();
        $oDaoAtributoSelecionavel = new cl_lab_valorreferenciasel();
        $sSqlAtributos            = $oDaoAtributoSelecionavel->sql_query_file();
        $rsAtributosSelecionaveis = db_query($sSqlAtributos);

        if (!$rsAtributosSelecionaveis) {
            throw new DBException('Falha ao buscar os atributos do exame.');
        }

        for ($iAtributo = 0; $iAtributo < pg_num_rows($rsAtributosSelecionaveis); $iAtributo++) {
            $oDadosAtributoSelecionavel = db_utils::fieldsMemory($rsAtributosSelecionaveis, $iAtributo);
            $aAtributosSelecionaveis[$oDadosAtributoSelecionavel->la28_i_codigo] = $oDadosAtributoSelecionavel->la28_c_descr;
        }

        /**
         * Percorre os exames da requisição, para montar a estrutura do relatório
         */
        foreach ($oRequisicaoLaboratorial->getRequisicoesDeExames() as $oRequisicao) {
            $oDadosEstrutura->sData = $oRequisicao->getData()->convertTo(DBDate::DATA_PTBR);

            /**
             * Caso não seja do tipo CONFERIDO '60 - Conferido', segue percorrendo o próximo registro
             */
            if (!in_array($oRequisicao->getSituacao(), array(RequisicaoExame::CONFERIDO, RequisicaoExame::ENTREGUE))) {
                continue;
            }

            /**
             * Valida se foi selecionado algum tipo de exame específico
             */
            if (!empty($exame) && $exame != $oRequisicao->getExame()->getCodigo()) {
                continue;
            }


            /**
             * Valida se foi selecionado algum setor específico
             */
            $setor = $oRequisicao->getLaboratorioSetor();
            if (!empty($labsetor) && $labsetor != $setor->getCodigo()) {
                continue;
            }

            /**
             * Valida se foi selecionado algum laboratório específico
             */
            if (!empty($laboratorio) && $laboratorio != $oRequisicao->getLaboratorio()->getCodigo()) {
                continue;
            }

            $oExame          = $oRequisicao->getExame();
            $oResultadoExame = $oRequisicao->getResultado();
            $aAtributos      = $oExame->getAtributos();

            // Se o exame não tiver atributos, pula para o próximo exame
            if (empty($aAtributos)) {
                continue;
            }

            $iCodigoSetor = $oRequisicao->getLaboratorioSetor()->getCodigo();
            if (!array_key_exists($iCodigoSetor, $oDadosEstrutura->aSetor)) {
                $oDadosSetor             = new stdClass();
                $oDadosSetor->iCodigo    = $oRequisicao->getLaboratorioSetor()->getCodigo();
                $oDadosSetor->sDescricao = $oRequisicao->getLaboratorioSetor()->getDescricao();
                $oDadosSetor->aExames    = array();

                $oDadosEstrutura->aSetor[$iCodigoSetor] = $oDadosSetor;
            }

            $oDadosEstrutura->aExames[] = $oRequisicao->getCodigo();

            /**
             * Preenche os dados do solicitante para impressão do cabeçalho
             */
            $dtNascimento = $oRequisicao->getSolicitante()->getDataNascimento()->getDate();

            $oDadosEstrutura->lSalvarArquivo        = $oRequisicao->getSituacao() == RequisicaoExame::CONFERIDO;
            $oDadosEstrutura->oSolicitante->iCodigo = $oRequisicao->getSolicitante()->getCodigo();
            $oDadosEstrutura->oSolicitante->sNome   = $oRequisicao->getSolicitante()->getNome();
            $oDadosEstrutura->oSolicitante->sSexo   = $oRequisicao->getSolicitante()->getSexo();
            $oDadosEstrutura->oSolicitante->iIdade  = getIdadeSolicitante($dtNascimento)->anos;

            $aDadosMaterialColeta            = $oExame->getMaterialColeta();

            $iCodigoExame = $oExame->getCodigo();
            if (!array_key_exists($iCodigoExame, $oDadosEstrutura->aSetor[$iCodigoSetor]->aExames)) {
                $oStdExame                       = new stdClass();
                $oStdExame->sNomeExame           = $oExame->getNome();
                $oStdExame->sObservacaoExame     = $oExame->getObservacao();
                $oStdExame->aMedicamentosExame   = $oRequisicao->getMedicamentos();
                $oStdExame->aDadosMaterialColeta = $aDadosMaterialColeta;
                $oStdExame->sObservacao          = $oRequisicao->getObservacao();
                $oStdExame->aAtributos           = array();

                $responsavelRepository = ResponsavelRepository::getInstance();
                $coletaItem = $oRequisicao->getColetaItem();
                $conferenciaResultado = $oRequisicao->getConferenciaResultado();
                $cgmConferencia = $conferenciaResultado->getUsuarioSistema()->getCGM();
                $responsavelConferenciaModel = $responsavelRepository->getByLaboratorioCgm(
                    $oRequisicao->getLaboratorio(),
                    $cgmConferencia
                );

                $mensagemLiberacao = '';

                if ($responsavelConferenciaModel instanceof Responsavel) {
                    $mensagemLiberacao = "Exame liberado por {$cgmConferencia->getNome()}";

                    if ($responsavelConferenciaModel->getOrgaoClasse() !== '') {
                        $mensagemLiberacao .= ", CRBM {$responsavelConferenciaModel->getOrgaoClasse()}, ";
                    }

                    $mensagemLiberacao .= " em {$conferenciaResultado->getData()->getDate(DBDate::DATA_PTBR)}";
                    $mensagemLiberacao .= " às {$conferenciaResultado->getHora()}.";
                    $mensagemLiberacao .= " Coletado dia {$coletaItem->getData()->getDate(DBDate::DATA_PTBR)}";
                    $mensagemLiberacao .= " às {$coletaItem->getHora()}.";
                    $mensagemLiberacao .= " Impresso em " . date('d/m/Y', db_getsession('DB_datausu')) . ".";
                }

                $oStdExame->mensagemLiberacao = $mensagemLiberacao;


                $oDadosEstrutura->aSetor[$iCodigoSetor]->aExames[$iCodigoExame] = $oStdExame;
            }

            /**
             * Percorre cada atributo, e monta o objeto com as informações necessários do mesmo
             */
            foreach ($aAtributos as $oAtributo) {
                $oResultadoAtributo = $oResultadoExame->getValorDoAtributo($oAtributo);

                $oAtributoDoExame                          = new stdClass();
                $oAtributoDoExame->nome                    = $oAtributo->getNome();
                $oAtributoDoExame->nivel                   = $oAtributo->getNivel();
                $oAtributoDoExame->valorabsoluto           = '';
                $oAtributoDoExame->valorpercentual         = '';
                $oAtributoDoExame->unidade                 = '';
                $oAtributoDoExame->referencia              = '';
                $oAtributoDoExame->tipo                    = $oAtributo->getTipo();
                $oAtributoDoExame->tiporeferencia          = $oAtributo->getTipoReferencia();
                $oAtributoDoExame->iSetor                  = $oRequisicao->getLaboratorioSetor()->getCodigo();
                $oAtributoDoExame->valorabsolutoanterior   = '';
                $oAtributoDoExame->valorpercentualanterior = '';
                $oAtributoDoExame->referenciaanterior      = '';
                $oAtributoDoExame->titulacaoanterior       = '';
                $oAtributoDoExame->dataResultadoAnterior   = $oResultadoExame->getDataResultadoAnterior();

                if ($oAtributo->getUnidadeMedida() != "") {
                    $oAtributoDoExame->unidade = $oAtributo->getUnidadeMedida()->getNome();
                }

                if (!empty($oResultadoAtributo)) {
                    $oRetorno = organizaValoresAtributo(
                        $oResultadoAtributo,
                        $oAtributoDoExame->unidade,
                        $oAtributo->getTipoReferencia(),
                        $aAtributosSelecionaveis
                    );

                    $oAtributoDoExame->valorabsoluto   = $oRetorno->valorabsoluto;
                    $oAtributoDoExame->valorpercentual = $oRetorno->valorpercentual;
                    $oAtributoDoExame->referencia      = $oRetorno->referencia;
                    $oAtributoDoExame->titulacao       = $oRetorno->titulacao;
                }

                $oResultadoAnterior = $oResultadoExame->getValorDoAtributoResultadoAnterior($oAtributo);

                if (!empty($oResultadoAnterior)) {
                    $oRetornoAnterior = organizaValoresAtributo(
                        $oResultadoAnterior,
                        $oAtributoDoExame->unidade,
                        $oAtributo->getTipoReferencia(),
                        $aAtributosSelecionaveis
                    );

                    $oAtributoDoExame->valorabsolutoanterior   = $oRetornoAnterior->valorabsoluto;
                    $oAtributoDoExame->valorpercentualanterior = $oRetornoAnterior->valorpercentual;
                    $oAtributoDoExame->referenciaanterior      = $oRetornoAnterior->referencia;
                    $oAtributoDoExame->titulacaoanterior       = $oRetornoAnterior->titulacao;
                }

              /**
               * Cria um objeto com os dados do setor e um array vazio de atributos a ser incrementado após incrementar o array
               * dos atributos
               */
                $oDadosEstrutura->aSetor[$iCodigoSetor]->aExames[$iCodigoExame]->aAtributos[] = $oAtributoDoExame;

                $lExisteAtributos = true;
            }
        }

        if (!empty($oDadosEstrutura->aExames) && !empty($oDadosEstrutura->aSetor)) {
            $dados[] = $oDadosEstrutura;
        }
    }

    if (empty($dados)) {
        throw new Exception("Nenhum registro encontrado para os filtros selecionados.");
    }

    if ($modeloImpressao == 2) {
        $impressaoMatricialLote->gerarArquivo($dados);
        $retorno->mensagem = "Dados enviados para a impressora.";
        $retorno->dados = $impressaoMatricialLote->getConteudo();
        $retorno->utilizarAutenticadoraNova = $impressaoMatricialLote->isUtilizarAutenticadoraNova();
    } elseif ($modeloImpressao == 1) {
        include('lab4_emissaoresultadolotepdf.php');
    }
} catch (Exception $oErro) {
    $retorno->erro = true;
    $retorno->mensagem = $oErro->getMessage();
    if ($modeloImpressao == 1) {
        db_redireciona("db_erros.php?fechar=true&db_erro={$retorno->mensagem}");
    }
}

if ($modeloImpressao == 2) {
    echo JSON::create()->stringify($retorno);
}

/**
 * Calcula a idade que o solicitante tem com base na data do sistema
 * @param  date    $dtNascimento
 * @return stdClass
 */
function getIdadeSolicitante($dtNascimento)
{

    $oIdade        = new stdClass();
    $oIdade->anos  = 0;
    $oIdade->meses = 0;
    $oIdade->dias  = 0;

    if ($dtNascimento == "") {
        return '';
    }

    $dtSistema       = date("Y-m-d", db_getsession("DB_datausu"));
    $sSqlAnoMesDia   = "SELECT fc_idade_anomesdia('{$dtNascimento}', '{$dtSistema}', false) as dias;";
    $rsAnoMesDia     = db_query($sSqlAnoMesDia);
    if ($rsAnoMesDia && pg_num_rows($rsAnoMesDia) > 0) {
        $aDadosIdade   = explode(',', db_utils::fieldsMemory($rsAnoMesDia, 0)->dias);
        $oIdade->anos  = trim($aDadosIdade[0]);
        $oIdade->meses = trim($aDadosIdade[1]);
        $oIdade->dias  = trim($aDadosIdade[2]);
    }

    return $oIdade;
}


function organizaValoresAtributo(ResultadoExameAtributo $oResultadoAtributo, $unidade, $iTipoReferencia, $aAtributosSelecionaveis)
{

    $oAtributoDoExame = new stdClass();
    $oAtributoDoExame->valorabsoluto   = $oResultadoAtributo->getValorAbsoluto();
    $oAtributoDoExame->valorpercentual = $oResultadoAtributo->getValorPercentual();
    $oAtributoDoExame->titulacao       = $oResultadoAtributo->getTitulacao();
    $oAtributoDoExame->referencia      = '';
    $oAtributoDoExame->valorreferencia = '';

    switch ($iTipoReferencia) {
        case AtributoExame::REFERENCIA_NUMERICA:
            $oReferenciaAtributo  = $oResultadoAtributo->getFaixaUtilizada();

            if (!empty($oReferenciaAtributo) && $oReferenciaAtributo->getCodigo() == '') {
                $oReferenciaAtributo = $oAtributo->getValoresDeReferenciaParaExame($oRequisicao);
            }

            $iCasasDecimaisApresentacao = null;

            if ($oReferenciaAtributo instanceof AtributoValorReferenciaNumerico) {
                $iCasasDecimaisApresentacao = $oReferenciaAtributo->getCasasDecimaisApresentacao();
            }

            $oAtributoDoExame->valorabsoluto = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oAtributoDoExame->valorabsoluto);

            if ($oReferenciaAtributo != '') {
                $iValorMinimo = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oReferenciaAtributo->getValorMinimo());
                $iValorMaximo = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oReferenciaAtributo->getValorMaximo());

                $sStringReferencia                 = "({$iValorMinimo} - {$iValorMaximo}) {$unidade}";
                $oAtributoDoExame->referencia      = $sStringReferencia;
                $oAtributoDoExame->valorreferencia = "({$iValorMinimo} - {$iValorMaximo})";
            }
            break;

        case AtributoExame::REFERENCIA_SELECIONAVEL:
            $oAtributoDoExame->referencia      = $unidade;
            $oAtributoDoExame->valorreferencia = null;
            if (isset($aAtributosSelecionaveis[$oResultadoAtributo->getValorAbsoluto()])) {
                $oAtributoDoExame->valorabsoluto = $aAtributosSelecionaveis[$oResultadoAtributo->getValorAbsoluto()];
            }
            break;

        case AtributoExame::REFERENCIA_FIXA:
            $oAtributoDoExame->referencia      = $unidade;
            $oAtributoDoExame->valorreferencia = null;
            $oAtributoDoExame->valorabsoluto   = $oResultadoAtributo->getValorAbsoluto();
            break;
    }

    return $oAtributoDoExame;
}
