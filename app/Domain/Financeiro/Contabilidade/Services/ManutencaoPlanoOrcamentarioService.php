<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Models\ConplanoOrcamento;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoOrcamentoAnalitica;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

abstract class ManutencaoPlanoOrcamentarioService
{
    /**
     * @var integer
     */
    protected $exercicio;
    /**
     * Array com contas sintéticas que não puderam ser excluídas
     * @var string[]
     */
    protected $logContasSinteticasComFilhos = [];

    protected $contasAnaliticas = [];
    protected $contasSinteticas = [];

    /**
     * Retorna as receitas conforme filtros informados
     * @param array $filtros
     * @return Collection
     */
    public function getContas(array $filtros)
    {
        return ConplanoOrcamento::query()
            ->select('*')
            ->orderBy('c60_estrut')
            ->when(!empty($filtros['estrutural']), function ($query) use ($filtros) {
                $estrutural = new Estrutural($filtros['estrutural']);
                $ateNivel = $estrutural->getEstruturalAteNivel();
                $query->where('c60_estrut', 'like', "{$ateNivel}%");
            })
            ->when(!empty($filtros['exercicio']), function ($query) use ($filtros) {
                $query->where('c60_anousu', '=', $filtros['exercicio']);
            })
            ->get();
    }

    public function excluirContas($contas)
    {
        $this->exercicio = $contas[0]->c60_anousu;
        $contas = array_reverse($contas);
        $this->separaContasSinteticas($contas);

        try {
            if (!empty($this->contasAnaliticas)) {
                $this->removerVinculos($this->contasAnaliticas);
            }

            $excluir = $this->validaContasSinteticasPodemExcluir($this->contasSinteticas);
            if (!empty($excluir)) {
                $this->removerVinculos($excluir);
            }
        } catch (QueryException $e) {
            throw new \Exception(
                "Erro ao excluir contas selecionadas. Entre em contato com o suporte para análise."
            );
        }
    }

    protected function removeVinculosContabilidade(array $codigosContas)
    {
        DB::table('contabilidade.conplanoorcamentoanalitica')
            ->where('c61_anousu', '>=', $this->exercicio)
            ->whereIn('c61_codcon', $codigosContas)
            ->delete();

        DB::table('contabilidade.avaliacaogruporespostaconta')
            ->where('c06_ano', '>=', $this->exercicio)
            ->whereIn('c06_conta', $codigosContas)
            ->delete();

        DB::table('empenho.classificacaocredoreselemento')
            ->where('cc32_anousu', '>=', $this->exercicio)
            ->whereIn('cc32_codcon', $codigosContas)
            ->delete();

        DB::table('contabilidade.conplanoconplanoorcamento')
            ->where('c72_anousu', '>=', $this->exercicio)
            ->whereIn('c72_conplanoorcamento', $codigosContas)
            ->delete();

        DB::table('contabilidade.conplanoorcamentoanalitica')
            ->where('c61_anousu', '>=', $this->exercicio)
            ->whereIn('c61_codcon', $codigosContas)
            ->delete();

        DB::table('contabilidade.conplanoorcamentoconta')
            ->where('c63_anousu', '>=', $this->exercicio)
            ->whereIn('c63_codcon', $codigosContas)
            ->delete();

        DB::table('contabilidade.conplanoorcamentocontabancaria')
            ->where('c56_anousu', '>=', $this->exercicio)
            ->whereIn('c56_codcon', $codigosContas)
            ->delete();

        DB::table('contabilidade.conplanoorcamentogrupo')
            ->where('c21_anousu', '>=', $this->exercicio)
            ->whereIn('c21_codcon', $codigosContas)
            ->delete();
        DB::table('orcamento.orccenarioeconomicoconplanoorcamento')
            ->where('o04_anousu', '>=', $this->exercicio)
            ->whereIn('o04_conplano', $codigosContas)
            ->delete();

        DB::table('contabilidade.conplanoorcamento')
            ->where('c60_anousu', '>=', $this->exercicio)
            ->whereIn('c60_codcon', $codigosContas)
            ->delete();
    }

    /**
     * @param array $contas
     */
    protected function removerVinculos(array $contas)
    {
        $this->removeVinculosPlanejamento($contas);
        $this->removeVinculosOrcamento($contas);
        $this->removeVinculosContabilidade($contas);
    }

    abstract protected function removeVinculosPlanejamento(array $contas);

    protected function removeVinculosOrcamento(array $codigosContas)
    {
        DB::table('orcamento.ppaestimativareceita')
            ->where('o06_anousu', '>=', $this->exercicio)
            ->whereIn('o06_codrec', $codigosContas)
            ->delete();

        DB::table('orcamento.orcfontesdes')
            ->where('o60_anousu', '>=', $this->exercicio)
            ->whereIn('o60_codfon', $codigosContas)
            ->delete();
    }
    /**
     * Só pode ser excluída a conta sintética quando todas as contas analítica abaixo dela tiverem sido excluídas
     * @param array $contasSinteticas
     * @return array
     */
    protected function validaContasSinteticasPodemExcluir(array $contasSinteticas)
    {
        $excluir = [];
        foreach ($contasSinteticas as $contaSintetica) {
            $estrutural = $this->getEstrutural($contaSintetica->c60_estrut);
            $ateNivel = $estrutural->getEstruturalAteNivel();
            $model = ConplanoOrcamento::query()
                ->where('c60_codcon', '!=', $contaSintetica->c60_codcon)
                ->where('c60_anousu', $contaSintetica->c60_anousu)
                ->whereRaw("c60_estrut like '$ateNivel%'")
                ->whereRaw("exists(
                  select 1 from conplanoorcamentoanalitica
                    join conplanoorcamento on (c60_codcon, c60_anousu) = (c61_codcon, c61_anousu)
                    where c60_estrut like '$ateNivel%'
                      and c60_anousu = {$contaSintetica->c60_anousu})
                ")
                ->get();

            if ($model->isEmpty()) {
                $excluir[] = $contaSintetica->c60_codcon;
            } else {
                $this->logContasSinteticasComFilhos[] = $ateNivel;
            }
        }

        return $excluir;
    }

    public function getLog()
    {
        if (empty($this->logContasSinteticasComFilhos)) {
            return null;
        }
        return sprintf(
            'As seguintes contas sintéticas não foram excluídas: %s pois existem contas analíticas dentro %s',
            implode(', ', $this->logContasSinteticasComFilhos),
            'deste grupo que não podem ser excluídas ou devem ser excluídas antes.'
        );
    }
    /**
     * @param $contas
     */
    protected function separaContasSinteticas($contas)
    {
        foreach ($contas as $conta) {
            $model = ConplanoOrcamentoAnalitica::query()
                ->where('c61_codcon', $conta->c60_codcon)
                ->where('c61_anousu', $conta->c60_anousu)
                ->first();
            if (is_null($model)) {
                $this->contasSinteticas[] = $conta;
            } else {
                $this->contasAnaliticas[] = $conta->c60_codcon;
            }
        }
    }

    /**
     * @param $estrutural
     * @return Estrutural|EstruturalReceita
     */
    private function getEstrutural($estrutural)
    {
        if (substr($estrutural, 0, 1) == 3) {
            return new Estrutural($estrutural);
        }
        return new EstruturalReceita($estrutural);
    }
}
