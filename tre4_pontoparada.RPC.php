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

require_once ("std/db_stdClass.php");
require_once ("std/DBNumber.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once 'libs/exceptions/DBException.php';
require_once 'libs/exceptions/FileException.php';
require_once 'libs/exceptions/BusinessException.php';
require_once 'libs/exceptions/ParameterException.php';

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$iModuloEscola     = 1100747;

try {

  db_inicio_transacao();
  switch ($oParam->exec) {

    case "salvar":

      $oRetorno->iCodigoParada = '';

      $oPontoParada = new PontoParada($oParam->iCodigoParada);
      $oPontoParada->setNome(db_stdClass::normalizeStringJsonEscapeString($oParam->sNome));
      $oPontoParada->setAbreviatura(db_stdClass::normalizeStringJsonEscapeString($oParam->sAbreviatura));
      $oPontoParada->setCodigoRuaBairro($oParam->iCodigoRuaBairro);
      $oPontoParada->setPontoReferencia(db_stdClass::normalizeStringJsonEscapeString($oParam->sPontoReferencia));
      $oPontoParada->setLatitude($oParam->nLatitude);
      $oPontoParada->setLongitude($oParam->nLongitude);
      if (!empty($oParam->iCodigoDepartamento)) {

        $oPontoParada->setDepartamento(DBDepartamentoRepository::getDBDepartamentoByCodigo($oParam->iCodigoDepartamento));
      }
      $oPontoParada->salvar();
      $oRetorno->iCodigoParada = $oPontoParada->getCodigo();
      $oRetorno->message       = urlencode(_M('educacao.transporteescolar.db_frmpontosparada.confirma_salvar'));
      break;

    case 'remover' :

      $oPontoParada = new PontoParada($oParam->iCodigoParada);
      $oPontoParada->remover();
      $oRetorno->message = urlencode(_M('educacao.transporteescolar.db_frmpontosparada.confirma_excluir'));
      break;

    case 'getDados':

      $oPontoParada      = new PontoParada($oParam->iCodigoParada);
      $oLogradouroBairro = new LogradouroBairro($oPontoParada->getCodigoRuaBairro());

      $oDadosParada                         = new stdClass();
      $oDadosParada->iCodigoParada          = $oPontoParada->getCodigo();
      $oDadosParada->sNome                  = urlencode($oPontoParada->getNome());
      $oDadosParada->sAbreviatura           = urlencode($oPontoParada->getAbreviatura());
      $oDadosParada->iCodigoRuaBairro       = $oPontoParada->getCodigoRuaBairro();
      $oDadosParada->iCodigoBairro          = $oLogradouroBairro->getBairro()->getSequencial();
      $oDadosParada->sNomeBairro            = urlencode($oLogradouroBairro->getBairro()->getDescricao());
      $oDadosParada->iCodigoRua             = $oLogradouroBairro->getLogradouro()->getSequencial();
      $oDadosParada->sNomeRua               = urlencode($oLogradouroBairro->getLogradouro()->getDescricao());
      $oDadosParada->sPontoRefencia         = urlencode($oPontoParada->getPontoReferencia());
      $oDadosParada->nLatitude              = $oPontoParada->getLatitude();
      $oDadosParada->nLongitude             = $oPontoParada->getLongitude();
      $oDadosParada->iTipo                  = 2;
      $oDadosParada->iCodigoDepartamento    = '';
      $oDadosParada->sDescricaoDepartamento = '';
      $oDepartamento                        = $oPontoParada->getDepartamento();
      if (!empty($oDepartamento)) {

        $oDadosParada->iTipo                  = 1;
        $oDadosParada->iCodigoDepartamento    = $oDepartamento->getCodigo();
        $oDadosParada->sDescricaoDepartamento = urldecode($oDepartamento->getNomeDepartamento());
      }
      $oRetorno->dados = $oDadosParada;
      break;

    case 'salvarVinculoAluno':

      $oAluno                  = AlunoRepository::getAlunoByCodigo($oParam->iCodigoAluno);
      $oItinerarioPontoParada  = new ItinerarioPontoParada($oParam->iCodigoPontoParada);
      $oItinerarioVinculoAluno = new ItinerarioVinculoAluno();
      $oItinerarioVinculoAluno->setAluno($oAluno);
      $oItinerarioVinculoAluno->setItinerarioPontoParada($oItinerarioPontoParada);
      $oItinerarioVinculoAluno->setLinhaTransporteHorarioVeiculo($oParam->iLinhaHorarioVeiculo);
    
      $oItinerarioVinculoAluno->salvar();
      $oRetorno->message = urlencode(_M('educacao.transporteescolar.tre4_pontoparada.vinculo_salvo'));
      
      break;

    case 'removerVinculoAluno':

      if (isset($oParam->aDados) && !empty($oParam->aDados)) {

        foreach ($oParam->aDados as $oDadosRemocao) {

          $oVinculoAlunoPontoParada = new ItinerarioVinculoAluno($oDadosRemocao->iCodigoVinculo);
          $oVinculoAlunoPontoParada->remover();

        }
        $oRetorno->message = urlencode(_M('educacao.transporteescolar.tre4_pontoparada.vinculo_removido'));
      }

      break;

    case 'getAlunosVinculadosLinha':

      $oRetorno->aAlunos = array();


      if (!empty($oParam->iLinha)) {

        $oLinha = new LinhaTransporte($oParam->iLinha);

        foreach ($oLinha->getItinerarios() as $oLinhaItinerario) {

          foreach($oLinhaItinerario->getLogradouros() as $oLinhaItinerarioLogradouro) {

            foreach($oLinhaItinerarioLogradouro->getPontosDeParada() as $oItinerarioPontoParada) {

              foreach (ItinerarioVinculoAlunoRepository::getItinerarioVinculoAlunoPorPontoParada($oItinerarioPontoParada) as $oVinculoAluno) {

                $oAluno = $oVinculoAluno->getAluno();

                $oTemporario                                         = new stdClass();
                $oTemporario->iCodigoLinhaTransportePontoParadaAluno = $oVinculoAluno->getCodigo();
                $oTemporario->iCodigoItinerarioPontoParada           = $oItinerarioPontoParada->getCodigo();
                $oTemporario->iCodigoAluno                           = $oAluno->getCodigoAluno();
                $oTemporario->sNome                                  = urlencode($oAluno->getNome());
                $oTemporario->sHoraSaida                             = urlencode($oVinculoAluno->getLinhaItinerarioHorario()->getHoraSaida());
                $oTemporario->sHoraChegada                           = urlencode($oVinculoAluno->getLinhaItinerarioHorario()->getHoraChegada());

                $sPontoParada = urlencode($oItinerarioPontoParada->getPontoParada()->getNome());
                $sEscola      = "" ;

                foreach ($oAluno->getMatriculas() as $oMatricula) {

                  if (!$oMatricula->isConcluida() && $oMatricula->isAtiva() && $oMatricula->getSituacao() == 'MATRICULADO') {
                    $sEscola  = urlencode($oMatricula->getTurma()->getEscola()->getNome());
                  }
                }

                $oTemporario->sEmbarque     = $sPontoParada;
                $oTemporario->sDesembarque  = $sEscola;
                // Se tipo == 2 o aluno esta retornando, portanto o embarque é na escola
                if ( $oLinhaItinerario->getTipo() == 2 ) {

                  $oTemporario->sEmbarque     = $sEscola;
                  $oTemporario->sDesembarque  = $sPontoParada;
                }

                $oTemporario->sItinerario = urlencode($oLinhaItinerario->getTipo() == LinhaItinerario::IDA ? "Ida" : "Volta");
                $oRetorno->aAlunos[]      = $oTemporario;
              }
            }
          }
        }
      }
      break;

    case 'pesquisaEscola':

      $aFiltros = array();

      if (isset($oParam->filtraModulo) && !empty($oParam->filtraModulo)) {
        $aFiltros[] = " ed18_i_codigo in ($iEscola) ";
      }

      $oRetorno->iEscolaAtual = "";

      if (db_getsession("DB_modulo") == $iModuloEscola && isset($oParam->lTodasEscolas) && !$oParam->lTodasEscolas) {

        $aFiltros[]             = " ed18_i_codigo in ($iEscola) ";
        $oRetorno->iEscolaAtual = $iEscola;
      }

      $sWhere = implode(" and ", $aFiltros);

      $oDaoEscola     = db_utils::getdao('escola');
      $sCamposEscola  = "ed18_i_codigo as codigo_escola, ed18_c_nome as nome_escola";
      $sSqlEscola     = $oDaoEscola->sql_query_file("", $sCamposEscola, "ed18_c_nome", $sWhere);
      $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);

      $oRetorno->dados        = db_utils::getCollectionByRecord($rsResultEscola, false, false, true);

      break;

    case 'getHorariosLinha' :
      
      $sWhere  = "      tre06_sequencial = {$oParam->iLinha}";
      $sWhere .= "  and tre09_tipo       = {$oParam->iItinerario}";

      $oDaoLinhaTransporte = new cl_linhatransporte();
      $sSqlLinaTransporte  = $oDaoLinhaTransporte->sql_query_linha_horario_veiculo( $sWhere );
      $rsLinaTransporte    = db_query( $sSqlLinaTransporte );

      if ( !$rsLinaTransporte ) {
        throw new Exception(pg_last_error( $rsLinaTransporte ));
      }

      $oRetorno->aHorarioLinha = db_utils::getCollectionByRecord( $rsLinaTransporte, false, false, true );
      break;
  }
  db_fim_transacao(false);
} catch (Exception $eException) {

  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($eException->getMessage());
  db_fim_transacao(true);
}
echo $oJson->encode($oRetorno);