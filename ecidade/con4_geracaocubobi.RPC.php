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
define( 'MENSAGEM_CON4_GERACAOCUBOBIRPC', 'configuracao.configuracao.con4_geracaocubobi_RPC.' );

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$oRetorno->erro      = false;

try {

  switch( $oParam->sExecuta ) {

    /**
     * Busca todos os relatórios(Cubos) do Grupo 4 - Cubo BI
     * Retorna o código, o nome se já possui agenda
     */
    case 'getCubos':

      $sWhereCubos      = "db63_db_gruporelatorio in (4, 5)";
      $sCamposCubo      = "db63_sequencial, db63_nomerelatorio, case when db126_cubo is not null then 't' else 'f' end as agendado";
      $sSqlCubos        = "select {$sCamposCubo}
                             from db_relatorio 
                             left join relatorioscubos 
                               on db126_cubo = db63_sequencial                                
                            where {$sWhereCubos}";

      $rsCubos          = db_query( $sSqlCubos );
      $oRetorno->aCubos = array();

      if ( !$rsCubos ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new Exception( _M( MENSAGEM_CON4_GERACAOCUBOBIRPC . "erro_buscar_cubos", $oErro) );
      }

      $iLinhas = pg_num_rows($rsCubos);

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

        $oDadosCubo = db_utils::fieldsMemory( $rsCubos, $iContador );

        $oCubo                       = new stdClass();
        $oCubo->iCubo                = $oDadosCubo->db63_sequencial;
        $oCubo->sCubo                = urlencode($oDadosCubo->db63_nomerelatorio);
        $oCubo->lPossuiPeriodicidade = $oDadosCubo->agendado=="t"?true:false;
        
        $oRetorno->aCubos[] = $oCubo;
      }
    break;

    /**
     * Exlcui o arquivo XML contendo as periodicidades que o cubo deve ser executado
     */
    case "excluir":

      if ( !isset($oParam->iCubo) || empty($oParam->iCubo) ) {
        throw new Exception( _M( MENSAGEM_CON4_GERACAOCUBOBIRPC . "cubo_nao_informado") );
      }

      $daoRelatoriosCubos = new cl_relatorioscubos();
      $daoRelatoriosCubos->excluir(null, "db126_cubo = {$oParam->iCubo}");

      $oRetorno->sMensagem = urlencode(_M( MENSAGEM_CON4_GERACAOCUBOBIRPC . "cubo_excluido"));

    break;

    /**
     * Salva as periodicidade na qual deve ser executado os Cubos do BI
     */
    case "salvar":

      if ( !isset($oParam->aCubos) || count($oParam->aCubos) == 0 ) {
        throw new Exception( _M( MENSAGEM_CON4_GERACAOCUBOBIRPC . "cubos_nao_informados") );
      }

      $daoRelatoriosCubos = new cl_relatorioscubos();
      foreach ( $oParam->aCubos as $oCubo ) {

        $sNomeCubo = db_stdClass::db_stripTagsJsonSemEscape( $oCubo->sCubo );
        $daoRelatoriosCubos->excluir($oCubo->iCubo);
	      $daoRelatoriosCubos->db126_codigo        = null;
        $daoRelatoriosCubos->db126_cubo          = $oCubo->iCubo;
        $daoRelatoriosCubos->db126_tipo          = $oCubo->iTipoPeriodicidade;
        $daoRelatoriosCubos->db126_periodicidade = implode(',',$oCubo->aPeriodicidade);
        $daoRelatoriosCubos->incluir(null);    
      }

      $oRetorno->sMensagem = urlencode(_M( MENSAGEM_CON4_GERACAOCUBOBIRPC . "cubo_salvo"));

    break;

  }

} catch( Exception $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
  $oRetorno->erro      = true;
}

echo $oJson->encode( $oRetorno );
