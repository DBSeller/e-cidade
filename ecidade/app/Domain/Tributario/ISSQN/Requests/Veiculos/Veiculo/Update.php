<?php
namespace App\Domain\Tributario\ISSQN\Requests\Veiculos\Veiculo;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Requests\BaseFormRequest;

class Update extends BaseFormRequest
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
            'q172_sequencial'    => 'required|filled|integer|exists:issveiculo',
            'q172_datacadastro'  => 'required|filled|date',
            'q172_issbase'       => 'required|filled|integer',
            'q172_tipo'          => 'nullable|integer',
            'q172_marca'         => 'nullable|integer',
            'q172_modelo'        => 'nullable|integer',
            'q172_procedencia'   => 'nullable|integer',
            'q172_categoria'     => 'nullable|integer',
            'q172_chassi'        => 'nullable|string',
            'q172_renavam'       => 'nullable|string',
            'q172_placa'         => 'nullable|string',
            'q172_potencia'      => 'nullable|string',
            'q172_capacidade'    => 'nullable|integer',
            'q172_anofabricacao' => 'nullable|integer',
            'q172_anomodelo'     => 'nullable|integer',
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

            "q172_sequencial.required" => utf8_encode("Sequencial não informado."),
            "q172_sequencial.filled"   => utf8_encode("Sequencial informado está vazio."),
            "q172_sequencial.integer"  => utf8_encode("Sequencial inválido."),
            "q172_sequencial.exists"   => utf8_encode("Nenhum registro para o código informado."),

            "q172_datacadastro.required" => utf8_encode("Data de cadastro não informada."),
            "q172_datacadastro.filled"   => utf8_encode("Data de cadastro vazia."),
            "q172_datacadastro.date"     => utf8_encode("Data de cadastro inválida."),

            "q172_issbase.required"        => utf8_encode("Inscrição não informada."),
            "q172_issbase.filled"          => utf8_encode("Inscrição informada está vazia."),
            "q172_issbase.integer"         => utf8_encode("Inscrição inválida."),

            "q172_tipo.integer"          => utf8_encode("Tipo inválido."),

            "q172_marca.integer"         => utf8_encode("Marca inválida."),

            "q172_modelo.integer"        => utf8_encode("Modelo inválido."),

            "q172_procedencia.integer"   => utf8_encode("Procedencia inválida."),

            "q172_categoria.integer"     => utf8_encode("Categoria inválida."),

            "q172_chassi.string"        => utf8_encode("Chassi inválido."),

            "q172_renavam.string"       => utf8_encode("Renavan inválido."),

            "q172_placa.string"         => utf8_encode("Placa inválida."),

            "q172_potencia.string"      => utf8_encode("Potencia inválida."),

            "q172_capacidade.integer"    => utf8_encode("Capacidade inválida."),

            "q172_anofabricacao.integer" => utf8_encode("Ano de Fabricação inválido."),

            "q172_anomodelo.integer"     => utf8_encode("Ano modelo inválido."),


        ];
    }
}
