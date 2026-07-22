<?php
namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Funcao;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Funcao\Store as StoreRequest;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Funcao\Sequencial as SequencialRequest;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Funcao\Update as UpdateRequest;

use ECidade\Core\Request\Request;
use ECidade\Lib\Session\DefaultSession;
use App\Http\Controllers\Controller;
use Psr\Http\Message\ServerRequestInterface;

class FuncaoController extends Controller
{
    /**
     * @param ServerRequestInterface|null $request
     * @return DBJsonResponse
     */
    public function index(ServerRequestInterface $request = null)
    {
        if (!empty($request)) {
            if (isset($request->getQueryParams()['instituicao']) && !empty($request->getQueryParams()['instituicao'])) {
                DefaultSession::getInstance()->set('DB_instit', $request->getQueryParams()['instituicao']);
            }
        }
        return new DBJsonResponse(Funcao::all());
    }

    /**
     * @param SequencialRequest $request
     * @return DBJsonResponse
     */
    public function show(SequencialRequest $request)
    {
        return new DBJsonResponse([Funcao::getFuncao($request->id)]);
    }

    /**
     * @param SequencialRequest $request
     * @return DBJsonResponse
     */
    public function delete(SequencialRequest $request)
    {
        $funcao = Funcao::getFuncao($request->id);
        if (!empty($funcao)) {
            try {
                if ($funcao->delete()) {
                    return new DBJsonResponse([], "Função excluida com sucesso.");
                }
                return new DBJsonResponse(
                    [],
                    "Houve um erro ao deletar a Função. Verifique se a função está sendo utilizada em alguma comissão.",
                    400
                );
            } catch (\Exception $e) {
                return new DBJsonResponse(
                    ['exception' => $e->getMessage()],
                    "Houve um erro ao deletar a Função. Verifique se a função está sendo utilizada em alguma comissão.",
                    400
                );
            }
        } else {
            return new DBJsonResponse([], "Função não encontrada.", 406);
        }
    }

    /**
     * @param StoreRequest $request
     * @return DBJsonResponse
     */
    public function store(StoreRequest $request)
    {
        $funcao = new Funcao();
        $instituicao = (int)DefaultSession::getInstance()->get('DB_instit');
        $descricao = trim($request->descricao);

        if (!empty($request->instituicao)) {
            $instituicao = $request->instituicao;
        }

        $funcao->setDescricao($descricao);
        $funcao->setInstituicao($instituicao);
        try {
            if ($funcao->save()) {
                return new DBJsonResponse(
                    ["id" => $funcao->getSequencial()],
                    "Inclusão da função realizada com sucesso."
                );
            } else {
                return new DBJsonResponse([], "Erro ao incluir a função.", 400);
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ['exception' => $e->getMessage()],
                "Erro ao incluir a função",
                400
            );
        }
    }

    /**
     * @param UpdateRequest $request
     * @return DBJsonResponse
     */
    public function edit(UpdateRequest $request)
    {
        // a instituicao nao pode ser alterada
        // a unica coisa que pode ser alterada é a descrição desde que não tenha outra na mesma instituicao
        $funcao = Funcao::getFuncao($request->id);
        if (empty($funcao)) {
            return new DBJsonResponse([], 'Função não encontrada na instituição.', 406);
        }
        $funcao->setDescricao(trim($request->descricao));
        if ($funcao->getInstituicao() != $request->instituicao) {
            return new DBJsonResponse([], 'Função informada não pertence a instituição.', 406);
        }

        try {
            if ($funcao->edit()) {
                return new DBJsonResponse([], "Função atualizada com sucesso.");
            } else {
                return new DBJsonResponse([], "Erro ao atualizar a função.", 400);
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ['exception' => $e->getMessage()],
                "Erro ao atualizar a função",
                400
            );
        }
    }
}
