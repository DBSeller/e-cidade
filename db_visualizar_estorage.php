<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

$id = trim($_GET['id']);
if (empty($id)) {
    die('Arquivo não encontrado');
}
$arquivo = \App\Domain\Configuracao\Helpers\StorageHelper::downloadArquivo($id);
header("Content-Type: application/pdf");
echo file_get_contents($arquivo);
