<?php
namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoConfiguracao;
use ECidade\Lib\Session\DefaultSession;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoConfiguracao\Update;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoConfiguracao\Sequencial;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoConfiguracao\Store;

class ComissaoConfiguracaoController extends Controller
{
    /**
     * @param Request $request
     * @return DBJsonResponse
     */
    public function index(Request $request)
    {
        if (!empty($request->comissao)) {
            return new DBJsonResponse(ComissaoConfiguracao::buscaPorComissao($request->comissao));
        } else {
            return new DBJsonResponse(ComissaoConfiguracao::all());
        }
    }

    /**
     * @param Store $request
     * @return DBJsonResponse
     */
    public function store(Store $request)
    {
        try {
            $comissaoConfiguracao = new ComissaoConfiguracao();
            $comissaoConfiguracao->setComissao($request->comissao);
            $comissaoConfiguracao->setFuncao($request->funcao);
            $comissaoConfiguracao->setTipoSessao($request->tiposessao);
            $comissaoConfiguracao->setRubrica($request->rubrica);
            $comissaoConfiguracao->setValor($request->valor);

            if ($comissaoConfiguracao->callSave()) {
                return new DBJsonResponse(
                    ["id" => $comissaoConfiguracao->getSequencial()],
                    "Configuração da Rubrica cadastrada com sucesso."
                );
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao cadastrar os dados da rubrica na comissão.",
                400
            );
        }
    }

    /**
     * @param Sequencial $request
     * @param ComissaoConfiguracao $comissaoConfiguracao
     * @return DBJsonResponse
     */
    public function show(Sequencial $request, ComissaoConfiguracao $comissaoConfiguracao)
    {
        try {
            if ($comissaoConfiguracao->find($request->id)) {
                return new DBJsonResponse(
                    $comissaoConfiguracao->find($request->id),
                    "Encontrado com sucesso."
                );
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao buscar as configurações da rubrica da comissão.",
                400
            );
        }
    }

    /**
     * @param Update $request
     * @param ComissaoConfiguracao $comissaoConfiguracao
     * @return DBJsonResponse
     */
    public function update(Update $request, ComissaoConfiguracao $comissaoConfiguracao)
    {
        try {
            $comissaoConfiguracao->setComissao($request->comissao);
            $comissaoConfiguracao->setFuncao($request->funcao);
            $comissaoConfiguracao->setTipoSessao($request->tiposessao);
            $comissaoConfiguracao->setRubrica($request->rubrica);
            $comissaoConfiguracao->setValor($request->valor);

            $valida = ComissaoConfiguracao::validaAlteracao(
                $request->id,
                $comissaoConfiguracao->getComissao(),
                $comissaoConfiguracao->getFuncao(),
                $comissaoConfiguracao->getTipoSessao()
            );

            if ($valida) {
                if ($comissaoConfiguracao->callUpdate($request->id)) {
                    return new DBJsonResponse([], "Alterado com sucesso.");
                }
            } else {
                $mensagem = "Comissão já possui a configuração cadastrada. "
                    . "É necessário alterar alguma das seguintes informações:\n Rubrica;\n Tipo de Sessão ou \nFunção.";
                return new DBJsonResponse(
                    ["exception" => $mensagem],
                    "Comissão já possui a configuração cadastrada.",
                    400
                );
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(["exception" => $e->getMessage()], "Ocorreu algum erro.", 400);
        }
    }

    /**
     * @param Sequencial $request
     * @param ComissaoConfiguracao $comissaoConfiguracao
     * @return DBJsonResponse
     */
    public function destroy(Sequencial $request, ComissaoConfiguracao $comissaoConfiguracao)
    {
        try {
            if ($comissaoConfiguracao->destroy($request->id)) {
                return new DBJsonResponse([], "Deletado com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(["exception" => $e->getMessage()], "Ocorreu algum erro.", 400);
        }
    }
}
