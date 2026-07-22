<?php


namespace App\Domain\Financeiro\Orcamento\Services;

use App\Domain\Financeiro\Orcamento\Factories\CodigoRecurso;
use App\Domain\Financeiro\Orcamento\Models\Complemento;
use App\Domain\Financeiro\Orcamento\Models\FonteRecurso;
use App\Domain\Financeiro\Orcamento\Models\Recurso;
use App\Domain\Financeiro\Orcamento\Models\ValorEstrutural;
use App\Domain\Financeiro\Orcamento\Repositories\RecursoRepository;
use App\Domain\Financeiro\Orcamento\Repositories\ValorEstruturalRepository;
use App\Domain\Financeiro\Orcamento\Requests\Cadastro\RecursoExcluirAntes2022Request;
use App\Domain\Financeiro\Orcamento\Requests\Cadastro\RecursoSalvarAntes2022Request;
use ECidade\Financeiro\Orcamento\Repository\FonteRecursoSiconfiRepository;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class RecursoService
 * @package App\Domain\Financeiro\Orcamento\Services
 */
class RecursoAntes2022Service extends RecursoService
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = Recurso::class;

    /**
     * @param RecursoSalvarAntes2022Request $recursoRequest
     * @throws Exception
     */
    public function salvarAntes2022(RecursoSalvarAntes2022Request $recursoRequest)
    {
        $valorEstrutural = ValorEstrutural::where('db121_db_estrutura', $recursoRequest->get('codigoEstrutural'))
            ->where('db121_estrutural', $recursoRequest->get('codigoTribunal'))
            ->first();

        if (is_null($valorEstrutural)) {
            $valorEstrutural = $this->salvarValorEstrutural($recursoRequest);
        }

        $recurso = $this->buildByRequest($recursoRequest);
        $recurso->setDbEstruturaValor($valorEstrutural);

        $this->validarDados($recurso);
        $recurso = $this->repository->persist($recurso);

        $this->salvarFonteRecurso(
            $recurso,
            $recurso->o15_codigosiconfi,
            $recurso->o15_recurso,
            2021,
            $recurso->o15_descr
        );
    }

    /**
     * Para manter compatibilidade com o cadastro de recurso, é incluso o registro na tabela db_estruturavalor
     * @param RecursoSalvarAntes2022Request $recursoRequest
     * @return ValorEstrutural
     * @throws Exception
     */
    protected function salvarValorEstrutural(RecursoSalvarAntes2022Request $recursoRequest)
    {
        return $this->criarValorEstrutural(
            $recursoRequest->get('codigoEstrutural'),
            $recursoRequest->get('codigoTribunal'),
            $recursoRequest->get('descricao')
        );
    }

    /**
     * Realiza as validações necessárias para o cadastro ou alteração de um recurso novo
     * @param Recurso $recurso
     * @return bool
     * @throws Exception
     */
    protected function validarDados(Recurso $recurso)
    {
        $recursoUtilizado = $this->recursoUtilizado($recurso);
        if ($recursoUtilizado) {
            $first = $recurso->alterouCamposCompoeRecurso($recurso)->first();

            if (!empty($first)) {
                throw new Exception("Alteração de dados não permitidos para uma fonte de recursos já utilizado.", 406);
            }

            return true;
        }
        $this->recursoJaCadastrado($recurso);

        return true;
    }

    /**
     * @param RecursoSalvarAntes2022Request $recursoRequest
     * @return Recurso
     */
    private function buildByRequest(RecursoSalvarAntes2022Request $recursoRequest)
    {
        $recurso = new Recurso();
        $recurso->o15_codigo = $recursoRequest->get('codigo');
        $recurso->o15_descr = $recursoRequest->get('descricao');
        $recurso->o15_codtri = $recursoRequest->get('codigoTribunal');
        $recurso->o15_finali = $recursoRequest->get('finalidade');
        $recurso->o15_tipo = $recursoRequest->get('tipoRecurso');

        $data = !empty($recursoRequest->get('dataLimite')) ? $recursoRequest->get('dataLimite') : null;

        $recurso->o15_datalimite = $data;
        $recurso->o15_codigosiconfi = $recursoRequest->get('codigoSiconf');
        $recurso->o15_loaidentificadoruso = $recursoRequest->get('loaIdentificacao');
        $recurso->o15_loatipo = $recursoRequest->get('loaTipo');
        $recurso->o15_loagrupo = $recursoRequest->get('loaGrupo');
        $recurso->o15_loaespecificacao = $recursoRequest->get('loaEspecificacao');
        $recurso->setComplemento(Complemento::find($recursoRequest->get('complemento')));
        $codigoRecurso = CodigoRecurso::build($recurso);
        $recurso->o15_recurso = $codigoRecurso;

        return $recurso;
    }


    /**
     * @param RecursoExcluirAntes2022Request $request
     * @return bool
     * @throws Exception
     */
    public function excluirAntes2022(RecursoExcluirAntes2022Request $request)
    {
        $recurso = Recurso::find($request->get('codigo'));
        if (is_null($recurso)) {
            throw new Exception("Código do Recurso informado não existe.", 406);
        }

        if ($this->recursoUtilizado($recurso)) {
            throw new Exception("Recurso informado não pode ser excluído pois já foi utilizado.", 406);
        }

        $repositoryFR = new FonteRecursoSiconfiRepository();
        $repositoryFR->scopeCodigoRecurso($recurso->o15_codigo)->deleteByScope();

        return $this->repository->excluir($recurso);
    }

    public function all()
    {
        return Recurso::all()->sortBy('o15_recurso');
    }
}
