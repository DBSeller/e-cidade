<?php


namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\FatorCorrecaoDespesa;
use App\Domain\Financeiro\Planejamento\Models\FatorCorrecaoReceita;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Projecao\PersisteFatorCorrecaoRequest;
use ECidade\Financeiro\Orcamento\Model\FonteReceita;
use ECidade\Financeiro\Orcamento\Model\NaturezaDespesa;
use ECidade\Financeiro\Orcamento\Repository\FonteReceitaRepository;
use ECidade\Financeiro\Orcamento\Repository\NaturezaDespesaRepository;
use Exception;

/**
 * Class FatorCorrecao
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class FatorCorrecaoService
{
    /**
     * @var Planejamento
     */
    protected $planejamento;
    /**
     * @var array
     */
    private $valores = [];

    /**
     * @var string
     */
    private $natureza;
    /**
     * @var mixed
     */
    private $deflator;

    /**
     * @param PersisteFatorCorrecaoRequest $request
     */
    public function setRequestFatorCorrecao(PersisteFatorCorrecaoRequest $request)
    {
        $this->planejamento = Planejamento::find($request->get('planejamento'));
        $this->valores = json_decode(str_replace('\"', '"', $request->get('valores')));
        $this->natureza = $request->get('natureza');
        $this->deflator = $request->get('deflator');
    }

    /**
     * @throws Exception
     */
    public function persistirFatorDespesa()
    {
        $naturezasDespesa = $this->buscarNaturezaDespesa();
        if (count($naturezasDespesa) === 0) {
            throw new Exception("Nenhuma natureza de despesa encontrada.", 403);
        }

        foreach ($naturezasDespesa as $naturezaDespesa) {
            FatorCorrecaoDespesa::where('pl7_planejamento', '=', $this->planejamento->pl2_codigo)
                ->where('pl7_orcelemento', '=', $naturezaDespesa->getCodigo())
                ->where('pl7_anoorcamento', '=', $naturezaDespesa->getAno())
                ->delete();

            foreach ($this->valores as $valor) {
                $fator = new FatorCorrecaoDespesa();
                $fator->planejamento()->associate($this->planejamento);
                $fator->pl7_exercicio = $valor->ano;
                $fator->pl7_percentual = $valor->valor;
                $fator->pl7_orcelemento = $naturezaDespesa->getCodigo();
                $fator->pl7_anoorcamento = $naturezaDespesa->getAno();
                $fator->deflator = $this->deflator;
                $fator->save();
            }
        }
    }

    /**
     * @return NaturezaDespesa[]
     * @throws Exception
     */
    private function buscarNaturezaDespesa()
    {
        $repository = new NaturezaDespesaRepository();
        return $repository->scopeAno($this->planejamento->pl2_ano_inicial)
            ->scopeNaturezaDespesa($this->natureza)
            ->scopeApenasNaturezaSintetica()
            ->nivelDotacao()
            ->get(['orcelemento.*'], ['o56_elemento']);
    }

    /**
     * @throws Exception
     */
    public function persistirFatorReceita()
    {
        $fontesReceitas = $this->buscarFontesReceita();
        foreach ($fontesReceitas as $fonteReceita) {
            FatorCorrecaoReceita::where('planejamento_id', '=', $this->planejamento->pl2_codigo)
                ->where('orcfontes_id', '=', $fonteReceita->getCodigo())
                ->where('anoorcamento', '=', $fonteReceita->getExercicio())
                ->delete();

            foreach ($this->valores as $valor) {
                $fator = new FatorCorrecaoReceita();
                $fator->planejamento()->associate($this->planejamento);
                $fator->exercicio = $valor->ano;
                $fator->percentual = $valor->valor;
                $fator->orcfontes_id = $fonteReceita->getCodigo();
                $fator->anoorcamento = $fonteReceita->getExercicio();
                $fator->deflator = $this->deflator;
                $fator->save();
            }
        }
    }

    /**
     * @return FonteReceita[]
     * @throws Exception
     */
    public function buscarFontesReceita()
    {
        $repository = new FonteReceitaRepository();
        return $repository->scopeAno($this->planejamento->pl2_ano_inicial)
            ->scopeFonte($this->natureza)
            ->scopeApenasFonteAnalitica()
            ->get(['o57_fonte']);
    }

    public function get(array $filtros)
    {
        if ($filtros['tipo'] === 'despesa') {
            return $this->getDespesa($filtros);
        }
        if ($filtros['tipo'] === 'receita') {
            return $this->getReceita($filtros);
        }
    }

    /**
     * @param array $filtros
     * @return mixed
     */
    private function getDespesa(array $filtros)
    {
        return FatorCorrecaoDespesa::query()
            ->where('pl7_planejamento', '=', $filtros['planejamento_id'])
            ->when(!empty($filtros['natureza_id']), function ($query) use ($filtros) {
                $query->where('pl7_orcelemento', '=', $filtros['natureza_id']);
            })
            ->get();
    }

    /**
     * @param array $filtros
     * @return mixed
     */
    private function getReceita(array $filtros)
    {
        return FatorCorrecaoReceita::query()
            ->where('planejamento_id', '=', $filtros['planejamento_id'])
            ->when(!empty($filtros['natureza_id']), function ($query) use ($filtros) {
                $query->where('orcfontes_id', '=', $filtros['natureza_id']);
            })
            ->get();
    }
}
