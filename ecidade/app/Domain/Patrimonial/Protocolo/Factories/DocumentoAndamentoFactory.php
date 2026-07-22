<?php

namespace App\Domain\Patrimonial\Protocolo\Factories;

use App\Domain\Patrimonial\Protocolo\Model\DocumentoAndamento;
use App\Domain\Patrimonial\Protocolo\Services\DocumentoAndamentoService;
use App\Domain\Patrimonial\Protocolo\Services\EmpenhoDocumentoService;
use EmpenhoFinanceiro;
use Exception;

class DocumentoAndamentoFactory
{

    /**
     * @param DocumentoAndamento $documentoAndamento
     * @return DocumentoAndamentoService
     * @throws Exception
     */
    public static function getService(DocumentoAndamento $documentoAndamento)
    {
        $tipo = $documentoAndamento->processo->tipoProcesso->p51_prottipodocumentoprocesso;

        switch ($tipo) {
            case '6':
                $empenho = new EmpenhoFinanceiro($documentoAndamento->p116_codigo_origem);
                return new EmpenhoDocumentoService($empenho);
            default:
                throw new Exception('Erro ao buscar Documento Service.');
        }
    }
}
