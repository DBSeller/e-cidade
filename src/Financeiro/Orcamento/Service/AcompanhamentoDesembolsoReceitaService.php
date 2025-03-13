<?php

namespace ECidade\Financeiro\Orcamento\Service;

use App\Domain\Financeiro\Orcamento\Builder\AcompanhamentoReceitaBuilder;
use App\Domain\Financeiro\Orcamento\Models\FonteReceita;
use App\Domain\Financeiro\Planejamento\Models\CronogramaDesembolsoReceita;
use App\Domain\Financeiro\Planejamento\Services\CronogramaDesembolsoService;
use ECidade\Enum\Financeiro\Orcamento\BaseCalculoEnum;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use ECidade\Financeiro\Orcamento\Model\AcompanhamentoCronogramaReceita;
use ECidade\Financeiro\Orcamento\Model\Receita;
use ECidade\Financeiro\Orcamento\Repository\AcompanhamentoCronogramaReceitaRepository;
use Exception;

class AcompanhamentoDesembolsoReceitaService extends CronogramaDesembolsoService
{
    /**
     * @var Receita
     */
    private $receita;

    /**
     * @var AcompanhamentoCronogramaReceitaRepository
     */
    private $repository;
    private $exercicio;
    /**
     * @var BaseCalculoEnum
     */
    private $baseCalculo;

    public function __construct()
    {
        $this->repository = new AcompanhamentoCronogramaReceitaRepository();
    }

    /**
     * @param Receita $receita
     */
    public function setReceita(Receita $receita)
    {
        $this->receita = $receita;
    }

    /**
     * Chamado na hora de gerar o orçamento no módulo planejamento
     * @param CronogramaDesembolsoReceita $cronograma
     * @throws Exception
     */
    public function copiarCronogramaDetalhamento(CronogramaDesembolsoReceita $cronograma)
    {
        $model = $this->zeraValores(new AcompanhamentoCronogramaReceita());
        $model->exercicio = $this->receita->getAno();
        $model->receita_id = $this->receita->getReduzido();
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
     * @param integer|null $reduzido
     * @throws Exception
     */
    public function excluir($exercicio, $reduzido = null)
    {
        $this->repository->scopeExercicio($exercicio);
        if (!is_null($reduzido)) {
            $this->repository->scopeReduzido($reduzido);
        }
        $this->repository->excluir();
    }

    /**
     *  Sempre que criamos uma receita, é criado o acompanhamento
     * @param Receita $receita
     * @return AcompanhamentoCronogramaReceita
     * @throws Exception
     */
    public function criar(Receita $receita)
    {
        $model = $this->repository
            ->scopeReceita($receita)
            ->first();

        if ($model !== false) {
            return $model;
        }

        $valorMes = $this->getValorMensal($receita->getValor());
        $valorDezembro = $this->getValorDezembro($valorMes, $receita->getValor());

        $model = new AcompanhamentoCronogramaReceita();
        $model->receita_id = $receita->getReduzido();
        $model->exercicio = $receita->getAno();
        $model->base_calculo = BaseCalculoEnum::SALDO_INICIAL;
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
        return $this->repository->save($model);
    }

    /**
     * @param array $metasArrecadacao
     * @return AcompanhamentoCronogramaReceita|void
     * @throws Exception
     */
    public function atualizarEstimativas(array $metasArrecadacao)
    {
        $metas = [];
        foreach ($metasArrecadacao as $meta) {
            $model = $this->repository
                ->scopeCodigo($meta->idCronograma)
                ->first();

            $model->base_calculo = $meta->baseCalculo;
            $model->janeiro = $meta->janeiro;
            $model->fevereiro = $meta->fevereiro;
            $model->marco = $meta->marco;
            $model->abril = $meta->abril;
            $model->maio = $meta->maio;
            $model->junho = $meta->junho;
            $model->julho = $meta->julho;
            $model->agosto = $meta->agosto;
            $model->setembro = $meta->setembro;
            $model->outubro = $meta->outubro;
            $model->novembro = $meta->novembro;
            $model->dezembro = $meta->dezembro;
            $metas[] = $this->repository->save($model);
        }
        return $metas;
    }

    /**
     * @param array $dados
     * @return array
     * @throws \ReflectionException
     */
    public function recalcular(array $dados)
    {
        $this->baseCalculo = new BaseCalculoEnum((int)$dados['base_calculo']);
        $retorno = [];

        foreach ($dados['cronogramas'] as $dado) {
            $dado = parseStringJson($dado);
            $cronograma = $this->repository->scopeCodigo($dado->id)->first();
            $cronograma->base_calculo = $this->baseCalculo->value();

            $valorBase = $this->identificaValorBase($dado);
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

            $retorno[] = $this->repository->save($cronograma);
        }
        return $retorno;
    }

    /**
     * @param AcompanhamentoCronogramaReceita $model
     * @param $valorTotal
     */
    private function setValoresDivide12(AcompanhamentoCronogramaReceita $model, $valorTotal)
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

    public function buscarDadosCronograma($exercicio)
    {
        $this->exercicio = $exercicio;

        $receitas = $this->repository->buscarDados($exercicio);
        $this->baseCalculo = new BaseCalculoEnum((int)$receitas[0]->base_calculo);
        return $receitas;
    }

    /**
     * @throws \ReflectionException
     */
    public function buscarCronograma($exercicio)
    {
        $receitas = $this->buscarDadosCronograma($exercicio);

        return [
            'baseCalculo' => $this->baseCalculo->toOject(),
            'receitas' => array_values($this->montaArvore($receitas)),
        ];
    }

    private function montaArvore(array $estimativas)
    {
        $fontesReceitas = FonteReceita::where('o57_anousu', '=', $this->exercicio)->get();
        $receitas = [];
        foreach ($estimativas as $estimativa) {
            $estrutural = new EstruturalReceita($estimativa->natureza);
            $nivel = $estrutural->getNivel();
            $fonte = $estrutural->getEstrutural();

            $estruturalPaiDesdobramento = null;
            $temDesdobramento = false;

            if (FonteReceita::hasDesdobramento($estimativa->fonte, $estimativa->exercicio)) {
                $estruturalPaiDesdobramento = $estrutural->getCodigoEstruturalPai();
                $temDesdobramento = true;
            }

            $hash = "$fonte#$estimativa->cp";
            if (array_key_exists($hash, $receitas)) {
                $receita = $receitas[$hash];
                $receita->valorInicial += $estimativa->valor_inicial;
                $receita->previsaoAtualizada += $estimativa->previsao_atualizada;
                $receita->arrecadadoAcumulado += $estimativa->arrecadado_acumulado;
                $receita->valorBase += $this->identificaValorBase($estimativa);
            } else {
                $estimativa->valorBase = $this->identificaValorBase($estimativa);
                $builder = new AcompanhamentoReceitaBuilder();
                $receita = $builder->buildAnalitico($estimativa, $estrutural, $temDesdobramento);
                $receitas[$hash] = $receita;
            }

            while ($nivel != 1) {
                $estrutural = new EstruturalReceita($estrutural->getCodigoEstruturalPai());

                $fonte = $estrutural->getEstrutural();
                $nivel = $estrutural->getNivel();

                if (!array_key_exists($fonte, $receitas)) {
                    // localiza a fonte de receita para setar a descrição
                    $fonteReceita = $fontesReceitas->filter(function (FonteReceita $fonteReceita) use ($fonte) {
                        return $fonteReceita->o57_fonte === $fonte;
                    })->shift();

                    if (is_null($fonteReceita)) {
                        $msg = sprintf(
                            "Não foi encontrado a Natureza de Receita: %s. Acesse: %s",
                            $fonte,
                            "DB:FINANCEIRO > Contabilidade > Cadastros > Plano de Contas Orçamentário > Inclusão"
                        );
                        throw new Exception($msg);
                    }

                    $builder = new AcompanhamentoReceitaBuilder();
                    $receitas[$fonte] = $builder->buildSintetico($estrutural, $fonteReceita->o57_descr);
                }

                $receitas[$fonte]->valorBase += $receita->valorBase;

                $receitas[$fonte]->janeiro += $receita->janeiro;
                $receitas[$fonte]->fevereiro += $receita->fevereiro;
                $receitas[$fonte]->marco += $receita->marco;
                $receitas[$fonte]->abril += $receita->abril;
                $receitas[$fonte]->maio += $receita->maio;
                $receitas[$fonte]->junho += $receita->junho;
                $receitas[$fonte]->julho += $receita->julho;
                $receitas[$fonte]->agosto += $receita->agosto;
                $receitas[$fonte]->setembro += $receita->setembro;
                $receitas[$fonte]->outubro += $receita->outubro;
                $receitas[$fonte]->novembro += $receita->novembro;
                $receitas[$fonte]->dezembro += $receita->dezembro;

                if (!is_null($estruturalPaiDesdobramento)
                    && $estruturalPaiDesdobramento === $estrutural->getEstruturalComMascara()
                    && count($receitas[$fonte]->contasDesdobramento) === 0) {
                    $estruturalAteNivel = $estrutural->getEstruturalAteNivel();
                    $receitas[$fonte]->contasDesdobramento = getDesdobramentosReceita(
                        $estruturalAteNivel,
                        $this->exercicio
                    );
                }

                // Na receita analática indexa as contas que são pai da mesma
                $receita->fontesPai[] = $fonte;
            }
        }

        ksort($receitas);
        return $receitas;
    }

    /**
     * @param stdClass $data
     * @return float
     */
    private function identificaValorBase($data)
    {
        switch ($this->baseCalculo->value()) {
            case BaseCalculoEnum::SALDO_INICIAL:
                return $data->valor_inicial;
            case BaseCalculoEnum::PREVISAO_ATUALIZADA:
                return $data->previsao_atualizada;
            case BaseCalculoEnum::REALIZADO_REESTIMADO:
                return $data->arrecadado_acumulado;
        }
    }
}
