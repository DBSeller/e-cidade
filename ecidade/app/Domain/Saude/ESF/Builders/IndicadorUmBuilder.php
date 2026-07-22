<?php

namespace App\Domain\Saude\ESF\Builders;

use Illuminate\Database\Eloquent\Collection;
use App\Domain\Saude\ESF\Services\EquipesService;
use App\Domain\Saude\Ambulatorial\Models\Prontuario;
use App\Domain\Saude\Ambulatorial\Models\ProblemaPaciente;
use App\Domain\Saude\Ambulatorial\Services\PacienteService;

/**
 * @package App\Domain\Saude\ESF\Builders
 */
class IndicadorUmBuilder
{
    /**
     * @var Collection
     */
    private $dados;

    /**
     * @var \DateTime
     */
    private $periodoFim;

    /**
     * @param Collection $dados
     * @return IndicadorUmBuilder
     */
    public function setDados(Collection $dados)
    {
        $this->dados = $dados;
        return $this;
    }

    /**
     * @param \DateTime $periodoFim
     * @return IndicadorUmBuilder
     */
    public function setPeriodoFim(\DateTime $periodoFim)
    {
        $this->periodoFim = $periodoFim;
        return $this;
    }

    /**
     * @return array
     */
    public function build()
    {
        $dados = [];
        foreach ($this->dados as $prontuario) {
            if (array_key_exists($prontuario->sd24_i_numcgs, $dados)) {
                continue;
            }
            $dados[$prontuario->sd24_i_numcgs] = $this->buildPaciente($prontuario);
        }

        return array_values($dados);
    }

    /**
     * @param Prontuario $prontuario
     * @return \stdClass
     */
    private function buildPaciente(Prontuario $prontuario)
    {
        $paciente = $prontuario->paciente;
        $cartaoSus = PacienteService::getCartaoSus($paciente);
        $preNatal = $prontuario->problemasPaciente()->preNatal()->first();

        return (object)[
            'id' => $paciente->z01_i_cgsund,
            'nome' => $paciente->z01_v_nome,
            'nascimento' => db_formatar($paciente->z01_d_nasc, 'd'),
            'sexo' => $paciente->z01_v_sexo == 'F' ? 'FEMININO' : 'MASCULINO',
            'cns' => $cartaoSus ? $cartaoSus->s115_c_cartaosus : 'NÃO INFORMADO',
            'cpf' => $paciente->z01_v_cgccpf ? $paciente->z01_v_cgccpf : 'NÃO INFORMADO',
            'situacao' => $preNatal->s170_ativo ? 'ATIVO' : 'RESOLVIDO',
            'dum' => $preNatal->s170_data_inicio->format('d/m/Y'),
            'dataParto' => $preNatal->s170_data_fim ? $preNatal->s170_data_fim->format('d/m/Y') : '',
            'atendimentos' => $this->buildAtendimentos($preNatal)
        ];
    }

    /**
     * @param ProblemaPaciente $preNatal
     * @return array
     */
    private function buildAtendimentos(ProblemaPaciente $preNatal)
    {
        $dados = [];
        $prontuarios = $this->getProntuariosElegiveis($preNatal);
        foreach ($prontuarios as $prontuario) {
            $profissional = $this->getProfissionalAtendimento($prontuario);
            
            $dados[] = (object)[
                'id' => $prontuario->sd24_i_codigo,
                'data' => $prontuario->dataAtendimento->format('d/m/Y'),
                'unidade' => $prontuario->unidade->departamento->descrdepto,
                'equipe' => $this->getEquipeProfissional($prontuario),
                'profissional' => $profissional->nome,
                'cbo' => $profissional->cbo,
                'ig' => $this->getIdadeGestacional($prontuario),
                'cids' => $this->getCids($prontuario),
                'ciap2' => $this->getCiap2($prontuario)
            ];
        }

        return $dados;
    }

    /**
     * Retorna todos os atendimentos em que a gestante estava com o problema pré-natal ativo
     * @param ProblemaPaciente $preNatal
     * @return array
     */
    private function getProntuariosElegiveis(ProblemaPaciente $preNatal)
    {
        $prontuarios = [];
        foreach ($preNatal->prontuarios as $prontuario) {
            /**
             * Retorna a menor data dos procedimentos, pois o sistema não permite alteração da data da tabela
             * prontuarios e o registro do atendimento pode estar sendo feito de forma tardia. Por exemplo, devido à uma
             * queda de energia.
             */
            $procedimento = $prontuario->procedimentos()->orderBy('sd29_d_data')->first();
            if ($procedimento == null || $procedimento->sd29_d_data > $this->periodoFim) {
                continue;
            }

            $prontuario->dataAtendimento = $procedimento->sd29_d_data;
            $prontuarios[] = $prontuario;
        }

        // Ordena pela data em ordem crescente
        usort($prontuarios, function ($a, $b) {
            $timeA = $a->dataAtendimento->getTimestamp();
            $timeB = $b->dataAtendimento->getTimestamp();
            return $timeA - $timeB;
        });

        return $prontuarios;
    }

    /**
     * @param Prontuario $prontuario
     * @return \stdClass
     */
    private function getProfissionalAtendimento(Prontuario $prontuario)
    {
        $profissionalAtendimento = $prontuario->profissionalAtendimento;
        if (!$profissionalAtendimento) {
            return (object)[
                'nome' => 'NÃO INFORMADO',
                'cbo' => 'NÃO INFORMADO'
            ];
        }
        
        $cbo = $profissionalAtendimento->cbo->rh70_estrutural;
        $profissional = $profissionalAtendimento->especialidade->profissionalUnidade->profissional;

        return (object)[
            'cgm' => $profissional->cgm->z01_numcgm,
            'nome' => $profissional->cgm->z01_nome,
            'cbo' => $cbo
        ];
    }

    /**
     * @param Prontuario $prontuario
     * @return string
     */
    private function getEquipeProfissional(Prontuario $prontuario)
    {
        $profissional = $this->getProfissionalAtendimento($prontuario);
        if (!property_exists($profissional, 'cgm')) {
            return 'NÃO INFORMADO';
        }

        $equipe = EquipesService::getEquipeProfissional($profissional->cgm, $prontuario->sd24_i_unidade);
        if ($equipe === null) {
            return 'NÃO INFORMADO';
        }

        return $equipe->psf_nome_equipe;
    }

    /**
     * @param Prontuario $prontuario
     * @return string
     */
    private function getIdadeGestacional(Prontuario $prontuario)
    {
        $dum = $prontuario->problemasPaciente()->preNatal()->first()->s170_data_inicio;
        $idade = date_diff($dum, $prontuario->dataAtendimento);

        $semanas = (int)$idade->format('%a') / 7;
        $semanas = (int)$semanas;

        $dias = (int)$idade->format('%a') % 7;
        $dias = (int)$dias;

        return "{$semanas} semanas e {$dias} dias";
    }

    /**
     * @param Prontuario $prontuario
     * @return string
     */
    private function getCiap2(Prontuario $prontuario)
    {
        $ciap = $prontuario->prontuarioEsf->ciap;
        if ($ciap === null) {
            return 'NÃO INFORMADO';
        }

        return "{$ciap->codigo} - {$ciap->titulo_original}";
    }

    /**
     * @param Prontuario $prontuario
     * @return string
     */
    private function getCids(Prontuario $prontuario)
    {
        $cids = [];
        foreach ($prontuario->procedimentos as $procedimento) {
            if ($procedimento->procedimentoCid === null) {
                continue;
            }
            
            $cid = $procedimento->procedimentoCid->cid;
            if ($cid->problemas()->preNatal()->first() == null) {
                continue;
            }
            
            $cids[] = "{$cid->sd70_c_cid} - {$cid->sd70_c_nome}";
        }

        if (empty($cids)) {
            return 'NÃO INFORMADO';
        }

        return implode(', ', $cids);
    }
}
