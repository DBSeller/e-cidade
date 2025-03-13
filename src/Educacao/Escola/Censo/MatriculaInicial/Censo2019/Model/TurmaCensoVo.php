<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model;

use ECidade\Educacao\Escola\Model\CensoDisciplina;
use ECidade\Enum\Educacao\Secretaria\EstruturaCurricularEnum;
use Escola;
use EscolaRepository;

class TurmaCensoVo
{
    /**
     * Turmas que foram unidas a essa pelo fato de serem multietapa
     * @var Array
     */
    private $codigosTurmasVinculadas = array();


    private $codigoTurma;
    private $codigoTurmaUnificada;
    private $codigoInep;
    private $nomeTurma;
    private $nomeEtapa;
    private $codigoTurno;
    private $tipoMediacaoDidaticoPedagogica;
    private $tipoEnsino;
    private $tipoEstruturaCurricular = [];
    private $etapaCenso;
    private $codigoCurso;
    private $tipoTurma;
    private $etapasEF = [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 41, 56, 69, 70, 72];
    private $etapasEI = [1, 2, 3];
    private $etapasEM = [25, 26, 27, 28, 29,30, 31, 32, 33, 34, 35, 36, 37, 38, 71, 74];
    private $etapasFICconc = [68];
    private $etapasFIC = [67, 73];
    private $etapasTecnico = [39, 40, 64];
    private $base;
    /**
     * @var bool
     */
    private $programaMaiseducacao = false;
    /**
     * @var Escola
     */
    private $escola;

    private $diasSemana = array();

    private $escolarizacao = false;
    private $atividadeComplementar = false;
    private $atendimentoAEE = false;

    private $atividadesComplementar = array();

    /**
     * @var TurmaCensoDisciplinaVO[]
     */
    private $disciplinas = array();
    private $turmaUnificada = false;

    private $horaInicio;
    private $horaFim;

    private $localFuncionamento = 0;


    public function getBase()
    {
        return $this->base;
    }

    public function setBase($base)
    {
        $this->base = $base;
    }
    /**
     * Turmas que foram unidas a essa pelo fato de serem multietapa
     * @return Array
     */
    public function getCodigosTurmasVinculadas()
    {
        return $this->codigosTurmasVinculadas;
    }

    public function setCodigosTurmasVinculadas(array $codigosTurmasVinculadas)
    {
        $this->codigosTurmasVinculadas = $codigosTurmasVinculadas;
    }

    /**
     * @return mixed
     */
    public function getCodigoTurma()
    {
        return $this->codigoTurma;
    }

    /**
     * @param mixed $codigoTurma
     * @return TurmaCensoVo
     */
    public function setCodigoTurma($codigoTurma)
    {
        $this->codigoTurma = $codigoTurma;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoInep()
    {
        return $this->codigoInep;
    }

    /**
     * @param mixed $codigoInep
     * @return TurmaCensoVo
     */
    public function setCodigoInep($codigoInep)
    {
        $this->codigoInep = $codigoInep;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomeTurma()
    {
        return $this->nomeTurma;
    }

    /**
     * @param mixed $nomeTurma
     * @return TurmaCensoVo
     */
    public function setNomeTurma($nomeTurma)
    {
        $this->nomeTurma = $nomeTurma;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomeEtapa()
    {
        return $this->nomeEtapa;
    }

    /**
     * @param mixed $nomeEtapa
     * @return TurmaCensoVo
     */
    public function setNomeEtapa($nomeEtapa)
    {
        $this->nomeEtapa = $nomeEtapa;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoMediacaoDidaticoPedagogica()
    {
        return $this->tipoMediacaoDidaticoPedagogica;
    }

    /**
     * @param mixed $tipoMediacaoDidaticoPedagogica
     * @return TurmaCensoVo
     */
    public function setTipoMediacaoDidaticoPedagogica($tipoMediacaoDidaticoPedagogica)
    {
        $this->tipoMediacaoDidaticoPedagogica = $tipoMediacaoDidaticoPedagogica;
        return $this;
    }

    /**
     * @return array
     */
    public function getDiasSemana()
    {
        return $this->diasSemana;
    }

    /**
     * @param array $diasSemana
     * @return TurmaCensoVo
     */
    public function setDiasSemana($diasSemana)
    {
        $this->diasSemana = $diasSemana;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEscolarizacao()
    {
        return $this->escolarizacao;
    }

    /**
     * @param bool $escolarizacao
     * @return TurmaCensoVo
     */
    public function setEscolarizacao($escolarizacao)
    {
        $this->escolarizacao = $escolarizacao;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAtividadeComplementar()
    {
        return $this->atividadeComplementar;
    }

    /**
     * @param bool $atividadeComplementar
     * @return TurmaCensoVo
     */
    public function setAtividadeComplementar($atividadeComplementar)
    {
        $this->atividadeComplementar = $atividadeComplementar;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAtendimentoAEE()
    {
        return $this->atendimentoAEE;
    }

    /**
     * @param bool $atendimentoAEE
     * @return TurmaCensoVo
     */
    public function setAtendimentoAEE($atendimentoAEE)
    {
        $this->atendimentoAEE = $atendimentoAEE;
        return $this;
    }

    /**
     * @return array
     */
    public function getAtividadesComplementar()
    {
        return $this->atividadesComplementar;
    }

    /**
     * @param array $atividadesComplementar
     * @return TurmaCensoVo
     */
    public function setAtividadesComplementar(array $atividadesComplementar)
    {
        $this->atividadesComplementar = $atividadesComplementar;
        return $this;
    }

    /**
     * @return TurmaCensoDisciplinaVO[]
     */
    public function getDisciplinas()
    {
        return $this->disciplinas;
    }

    /**
     * @param TurmaCensoDisciplinaVO[] $disciplinas
     * @return TurmaCensoVo
     */
    public function setDisciplinas($disciplinas)
    {
        $this->disciplinas = $disciplinas;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTurmaUnificada()
    {
        return $this->turmaUnificada;
    }

    /**
     * @param bool $turmaUnificada
     * @return TurmaCensoVo
     */
    public function setTurmaUnificada($turmaUnificada)
    {
        $this->turmaUnificada = $turmaUnificada;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoEnsino()
    {
        return $this->tipoEnsino;
    }

    /**
     * @param mixed $tipoEnsino
     * @return TurmaCensoVo
     */
    public function setTipoEnsino($tipoEnsino)
    {
        $this->tipoEnsino = $tipoEnsino;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getEtapaCenso()
    {
        return $this->etapaCenso;
    }

    /**
     * @param mixed $etapaCenso
     * @return TurmaCensoVo
     */
    public function setEtapaCenso($etapaCenso)
    {
        $this->etapaCenso = $etapaCenso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoCurso()
    {
        return $this->codigoCurso;
    }

    /**
     * @param mixed $codigoCurso
     * @return TurmaCensoVo
     */
    public function setCodigoCurso($codigoCurso)
    {
        $this->codigoCurso = $codigoCurso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoTurma()
    {
        return $this->tipoTurma;
    }

    /**
     * @param mixed $tipoTurma
     * @return TurmaCensoVo
     */
    public function setTipoTurma($tipoTurma)
    {
        $this->tipoTurma = $tipoTurma;
        return $this;
    }

    /**
     * @return bool
     */
    public function isProgramaMaiseducacao()
    {
        return $this->programaMaiseducacao;
    }

    /**
     * @param bool $programaMaiseducacao
     * @return TurmaCensoVo
     */
    public function setProgramaMaiseducacao($programaMaiseducacao)
    {
        $this->programaMaiseducacao = $programaMaiseducacao;
        return $this;
    }

    /**
     * @return Escola
     */
    public function getEscola()
    {
        return $this->escola;
    }

    /**
     * @param Escola $escola
     * @return TurmaCensoVo
     */
    public function setEscola($escola)
    {
        $this->escola = $escola;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoTurmaUnificada()
    {
        return $this->codigoTurmaUnificada;
    }

    /**
     * @param mixed $codigoTurmaUnificada
     * @return TurmaCensoVo
     */
    public function setCodigoTurmaUnificada($codigoTurmaUnificada)
    {
        $this->codigoTurmaUnificada = $codigoTurmaUnificada;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoTurno()
    {
        return $this->codigoTurno;
    }

    /**
     * @param mixed $codigoTurno
     * @return TurmaCensoVo
     */
    public function setCodigoTurno($codigoTurno)
    {
        $this->codigoTurno = $codigoTurno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * @param mixed $horaInicio
     * @return TurmaCensoVo
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHoraFim()
    {
        return $this->horaFim;
    }

    /**
     * @param mixed $horaFim
     * @return TurmaCensoVo
     */
    public function setHoraFim($horaFim)
    {
        $this->horaFim = $horaFim;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocalFuncionamento()
    {
        return $this->localFuncionamento;
    }

    /**
     * @param mixed $localFuncionamento
     * @return TurmaCensoVo
     */
    public function setLocalFuncionamento($localFuncionamento)
    {
        $this->localFuncionamento = $localFuncionamento;
        return $this;
    }

    public function setTipoEstruturaCurricular($estrutura = null)
    {
        if (in_array($this->getEtapaCenso(), $this->etapasEI) ||
            in_array($this->getEtapaCenso(), $this->etapasFICconc) ||
            in_array($this->getEtapaCenso(), $this->etapasTecnico)
        ) {
            $this->tipoEstruturaCurricular[] = EstruturaCurricularEnum::NAO_APLICA;
        }

        if (in_array($this->getEtapaCenso(), $this->etapasEF)) {
            $this->tipoEstruturaCurricular[] = EstruturaCurricularEnum::FORMACAO_GERAL_BASICA;
        }

        if (!is_null($estrutura)) {
            $this->tipoEstruturaCurricular[] = $estrutura;
        }

        if ($this->isAtendimentoAEE()) {
            $this->tipoEstruturaCurricular = [EstruturaCurricularEnum::NAO_APLICA];
        }
    }

    public function getTipoEstruturaCurricular()
    {
        if ($this->isAtendimentoAEE()) {
           // dump($this->tipoEstruturaCurricular);
        }
        return $this->tipoEstruturaCurricular;
    }

    public function getEtapasPorEnsino()
    {
        $retorno['EI'] = $this->etapasEI;
        $retorno['EF'] = $this->etapasEF;
        $retorno['FICconc'] = $this->etapasFICconc;
        $retorno['FIC'] = $this->etapasFIC;
        $retorno['TEC'] = $this->etapasTecnico;

        return $retorno;
    }

    /**
     * @param array $state
     * @return TurmaCensoVo
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('inep_turma', $state)) {
            $self->setCodigoInep($state['inep_turma']);
        }
        if (array_key_exists('base', $state)) {
            $self->setBase($state['base']);
        }
        if (array_key_exists('turma', $state)) {
            $self->setCodigoTurma($state['turma']);
        }
        if (array_key_exists('escola', $state)) {
            $self->setEscola(EscolaRepository::getEscolaByCodigo($state['escola']));
        }
        if (array_key_exists('turno', $state)) {
            $self->setCodigoTurno($state['turno']);
        }
        if (array_key_exists('nome', $state)) {
            $self->setNomeTurma($state['nome']);
        }
        if (array_key_exists('nome_etapa_turma', $state)) {
            $self->setNomeEtapa($state['nome_etapa_turma']);
        }
        if (array_key_exists('tipo_ensino', $state)) {
            $self->setTipoEnsino($state['tipo_ensino']);
        }
        if (array_key_exists('codigo_curso_profissional', $state)) {
            $self->setCodigoCurso($state['codigo_curso_profissional']);
        }
        if (array_key_exists('censo_etapa', $state)) {
            $self->setEtapaCenso($state['censo_etapa']);
            $self->setTipoEstruturaCurricular();
        }
        if (array_key_exists('tipo_turma', $state)) {
            $self->setTipoTurma($state['tipo_turma']);
        }
        if (array_key_exists('programa_mais_educacao', $state)) {
            $self->setProgramaMaiseducacao($state['programa_mais_educacao'] == 't');
        }
        if (array_key_exists('mediacao_didatico_pedagogica', $state)) {
            $self->setTipoMediacaoDidaticoPedagogica($state['mediacao_didatico_pedagogica']);
        }
        if (array_key_exists('regular', $state)) {
            $self->setEscolarizacao($state['regular'] == 't');
        }
        if (array_key_exists('atividade_complementar', $state)) {
            $self->setAtividadeComplementar($state['atividade_complementar'] == 't');
        }
        if (array_key_exists('atendimento_aee', $state)) {
            $self->setAtendimentoAEE($state['atendimento_aee'] == 't');
        }

        if (array_key_exists('hora_inicio', $state)) {
            $self->setHoraInicio($state['hora_inicio']);
        }

        if (array_key_exists('hora_fim', $state)) {
            $self->setHoraFim($state['hora_fim']);
        }

        if (array_key_exists('local_funcionamento', $state)) {
            $self->setLocalFuncionamento($state['local_funcionamento']);
        }

        return $self;
    }
}
