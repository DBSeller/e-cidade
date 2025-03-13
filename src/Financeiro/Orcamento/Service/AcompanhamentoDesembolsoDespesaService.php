<?php

namespace ECidade\Financeiro\Orcamento\Service;

use App\Domain\Financeiro\Planejamento\Models\CronogramaDesembolsoDespesa;
use App\Domain\Financeiro\Planejamento\Services\CronogramaDesembolsoService;
use ECidade\Enum\Financeiro\Orcamento\BaseCalculoDespesaEnum;
use ECidade\Enum\Financeiro\Orcamento\BaseCalculoEnum;
use ECidade\Financeiro\Orcamento\Model\AcompanhamentoCronogramaDespesa;
use ECidade\Financeiro\Orcamento\Repository\AcompanhamentoCronogramaDespesaRepository;
use ECidade\Financeiro\Orcamento\Model\Dotacao;
use Exception;
use stdClass;

class AcompanhamentoDesembolsoDespesaService extends CronogramaDesembolsoService
{
    /**
     * @var Dotacao
     */
    private $dotacao;
    /**
     * @var AcompanhamentoCronogramaDespesaRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new AcompanhamentoCronogramaDespesaRepository();
    }

    public function setDotacao(Dotacao $dotacao)
    {
        $this->dotacao = $dotacao;
    }

    /**
     * Retorna
     * @throws Exception
     */
    public function buscarCronograma($exercicio)
    {
        $base = null;
        return collect($this->repository->buscarDados($exercicio))->map(function ($data) use ($base) {
            if (is_null($base)) {
                $base = new BaseCalculoDespesaEnum((int)$data->base_calculo);
            }

            $data->valor_base = $this->identificaValorBase($base, $data);
            $data->orgao = str_pad($data->orgao, 2, '0', STR_PAD_LEFT);
            $data->unidade = str_pad($data->unidade, 2, '0', STR_PAD_LEFT);
            $data->funcao = str_pad($data->funcao, 2, '0', STR_PAD_LEFT);
            $data->subfuncao = str_pad($data->subfuncao, 3, '0', STR_PAD_LEFT);
            $data->programa = str_pad($data->programa, 4, '0', STR_PAD_LEFT);
            $data->projeto = str_pad($data->projeto, 4, '0', STR_PAD_LEFT);
            $data->descricao_base_calculo = $base->name();
            return $data;
        });
    }

    /**
     * Chamado na hora de gerar o orçamento no módulo planejamento
     * @param CronogramaDesembolsoDespesa $cronograma
     * @throws Exception
     */
    public function copiarCronogramaDetalhamento(CronogramaDesembolsoDespesa $cronograma)
    {
        $model = new AcompanhamentoCronogramaDespesa();
        $this->zeraValores($model);
        $model->exercicio = $this->dotacao->getAno();
        $model->dotacao_id = $this->dotacao->getCodigoDotacao();
        $model->base_calculo = BaseCalculoEnum::SALDO_INICIAL;
        $model->janeiro = $cronograma->janeiro;
        $model->fevereiro = $cronograma->fevereiro;
        $model->marco = $cronograma->marco;
        $model->abril = $cronograma->abril;
        $model->maio = $cronograma->maio;
        $model->junho = $cronograma->junho;
        $model->julho = $cronograma->julho;
        $model->agosto = $cronograma->agosto;
        $model->setembro = $cronograma->setembro;
        $model->outubro = $cronograma->outubro;
        $model->novembro = $cronograma->novembro;
        $model->dezembro = $cronograma->dezembro;
        $this->repository->save($model);
    }

    /**
     * @param $exercicio
     * @param null $codigoDotacao
     * @throws Exception
     */
    public function excluir($exercicio, $codigoDotacao = null)
    {
        $this->repository->scopeExercicio($exercicio);
        if (!is_null($codigoDotacao)) {
            $this->repository->scopeCodigoDotacao($codigoDotacao);
        }
        $this->repository->excluir();
    }

    /**
     * Sempre que criamos a dotação, é criado o acompanhamento
     * @param Dotacao $dotacao
     * @return AcompanhamentoCronogramaDespesa
     * @throws Exception
     */
    public function criar(Dotacao $dotacao)
    {
        $model = $this->repository
            ->scopeDotacao($dotacao)
            ->first();

        if ($model !== false) {
            return $model;
        }


        $model = new AcompanhamentoCronogramaDespesa();
        $model->dotacao_id = $dotacao->getCodigoDotacao();
        $model->exercicio = $dotacao->getAno();
        $model->base_calculo = BaseCalculoEnum::SALDO_INICIAL;
        $valorTotal = $dotacao->getValor();
        $this->setValoresDivide12($model, $valorTotal);
        return $this->repository->save($model);
    }

    public function atualizarEstimativa(array $dados)
    {
        $model = new AcompanhamentoCronogramaDespesa();
        $model->id = $dados['id'];
        $model->dotacao_id = $dados['dotacao_id'];
        $model->exercicio = $dados['exercicio'];
        $model->base_calculo = $dados['base_calculo'];
        $model->janeiro = $dados['janeiro'];
        $model->fevereiro = $dados['fevereiro'];
        $model->marco = $dados['marco'];
        $model->abril = $dados['abril'];
        $model->maio = $dados['maio'];
        $model->junho = $dados['junho'];
        $model->julho = $dados['julho'];
        $model->agosto = $dados['agosto'];
        $model->setembro = $dados['setembro'];
        $model->outubro = $dados['outubro'];
        $model->novembro = $dados['novembro'];
        $model->dezembro = $dados['dezembro'];

        return $this->repository->save($model);
    }

    public function recalcular(array $dados)
    {
        $base = new BaseCalculoDespesaEnum((int)$dados['base_calculo']);

        $retorno = [];
        foreach ($dados['cronogramas'] as $id) {
            $dadosCronograma = $this->repository
                ->resetScopes()
                ->scopeCodigo($id)
                ->buscarDados($dados['exercicio']);

            $dadosCronograma = array_shift($dadosCronograma);

            $cronograma = new AcompanhamentoCronogramaDespesa();
            $cronograma->id = $dadosCronograma->id;
            $cronograma->dotacao_id = $dadosCronograma->dotacao_id;
            $cronograma->exercicio = $dadosCronograma->exercicio;
            $cronograma->base_calculo = $base->value();


            $valorBase = $this->identificaValorBase($base, $dadosCronograma);

            switch ($dados['formula']) {
                case 1:
                    $this->setValoresDivide12($cronograma, $valorBase);
                    break;
                case 2:
                    $cronograma = $this->zeraValores($cronograma);
                    $cronograma->{$dados['mes']} = $valorBase;
                    break;
                default:
                    throw new Exception("Fórmula desconhecida.");
            }

            $cronograma->valorBase = $valorBase;
            $retorno[] = $this->repository->save($cronograma);
        }

        return $retorno;
    }

    /**
     * @param AcompanhamentoCronogramaDespesa $model
     * @param $valorTotal
     */
    private function setValoresDivide12(AcompanhamentoCronogramaDespesa $model, $valorTotal)
    {
        $valorMes = $this->getValorMensal($valorTotal);
        $valorDezembro = $this->getValorDezembro($valorMes, $valorTotal);
        $model->janeiro = $valorMes;
        $model->fevereiro = $valorMes;
        $model->marco = $valorMes;
        $model->abril = $valorMes;
        $model->maio = $valorMes;
        $model->junho = $valorMes;
        $model->julho = $valorMes;
        $model->agosto = $valorMes;
        $model->setembro = $valorMes;
        $model->outubro = $valorMes;
        $model->novembro = $valorMes;
        $model->dezembro = $valorDezembro;
    }

    /**
     * @param BaseCalculoDespesaEnum $base
     * @param stdClass $data
     * @return float
     */
    private function identificaValorBase(BaseCalculoDespesaEnum $base, $data)
    {
        switch ($base->value()) {
            case BaseCalculoDespesaEnum::SALDO_INICIAL:
                return $data->saldo_inicial;
            case BaseCalculoDespesaEnum::PREVISAO_ATUALIZADA:
                return $data->previsao_atualizada;
            case BaseCalculoDespesaEnum::REALIZADO_REESTIMADO_EMPENHADO:
                return $data->empenhado_liquido;
            case BaseCalculoDespesaEnum::REALIZADO_REESTIMADO_LIQUIDADO:
                return $data->liquidado_acumulado;
            case BaseCalculoDespesaEnum::REALIZADO_REESTIMADO_PAGO:
                return $data->pago_acumulado;
        }
    }
}
