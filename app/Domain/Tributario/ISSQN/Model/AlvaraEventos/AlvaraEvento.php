<?php

namespace App\Domain\Tributario\ISSQN\Model\AlvaraEventos;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AlvaraEvento
 *
 * @package App\Domain\Tributario\ISSQN\Model\AlvaraEventos
 * @property int q170_codigo
 * @property int q170_tipoalvara
 * @property int q170_ordemservico
 * @property string q170_certidaobombeiro
 * @property Date q170_dataemissao
 * @property int q170_estimativapublico
 * @property string q170_observacao
 */
class AlvaraEvento extends Model
{
    protected $table = 'issqn.alvaraevento';
    protected $primaryKey = 'q170_codigo';
    public $timestamps = false;

    /**
     * @param int $q170_codigo
     * @return this
     */
    public function setCodigo($q170_codigo)
    {
        $this->q170_codigo = $q170_codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->q170_codigo;
    }

    /**
     * @param int $q170_tipoalvara
     * @return this
     */
    public function setTipoAlvara($q170_tipoalvara)
    {
        $this->q170_tipoalvara = $q170_tipoalvara;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipoAlvara()
    {
        return $this->q170_tipoalvara;
    }

    /**
     * @param int $q170_ordemservico
     * @return this
     */
    public function setOrdemServico($q170_ordemservico)
    {
        $this->q170_ordemservico = $q170_ordemservico;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdemServico()
    {
        return $this->q170_ordemservico;
    }

    /**
     * @param string $q170_certidaobombeiro
     * @return this
     */
    public function setCertidaoBombeiro($q170_certidaobombeiro)
    {
        $this->q170_certidaobombeiro = $q170_certidaobombeiro;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertidaoBombeiro()
    {
        return $this->q170_certidaobombeiro;
    }

    /**
     * @param Date $q170_dataemissao
     * @return this
     */
    public function setDataEmissao($q170_dataemissao)
    {
        $this->q170_dataemissao = $q170_dataemissao;
        return $this;
    }

    /**
     * @return Date
     */
    public function getDataEmissao()
    {
        return $this->q170_dataemissao;
    }

    /**
     * @param int $q170_estimativapublico
     * @return this
     */
    public function setEstimativaPublico($q170_estimativapublico)
    {
        $this->q170_estimativapublico = $q170_estimativapublico;
        return $this;
    }

    /**
     * @return int
     */
    public function getEstimativaPublico()
    {
        return $this->q170_estimativapublico;
    }

    /**
     * @param string $q170_observacao
     * @return this
     */
    public function setObservacao($q170_observacao)
    {
        $this->q170_observacao = $q170_observacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->q170_observacao;
    }

    /**
     * Relação com a ordem de serviço
     */
    public function ordemServico()
    {
        return $this->hasOne(OrdemServico::class);
    }
}
