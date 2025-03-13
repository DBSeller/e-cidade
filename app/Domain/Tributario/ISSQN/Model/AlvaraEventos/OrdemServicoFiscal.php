<?php

namespace App\Domain\Tributario\ISSQN\Model\AlvaraEventos;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\OrdemServico;

/**
 * Class OrdemServicoFiscal
 *
 * @package App\Domain\Tributario\ISSQN\Model\AlvaraEventos
 * @property int q169_codigo
 * @property int q169_ordemservico
 * @property int q169_fiscal
 */
class OrdemServicoFiscal extends Model
{
    protected $table = 'issqn.ordemservicofiscal';
    protected $primaryKey = 'q169_codigo';
    public $timestamps = false;

    /**
     * @param int $q169_codigo
     * @return this
     */
    public function setCodigo($q169_codigo)
    {
        $this->q169_codigo = $q169_codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->q169_codigo;
    }

    /**
     * @param int $q169_ordemservico
     * @return this
     */
    public function setOrdemServico($q169_ordemservico)
    {
        $this->q169_ordemservico = $q169_ordemservico;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdemServico()
    {
        return $this->q169_ordemservico;
    }

    /**
     * @param int $q169_fiscal
     * @return this
     */
    public function setFiscal($q169_fiscal)
    {
        $this->q169_fiscal = $q169_fiscal;
        return $this;
    }

    /**
     * @return int
     */
    public function getFiscal()
    {
        return $this->q169_fiscal;
    }

    /**
     * Relação com a ordem de serviço
     */
    public function ordemServico()
    {
        return $this->hasOne(OrdemServico::class);
    }
}
