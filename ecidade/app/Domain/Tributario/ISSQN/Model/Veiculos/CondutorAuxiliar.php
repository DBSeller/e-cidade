<?php

namespace App\Domain\Tributario\ISSQN\Model\Veiculos;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CondutorAuxiliar
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int q173_sequencial
 * @property int q173_issveiculo
 * @property int q173_cgm
 * @property Date q173_datainicio
 * @property Date q173_datafim
 */
class CondutorAuxiliar extends Model
{
    protected $table = 'issqn.issveiculocondutorauxiliar';
    protected $primaryKey = 'q173_sequencial';
    public $timestamps = false;

    /**
     * @param int $q173_sequencial
     * @return this
     */
    public function setCodigo($q173_sequencial)
    {
        $this->q173_sequencial = $q173_sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->q173_sequencial;
    }

    /**
     * @param int $q173_issveiculo
     * @return this
     */
    public function setVeiculo($q173_issveiculo)
    {
        $this->q173_issveiculo = $q173_issveiculo;
        return $this;
    }

    /**
     * @return int
     */
    public function getVeiculo()
    {
        return $this->q173_issveiculo;
    }

    /**
     * @param int $q173_cgm
     * @return this
     */
    public function setCgm($q173_cgm)
    {
        $this->q173_cgm = $q173_cgm;
        return $this;
    }

    /**
     * @return int
     */
    public function getCgm()
    {
        return $this->q173_cgm;
    }

    /**
     * @param Date $q173_datainicio
     * @return this
     */
    public function setDataInicio($q173_datainicio)
    {
        $this->q173_datainicio = $q173_datainicio;
        return $this;
    }

    /**
     * @return Date
     */
    public function getDataInicio()
    {
        return $this->q173_datainicio;
    }

    /**
     * @param Date $q173_datafim
     * @return this
     */
    public function setDataFim($q173_datafim)
    {
        $this->q173_datafim = $q173_datafim;
        return $this;
    }

    /**
     * @return Date
     */
    public function getDataFim()
    {
        return $this->q173_datafim;
    }
}
