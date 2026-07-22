<?php

namespace App\Domain\Tributario\ISSQN\Model\AlvaraEventos;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrdemServico
 *
 * @package App\Domain\Tributario\ISSQN\Model\AlvaraEventos
 * @property int q168_codigo
 * @property int q168_processo
 * @property int q168_cgm
 * @property int q168_inscricao
 * @property text q168_descricao
 * @property text q168_localizacao
 * @property Date q168_dataemissao
 * @property Date q168_datainicio
 * @property Date q168_datafim
 * @property text q168_horainicio
 * @property text q168_horafim
 * @property text q168_processoexterno
 * @property text q168_titularprocessoexterno
 * @property Date q168_dataprocessoexterno
 */
class OrdemServico extends Model
{
    protected $table = 'issqn.ordemservico';
    protected $primaryKey = 'q168_codigo';
    public $timestamps = false;

    protected $fiscais = [];
    protected $appends = array('fiscais');

    /**
     * setter Cidadao
     * @return this
     */
    public function setFiscais($fiscais)
    {
        $this->fiscais = $fiscais;
        return $this;
    }

    /**
     * Get Cidadao
     *
     * @return Cidadao
     */
    public function getFiscaisAttribute()
    {
        return $this->fiscais;
    }

    /**
     * @param int $q168_codigo
     * @return this
     */
    public function setCodigo($q168_codigo)
    {
        $this->q168_codigo = $q168_codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->q168_codigo;
    }

    /**
     * @param int $q168_processo
     * @return this
     */
    public function setProcesso($q168_processo)
    {
        $this->q168_processo = $q168_processo;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcesso()
    {
        return $this->q168_processo;
    }

    /**
     * @param int $q168_cgm
     * @return this
     */
    public function setCgm($q168_cgm)
    {
        $this->q168_cgm = $q168_cgm;
        return $this;
    }

    /**
     * @return int
     */
    public function getCgm()
    {
        return $this->q168_cgm;
    }

    /**
     * @param int $q168_inscricao
     * @return this
     */
    public function setInscricao($q168_inscricao)
    {
        $this->q168_inscricao = $q168_inscricao;
        return $this;
    }

    /**
     * @return int
     */
    public function getInscricao()
    {
        return $this->q168_inscricao;
    }

    /**
     * @param text $q168_descricao
     * @return this
     */
    public function setDescricao($q168_descricao)
    {
        $this->q168_descricao = $q168_descricao;
        return $this;
    }

    /**
     * @return text
     */
    public function getDescricao()
    {
        return $this->q168_descricao;
    }

    /**
     * @param text $q168_localizacao
     * @return this
     */
    public function setLocalizacao($q168_localizacao)
    {
        $this->q168_localizacao = $q168_localizacao;
        return $this;
    }

    /**
     * @return text
     */
    public function getLocalizacao()
    {
        return $this->q168_localizacao;
    }

    /**
     * @param int $q168_dataemissao
     * @return this
     */
    public function setDataEmissao($q168_dataemissao)
    {
        $this->q168_dataemissao = $q168_dataemissao;
        return $this;
    }

    /**
     * @return int
     */
    public function getDataEmissao()
    {
        return $this->q168_dataemissao;
    }

    /**
     * @param int $q168_datainicio
     * @return this
     */
    public function setDataInicio($q168_datainicio)
    {
        $this->q168_datainicio = $q168_datainicio;
        return $this;
    }

    /**
     * @return int
     */
    public function getDataInicio()
    {
        return $this->q168_datainicio;
    }

    /**
     * @param int $q168_datafim
     * @return this
     */
    public function setDataFim($q168_datafim)
    {
        $this->q168_datafim = $q168_datafim;
        return $this;
    }

    /**
     * @return int
     */
    public function getDataFim()
    {
        return $this->q168_datafim;
    }

    /**
     * @param text $q168_horainicio
     * @return this
     */
    public function setHoraInicio($q168_horainicio)
    {
        $this->q168_horainicio = $q168_horainicio;
        return $this;
    }

    /**
     * @return text
     */
    public function getHoraInicio()
    {
        return $this->q168_horainicio;
    }

    /**
     * @param text $q168_horafim
     * @return this
     */
    public function setHoraFim($q168_horafim)
    {
        $this->q168_horafim = $q168_horafim;
        return $this;
    }

    /**
     * @return text
     */
    public function getHoraFim()
    {
        return $this->q168_horafim;
    }

    /**
     * @param text $q168_processoexterno
     * @return this
     */
    public function setProcessoExterno($q168_processoexterno)
    {
        $this->q168_processoexterno = $q168_processoexterno;
        return $this;
    }

    /**
     * @return text
     */
    public function getProcessoExterno()
    {
        return $this->q168_processoexterno;
    }

    /**
     * @param text $q168_titularprocessoexterno
     * @return this
     */
    public function setTitularprocessoExterno($q168_titularprocessoexterno)
    {
        $this->q168_titularprocessoexterno = $q168_titularprocessoexterno;
        return $this;
    }

    /**
     * @return text
     */
    public function getTitularProcessoExterno()
    {
        return $this->q168_titularprocessoexterno;
    }

    /**
     * @param text $q168_titularprocessoexterno
     * @return this
     */
    public function setDataProcessoExterno($q168_dataprocessoexterno)
    {
        $this->q168_dataprocessoexterno = $q168_dataprocessoexterno;
        return $this;
    }

    /**
     * @return text
     */
    public function getDataProcessoExterno()
    {
        return $this->q168_dataprocessoexterno;
    }
}
