<?php

namespace App\Domain\Educacao\Escola\Services;

use App\Domain\Educacao\Escola\Models\Matricula;
use App\Domain\Educacao\Escola\Models\Turma;
use DBDate;
use DBException;
use Etapa;
use Exception;
use MatriculaRepository;
use TurmaRepository;

/**
 * Class TrocaAlunosTurmaService
 * @package App\Domain\Educacao\Escola\Services
 */
class TrocaAlunosTurmaService
{
    /**
     * @var Turma
     */
    private $turmaOrigem;

    /**
     * @var Turma
     */
    private $turmaDestino;
    /**
     * @var Matricula[]
     */
    private $matriculas;
    /**
     * @var array
     */
    private $regencias;
    /**
     * @var array
     */
    private $procedimentos;
    /**
     * @var DBDate
     */
    private $dataAlteracao;
    /**
     * @var Etapa
     */
    private $etapaDestino;
    /**
     * @var array
     */
    private $turnos;
    /**
     * @var string
     */
    private $importarAvaliacoes;

    /**
     * TrocaAlunosTurmaService constructor.
     * @param $turmaDestino
     * @param $matriculas
     * @param $regencias
     * @param $procedimentos
     * @param $dataAlteracao
     * @param $etapaDestino
     * @param $turnos
     * @param $importarAvaliacoes
     * @throws DBException
     */
    public function __construct(
        $turmaDestino,
        $matriculas,
        $regencias,
        $procedimentos,
        $dataAlteracao,
        $etapaDestino,
        $turnos,
        $importarAvaliacoes,
        $turmaOrigem = null
    ) {
        $this->turmaDestino = TurmaRepository::getTurmaByCodigo($turmaDestino);
        $this->turmaOrigem = TurmaRepository::getTurmaByCodigo($turmaOrigem);
        $this->dataAlteracao = DBDate::create($dataAlteracao);
        $this->etapaDestino = new Etapa($etapaDestino);
        $this->matriculas = [];
        foreach ($matriculas as $matricula) {
            $this->matriculas[] = MatriculaRepository::getMatriculaByFiltros(
                [
                    "ed60_matricula = {$matricula}",
                    "ed60_c_situacao = 'MATRICULADO'"
                ]
            );
        }
        $this->regencias = $regencias;
        $this->procedimentos = $procedimentos;
        $this->turnos = implode(',', $turnos);
        $this->importarAvaliacoes = $importarAvaliacoes;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function processar()
    {
        if (!DBDate::dataEstaNoIntervalo(
            $this->dataAlteracao,
            $this->turmaOrigem->getCalendario()->getDataInicio(),
            $this->turmaOrigem->getCalendario()->getDataFinal()
        )) {
            throw new Exception("Data de Alteração fora do período do Calendário!");
        }
        foreach ($this->matriculas as $matricula) {
            $trocaTurma = new \TrocaTurma($matricula, $this->turmaDestino, $this->turnos);

            $trocaTurma->trocarTurmaComRegistro(
                $this->dataAlteracao,
                $this->etapaDestino,
                $this->importarAvaliacoes,
                $this->regencias
            );
        }
        return "Troca de turma realizada com sucesso.";
    }
}
