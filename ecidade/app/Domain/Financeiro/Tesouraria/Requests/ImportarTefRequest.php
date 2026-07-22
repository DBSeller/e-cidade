<?php


namespace App\Domain\Financeiro\Tesouraria\Requests;

use App\Http\Requests\DBFormRequest;
use Illuminate\Validation\Rule;

class ImportarTefRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'extension' => ['required', 'filled', Rule::in(['csv'])],
            'path' => ['required', 'string', 'filled'],
            'file' => ['required', 'string', 'filled'],
        ];
    }

    public function messages()
    {
        return [
            'extension.required' => 'O CSV deve ser informado.',
            'extension.in' => 'Só arquivos CSV são permitidos.',
            'path.required' => 'O arquivo deve ser informado.',
            'file.required' => 'O arquivo deve ser informado.',
            'file.string' => 'O arquivo deve ser uma string',
            'file.filled' => 'O arquivo deve ser preenchido',
        ];
    }
}
