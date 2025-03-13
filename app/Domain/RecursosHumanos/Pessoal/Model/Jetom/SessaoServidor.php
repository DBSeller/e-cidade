<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SessaoServidor
 * @package App\Domain\RecursosHumanos\Pessoal\Model\Jetom
 * @property int rh248_sequencial
 * @property int rh248_sessao
 * @property int rh248_servidor
 * @property ComissaoServidor dadosServidor
 */
class SessaoServidor extends Model
{
    protected $table = 'pessoal.jetomsessaoservidor';
    protected $primaryKey = 'rh248_sequencial';
    public $timestamps = false;
    protected $with = ['dadosServidor'];

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->rh248_sequencial;
    }

    /**
     * @param int $rh248_sequencial
     */
    public function setSequencial($rh248_sequencial)
    {
        $this->rh248_sequencial = $rh248_sequencial;
    }

    /**
     * @return int
     */
    public function getSessao()
    {
        return $this->rh248_sessao;
    }

    /**
     * @param int $rh248_sessao
     */
    public function setSessao($rh248_sessao)
    {
        $this->rh248_sessao = $rh248_sessao;
    }

    /**
     * @return int
     */
    public function getServidor()
    {
        return $this->rh248_servidor;
    }

    /**
     * @param int $rh248_servidor
     */
    public function setServidor($rh248_servidor)
    {
        $this->rh248_servidor = $rh248_servidor;
    }

    public function dadosServidor()
    {
        return $this->hasOne(
            'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoServidor',
            'rh245_sequencial',
            'rh248_servidor'
        );
    }
}
