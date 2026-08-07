<?php

namespace App\Domain\Configuracao\Menu\Requests;

use App\Http\Requests\DBFormRequest;

/**
 * @property integer $anoOrigem
 * @property integer $anoDestino
 */
class DuplicaPermissoesSaudeRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'anoOrigem' => ['required', 'integer'],
            'anoDestino' => ['required', 'integer']
        ];
    }
}
