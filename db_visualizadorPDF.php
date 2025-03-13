<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
  </head>
  <body>
  <div>
    <iframe src="<?php echo $_GET['documentPath'] ?>" id="iframeVisualizarDocumento" name="iframeVisualizarDocumento" style="width:100%; height:100%;"></iframe>
  </div>
  </body>
</html>