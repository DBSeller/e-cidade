<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class Disarq extends Model
{
    protected $table = "disarq";

    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param int $idUsuario
     * @return Disarq
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodbco()
    {
        return $this->codbco;
    }

    /**
     * @param int $codbco
     * @return Disarq
     */
    public function setCodbco($codbco)
    {
        $this->codbco = $codbco;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodage()
    {
        return $this->codage;
    }

    /**
     * @param int $codage
     * @return Disarq
     */
    public function setCodage($codage)
    {
        $this->codage = $codage;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodret()
    {
        return $this->codret;
    }

    /**
     * @param int $codret
     * @return Disarq
     */
    public function setCodret($codret)
    {
        $this->codret = $codret;
        return $this;
    }

    /**
     * @return string
     */
    public function getArqret()
    {
        return $this->arqret;
    }

    /**
     * @param string $arqret
     * @return Disarq
     */
    public function setArqret($arqret)
    {
        $this->arqret = $arqret;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextoret()
    {
        return $this->textoret;
    }

    /**
     * @param string $textoret
     * @return Disarq
     */
    public function setTextoret($textoret)
    {
        $this->textoret = $textoret;
        return $this;
    }

    /**
     * @return string
     */
    public function getDtretorno()
    {
        return $this->dtretorno;
    }

    /**
     * @param string $dtretorno
     * @return Disarq
     */
    public function setDtretorno($dtretorno)
    {
        $this->dtretorno = $dtretorno;
        return $this;
    }

    /**
     * @return string
     */
    public function getDtarquivo()
    {
        return $this->dtarquivo;
    }

    /**
     * @param string $dtarquivo
     * @return Disarq
     */
    public function setDtarquivo($dtarquivo)
    {
        $this->dtarquivo = $dtarquivo;
        return $this;
    }

    /**
     * @return int
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param int $conta
     * @return Disarq
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAutent()
    {
        return $this->autent;
    }

    /**
     * @param bool $autent
     * @return Disarq
     */
    public function setAutent($autent)
    {
        $this->autent = $autent;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstit()
    {
        return $this->instit;
    }

    /**
     * @param int $instit
     * @return Disarq
     */
    public function setInstit($instit)
    {
        $this->instit = $instit;
        return $this;
    }

    /**
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * @param string $md5
     * @return Disarq
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
        return $this;
    }
}
