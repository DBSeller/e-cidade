<?php

namespace App\Domain\Saude\Laboratorio\Services;

class RequisicaoExameService
{
    /**
     * @param integer $idRequisicao
     * @return object
     * @throws \Exception
     */
    public function getInfo($idRequisicao, $idPaciente)
    {
        $requisicaoLaboratorial = new \RequisicaoLaboratorial((int)$idRequisicao);
        if ($requisicaoLaboratorial->getCgs()->getCodigo() != $idPaciente) {
            throw new \Exception('Requisição não pertence ao paciente informado.', 401);
        }

        $info = (object)[
            'medico' => $requisicaoLaboratorial->getMedico(),
            'paciente' => $requisicaoLaboratorial->getCgs()->getNome(),
            'examesSolicitados' => []
        ];
        foreach ($requisicaoLaboratorial->getRequisicoesDeExames() as $requisicaoExame) {
            $exame = $requisicaoExame->getExame();
            $info->examesSolicitados[] = (object)[
                'codigo' => $requisicaoExame->getCodigo(),
                'descricao' => $exame->getNome(),
                'situacao' => $requisicaoExame->getSituacao()
            ];
        }

        return $info;
    }
}
