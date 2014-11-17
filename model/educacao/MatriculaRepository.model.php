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

define( 'MENSAGEM_MATRICULA_REPOSITORY', 'educacao.escola.MatriculaRepository.' );

/**
 * Repositoy para Matriculas
 * @package   Educacao
 * @author
 * @version   $Revision: 1.17 $
 */
class MatriculaRepository {

  private $aMatricula = array();
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorna a instancia do Repositorio
   * @return MatriculaRepository
   */
  protected function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new MatriculaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se a Matricula  possui instancia, se não instancia e retorna a instancia de da Matricula
   *
   * @param integer $iMatricula
   * @return Matricula
   */
  public static function getMatriculaByCodigo($iMatricula) {

    if (!array_key_exists($iMatricula, MatriculaRepository::getInstance()->aMatricula)) {
      MatriculaRepository::getInstance()->aMatricula[$iMatricula] = new Matricula($iMatricula);
    }

    return MatriculaRepository::getInstance()->aMatricula[$iMatricula];
  }

  /**
   * Busca o aluno pela matricula
   *
   * @deprecated
   *
   * @param integer $iMatricula
   * @return Matricula
   */
  public static function getAlunoByMatricula($iMatricula) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sSqlMatricula = $oDaoMatricula->sql_query_file($iMatricula, "ed60_i_codigo");
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

    if ($oDaoMatricula->numrows > 0) {
      return MatriculaRepository::getInstance()->getMatriculaByCodigo(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo);
    }
    return false;
  }

  /**
   * Busca os Alunos de uma turma
   *
   * @param Turma $oTurma
   * @return Matricula
   */
  public static function getMatriculasByTurma(Turma $oTurma) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sWhere        = " ed60_i_turma         = {$oTurma->getCodigo()}";
    $sSqlMatricula = $oDaoMatricula->sql_query_aluno_matricula(null, "ed60_i_codigo, ed60_i_aluno, ed60_i_numaluno",
                                                               "ed60_i_numaluno, ed47_v_nome", $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
    $iTotalLinhas  = $oDaoMatricula->numrows;
    $aMatriculasTurma  = array();

    if ($iTotalLinhas > 0) {

      for ($i = 0; $i < $iTotalLinhas; $i++) {

       $iCodigoMatricula   = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_codigo;
       $aMatriculasTurma[] = MatriculaRepository::getInstance()->getMatriculaByCodigo($iCodigoMatricula);
      }
    }
    return $aMatriculasTurma;
  }

  /**
   * Busca os Alunos de uma turma
   *
   * @param Turma $oTurma
   * @return Matricula[]
   */
  public static function getMatriculasByTurmaOrdemAlfabetica(Turma $oTurma) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sWhere        = " ed60_i_turma = {$oTurma->getCodigo()}";
    $sSqlMatricula = $oDaoMatricula->sql_query_aluno_matricula(null, "ed60_i_codigo, ed60_i_aluno, ed60_i_numaluno",
                                                              "ed47_v_nome, ed60_i_numaluno", $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
    $iTotalLinhas  = $oDaoMatricula->numrows;
    $aMatriculasTurma  = array();

    if ($iTotalLinhas > 0) {

      for ($i = 0; $i < $iTotalLinhas; $i++) {

        $iCodigoMatricula   = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_codigo;
        $aMatriculasTurma[] = MatriculaRepository::getInstance()->getMatriculaByCodigo($iCodigoMatricula);
      }
    }
    return $aMatriculasTurma;
  }

  /**
   * Adiciona um Aluno ao repositorio
   *
   * @param Matricula $oMatricula
   * @return boolean
   */
  public static function adicionarMatricula(Matricula $oMatricula) {

    if (!array_key_exists($oMatricula->getCodigo(), MatriculaRepository::getInstance()->aMatricula)) {
      MatriculaRepository::getInstance()->aMatricula[$oMatricula->getCodigo()] = $oMatricula;
    }
    return true;
  }

  /**
   * Remove uma Matricula do repository
   *
   * @param Matricula $oMatricula
   * @return boolean
   */
  public static function removerMatricula(Matricula $oMatricula) {

    if (array_key_exists($oMatricula->getCodigo(), MatriculaRepository::getInstance()->aMatricula)) {
      unset(MatriculaRepository::getInstance()->aMatricula[$oMatricula->getCodigo()]);
    }
    return true;
  }

  public static function removeAll() {

    unset(MatriculaRepository::getInstance()->aMatricula);
    MatriculaRepository::getInstance()->aMatricula = array();
    return true;
  }


  /**
   * Retorna a matrícula ativa do aluno
   * @param Aluno $oAluno
   * @return Matricula|null
   */
  public static function getMatriculaAtivaPorAluno(Aluno $oAluno) {

    $sWhere  = "     ed60_i_aluno = {$oAluno->getCodigoAluno()} ";
    $sWhere .= " and ed60_c_concluida = 'N' ";
    $sWhere .= " and ed60_c_situacao = 'MATRICULADO'";

    $oDaoMatricula = new cl_matricula();
    $sSqlMatricula = $oDaoMatricula->sql_query_file(null, "ed60_i_codigo", null, $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

    if ($oDaoMatricula->numrows == 0) {
    	return null;
    }

    return MatriculaRepository::getInstance()->getMatriculaByCodigo(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo);
  }

  /**
   * Retorna a ultima matricula de um aluno
   * @param Aluno $oAluno
   * @return NULL|Matricula
   */
  public static function getUltimaMatriculaAluno(Aluno $oAluno) {

    $sWhere  = "     ed60_i_aluno = {$oAluno->getCodigoAluno()} ";

    $oDaoMatricula = new cl_matricula();
    $sSqlMatricula = $oDaoMatricula->sql_query_file(null, "max(ed60_i_codigo) as ed60_i_codigo", null, $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

    if ($oDaoMatricula->numrows == 0 || empty(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo)) {
      return null;
    }

    return MatriculaRepository::getInstance()->getMatriculaByCodigo(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo);
  }

  /**
   * Busca todas as matriculas que o aluno possui.
   * @param  Aluno   $oAluno
   * @param  boolean $lSomenteSituacaoMatriculado
   * @return array
   */
  public static function getTodasMatriculasAluno( Aluno $oAluno, $lSomenteSituacaoMatriculado = true ) {

    $aMatriculas     = array();
    $oDaoMatricula   = new cl_matricula();
    $sWhereMatricula = "ed60_i_aluno = {$oAluno->getCodigoAluno()}";
    if( $lSomenteSituacaoMatriculado ) {
      $sWhereMatricula .= " and ed60_c_situacao = 'MATRICULADO' ";
    }

    $sSqlMatricula = $oDaoMatricula->sql_query_file( null, 'ed60_i_codigo', null, $sWhereMatricula );
    $rsMatricula   = db_query( $sSqlMatricula );

    if( !$rsMatricula ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( MENSAGEM_MATRICULA_REPOSITORY + 'erro_buscar_matriculas', $oErro ) );
    }

    $iTotalMatriculas = pg_num_rows( $rsMatricula );
    if( $iTotalMatriculas == 0 ) {
      return $aMatriculas;
    }

    for( $iContador = 0; $iContador < $iTotalMatriculas; $iContador++ ) {

      $iCodigoMatricula = db_utils::fieldsMemory( $rsMatricula, $iContador)->ed60_i_codigo;
      $aMatriculas[]    = MatriculaRepository::getInstance()->getMatriculaByCodigo( $iCodigoMatricula );
    }

    return $aMatriculas;
  }
}