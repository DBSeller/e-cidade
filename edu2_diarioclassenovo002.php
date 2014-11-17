<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once( "fpdf151/pdf.php" );

$oGet                = db_utils::postmemory( $_GET );
$oTurma              = TurmaRepository::getTurmaByCodigo( $oGet->iTurma );
$oEtapa              = EtapaRepository::getEtapaByCodigo( $oGet->iEtapa );
$oAvaliacaoPeriodica = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo( $oGet->iPeriodo );
//$o = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);
//$o->getResultados();

try {

  /**
   * Verifica o modelo selecionado, e seta as propriedades específicas de cada um
   */
  switch ( $oGet->iModelo ) {

    /**
     * Modelo 1 - Uma disciplina por página (Área)
     */
    case '1':

      $oRelatorio = new RelatorioDiarioClasseDisciplina( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
      $oRelatorio->setExibirAvaliacao( $oGet->avaliacoes == "true" );
      $oRelatorio->setExibirFaltas( $oGet->totalFaltas == "true");
      $oRelatorio->setExibirDataPeriodo( $oGet->dataPeriodo == "true");
      break;

    /**
     * Modelo 2 - Todas disciplinas em uma página (Currículo)
     */
    case '2':

      $oRelatorio = new RelatorioDiarioClasseGlobalizada( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
      break;

    /**
     * Modelo 3 - Duas páginas por disciplina (Página 1 - Presenças / Página 2 - Avaliações)
     */
    case '3':

      $oRelatorio = new RelatorioDiarioClasseCompleto( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
      $oRelatorio->setExibirTotalFaltas( $oGet->totalFaltas == "true" );
      $oRelatorio->setExibirSexo( $oGet->sexo == "true" );
      $oRelatorio->setExibirIdadeSegundaPagina( $oGet->idade == "true" );
      $oRelatorio->setExibirFaltasAbonadas( $oGet->faltasAbonadas == "true" );
      $oRelatorio->setExibirCodigo( $oGet->codigo == "true" );
      $oRelatorio->setExibirNascimento( $oGet->nascimento == "true" );
      $oRelatorio->setExibirResultadoAnterior( $oGet->resultadoAnterior == "true" );
      $oRelatorio->setExibirParecer( $oGet->parecer == "true" );
      break;

    /**
     * Modelo 4 - Turma EJA
     */
    case '4':

      $oRelatorio = new RelatorioDiarioClasseEja( $oTurma, $oEtapa, $oAvaliacaoPeriodica );
      $oRelatorio->setExibirAvaliacao( $oGet->avaliacoes == "true" );
      $oRelatorio->setExibirFaltas( $oGet->totalFaltas == "true");
      break;
  }


  /**
   * Propriedades padrão independente do modelo selecionado
   */
  $oRelatorio->setRegistroManual( $oGet->sRegistro == 'M' );
  $oRelatorio->setExibirPontos( $oGet->sExibirPontos == 'S' );
  $oRelatorio->setInformarDiasLetivos( $oGet->sDiasLetivos == 'S' );
  $oRelatorio->setDiasLetivos( $oGet->iQuantidadeColunas );
  $oRelatorio->setSomenteMatriculados( $oGet->sAlunosAtivos == 'S' );
  $oRelatorio->setExibirTrocaTurma( $oGet->sTrocaTurma == 'S' );

  /**
   * Adiciona as regências selecionadas ao array da classe
   */
  $aRegencias = explode( ",", $oGet->aRegencias );
  foreach ( $aRegencias as $iRegencia ) {
    $oRelatorio->adicionarRegencias( new Regencia( $iRegencia ) );
  }

  $oRelatorio->escrever();
  $oRelatorio->Output();

} catch ( Exception $oErro ) {
  echo $oErro->getMessage();
}