<?php

namespace App\Domain\Financeiro\Empenho\Services\RelatorioRetencoesEfdReinf;

use App\Domain\Financeiro\Empenho\Relatorios\RetencoesEfdReinf\RelatorioRetencoesEfdReinfPdf;
use App\Domain\Financeiro\Empenho\Relatorios\RetencoesEfdReinf\RelatorioRetencoesEfdReinfCsv;
use App\Domain\Financeiro\Empenho\Relatorios\RetencoesEfdReinf\RelatorioRetencoesEfdReinfFiltros;
use App\Domain\Financeiro\Empenho\Services\RelatorioRetencoesEfdReinf\RelatorioRetencoesEfdReinfProcessamentoService;
use Carbon\Carbon;

class RelatorioRetencoesEFDReinfService extends RelatorioRetencoesEfdReinfProcessamentoService
{
   
    protected $relatorio;
    /**
     * @var RelatorioRetencoesEfdReinfFiltros $filtrosRetencoesEfdReinf
     */
    protected $filtrosRetencoesEfdReinf;
    protected $filtroModeloRelatorio;

    public function setFiltrosRequest(array $filtros)
    {
        $filtrosRetencoesEfdReinf = new RelatorioRetencoesEfdReinfFiltros();

        $filtrosRetencoesEfdReinf->setdataInicial($filtros['dataInicial']);
        $filtrosRetencoesEfdReinf->setdataFinal($filtros['dataFinal']);
        $filtrosRetencoesEfdReinf->setano(Carbon::createFromFormat('d/m/Y', $filtros['dataFinal'])->year);
        $filtrosRetencoesEfdReinf->setinstit(db_getsession("DB_instit"));
        $filtrosRetencoesEfdReinf->setfiltroEvento($filtros['evento']);
        $filtrosRetencoesEfdReinf->setfiltroAgrupaPor($filtros['agrupa_por']);
        $filtrosRetencoesEfdReinf->setfiltroCredor($filtros['credor']);
        $filtrosRetencoesEfdReinf->setfiltroOrgao($filtros['orgao']);
        $filtrosRetencoesEfdReinf->setfiltroUnidade($filtros['unidade']);

        $this->filtrosRetencoesEfdReinf = $filtrosRetencoesEfdReinf;
        $this->filtroModeloRelatorio = $filtros['formato_relatorio'];
    }

    public function emitir()
    {
        $this->getInstanceRelatorio();
        
        $dados = $this->processar();
        
        $this->inicializaRelatorio($dados);
        
        if ($this->filtroModeloRelatorio == 'p') {
            $this->relatorio->headers();
        }

        return  $this->relatorio->emitir();
    }

    public function processar()
    {
        $dados = $this->processarDadosRelatorio($this->filtrosRetencoesEfdReinf);
        return $dados;
    }

    public function getInstanceRelatorio()
    {
        if ($this->filtroModeloRelatorio == 'p') {
            $this->relatorio = new RelatorioRetencoesEfdReinfPdf();
        } else {
            $this->relatorio = new RelatorioRetencoesEfdReinfCsv();
        }
    }

    public function inicializaRelatorio($dados)
    {
        $this->relatorio->setFiltros($this->filtrosRetencoesEfdReinf);
        $this->relatorio->setDados($dados);
    }
}
