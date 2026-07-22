<?php

namespace App\Domain\Saude\Ambulatorial\Repositories;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Saude\Ambulatorial\Models\ProblemaPaciente;

/**
 * @package App\Domain\Saude\Ambulatorial\Repositories
 */
class ProblemasPacienteRepository extends BaseRepository
{
    protected $modelClass = ProblemaPaciente::class;

    public static function salvar(\stdClass $dados)
    {
        $model = static::getModel($dados->id);
    
        if ($dados->id == null) {
            $model->s170_problema = $dados->problema;
            $model->s170_paciente = $dados->paciente;
            $model->s170_usuario = $dados->usuario;
            $model->s170_data = (new \DateTime())->format('Y-m-d');
        }
        $model->s170_data_inicio = $dados->dataInicio;
        $model->s170_data_fim = $dados->dataFim;
        $model->s170_ativo = $dados->ativo;
        $model->save();

        return $model;
    }

    public static function vincularProntuario($idProntuario, $idProblemaPaciente)
    {
        $problemaPaciente = ProblemaPaciente::find($idProblemaPaciente);
        $problemaPaciente->prontuarios()->attach($idProntuario);
    }

    public static function getModel($id = null)
    {
        if ($id != null) {
            return ProblemaPaciente::find($id);
        }

        return new ProblemaPaciente;
    }

    public function getByPaciente($idPaciente, $somenteAtivo = false)
    {
        $query = $this->newQuery();
        $query->where('s170_paciente', $idPaciente)
            ->orderByDesc('s170_ativo')
            ->orderByDesc('s170_data')
            ->orderByDesc('s170_id');

        if ($somenteAtivo) {
            $query->where('s170_ativo', true);
        }

        return $query->get();
    }

    public function getByProntuario($idProntuario, $idProblemaPaciente = null)
    {
        $query = $this->newQuery();
        $query->join('ambulatorial.prontuario_problemaspaciente', 's171_problemapaciente', 's170_id');
        $query->where('s171_prontuario', $idProntuario);

        if ($idProblemaPaciente) {
            $query->where('s171_problemapaciente', $idProblemaPaciente);
        }

        return $query->get();
    }
}
