<?php

namespace App\Domain\Configuracao\Usuario\Transform;

use League\Fractal\TransformerAbstract;

class AssinanteTransformer extends TransformerAbstract
{
    public function transform($assinantes)
    {
        return collect($assinantes)->map(function ($assinante) {

            $assinanteNome = '';
            if (!empty($assinante->name)) {
                $assinanteNome = $assinante->name;
            } elseif (!empty($assinante->nome)) {
                $assinanteNome = $assinante->nome;
            }

            $assinanteTipo = '';
            if (!empty($assinante->type)) {
                $assinanteTipo = $assinante->type;
            } elseif (!empty($assinante->tipo)) {
                $assinanteTipo = $assinante->tipo;
            }

            return (object) [
                'codigo'      => !empty($assinante->codigo) ? $assinante->codigo : null,
                'id_estorage' => !empty($assinante->id) ? $assinante->id : null,
                'cpf_cnpj'    => !empty($assinante->cpf_cnpj) ? $assinante->cpf_cnpj : '',
                'id_usuario'  => !empty($assinante->id_usuario) ? (int) $assinante->id_usuario : null,
                'nome'      => $assinanteNome,
                'permissao' => !empty($assinante->permissao) ? $assinante->permissao : 'ASSINANTE',
                'tipo'      => $assinanteTipo
            ];
        });
    }
}
