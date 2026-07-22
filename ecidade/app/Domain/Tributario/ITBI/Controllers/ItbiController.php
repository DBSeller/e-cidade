<?php

namespace App\Domain\Tributario\ITBI\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\ITBI\Requests\CaracterRuralRequest;
use App\Domain\Tributario\ITBI\Requests\FormaPagamentoTipoTransacaoRequest;
use App\Domain\Tributario\ITBI\Requests\TipoItbiRequest;
use App\Domain\Tributario\ITBI\Requests\TipoTaxaRequest;
use App\Domain\Tributario\ITBI\Requests\MatriculaRequest;
use App\Domain\Tributario\ITBI\Services\ItbiService;
use App\Http\Controllers\Controller;

class ItbiController extends Controller
{
    private $itbiService;

    public function __construct(ItbiService $itbiService)
    {
        $this->itbiService = $itbiService;
    }

    public function getTipos(TipoItbiRequest $request)
    {
        $aTipos = $this->itbiService->getTipos($request->tipoItbi);

        return new DBJsonResponse($aTipos);
    }

    public function getSituacao()
    {
        $aSituacao = $this->itbiService->getSituacao();

        return new DBJsonResponse($aSituacao);
    }

    public function getTipoTransacao()
    {
        $aTipoTransacao = $this->itbiService->getTipoTransacao();

        return new DBJsonResponse($aTipoTransacao);
    }

    public function getFormaPagamentoTipoTransacao(FormaPagamentoTipoTransacaoRequest $request)
    {
        $aFormaPagamento = $this->itbiService->getFormaPagamentoTipoTransacao($request->tipoTransacao);

        return new DBJsonResponse($aFormaPagamento);
    }

    public function getTaxasItbi(TipoTaxaRequest $request)
    {
        $aTaxas = $this->itbiService->getTaxasItbi($request->tipoTaxa, $request->matricula);

        return new DBJsonResponse($aTaxas);
    }

    public function getTipoBenfeitoria(TipoItbiRequest $request)
    {
        $aTipos = $this->itbiService->getTipoBenfeitoria($request->tipoItbi);

        return new DBJsonResponse($aTipos);
    }

    public function getEspecieBenfeitoria(TipoItbiRequest $request)
    {
        $aEspecies = $this->itbiService->getEspecieBenfeitoria($request->tipoItbi);

        return new DBJsonResponse($aEspecies);
    }

    public function getPadraoConstrutivoBenfeitoria()
    {
        $aPadraoConstrutivo = $this->itbiService->getPadraoConstrutivoBenfeitoria();

        return new DBJsonResponse($aPadraoConstrutivo);
    }

    public function getCaractImovelOrUtilImovel(CaracterRuralRequest $request)
    {
        $aCaractImovelOrUtilImovel = $this->itbiService->getCaractImovelOrUtilImovel($request->tipo);

        return new DBJsonResponse($aCaractImovelOrUtilImovel);
    }

    public function getTransmitentePrincipal(MatriculaRequest $request)
    {
        $oTrnasmitentePrincipal = $this->itbiService->getTransmitentesMatricula($request->matricula);

        return new DBJsonResponse($oTrnasmitentePrincipal);
    }

    public function getCartorios()
    {
        $aCartorios = $this->itbiService->getCartorios();

        return new DBJsonResponse($aCartorios);
    }

    public function getBenfeitoriasByMatric(MatriculaRequest $request)
    {
        $aBenfeitorias = $this->itbiService->getBenfeitoriasByMatric($request->matricula);

        return new DBJsonResponse($aBenfeitorias);
    }
}
