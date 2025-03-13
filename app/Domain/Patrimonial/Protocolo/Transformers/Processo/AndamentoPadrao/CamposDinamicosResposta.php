<?php

namespace App\Domain\Patrimonial\Protocolo\Transformers\Processo\AndamentoPadrao;

use App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao
\CamposDinamicosResposta as CamposDinamicosRespostaModel;

use League\Fractal\TransformerAbstract;

class CamposDinamicosResposta extends TransformerAbstract
{
    public function transform($respostas)
    {

        if ($respostas === null) {
            return $respostas;
        }

        if ($respostas instanceof CamposDinamicosRespostaModel) {
            return [
                'codigo'           => $respostas->p111_sequencial,
                'codandam'         => $respostas->p111_codandam,
                'camposandpadrao'  => $respostas->p111_camposandpadrao,
                'resposta'         => $respostas->p111_resposta,
                'campo' => [
                    'codcam'           => $respostas->codcam,
                    'nomecam'          => !empty($respostas->nomecam)   ? $respostas->nomecam   : '',
                    'label'            => !empty($respostas->rotulo)    ? $respostas->rotulo    : '',
                    'descricao'        => !empty($respostas->descricao) ? $respostas->descricao : '',
                    'tipo'             => !empty($respostas->conteudo)  ? $respostas->conteudo  : 'text',
                ],
            ];
        } else {
            return collect($respostas)->map(function ($resposta) {

                return [
                    'codigo'           => $resposta->p111_sequencial,
                    'codandam'         => $resposta->p111_codandam,
                    'camposandpadrao'  => $resposta->p111_camposandpadrao,
                    'resposta'         => $resposta->p111_resposta,
                    'campo' => [
                        'codcam'           => $resposta->codcam,
                        'nomecam'          => !empty($resposta->nomecam)   ? $resposta->nomecam   : '',
                        'label'            => !empty($resposta->rotulo)    ? $resposta->rotulo    : '',
                        'descricao'        => !empty($resposta->descricao) ? $resposta->descricao : '',
                        'tipo'             => !empty($resposta->conteudo)  ? $resposta->conteudo  : 'text',
                    ],
                ];
            });
        }
    }
}
