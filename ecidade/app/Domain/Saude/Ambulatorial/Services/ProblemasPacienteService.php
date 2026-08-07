<?php

namespace App\Domain\Saude\Ambulatorial\Services;

use App\Domain\Saude\Ambulatorial\Models\Problema;
use ECidade\Saude\Ambulatorial\Service\ProntuarioService;
use App\Domain\Saude\Ambulatorial\Models\ProblemaPaciente;
use App\Domain\Saude\Ambulatorial\Resources\ProblemasPacienteResource;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use App\Domain\Saude\Ambulatorial\Requests\SalvarProblemasPacienteRequest;
use App\Domain\Saude\Ambulatorial\Repositories\ProblemasPacienteRepository;

class ProblemasPacienteService
{
    /**
     * Salva a partir da request
     * @param SalvarProblemasPacienteRequest $request
     * @throws NotAcceptableHttpException
     */
    public function salvarFromRequest(SalvarProblemasPacienteRequest $request)
    {
        if ($request->ativo && $this->hasProblemaAtivo($request->paciente, $request->problema, $request->get('id'))) {
            $mensagem = 'Operação não permitida. Problema/Condição já adicionado como ativo para o paciente!';
            throw new NotAcceptableHttpException($mensagem);
        }

        $dados = ProblemasPacienteResource::requestToObject($request);
        ProblemasPacienteRepository::salvar($dados);

        if ($request->get('problema') == Problema::PRE_NATAL) {
            $dum = new \DateTime($request->get('dataInicio'));
            ProntuarioService::salvarDadosGestante($request->get('prontuario'), $dum);
        }
    }

    /**
     * @param integer $id
     */
    public function apagar($id)
    {
        $problema = ProblemaPaciente::find($id);

        if ($problema->prontuarios->isNotEmpty()) {
            $erro = 'Não foi possível excluir o problema/condição. Problema/condição já vinculado á uma FAA!';
            throw new \BusinessException($erro);
        }

        $problema->delete();
    }

    /**
     * Retorna um array com os problemas do Paciente
     * @param integer $idPaciente
     * @return array
     */
    public function getProblemasPaciente($idPaciente)
    {
        $repository = new ProblemasPacienteRepository;
        $dados = $repository->getByPaciente($idPaciente);

        return ProblemasPacienteResource::toArray($dados);
    }

    /**
     * Valida se o paciente possui o problema informado já incluso como ativo
     * @param integer $idPaciente
     * @param integer $idProblema
     * @return boolean
     */
    private function hasProblemaAtivo($idPaciente, $idProblema, $id = null)
    {
        $dados = ProblemaPaciente::where('s170_paciente', $idPaciente)
            ->where('s170_id', '!=', $id)
            ->where('s170_problema', $idProblema)
            ->where('s170_ativo', true)
            ->get();

        return $dados->isNotEmpty();
    }
}
