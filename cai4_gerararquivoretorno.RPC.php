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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$iAnoSessao = db_getsession("DB_anousu");
switch ($oParam->exec) {

  case 'getRegistros':

    $sSqlArquivo  = "select distinct db90_codban,";
    $sSqlArquivo .= "       db90_descr ";
    $sSqlArquivo .= "  from db_bancos ";
    $sSqlArquivo .= "       inner join conplanoconta on conplanoconta.c63_banco = db_bancos.db90_codban";
    $sSqlArquivo .= "                               and c63_anousu = {$iAnoSessao} ";
    $sSqlArquivo .= "       inner join conplanoreduz on conplanoreduz.c61_codcon  = conplanoconta.c63_codcon ";
    $sSqlArquivo .= "                               and c61_anousu                = c63_anousu ";
    $sSqlArquivo .= "                               and c61_reduz                 = c63_reduz ";
    $sSqlArquivo .= "       inner join empagetipo    on empagetipo.e83_conta      = conplanoreduz.c61_reduz ";
    $sSqlArquivo .= "       inner join empagepag     on empagepag.e85_codtipo     = empagetipo.e83_codtipo ";
    $sSqlArquivo .= "       inner join empageconf    on empageconf.e86_codmov     = empagepag.e85_codmov ";
    $sSqlArquivo .= "       left join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov and empageconfgera.e90_cancelado is false";
    $sSqlArquivo .= " where e90_codgera = {$oParam->iCodigoArquivo} ";
    $sSqlArquivo .= " order by db90_descr";
    $rsBanco      = db_query($sSqlArquivo);
    if (pg_num_rows($rsBanco) > 0) {

      $oDadosBanco = db_utils::fieldsMemory($rsBanco, 0);
      $oRetorno->codigobanco    = $oDadosBanco->db90_codban;
      $oRetorno->descricaobanco = $oDadosBanco->db90_descr;
      $oRetorno->registros      = getRegistroArquivo($oParam->iCodigoArquivo);
    }
    break;

  case 'processarArquivo' :

    $iCodigoEnvio = $oParam->iCodigoArquivo;
    $sSqlArquivo  = "select distinct db90_codban,";
    $sSqlArquivo .= "       db90_descr ";
    $sSqlArquivo .= "  from db_bancos ";
    $sSqlArquivo .= "       inner join conplanoconta on conplanoconta.c63_banco = db_bancos.db90_codban";
    $sSqlArquivo .= "                               and c63_anousu = {$iAnoSessao} ";
    $sSqlArquivo .= "       inner join conplanoreduz on conplanoreduz.c61_codcon  = conplanoconta.c63_codcon ";
    $sSqlArquivo .= "                               and c61_anousu                = c63_anousu ";
    $sSqlArquivo .= "                               and c61_reduz                 = c63_reduz ";
    $sSqlArquivo .= "       inner join empagetipo    on empagetipo.e83_conta      = conplanoreduz.c61_reduz ";
    $sSqlArquivo .= "       inner join empagepag     on empagepag.e85_codtipo     = empagetipo.e83_codtipo ";
    $sSqlArquivo .= "       inner join empageconf    on empageconf.e86_codmov     = empagepag.e85_codmov ";
    $sSqlArquivo .= "       left join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov ";
    $sSqlArquivo .= " where e90_codgera = {$oParam->iCodigoArquivo}";
    $sSqlArquivo .= " order by db90_descr";
    $rsBanco      = db_query($sSqlArquivo);
    if (pg_num_rows($rsBanco) > 0) {

      $oDadosBanco   = db_utils::fieldsMemory($rsBanco, 0);
      $sBanco        = $oDadosBanco->db90_codban;
      $sNomeArquivo  = "retornoTeste_{$oDadosBanco->db90_codban}_{$iCodigoEnvio}.txt";
      $rsArquivo     = fopen("tmp/{$sNomeArquivo}", "w");
      $sLinhaHeader  = $sBanco;
      $sLinhaHeader .= str_repeat(0, 3); //filler
      $sLinhaHeader .= "0";//tipodalinha
      $sLinhaHeader .= str_repeat("0", 135);//filler
      $sLinhaHeader .= "2";//retorno ou remessa
      $sLinhaHeader .= str_repeat("0", 97);//filler  de outros dados
      fputs($rsArquivo, $sLinhaHeader."\n");

      /**
       * escrevemos a linha de retorno do lote
       */
      $sLinhaLote  = $sBanco;
      $sLinhaLote .= str_pad(substr($iCodigoEnvio, 0, 4), 4,"0", STR_PAD_LEFT); //numerodolote;
      $sLinhaLote .= 1; //tipo dalinha
      switch ($sBanco) {

        case '001':

          $sLinhaLote .= str_pad($iCodigoEnvio, 233, "0", STR_PAD_LEFT);
          break;
        default:

          $sLinhaLote .= str_pad($iCodigoEnvio, 233, "0", STR_PAD_LEFT);
          break;
      }
      fputs($rsArquivo, $sLinhaLote."\n");

      /**
       * Montamos o Sql com os empenhos/OPS enviadas no Arquivo
       */
      $sSqlDadosArquivo  = "select e81_codmov,";
      $sSqlDadosArquivo .= "       e81_valor, ";
      $sSqlDadosArquivo .= "       case when z01_nome is null then 'SLIP' else z01_nome end as z01_nome, ";
      $sSqlDadosArquivo .= "       to_char(e87_data,'ddmmYYYY') as dataenvio, ";
      $sSqlDadosArquivo .= "       to_char(e87_data+1,'ddmmYYYY') as datapgto";
      $sSqlDadosArquivo .= "  from empageconfgera ";
      $sSqlDadosArquivo .= "       inner join  empagegera on e90_codgera = e87_codgera";
      $sSqlDadosArquivo .= "       inner join  empagemov  on e90_codmov  = e81_codmov ";
      $sSqlDadosArquivo .= "       left  join  empempenho on e60_numemp  = e81_numemp ";
      $sSqlDadosArquivo .= "       left  join  cgm        on e60_numcgm  = z01_numcgm ";
      $sSqlDadosArquivo .= " where e90_codgera = {$iCodigoEnvio}";
      $rsDadosArquivo    = db_query($sSqlDadosArquivo);
      $aRegistrosArquivo = db_utils::getCollectionByRecord($rsDadosArquivo);
      reset($aRegistrosArquivo);
      foreach ($aRegistrosArquivo as $oArquivo) {

        $sLinhaRegistro  = $sBanco;
        $sLinhaRegistro .= '0000';
        $sLinhaRegistro .= '3';
        $sLinhaRegistro .= '00000A';
        $sLinhaRegistro .= str_repeat("0", 59);
        switch ($sBanco) {

          case '001':

            $sLinhaRegistro .= str_pad($oArquivo->e81_codmov, 20, "0", STR_PAD_LEFT);
            $sLinhaRegistro .= str_repeat(" ", 61);
            break;
          case '041':

            $sLinhaRegistro .= str_pad($oArquivo->e81_codmov, 15, "0", STR_PAD_RIGHT);
            $sLinhaRegistro .= str_repeat("0", 66);
            break;

          case '104':

            $sLinhaRegistro .= str_pad($oArquivo->e81_codmov, 6, "0", STR_PAD_LEFT);
            $sLinhaRegistro .= str_repeat("0", 75);
            break;
        }

        $sLinhaRegistro .= $oArquivo->datapgto;
        $sLinhaRegistro .= str_pad(number_format($oArquivo->e81_valor,2,"", ""), 15,"0",STR_PAD_LEFT);
        $sLinhaRegistro .= str_repeat(" ", 53);
        $sCodigoRetorno  = "00";
        $iCodigoUsuario  = db_stdClass::inCollection('codigomovimento',
                                                     $oArquivo->e81_codmov,
                                                     $oParam->aRegistros);
        if ($iCodigoUsuario !== false) {
          $iCodigoEnvio = $oParam->aRegistros[$iCodigoUsuario]->codigoretorno;
        }
        $sLinhaRegistro .= str_pad("{$iCodigoEnvio}", 10, " ", STR_PAD_RIGHT);
        $sLinhaRegistro .= "\n";
        fputs($rsArquivo, $sLinhaRegistro);
      }
      fclose($rsArquivo);
      $oRetorno->nomearquivo = $sNomeArquivo;
    }
    break;
}

echo $oJson->encode($oRetorno);

/**
 * Retorna os registros do arquivo
 *
 * @param integer $iArquivo codigo do arquivo
 * @return array
 */
function getRegistroArquivo ($iArquivo) {

  $sSqlDadosArquivo  = "select e81_codmov,";
  $sSqlDadosArquivo .= "       e81_valor, ";
  $sSqlDadosArquivo .= "       case when z01_nome is null then 'SLIP' else z01_nome end as z01_nome, ";
  $sSqlDadosArquivo .= "       to_char(e87_data,'ddmmYYYY') as dataenvio, ";
  $sSqlDadosArquivo .= "       to_char(e87_data+1,'ddmmYYYY') as datapgto";
  $sSqlDadosArquivo .= "  from empageconfgera ";
  $sSqlDadosArquivo .= "       inner join empagegera on e90_codgera = e87_codgera";
  $sSqlDadosArquivo .= "       inner join empagemov  on e90_codmov  = e81_codmov ";
  $sSqlDadosArquivo .= "       left join  empempenho on e60_numemp  = e81_numemp ";
  $sSqlDadosArquivo .= "       left join  cgm        on e60_numcgm  = z01_numcgm ";
  $sSqlDadosArquivo .= " where e90_codgera = {$iArquivo}  and empageconfgera.e90_cancelado is false ";
  $rsDadosArquivo    = db_query($sSqlDadosArquivo);
  $aRegistrosArquivo = db_utils::getCollectionByRecord($rsDadosArquivo, false, false, true);
  return $aRegistrosArquivo;
}