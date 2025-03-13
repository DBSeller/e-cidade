<?php


namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Orcamento\Models\Recurso;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class ManutencaoFonteRecurso
 * @package App\Domain\Financeiro\Contabilidade\Repositories
 */
abstract class ManutencaoFonteRecursoService
{
    /**
     * @var FormRequest
     */
    protected $request;

    /**
     * @var array
     */
    protected $recursos = [];

    /**
     * @param $recurso
     * @return Recurso[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getRecurso($recurso)
    {
        $this->recursos = Recurso::with('complemento')->where('o15_recurso', $recurso)
            ->orderBy('o15_complemento')->get();
        return $this->recursos;
    }

    /**
     * @return Recurso[]
     * @throws Exception
     */
    public function buscarRecursos()
    {
        if (!empty($this->recursos)) {
            return $this->recursos;
        }

        $recurso = $this->request->get('recurso');
        if (!empty($recurso)) {
            return $this->getRecurso($recurso);
        }

        $ano = $this->request->get('DB_anousu');
        $idEmpenho = $this->request->get('idEmpenho');
        if (!empty($idEmpenho)) {
            $empenho = new \EmpenhoFinanceiro($idEmpenho);

            if ($empenho->isRP($ano)) {
                $recurso = DB::table('empresto')
                    ->select('o15_recurso')
                    ->join('orctiporec', 'orctiporec.o15_codigo', '=', 'empresto.e91_recurso')
                    ->where('e91_numemp', '=', $idEmpenho)
                    ->where('e91_anousu', '<=', $ano)
                    ->orderBy('e91_anousu', 'desc')
                    ->first();

                return $this->getRecurso($recurso->o15_recurso);
            }

            return $this->getRecurso($empenho->getDotacao()->getDadosRecurso()->getRecurso());
        }

        $codigoReceita = $this->request->get('codigoReceita');

        if (!empty($codigoReceita)) {
            $recurso = DB::table('conlancamrec')
                ->join('conlancam', 'conlancam.c70_codlan', '=', 'conlancamrec.c74_codlan')
                ->join('orcreceita', function ($join) {
                    $join->on('orcreceita.o70_anousu', '=', 'conlancamrec.c74_anousu')
                        ->on('orcreceita.o70_codrec', '=', 'conlancamrec.c74_codrec');
                })
                ->join('orctiporec', 'orctiporec.o15_codigo', '=', 'orcreceita.o70_codigo')
                ->select('o15_recurso')
                ->where('o70_codrec', $codigoReceita)
                ->where('o70_anousu', $ano)
                ->first();

            if (is_null($recurso)) {
                throw new Exception("Não foi encontrado lançamentos para receita selecionada.");
            }
            return $this->getRecurso($recurso->o15_recurso);
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function buscarComplementos()
    {
        $this->buscarRecursos();

        return $this->recursos->map(function (Recurso $recurso) {
            return $recurso->getComplemento();
        });
    }
    /**
     * @param FormRequest $request
     */
    public function setRequest(FormRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Atualiza a fonte de recursos do
     * @throws Exception
     */
    public function atualizaRecursos()
    {
        foreach ($this->request->get('itens') as $item) {
            $item = str_replace('\"', '"', $item);
            if (strpos($item, '\"')) {
                $item = str_replace('\"', '"', $item);
            }

            $item = \JSON::create()->parse($item);
            $recursos = array_filter($item->recursos, function ($recurso) use ($item) {
                return $item->complemento == $recurso->complemento->codigo;
            });

            if (empty($recursos)) {
                throw new Exception("Erro ao identificar o recurso.", 406);
            }

            $recurso = array_shift($recursos);
            $this->atualizarRecurso($item->codigo, $recurso->o15_codigo);
        }
    }

    /**
     * @param integer $codigo código do empenho ou lançamento depende da fonte
     * @param string $fonteRecuso cógido da fonte de recurso
     * @return mixed
     */
    abstract public function atualizarRecurso($codigo, $fonteRecuso);
}
