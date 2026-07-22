<?php

namespace App\Domain\Tributario\ISSQN\Controller\AlvaraEventos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\MensagemPadrao;
use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\MensagemPadraoRepository;

class MensagemPadraoController extends Controller
{
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(
        MensagemPadraoRepository $mensagemPadraoRepository
    ) {
        $this->repository = $mensagemPadraoRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new DBJsonResponse($this->repository->findAll());
    }
}
