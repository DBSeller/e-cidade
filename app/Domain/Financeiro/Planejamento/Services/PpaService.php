<?php


namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\Status;
use App\Domain\Financeiro\Planejamento\Requests\SalvarPPARequest;
use ECidade\Enum\Financeiro\Planejamento\StatusEnum;
use Exception;

class PpaService extends PlanejamentoService
{
    protected $tipo = 'PPA';

    /**
     * @param SalvarPPARequest $request
     * @return Planejamento
     * @throws Exception
     */
    public function salvar(SalvarPPARequest $request)
    {
        $this->model = new Planejamento(); // cria uma instancia vazia do Planejamento
        $this->validaPersistenciaPPA($request);

        // se alterar o controle por área de resultado, deleta todos vínculos
        if (!is_null($this->model->pl2_codigo) &&
            ($this->model->pl2_composicao != $request->get('pl2_composicao'))) {
            $this->model->areasResultado()->delete();
        }

        $this->model->pl2_ativo = true;
        $this->model->status()->associate(Status::find(StatusEnum::EM_DESENVOLVIMENTO));
        $this->model->pl2_tipo = $request->get('pl2_tipo');
        $this->model->pl2_codigo_pai = $request->get('pl2_codigo_pai') ?: null;
        $this->model->pl2_ano_inicial = $request->get('pl2_ano_inicial');
        $this->model->pl2_ano_final = $request->get('pl2_ano_final');
        $this->model->pl2_titulo = $request->get('pl2_titulo');
        $this->model->pl2_base_calculo = (int)$request->get('pl2_base_calculo');
        $this->model->pl2_base_despesa = $request->get('pl2_base_despesa') ?: null;
        $this->model->pl2_composicao = $request->get('pl2_composicao');
        $this->model->pl2_ementa = $request->get('pl2_ementa');

        $this->model->save();

        return $this->model;
    }

    private function validaPersistenciaPPA(SalvarPPARequest $request)
    {
        if ($request->get('pl2_base_calculo') == 1 && $request->get('pl2_base_despesa') != '') {
            throw new Exception(
                'Quando base de cálculo for "Previsão Atualizada" não deve ser informado a Base de Despesa.'
            );
        }

        if ($request->get('pl2_base_calculo') == 2 && $request->get('pl2_base_despesa') == '') {
            throw new Exception(
                'Quando base de cálculo for "Realizado e Reestimado" você deve informar a Base de Despesa.'
            );
        }

        $id = $request->get('pl2_codigo');

        if (!empty($id)) {
            $this->model = Planejamento::find($id);

            if ($this->model->status->pl1_codigo !== StatusEnum::EM_DESENVOLVIMENTO) {
                throw new Exception('Não é possivel alterar um PPA que não esteja "Em Desenvolvimento".');
            }
        }

        /**
         * verificar se existe outro plano que conflita com o período do plano sendo salvo
         */
        $this->validaConflito($id, $request->get('pl2_ano_inicial'), $request->get('pl2_ano_final'));

        return true;
    }
}
