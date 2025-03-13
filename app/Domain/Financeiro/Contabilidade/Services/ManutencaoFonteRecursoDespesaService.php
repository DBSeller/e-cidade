<?php


namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Repositories\OrigemComplementoRecursoRepository;
use ECidade\Financeiro\Contabilidade\LancamentoContabil\Service\ManutencaoFonteRecursoService as Manutencao;
use Exception;

/**
 * Class ManutencaoFonteRecursoDespesa
 * @package App\Domain\Financeiro\Contabilidade\Services
 */
class ManutencaoFonteRecursoDespesaService extends ManutencaoFonteRecursoService
{

    /**
     * @return array
     * @throws Exception
     */
    public function buscarEmpenhos()
    {
        $origemComplementoRecurso = new OrigemComplementoRecursoRepository();
        $empenho = $this->request->get('idEmpenho');
        $origemComplementoRecurso->setAno($this->request->get('DB_anousu'));
        if (!empty($empenho)) {
            $origemComplementoRecurso->scopeIdEmpenho($this->request->get('idEmpenho'));
        } else {
            $origemComplementoRecurso->scopeIntervaloEmissaoEmpenho(
                $this->request->get('dataInicio'),
                $this->request->get('dataFinal')
            );
        }

        return $origemComplementoRecurso->scopeRecursosDespesa($this->buscarRecursos())
            ->getComplementosEmpenho();
    }

    /**
     * @param integer $codigoEmpenho
     * @param integer $codigoRecurso
     * @throws Exception
     */
    public function atualizarRecurso($codigoEmpenho, $codigoRecurso)
    {
        $manutencao = new Manutencao();
        $manutencao->atualizarRecursoEmpenho($codigoEmpenho, $codigoRecurso, $this->request->get('DB_anousu'));
    }
}
