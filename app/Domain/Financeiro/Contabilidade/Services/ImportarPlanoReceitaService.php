<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Builder\PlanoOrcamentarioReceitaBuilder;
use App\Domain\Financeiro\Contabilidade\Builder\PlanoOrcamentarioReceitaROBuilder;
use App\Domain\Financeiro\Contabilidade\Builder\PlanoOrcamentarioReceitaRSBuilder;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Orcamentario\E2022\ReceitaUniaoMapper;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;
use App\Domain\Financeiro\Contabilidade\Models\PlanoReceita;
use ECidade\File\Csv\LerCsv;
use Exception;

class ImportarPlanoReceitaService
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
    /**
     * @var string
     */
    private $filepath;
    /**
     * @var string
     */
    private $uf;

    public function setFiltrosFromRequest(array $filtros)
    {
        $this->uf = getEstadoInstituicao();
        $this->plano = $filtros['plano'];
        $this->layout = new ReceitaUniaoMapper();

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

                $builder = new PlanoOrcamentarioReceitaBuilder();
                if ($this->uf === 'RS' && PlanoContas::PLANO_UNIAO !== $this->plano) {
                    $builder = new PlanoOrcamentarioReceitaRSBuilder();
                }
                if ($this->uf === 'RO' && PlanoContas::PLANO_UNIAO !== $this->plano) {
                    $builder = new PlanoOrcamentarioReceitaROBuilder();
                }

                // usando BULK inserts
                $dadoBuild = $builder
                    ->addExercicio($this->exercicio)
                    ->addTipoPlano($this->plano)
                    ->addLayout($this->layout)
                    ->addLinha($dadosLinha)
                    ->build();

                $dados = array_merge($dados, $dadoBuild);
                if (count($dados) >= 100) {
                    $this->inserir($dados);
                    $dados = [];
                }
            }

            if (!empty($dados)) {
                $this->inserir($dados);
            }
        } catch (Exception $exception) {
            throw new Exception('Erro ao importar planilha. Contate o Suporte.' . $exception->getMessage());
        }
    }

    private function inserir($dados)
    {
        $model = new PlanoReceita();
        $model->insert($dados);
    }

    private function validaImportacao()
    {
        $uniao = PlanoContas::PLANO_UNIAO == $this->plano;
        $importado = PlanoReceita::query()
                ->where('uniao', $uniao)
                ->where('exercicio', $this->exercicio)
                ->get()
                ->count() != 0;

        if ($importado) {
            throw new Exception(sprintf(
                'Já foi importado o plano de contas orçamentário da receita do exercício %s %s',
                $this->exercicio,
                $uniao ? 'da união' : 'do tribunal regional'
            ));
        }
    }
}
