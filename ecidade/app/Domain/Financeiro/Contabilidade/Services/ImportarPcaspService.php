<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Builder\PcaspBuilder;
use App\Domain\Financeiro\Contabilidade\Contracts\PlanoContasPcaspInterface;
use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022\UniaoMapper;
use App\Domain\Financeiro\Contabilidade\Models\Pcasp;
use ECidade\File\Csv\LerCsv;
use Exception;

class ImportarPcaspService
{
    /**
     * @var PlanoContasPcaspInterface
     */
    private $layout;
    /**
     * @var integer
     */
    private $exercicio;
    /**
     * @var string
     */
    private $filepath;
    /**
     * Tipo do plano selecionado
     * @var string
     */
    private $plano;
    /**
     * @var string
     */
    private $uf;

    /**
     * @param array $filtros
     * @throws Exception
     */
    public function setFiltrosFromRequest(array $filtros)
    {
        $this->plano = $filtros['plano'];
        $this->uf = getEstadoInstituicao();
        $this->layout = new UniaoMapper();
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

            // Valida se a conta esta ativa... Só importa contas ativas.
            $status = $dadosLinha[$this->layout->colunaStatus()];
            if (!$this->layout->importar($status)) {
                continue;
            }

            // usando BULK inserts
            $dados[] = (new PcaspBuilder())
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
    }

    private function inserir($dados)
    {
        $model = new Pcasp();
        $model->insert($dados);
    }

    private function validaImportacao()
    {
        $uniao = PcaspBuilder::UNIAO == $this->plano;
        $importado = Pcasp::query()
            ->where('uniao', $uniao)
            ->where('exercicio', $this->exercicio)
            ->get()
            ->count() != 0;

        if ($importado) {
            throw new Exception(sprintf(
                'Já foi importado o plano de contas do exercício %s %s',
                $this->exercicio,
                $uniao ? 'da união' : 'do tribunal regional'
            ));
        }
    }
}
