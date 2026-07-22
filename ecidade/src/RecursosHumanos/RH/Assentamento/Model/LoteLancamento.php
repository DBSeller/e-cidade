<?php

namespace ECidade\RecursosHumanos\RH\Assentamento\Model;

use Assentamento;
use DateTime;
use ECidade\RecursosHumanos\RH\Assentamento\Repository\LoteLancamentoRepository;
use Exception;
use Instituicao;
use InstituicaoRepository;
use TipoAssentamento;
use TipoAssentamentoRepository;

class LoteLancamento
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var DateTime
     */
    private $data;

    /**
     * @var Assentamento[]
     */
    private $assentamentos;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var TipoAssentamento
     */
    private $tipoAssentamento;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param DateTime $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return Assentamento[]
     */
    public function getAssentamentos()
    {
        return $this->assentamentos;
    }

    /**
     * @param $indice
     */
    public function unsetAssentamentoPorIndice($indice)
    {
        if (!empty($this->assentamentos[$indice])) {
            unset($this->assentamentos[$indice]);
        }
    }

    /**
     * @param Assentamento $assentamentoRemover
     */
    public function unsetAssentamento(Assentamento $assentamentoRemover)
    {
        foreach ($this->assentamentos as $indice => $assentamento) {
            if ($assentamento->getCodigo() == $assentamentoRemover->getCodigo()) {
                $this->unsetAssentamentoPorIndice($indice);
            }
        }
    }

    /**
     * @param Assentamento[] $assentamentos
     */
    public function setAssentamentos($assentamentos)
    {
        $this->assentamentos = $assentamentos;
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
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return TipoAssentamento
     */
    public function getTipoAssentamento()
    {
        return $this->tipoAssentamento;
    }

    /**
     * @param TipoAssentamento $tipoAssentamento
     */
    public function setTipoAssentamento($tipoAssentamento)
    {
        $this->tipoAssentamento = $tipoAssentamento;
    }

    public function addAssentamento(Assentamento $assentamento)
    {
        $this->assentamentos[] = $assentamento;
    }

    /**
     * @param array $state
     * @return LoteLancamento
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('h23_sequencial', $state)) {
            $self->setSequencial($state['h23_sequencial']);
        }

        if (array_key_exists('h23_data', $state)) {
            $self->setData(new DateTime($state['h23_data']));
        }

        if (array_key_exists('h23_instituicao', $state)) {
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo($state['h23_instituicao']);
            $self->setInstituicao($instituicao);
        }

        if (array_key_exists('h23_tipoassentamento', $state)) {
            $tipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($state['h23_tipoassentamento']);
            $self->setTipoAssentamento($tipoAssentamento);
        }

        return $self;
    }

    /**
     * @throws Exception
     */
    public function withAssentamentos()
    {
        if (empty($this->assentamentos)) {
            $assentamentos = LoteLancamentoRepository::getAssentamentosPorLote($this);
            $this->setAssentamentos($assentamentos);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $toArray = array(
            'codigo' => $this->getSequencial(),
            'data' => $this->getData() ? $this->getData()->format('Y-m-d') : null,
            'instituicao' => $this->getInstituicao()->toArray(),
            'tipo' => $this->getTipoAssentamento()->toArray(),
            'assentamentos' => array()
        );

        foreach ($this->getAssentamentos() as $assentamento) {
            $toArray['assentamentos'][] = $assentamento->toArray();
        }

        return $toArray;
    }
}
