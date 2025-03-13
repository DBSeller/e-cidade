<?php


namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Repositories\OrigemComplementoRecursoRepository;
use ECidade\Financeiro\Contabilidade\LancamentoContabil\Service\ManutencaoFonteRecursoService as Manutencao;
use Exception;

/**
 * Class ManutencaoFonteRecursoDespesa
 * @package App\Domain\Financeiro\Contabilidade\Services
 */
class ManutencaoFonteRecursoReceitaService extends ManutencaoFonteRecursoService
{
    /**
     * @return array
     * @throws Exception
     */
    public function buscarLancamentos()
    {
        $origemComplementoRecurso = new OrigemComplementoRecursoRepository();
        $receita = $this->request->get('codigoReceita');
        if (!empty($receita)) {
            $origemComplementoRecurso->scopeCodigoReceita($receita);
        }
        return $origemComplementoRecurso->scopeIntervaloLancamento(
            $this->request->get('dataInicio'),
            $this->request->get('dataFinal')
        )->scopeRecursosReceita($this->buscarRecursos())
            ->scopeValidaEncerramentoContabil()
            ->getComplementosReceita();
    }

    /**
     * @param integer $lancamento
     * @param integer $codigoRecurso
     * @throws Exception
     */

    public function atualizarRecurso($lancamento, $codigoRecurso)
    {
        $manutencao = new Manutencao();
        $manutencao->atualizarRecursoReceita($lancamento, $codigoRecurso);
    }
}
