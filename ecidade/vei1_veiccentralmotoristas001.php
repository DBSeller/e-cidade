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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_veicmotoristascentral_classe.php"));

db_postmemory($_POST);

$clveicmotoristascentral  = new cl_veicmotoristascentral;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

if (!isset($ve41_veicmotoristas)) {
  exit;
}

$db_opcao = 1;
$db_botao = true;

if (isset($novo)) {
  unset($sequencial);
  unset($ve41_veiccadcentral);
  unset($ve41_dtini);
  unset($ve41_dtini_dia);
  unset($ve41_dtini_mes);
  unset($ve41_dtini_ano);
  unset($ve41_dtfim);
  unset($ve41_dtfim_dia);
  unset($ve41_dtfim_mes);
  unset($ve41_dtfim_ano);
  unset($descrdepto);
}

if (isset($opcao)) {
  $dbwhere = "";

  if (isset($ve41_sequencial) && trim(@$ve41_sequencial) != "") {
    $dbwhere = "and ve41_sequencial = $ve41_sequencial";
  }

  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query(null, "ve41_sequencial,ve41_veiccadcentral,ve41_veicmotoristas,ve41_dtini,ve41_dtfim,descrdepto", null, "ve41_veicmotoristas = $ve41_veicmotoristas $dbwhere"));
  if ($clveicmotoristascentral->numrows > 0) {
    db_fieldsmemory($res_veicmotoristascentral, 0);
  }
}

if (isset($opcao) && $opcao == "alterar") {
  $sequencial = $ve41_sequencial;
  $db_opcao   = 2;

  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query($sequencial, "ve41_veiccadcentral,ve41_dtini,ve41_dtfim,descrdepto"));
  if ($clveicmotoristascentral->numrows > 0) {
    db_fieldsmemory($res_veicmotoristascentral, 0);
  }
}

if (isset($incluir)) {
  $erro_msg = "";
  $sqlerro  = false;

  if (isset($ve41_dtfim_dia) && trim($ve41_dtfim_dia) != "") {
    $ve41_dtfim = $ve41_dtfim_ano . "-" . $ve41_dtfim_mes . "-" . $ve41_dtfim_dia;
  } else {
    $ve41_dtfim = null;
  }

  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query(null, "ve41_veicmotoristas", null, "ve41_veiccadcentral = $ve41_veiccadcentral and ve41_veicmotoristas = $ve41_veicmotoristas and ve41_dtini between '" . $ve41_dtini_ano . "-" . $ve41_dtini_mes . "-" . $ve41_dtini_dia . "' and coalesce(ve41_dtfim,cast('9999-12-31' as date))"));
  if ($clveicmotoristascentral->numrows > 0) {
    $erro_msg                            = "Central já cadastrada para motorista. Verifique.";
    $clveicmotoristascentral->erro_campo = "ve41_veiccadcentral";
    $sqlerro = true;
  }

  db_inicio_transacao();

  if ($sqlerro == false) {
    $clveicmotoristascentral->ve41_veicmotoristas = $ve41_veicmotoristas;
    $clveicmotoristascentral->ve41_veiccadcentral = $ve41_veiccadcentral;

    if (isset($ve41_dtini_dia) && trim($ve41_dtini_dia) == "") {
      $clveicmotoristascentral->ve41_dtini = null;
    } else {
      $clveicmotoristascentral->ve41_dtini = $ve41_dtini_ano . "-" . $ve41_dtini_mes . "-" . $ve41_dtini_dia;
    }
    if (isset($ve41_dtfim_dia) && trim($ve41_dtfim_dia) != "") {
      $clveicmotoristascentral->ve41_dtfim = $ve41_dtfim_ano . "-" . $ve41_dtfim_mes . "-" . $ve41_dtfim_dia;
    } else {
      $clveicmotoristascentral->ve41_dtfim = null;
    }

    $clveicmotoristascentral->incluir(null);
    $erro_msg = $clveicmotoristascentral->erro_msg;
    if ($clveicmotoristascentral->erro_status == 0) {
      $sqlerro = true;
      if (trim($clveicmotoristascentral->erro_campo) == "") {
        $clveicmotoristascentral->erro_campo = "ve41_veiccadcentral";
      } else if ($clveicmotoristascentral->erro_campo == "ve41_dtini_dia") {
        $clveicmotoristascentral->erro_campo = "ve41_dtini";
      }
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false) {
    unset($ve41_sequencial);
    unset($sequencial);
    unset($ve41_veiccadcentral);
    unset($ve41_dtini);
    unset($ve41_dtini_dia);
    unset($ve41_dtini_mes);
    unset($ve41_dtini_ano);
    unset($ve41_dtfim);
    unset($ve41_dtfim_dia);
    unset($ve41_dtfim_mes);
    unset($ve41_dtfim_ano);
    unset($descrdepto);
  }
}

if (isset($alterar)) {
  $ve41_sequencial = $sequencial;
  $erro_msg        = "";
  $sqlerro         = false;

  db_inicio_transacao();

  if ($sqlerro == false) {
    $clveicmotoristascentral->ve41_sequencial     = $ve41_sequencial;
    $clveicmotoristascentral->ve41_veiccadcentral = $ve41_veiccadcentral;
    $clveicmotoristascentral->ve41_veicmotoristas = $ve41_veicmotoristas;

    if (isset($ve41_dtini_dia) && trim($ve41_dtini_dia) == "") {
      $clveicmotoristascentral->ve41_dtini = null;
    } else {
      $clveicmotoristascentral->ve41_dtini = $ve41_dtini_ano . "-" . $ve41_dtini_mes . "-" . $ve41_dtini_dia;
    }

    if (isset($ve41_dtfim_dia) && trim($ve41_dtfim_dia) != "") {
      $clveicmotoristascentral->ve41_dtfim = $ve41_dtfim_ano . "-" . $ve41_dtfim_mes . "-" . $ve41_dtfim_dia;
    } else {
      $clveicmotoristascentral->ve41_dtfim = null;
    }

    $clveicmotoristascentral->alterar($ve41_sequencial);
    $erro_msg = $clveicmotoristascentral->erro_msg;
    if ($clveicmotoristascentral->erro_status == 0) {
      $sqlerro = true;
      if (trim($clveicmotoristascentral->erro_campo) == "") {
        $clveicmotoristascentral->erro_campo = "ve41_veiccadcentral";
      } else if ($clveicmotoristascentral->erro_campo == "ve41_dtini_dia") {
        $clveicmotoristascentral->erro_campo = "ve41_dtini";
      }
    }
  }

  db_fim_transacao($sqlerro);
  $db_opcao = 2;
}

include(modification("forms/db_frmveiccentralmotoristas.php"));

if (isset($incluir) || isset($alterar) || isset($excluir)) {
  if ($sqlerro == true) {
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clveicmotoristascentral->erro_campo != "") {
      echo "<script> document.form1." . $clveicmotoristascentral->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clveicmotoristascentral->erro_campo . ".focus();</script>";
    }
  }

  if (trim($erro_msg) != "") {
    db_msgbox($erro_msg);
  }
}
