<?php

namespace App\Domain\Configuracao\DocumentosAssinatura\Transform;

use App\Domain\Configuracao\Helpers\StorageHelper;
use League\Fractal\TransformerAbstract;

class DocumentosAssinarTransformer extends TransformerAbstract
{
    public function transform($documentos)
    {
        $configStorage = StorageHelper::getStorageConfig();

        return collect($documentos)->map(function ($doc) use ($configStorage) {

            if ($doc->visibility == 'public') {
                $doc->url = $configStorage->url . '/' . $doc->url ;
            }

            $metadata = null;

            if (!empty($doc->metadata) && !empty($doc->metadata->data)) {
                $metadata = $doc->metadata->data;
            }

            return (object) [
                'file_id'        => !empty($doc->id) ? $doc->id : null,
                'file_name'      => !empty($doc->name) ? $doc->name : null,
                'file_metadata'  => $metadata,
                'file_type'      => !empty($doc->mime_type) ? $doc->mime_type : null,
                'file_url'       => !empty($doc->url) && $doc->visibility == 'public' ? $doc->url : null,
                'file_previous_version' => !empty($doc->file_father) ? $doc->file_father : null
            ];
        });
    }
}
