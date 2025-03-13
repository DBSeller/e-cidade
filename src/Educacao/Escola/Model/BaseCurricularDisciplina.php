<?php

namespace ECidade\Educacao\Escola\Model;

use Disciplina;
use DisciplinaRepository;
use ECidade\Educacao\Escola\Registry\AreaConhecimentoRegistry;
use ECidade\Educacao\Escola\Registry\BaseCurricularRegistry;
use Etapa;
use EtapaRepository;
use Exception;
use ProcedimentoAvaliacao;
use ProcedimentoAvaliacaoRepository;

class BaseCurricularDisciplina
{
    private $codigo;
    /**
     * @var BaseCurricular
     */
    private $base;
    /**
     * @var Etapa
     */
    private $etapa;
    /**
     * @var Disciplina
     */
    private $disciplina;
    /**
     * @var integer
     */
    private $horasAula;
    /**
     * @var integer
     */
    private $cargaHorariaTotal;
    /**
     * @var string
     */
    private $tipoMatricula;
    /**
     * @var integer
     */
    private $ordenacao;
    /**
     * @var boolean
     */
    private $lancarHistorico;
    /**
     * @var boolean
     */
    private $disiciplinaglobalizada;
    /**
     * @var boolean
     */
    private $possuiCaracterReprobatorio;
    /**
     * @var boolean
     */
    private $baseComum;
    /**
     * @var AreaConhecimento
     */
    private $areaConhecimento;

    /**
     * @var ProcedimentoAvaliacao
     */
    private $procedimento;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     * @return BaseCurricularDisciplina
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return BaseCurricular
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param BaseCurricular $base
     * @return BaseCurricularDisciplina
     */
    public function setBase(BaseCurricular $base)
    {
        $this->base = $base;
        return $this;
    }

    /**
     * @return Etapa
     */
    public function getEtapa()
    {
        return $this->etapa;
    }

    /**
     * @param Etapa $etapa
     * @return BaseCurricularDisciplina
     */
    public function setEtapa(Etapa $etapa)
    {
        $this->etapa = $etapa;
        return $this;
    }

    /**
     * @return Disciplina
     */
    public function getDisciplina()
    {
        return $this->disciplina;
    }

    /**
     * @param Disciplina $disciplina
     * @return BaseCurricularDisciplina
     */
    public function setDisciplina(Disciplina $disciplina)
    {
        $this->disciplina = $disciplina;
        return $this;
    }

    /**
     * @return int
     */
    public function getHorasAula()
    {
        return $this->horasAula;
    }

    /**
     * @param int $horasAula
     * @return BaseCurricularDisciplina
     */
    public function setHorasAula($horasAula)
    {
        $this->horasAula = $horasAula;
        return $this;
    }

    /**
     * @return int
     */
    public function getCargaHorariaTotal()
    {
        return $this->cargaHorariaTotal;
    }

    /**
     * @param int $cargaHorariaTotal
     * @return BaseCurricularDisciplina
     */
    public function setCargaHorariaTotal($cargaHorariaTotal)
    {
        $this->cargaHorariaTotal = $cargaHorariaTotal;
        return $this;
    }

    /**
     * Se obrigatória ou opcional
     * @return string
     */
    public function getTipoMatricula()
    {
        return $this->tipoMatricula;
    }

    /**
     * @param string $tipoMatricula
     * @return BaseCurricularDisciplina
     */
    public function setTipoMatricula($tipoMatricula)
    {
        $this->tipoMatricula = $tipoMatricula;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdenacao()
    {
        return $this->ordenacao;
    }

    /**
     * @param int $ordenacao
     * @return BaseCurricularDisciplina
     */
    public function setOrdenacao($ordenacao)
    {
        $this->ordenacao = $ordenacao;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLancarHistorico()
    {
        return $this->lancarHistorico;
    }

    /**
     * @param bool $lancarHistorico
     * @return BaseCurricularDisciplina
     */
    public function setLancarHistorico($lancarHistorico)
    {
        $this->lancarHistorico = $lancarHistorico;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisiciplinaglobalizada()
    {
        return $this->disiciplinaglobalizada;
    }

    /**
     * @param bool $disiciplinaglobalizada
     * @return BaseCurricularDisciplina
     */
    public function setDisiciplinaglobalizada($disiciplinaglobalizada)
    {
        $this->disiciplinaglobalizada = $disiciplinaglobalizada;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPossuiCaracterReprobatorio()
    {
        return $this->possuiCaracterReprobatorio;
    }

    /**
     * @param bool $possuiCaracterReprobatorio
     * @return BaseCurricularDisciplina
     */
    public function setPossuiCaracterReprobatorio($possuiCaracterReprobatorio)
    {
        $this->possuiCaracterReprobatorio = $possuiCaracterReprobatorio;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBaseComum()
    {
        return $this->baseComum;
    }

    /**
     * @param bool $baseComum
     * @return BaseCurricularDisciplina
     */
    public function setBaseComum($baseComum)
    {
        $this->baseComum = $baseComum;
        return $this;
    }

    /**
     * @return AreaConhecimento
     */
    public function getAreaConhecimento()
    {
        return $this->areaConhecimento;
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     * @return BaseCurricularDisciplina
     */
    public function setAreaConhecimento(AreaConhecimento $areaConhecimento)
    {
        $this->areaConhecimento = $areaConhecimento;
        return $this;
    }

    /**
     * @return ProcedimentoAvaliacao
     */
    public function getProcedimento()
    {
        return $this->procedimento;
    }

    /**
     * @param ProcedimentoAvaliacao $procedimento
     * @return BaseCurricularDisciplina
     */
    public function setProcedimento($procedimento)
    {
        if (!($procedimento instanceof ProcedimentoAvaliacao) && $procedimento != null) {
            throw new Exception("Erro ao setar o Procedimento de Avaliação.");
        }
        $this->procedimento = $procedimento;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed34_i_codigo', $state)) {
            $self->setCodigo($state['ed34_i_codigo']);
        }

        if (array_key_exists('ed34_i_base', $state)) {
            $self->setBase(BaseCurricularRegistry::get($state['ed34_i_base']));
        }

        if (array_key_exists('ed34_i_serie', $state)) {
            $self->setEtapa(EtapaRepository::getEtapaByCodigo($state['ed34_i_serie']));
        }

        if (array_key_exists('ed34_i_disciplina', $state)) {
            $self->setDisciplina(DisciplinaRepository::getDisciplinaByCodigo($state['ed34_i_disciplina']));
        }

        if (array_key_exists('ed34_i_qtdperiodo', $state)) {
            $self->setHorasAula($state['ed34_i_qtdperiodo']);
        }

        if (array_key_exists('ed34_i_chtotal', $state)) {
            $self->setCargaHorariaTotal($state['ed34_i_chtotal']);
        }

        if (array_key_exists('ed34_c_condicao', $state)) {
            $self->setTipoMatricula($state['ed34_c_condicao']);
        }

        if (array_key_exists('ed34_i_ordenacao', $state)) {
            $self->setOrdenacao($state['ed34_i_ordenacao']);
        }

        if (array_key_exists('ed34_lancarhistorico', $state)) {
            $self->setLancarHistorico($state['ed34_lancarhistorico'] === 't');
        }

        if (array_key_exists('ed34_disiciplinaglobalizada', $state)) {
            $self->setDisiciplinaglobalizada($state['ed34_disiciplinaglobalizada'] === 't');
        }

        if (array_key_exists('ed34_caracterreprobatorio', $state)) {
            $self->setPossuiCaracterReprobatorio($state['ed34_caracterreprobatorio'] === 't');
        }

        if (array_key_exists('ed34_basecomum', $state)) {
            $self->setBaseComum($state['ed34_basecomum'] === 't');
        }

        if (array_key_exists('ed34_areaconhecimento', $state) && !empty($state['ed34_areaconhecimento'])) {
            $self->setAreaConhecimento(AreaConhecimentoRegistry::get($state['ed34_areaconhecimento']));
        }

        if (array_key_exists('ed34_procedimento', $state) && !empty($state['ed34_procedimento'])) {
            $self->setProcedimento(
                ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo($state['ed34_procedimento'])
            );
        }

        return $self;
    }
}
