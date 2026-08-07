<?php


namespace App\Domain\Financeiro\Planejamento\Services\Relatorios;

use App\Domain\Financeiro\Planejamento\Relatorios\ResumoReceita;

/**
 * Class ResumoProjecaoReceitaService
 * @package App\Domain\Financeiro\Planejamento\Services\Relatorios
 */
class ResumoProjecaoReceitaService extends ReceitaService
{
    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
        $this->processarFiltros();
    }

    public function processar()
    {
        $projecao = $this->montaArvoreEstrutural($this->buscarProjecao());

        $this->organizaDados($this->filtrarReceitas($projecao));
        return $this->dados;
    }

    public function emitirPdf()
    {
        $relatorio = new ResumoReceita();
        $relatorio->setDados($this->processar());
        return $relatorio->emitir();
    }

    private function filtrarReceitas(array $projecao)
    {
        $dados = [];
        foreach ($projecao as $receita) {
            if ($receita->nivel <= 3) {
                if ($receita->nivel === 3 &&
                    (strpos($receita->fonte, '47') === 0 || strpos($receita->fonte, '48') === 0)) {
                    continue;
                }

                $dados[] = $receita;
            }
        }

        return $dados;

//        dd($dados);
    }
}
