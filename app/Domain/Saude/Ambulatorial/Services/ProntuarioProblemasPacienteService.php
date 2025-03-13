<?php

namespace App\Domain\Saude\Ambulatorial\Services;

use App\Domain\Saude\Ambulatorial\Models\Prontuario;
use App\Domain\Saude\Ambulatorial\Repositories\ProblemasPacienteRepository;
use App\Domain\Saude\ESF\Adapters\ConsultaIndividualProblemasAdapter;
use App\Domain\Saude\ESF\Factories\ConsultaIndividualFactory;

/**
 * @package App\Domain\Saude\Ambulatorial\Services
 */
class ProntuarioProblemasPacienteService
{
    /**
     * Vincula o prontuario com os problemas ativos do Paciente, caso o parametro salvarEsf seja true
     * salva os problemas ativos na tabela plugins.psf_consulta_medica
     * @param integer $idProntuario
     * @param bool $salvarEsf
     */
    public static function vincular($idProntuario, $salvarEsf = false)
    {
        $idPaciente = Prontuario::find($idProntuario)->sd24_i_numcgs;
        $problemasPaciente = (new ProblemasPacienteRepository)->getByPaciente($idPaciente, true);

        $consultaIndividualAdapter = new ConsultaIndividualProblemasAdapter($idProntuario);

        foreach ($problemasPaciente as $problemaPaciente) {
            static::salvar($idProntuario, $problemaPaciente->s170_id);
            $consultaIndividualAdapter->addProblema($problemaPaciente->problema);
        }

        if ($salvarEsf) {
            $repository = ConsultaIndividualFactory::getRepository();
            $repository->salvar($consultaIndividualAdapter);
        }
    }

    /**
     * Salva o vinculo com o prontuario se o mesmo não existir
     * @param integer $idProntuario
     * @param integer $idProblemaPaciente
     */
    public static function salvar($idProntuario, $idProblemaPaciente)
    {
        $problemaPaciente = (new ProblemasPacienteRepository)->getByProntuario($idProntuario, $idProblemaPaciente);
    
        if ($problemaPaciente->isNotEmpty()) {
            return;
        }

        ProblemasPacienteRepository::vincularProntuario($idProntuario, $idProblemaPaciente);
    }
}
