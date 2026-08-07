<?php


namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\Status;
use App\Domain\Financeiro\Planejamento\Requests\SalvarLDORequest;
use ECidade\Enum\Financeiro\Planejamento\StatusEnum;

/**
 * Class LdoService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class LdoService extends PlanejamentoService
{
    protected $tipo = 'LDO';

    public function salvar(SalvarLDORequest $request)
    {
        $id = $request->get('pl2_codigo');
        $this->validaConflito($id, $request->get('pl2_ano_inicial'), $request->get('pl2_ano_final'));
        $this->model = Planejamento::find($id);

//        $this->model->pl2_ativo = true;
//        $this->model->status()->associate(Status::find(StatusEnum::EM_DESENVOLVIMENTO));
//        $this->model->pl2_tipo = $request->get('pl2_tipo');
//        $this->model->pl2_codigo_pai = $request->get('pl2_codigo_pai') ?: null;
//        $this->model->pl2_ano_inicial = $request->get('pl2_ano_inicial');
//        $this->model->pl2_ano_final = $request->get('pl2_ano_final');
        $this->model->pl2_titulo = $request->get('pl2_titulo');
//        $this->model->pl2_base_calculo = (int)$request->get('pl2_base_calculo');
//        $this->model->pl2_base_despesa = $request->get('pl2_base_despesa') ?: null;
//        $this->model->pl2_area_resultado = $request->get('pl2_area_resultado');
        $this->model->pl2_ementa = $request->get('pl2_ementa');

        $this->model->save();
        return $this->model;
    }
}
