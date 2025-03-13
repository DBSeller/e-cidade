<?php

namespace App\Domain\Saude\ESF\Services;

use App\Domain\Saude\ESF\Models\FichaVacinacao;
use ECidade\Enum\Saude\ESF\DoseEnum;
use ECidade\Enum\Saude\ESF\EstrategiaVacinacaoEnum;
use ECidade\Enum\Saude\ESF\SituacaoPacienteVacinacaoEnum;

class FichaVacinacaoService
{
    public static function getVacinasPorPaciente($cgs)
    {
        $self = new self();
        $vacinas = [];
        $fichas = FichaVacinacao::where('psf20_cgs', $cgs)
            ->join('plugins.psf_ficha_vacinacao_profissional', 'psf20a_id', 'psf20_id_profissional')
            ->orderByDesc('psf20a_profissional_data')
            ->get();

        foreach ($fichas as $ficha) {
            $profissional = $ficha->profissional;
            $unidade = $profissional->unidade;

            foreach ($ficha->vacinas as $vacina) {
                $vacinas[] = (object)[
                    'data' => db_formatar($profissional->psf20a_profissional_data, 'd'),
                    'id_unidade' => $unidade->sd02_i_codigo,
                    'descricao_unidade' => $unidade->departamento->descrdepto,
                    'profissional' => $profissional->cgm->z01_nome,
                    'imunobiologico' => $vacina->imunobiologico->psf22_descricao,
                    'situacao' => !empty($self->getSituacoes($ficha)) ? $self->getSituacoes($ficha) : 'NÃO INFORMADO',
                    'estrategia' => (new EstrategiaVacinacaoEnum($vacina->psf21_estrategia))->name(),
                    'dose' => (new DoseEnum($vacina->psf21_dose))->name(),
                    'lote' => $vacina->psf21_lote,
                    'fabricante' => $vacina->psf21_fabricante
                ];
            }
        }

        return $vacinas;
    }

    private function getSituacoes(FichaVacinacao $ficha)
    {
        $situacoes = [];
        
        if ($ficha->psf20_comunicante_hanseniase) {
            $situacoes[] = (new SituacaoPacienteVacinacaoEnum(1))->name();
        }
        if ($ficha->psf20_gestante) {
            $situacoes[] = (new SituacaoPacienteVacinacaoEnum(2))->name();
        }
        if ($ficha->psf20_puerpera) {
            $situacoes[] = (new SituacaoPacienteVacinacaoEnum(3))->name();
        }
        if ($ficha->psf20_viajante) {
            $situacoes[] = (new SituacaoPacienteVacinacaoEnum(4))->name();
        }

        return implode(', ', $situacoes);
    }
}
