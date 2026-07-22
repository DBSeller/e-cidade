<?php

namespace ECidade\RecursosHumanos\RH\Efetividade\Model;

use DateTime;
use Exception;
use Instituicao;
use InstituicaoRepository;

class ConfiguracaoDataEfetividade
{
    /**
     * @var int
     */
    private $ano;
    /**
     * @var string
     */
    private $mes;
    /**
     * @var DateTime
     */
    private $inicio;
    /**
     * @var DateTime
     */
    private $fechamento;
    /**
     * @var DateTime
     */
    private $entrega;
    /**
     * @var bool
     */
    private $processada;
    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @param array $state
     * @return ConfiguracaoDataEfetividade
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self;

        if (array_key_exists('rh186_exercicio', $state)) {
            $self->setAno($state['rh186_exercicio']);
        }

        if (array_key_exists('rh186_competencia', $state)) {
            $self->setMes($state['rh186_competencia']);
        }

        if (array_key_exists('rh186_datainicioefetividade', $state)) {
            $self->setInicio(new DateTime($state['rh186_datainicioefetividade']));
        }

        if (array_key_exists('rh186_datafechamentoefetividade', $state)) {
            $self->setFechamento(new DateTime($state['rh186_datafechamentoefetividade']));
        }

        if (array_key_exists('rh186_dataentregaefetividade', $state)) {
            $self->setEntrega(new DateTime($state['rh186_dataentregaefetividade']));
        }

        if (array_key_exists('rh186_processado', $state)) {
            $self->setProcessada($state['rh186_processado'] === 't');
        }

        if (array_key_exists('rh186_instituicao', $state)) {
            $self->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($state['rh186_instituicao']));
        }

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'ano' => $this->getAno(),
            'mes' => $this->getMes(),
            'inicio' => $this->getInicio()->format('d/m/Y'),
            'fechamento' => $this->getFechamento()->format('d/m/Y'),
            'entrega' => $this->getEntrega()->format('d/m/Y'),
            'processada' => $this->isProcessada(),
            'instituicao' => $this->getInstituicao()->toArray(),
        );
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return (int)$this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = (int)$ano;
    }

    /**
     * @return string
     */
    public function getMes()
    {
        return (string)$this->mes;
    }

    /**
     * @param string $mes
     */
    public function setMes($mes)
    {
        $this->mes = (string)$mes;
    }

    /**
     * @return DateTime
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * @param DateTime $inicio
     */
    public function setInicio(DateTime $inicio)
    {
        $this->inicio = $inicio;
    }

    /**
     * @return DateTime
     */
    public function getFechamento()
    {
        return $this->fechamento;
    }

    /**
     * @param DateTime $fechamento
     */
    public function setFechamento(DateTime $fechamento)
    {
        $this->fechamento = $fechamento;
    }

    /**
     * @return DateTime
     */
    public function getEntrega()
    {
        return $this->entrega;
    }

    /**
     * @param DateTime $entrega
     */
    public function setEntrega(DateTime $entrega)
    {
        $this->entrega = $entrega;
    }

    /**
     * @return bool
     */
    public function isProcessada()
    {
        return (bool)$this->processada;
    }

    /**
     * @param bool $processada
     */
    public function setProcessada($processada)
    {
        $this->processada = (bool)$processada;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }
}
