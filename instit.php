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

session_start();
$_SESSION["DB_itemmenu_acessado"] = "0";

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once('model/configuracao/SkinService.service.php');

session_unregister("DB_instit");
session_unregister("DB_Area");

if (session_is_registered("DB_uol_hora")) {

  db_query("update db_usuariosonline
               set uol_arquivo = '',
                   uol_modulo = 'Selecionando Instituição' ,
                   uol_inativo = ".time()."
             where uol_id = ".db_getsession("DB_id_usuario")."
               and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."'
               and uol_hora = ".db_getsession("DB_uol_hora")) or die("Erro(26) atualizando db_usuariosonline");

  if(db_getsession("DB_id_usuario") == "1") {
    $rsInstituicoes = db_query( "select codigo, nomeinst, figura, db21_tipoinstit from db_config order by prefeitura desc, codigo" );
  } else {
    $rsInstituicoes = db_query( "select c.codigo, c.nomeinst, c.figura, db21_tipoinstit
                                   from db_config c
                                        inner join db_userinst u on u.id_instit = c.codigo
                                  where u.id_usuario = ".db_getsession("DB_id_usuario")."
                                  order by c.prefeitura desc, c.codigo" );
  }

}
?>

<html>
  <?php

    $oDBReleaseNote = new DBReleaseNote(db_getsession('DB_id_usuario'), "nota_geral_01");
    $tem_atualizacoes = $oDBReleaseNote->check();

    $oSkin = new SkinService();
    include( $oSkin->getPathFile("instit.php") );

    if ($tem_atualizacoes) {

      $sScriptChangelog  = "<script src=\"scripts/classes/configuracao/DBViewReleaseNote.classe.js\" type=\"text/javascript\"></script>\n";
      $sScriptChangelog .= "<script type=\"text/javascript\">\n";
      $sScriptChangelog .= " var oDBReleaseNote = new DBViewReleaseNote(null, true); \n";
      $sScriptChangelog .= " oDBReleaseNote.show();                                  \n";
      $sScriptChangelog .= "</script>";

      echo $sScriptChangelog;

    }

  ?>
</html>