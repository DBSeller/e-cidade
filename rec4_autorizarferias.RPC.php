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
 *  detalhes
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
$oRetorno->sMessage = '';
$oRetorno->erro = false;

const MENSAGENS = 'recursoshumanos.rh.rec4_autorizarferias.';

try {
    db_inicio_transacao();

    switch ($oParam->exec) {
        case "getEscalasFerias":
            $oServidor = null;
            $oDataInicio = null;
            $oDataFim = null;
            $oAno = null;
            $oMes = null;

            if (!empty($oParam->AnoFolha)) {
                $AnoFolha = $oParam->AnoFolha;
            }
            if (!empty($oParam->MesFolha)) {
                $MesFolha = $oParam->MesFolha;
            }

            if (!empty($oParam->iMatricula)) {
                $oServidor = ServidorRepository::getInstanciaByCodigo($oParam->iMatricula);
            }

            if (!empty($oParam->sDataInicio)) {
                $oDataInicio = new DBDate($oParam->sDataInicio);
            }

            if (!empty($oParam->sDataFinal)) {
                $oDataFim = new DBDate($oParam->sDataFinal);
            }

            $aCondicoes = array('situacaoAgendado' => true);

            if (isset($oParam->periodosAutorizados)) {
                $aCondicoes = array();
            }

            $oPeriodoGozoFerias = new PeriodoGozoFerias();
            $aPeriodosGozo = $oPeriodoGozoFerias
            ->getPeriodosGozo($oServidor, $oDataInicio, $oDataFim, $aCondicoes, $AnoFolha, $MesFolha);
            $aPeriodos = array();
            $oRetorno->erro = true;

            foreach ($aPeriodosGozo as $oPeriodoGozo) {
                $assentamentoAutorizacao = $oPeriodoGozo->getAssentamentoAutorizacao();

                if (!empty($oParam->autorizadas)) {
                    if ($oParam->autorizadas == 1 && $assentamentoAutorizacao == null) {
                        continue;
                    }

                    if ($oParam->autorizadas == 2 && $assentamentoAutorizacao != null) {
                        continue;
                    }
                }

                $oPeriodo = new stdClass();
                $oPeriodo->iCodigo = $oPeriodoGozo->getCodigoPeriodo();
                $oPeriodo->iMatricula = $oPeriodoGozo->getPeriodoAquisitivo()->getServidor()->getMatricula();
                $oPeriodo->sNome = urlencode($oPeriodoGozo->getPeriodoAquisitivo()->getServidor()->getCgm()->getNome());
                $oPeriodo->sDataInicio = $oPeriodoGozo->getPeriodoInicial()->getDate(DBDate::DATA_PTBR);
                $oPeriodo->sDataFinal = $oPeriodoGozo->getPeriodoFinal()->getDate(DBDate::DATA_PTBR);
                $oPeriodo->nDiasGozo = $oPeriodoGozo->getDiasGozo();
                $oPeriodo->nDiasAbono = $oPeriodoGozo->getDiasAbono();
                $oPeriodo->nDiasPecunia = $oPeriodoGozo->getDiasPecunia();
                $oPeriodo->temAssentamentoAutorizacao = $assentamentoAutorizacao != null ? 'Sim' : 'Não';
                $oPeriodo->temAssentamentoAutorizacao = urlencode($oPeriodo->temAssentamentoAutorizacao);
                $oPeriodo->periodoAquisitivoInicio =
                $oPeriodoGozo->getPeriodoAquisitivo()->getDataInicial()->getDate(DBDate::DATA_PTBR);
                $oPeriodo->periodoAquisitivoFim =
                $oPeriodoGozo->getPeriodoAquisitivo()->getDataFinal()->getDate(DBDate::DATA_PTBR);
                $oPeriodo->ponto = $oPeriodoGozo->getTipoPonto() == '1' ? 'Salário' : 'Complementar';
                $oPeriodo->ponto = urlencode($oPeriodo->ponto);
                $oPeriodo->observacoes = urlencode($oPeriodoGozo->getObservacao());
                $oPeriodo->diasPagos = $oPeriodoGozo->getDiasAPagar();
                $oPeriodo->codigoRhFerias = $oPeriodoGozo->getCodigoFerias();

                $aPeriodos[] = $oPeriodo;
            }

            $oRetorno->erro = false;
            $oRetorno->aEscalasFerias = $aPeriodos;

            break;

        case "processarEscalasFerias":
            if (!$oParam->aEscalas || $oParam->aEscalas == '') {
                throw new BusinessException(_M(MENSAGENS . 'erro_escala_ferias'));
            }

            if (count($oParam->aEscalas) < 1) {
                throw new BusinessException(_M(MENSAGENS . 'nenhuma_escala'));
            }

            $oDaoFeriasConfiguracao = new cl_rhferiasconfiguracao();
            $sSqlConfiguracaoFerias = $oDaoFeriasConfiguracao->sql_query_file(
                null,
                "rh168_tipoassentamentoferias, rh168_tipoassentamentoabono, rh168_tipoassentamentopecunia"
            );
            $rsConfiguracaoFerias = db_query($sSqlConfiguracaoFerias);

            if (!$rsConfiguracaoFerias) {
                throw new DBException(_M(MENSAGENS . 'erro_tipo_assentamentos'));
            }

            if (pg_num_rows($rsConfiguracaoFerias) == 0) {
                throw new BusinessException(_M(MENSAGENS . 'tipo_assentamento_nao_configurado'));
            }

            $oConfiguracaoFerias = db_utils::fieldsMemory($rsConfiguracaoFerias, 0);
            $iTipoAssentamentoFerias  = $oConfiguracaoFerias->rh168_tipoassentamentoferias;
            $iTipoAssentamentoAbono   = $oConfiguracaoFerias->rh168_tipoassentamentoabono;
            $iTipoAssentamentoPecunia = $oConfiguracaoFerias->rh168_tipoassentamentopecunia;

            if (empty($iTipoAssentamentoPecunia)) {
                throw new BusinessException(_M(MENSAGENS . 'tipo_assentamento_pecunia_nao_configurado'));
            }

            if (!$iTipoAssentamentoFerias) {
                throw new BusinessException(_M(MENSAGENS . 'tipo_assentamento_nao_configurado'));
            }

            if (!$iTipoAssentamentoAbono) {
                throw new BusinessException(_M(MENSAGENS . 'tipo_assentamento_abono_nao_configurado'));
            }

            foreach ($oParam->aEscalas as $aItemEscala) {
                if ($aItemEscala[8] == 'Sim') {
                    throw new BusinessException("Período de férias do servidor {$aItemEscala[2]} já autorizado.");
                }

                $oEscala = new \stdClass();
                $oEscala->iCodigo = $aItemEscala[0];
                $oEscala->iMatricula = $aItemEscala[2];
                $oEscala->sNome = $aItemEscala[3];
                $oEscala->sDataInicio = $aItemEscala[4];
                $oEscala->sDataFinal = $aItemEscala[5];
                $oEscala->nDiasGozo = (int)$aItemEscala[6];
                $oEscala->nDiasAbono  = (int)$aItemEscala[7];
                $oEscala->nDiasPecunia = (int)$aItemEscala[8];
                $oEscala->codigoRhFerias = (int)$aItemEscala[10];

                if (!empty($oEscala->sDataInicio)) {
                    $oDataConcessao = new DBDate($oEscala->sDataInicio);
                }

                $oDaoFeriasPeriodo = new cl_rhferiasperiodo;
                $oDaoFeriasPeriodo->rh110_anopagamento = $oParam->AnoMesfolha[0];
                $oDaoFeriasPeriodo->rh110_mespagamento = $oParam->AnoMesfolha[1];
                $oDaoFeriasPeriodo->rh110_sequencial = $aItemEscala[0];
                $oDaoFeriasPeriodo->alterar($aItemEscala[0]);



                $periodoGozoFerias = new PeriodoGozoFerias($oEscala->iCodigo);
                $historicoPeriodoAquisitivo  = " referente ao período aquisitivo de 
                {$periodoGozoFerias->getPeriodoAquisitivo()->getDataInicial()->getDate(DBDate::DATA_PTBR)}";
                $historicoPeriodoAquisitivo .=" à 
                {$periodoGozoFerias->getPeriodoAquisitivo()->getDataFinal()->getDate(DBDate::DATA_PTBR)}";

                if ($oEscala->nDiasGozo > 0) {
                    $oAssentamento = new Assentamento();
                    $oAssentamento->setMatricula($oEscala->iMatricula);
                    $oAssentamento->setTipoAssentamento($iTipoAssentamentoFerias);
                    $oAssentamento->setHistorico("Gozo de Férias {$historicoPeriodoAquisitivo}");
                    $oAssentamento->setPercentual("0");
                    $oAssentamento->setLoginUsuario(db_getsession("DB_id_usuario"));
                    $oAssentamento->setDataLancamento(date("Y-m-d", db_getsession("DB_datausu")));
                    $oAssentamento->setConvertido("false");
                    $oAssentamento->setDias("0");
                    $oAssentamento->setDataConcessao($oDataConcessao);

                    if (!empty($oEscala->sDataFinal)) {
                        $oDataTermino = new DBDate($oEscala->sDataFinal);
                        $oAssentamento->setDataTermino($oDataTermino);
                    }

                    if (!empty($oEscala->sDataInicio) && !empty($oEscala->sDataFinal)) {
                        $oAssentamento->setDias(DBDate::getIntervaloEntreDatas($oDataTermino, $oDataConcessao)->d + 1);
                    }

                    $oAssentamentoSalvo = $oAssentamento->persist();

                    persisteAssentamentoFuncional($oEscala, $oAssentamentoSalvo);
                    persistePeriodoFeriasAssentamento($oEscala, $oAssentamento);
                }

                if (!empty($oEscala->nDiasAbono) && $oEscala->nDiasAbono > 0) {
                    $oAssentamento = new Assentamento();
                    $oAssentamento->setMatricula($oEscala->iMatricula);
                    $oAssentamento->setTipoAssentamento($iTipoAssentamentoAbono);
                    $oAssentamento->setHistorico("Abono de Férias {$historicoPeriodoAquisitivo}");
                    $oAssentamento->setPercentual("0");
                    $oAssentamento->setLoginUsuario(db_getsession("DB_id_usuario"));
                    $oAssentamento->setDataLancamento(date("Y-m-d", db_getsession("DB_datausu")));
                    $oAssentamento->setConvertido("false");
                    $oAssentamento->setDias($oEscala->nDiasAbono);

                    if (!empty($oEscala->sDataInicio)) {
                        $iDiasAbono = $oEscala->nDiasAbono - 1;
                        $oDataConcessao = new DBDate($oEscala->sDataInicio);
                        $oDataTermino = clone $oDataConcessao;
                        $oDataTermino->modificarIntervalo("+$iDiasAbono days");
                        $oAssentamento->setDataConcessao($oDataConcessao);
                        $oAssentamento->setDataTermino($oDataTermino);
                    }

                    $oAssentamentoSalvo = $oAssentamento->persist();

                    persisteAssentamentoFuncional($oEscala, $oAssentamentoSalvo);
                    persistePeriodoFeriasAssentamento($oEscala, $oAssentamento);
                }

                if (!empty($oEscala->nDiasPecunia) && $oEscala->nDiasPecunia > 0) {
                    $oAssentamento = new Assentamento();
                    $oAssentamento->setMatricula($oEscala->iMatricula);
                    $oAssentamento->setTipoAssentamento($iTipoAssentamentoPecunia);
                    $oAssentamento->setHistorico("Pecúnia de Férias {$historicoPeriodoAquisitivo}");
                    $oAssentamento->setPercentual("0");
                    $oAssentamento->setLoginUsuario(db_getsession("DB_id_usuario"));
                    $oAssentamento->setDataLancamento(date("Y-m-d", db_getsession("DB_datausu")));
                    $oAssentamento->setConvertido("false");
                    $oAssentamento->setDias($oEscala->nDiasPecunia);

                    if (!empty($oEscala->sDataInicio)) {
                        $oDataConcessao = new DBDate($oEscala->sDataInicio);
                        $oDataTermino = new DBDate($oEscala->sDataFinal);
                        $oAssentamento->setDataConcessao($oDataConcessao);
                        $oAssentamento->setDataTermino($oDataTermino);
                    }

                    $oAssentamentoSalvo = $oAssentamento->persist();

                    persisteAssentamentoFuncional($oEscala, $oAssentamentoSalvo);
                    persistePeriodoFeriasAssentamento($oEscala, $oAssentamento);
                }

                $oRetorno->sMessage = urlencode(_M(MENSAGENS . 'ferias_autorizada_sucesso'));
            }


            break;

        case 'excluirPeriodo':
            if (empty($oParam->periodosFerias)) {
                throw new ParameterException('Nenhum período de férias selecionado.');
            }

            foreach ($oParam->periodosFerias as $periodoFerias) {
                $periodoGozoFerias = new PeriodoGozoFerias($periodoFerias);
                $periodoGozoFerias->excluirPeriodo();
            }

            $oRetorno->sMessage = urlencode('Cancelamento concluído com sucesso.');

            break;
    }

    db_fim_transacao();
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->erro = true;
    $oRetorno->iStatus = false;
    $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);

function vinculaRhFeriasAssenta($codigoRhFerias, $assentamento)
{

    $daoRhFeriasAssenta = new cl_rhferiasassenta();
    $daoRhFeriasAssenta->rh131_assenta = $assentamento->getCodigo();
    $daoRhFeriasAssenta->rh131_rhferias = $codigoRhFerias;
    $daoRhFeriasAssenta->incluir(null);

    if ($daoRhFeriasAssenta->erro_status == '0') {
        throw new DBException('Erro ao vincular o período aquisitivo com o assentamento.');
    }
}

function persisteAssentamentoFuncional($oEscala, $oAssentamentoSalvo)
{
    $oAssentamentoFuncional = new AssentamentoFuncional();
    $oAssentamentoFuncional->setCodigo($oAssentamentoSalvo->getCodigo());
    $oAssentamentoFuncional->setMatricula($oAssentamentoSalvo->getMatricula());
    $oAssentamentoFuncional->setTipoAssentamento($oAssentamentoSalvo->getTipoAssentamento());
    $oAssentamentoFuncional->setHistorico($oAssentamentoSalvo->getHistorico());
    $oAssentamentoFuncional->setPercentual($oAssentamentoSalvo->getPercentual());
    $oAssentamentoFuncional->setLoginUsuario($oAssentamentoSalvo->getLoginUsuario());
    $oAssentamentoFuncional->setDataLancamento($oAssentamentoSalvo->getDataLancamento());
    $oAssentamentoFuncional->setConvertido($oAssentamentoSalvo->isConvertido());
    $oAssentamentoFuncional->setDias($oAssentamentoSalvo->getDias());
    $oAssentamentoFuncional->setDataConcessao($oAssentamentoSalvo->getDataConcessao());

    if (!empty($oEscala->sDataFinal)) {
        $oDataTermino = new DBDate($oEscala->sDataFinal);
        $oAssentamentoFuncional->setDataTermino($oDataTermino);
    }

    AssentamentoFuncionalRepository::persist($oAssentamentoFuncional);
}

function persistePeriodoFeriasAssentamento($oEscala, $oAssentamento)
{
    $oDaoRhferiasperiodoassentamento = new cl_rhferiasperiodoassentamento;
    $oDaoRhferiasperiodoassentamento->rh169_sequencial = null;
    $oDaoRhferiasperiodoassentamento->rh169_rhferiasperiodo = $oEscala->iCodigo;
    $oDaoRhferiasperiodoassentamento->rh169_assenta = $oAssentamento->getCodigo();
    $oDaoRhferiasperiodoassentamento->incluir(null);

    if ($oDaoRhferiasperiodoassentamento->erro_status == "0") {
        throw new BusinessException(_M(MENSAGENS . 'erro_vincula_assentamento_aturorizacao'));
    }

    vinculaRhFeriasAssenta($oEscala->codigoRhFerias, $oAssentamento);
}
