<?php
/**
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));


$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->erro = false;
$oRetorno->sMessage = '';

CONST MENSAGEM = "recursoshumanos.rh.rec4_agendaassentamentoRPC.";

try {

    db_inicio_transacao();

    switch ($oParam->exec) {

        case "carregarSelecao":

            if (empty($oParam->iTipoAssentamento)) {
                throw new BusinessException(_M(MENSAGEM . "erro_buscar_selecoes"));
            }

            $aSelecao = array();

            $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oParam->iTipoAssentamento);
            $oAgendaAssentamento = AgendaAssentamentoRepository::getInstanciaPorTipoAssentamento($oTipoAssentamento);
            $oAgendaAssentamento = AgendaAssentamentoRepository::getListaSelecaoParaTipo($oAgendaAssentamento);

            foreach ($oAgendaAssentamento->getListaSelecao() as $oSelecao) {

                $oStdSelecao = new stdClass;
                $oStdSelecao->iCodigo = $oSelecao->getCodigo();
                $oStdSelecao->sDescricao = $oSelecao->getDescricao();

                $aSelecao[] = $oStdSelecao;
            }

            $oRetorno->aSelecao = $aSelecao;

            break;

        case "buscarServidoresAssentamento":

            if (empty($oParam->aMatriculas)) {
                $oParam->aMatriculas = array();
            }

            $aMatriculas = array_map(function ($value) {
                return $value->sCodigo;
            }, $oParam->aMatriculas);

            $oParam->periodo->dataInicio = trim(str_replace('/', '', $oParam->periodo->dataInicio));
            $oParam->periodo->dataFim = trim(str_replace('/', '', $oParam->periodo->dataFim));


            $oDataInicio = '';
            $oDataFim = '';

            if (!empty($oParam->periodo->dataInicio) && !empty($oParam->periodo->dataFim)) {

                $oDataInicio = new DBDate($oParam->periodo->dataInicio);
                $oDataFim = new DBDate($oParam->periodo->dataFim);
            }

            $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oParam->iTipoAssentamento);
            $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
            $oSelecao = new Selecao($oParam->iCodigoSelecao);
            $oAgendaAssentamento = AgendaAssentamentoRepository::getInstanciaPorTipoSelecaoInstituicao($oTipoAssentamento, $oSelecao, $oInstituicao);

            if (empty($aMatriculas)) {
                $aServidores = ServidorRepository::getServidoresBySelecao(DBPessoal::getAnoFolha(),
                    DBPessoal::getMesFolha(),
                    $oParam->iCodigoSelecao, null);
            } else {

                $aServidores = ServidorRepository::getServidoresByMatriculas(
                    DBPessoal::getAnoFolha(),
                    DBPessoal::getMesFolha(),
                    $aMatriculas,
                    null
                );

            }

            foreach ($aServidores as $oServidorSelecao) {
                $aMatriculasServidoresSelecao[] = $oServidorSelecao;
            }

            $aServidores = $aMatriculasServidoresSelecao;
            $aServidoresComDireito = array();

            foreach ($aServidores as $oServidor) {

                $oFormula = new DBFormulaServidorAgendaAssentamentos($oServidor);

                $sFormulaDataInicio = "([" . $oAgendaAssentamento->getNomeFormulaInicio() . "])::date as data_inicio";
                $sFormulaDataFim = "null as data_direito";

                if ($oAgendaAssentamento->getNomeFormulaFim() != '') {
                    $sFormulaDataFim = "([" . $oAgendaAssentamento->getNomeFormulaFim() . "])::date as data_direito";
                }

                $sFormulaProrrogaFim = "null as data_prevista";
                if ($oAgendaAssentamento->getNomeFormulaProrrogaFim() != '') {
                    $sFormulaProrrogaFim = "[" . $oAgendaAssentamento->getNomeFormulaProrrogaFim() . "] as data_prevista";
                }

                $sSqlCondicaoServidor = $oFormula->parse("SELECT [" . $oAgendaAssentamento->getNomeFormulaCondicao() . "] as condicao ,  {$sFormulaDataInicio} ,  {$sFormulaDataFim} , {$sFormulaProrrogaFim}");


                $rsCondicaoServidor = db_query($sSqlCondicaoServidor);

                if (!$rsCondicaoServidor) {
                    throw new BusinessException(_M(MENSAGEM . 'erro_executar_formula_condicao'));
                }

                if (pg_num_rows($rsCondicaoServidor) > 0) {

                    $lDireitoServidor[$oServidor->getMatricula()] = false;

                    $oDadosCondicao = db_utils::fieldsMemory($rsCondicaoServidor, 0);
                    $sDataVerificar = $oDadosCondicao->data_direito;

                    if (empty($oDadosCondicao->data_direito)) {
                        $sDataVerificar = $oDadosCondicao->data_inicio;
                    }

                    if (!empty($oDadosCondicao->data_prevista)) {
                        $sDataVerificar = $oDadosCondicao->data_prevista;
                    }

                    if (empty($sDataVerificar)) {
                        continue;
                    }

                    $oDataVerificar = new DBDate($sDataVerificar);

                    if (!empty($oDataInicio) && !empty($oDataFim) & !DBDate::dataEstaNoIntervalo($oDataVerificar, $oDataInicio, $oDataFim)) {
                        continue;
                    }

                    if ((bool)$oDadosCondicao->condicao) {

                        $lDireitoServidor[$oServidor->getMatricula()] = true;

                        $oStdServidorComDireito = new stdClass;
                        $oStdServidorComDireito->iMatricula = $oServidor->getMatricula();
                        $oStdServidorComDireito->sNome = $oServidor->getCgm()->getNome();

                        $aServidoresComDireito[] = $oStdServidorComDireito;
                    }
                }
            }

            $oRetorno->aServidores = $aServidoresComDireito;

            break;
        case "processarAssentamentos":

            $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oParam->iTipoAssentamento);


            if (count($oParam->aServidores) < 1) {
                throw new BusinessException(_M(MENSAGEM . "nenhum_assentamento_processar"));
            }

            $oAssetamento = new Assentamento();

            $stdAgendaAssentamento = $oAssetamento->buscaAgendaAssetamento($oParam->iTipoAssentamento, $oParam->iSelecao);

            $sFormulaInicio = $stdAgendaAssentamento->db148_nome_inicio;// 'INICIO';
            $sFormulaFinal = !empty($stdAgendaAssentamento->db148_nome_fim) ? $stdAgendaAssentamento->db148_nome_fim : null; // 'FIM';
            $sFormulaFaltasPeriodo = !empty($stdAgendaAssentamento->db148_nome_faltasperiodo) ? '[' . $stdAgendaAssentamento->db148_nome_faltasperiodo . ']' : 0; // 'FALTAS_PERIODO';

            if ($oParam->iTipoPortaria == '1' || $oParam->iTipoPortaria == '2') {
                $oPortaria = new Portaria();
                $oPortaria->setUsuario(db_getsession('DB_id_usuario'));
                $oPortaria->setPortariatipo(
                    $oPortaria->buscaTipoPortariaPorTipoAssetamento($oParam->iTipoAssentamento)
                );

                $oPortaria->setAnousu(date('Y'));
                $oPortaria->setDtlanc(date("Y-m-d", db_getsession("DB_datausu")));
                $oPortaria->setDtinicio(date("Y-m-d", db_getsession("DB_datausu")));
                $oPortaria->setDtportaria(date("Y-m-d", db_getsession("DB_datausu")));

                $isAutomatico = $oPortaria->isAutomatico();

                if (!$isAutomatico) {
                    throw new BusinessException(_M(MENSAGEM . "erro_gerar_portaria_config_auto"));
                }

            }

            $clportariaassenta = new cl_portariaassenta;

            $iSequencialPortaria = 0;
            $iNroPortaria = null;

            foreach ($oParam->aServidores as $key => $iMatricula) {


                if ($oParam->iTipoPortaria == 2 && $key == 0) {
                    $iNroPortaria = $oPortaria->gerarNumeroPortaria();
                    $oPortaria->persist();
                    $iSequencialPortaria = $oPortaria->getSequencial();

                } elseif ($oParam->iTipoPortaria == 1) {

                    $iNroPortaria = $oPortaria->gerarNumeroPortaria();
                    $oPortaria->setSequencial(null);
                    $oPortaria->persist();
                    $iSequencialPortaria = $oPortaria->getSequencial();
                }

                $oServidor = ServidorRepository::getInstanciaByCodigo($iMatricula, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), db_getsession('DB_instit'));
                $oFormula = new DBFormulaServidorAgendaAssentamentos($oServidor);

                $sSqlInformacoesAssentamentos = $oFormula->parse("SELECT [$sFormulaInicio] as inicio");

                if (!empty($sFormulaFinal)) {

                    $sSqlInformacoesAssentamentos = $oFormula->parse("SELECT [$sFormulaInicio] as inicio, [$sFormulaFinal] as final");

                    if (!empty($sFormulaFaltasPeriodo)) {
                        $sSqlInformacoesAssentamentos = $oFormula->parse("SELECT [$sFormulaInicio] as inicio, [$sFormulaFinal] as final, {$sFormulaFaltasPeriodo} as faltas");
                    }
                }

                $rsInformacoesAssentamentos = db_query($sSqlInformacoesAssentamentos);

                if (!$rsInformacoesAssentamentos) {
                    throw new BusinessException(_M(MENSAGEM . "erro_gerar_informacoes_assentamentos"));
                }


                if (pg_num_rows($rsInformacoesAssentamentos) > 0) {
                    $stdInformacoesAssentamentos = db_utils::fieldsMemory($rsInformacoesAssentamentos, 0);


                    $oDataConcessao = new DBDate(date('Y-m-d', strtotime($stdInformacoesAssentamentos->inicio)));
                    $oDataTermino = null;
                    $iQuantidadeDias = 0;

                    if (isset($stdInformacoesAssentamentos->final) && !empty($stdInformacoesAssentamentos->final)) {

                        $iFaltas = 0;
                        if (isset($stdInformacoesAssentamentos->faltas)) {
                            $iFaltas = (int)$stdInformacoesAssentamentos->faltas;
                        }
                        $oDataTermino = new DBDate(date('Y-m-d', strtotime($stdInformacoesAssentamentos->final)));
                        $oDataTermino = $oDataTermino->adiantarPeriodo($iFaltas, 'd');
                        $iQuantidadeDias = DBDate::getIntervaloEntreDatas($oDataConcessao, $oDataTermino);
                        $iQuantidadeDias = $iQuantidadeDias->format('%a') + 1;
                    }

                    $oDataAtual = new DBDate(date('Y-m-d'));

                    $oDaoAssentamento = new cl_assenta;

                    $oDaoAssentamento->h16_regist = $oServidor->getMatricula();
                    $oDaoAssentamento->h16_assent = $oParam->iTipoAssentamento;
                    $oDaoAssentamento->h16_dtconc = $oDataConcessao->getDate();
                    $oDaoAssentamento->h16_dtterm = $oDataTermino instanceof DBDate ? $oDataTermino->getDate() : '';
                    $oDaoAssentamento->h16_dtlanc = $oDataAtual->getDate();
                    $oDaoAssentamento->h16_quant = $iQuantidadeDias;
                    $oDaoAssentamento->h16_perc = '0';
                    $oDaoAssentamento->h16_login = db_getsession("DB_id_usuario");
                    $oDaoAssentamento->h16_anoato = date('Y');
                    $GLOBALS["HTTP_POST_VARS"]["h16_conver"] = 'f';
                    $oDaoAssentamento->h16_conver = 'f';
                    $oDaoAssentamento->h16_nrport = $iNroPortaria;

                    $oDaoAssentamento->incluir(null);

                    if ($oDaoAssentamento->erro_status == '0') {
                        throw new BusinessException($oDaoAssentamento->erro_msg);
                    }

                    $oDaoAssentamentoFuncional = new cl_assentamentofuncional;
                    $oDaoAssentamentoFuncional->rh193_assentamento_funcional = $oDaoAssentamento->h16_codigo;
                    $oDaoAssentamentoFuncional->rh193_assentamento_efetividade = 'null';

                    $oDaoAssentamentoFuncional->incluir(null);

                    if ($oDaoAssentamentoFuncional->erro_status == '0') {
                        throw new BusinessException($oDaoAssentamentoFuncional->erro_msg);
                    }

                    $aAttrs = $oTipoAssentamento->getAtributosDinamicos();

                    if (!empty($aAttrs)) {

                        $sSqlNovoGrupo = "insert into db_cadattdinamicovalorgrupo (select nextval('db_cadattdinamicovalorgrupo_db120_sequencial_seq')) returning * ; ";
                        $rsNovoGrupo = db_query($sSqlNovoGrupo);
                        $iNovoGrupo = db_utils::fieldsMemory($rsNovoGrupo, 0)->db120_sequencial;

                        foreach ($aAttrs as $aAttr) {

                            $objDBAtt = new DBAttDinamicoAtributo($aAttr->codigoAtributo);
                            $codFormula = $objDBAtt->getFormula();

                            if (empty($codFormula)) {
                                continue;
                            }

                            $sFormulaAttr = $objDBAtt->getNomeFormula();

                            $oDBFormulaMat = new  DBFormulaMatricula($oServidor);
                            $oDBFormulaMat->adicionar('H16_REGIST', $oServidor->getMatricula());

                            if (empty($sFormulaAttr)) {
                                continue;
                            }

                            $sSqlForm = $oDBFormulaMat->parse("SELECT [$sFormulaAttr] as valor");

                            $rsSqlForm = db_query($sSqlForm);

                            if (!$rsSqlForm) {
                                throw new BusinessException("Erro ao executar formula");
                            }

                            $oForm = pg_fetch_object($rsSqlForm);

                            $oDaocadattdinamicoatributosvalor = new  cl_db_cadattdinamicoatributosvalor();

                            $oDaocadattdinamicoatributosvalor->db110_valor = !empty($oForm->valor) ? $oForm->valor : ' ';
                            $oDaocadattdinamicoatributosvalor->db110_db_cadattdinamicoatributos = $aAttr->codigoAtributo;
                            $oDaocadattdinamicoatributosvalor->db110_cadattdinamicovalorgrupo = $iNovoGrupo;
                            $oDaocadattdinamicoatributosvalor->incluir(null);

                            if ($oDaocadattdinamicoatributosvalor->erro_status == "0") {
                                throw new BusinessException("Erro ao salvar atributosvalor.");
                            }
                        }
                    }

                    // caso  seja portaria
                    if (!empty($iNroPortaria)) {

                        // inclui portaria
                        $clportariaassenta->h33_assenta = $oDaoAssentamento->h16_codigo;
                        $clportariaassenta->h33_portaria = $iSequencialPortaria;
                        $clportariaassenta->incluir(null);

                        if ($clportariaassenta->erro_status == "0") {
                            throw  new Exception("Erro ao vincular assentamento a portaria.");
                        }

                        $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
                        $oDaoAssentaAttr->h80_assenta = $oDaoAssentamento->h16_codigo;
                        $oDaoAssentaAttr->h80_db_cadattdinamicovalorgrupo = $iNovoGrupo;
                        $oDaoAssentaAttr->incluir($oDaoAssentamento->h16_codigo, $iNovoGrupo);


                        if ($oDaoAssentaAttr->erro_status == "0") {

                            throw  new Exception("Erro cadastrar o atributo do assentamento.");
                        }

                        $oDaoAssentaalteracadastroservidor = new  cl_assentaalteracadastroservidor();

                        $oDaoAssentaalteracadastroservidor->h15_assent = $oDaoAssentamento->h16_codigo;
                        $oDaoAssentaalteracadastroservidor->h15_regist = $oServidor->getMatricula();
                        $oDaoAssentaalteracadastroservidor->incluir(null);

                        if ($oDaoAssentaalteracadastroservidor->erro_status == "0") {
                            throw  new Exception("Erro  incluir ao assetamentocadastro servidor.");
                        }

                    }

                }
            }

            $oRetorno->sMessage = urlencode(_M(MENSAGEM . "sucesso_processar"));

            break;
    }

    db_fim_transacao(false);


} catch (Exception $eErro) {


    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->erro = true;
    $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo JSON::stringify($oRetorno);
