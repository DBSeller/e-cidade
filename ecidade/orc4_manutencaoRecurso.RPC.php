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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_app::import("configuracao.DBEstrutura");
db_app::import("orcamento.TribunalEstrutura");
db_app::import("orcamento.Recurso");

$oPost = db_utils::postMemory($_POST);
$oGet = db_utils::postMemory($_GET);

$oJson = new services_json();
$oParam = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $oPost->json)));
$iAnoUsu = db_getsession('DB_anousu');

$oRetorno = new stdClass;
$oRetorno->status = 1;
$oRetorno->message = "";

if (isset($oParam->finalidaderecurso)) {
    $sFinalidadeRecurso = utf8_decode($oParam->finalidaderecurso);
}

switch ($oParam->exec) {
    case "getEspecificacaoLOA":

        $iEspecificacao = Recurso::getFonteRecusoByCodigo($oParam->iRecurso);
        if ( $iEspecificacao == null ) {
            throw new \Exception("Especificacao LOA nao encontrada.");
        }
        $oRetorno->sEspecificacao = $iEspecificacao;
    break;

    case "getComplementoFonteRecurso":

        $aDadosComplemento = array();
        $oDaoComplementoFonteRecurso = db_utils::getDao('complementofonterecurso');
        $sSql = $oDaoComplementoFonteRecurso->sql_query(null, " * ", "o200_sequencial", " o200_sequencial > 0");
        $rsComplemento = $oDaoComplementoFonteRecurso->sql_record($sSql);

        for($iComplemento = 0; $iComplemento < $oDaoComplementoFonteRecurso->numrows; $iComplemento++){

            $oDados = db_utils::fieldsMemory($rsComplemento, $iComplemento);
            $oDadosComplemento = new stdClass();
            $oDadosComplemento->o200_sequencial = $oDados->o200_sequencial;
            $oDadosComplemento->o200_descricao = urlencode($oDados->o200_descricao);
            $oDadosComplemento->o200_msc = urlencode($oDados->o200_msc == 't' ? 'Sim' : 'Não');
            $aDadosComplemento[] = $oDadosComplemento;
        }

        $oRetorno->aDados = $aDadosComplemento;

    break;

    case "incluirComplementoFonteRecurso":

        $oDaoComplementoFonteRecurso = db_utils::getDao('complementofonterecurso');
        $o200_sequencial = $oParam->o200_sequencial;
        $o200_descricao  = addslashes(utf8_decode( urlDecode($oParam->o200_descricao) ));
        $o200_msc        = $oParam->o200_msc;

        db_inicio_transacao();
        $oDaoComplementoFonteRecurso->o200_sequencial = $o200_sequencial;
        $oDaoComplementoFonteRecurso->o200_descricao = $o200_descricao;
        $oDaoComplementoFonteRecurso->o200_msc = $o200_msc;
        $oDaoComplementoFonteRecurso->incluir($o200_sequencial);
        if ($oDaoComplementoFonteRecurso->erro_status == "0") {
            throw new \Exception("Erro ao Incluir Registro: " . $oDaoComplementoFonteRecurso->erro_msg);
        }
        $oRetorno->message = "Resgistro Incluido com Sucesso.";
        db_fim_transacao(false);

    break;


    case "excluirComplementoFonteRecurso":

        db_inicio_transacao();

        $sLista = implode(", ", $oParam->aDados);
        $oDaoComplementoFonteRecurso = db_utils::getDao('complementofonterecurso');
        $oDaoComplementoFonteRecurso->excluir(null, "o200_sequencial in ($sLista)");
        if ($oDaoComplementoFonteRecurso->erro_status == "0") {
            throw new \Exception("Erro ao Excluir Registros: " . $oDaoComplementoFonteRecurso->erro_msg);
        }

        db_fim_transacao(false);
        $oRetorno->message = "Resgistro Excluido com Sucesso.";
    break;

    case "getDadosMascara":
        $oDaoOrcParametro = db_utils::getDao("orcparametro");
        $sSqlOrcParametro = $oDaoOrcParametro->sql_query_file($iAnoUsu, "o50_estruturarecurso", null, null);
        $rsSqlOrcParametro = $oDaoOrcParametro->sql_record($sSqlOrcParametro);
        if ($oDaoOrcParametro->numrows > 0) {
            $iCodigoEstrutura = db_utils::fieldsMemory($rsSqlOrcParametro, 0)->o50_estruturarecurso;

            $oEstrutura = new DBEstrutura((int)$iCodigoEstrutura);
            $oRetorno->mascara = $oEstrutura->getMascara();
            $oRetorno->codigo = $oEstrutura->getCodigo();
            $oRetorno->niveis = count($oEstrutura->getNiveis());
        } else {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode("Nenhuma estrutura cadastrada! Verifique.");
        }
        break;
    case "getDadosRecurso":
        $oRecurso = new Recurso((int)$oParam->codigorecurso);
        $oRetorno->codigorecurso = $oRecurso->getCodigo();
        $oRetorno->descricaorecurso = urlencode($oRecurso->getDescricao());
        $oRetorno->codigotribunalrecurso = urlencode($oRecurso->getEstruturaValor()->getEstrutural());
        $oRetorno->finalidaderecurso = urlencode($oRecurso->getFinalidadeRecurso());
        $oRetorno->tipo = $oRecurso->getEstruturaValor()->getTipoConta();
        $oRetorno->tiporecurso = $oRecurso->getTipoRecurso();
        $oRetorno->datalimiterecurso = $oRecurso->getDataLimiteRecurso();
        $oRetorno->codigosiconfi = $oRecurso->getCodigoSiconfi();

        $oRetorno->classificacao = new stdClass();
        $oRetorno->classificacao->identificador = $oRecurso->getIdentificadorUsoLOA();
        $oRetorno->classificacao->tipo = $oRecurso->getTipoDetalhamentoLOA();
        $oRetorno->classificacao->grupo = $oRecurso->getGrupoLOA();
        $oRetorno->classificacao->especificacao = $oRecurso->getEspecificacaoLOA();
        if (!FONTE_RECURSO_UNIAO) {
            $oRetorno->classificacao->especificacao = $oRecurso->getEspecificacaoLOA();
        }
        $oRetorno->classificacao->complemento = $oRecurso->getComplemento();

        break;
    case "getRecursos":
        $sCamposRecurso = " distinct o15_recurso as codigo, o15_descr as descricao";
        $sOrderBy = "o15_recurso ";
        $sWhere = " o15_codigo <> 0 ";

        $oDaoRecursos = new cl_orctiporec();
        $sSqlRecursos = $oDaoRecursos->sql_query(null, $sCamposRecurso, $sOrderBy, $sWhere);
        $rsRecursos = $oDaoRecursos->sql_record($sSqlRecursos);

        if (!$rsRecursos || $oDaoRecursos->numrows == 0) {
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->mensagem = "Recursos não encontrados.";
            break;
        }

        $aRecursos = array();
        for ($iRecurso = 0; $iRecurso < $oDaoRecursos->numrows; $iRecurso++) {
            $oRecurso = db_utils::fieldsMemory($rsRecursos, $iRecurso);
            $oRecurso->descricao = urlencode($oRecurso->descricao);
            $aRecursos[] = $oRecurso;
        }

        $oRetorno->erro = false;
        $oRetorno->aRecursos = $aRecursos;
        break;
    case "salvarRecurso":
        try {
            db_inicio_transacao();

            /**
             * verificamos se existe especificacoes para incluir
             */
            if (!empty($oParam->codigo_especificacao) && empty($oParam->classificacao->especificacao)) {

                $codigoEspecificacao = trim(pg_escape_string($oParam->codigo_especificacao));
                $descricaoEspecificacao = trim(pg_escape_string($oParam->descricao_especificacao));
                $daoRecursoEspecificacao = new cl_recursoespecificacao();
                $where = "o205_codigo = '{$codigoEspecificacao}'";
                $sqlVerificacaoEspecificacao = $daoRecursoEspecificacao->sql_query_file(null, "o205_sequencial", null, $where);
                $rsVerificacao = db_query($sqlVerificacaoEspecificacao);
                if (!$rsVerificacao) {
                    throw new \Exception("Erro ao pesquisar dados da especificação do recurso.");
                }
                if (pg_num_rows($rsVerificacao) > 0) {
                    $oParam->classificacao->especificacao = db_utils::fieldsMemory($rsVerificacao, 0)->o205_sequencial;
                } else {

                    $estado = '';
                    if (FONTE_RECURSO_UNIAO) {
                        $instituicao = InstituicaoRepository::getInstituicaoSessao();
                        $estado = $instituicao->getUf();
                    }
                    $daoRecursoEspecificacao->o205_codigo = $codigoEspecificacao;
                    $daoRecursoEspecificacao->o205_descricao = $descricaoEspecificacao;
                    $daoRecursoEspecificacao->o205_estado = $estado;
                    $daoRecursoEspecificacao->incluir();
                    if ($daoRecursoEspecificacao->erro_status == 0) {
                        throw new \Exception("Erro ao salvar dados da especificação do recurso.");
                    }
                    $oParam->classificacao->especificacao = $daoRecursoEspecificacao->o205_codigo;
                    $oRetorno->codigo_especificacao = $daoRecursoEspecificacao->o205_sequencial;
                    $oRetorno->descricao_especificacao = $daoRecursoEspecificacao->o205_codigo. " - ".$daoRecursoEspecificacao->o205_descricao;
                }
            }
            $oDaoOrcParametro = new cl_orcparametro();
            $sSqlOrcParametro = $oDaoOrcParametro->sql_query_file($iAnoUsu, "o50_estruturarecurso", null, null);
            $rsSqlOrcParametro = $oDaoOrcParametro->sql_record($sSqlOrcParametro);

            $iCodigoEstrutura = '';
            if ($oDaoOrcParametro->numrows > 0) {
                $iCodigoEstrutura = db_utils::fieldsMemory($rsSqlOrcParametro, 0)->o50_estruturarecurso;
            }

            if ($oParam->modo == 1) {
                $oRecurso = new Recurso();
            } else {
                $oRecurso = new Recurso((int)$oParam->codigorecurso);
            }

            $oDaoEstruturaValor = db_utils::getDao("db_estruturavalor");
            $sWhere = "     db121_estrutural = '" . db_stdClass::normalizeStringJson($oParam->codigotribunalrecurso) . "'";
            $sWhere .= " and db121_db_estrutura = '{$iCodigoEstrutura}'";

            $sSqlEstruturaValor = $oDaoEstruturaValor->sql_query_file(null, "*", null, $sWhere);
            $rsEstruturaValor = $oDaoEstruturaValor->sql_record($sSqlEstruturaValor);

            $oTribunalEstrutura = null;
            if ($oDaoEstruturaValor->numrows > 0) {
                $iSequencialEstruturaValor = db_utils::fieldsMemory($rsEstruturaValor, 0)->db121_sequencial;
                $oTribunalEstrutura = new TribunalEstrutura($iSequencialEstruturaValor);
                $oTribunalEstrutura->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->descricaorecurso));
            } else {
                $oTribunalEstrutura = new TribunalEstrutura();

                $oTribunalEstrutura->setEstrutura((int)$iCodigoEstrutura);
                $oTribunalEstrutura->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->descricaorecurso));
                $oTribunalEstrutura->setTipoConta($oParam->tipo);
                $oTribunalEstrutura->setEstrutural(db_stdClass::normalizeStringJsonEscapeString($oParam->codigotribunalrecurso));
            }
            $oTribunalEstrutura->salvar();

            $oRecurso->setCodigoRecurso((int)$oParam->codigorecurso);
            $oRecurso->setTipoRecurso($oParam->tiporecurso);
            $oRecurso->setDataLimiteRecurso(implode("-", array_reverse(explode("/", $oParam->datalimiterecurso))));
            $oRecurso->setFinalidadeRecurso(db_stdClass::normalizeStringJsonEscapeString($oParam->finalidaderecurso));
            $oRecurso->setEstruturaValor($oTribunalEstrutura);
            $oRecurso->setCodigoSiconfi($oParam->codigosiconfi);

            $oRecurso->setIdentificadorUsoLOA($oParam->classificacao->identificador);
            $oRecurso->setTipoDetalhamentoLOA($oParam->classificacao->tipoDetalhamento);
            $oRecurso->setGrupoLOA($oParam->classificacao->grupo);
            $oRecurso->setEspecificacaoLOA($oParam->classificacao->especificacao);
            $oRecurso->setComplemento($oParam->classificacao->complemento);


            $oRecurso->salvar();

            $oRetorno->message = urlencode("Recurso {$oParam->codigorecurso} salvo com sucesso.");
            db_fim_transacao(false);
        } catch (Exception $eErro) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;
    case "removerRecurso":
        try {
            db_inicio_transacao();
            $oRecurso = new Recurso((int)$oParam->codigorecurso);
            $oRecurso->remover();
            $oRetorno->message = urlencode("Recurso {$oParam->codigorecurso} excluído com sucesso.");
            db_fim_transacao(false);
        } catch (Exception $eErro) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;
    case "getRecursosClassificacao":

        try {

            $sWhere = " true ";
            if ($oParam->classificacao->identificador !== '') {
                $sWhere .= "and o15_loaidentificadoruso  = {$oParam->classificacao->identificador} ";
            }
            if ($oParam->classificacao->tipoDetalhamento !== '') {
                $sWhere .= "and o15_loatipo  = {$oParam->classificacao->tipoDetalhamento} ";
            }
            if ($oParam->classificacao->grupo !== '') {
                $sWhere .= "and o15_loagrupo = {$oParam->classificacao->grupo} ";
            }
            if ($oParam->classificacao->especificacao !== '') {
                $sWhere .= "and o15_loaespecificacao = '{$oParam->classificacao->especificacao}'";
            }

            $oDaoOrcTipoRec = new cl_orctiporec();
            $campos = "o15_codigo as codigo, o15_loaespecificacao || ' - ' || o15_descr as descricao";
            $sSqlOrcTipoRec = $oDaoOrcTipoRec->sql_query_file(null, $campos, "o15_codigo, o15_descr", $sWhere);

            if ($iAnoUsu >= 2022 ) {

                $where = "exercicio = {$iAnoUsu}";
                if ($oParam->classificacao->especificacao !== '') {
                    $where .= "and gestao = '{$oParam->classificacao->especificacao}'";
                }

                $oDao = new cl_fonterecurso;
                $campos  = "o15_codigo as codigo, ";
                $campos .= "gestao  || ' - ' || o15_descr as descricao";

                $sSqlOrcTipoRec = $oDao->sql_query(null, $campos , "o15_codigo, o15_descr", $where);

            }

            $rsSqlOrcTipoRec = $oDaoOrcTipoRec->sql_record($sSqlOrcTipoRec);
            $oRetorno->recursos = array();
            if ($oDaoOrcTipoRec->numrows > 0) {
                $recursos = db_utils::getCollectionByRecord($rsSqlOrcTipoRec, false, false, true);
                $oRetorno->recursos = $recursos;
            }


        } catch (Exception $erro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;
}
echo $oJson->encode($oRetorno);
