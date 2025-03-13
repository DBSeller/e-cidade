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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification('libs/db_utils.php'));

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta charset="iso-8859-1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css"/>
  <link rel="stylesheet" type="text/css" href="estilos.css">
  <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
  <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
  <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>

<div id="problemas-paciente"></div>

<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script rel="script" type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script rel="script" type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script rel="script" type="text/javascript" src='scripts/classes/saude/ambulatorial/ViewProblemasPaciente.js'></script>
<script>
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

const ProblemasPaciente = new ViewProblemasPaciente(document.getElementById('problemas-paciente'), false, false);
ProblemasPaciente.show(urlParams.get('z01_i_cgsund'));
</script>
</body>
</html>