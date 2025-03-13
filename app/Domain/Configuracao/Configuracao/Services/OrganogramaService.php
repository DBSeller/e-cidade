<?php

namespace App\Domain\Configuracao\Configuracao\Services;

use App\Domain\Configuracao\Configuracao\Model\Organograma;
use App\Domain\Configuracao\Configuracao\Requests\SalvarOrganogramaRequest;
use Exception;

class OrganogramaService
{
    /**
     * Procura os departamentos filhos a partir do departamento informado
     * @param string descrição abreviada para o departamento
     * @param integer codigo do departamento
     * @param integer codigo do departamento pai
     * @param bool associado
     * @param integer codigo do departamento que fez a requisição
     * @return object estrutura do Organograma
     */
    public function getOrganograma($descricao, $codigoDepartamento, $departamentoPai, $associado, $departamento = null)
    {
        $estruraOrganograma = (object)[
            'descricao' => $descricao,
            'departamento' => (int)$codigoDepartamento,
            'departamentopai' => $departamentoPai,
            'checked' => false,
            'associado' => $associado,
            'filhos' => []
        ];
        Organograma::query()
            ->where('db122_depart', '=', $codigoDepartamento)
            ->get()
            ->each(function (Organograma $organograma) use ($estruraOrganograma, $departamento) {
                if ($departamento == $organograma->db122_departfilho) {
                    $estruraOrganograma->checked = true;
                }
                $organogramaFilho = $this->getOrganograma(
                    $organograma->db122_descricao,
                    $organograma->db122_departfilho,
                    $organograma->db122_depart,
                    $organograma->db122_associado,
                    $departamento
                );
                $estruraOrganograma->filhos[] = $organogramaFilho;
            });
        return $estruraOrganograma;
    }

    /**
     * Metódo responsável por salvar ou alterar
     * @param SalvarOrganogramaRequest request
     * @throws Exception
     */
    public function salvar(SalvarOrganogramaRequest $request)
    {
        $organograma = Organograma::query()
            ->where('db122_departfilho', '=', $request->get('departamentofilho'))
            ->first();
        if ($organograma instanceof Organograma) {
            $estruturaOrganograma = $this->getOrganograma(
                $organograma->db122_descricao,
                $organograma->db122_departfilho,
                $organograma->db122_depart,
                $organograma->db122_associado
            );
            if ($estruturaOrganograma->filhos) {
                if ($request->get('associado')) {
                    throw new Exception('Operação não permitida.');
                }
                $this->validaPersistencia($estruturaOrganograma->filhos, $request->get('departamento'));
            }
        } else {
            $organograma = new Organograma();
        }
        $organograma->db122_depart = $request->get('departamento');
        $organograma->db122_descricao = $request->get('descricao');
        $organograma->db122_departfilho = $request->get('departamentofilho');
        $organograma->db122_associado = $request->get('associado');
        $organograma->db122_instit = $request->get('DB_instit');
        $organograma->save();
    }

    /**
     * Metódo responsável por excluir e alterar o filhos caso possua
     * @param integer código do departamento
     */
    public function excluir($departamento)
    {
        $organograma = Organograma::query()
            ->where('db122_departfilho', '=', $departamento)
            ->first();
        if ($organograma instanceof Organograma) {
            Organograma::query()
                ->where('db122_depart', '=', $departamento)
                ->get()
                ->each(function (Organograma $organogramaFilho) use ($organograma) {
                    $organogramaFilho->db122_depart = $organograma->db122_depart;
                    $organogramaFilho->save();
                });
            
            $organograma->delete();
        }
    }

    /**
     * Válida se o departamento pai selecionado é filho do departamento da requisição,
     * caso seja, não permite a alteração.
     * @throws Exception
     */
    private function validaPersistencia($filhos, $departamento)
    {
        foreach ($filhos as $filho) {
            if ($filho->departamento == $departamento) {
                throw new Exception('Não é possível realizar esta operação.');
            }
            if ($filho->filhos) {
                $this->validaPersistencia($filho->filhos, $departamento);
            }
        }
    }
}
