<?php

namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoServidor;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoFuncao;

use ECidade\Lib\Session\DefaultSession;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoServidor\Update;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoServidor\Sequencial;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoServidor\Store;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoServidor\Limitador;
use Exception;

class ComissaoServidorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->comissao)) {
            $comissaoServidor = new ComissaoServidor();
            $comissaoServidor->setComissao($request->comissao);
            return new DBJsonResponse($comissaoServidor->buscaDadosServidor());
        } else {
            return new DBJsonResponse(ComissaoServidor::all());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, ComissaoServidor $comissaoServidor)
    {
        try {
            $comissaoServidor->setComissao($request->comissao);
            $comissaoServidor->setMatricula($request->matricula);
            $comissaoServidor->setMesinicio($request->mesinicio);
            $comissaoServidor->setMesfim($request->mesfim);
            $comissaoServidor->setAnoinicio($request->anoinicio);
            $comissaoServidor->setAnofim($request->anofim);
            $comissaoServidor->setAtivo(true);
            $comissaoServidor->setAtonomeacao($request->atonomeacao);
            $comissaoServidor->setDocumento($request->documento);
            $comissaoServidor->setFuncao($request->funcao);

            if ($comissaoServidor->verificaCargo($request->comissao, $request->matricula, true)) {
                return new DBJsonResponse(
                    [],
                    "Servidor com outra função ativa.",
                    406
                );
            };

            if ($comissaoServidor->callSave()) {
                return new DBJsonResponse(
                    ["id" => $comissaoServidor->getSequencial()],
                    "Cadastrado com sucesso."
                );
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro.",
                400
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ComissaoServidor $comissaoServidor
     * @return \Illuminate\Http\Response
     */
    public function show(Sequencial $request, ComissaoServidor $comissaoServidor)
    {
        try {
            if ($comissaoServidor->find($request->id)) {
                return new DBJsonResponse([$comissaoServidor->find($request->id)], "Encontrado com sucesso.");
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                "message"   => "Ocorreu algum erro.",
                "exception" => $e->getMessage()
                ]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\ComissaoServidor    $comissaoServidor
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, ComissaoServidor $comissaoServidor)
    {
        try {
            $comissaoServidor->setComissao($request->comissao);
            $comissaoServidor->setMatricula($request->matricula);
            $comissaoServidor->setMesinicio($request->mesinicio);
            $comissaoServidor->setMesfim($request->mesfim);
            $comissaoServidor->setAnoinicio($request->anoinicio);
            $comissaoServidor->setAnofim($request->anofim);
            $comissaoServidor->setAtivo($request->ativo);
            $comissaoServidor->setAtonomeacao($request->atonomeacao);
            $comissaoServidor->setDocumento($request->documento);
            $comissaoServidor->setFuncao($request->funcao);
            if ($comissaoServidor->verificaCargo($request->comissao, $request->matricula, true, $request->id)) {
                return new DBJsonResponse(
                    [],
                    "Servidor com outra função ativa.",
                    406
                );
            };

            if ($comissaoServidor->callUpdate($request->id)) {
                return new DBJsonResponse([], "Alterado com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(["exception" => $e->getMessage()], "Ocorreu algum erro.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ComissaoServidor $comissaoServidor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sequencial $request, ComissaoServidor $comissaoServidor)
    {
        try {
            if ($comissaoServidor->destroy($request->id)) {
                return new DBJsonResponse([], "Deletado com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(["exception" => $e->getMessage()], "Ocorreu algum erro.");
        }
    }
}
