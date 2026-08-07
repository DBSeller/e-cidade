<?php

namespace App\Domain\Tributario\Arrecadacao\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegraEmissaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param $k00_tipo Indece da tabela arretipo
     *
     * @return array
     */
    public function rules($k48_sequencial = null)
    {
        $codbank = request("codbank");

        $rules =  [
            "k48_ammpix" => ["boolean"],
            "k48_sequencial" => [
                "required",
                "numeric",
                "exists:modcarnepadrao,k48_sequencial",
                Rule::unique("modcarnepadraopix")->ignore($k48_sequencial, "k48_sequencial")
            ],
            "codbank"      => ["required", "array", "min:1"],
            "codbank.*"    => [
                "required",
                "numeric",
                "exists:db_bancos_pix,db90_codban"
            ],
        ];
        
        if (is_null($codbank)) {
            array_shift($rules["codbank"]);
            array_shift($rules["codbank.*"]);
        }

        return $rules;
    }
}
