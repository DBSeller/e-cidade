<?php


namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Services\Relatorios\EvolucaoReceitaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvolucaoReceitaController extends Controller
{
    public function demonstrativoEvolucaoReceita(Request $request)
    {
        $rule = [
            "mes" => ['required'],
            "instituicoes" =>  ['required']
        ];

        $mensagens = [
            "instituicoes" => "Pelo menos uma Instituição deve ser selecionada.",
            "mes.required" => "O Mês deve ser selecionado."
        ];


        validaRequest($request->all(), $rule, $mensagens);
        
        $service = new EvolucaoReceitaService();
        $service->setFiltrosRequest($request->all());
        $files = $service->emitir();
        return new DBJsonResponse($files, 'Demonstrativo da Evolução da Receita.');
    }
}
