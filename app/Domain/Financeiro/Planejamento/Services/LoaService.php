<?php


namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Requests\SalvarLDORequest;

class LoaService extends PlanejamentoService
{
    protected $tipo = 'LOA';

    public function salvar(SalvarLDORequest $request)
    {
        $id = $request->get('pl2_codigo');
        $this->validaConflito($id, $request->get('pl2_ano_inicial'), $request->get('pl2_ano_final'));
        $this->model = Planejamento::find($id);

        $this->model->pl2_titulo = $request->get('pl2_titulo');
        $this->model->pl2_ementa = $request->get('pl2_ementa');

        $this->model->save();
        return $this->model;
    }
}
