<?php

namespace App\Domain\Financeiro\Orcamento\Services\Relatorios;

use App\Domain\Financeiro\Orcamento\Relatorios\AcompanhamentoCotasDespesaPdf;
use App\Domain\Financeiro\Planejamento\Relatorios\CotasDespesaCSV;
use App\Domain\Financeiro\Planejamento\Relatorios\CotasDespesaPdf;
use ECidade\Financeiro\Orcamento\Repository\AcompanhamentoCronogramaDespesaRepository;

class CotasDespesaService extends BaseCronograma
{
    const ORGAO = "orgao";
    const UNIDADE = "unidade";
    const FUNCAO = "funcao";
    const SUBFUNCAO = "subfuncao";
    const PROGRAMA = "programa";
    const INICIATIVA = "iniciativa";
    const ELEMENTO = "elemento";
    const RECURSO = "recurso";

    /**
     * @var object
     */
    private $filtros;

    protected function processaFiltros(array $filtros)
    {
        $this->exercicio = (int)$filtros['DB_anousu'];
        $this->agruparPor = $filtros['agruparPor'];
        $this->periodicidade = $filtros['periodicidade'];
        $this->instituicoes = $filtros['instituicoes'];
        if (!empty($filtros['recursos'])) {
            $this->recursos = $filtros['recursos'];
        }
        $this->organizaFiltrosEmissao();
        $this->inicializaTotalizadores();
        $this->filtros = filtrosDespesaJsonToPlanejamento($filtros['filtros']);
    }

    /**
     * Organiza os filtros de emissão
     */
    protected function organizaFiltrosEmissao()
    {
        $this->dados['filtros']['exercicio'] = $this->exercicio;
        $this->dados['filtros']['agruparPor'] = $this->agruparPor;
        $this->dados['filtros']['periodicidade'] = $this->periodicidade;
        $this->dados['filtros']['filtrouRecurso'] = !empty($this->recursos);
    }

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

        $relatorio = new AcompanhamentoCotasDespesaPdf();
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

    private function processar()
    {
        $estimativas = $this->buscarEstimativas();
        $this->dados['dados'] = $this->agrupar($estimativas);
        $this->totalizar();
    }

    private function buscarEstimativas()
    {
        $filtros = $this->filtros;
        $repository = new AcompanhamentoCronogramaDespesaRepository();
        $repository->scopeInstituicoes($this->instituicoes);
        if (!empty($filtros->programa->valores)) {
            $operador = $filtros->programa->contem ? 'in' : 'not in';
            $repository->scopeProgramas($filtros->programa->valores, $operador);
        }
        if (!empty($filtros->orgao->valores)) {
            $operador = $filtros->orgao->contem ? 'in' : 'not in';
            $repository->scopeOrgaos($filtros->orgao->valores, $operador);
        }
        if (!empty($filtros->iniciativa->valores)) {
            $operador = $filtros->iniciativa->contem ? 'in' : 'not in';
            $repository->scopeProjetos($filtros->iniciativa->valores, $operador);
        }
        if (!empty($filtros->unidade->valores)) {
            $operador = $filtros->unidade->contem ? 'in' : 'not in';
            $repository->scopeOrgaosUnidades($filtros->unidade->valores, $operador);
        }
        if (!empty($filtros->funcao->valores)) {
            $operador = $filtros->funcao->contem ? 'in' : 'not in';
            $repository->scopeFuncoes($filtros->funcao->valores, $operador);
        }
        if (!empty($filtros->subfuncao->valores)) {
            $operador = $filtros->subfuncao->contem ? 'in' : 'not in';
            $repository->scopeSubfuncoes($filtros->subfuncao->valores, $operador);
        }
        if (!empty($filtros->elemento->valores)) {
            $operador = $filtros->elemento->contem ? 'in' : 'not in';
            $repository->scopeElementos($filtros->elemento->valores, $operador);
        }
        if (!empty($filtros->recurso->valores)) {
            $operador = $filtros->recurso->contem ? 'in' : 'not in';
            $repository->scopeRecursos($filtros->recurso->valores, $operador);
        }

        return $repository->buscarDados($this->exercicio);
    }

    private function agrupar($estimativas)
    {
        $dadosAgrupados = [];
        foreach ($estimativas as $estimativa) {
            $agrupar = $this->criaObjetoPorAgrupador($estimativa);

            if (!array_key_exists($agrupar->codigo, $dadosAgrupados)) {
                $dadosAgrupados[$agrupar->codigo] = $this->criaObjeto($estimativa);
            }

            $dadosAgrupados[$agrupar->codigo]->valor += $this->totalizaLinha($estimativa);
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

    private function criaObjeto($cronograma)
    {
        $objeto = $this->criaObjetoPorAgrupador($cronograma);
        $objeto->valor = 0;

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


    private function criaObjetoPorAgrupador($cronograma)
    {
        switch ($this->agruparPor) {
            case self::ORGAO:
                return (object)['codigo' => $cronograma->orgao, 'descricao' => $cronograma->descricao_orgao];
            case self::UNIDADE:
                return (object)['codigo' => $cronograma->unidade, 'descricao' => $cronograma->descricao_unidade];
            case self::FUNCAO:
                return (object)['codigo' => $cronograma->funcao, 'descricao' => $cronograma->descricao_funcao];
            case self::SUBFUNCAO:
                return (object)['codigo' => $cronograma->subfuncao, 'descricao' => $cronograma->descricao_subfuncao];
            case self::PROGRAMA:
                return (object)['codigo' => $cronograma->programa, 'descricao' => $cronograma->descricao_programa];
            case self::INICIATIVA:
                return (object)['codigo' => $cronograma->projeto, 'descricao' => $cronograma->descricao_projeto];
            case self::ELEMENTO:
                return (object)['codigo' => $cronograma->elemento, 'descricao' => $cronograma->descricao_elemento];
            case self::RECURSO:
                return (object)['codigo' => $cronograma->fonte_recurso, 'descricao' => $cronograma->descricao_recurso];
        }
    }

    private function totalizaLinha($cronograma)
    {
        return $cronograma->janeiro + $cronograma->fevereiro + $cronograma->marco + $cronograma->abril +
            $cronograma->maio + $cronograma->junho + $cronograma->julho + $cronograma->agosto +
            $cronograma->setembro + $cronograma->outubro + $cronograma->novembro + $cronograma->dezembro;
    }
}
