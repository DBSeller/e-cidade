<?php
namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Financeiro\Contabilidade\Services\BalancetesMensaisService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use stdClass;
use DBDate;

/**
 * Class BalancetesMensaisController
 * @package App\Domain\Financeiro\Contabilidade\Controllers
 */
class BalancetesMensaisController extends Controller
{
    private $service;
    private $filtros;

    public function __construct(BalancetesMensaisService $service)
    {
        $this->service = $service;
    }


    public function processarAnexo1(Request $request)
    {
        $this->parseFiltros($request);
        $response = $this->service->balanceteMensalAnexo1($this->filtros);
        return new DBJsonResponse($response);
    }


    private function parseFiltros(Request $request)
    {
        $rule = [

            "competencia" => ['required', 'int']
        ];

        $mensagens = [
          //  "instituicoes.*" => "Selecione uma Instituição Válida."
        ];

        validaRequest($request->all(), $rule, $mensagens);

        $ano = $request->DB_anousu;
        $mes = $request->competencia;
        $ultimo_dia = date("t", mktime(0, 0, 0, $mes, '01', $ano));

        $dataInicial = date("Y-m-d", mktime(0, 0, 0, $mes, '01', $ano));
        $dataFinal = date("Y-m-d", mktime(0, 0, 0, $mes, $ultimo_dia, $ano));

        $filtros = new stdClass();
        $filtros->dataInicial = DBDate::converter($dataInicial);
        $filtros->dataFinal = DBDate::converter($dataFinal);
        $filtros->instituicoes = $request->instituicoes;
        $filtros->ano = $request->DB_anousu;
        $filtros->mes = $mes;
        $filtros->DB_instit = $request->DB_instit;

        //dd($filtros);
        $this->filtros =  $filtros;
    }
}
