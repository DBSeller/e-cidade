<?php

namespace App\Domain\Educacao\Escola\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Educacao\Escola\Models\Etapa;
use App\Domain\Educacao\Escola\Models\Turma;
use App\Domain\Educacao\Escola\Models\TurmaEspecial;
use App\Domain\Educacao\Escola\Requests\TrocaAlunosTurmaRequest;
use App\Domain\Educacao\Escola\Services\TrocaAlunosTurmaService;
use App\Http\Controllers\Controller;
use ECidade\Enum\Educacao\Escola\SituacaoMatriculaEnum;
use Exception;
use Illuminate\Http\Request;
use Regencia;
use stdClass;

/**
 * Class TurmasController
 * @package App\Domain\Educacao\Escola\Controllers
 */
class TurmasController extends Controller
{
    public function buscar($codigo)
    {
        $turma = Turma::with('calendario')->find($codigo);
        $turma->etapas = $turma->getEtapas();
        return new DBJsonResponse($turma);
    }

    public function matriculasEtapa(Turma $turma, Etapa $etapa)
    {
        $matriculas = $turma->getMatriculas();
        foreach ($matriculas as $key => $matricula) {
            if ($matricula['ed60_c_concluida'] == "S") {
                unset($matriculas[$key]);
                continue;
            }
            if ($matricula['ed60_c_ativa'] != "S") {
                unset($matriculas[$key]);
                continue;
            }
            if ($matricula['ed60_c_situacao'] != SituacaoMatriculaEnum::MATRICULADO) {
                unset($matriculas[$key]);
                continue;
            }
            $etapaMatricula = $matricula->getEtapaMatricula()->shift();
            if ($etapaMatricula->ed11_i_codigo !== $etapa->ed11_i_codigo) {
                unset($matriculas[$key]);
                continue;
            }
            $matricula->aluno;
        }
        $matriculas = array_values($matriculas->toArray());

        return new DBJsonResponse($matriculas);
    }

    public function vagas(Turma $turma)
    {
        foreach ($turma->turnosReferentes as $turnoReferente) {
            $turnoReferente->turno = (object)[
                "nome" => ''
            ];
            switch ($turnoReferente->ed336_turnoreferente) {
                case 1:
                    $turnoReferente->turno->nome = 'MANHÃ';
                    break;
                case 2:
                    $turnoReferente->turno->nome = 'TARDE';
                    break;
                case 3:
                    $turnoReferente->turno->nome = 'NOITE';
                    break;
            }
        }
        return new DBJsonResponse($turma);
    }

    public function regenciasTurmas(Turma $turmaOrigem, Turma $turmaDestino, Etapa $etapa)
    {
        $turmaOrigem->etapaRegimeMatricula;
        $turmaDestino->etapaRegimeMatricula;

        foreach ($turmaOrigem->etapaRegimeMatricula->procedimento->procedimentosAvaliacao as $procedimentoAvaliacao) {
            $procedimentosAvaliacaoDestino = $turmaDestino->etapaRegimeMatricula->procedimento->procedimentosAvaliacao;
            foreach ($procedimentosAvaliacaoDestino as $key => $procedimentoAvaliacaoDestino) {
                $periodoOrigem = $procedimentoAvaliacao['ed41_i_periodoavaliacao'];
                $periodoDestino = $procedimentoAvaliacaoDestino['ed41_i_periodoavaliacao'];
                if ($periodoOrigem === $periodoDestino) {
                    $procedimentoAvaliacao->equivalente = $procedimentoAvaliacaoDestino;
                    unset($procedimentosAvaliacaoDestino[$key]);
                }
            }
        }

        foreach ($turmaOrigem->regencias as $key => $regenciaOrigem) {
            if ($regenciaOrigem['ed59_i_serie'] !== $etapa['ed11_i_codigo']) {
                unset($turmaOrigem->regencias[$key]);
                continue;
            }
            foreach ($turmaDestino->regencias as $key => $regenciaDestino) {
                if ($regenciaOrigem['ed59_i_disciplina'] === $regenciaDestino['ed59_i_disciplina']) {
                    $regenciaOrigem->equivalente = $regenciaDestino;
                    unset($turmaDestino->regencias[$key]);
                }
            }
        }

        $turmaOrigem->regenciasOrigem = array_values($turmaOrigem->regencias->toArray());
        $turmaDestino->regenciasSemVinculo = array_values($turmaDestino->regencias->toArray());
        return new DBJsonResponse([$turmaOrigem, $turmaDestino]);
    }

    /**
     * @param TrocaAlunosTurmaRequest $request
     * @return DBJsonResponse
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     * @throws Exception
     */
    public function trocarAlunosTurma(TrocaAlunosTurmaRequest $request)
    {
        $turmaDestino = $request->get('turmaDestino');
        $turmaOrigem =  $request->get('turmaOrigem');
        $matriculas = $request->get('matriculas');
        $regencias = $request->get('regencias');
        $dataAlteracao = $request->get('dataAlteracao');
        $etapaDestino = $request->get('etapaDestino');
        $turnos = $request->get('turnosReferentes');
        $importarAvaliacoes = $request->get('importarAvaliacoes');

        $regenciasVinculadas = [];
        if (is_null($regencias)) {
            throw new Exception("Erro ao buscar disciplinas na turma de Origem.");
        }
        foreach ($regencias as $key => $regencia) {
            $regencia = json_decode(str_replace('\\"', '"', $regencia));
            if (!empty($regencia->equivalente)) {
                $oRegencia              = new stdClass();
                $oRegencia->origem      = new Regencia($regencia->regenciaOrigem);
                $oRegencia->destino     = new Regencia($regencia->equivalente->regenciaDestino);
                $regenciasVinculadas[] = $oRegencia;
            }
        }
        $procedimentos = $request->get('procedimentosAvaliacao');
        foreach ($procedimentos as $key => $procedimento) {
            $string = str_replace('\\"', '"', $procedimento);
            $procedimentos[$key] = json_decode($string);
        }

        $trocaAlunosService = new TrocaAlunosTurmaService(
            $turmaDestino,
            $matriculas,
            $regenciasVinculadas,
            $procedimentos,
            $dataAlteracao,
            $etapaDestino,
            $turnos,
            $importarAvaliacoes,
            $turmaOrigem
        );

        $retorno = $trocaAlunosService->processar();
        return new DBJsonResponse('', $retorno);
    }


    public function buscarTurmasEspeciaisPorCalendarioEscola(Request $request)
    {
        $turmas = TurmaEspecial::select('ed268_i_codigo as id', 'ed268_c_descr as descricao')
            ->getPorCalendarioEscola($request->get('calendario'), $request->get('escola'))
            ->get()->all();

        return new DBJsonResponse($turmas, '');
    }
}
