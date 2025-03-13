<?php

namespace App\Domain\Tributario\ISSQN\Model\Veiculos;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Veiculo
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int q172_sequencial
 * @property Date q172_datacadastro
 * @property int q172_issbase
 * @property int q172_tipo
 * @property int q172_marca
 * @property int q172_modelo
 * @property int q172_cor
 * @property int q172_procedencia
 * @property int q172_categoria
 * @property string q172_chassi
 * @property string q172_renavam
 * @property string q172_placa
 * @property string q172_potencia
 * @property int q172_capacidade
 * @property int q172_anofabricacao
 * @property int q172_anomodelo
 * @property string q172_aam
 */
class Veiculo extends Model
{
    protected $table = 'issqn.issveiculo';
    protected $primaryKey = 'q172_sequencial';
    public $timestamps = false;

    protected $condutores = [];
    protected $appends = array('condutores');

    /**
     * @param App\Domain\Tributario\ISSQN\Model\Veiculos\CondutorAuxiliar $condutores
     * @return this
     */
    public function setCondutores($condutores)
    {
        $this->condutores = $condutores;
        return $this;
    }

    public function getCondutoresAttribute()
    {
        return $this->condutores;
    }

    /**
     * @param int $q172_sequencial
     * @return this
     */
    public function setCodigo($q172_sequencial)
    {
        $this->q172_sequencial = $q172_sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->q172_sequencial;
    }

    /**
     * @param Date $q172_datacadastro
     * @return this
     */
    public function setDataCadastro($q172_datacadastro)
    {
        $this->q172_datacadastro = $q172_datacadastro;
        return $this;
    }

    /**
     * @return Date
     */
    public function getDataCadastro()
    {
        return $this->q172_datacadastro;
    }

    /**
     * @param int $q172_issbase
     * @return this
     */
    public function setInscricao($q172_issbase)
    {
        $this->q172_issbase = $q172_issbase;
        return $this;
    }

    /**
     * @return int
     */
    public function getInscricao()
    {
        return $this->q172_issbase;
    }

    /**
     * @param Date $q172_tipo
     * @return this
     */
    public function setTipo($q172_tipo)
    {
        $this->q172_tipo = $q172_tipo;
        return $this;
    }

    /**
     * @return Date
     */
    public function getTipo()
    {
        return $this->q172_tipo;
    }

    /**
     * @param Date $q172_marca
     * @return this
     */
    public function setMarca($q172_marca)
    {
        $this->q172_marca = $q172_marca;
        return $this;
    }

    /**
     * @return Date
     */
    public function getMarca()
    {
        return $this->q172_marca;
    }

    /**
     * @param int $q172_modelo
     * @return this
     */
    public function setModelo($q172_modelo)
    {
        $this->q172_modelo = $q172_modelo;
        return $this;
    }

    /**
     * @return int
     */
    public function getModelo()
    {
        return $this->q172_modelo;
    }

    /**
     * @param int $q172_cor
     * @return this
     */
    public function setCor($q172_cor)
    {
        $this->q172_cor = $q172_cor;
        return $this;
    }

    /**
     * @return int
     */
    public function getCor()
    {
        return $this->q172_cor;
    }

    /**
     * @param int $q172_procedencia
     * @return this
     */
    public function setProcedencia($q172_procedencia)
    {
        $this->q172_procedencia = $q172_procedencia;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcedencia()
    {
        return $this->q172_procedencia;
    }

    /**
     * @param int $q172_categoria
     * @return this
     */
    public function setCategoria($q172_categoria)
    {
        $this->q172_categoria = $q172_categoria;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoria()
    {
        return $this->q172_categoria;
    }

    /**
     * @param texto $q172_chassi
     * @return this
     */
    public function setChassi($q172_chassi)
    {
        $this->q172_chassi = $q172_chassi;
        return $this;
    }

    /**
     * @return texto
     */
    public function getChassi()
    {
        return $this->q172_chassi;
    }

    /**
     * @param string $q172_renavam
     * @return this
     */
    public function setRenavan($q172_renavam)
    {
        $this->q172_renavam = $q172_renavam;
        return $this;
    }

    /**
     * @return string
     */
    public function getRenavan()
    {
        return $this->q172_renavam;
    }

    /**
     * @param string $q172_placa
     * @return this
     */
    public function setPlaca($q172_placa)
    {
        $this->q172_placa = $q172_placa;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlaca()
    {
        return $this->q172_placa;
    }

    /**
     * @param string $q172_potencia
     * @return this
     */
    public function setPotencia($q172_potencia)
    {
        $this->q172_potencia = $q172_potencia;
        return $this;
    }

    /**
     * @return string
     */
    public function getPotencia()
    {
        return $this->q172_potencia;
    }

    /**
     * @param int $q172_capacidade
     * @return this
     */
    public function setCapacidade($q172_capacidade)
    {
        $this->q172_capacidade = $q172_capacidade;
        return $this;
    }

    /**
     * @return int
     */
    public function getCapacidade()
    {
        return $this->q172_capacidade;
    }

    /**
     * @param int $q172_anofabricacao
     * @return this
     */
    public function setAnoFabricacao($q172_anofabricacao)
    {
        $this->q172_anofabricacao = $q172_anofabricacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getAnoFabricacao()
    {
        return $this->q172_anofabricacao;
    }

    /**
     * @param int $q172_anomodelo
     * @return this
     */
    public function setAnoModelo($q172_anomodelo)
    {
        $this->q172_anomodelo = $q172_anomodelo;
        return $this;
    }

    /**
     * @return int
     */
    public function getAnoModelo()
    {
        return $this->q172_anomodelo;
    }

    /**
     * @param string $q172_aam
     * @return this
     */
    public function setAam($q172_aam)
    {
        $this->q172_aam = $q172_aam;
        return $this;
    }

    /**
     * @return string
     */
    public function getAam()
    {
        return $this->q172_aam;
    }
}
