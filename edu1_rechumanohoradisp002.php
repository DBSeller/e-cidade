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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$clrechumanohoradisp = new cl_rechumanohoradisp;
$clperiodoescola     = new cl_periodoescola;
$cldiasemana         = new cl_diasemana;
$clrechumanoescola   = new cl_rechumanoescola;
$escola              = db_getsession("DB_coddepto");
$sCampos             = "min(ed17_h_inicio) as menorhorario,max(ed17_h_fim) as maiorhorario";
$result_per          = $clperiodoescola->sql_record($clperiodoescola->sql_query("",
                                                                                $sCampos,
                                                                                "",
                                                                                ""
                                                                               )
                                                   );
db_fieldsmemory($result_per,0);
$hora1         = (int)substr($menorhorario,0,2);
$hora2         = (int)substr($maiorhorario,0,2)+1;
$horainicial   = $hora1*100;
$horafinal     = $hora2*100;
$tempo_ini     = mktime($hora1,0,0,date("m"),date("d"),date("Y"));
$tempo_fim     = mktime($hora2,0,0,date("m"),date("d"),date("Y"));
$difer_minutos = ($tempo_fim-$tempo_ini)/60;
$alt_tab_hora  = $difer_minutos/2;
$qtd_hora      = $difer_minutos/60;
$larg_tabela   = @$larg_obj;
$larg_coluna1  = 40;
$larg_coluna2  = 40;
$tabela1_top   = 20;
$tabela1_left  = 2;
$oPost         = db_utils::postMemory($_POST);

if ($oPost->sAction == 'Excluir') {

  $codigos = substr(str_replace("tab","",$oPost->codhorario),1);
  db_inicio_transacao();
  $clrechumanohoradisp->excluir(""," ed33_i_codigo in ($codigos)");
  db_fim_transacao();
  $oJson = new services_json();
  echo $oJson->encode($oPost->codhorario);

}

if ($oPost->sAction == 'PesquisaPeriodo') {

  $restricao  = " AND not exists(select * from rechumanohoradisp ";
  $restricao .= "                 where ed33_rechumanoescola = {$oPost->rechumanoescola} ";
  $restricao .= "               and ed33_i_diasemana = {$oPost->diasemana} ";
  $restricao .= "               and ed33_i_periodo = ed17_i_codigo ";
  $restricao .= "              )";
  $sCampos    = "ed15_c_nome,ed17_i_codigo,ed08_c_descr,ed17_h_inicio,ed17_h_fim";
  $sWhere     = "ed17_i_escola = $escola AND ed17_i_turno = {$oPost->turno} $restricao";
  $result     = $clperiodoescola->sql_record($clperiodoescola->sql_query("",
                                                                         $sCampos,
                                                                         "ed15_i_sequencia,ed08_i_sequencia",
                                                                         $sWhere
                                                                        )
                                            );
  $aResult = db_utils::getCollectionByRecord($result, false, false, true);
  $oJson   = new services_json();
  echo $oJson->encode($aResult);

}

if ($oPost->sAction == 'BuscaOutrosDias') {

  $sWhere  = " ed04_i_escola = $escola AND ed04_c_letivo = 'S' AND ed32_i_codigo not in ({$oPost->diasemana})";
  $result  = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                 "ed32_i_codigo,ed32_c_abrev,ed32_c_descr",
                                                                 "ed32_i_codigo",
                                                                 $sWhere
                                                                )
                                     );
  $aResult = db_utils::getCollectionByRecord($result, false, false, true);
  $oJson   = new services_json();
  echo $oJson->encode($aResult);
}

if ($oPost->sAction == 'IncluirPeriodo') {

  $oRetorno                = new stdClass();
  $oRetorno->lVinculoAtivo = true;

  $quebra_periodos = explode(",",$oPost->periodos);
  $erro            = false;
  $inclusao        = false;
  $mensagem        = "";
  $sep_msg         = "";
  $pk_periodo      = "";
  $sep_pk          = "";

  $oDaoRecHumanoEscola    = new cl_rechumanoescola();
  $sWhereRecHumanoEscola  = "     ed75_i_codigo = {$oPost->rechumanoescola} AND ed75_i_escola = {$escola}";
  $sWhereRecHumanoEscola .= " AND ed75_i_saidaescola is null";
  $sSqlRecHumanoEscola    = $oDaoRecHumanoEscola->sql_query_file( null, "ed75_i_codigo", null, $sWhereRecHumanoEscola );
  $rsRecHumanoEscola      = db_query($sSqlRecHumanoEscola  );

  if( $rsRecHumanoEscola && pg_num_rows( $rsRecHumanoEscola ) == 0 ) {

    $mensagem                = urlencode( "Recurso humano não possui vínculo ativo com a escola." );
    $erro                    = true;
    $oRetorno->lVinculoAtivo = false;
  }

  if( !$erro ) {

    for ($x = 0; $x < count($quebra_periodos); $x++) {

      $sCampos = "ed17_h_inicio as hrinicio,ed17_h_fim as hrfim,ed15_c_nome as descrturno,ed08_c_descr as descrperiodo";
      $result  = $clperiodoescola->sql_record($clperiodoescola->sql_query("",
          $sCampos,
          "",
          " ed17_i_codigo = $quebra_periodos[$x]"
        )
      );
      db_fieldsmemory($result,0);
      $restrict  = " AND ( ";
      $restrict .= "     ( (ed17_h_inicio > '$hrinicio' AND ed17_h_inicio < '$hrfim') ";
      $restrict .= "       OR (ed17_h_fim  > '$hrinicio' AND ed17_h_fim < '$hrfim') ";
      $restrict .= "     ) ";
      $restrict .= "     OR (ed17_h_inicio <= '$hrinicio' AND ed17_h_fim >= '$hrfim') ";
      $restrict .= "     OR (ed17_h_inicio >= '$hrinicio' AND ed17_h_fim <= '$hrfim') ";
      $restrict .= "     OR (ed17_h_inicio = '$hrinicio' AND ed17_h_fim = '$hrfim') ";
      $restrict .= "    )";
      $sCampos   = " case when ed20_i_tiposervidor = 1 then 'Matrícula: '||ed284_i_rhpessoal else 'CGM: '||ed285_i_cgm end ";
      $sCampos  .= " as codmatricula,ed17_i_escola,ed18_c_nome,ed17_h_inicio,ed17_h_fim,ed08_c_descr";
      $sWhere    = " ed33_i_diasemana = {$oPost->diasemana} ";
      $sWhere   .= "  AND (cgmrh.z01_numcgm = {$oPost->z01_numcgm} OR cgmcgm.z01_numcgm = {$oPost->z01_numcgm}) $restrict ";
      $sWhere   .= "  AND ed33_ativo = 't'";
      $result2   = $clrechumanohoradisp->sql_record($clrechumanohoradisp->sql_query_disponibilidade("",
        $sCampos,
        "",
        $sWhere
      ));

      if ($clrechumanohoradisp->numrows > 0) {

        $msg_erro = "$descrturno $descrperiodo Período ($hrinicio às $hrfim) não Incluído. Conflito com período(s):\n";
        for ($q = 0; $q < $clrechumanohoradisp->numrows; $q++) {

          db_fieldsmemory($result2,$q);
          $msg_erro .= " -> $ed08_c_descr ($ed17_h_inicio às $ed17_h_fim) já marcado na Escola $ed17_i_escola ($codmatricula)\n";

        }

        $mensagem .= $sep_msg.urlencode($msg_erro);
        $sep_msg   = "";
        $erro      = true;

      } else {

        db_inicio_transacao();

        $clrechumanohoradisp->ed33_rechumanoescola = $oPost->rechumanoescola;
        $clrechumanohoradisp->ed33_i_diasemana     = $oPost->diasemana;
        $clrechumanohoradisp->ed33_i_periodo       = $quebra_periodos[$x];
        $clrechumanohoradisp->ed33_ativo           = 'true';
        $clrechumanohoradisp->incluir(null);

        db_fim_transacao();

        if ( $clrechumanohoradisp->erro_status == 0 ) {

          $mensagem .= $sep_msg.urlencode("Erro ao incluir alguns períodos informados.");
          $erro      = true;
        }

        $pk_periodo  .= $sep_pk.$quebra_periodos[$x];
        $sep_pk       = ",";
        $inclusao     = true;

      }
    }

    if ($erro == true && $inclusao == true) {
      $mensagem .= $sep_msg.urlencode("Demais períodos incluídos com sucesso!");
    } else if ($erro == false) {
      $mensagem = urlencode("Inclusão efetuada com sucesso!");
    } else if ($inclusao == false) {
      $pk_periodo = "0";
    }
  }

  $oRetorno->aRetorno   = array();
  $oRetorno->aRetorno[] = $pk_periodo;
  $oRetorno->aRetorno[] = $mensagem;
  $oJson                = new services_json();
  echo $oJson->encode($oRetorno);
}

if ($oPost->sAction == 'IncluirOutrosPeriodos') {

  $sWhere     = " ed04_i_escola = $escola AND ed04_c_letivo = 'S' AND ed32_i_codigo in ({$oPost->diasemana})";
  $result_day = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                    "ed32_i_codigo as coddia,ed32_c_descr as descrdia",
                                                                    "ed32_i_codigo",
                                                                    $sWhere
                                                                   )
                                        );
  $erro       = false;
  $inclusao   = false;
  $mensagem   = "";
  $sep_msg    = "";
  $pk_periodo = "";
  $sep_pk     = "";

  for ($dd = 0; $dd < $cldiasemana->numrows; $dd++) {

    db_fieldsmemory($result_day,$dd);
    $quebra_periodos = explode(",",$oPost->periodos);

    for ($x = 0; $x < count($quebra_periodos); $x++) {

      $sCampos = "ed17_h_inicio as hrinicio,ed17_h_fim as hrfim,ed15_c_nome as descrturno,ed08_c_descr as descrperiodo";
      $result  = $clperiodoescola->sql_record($clperiodoescola->sql_query("",
                                                                          $sCampos,
                                                                          "",
                                                                          " ed17_i_codigo = $quebra_periodos[$x]"
                                                                         )
                                             );
      db_fieldsmemory($result,0);
      $restrict  = " AND ( ";
      $restrict .= "     ( (ed17_h_inicio > '$hrinicio' AND ed17_h_inicio < '$hrfim') ";
      $restrict .= "       OR (ed17_h_fim  > '$hrinicio' AND ed17_h_fim < '$hrfim') ";
      $restrict .= "     ) ";
      $restrict .= "     OR (ed17_h_inicio <= '$hrinicio' AND ed17_h_fim >= '$hrfim') ";
      $restrict .= "     OR (ed17_h_inicio >= '$hrinicio' AND ed17_h_fim <= '$hrfim') ";
      $restrict .= "     OR (ed17_h_inicio = '$hrinicio' AND ed17_h_fim = '$hrfim') ";
      $restrict .= "    )";
      $sCampos   = "case when ed20_i_tiposervidor = 1 then 'Matrícula: '||ed284_i_rhpessoal else 'CGM: '||ed285_i_cgm";
      $sCampos  .= " end as codmatricula,ed17_i_escola,ed18_c_nome,ed17_h_inicio,ed17_h_fim,ed08_c_descr";
      $sWhere    = "  ed33_i_diasemana = $coddia AND (cgmrh.z01_numcgm = {$oPost->z01_numcgm} OR";
      $sWhere   .= " cgmcgm.z01_numcgm = {$oPost->z01_numcgm}) $restrict and ed33_ativo is true";
      $result2   = $clrechumanohoradisp->sql_record($clrechumanohoradisp->sql_query_disponibilidade("",
                                                                                                     $sCampos,
                                                                                                     "",
                                                                                                     $sWhere
                                                                                                    )
                                                   );

      if ($clrechumanohoradisp->numrows > 0) {

        $msg_erro  = " $descrdia - $descrturno $descrperiodo Período ($hrinicio às $hrfim) ";
        $msg_erro .= " não Incluído. Conflito com período(s):\n";

        for ($q = 0; $q < $clrechumanohoradisp->numrows; $q++) {

          db_fieldsmemory($result2,$q);
          $msg_erro .= " -> $ed08_c_descr ($ed17_h_inicio às $ed17_h_fim) já marcado na";
          $msg_erro .= " Escola $ed17_i_escola ($codmatricula)\n";

        }

        $mensagem .= $sep_msg.urlencode($msg_erro);
        $sep_msg   = "";
        $erro      = true;

      } else {

        db_inicio_transacao();

        $clrechumanohoradisp->ed33_rechumanoescola = $oPost->rechumanoescola;
        $clrechumanohoradisp->ed33_i_diasemana     = $coddia;
        $clrechumanohoradisp->ed33_i_periodo       = $quebra_periodos[$x];
        $clrechumanohoradisp->ed33_ativo           = 'true';
        $clrechumanohoradisp->incluir(null);

        db_fim_transacao();

        if ( $clrechumanohoradisp->erro_status == 0 ) {

          $mensagem .= $sep_msg.urlencode("Erro ao incluir alguns períodos informados.");
          $erro      = true;
        }
        $pk_periodo  .= $sep_pk.$quebra_periodos[$x];
        $sep_pk       = ",";
        $inclusao     = true;

      }
    }
  }

  if ($erro == true && $inclusao == true) {
    $mensagem .= $sep_msg.urlencode("Demais períodos incluídos com sucesso!");
  } else if ($erro == false) {
    $mensagem = urlencode("Inclusão efetuada com sucesso!");
  } else if ($inclusao == false) {
    $pk_periodo = "0";
  }
  $retorno   = array();
  $retorno[] = $pk_periodo;
  $retorno[] = $mensagem;
  $oJson     = new services_json();
  echo $oJson->encode($retorno);

}

if ($oPost->sAction == 'MontaGrade') {

  unset($_SESSION["sess_cordisp"]);
  $array_cores  = array("#FFCC99","#CCCCFF","#99FFCC","#CCFF66","#CC9933","#FF99FF","#996699","#66CC99","#FFCCCC","#9999FF");
  $sess_cordisp = array();
  $sCampos    = " DISTINCT ed18_i_codigo,ed18_c_nome,case when ed20_i_tiposervidor = 1 then";
  $sCampos   .= " cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as z01_numcgm";
  $result_cor = $clrechumanohoradisp->sql_record($clrechumanohoradisp->sql_query_disponibilidade("",
                                                                                 $sCampos,
                                                                                 "ed18_c_nome",
                                                                                 "ed33_rechumanoescola = {$oPost->rechumanoescola}"
                                                                                )
                                                );
  if ($clrechumanohoradisp->numrows > 0) {

    for ($y = 0; $y < $clrechumanohoradisp->numrows; $y++) {

      db_fieldsmemory($result_cor,$y);
      $sess_cordisp[$ed18_i_codigo] = $array_cores[$y];

    }
    @session_register("sess_cordisp");
  }
  $sHtml    = '<tr><td>';
  $ini_left = $tabela1_left+$larg_coluna1+$larg_coluna2;
  $result   = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                  "ed32_i_codigo,ed32_c_abrev,ed32_c_descr",
                                                                  "ed32_i_codigo",
                                                                  " ed04_i_escola = $escola AND ed04_c_letivo = 'S'"
                                                                 )
                                      );
  $larg_dia = floor(($larg_tabela-$larg_coluna1-$larg_coluna2)/$cldiasemana->numrows);
  for ($x = 0; $x < $cldiasemana->numrows; $x++) {

    $ini_top = $tabela1_top+25;
    db_fieldsmemory($result,$x);
    $sCampos = "ed20_i_codigo,ed33_i_codigo,ed08_c_descr,ed18_c_nome,ed15_c_nome,ed17_h_inicio,ed17_h_fim,ed17_i_escola";
    $sOrder  = "ed33_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhere  = " ed33_rechumanoescola = {$oPost->rechumanoescola} AND ed33_i_diasemana = $ed32_i_codigo";
    $result1 = $clrechumanohoradisp->sql_record($clrechumanohoradisp->sql_query_disponibilidade("",
                                                                                                 $sCampos,
                                                                                                 $sOrder,
                                                                                                 $sWhere
                                                                                                )
                                               );
    $tt      = 0;
    for ($t = $horainicial; $t <= $horafinal; $t+=1) {

      $hora = strlen($t) == 3?"0".$t:$t;
      $hora = substr($hora,0,2).":".substr($hora,2,2);

      if ($clrechumanohoradisp->numrows > 0) {

        for ($y = 0; $y < $clrechumanohoradisp->numrows; $y++) {

          db_fieldsmemory($result1,$y);
          if (trim($hora) == trim($ed17_h_inicio)) {

            $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
            $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
            $difermin  = ($tempo_fim-$tempo_ini)/60;
            $difer     = ceil($difermin/2);
            $sHtml    .= '<table id="tab'.$ed33_i_codigo.'" width="'.$larg_dia.'" border="0" bgcolor="#CCCCCC" ';
            $sHtml    .= ' height="'.$difer.'" style="background:'.$_SESSION["sess_cordisp"][$ed17_i_escola].';';
            $sHtml    .= ' border:1px outset #000000;position:absolute;top:'.$ini_top.'px;left:'.$ini_left.'px;" ';
            $sHtml    .= ' cellspacing="0" cellpading="0">';
            $sHtml    .= '<tr>';
            if ($ed17_i_escola == $escola) {

              $sHtml .= '<td onclick="js_marca('.$ed33_i_codigo.',\''.$_SESSION["sess_cordisp"][$ed17_i_escola].'\')" ';
              $sHtml .= 'style="cursor:pointer;font-size:10px;" align="center" ';
              $sHtml .= 'onmouseover="js_Mover(\'tab'.$ed33_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim;
              $sHtml .= '\',\''.$ed17_i_escola.'\',\''.$ed18_c_nome.'\',\''.$ed08_c_descr.'\',\''.$ed15_c_nome;
              $sHtml .= '\',\''.$_SESSION["sess_cordisp"][$ed17_i_escola].'\',\''.$ed20_i_codigo.'\')"';
              $sHtml .= ' onmouseout="js_Mout(\'tab'.$ed33_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';

            } else {
              $sHtml .= '<td style="font-size:10px;" align="center" ';
              $sHtml .= 'onmouseover="js_Mover(\'tab'.$ed33_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim;
              $sHtml .= '\',\''.$ed17_i_escola.'\',\''.$ed18_c_nome.'\',\''.$ed08_c_descr.'\',\''.$ed15_c_nome;
              $sHtml .= '\',\''.$_SESSION["sess_cordisp"][$ed17_i_escola].'\',\''.$ed20_i_codigo.'\')"';
              $sHtml .= ' onmouseout="js_Mout(\'tab'.$ed33_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';
            }
            $sHtml .= "Escola: ".$ed17_i_escola." -> ".$ed17_h_inicio." às ".$ed17_h_fim;
            $sHtml .= '<input type="hidden" id="input'.$ed33_i_codigo.'" value="" size="5">';
            $sHtml .= '</td>';
            $sHtml .= '</tr>';
            $sHtml .= '</table>';
          }
        }
      }
      $tt += 1;
      if ($tt == 60) {

        $t += 40;
        $tt = 0;

      }
      $ini_top += 0.5;
    }
    $ini_left += $larg_dia;
  }

  $sHtml .= '</td></tr>';
  $oJson = new services_json();
  echo $oJson->encode(urlencode($sHtml));

}
?>