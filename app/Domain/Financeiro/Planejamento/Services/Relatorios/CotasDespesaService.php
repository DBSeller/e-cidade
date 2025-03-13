<?php

namespace App\Domain\Financeiro\Planejamento\Services\Relatorios;

use App\Domain\Financeiro\Planejamento\Relatorios\CotasDespesaCSV;
use App\Domain\Financeiro\Planejamento\Relatorios\CotasDespesaPdf;
use App\Domain\Financeiro\Planejamento\Services\CronogramaDesembolsoDespesaService;
use App\Domain\Financeiro\Planejamento\Services\ProgramaEstrategicoService;
use Exception;
use stdClass;

class CotasDespesaService extends BaseRelatoriosCronograma
{
    /**
     * Lista de filtros do orçamento para aplicar na busca dos dados
     * @var stdClass
     */
    protected $filtros = [];


    public function emitir()
    {
        $this->processar();
        /**
         * emitir em pdf e csv
         * return array_merge($this->emitirPdf(), $this->emitirCSV());
         */
        return array_merge($this->emitirPdf(), $this->emitirCSV());
    }

    public function emitirPdf()
    {
        if (empty($this->dados['dados'])) {
            $this->processar();
        }

        $relatorio = new CotasDespesaPdf();
        $relatorio->setDados($this->dados);
        return $relatorio->emitir();
    }

    public function emitirCSV()
    {
        if (empty($this->dados['dados'])) {
            $this->processar();
        }

        $relatorio = new CotasDespesaCSV();
        $relatorio->setDados($this->dados);
        return $relatorio->emitir();
    }

    /**
     * - Busca os valores conforme os filtros informados
     * - Agrupa os valores conforme agrupador informado
     * - Totaliza os valores
     * @throws Exception
     */
    private function processar()
    {
        $estimativas = $this->buscarEstimativas();
        $this->dados['dados'] = $this->agrupar($estimativas);
        $this->totalizar();
    }

    protected function processaFiltros(array $filtros)
    {
        parent::processaFiltros($filtros);
        $this->buscarNotasExplicativas($filtros['DB_anousu'], $filtros['DB_instit'], 78);

        $this->filtros = filtrosDespesaJsonToPlanejamento($filtros['filtros']);
    }

    /**
     * @return array
     * @throws Exception
     */
    private function buscarEstimativas()
    {
        $servicePrograma = new ProgramaEstrategicoService();
        $programas = $servicePrograma->getByFiltroOrcamento($this->planejamento, $this->instituicoes, $this->filtros);

        if ($programas->count() === 0) {
            throw new Exception("Não foram encontrados dados para o(s) filtro(s) selecionado(s).");
        }

        $cronograma = new CronogramaDesembolsoDespesaService();

        return $cronograma->buscarEstimativas($programas, $this->exercicio, $this->filtros);
    }

    /**
     * Agrupa os dados conforme agrupador selecionado na tela de emissão
     * @param array $estimativas
     * @return array
     */
    private function agrupar(array $estimativas)
    {
        $dadosAgrupados = [];
        foreach ($estimativas as $estimativa) {
            $agrupar = $estimativa->{$this->agruparPor};

            if (!array_key_exists($agrupar->codigo, $dadosAgrupados)) {
                $dadosAgrupados[$agrupar->codigo] = $this->criaObjeto($agrupar);
            }

            $dadosAgrupados[$agrupar->codigo]->valor += $estimativa->valor_base;
            if ($this->periodicidade === 'mensal') {
                $dadosAgrupados[$agrupar->codigo]->janeiro += $estimativa->janeiro;
                $dadosAgrupados[$agrupar->codigo]->fevereiro += $estimativa->fevereiro;
                $dadosAgrupados[$agrupar->codigo]->marco += $estimativa->marco;
                $dadosAgrupados[$agrupar->codigo]->abril += $estimativa->abril;
                $dadosAgrupados[$agrupar->codigo]->maio += $estimativa->maio;
                $dadosAgrupados[$agrupar->codigo]->junho += $estimativa->junho;
                $dadosAgrupados[$agrupar->codigo]->julho += $estimativa->julho;
                $dadosAgrupados[$agrupar->codigo]->agosto += $estimativa->agosto;
                $dadosAgrupados[$agrupar->codigo]->setembro += $estimativa->setembro;
                $dadosAgrupados[$agrupar->codigo]->outubro += $estimativa->outubro;
                $dadosAgrupados[$agrupar->codigo]->novembro += $estimativa->novembro;
                $dadosAgrupados[$agrupar->codigo]->dezembro += $estimativa->dezembro;
            } else {
                $dadosAgrupados[$agrupar->codigo]->bimestre_1 += $estimativa->janeiro;
                $dadosAgrupados[$agrupar->codigo]->bimestre_1 += $estimativa->fevereiro;

                $dadosAgrupados[$agrupar->codigo]->bimestre_2 += $estimativa->marco;
                $dadosAgrupados[$agrupar->codigo]->bimestre_2 += $estimativa->abril;

                $dadosAgrupados[$agrupar->codigo]->bimestre_3 += $estimativa->maio;
                $dadosAgrupados[$agrupar->codigo]->bimestre_3 += $estimativa->junho;

                $dadosAgrupados[$agrupar->codigo]->bimestre_4 += $estimativa->julho;
                $dadosAgrupados[$agrupar->codigo]->bimestre_4 += $estimativa->agosto;

                $dadosAgrupados[$agrupar->codigo]->bimestre_5 += $estimativa->setembro;
                $dadosAgrupados[$agrupar->codigo]->bimestre_5 += $estimativa->outubro;

                $dadosAgrupados[$agrupar->codigo]->bimestre_6 += $estimativa->novembro;
                $dadosAgrupados[$agrupar->codigo]->bimestre_6 += $estimativa->dezembro;
            }
        }

        sort($dadosAgrupados);
        return $dadosAgrupados;
    }

    private function criaObjeto($agrupar)
    {
        $objeto = (object)[
            'codigo' => $agrupar->codigo,
            'descricao' => $agrupar->descricao,
            'valor' => 0
        ];

        if ($this->periodicidade === 'mensal') {
            $objeto->janeiro = 0;
            $objeto->fevereiro = 0;
            $objeto->marco = 0;
            $objeto->abril = 0;
            $objeto->maio = 0;
            $objeto->junho = 0;
            $objeto->julho = 0;
            $objeto->agosto = 0;
            $objeto->setembro = 0;
            $objeto->outubro = 0;
            $objeto->novembro = 0;
            $objeto->dezembro = 0;
        } else {
            $objeto->bimestre_1 = 0;
            $objeto->bimestre_2 = 0;
            $objeto->bimestre_3 = 0;
            $objeto->bimestre_4 = 0;
            $objeto->bimestre_5 = 0;
            $objeto->bimestre_6 = 0;
        }

        return $objeto;
    }
}
