<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Builder\PlanoOrcamentarioDespesaBuilder;
use App\Domain\Financeiro\Contabilidade\Builder\PlanoOrcamentarioDespesaRsBuilder;
use App\Domain\Financeiro\Contabilidade\Factories\PlanoContasOrcamentarioDespesaFactory;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Orcamentario\E2022\DespesaUniaoMapper;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;
use App\Domain\Financeiro\Contabilidade\Models\PlanoDespesa;
use ECidade\File\Csv\LerCsv;
use Exception;

class ImportarPlanoDespesaService
{
    /**
     * Tipo do plano selecionado
     * @var string
     */
    private $plano;
    /**
     * @var integer
     */
    private $exercicio;
    private $filepath;
    private $uf;

    public function setFiltrosFromRequest(array $filtros)
    {
        $this->uf = getEstadoInstituicao();
        $this->plano = $filtros['plano'];
        $this->layout = new DespesaUniaoMapper();

        $this->exercicio = $filtros['exercicio'];
        $file = \JSON::create()->parse(str_replace('\"', '"', $filtros['file']));
        if ($file->extension !== 'csv') {
            throw new Exception('O arquivo deve vir no formato csv.');
        }
        $this->filepath = $file->path;
    }

    public function processar()
    {
        $this->validaImportacao();

        $csv = new LerCsv($this->filepath);
        $csv->setCsvControl(';');
        $linha = $csv->read();

        try {
            $dados = [];
            foreach ($linha as $key => $dadosLinha) {
                if ($key < $this->layout->linhaInicio()) {
                    continue;
                }
                // valida se tem dados na coluna em que deveria conter o código da conta
                $conta = $dadosLinha[$this->layout->indexColunaConta()];
                if (empty($conta)) {
                    continue;
                }

                $builder = new PlanoOrcamentarioDespesaBuilder();

                $dados[] = $builder
                    ->addExercicio($this->exercicio)
                    ->addTipoPlano($this->plano)
                    ->addLayout($this->layout)
                    ->addLinha($dadosLinha)
                    ->build();

                if (count($dados) === 100) {
                    $this->inserir($dados);
                    $dados = [];
                }
            }

            if (!empty($dados)) {
                $this->inserir($dados);
            }
        } catch (Exception $exception) {
            throw new Exception('Erro ao importar planilha. Contate o Suporte. ' . $exception->getMessage());
        }
    }

    private function inserir($dados)
    {
        $model = new PlanoDespesa();
        $model->insert($dados);
    }

    private function validaImportacao()
    {
        $uniao = PlanoContas::PLANO_UNIAO === $this->plano;
        $importado = PlanoDespesa::query()
                ->where('uniao', $uniao)
                ->where('exercicio', $this->exercicio)
                ->get()
                ->count() != 0;

        if ($importado) {
            throw new Exception(sprintf(
                'Já foi importado o plano de contas orçamentário da despesa do exercício %s %s',
                $this->exercicio,
                $uniao ? 'da união' : 'do tribunal regional'
            ));
        }
    }
}
