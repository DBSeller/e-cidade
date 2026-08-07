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

namespace ECidade\Educacao\Escola\Service;

use cl_regenteconselho;
use Etapa;
use Exception;
use GradeHorario;
use Turma;

class GradeHorarioService
{
    /**
     *
     * @todo refatorar... extraido lógica do rpc edu4_regenciaHorario.RPC.php case removerPeriodo
     *
     * @param Turma $turma
     * @param Etapa $etapa
     * @param $codigoRegenciaHorario
     * @param $rechumano
     * @throws \DBException
     * @throws \ParameterException
     */
    public static function removerRegentePermanente(Turma $turma, Etapa $etapa, $codigoRegenciaHorario, $rechumano)
    {
        $gradeHorario = new GradeHorario($turma, $etapa, false);
        $periodos = $gradeHorario->getPeriodosAula();
        foreach ($periodos as $periodo) {
            if (!empty($codigoRegenciaHorario)) {
                if ($codigoRegenciaHorario == $periodo->getCodigo()) {
                    if ($periodo->getRegente() == 0) {
                        $periodo->removerDisciplinaSemRegente();
                    } else {
                        $periodo->remover();
                    }
                    self::atualizarFaltasDiarioDeClasse($turma, $etapa, $periodo->getRegencia());
                    break;
                }
            } else {
                if ($periodo->getRegente() == 0) {
                    $periodo->removerDisciplinaSemRegente();
                } else {
                    $periodo->remover();
                }
                self::atualizarFaltasDiarioDeClasse($turma, $etapa, $periodo->getRegencia());
            }
        }

        self::removerRegenteConcelheiro($turma, $etapa, $rechumano);
    }

    private static function atualizarFaltasDiarioDeClasse(Turma $oTurma, $oEtapa, $oRegencia)
    {
        $aMatriculas = $oTurma->getAlunosMatriculadosNaturmaPorSerie($oEtapa);
        $aPeriodos = $oTurma->getCalendario()->getPeriodos();
        foreach ($aMatriculas as $oMatricula) {
            $oDiarioClasse = $oMatricula->getDiarioDeClasse();
            $oDisciplina = $oDiarioClasse->getDisciplinasPorRegencia($oRegencia);

            foreach ($aPeriodos as $oPeriodoCalendario) {
                $oPeriodoAvaliacaoCalendario = $oPeriodoCalendario->getPeriodoAvaliacao();
                $iTotalDeFaltas = $oDisciplina->getTotalDeFaltasPorPeriodoDeAula($oPeriodoAvaliacaoCalendario);
                foreach ($oDisciplina->getAvaliacoes() as $oAvaliacao) {
                    if (!$oAvaliacao->getElementoAvaliacao()->isResultado()) {
                        $oPeriodoAvaliacao = $oAvaliacao->getElementoAvaliacao()->getPeriodoAvaliacao();
                        if ($oPeriodoAvaliacao->getCodigo() == $oPeriodoAvaliacaoCalendario->getCodigo()) {
                            $oAvaliacao->setNumeroFaltas($iTotalDeFaltas);
                        }
                    }
                }
            }

            $oDisciplina->salvar();
        }
    }

    private static function removerRegenteConcelheiro($oTurma, $oEtapa, $rechumano)
    {
        $oDao = new cl_regenteconselho();
        $sWhere = "ed235_i_turma = {$oTurma->getCodigo()}";
        $lRemover = true;

        if (!empty($rechumano)) {
            $lRemover = false;
            $oGrade = new GradeHorario($oTurma, $oEtapa);
            $aPeriodos = $oGrade->getPeriodosAula();
            $lTemVinculo = false;

            foreach ($aPeriodos as $oPeriodo) {
                if ($oPeriodo->getRegente() == $rechumano) {
                    $lTemVinculo = true;
                    break;
                }
            }

            if (!$lTemVinculo) {
                $sWhere .= " and ed235_i_rechumano = {$rechumano} ";
                $lRemover = true;
            }
        }

        if ($lRemover) {
            $oDao->excluir(null, $sWhere);
            if ($oDao->erro_status == 0) {
                throw new Exception(_M(MSG_EDU4_REGENCIAHORARIORPC . "erro_remover_regente_conselheiro"));
            }
        }
    }
}
