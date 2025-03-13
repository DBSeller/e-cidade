<?php

use App\Domain\Configuracao\Departamento\Factories\DepartamentoFactory;
use App\Domain\Patrimonial\Protocolo\Factories\BuscaCgmLogadoFactory;
use Illuminate\Support\Facades\Validator;

if (!function_exists('valorCodigoBarras')) {
    /**
     * Função que formata o valor para ser adicionado ao codigo de barras
     * @param $fValor
     * @return float
     */
    function valorCodigoBarras($fValor)
    {
        $fValor = number_format($fValor, 2, "", ".");
        $fValor = str_pad($fValor, 11, "0", STR_PAD_LEFT);
        $fValor = str_replace('.', '', $fValor);
        return db_formatar($fValor, 's', '0', 11, 'e');
    }
}

if (!function_exists('convertToPdf')) {
    /**
     * Função que converte um arquivo (SXW, DOC, DOCX) para PDF
     * @param $sLocalArquivo
     * @param string $sCaminhoSaida
     * @return float
     */
    function convertToPdf($sLocalArquivo, $sCaminhoSaida = ".")
    {
        ob_start();
            system(
                "export HOME=/tmp && soffice --convert-to pdf {$sLocalArquivo} --headless --outdir {$sCaminhoSaida}"
            );
        ob_end_clean();
    }
}

if (!function_exists('formataValorMonetario')) {
    /**
     * Função que adiciona as casas decimais para valor monetário
     * @param $fValor
     * @return float
     */
    function formataValorMonetario($fValor)
    {
        if (!is_numeric($fValor)) {
             return 0;
        }

        return number_format(floatval($fValor), 2, ",", ".");
    }
}

if (!function_exists('parseStringJson')) {
    /**
     * Realiza o parse de uma string json para um array ou objeto stdClass
     * @param $stringJson
     * @return mixed|stdClass|array
     */
    function parseStringJson($stringJson)
    {
        $filtro = str_replace('\"', '"', $stringJson);
        return \JSON::create()->parse($filtro);
    }
}

if (!function_exists('validaDepartamentoLogado')) {
    /**
     * Valida o departamento logado de acordo com o tipo do departamento passado por parametro
     * @param $tipo
     * @return boolean
     */
    function validaDepartamentoLogado($tipo)
    {
        $validador = DepartamentoFactory::getValidador($tipo);
        
        return $validador->validar();
    }
}

if (!function_exists('buscaCgmLogado')) {
    /**
     * Busca o cgm logado de acordo com o tipo passado por parametro
     * @param $tipo
     * @return mixed
     */
    function buscaCgmLogado($tipo)
    {
        $service = BuscaCgmLogadoFactory::getService($tipo);

        return $service->getCgm();
    }
}

if (!function_exists('validaRequest')) {
    /**
     * valida se o array de campos está conforme o array com as regras passados por parametro,
     * caso falhe toca a excessão com a devida mensagem
     * @param array $campos
     * @param array $regras
     * @param array $mensagens
     * @throws Exception
     */
    function validaRequest(array $campos, array $regras, array $mensagens = [])
    {
        $validator = Validator::make($campos, $regras, $mensagens);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $mensagem = $errors->all()[0];

            throw new Exception($mensagem, 406);
        }
    }
}
