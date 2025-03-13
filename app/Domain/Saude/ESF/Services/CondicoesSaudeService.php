<?php

namespace App\Domain\Saude\ESF\Services;

use App\Domain\Saude\ESF\Models\CadastroIndividual;
use App\Domain\Saude\ESF\Relatorios\CondicoesSaudePdf;
use ECidade\Enum\Common\FaixaEtariaEnum;
use ECidade\Enum\Saude\ESF\SituacaoCondicaoPacienteEnum;
use Exception;
use Illuminate\Support\Collection;
use stdClass;

class CondicoesSaudeService
{
    /**
     * @param stdClass $filtros
     * @return CondicoesSaudePdf
     * @throws Exception
     */
    public function gerarRelatorio(stdClass $filtros)
    {
        $dados = [];

        $pacientes = $this->buscarDados($filtros);
        if ($pacientes->isEmpty()) {
            throw new Exception('Nenhum registro encontrado com os filtros informados.');
        }

        foreach ($pacientes as $paciente) {
            $key = "{$paciente->coddepto}#{$paciente->psf_id}#{$paciente->psf5_profissional_microarea}";
            if (!array_key_exists($key, $dados)) {
                $dados[$key] = (object)[
                    'departamento' => $paciente->descrdepto,
                    'equipe' => $paciente->psf_nome_equipe,
                    'microarea' => $paciente->sd34_v_descricao,
                    'pacientes' => []
                ];
            }

            $dados[$key]->pacientes[] = (object)[
                'id' => $paciente->z01_i_cgsund,
                'nome' => $paciente->z01_v_nome,
                'sexo' => $paciente->z01_v_sexo === 'M' ? 'Masculino' : 'Feminino'
            ];

            if ($filtros->condicao == SituacaoCondicaoPacienteEnum::DEFICIENCIA) {
                $deficiencia = 'OUTRA';
                if ($paciente->psf5_deficiencia_auditiva) {
                    $deficiencia = 'Auditiva';
                }
                if ($paciente->psf5_deficiencia_visual) {
                    $deficiencia = 'Visual';
                }
                if ($paciente->psf5_deficiencia_intelectual) {
                    $deficiencia = 'Intelectual/Cognitiva';
                }
                if ($paciente->psf5_deficiencia_fisica) {
                    $deficiencia = 'Física';
                }
                end($dados[$key]->pacientes)->deficiencia = $deficiencia;
            }
        }

        return new CondicoesSaudePdf(array_values($dados), $filtros);
    }

    /**
     * @param stdClass $filtros
     * @return Collection
     * @throws Exception
     */
    public function buscarDados(stdClass $filtros)
    {
        $campos = [
            'coddepto',
            'descrdepto',
            'psf_id',
            'psf_nome_equipe',
            'psf5_profissional_microarea',
            'sd34_v_descricao',
            'z01_i_cgsund',
            'z01_v_nome',
            'z01_v_sexo',
            'psf5_deficiencia_auditiva',
            'psf5_deficiencia_visual',
            'psf5_deficiencia_intelectual',
            'psf5_deficiencia_fisica',
            'psf5_deficiencia_outra'
        ];
        $query = CadastroIndividual::select($campos)
            ->unidade($filtros->unidade)
            ->equipe($filtros->equipe)
            ->paciente()
            ->microarea($filtros->microarea)
            ->data(new \DateTime($filtros->data))
            ->condicao($filtros->condicao ? new SituacaoCondicaoPacienteEnum((int)$filtros->condicao) : null)
            ->ativo();

        if ($filtros->sexo) {
            $query->sexo($filtros->sexo);
        }
        if ($filtros->faixaEtaria) {
            $query->faixaEtaria(new FaixaEtariaEnum((int)$filtros->faixaEtaria));
        }

        $query->orderBy('descrdepto')->orderBy('psf_nome_equipe')->orderBy('sd34_v_descricao')->orderBy('z01_v_nome');

        return $query->get();
    }
}
