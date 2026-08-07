<?php

namespace App\Domain\Saude\Ambulatorial\Services;

use App\Domain\Saude\Ambulatorial\Relatorios\AcompanhamentoAcsPDF;
use Illuminate\Database\Eloquent\Collection;

class AcompanhamentoAcsService
{
    /**
     * Retorna um relátorio com um cabecelha por paciente
     * @param Collection $acompanhamentos
     * @return AcompanhamentosAcsPDF $pdf
     */
    public function gerarRelatorioPorPaciente(Collection $acompanhamentos)
    {
        $dados = [];

        foreach ($acompanhamentos as $acompanhamento) {
            $paciente = $acompanhamento->paciente;
            
            $familia = '';
            $microarea = '';
            if ($paciente->familiaMicroarea) {
                $familia = $paciente->familiaMicroarea->familia->sd33_v_descricao;
                $microarea = $paciente->familiaMicroarea->microarea->sd34_v_descricao;
            }

            $cartaoSus = '';
            if ($paciente->cgs->cartaoSusDefinitivo) {
                $cartaoSus = $paciente->cgs->cartaoSusDefinitivo->s115_c_cartaosus;
            } elseif ($paciente->cgs->cartoesSus) {
                $cartaoSus = $paciente->cgs->cartoesSus->first()->s115_c_cartaosus;
            }

            if (!array_key_exists($acompanhamento->s168_paciente, $dados)) {
                $dados[$acompanhamento->s168_paciente] = (object) [
                    'id' => $acompanhamento->s168_paciente,
                    'nome' => $paciente->z01_v_nome,
                    'microarea' => $microarea,
                    'familia' => $familia,
                    'sexo' => $paciente->z01_v_sexo == 'F' ? 'Feminino' : 'Masculino',
                    'data_nascimento' => db_formatar($paciente->z01_d_nasc, 'd'),
                    'municipio_nascimento' => $paciente->z01_v_munic,
                    'nome_pai' => $paciente->z01_v_pai,
                    'nome_mae' => $paciente->z01_v_mae,
                    'endereco' => "{$paciente->z01_v_ender}, {$paciente->z01_i_numero}",
                    'bairro' => $paciente->z01_v_bairro,
                    'telefone' => $paciente->z01_v_telef,
                    'celular' => $paciente->z01_v_telcel,
                    'cpf' => $paciente->z01_v_cgccpf,
                    'cns' => $cartaoSus,
                    'acompanhamentos' => []
                ];
            }

            $dados[$acompanhamento->s168_paciente]->acompanhamentos[] = $acompanhamento;
        }
        
        $pdf = new AcompanhamentoAcsPDF($dados);

        return $pdf;
    }
}
