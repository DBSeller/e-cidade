<?php
namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\PermissaoComissao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Comissao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoPermissao\Sequencial;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoPermissao\Store;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoPermissao\Update;
use ECidade\Lib\Session\DefaultSession;

class PermissaoComissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (PermissaoComissao::comissaoExists($request->comissao)) {
            $servidores = PermissaoComissao::select([
                'rh251_sequencial',
                'rh251_comissao as comissao',
                'rh251_matricula',
                'rh242_descricao as descricao',
                'rh01_numcgm as cgmcod',
                'z01_nome as nome',
            ])
            ->join("pessoal.jetomcomissao", "rh242_sequencial", "rh251_comissao")
            ->join("pessoal.rhpessoal", "rh251_matricula", "rh01_regist")
            ->join("protocolo.cgm", "rh01_numcgm", "z01_numcgm")
            ->where('rh251_comissao', $request->comissao)
            ->orderBy('z01_nome')
            ->get();

            // dd($servidores);

            return new DBJsonResponse(
                $servidores,
                "Permissões encontradas",
                200
            );
        }

        $comissao = Comissao::select([
            'rh242_descricao as descricao',
            'rh242_sequencial as comissao'
        ])->find($request->comissao);

        return new DBJsonResponse([$comissao], 'Permissões não foram encontradas', 406);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, PermissaoComissao $permComissao)
    {
        try {
            $rescisao = \DB::table('rhpesrescisao')
                ->select(['rh05_recis'])
                    ->join('rhpessoalmov', 'rh05_seqpes', 'rh02_seqpes')
                    ->where('rh02_regist', $request->matricula)
                    ->limit(1)
            ->first();

            if ($rescisao) {
                return new DBJsonResponse(
                    [],
                    "Matricula com rescisão, não poderá ser cadastrada.",
                    406
                );
            }

            $matricula = $request->matricula; // fk
            $comissao = $request->comissao; // fk


            $permComissao->setMatricula($matricula);
            $permComissao->setComissao($comissao);


            if ($permComissao->callSave()) {
                return new DBJsonResponse(
                    ["id" => $permComissao->getSequencial()],
                    "Inclusão da permissão da matricula realizada com sucesso."
                );
            } else {
                return new DBJsonResponse([], "Erro ao incluir a permissão.", 400);
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ['exception' => $e->getMessage()],
                "Erro ao incluir a permissão",
                400
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\RecursosHumanos\Pessoal\Model\Jetom\PermissaoComissao  $permissaoComissao
     * @return \Illuminate\Http\Response
     */
    public function show(PermissaoComissao $permissaoComissao, Sequencial $request)
    {

        if ($permissao = $permissaoComissao->find($request->id)) {
            return new DBJsonResponse($permissao, []);
        } else {
            return new DBJsonResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\RecursosHumanos\Pessoal\Model\Jetom\PermissaoComissao  $permissaoComissao
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request)
    {
        try {
            $id_permissao = $request->id;

            $permissaoComissao = new PermissaoComissao();
            $permissaoComissao = $permissaoComissao->find($id_permissao);

            $permissaoComissao->setComissao($request->comissao);
            $permissaoComissao->setMatricula($request->matricula);

            if ($permissaoComissao->update()) {
                return new DBJsonResponse([], "Permissão de comissão alterada com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao alterar a permissão de comissão.",
                400
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\RecursosHumanos\Pessoal\Model\Jetom\PermissaoComissao  $permissaoComissao
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermissaoComissao $permissaoComissao, Sequencial $request)
    {
        try {
            $id_permissao = $request->id;

            if ($permissaoComissao->destroy($id_permissao)) {
                return new DBJsonResponse([], "Permissão de comissão excluida com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao excluir a permissão de comissão.",
                400
            );
        }
    }
}
