<?php

namespace App\Domain\Patrimonial\Protocolo\Controller\Processo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Protocolo\Model\Processo\Processo;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\ProcessoRepository;

class ProcessoController extends Controller
{
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(ProcessoRepository $processoRepository)
    {
        $this->repository = $processoRepository;
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
