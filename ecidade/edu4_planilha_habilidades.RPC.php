<?php

use ECidade\Educacao\Secretaria\BNCC\Factory\ImportaPlanilhaHabilidadeFactory;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
$parametros = JSON::requestParameters();
$retorno = (object) ['erro' => false, 'mensagem' => ''];
try {
    db_inicio_transacao();
    switch ($parametros->acao) {
        case 'processar':
            if (!isset($parametros->tipo_planilha)) {
                throw new Exception('Informe tipo da planilha.');
            }
            if (!isset($parametros->planilha)) {
                throw new Exception('Informe o arquivo.');
            }

            if ($parametros->planilha->type !== 'text/csv') {
                throw new Exception('Formato do arquivo deve ser um csv.');
            }

            $dumpCsv = new \ECidade\File\Csv\Dumper\Dumper();
            $dumpCsv->setCsvControl(";", '"');
            $linhas = $dumpCsv->ler($parametros->planilha->tmp_name);

            $service = ImportaPlanilhaHabilidadeFactory::porTipo($parametros->tipo_planilha);
            $service->setLinhas($linhas);
            $service->processarLinhas();
            $retorno->arquivo_dump = $service->getFileDump();
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
