<?php
namespace App\Domain\Tributario\ISSQN\Requests\AlvaraEventos\AlvaraEvento;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Requests\BaseFormRequest;

class Store extends BaseFormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'q170_tipoalvara'        => 'required|filled|integer',
            'q170_ordemservico'      => 'required|filled|integer|unique:alvaraevento',
            'q170_certidaobombeiro'  => 'required|filled|string',
            'q170_dataemissao'       => 'nullable|date',
            'q170_estimativapublico' => 'nullable|integer',
            'q170_observacao'        => 'required|filled|string',
        ];
    }

    /**
     * @param array $errors
     * @return DBJsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $mensagem = utf8_decode($errors[array_keys($errors)[0]][0]);
        return new DBJsonResponse($errors, $mensagem, 406);
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [

            "q170_tipoalvara.required"       => utf8_encode("Tipo de alvará não informado."),
            "q170_tipoalvara.filled"         => utf8_encode("Tipo de alvará informado está vazia."),
            "q170_tipoalvara.integer"        => utf8_encode("Tipo de alvará inválido."),

            "q170_ordemservico.unique"       => utf8_encode("Já existe um alvara de evento para a ordem de serviço."),
            "q170_ordemservico.required"     => utf8_encode("Ordem de serviço não informada."),
            "q170_ordemservico.filled"       => utf8_encode("Ordem de serviço informada está vazia."),
            "q170_ordemservico.integer"      => utf8_encode("Ordem de serviço inválida."),

            "q170_certidaobombeiro.required" => utf8_encode("Certidão de bombeiros não informada."),
            "q170_certidaobombeiro.filled"   => utf8_encode("Certidão de bombeiros informada está vazia."),
            "q170_certidaobombeiro.string"   => utf8_encode("Certidão de bombeiros inválida."),

            "q170_dataemissao.date"          => utf8_encode("Data de emissao inválida."),

            "q170_estimativapublico.integer" => utf8_encode("Estimativa de público inválida."),

            "q170_observacao.required"       => utf8_encode("Observação não informada."),
            "q170_observacao.filled"         => utf8_encode("Observação informada está vazia."),
            "q170_observacao.string"         => utf8_encode("Observação inválida."),

        ];
    }
}
