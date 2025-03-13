<?php

namespace App\Domain\Configuracao\Instituicao\Controller;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Configuracao\Instituicao\Model\DBConfig as Instituicao;
use App\Domain\Configuracao\Instituicao\Repository\InstituicaoRepository;
use ECidade\Configuracao\Instituicao\Repository\InstituicaoRepository as RepositoryInstituicao;

class InstituicaoController extends Controller
{
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(InstituicaoRepository $instituicaoRepository)
    {
        $this->repository = $instituicaoRepository;
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

    /**
     * Retorna uma instituição identificada pelo id passado.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new DBJsonResponse($this->repository->find($id));
    }

    /**
     * Salva o Departamento Principal da Instituição na tabela db_config
     * @param Request $request
     */
    public function configurarDepartamentoPrincipal(Request $request)
    {
        $repository = new RepositoryInstituicao();
        $instituicao = $repository->find($request->get('DB_instit'));
        $instituicao->setDescricaoDepartamentoAbreviado($request->get('descricaoAbreviada'));
        $instituicao->setCodigoDepartamentoPrincipal($request->get('departamento'));
        $repository->salvar($instituicao);

        return new DBJsonResponse([], 'Departamento salvo com sucesso!');
    }
}
