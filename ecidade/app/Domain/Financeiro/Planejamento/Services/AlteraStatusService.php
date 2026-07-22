<?php


namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\Status;
use ECidade\Enum\Financeiro\Planejamento\StatusEnum;
use ECidade\Enum\Financeiro\Planejamento\TipoEnum;

/**
 * Class AlteraStatusService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class AlteraStatusService extends PlanejamentoService
{

    /**
     * Movimenta o Plano de governo alterando a situação (pl2_status) do Plano
     * @param integer $idPlano
     * @param integer $idStatus
     */
    public function alterar($idPlano, $idStatus)
    {
        $plano = Planejamento::find($idPlano);
        $status = Status::find($idStatus);

        $situacoes = [StatusEnum::EM_DESENVOLVIMENTO, StatusEnum::ENCAMINHADO_PODER_LEGISLATIVO];
        if (in_array($status->pl1_codigo, $situacoes)) {
            $this->persisteAlteracao($plano, $status);
        }

        // plano aprovado com emendas deve ser realizada uma cópia do plano e criado um novo plano em desenvolvimento
        if ($status->pl1_codigo === StatusEnum::APROVADO_EMENDAS) {
            $this->duplicaPlano($plano, $status);
        }

        if ($status->pl1_codigo === StatusEnum::APROVADO) {
            if ($plano->pl2_tipo === TipoEnum::LOA) {
                $ldo = $plano->getPlanoPai();
                $ppa = $ldo->getPlanoPai();
                // se for a ultima loa do PPA deve alterar o campo pl2_ativo de todos para false
                if ($ppa->pl2_ano_final === $plano->pl2_ano_final) {
                    $this->inativarPlano($ppa);
                } else {
                    $this->persisteAlteracao($plano, $status);
                }
            } else {
                $this->persisteAlteracao($plano, $status);
            }
        }
    }

    /**
     * @param Planejamento $plano
     * @param Status $status
     */
    private function persisteAlteracao(Planejamento $plano, Status $status)
    {
        $plano->status()->associate($status);
        $plano->save();
    }

    /**
     * @param Planejamento $plano
     * @param Status $status
     */
    private function duplicaPlano(Planejamento $plano, Status $status)
    {
        $novoPlano = $plano->replicate();
        $novoPlano->status()->associate(Status::find(StatusEnum::EM_DESENVOLVIMENTO));
        $novoPlano->push();

        $replicar = new ReplicarPlanoService($novoPlano, $plano);
        $replicar->replicar();

        $plano->pl2_ativo = 'f';
        $this->persisteAlteracao($plano, $status);
    }

    /**
     * @param Planejamento $plano
     */
    private function inativarPlano(Planejamento $plano)
    {
        $planejamentos = $plano->getPlanosFilhos();
        if (count($planejamentos) > 0) {
            foreach ($planejamentos as $planejamento) {
                $this->inativarPlano($planejamento);
            }
        }

        $plano->pl2_ativo = 'f';
        $plano->save();
    }
}
