<?php
use ECidade\Patrimonial\Protocolo\Servicos\TipoDocumentoProcessoService;
use ECidade\Patrimonial\Protocolo\Repositorio\TipoDocumentoProcessoRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oParam = JSON::create()->parse(str_replace('\\', '', $_POST['json']));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->erro = false;

$service = new TipoDocumentoProcessoService(
    new TipoDocumentoProcessoRepository(new \cl_prottipodocumentoprocesso)
);

try {
    db_inicio_transacao();

    switch ($oParam->exec) {
        case 'buscar':
            $tiposDocumentoProcesso = $service->getAll();

            $oRetorno->tiposDocumentoProcesso = array();
            $length = count($tiposDocumentoProcesso);
            for ($i = 0; $i < $length; $i++) {
                $oRetorno->tiposDocumentoProcesso[$i] = $tiposDocumentoProcesso[$i]->toArray();
            }

        break;

        case 'salvar':
            $parametros = new stdClass();
            $parametros->id = $oParam->codigoTipoDocumento;
            $parametros->descricao = $oParam->descricaoTipoDocumento;
            $parametros->sigla = $oParam->siglaTipoDocumento;

            $tipoDocumentoProcesso = $service->save($parametros);

            $oRetorno->tipoDocumentoProcesso = $tipoDocumentoProcesso->toArray();

        break;

        case 'excluir':
            $service->remove($oParam->codigoTipoDocumento);

        break;
    }



} catch (Exception $e) {
    db_fim_transacao(true);
    $oRetorno->status = 2;
    $oRetorno->erro = true;
    $oRetorno->message = urlencode($e->getMessage());
}

db_fim_transacao();

echo JSON::create()->stringify($oRetorno);
