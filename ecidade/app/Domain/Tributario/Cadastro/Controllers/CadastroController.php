<?php

namespace App\Domain\Tributario\Cadastro\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\Cadastro\Requests\ImovelRequest;
use App\Domain\Tributario\Cadastro\Requests\LogradouroRequest;
use App\Domain\Tributario\Cadastro\Services\CadastroService;
use App\Http\Controllers\Controller;

class CadastroController extends Controller
{
    private $cadastroService;

    public function __construct(CadastroService $cadastroService)
    {
        $this->cadastroService = $cadastroService;
    }

    public function dadosImovel(ImovelRequest $request)
    {
        $oIptubase = $this->cadastroService->getDadosRegImovByMatric($request->matricula);

        return new DBJsonResponse($oIptubase);
    }

    public function getSetorRegImoveis()
    {
        $aSetor = $this->cadastroService->getSetorRegImoveis();

        return new DBJsonResponse($aSetor);
    }

    public function getLocalidadeRural()
    {
        $aLocalidade = $this->cadastroService->getLocalidadeRural();

        return new DBJsonResponse($aLocalidade);
    }

    public function getBairros()
    {
        $aBairros = $this->cadastroService->getBairros();

        return new DBJsonResponse($aBairros);
    }

    public function getLogradouros(LogradouroRequest $request)
    {
        $iCep = $request->query("cep", null);
        $iBairro = $request->query("bairro", null);

        $aLogradouros = $this->cadastroService->getLogradouros($iCep, $iBairro);

        return new DBJsonResponse($aLogradouros);
    }
}
