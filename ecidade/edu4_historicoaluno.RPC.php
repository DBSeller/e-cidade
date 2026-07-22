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

use ECidade\Educacao\Escola\Model\AreaHistoricoFora;
use ECidade\Educacao\Escola\Model\AreaHistoricoRede;
use ECidade\Educacao\Escola\Registry\AreaConhecimentoRegistry;
use ECidade\Educacao\Escola\Repository\AreaHistoricoForaRepository;
use ECidade\Educacao\Escola\Repository\AreaHistoricoRedeRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->message = "";
$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
function criaObjetoDisciplinas($disciplinaHistorico, $areaHistorico = null)
{
    if (!is_null($disciplinaHistorico->getTipoBase()) && !empty($disciplinaHistorico->getTipoBase())) {
        $sql = "select ed182_id, ed182_descricao from tipobase where ed182_id = {$disciplinaHistorico->getTipoBase()}";
        $resource = db_query($sql);
        $tipoBase = pg_fetch_assoc($resource);
    }

    $obj = (object)[
        "codigo" => $disciplinaHistorico->getCodigo(),
        "codigo_disciplina" => $disciplinaHistorico->getDisciplina()->getCodigoDisciplina(),
        "descricao_disciplina" => urlencode($disciplinaHistorico->getDisciplina()->getNomeDisciplina()),
        "abreviatura_disciplina" => $disciplinaHistorico->getDisciplina()->getAbreviatura(),
        "resultado_final" => $disciplinaHistorico->getResultadoFinal(),
        "situacao" => urlencode($disciplinaHistorico->getSituacaoDisciplina()),
        "resultado_obtido" => urlencode($disciplinaHistorico->getResultadoObtido()),
        "carga_horaria" => $disciplinaHistorico->getCargaHoraria(),
        "justificativa" => $disciplinaHistorico->getJustificativa(),
        "descricao_justificativa" => "Não Implementado",
        "base" => $disciplinaHistorico->isBaseComum() ? "t" : "f",
        "codigo_area" => "",
        "descricao_area" => "",
        "nota_area" => "",
        "tipoBase" => isset($tipoBase) ? urlencode($tipoBase['ed182_descricao']) : urlencode('Não se aplica')
    ];
    if (!is_null($areaHistorico)) {
        $obj->codigo_area = $areaHistorico->getCodigo();
        $obj->descricao_area = urlencode($areaHistorico->getAreaConhecimento()->getDescricao());
        $obj->nota_area = $areaHistorico->getResultadoObtido();
    }
    return $obj;
}

switch ($oParam->exec) {
    case 'getDisciplinasHistorico':
        try {
            if (!isset($oParam->iCodigoHistoricoAno)) {
                throw new ParameterException('Histórico não informado.');
            }
            if (empty($oParam->iCodigoHistoricoAno)) {
                throw new ParameterException('Código do Historico informado é invalido');
            }
            if (!isset($oParam->iTipoHistorico)) {
                throw new Exception('Tipo do Histórico não informado');
            }

            $historicoEtapa = null;
            switch ($oParam->iTipoHistorico) {
                case 1:
                    $historicoEtapa = new HistoricoEtapaRede($oParam->iCodigoHistoricoAno);
                    break;
                case 2:
                    $historicoEtapa = new HistoricoEtapaForaRede($oParam->iCodigoHistoricoAno);
                    break;
            }

            $aDisciplinas = [];
            $areasConhecimento = $historicoEtapa->getAreasConhecimento();

            foreach ($areasConhecimento as $areaHistorico) {
                foreach ($areaHistorico->getDisciplinas() as $disciplinaHistorico) {
                    $objetoDisciplinas = criaObjetoDisciplinas($disciplinaHistorico, $areaHistorico);
                    $aDisciplinas[$disciplinaHistorico->getCodigo()] = $objetoDisciplinas;
                }
            }

            foreach ($historicoEtapa->getDisciplinas() as $disciplinaHistorico) {
                if (array_key_exists($disciplinaHistorico->getCodigo(), $aDisciplinas)) {
                    continue;
                }
                $aDisciplinas[$disciplinaHistorico->getCodigo()] = criaObjetoDisciplinas($disciplinaHistorico);
            }

            $oRetorno->disciplinas = array_values($aDisciplinas);
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    case 'incluirDisciplinaHistorico':
        db_inicio_transacao();
        try {
            $oAluno = new Aluno($oParam->iCodigoAluno);
            $oHistorico = $oAluno->getHistoricoEscolar($oParam->iCodigoCurso);
            $oEtapa = $oHistorico->getEtapaDeCodigo($oParam->iHistoricomps, $oParam->iTipoHistorico);

            switch ($oParam->iTipoHistorico) {
                case HistoricoEtapa::ETAPA_REDE:
                    if (!$oEtapa instanceof HistoricoEtapaRede) {
                        throw new Exception("Erro ao localizar etapa do histórico do aluno.");
                    }

                    if (empty($oParam->iCodigoLancamento)) {
                        $oDisciplina = new DisciplinaHistoricoRede();
                    } else {
                        $oDisciplina = $oEtapa->getDisciplinaByCodigoDeLancamento($oParam->iCodigoLancamento);
                    }
                    break;

                case HistoricoEtapa::ETAPA_FORA_REDE:
                    if (!$oEtapa instanceof HistoricoEtapaForaRede) {
                        throw new Exception("Erro ao localizar etapa do histórico do aluno.");
                    }

                    if (empty($oParam->iCodigoLancamento)) {
                        $oDisciplina = new DisciplinaHistoricoForaRede();
                    } else {
                        $oDisciplina = $oEtapa->getDisciplinaByCodigoDeLancamento($oParam->iCodigoLancamento);
                    }
                    break;
            }
            $oDisciplina->setDisciplina(new Disciplina($oParam->iCodigoDisciplina));
            $oDisciplina->setJustificativa($oParam->iJustificativa);

            if (!empty($oParam->iCargaHoraria) && strpos($oParam->iCargaHoraria, ",")) {
                $oParam->iCargaHoraria = str_replace(",", ".", $oParam->iCargaHoraria);
            }

            if ($oParam->iCargaHoraria == "") {
                $oParam->iCargaHoraria = "null";
            }

            $oDisciplina->setCargaHoraria($oParam->iCargaHoraria);
            $oDisciplina->setResultadoFinal($oParam->iResultado);
            $oDisciplina->setResultadoObtido(base64_decode($oParam->iAproveitamento));
            $oDisciplina->setSituacaoDisciplina(db_stdClass::normalizeStringJson($oParam->iSituacao));
            $oDisciplina->setTipoResultado($oParam->sTipoResultado);
            $oDisciplina->setOrdem($oParam->iOrdenacao);
            $oDisciplina->setTermoFinal($oParam->sTermoFinal);
            $oDisciplina->setLancamentoAutomatico(false);
            $oDisciplina->setBaseComum($oParam->lTipoBase == 1);
            $oDisciplina->setTipoBase($oParam->lTipoBase);
            if (empty($oParam->iCodigoLancamento)) {
                $oEtapa->adicionarDisciplina($oDisciplina);
            }

            $oEtapa->salvar();
            $oRetorno->message = urlencode('Disciplina salva com sucesso.');
        } catch (Exception $eErro) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        } catch (ParameterException $eParameterException) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eParameterException->getMessage());
        } catch (BusinessException $eBussinessException) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eBussinessException->getMessage());
        } catch (DBException $eDBException) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eDBException->getMessage());
        }

        db_fim_transacao(false);
        break;

    case 'excluirDisciplinaHistorico':
        try {
            db_inicio_transacao();
            $oAluno = new Aluno($oParam->iCodigoAluno);
            $oHistorico = $oAluno->getHistoricoEscolar($oParam->iCodigoCurso);
            $oEtapa = $oHistorico->getEtapaDeCodigo($oParam->iHistoricomps, $oParam->iTipoHistorico);
            $oEtapa->removerDisciplina($oParam->iDisciplina);
            $oRetorno->message = urlencode('Disciplina removida com sucesso.');
            db_fim_transacao(false);
        } catch (ParameterException $eParameterException) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eParameterException->getMessage());
        } catch (BusinessException $eBussinessException) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eBussinessException->getMessage());
        } catch (DBException $eDBException) {
            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eDBException->getMessage());
        }
        break;

    case 'carregaDadosDisciplina':
        try {
            $oAluno = new Aluno($oParam->iCodigoAluno);
            $oHistorico = $oAluno->getHistoricoEscolar($oParam->iCodigoCurso);
            $oEtapa = $oHistorico->getEtapaDeCodigo($oParam->iHistoricomps, $oParam->iTipoHistorico);
            $oDisciplina = $oEtapa->getDisciplinaByCodigoDeLancamento($oParam->iCodigo);
            $oDisciplinaRetorno = new stdClass();
            $oDisciplinaRetorno->iCodigoDisciplina = $oDisciplina->getDisciplina()->getCodigoDisciplina();
            $oDisciplinaRetorno->sDescricaoDisciplina = urlencode($oDisciplina->getDisciplina()->getNomeDisciplina());
            $oDisciplinaRetorno->sSituacao = urlencode($oDisciplina->getSituacaoDisciplina());
            $oDisciplinaRetorno->iCargaHoraria = $oDisciplina->getCargaHoraria();
            $oDisciplinaRetorno->sResultado = urlencode($oDisciplina->getResultadoFinal());
            $oDisciplinaRetorno->nAproveitamento = urlencode($oDisciplina->getResultadoObtido());
            $oDisciplinaRetorno->iCodigoLancamento = $oDisciplina->getCodigo();
            $oDisciplinaRetorno->iJustificativa = $oDisciplina->getJustificativa();
            $oDisciplinaRetorno->sTermoFinal = $oDisciplina->getTermoFinal();
            $oDisciplinaRetorno->lBaseComum = $oDisciplina->isBaseComum() ? 'true' : 'false';
            $oDisciplinaRetorno->lTipoBase = $oDisciplina->getTipoBase();

            $oRetorno->oDisciplina = $oDisciplinaRetorno;
        } catch (Exception $eErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    case 'pesquisaTermos':
        $oDaoHistorico = new cl_histmpsdisc();
        $sWhereHistorico = "ed62_i_codigo = {$oParam->iCodigoHistoricoAno}";
        $sSqlHistorico = $oDaoHistorico->sql_query(null, "ed62_i_anoref", null, $sWhereHistorico);
        $rsHistorico = $oDaoHistorico->sql_record($sSqlHistorico);

        $oRetorno->aTermos = array();
        $iContadorTermos = 0;
        if ($oDaoHistorico->numrows > 0) {
            $sAno = db_utils::fieldsMemory($rsHistorico, 0)->ed62_i_anoref;
        } else {
            $sAno = $oParam->iAnoReferencia;
        }
        $aTermos = DBEducacaoTermo::getTermoEncerramentoDoEnsino($oParam->iEnsino, $sAno);
        foreach ($aTermos as $oTermo) {
            $oRetorno->aTermos[$iContadorTermos] = new stdClass();
            $oRetorno->aTermos[$iContadorTermos]->sReferencia = urlencode($oTermo->sReferencia);
            $oRetorno->aTermos[$iContadorTermos]->sDescricao = urlencode($oTermo->sDescricao);
            $iContadorTermos++;
        }
        break;

    case 'validaEmissaoCertificado':
        try {
            if (empty($oParam->iAluno)) {
                throw new ParameterException("Aluno não informado para validação da emissão de certificado.");
            }

            $oRetorno->lPermiteImpressao = false;

            $oDaoHistorico = new cl_historico();
            $sWhereHistorico = "ed61_i_anoconc is not null AND ed61_i_aluno = {$oParam->iAluno}";
            $sSqlHistorico = $oDaoHistorico->sql_query_file(null, '1', null, $sWhereHistorico);
            $rsHistorico = db_query($sSqlHistorico);

            if (!$rsHistorico) {
                throw new DBException("Erro ao buscar o histórico do aluno.");
            }

            if (pg_num_rows($rsHistorico) > 0) {
                $oRetorno->lPermiteImpressao = true;
            }
        } catch (Exception $oErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        }

        break;

    case 'alunoTemHistorico':
        $oDaoHistorico = db_utils::getDao('historico');
        $sWhere = "ed61_i_aluno = {$oParam->iAluno} ";
        $rsHistorico = $oDaoHistorico->sql_record($oDaoHistorico->sql_query_file(null, '1', null, $sWhere));

        $oRetorno->lTemHistorico = false;
        if ($oDaoHistorico->numrows > 0) {
            $oRetorno->lTemHistorico = true;
        }

        break;

    case 'getDadosEtapa':
        $iCodigoEtapa = $oParam->iCodigoEtapa;
        $sTipoEtapa = $oParam->sTipoEtapa;
        $oEtapa = HistoricoEtapa::getInstanciaPeloTipo($sTipoEtapa, $iCodigoEtapa);
        $oRetorno->oEtapa = new stdClass();
        $oRetorno->oEtapa->sSituacao = urlencode($oEtapa->getSituacaoEtapa());
        $oRetorno->oEtapa->sResultado = $oEtapa->getResultadoAno();

        break;

    /*
     * Retorna se a escola pode ou não dar manutenção no histórico do aluno e em quais etapas e suas equivalências
    */
    case 'buscaStatusManutencaoHistorico':
        try {
            if (!isset($oParam->iCodigoAluno)) {
                throw new ParameterException("Aluno não informado.");
            }

            $oAluno = new Aluno($oParam->iCodigoAluno);
            if ($oAluno->getCodigoAluno() == null) {
                throw new ParameterException("Aluno informado inválido.");
            }

            $oEscola = new Escola(db_getsession('DB_coddepto'));
            if ($oEscola->getCodigo() == null) {
                throw new ParameterException("Departamento atual não é uma Escola.");
            }

            $oRetorno->iStatusAlteracaoHistorico = HistoricoEscolar::permiteManutencaoHistorico($oAluno, $oEscola);

            $aSequenciaEtapas = array();
            $oUltimaMatricula = MatriculaRepository::getUltimaMatriculaAluno($oAluno);
            if ($oUltimaMatricula != null) {
                $oUltimaEtapa = $oUltimaMatricula->getEtapaDeOrigem();

                if ($oUltimaEtapa != null) {
                    $aSequenciaEtapas[$oUltimaEtapa->getEnsino()->getCodigo()] = $oUltimaEtapa->getOrdem();

                    foreach ($oUltimaEtapa->buscaEtapaEquivalente() as $oEtapaEquivalente) {
                        $codigoEtapaEquivalente = $oEtapaEquivalente->getEnsino()->getCodigo();
                        $aSequenciaEtapas[$codigoEtapaEquivalente] = $oEtapaEquivalente->getOrdem();
                    }
                }
            }

            $oRetorno->aSenquenciaEtapas = $aSequenciaEtapas;
        } catch (Exception $oErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        }
        break;

    case 'buscarAreasHistoricoEtapa':
        try {
            if (empty($oParam->iHistoricoMps)) {
                throw new ParameterException('Código do Historico informado é invalido');
            }
            if (empty($oParam->iTipoHistorico)) {
                throw new Exception('Tipo do Histórico não informado');
            }

            $historicoEtapa = null;
            switch ($oParam->iTipoHistorico) {
                case 1:
                    $historicoEtapa = new HistoricoEtapaRede($oParam->iHistoricoMps);
                    break;
                case 2:
                    $historicoEtapa = new HistoricoEtapaForaRede($oParam->iHistoricoMps);
                    break;
            }
            $oRetorno->areas_conhecimento = [];
            $areasConhecimento = $historicoEtapa->getAreasConhecimento();

            foreach ($areasConhecimento as $areaHistorico) {
                $oRetorno->areas_conhecimento[] = (object) [
                    "codigo" => $areaHistorico->getCodigo(),
                    "codigo_areaconhecimento" => $areaHistorico->getAreaConhecimento()->getCodigo(),
                    "descricao_areaconhecimento" => urlencode($areaHistorico->getAreaConhecimento()->getDescricao()),
                    "resultado_obtido" => $areaHistorico->getResultadoObtido(),
                    "resultado_final" => $areaHistorico->getResultadoFinal()
                ];
            }
        } catch (Exception $oErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        }
        break;

    case 'salvarAreaHistorico':
        try {
            if (empty($oParam->iHistoricoMps)) {
                throw new Exception('Historico não pode ser vazio');
            }
            if (empty($oParam->iTipoHistorico)) {
                throw new Exception('Tipo Historico não pode ser vazio');
            }
            if (empty($oParam->iCodigoArea)) {
                throw new Exception('Código da Área de Conhecimento não pode ser vazio');
            }
            if (empty($oParam->iResultadoObtido)) {
                throw new Exception('Aproveitamento não pode ser vazio');
            }
            if (empty($oParam->iResultadoFinal)) {
                throw new Exception('Resultado Final não pode ser vazio');
            }

            $areaDeConhecimento = AreaConhecimentoRegistry::get($oParam->iCodigoArea);
            switch ($oParam->iTipoHistorico) {
                case 1:
                    $areaHistoricoRepository = new AreaHistoricoRedeRepository();
                    $historicoEtapaArea = new AreaHistoricoRede();
                    $historicoEtapaArea->setHistoricoEtapaRede(new HistoricoEtapaRede($oParam->iHistoricoMps));
                    break;
                case 2:
                    $areaHistoricoRepository = new AreaHistoricoForaRepository();
                    $historicoEtapaArea = new AreaHistoricoFora();
                    $historicoEtapaArea->setHistoricoEtapaForaRede(new HistoricoEtapaForaRede($oParam->iHistoricoMps));
                    break;
            }
            $historicoEtapaArea->setCodigo($oParam->iCodigo);
            $historicoEtapaArea->setAreaConhecimento($areaDeConhecimento);
            $historicoEtapaArea->setResultadoObtido($oParam->iResultadoObtido);
            $historicoEtapaArea->setResultadoFinal($oParam->iResultadoFinal);

            $historicoEtapaArea = $areaHistoricoRepository->salvar($historicoEtapaArea);

            $oRetorno->areahistorico = (object) [
                "codigo" => $historicoEtapaArea->getCodigo(),
                "codigo_areaconhecimento" => $historicoEtapaArea->getAreaConhecimento()->getCodigo(),
                "descricao_areaconhecimento" => urlencode($historicoEtapaArea->getAreaConhecimento()->getDescricao()),
                "resultado_obtido" => $historicoEtapaArea->getResultadoObtido(),
                "resultado_final" => $historicoEtapaArea->getResultadoFinal()
            ];
        } catch (Exception $oErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        }
        break;
    case 'excluirAreaHistorico':
        try {
            if (empty($oParam->iCodigo)) {
                throw new Exception('Código não pode ser vazio');
            }
            if (empty($oParam->iTipoHistorico)) {
                throw new Exception('Tipo Historico não pode ser vazio');
            }

            switch ($oParam->iTipoHistorico) {
                case 1:
                    $areaHistoricoRepository = new AreaHistoricoRedeRepository();
                    break;
                case 2:
                    $areaHistoricoRepository = new AreaHistoricoForaRepository();
                    break;
            }
            $historicoEtapaArea = $areaHistoricoRepository->find($oParam->iCodigo);
            $areaHistoricoRepository->excluir($historicoEtapaArea);

            $oRetorno->message = "Excluído com sucesso!";
        } catch (Exception $oErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        }
        break;
    case 'vincularAreaDisciplinaHistorico':
        try {
            if (empty($oParam->iDisciplinaEtapa)) {
                throw new Exception('Código da Disciplina Etapa não pode ser vazio');
            }
            if (!isset($oParam->iCodigoAreaEtapa)) {
                throw new Exception('O Codigo da área de conhecimento deve ser preenchido');
            }
            if (empty($oParam->iTipoHistorico)) {
                throw new Exception('Tipo Historico não pode ser vazio');
            }

            switch ($oParam->iTipoHistorico) {
                case 1:
                    $areaHistoricoRepository = new AreaHistoricoRedeRepository();
                    $disciplinaHistorico = new DisciplinaHistoricoRede($oParam->iDisciplinaEtapa);
                    break;
                case 2:
                    $areaHistoricoRepository = new AreaHistoricoForaRepository();
                    $disciplinaHistorico = new DisciplinaHistoricoForaRede($oParam->iDisciplinaEtapa);
                    break;
            }
            $areaHistoricoEtapa = $areaHistoricoRepository->find($oParam->iCodigoAreaEtapa);
            $areaHistoricoRepository->salvarAreaDisciplina($areaHistoricoEtapa, $disciplinaHistorico);
        } catch (Exception $oErro) {
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        }
}
echo $oJson->encode($oRetorno);
