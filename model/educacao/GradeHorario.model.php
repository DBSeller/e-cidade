<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

/**
 * Grade de horário da turma
 * @package educacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
class GradeHorario {

  /**
   * Instancia da Turma
   * @var Turma
   */
  private $oTurma;

  /**
   * Instância da Etapa
   * @var Etapa
   */
  private $oEtapa;

  /**
   * Instância do período de aula
   * @var PeriodoAula[]
   */
  private $aPeriodosAula;


  public function __construct( Turma $oTurma, Etapa $oEtapa ) {

    $iTurma = $oTurma->getCodigo();
    $iEtapa = $oEtapa->getCodigo();

    if ( empty($iTurma) || empty($iEtapa) ) {
      throw new ParameterException( "Etapa e turma deve ser informada para montar a grade de horário." );
    }

    $this->oEtapa = $oEtapa;
    $this->oTurma = $oTurma;

    $sWhere  = "     ed59_i_turma = {$iTurma} ";
    $sWhere .= " and ed59_i_serie = {$iEtapa} ";
    $sWhere .= " and ed58_ativo is TRUE ";
    $sCampos = " distinct ed59_i_disciplina, ed58_i_diasemana, ed58_i_periodo ";

    $oDaoRegencia        = new cl_regenciahorario();
    $sSqlRegenciaHorario = $oDaoRegencia->sql_query_regencia_dia_semana(null, $sCampos, null, $sWhere);
    $rsRegenciaHorario   = db_query( $sSqlRegenciaHorario );

    if ( !$rsRegenciaHorario ) {
      throw new DBException ( "Erro ao buscar grade horario. \n" . pg_last_error() );
    }

    $iLinhas = pg_num_rows( $rsRegenciaHorario );
    for ( $i = 0; $i < $iLinhas; $i++ ) {

      $oDados = db_utils::fieldsMemory( $rsRegenciaHorario, $i);
      $oPeriodoAula = new PeriodoAula();
      $oPeriodoAula->setDiaSemena( $oDados->ed58_i_diasemana - 1 );
      $oPeriodoAula->setDisciplina( DisciplinaRepository::getDisciplinaByCodigo($oDados->ed59_i_disciplina) );
      $oPeriodoAula->setPeriodoEscola( PeriodoEscolaRepository::getByCodigo($oDados->ed58_i_periodo) );
      $this->aPeriodosAula[] = $oPeriodoAula;
    }
  }

  /**
   * Retorna a Turma
   * @return Turma
   */
  public function getTurma() {
    return $this->oTurma;
  }

  /**
   * Retorna os Periodos de aula da turma e etapa informada
   * @return PeriodoAula[]
   */
  public function getPeriodosAula() {
    return $this->aPeriodosAula;
  }

  /**
   * Retorna a Etapa
   * @return Etapa
   */
  public function getOEtapa() {
    return $this->oEtapa;
  }

  /**
   * Retorna uma estrutura os dias que uma disciplina tem aula de acordo com o Período de avaliação do calendário da turma.
   *
   * @exemple [ aDatas : [ oData : DBDate,
   *                       aPeriodoAula : [PeriodoAula1, PeriodoAula2 ]
   *                    ]
   *          ]
   *
   * @param  Disciplina       $oDisciplina
   * @param  PeriodoAvaliacao $oPeriodoAvaliacao
   * @return $aDiasAula[]
   */
  public function getDiasDeAulaDaDisciplinaNoPeriodoDeAvaliacao(Disciplina $oDisciplina, PeriodoAvaliacao $oPeriodoAvaliacao) {

    $oPeriodoCalendario = $this->oTurma->getCalendario()->getPeriodoCalendarioPorPeriodoAvaliacao($oPeriodoAvaliacao);
    $aDiasSemenaComAula = array();
    foreach ( $this->aPeriodosAula as $oPeriodoAula ) {

      if ($oPeriodoAula->getDisciplina()->getCodigoDisciplina() != $oDisciplina->getCodigoDisciplina() ) {
        continue;
      }
      $aDiasSemenaComAula[$oPeriodoAula->getDiaSemena()] = $oPeriodoAula->getDiaSemena();
    }

    $aDatasNoIntervalo = DBDate::getDatasNoIntervalo( $oPeriodoCalendario->getDataInicio(), $oPeriodoCalendario->getDataTermino(), $aDiasSemenaComAula );

    $aDiasAula = array();
    foreach ( $aDatasNoIntervalo as $oDiaAula ) {

      $oDia               = new stdClass();
      $oDia->oData        = $oDiaAula;
      $oDia->aPeriodoAula = array();
      foreach ( $this->aPeriodosAula as $oPeriodoAula ) {

        if ($oPeriodoAula->getDisciplina()->getCodigoDisciplina() != $oDisciplina->getCodigoDisciplina() ) {
          continue;
        }

        if ( $oPeriodoAula->getDiaSemena() == $oDiaAula->getDiaSemana() ) {
          $oDia->aPeriodoAula[] = $oPeriodoAula;
        }
      }
      $aDiasAula[] = $oDia;
    }

    return $aDiasAula;
  }
}