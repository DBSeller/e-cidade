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

use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual as AHEM;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoJustificativa;
use ECidade\RecursosHumanos\RH\Assentamento\Model\ControleMedicoExame;
use ECidade\RecursosHumanos\RH\Assentamento\Model\LoteLancamento;
use ECidade\RecursosHumanos\RH\Assentamento\Repository\LoteLancamentoRepository;
use ECidade\RecursosHumanos\RH\Assentamento\Service\Alteracao;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\Assentamento\Service\Inclusao;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->erro = false;
$retorno->lTemInconsistencias = false;
$retorno->mensagem = '';

try {
    db_inicio_transacao();

    switch ($parametros->acao) {
        case 'tiposAssentamentoPermitemDuplicata':
            $tipos = TipoAssentamentoRepository::tiposPermitemDuplicata();
            $retorno->tipos = array();
            foreach ($tipos as $tipo) {
                $retorno->tipos[] = $tipo->toArray();
            }

            break;
        case 'duplicaAssentamento':
            $assentamento = new Inclusao();

            $daoAssentamento = new cl_assenta();
            $daoTipoAssentamento = new cl_tipoasse();

            if (!isset($parametros->h12_tipo)) {
                $parametros->h12_tipo = "";
                $parametros->h12_tipefe = "";
            }

            if (isset($parametros->h16_perc)) {
                $assentamento->setPercentual($parametros->h16_perc);
            }

            if (isset($parametros->h16_hora)) {
                $assentamento->setHora($parametros->h16_hora);
                $parametros->h16_hora = '0:00';
            }

            $assentamento->setDataInicial($parametros->h16_dtconc);

            if (!empty($parametros->h16_dtterm)) {
                $assentamento->setDataTermino($parametros->h16_dtterm);
            }

            /**
             * h12_assent, no formulario esta como h12_codigo
             */
            $assentamento->setTipoAssentamento($parametros->h12_codigo);

            /**
             * Validações quando o tipo de assentamento for de reajuste salarial
             */
            if (!$assentamento->getAssentamento()->getInstanciaTipoAssentamento()->permiteDuplicar()) {
                throw new Exception('Não é possível duplicar esse tipo de assentamento.');
            }

            $assentamentoOriginal = AssentamentoRepository::getInstanceByCodigo($parametros->h16_codigo);

            if (is_null($assentamentoOriginal->getDataConcessao())) {
                throw new Exception('Não é possível duplicar o assentamento pois o mesmo não possuí data final.');
            }

            if ($assentamento->getAssentamento()->getDataConcessao()->getTimeStamp()
                <= $assentamentoOriginal->getDataTermino()->getTimeStamp()) {
                $mensagem = 'A data inicial do assentamento duplicado deve ser maior que a data'
                    . ' final do assentamento original.';
                throw new Exception($mensagem);
            }

            if (isset($parametros->sOpcaoAssentamento) && $parametros->sOpcaoAssentamento == 2) {
                $assentamento->setAssentamentoFuncional(true);
            }
            $assentamento->setCodigoInstituicao(db_getsession("DB_instit"));
            $assentamento->setMatricula($parametros->h16_regist);

            if (!empty($parametros->rh213_horainicio)) {
                $assentamento->setHoraInicio($parametros->rh213_horainicio);
            }

            if (!empty($parametros->rh213_horafim)) {
                $assentamento->setHoraFim($parametros->rh213_horafim);
            }

            if (isset($parametros->iCodigoEfetividade)) {
                $assentamento->setCodigoEfetividade($parametros->iCodigoEfetividade);
            }

            if (isset($parametros->atributos)) {
                $assentamento->setAtributosDinamicos($parametros->atributos);
            }

            if ($parametros->iPeriodoAquisitivo) {
                $assentamento->setPeriodoAquisitivo($parametros->iPeriodoAquisitivo);
            }

            if (!empty($parametros->h83_valor)) {
                $assentamento->setValor($parametros->h83_valor);
            }

            if (!empty($parametros->h83_meses)) {
                $assentamento->setQuantidadeMes($parametros->h83_meses);

            }

            if (!empty($parametros->h83_encargos)) {
                $assentamento->setEncargo($parametros->h83_encargos);
            }

            if (!empty($parametros->periodoJustificativa1)) {
                $assentamento->setPeriodoJustificativa(1);
            }

            if (!empty($parametros->periodoJustificativa2)) {
                $assentamento->setPeriodoJustificativa(2);
            }

            if (!empty($parametros->periodoJustificativa3)) {
                $assentamento->setPeriodoJustificativa(3);
            }

            if (!empty($parametros->horaExtraManual50Diurna)) {
                $assentamento->setHoraExtraManual50Diurna($parametros->horaExtraManual50Diurna);
            }

            if (!empty($parametros->horaExtraManual50Noturna)) {
                $assentamento->setHoraExtraManual50Noturna($parametros->horaExtraManual50Noturna);
            }
            if (!empty($parametros->horaExtraManual75Diurna)) {
                $assentamento->setHoraExtraManual75Diurna($parametros->horaExtraManual75Diurna);
            }

            if (!empty($parametros->horaExtraManual75Noturna)) {
                $assentamento->setHoraExtraManual75Noturna($parametros->horaExtraManual75Noturna);
            }

            if (!empty($parametros->horaExtraManual100Diurna)) {
                $assentamento->setHoraExtraManual100Diurna($parametros->horaExtraManual100Diurna);
            }

            if (!empty($parametros->h80_db_cadattdinamicovalorgrupo)) {
                $assentamento->setGrupoAtributoDinamico($parametros->h80_db_cadattdinamicovalorgrupo);
            }

            if (!empty($parametros->horaExtraManual100Noturna)) {
                $assentamento->setHoraExtraManual100Noturna($parametros->horaExtraManual100Noturna);
            }

            $assentamento->processar();

            $retorno->tipoFuncionamento = $parametros->sOpcaoAssentamento;
            $retorno->codigo = $assentamento->getCodigoAssentamento();

            break;
        case 'incluiAssentamento':
            $codigoAssentamento = null;
            if (!empty($parametros->h16_codigo)) {
                $codigoAssentamento = $parametros->h16_codigo;
            }
            $assentamento = new Inclusao($codigoAssentamento);

            if (!isset($parametros->h12_tipo)) {
                $parametros->h12_tipo = "";
                $parametros->h12_tipefe = "";
            }

            if (isset($parametros->h16_perc)) {
                $assentamento->setPercentual($parametros->h16_perc);
            }

            if (isset($parametros->h16_hora)) {
                $assentamento->setHora($parametros->h16_hora);
                $parametros->h16_hora = '0:00';
            }

            $assentamento->setDataInicial($parametros->h16_dtconc);

            if (!empty($parametros->h16_dtterm)) {
                $assentamento->setDataTermino($parametros->h16_dtterm);
            }

            /**
             * h12_assent, no formulario esta como h12_codigo
             */
            $assentamento->setTipoAssentamento($parametros->h12_codigo);

            // Default é funcional
            // 1 = efetividade
            // 2 = funcional
            if (isset($parametros->sOpcaoAssentamento) && $parametros->sOpcaoAssentamento == 1) {
                $assentamento->setAssentamentoFuncional(false);
            }

            if (!empty($parametros->rh213_horainicio)) {
                $assentamento->setHoraInicio($parametros->rh213_horainicio);
            }

            if (!empty($parametros->rh213_horafim)) {
                $assentamento->setHoraFim($parametros->rh213_horafim);
            }

            if (isset($parametros->iCodigoEfetividade)) {
                $assentamento->setCodigoEfetividade($parametros->iCodigoEfetividade);
            }

            if (isset($parametros->atributos)) {
                $assentamento->setAtributosDinamicos($parametros->atributos);
            }

            if ($parametros->iPeriodoAquisitivo) {
                $assentamento->setPeriodoAquisitivo($parametros->iPeriodoAquisitivo);
            }

            if (!empty($parametros->h83_valor)) {
                $assentamento->setValor($parametros->h83_valor);
            }

            if (!empty($parametros->h83_meses)) {
                $assentamento->setQuantidadeMes($parametros->h83_meses);
            }

            if (!empty($parametros->h83_encargos)) {
                $assentamento->setEncargo($parametros->h83_encargos);
            }

            $assentamento->setHistorico($parametros->h16_histor);

            if (!empty($parametros->h16_nrport)) {
                $assentamento->setCodigoPortaria($parametros->h16_nrport);
            }

            if (!empty($parametros->h16_atofic)) {
                $assentamento->setDescricaoAto($parametros->h16_atofic);
            }

            if (!empty($parametros->h16_quant)) {
                $assentamento->setDias($parametros->h16_quant);
            }

            if (!empty($parametros->periodoJustificativa1)) {
                $assentamento->setPeriodoJustificativa(1);
            }

            if (!empty($parametros->periodoJustificativa2)) {
                $assentamento->setPeriodoJustificativa(2);
            }

            if (!empty($parametros->periodoJustificativa3)) {
                $assentamento->setPeriodoJustificativa(3);
            }

            if (!empty($parametros->horaExtraManual50Diurna)) {
                $assentamento->setHoraExtraManual50Diurna($parametros->horaExtraManual50Diurna);
            }

            if (!empty($parametros->horaExtraManual50Noturna)) {
                $assentamento->setHoraExtraManual50Noturna($parametros->horaExtraManual50Noturna);
            }
            if (!empty($parametros->horaExtraManual75Diurna)) {
                $assentamento->setHoraExtraManual75Diurna($parametros->horaExtraManual75Diurna);
            }

            if (!empty($parametros->horaExtraManual75Noturna)) {
                $assentamento->setHoraExtraManual75Noturna($parametros->horaExtraManual75Noturna);
            }

            if (!empty($parametros->horaExtraManual100Diurna)) {
                $assentamento->setHoraExtraManual100Diurna($parametros->horaExtraManual100Diurna);
            }

            if (!empty($parametros->h80_db_cadattdinamicovalorgrupo)) {
                $assentamento->setGrupoAtributoDinamico($parametros->h80_db_cadattdinamicovalorgrupo);
            }

            if (!empty($parametros->horaExtraManual100Noturna)) {
                $assentamento->setHoraExtraManual100Noturna($parametros->horaExtraManual100Noturna);
            }

            if (!empty($parametros->h26_crmresponsavel)) {
                $assentamento->setCrmResponsavel($parametros->h26_crmresponsavel);
            }

            if (!empty($parametros->h26_nomeresponsavel)) {
                $assentamento->setNomeResponsavel($parametros->h26_nomeresponsavel);
            }

            if (!empty($parametros->h26_cpfresponsavel)) {
                $assentamento->setCpfResponsavel($parametros->h26_cpfresponsavel);
            }

            if (!empty($parametros->h26_dataatestado)) {
                $assentamento->setDataAtestado(new DBDate($parametros->h26_dataatestado));
            }

            if (!empty($parametros->h26_tipoexameocupacional) || $parametros->h26_tipoexameocupacional == "0") {
                $assentamento->setTipoExameOcupacional($parametros->h26_tipoexameocupacional);
            }

            if (!empty($parametros->h26_nomemedico)) {
                $assentamento->setNomeMedico($parametros->h26_nomemedico);
            }

            if (!empty($parametros->h26_sequencial)) {
                $assentamento->setCodigoControleMedico($parametros->h26_sequencial);
            }

            if (!empty($parametros->h26_crmmedico)) {
                $assentamento->setCrmMedico($parametros->h26_crmmedico);
            }

            if (!empty($parametros->h26_ufcrmresponsavel)) {
                $assentamento->setUfCrm($parametros->h26_ufcrmresponsavel);
            }

            if (!empty($parametros->h26_ufcrm)) {
                $assentamento->setUfCrmMedico($parametros->h26_ufcrm);
            }

            if (!empty($parametros->h26_resultadoatestado) || $parametros->h26_resultadoatestado == "0") {
                $assentamento->setResultadoAtestado($parametros->h26_resultadoatestado);
            }

            if (!empty($parametros->exames)) {
                $exames = json_decode(stripslashes($parametros->exames), true);
                foreach ($exames as $ex) {
                    $exame = new ControleMedicoExame();
                    if (!empty($ex['codigoProcedimento'])) {
                        $exame->setProcedimento($ex['codigoProcedimento']);
                    }
                    if (!empty($ex['codigoOrdem'])) {
                        $exame->setOrdem($ex['codigoOrdem']);
                    }
                    if (!empty($ex['codigoResultado'])) {
                        $exame->setResultado($ex['codigoResultado']);
                    }
                    if (!empty($ex['observacao'])) {
                        $exame->setObservacao($ex['observacao']);
                    }
                    if (!empty($ex['data'])) {
                        $exame->setData(new DBDate($ex['data']));
                    }
                    $assentamento->adicionarExame($exame);
                }
            }

            $assentamento->setCodigoInstituicao(db_getsession("DB_instit"));
            if (!empty($parametros->lote)) {
                $inconsistencias = array();
                $matriculas = array();
                if (!empty($parametros->loteMatricula)) {
                    $matriculas = explode(",", $parametros->loteMatricula);
                }
                /**
                 * Valida se existe seleção
                 */
                if (!empty($parametros->selecao)) {
                    $sql = "
                        select distinct
                            rh01_regist as matricula
                        from
                            pessoal.rhpessoal
                            inner join pessoal.rhpessoalmov on rh01_regist = rh02_regist
                            inner join pessoal.rhpeslocaltrab on rh02_seqpes = rh56_seqpes
                            left join pessoal.rhpesrescisao on rh05_seqpes = rh02_seqpes
                         where
                            rh02_anousu = " . \DBPessoal::getAnoFolha() . "
                            and rh02_mesusu = " . \DBPessoal::getMesFolha() . "
                            and rh05_seqpes is null
                            and rh02_instit = " . db_getsession("DB_instit") ."
                            and ";

                    $where = trim(\db_utils::getDao("selecao")->getCondicaoSelecao($parametros->selecao));
                    $sql .= $where;
                    $rs = \db_query($sql);
                    if (!$rs) {
                        throw new DBException("Erro ao buscar informações da matrículas da seleção.");
                    }
                    $qtd = pg_num_rows($rs);
                    for ($i = 0; $i < $qtd; $i++) {
                        $registro = \db_utils::fieldsMemory($rs, $i);
                        $matriculas[] = $registro->matricula;
                    }
                }
                if (sizeof($matriculas) == 0) {
                    throw new BusinessException("Nenhuma matrícula informada.");
                }
                $instituicao = InstituicaoRepository::getInstituicaoSessao();
                $loteLancamento = new LoteLancamento();
                $loteLancamento->setData(new DateTime());
                $loteLancamento->setInstituicao($instituicao);
                $loteLancamento->setTipoAssentamento(
                    TipoAssentamentoRepository::getInstanciaPorCodigo($parametros->h12_codigo)
                );
                foreach ($matriculas as $matricula) {
                    try {
                        $assentamento->setMatricula($matricula);
                        $assentamento->processar();
                        $loteLancamento->addAssentamento($assentamento->getAssentamentoSalvo());
                    } catch (Exception $e) {
                        $erro = new \stdClass();
                        $erro->matricula = $matricula;
                        $erro->mensagem = $e->getMessage();
                        $erro->nome = ServidorRepository::getInstanciaByCodigo($matricula)->getCgm()->getNome();
                        $inconsistencias[$matricula] = $erro;
                    }
                }

                if (sizeof($inconsistencias) > 0) {
                    $retorno->lTemInconsistencias = true;
                    $retorno->mensagem .= "Foram encontradas inconsistências";
                    $retorno->mensagem .= " em alguns servidores. Deseja imprimí-las?";

                    file_put_contents('tmp/servidores_inconsistencia_assentamento.json', json_encode(DBString::utf8_encode_all($inconsistencias)));
                }

                if (!empty($loteLancamento)) {
                    if (count($loteLancamento->getAssentamentos()) == 0) {
                        $mensagem = "Não foi possível salvar os assentamentos no lote de assentamentos.";
                        if (sizeof($inconsistencias) > 0) {
                            $mensagem .= "\nForam encontradas inconsistências em alguns servidores. "
                                . "Deseja imprimí-las?";
                        }
                        throw new Exception($mensagem);
                    }
                    $response = LoteLancamentoRepository::save($loteLancamento);
                    if (!$response) {
                        throw new Exception('Não foi possível salvar o lote de assentamentos.');
                    }
                }
            } else {
                $assentamento->setMatricula($parametros->h16_regist);
                if (!empty($codigoAssentamento)) {
                    if ((isset($parametros->origemProcesso) && !empty($parametros->origemProcesso))
                        && (isset($parametros->tipoProcesso) && !empty($parametros->tipoProcesso))) {
                            $sql = "select rh220_sequencial from recursoshumanos.retificacaoafastamento where rh220_assenta = {$codigoAssentamento}";
                            $rs = db_query($sql);
                            if (!$rs) {
                                throw new DBException("Erro ao buscar infomações de retificação do assentamento.");
                            }
                            $sequencialRetificacao = null;
                            if (pg_numrows($rs) > 0) {
                                $sequencialRetificacao = db_utils::fieldsMemory($rs, 0)->rh220_sequencial;
                            }
                            $daoRetificacaoAfastamento = new \cl_retificacaoafastamento();
                            $daoRetificacaoAfastamento->rh220_sequencial = null;
                            $daoRetificacaoAfastamento->rh220_assenta = $codigoAssentamento;
                            $daoRetificacaoAfastamento->rh220_origemretificacao = $parametros->origemProcesso;
                            $daoRetificacaoAfastamento->rh220_tipoprocesso = $parametros->tipoProcesso;
                            $daoRetificacaoAfastamento->rh220_numeroprocesso = $parametros->numeroProcesso;

                            if (!empty($sequencialRetificacao)) {
                                $daoRetificacaoAfastamento->rh220_sequencial = $sequencialRetificacao;
                                $daoRetificacaoAfastamento->alterar($sequencialRetificacao);
                            } else {
                                $daoRetificacaoAfastamento->incluir(null);
                            }
                            if ($daoRetificacaoAfastamento->erro_status == "0") {
                                throw new DBException("Erro ao salvar os dados da retificação.");
                            }
                    }
                }
                $assentamento->processar();
            }

            $retorno->tipoFuncionamento = $parametros->sOpcaoAssentamento;
            $retorno->codigo = $assentamento->getCodigoAssentamento();
            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
