<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbi extends Model
{
    protected $table = "itbi";

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itbi
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return Itbi
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param string $hora
     * @return Itbi
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipotransacao()
    {
        return $this->tipotransacao;
    }

    /**
     * @param int $tipotransacao
     * @return Itbi
     */
    public function setTipotransacao($tipotransacao)
    {
        $this->tipotransacao = $tipotransacao;
        return $this;
    }

    /**
     * @return float
     */
    public function getAreaterreno()
    {
        return $this->areaterreno;
    }

    /**
     * @param float $areaterreno
     * @return Itbi
     */
    public function setAreaterreno($areaterreno)
    {
        $this->areaterreno = $areaterreno;
        return $this;
    }

    /**
     * @return float
     */
    public function getAreaedificada()
    {
        return $this->areaedificada;
    }

    /**
     * @param float $areaedificada
     * @return Itbi
     */
    public function setAreaedificada($areaedificada)
    {
        $this->areaedificada = $areaedificada;
        return $this;
    }

    /**
     * @return string
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * @param string $obs
     * @return Itbi
     */
    public function setObs($obs)
    {
        $this->obs = $obs;
        return $this;
    }

    /**
     * @return float
     */
    public function getValortransacao()
    {
        return $this->valortransacao;
    }

    /**
     * @param float $valortransacao
     * @return Itbi
     */
    public function setValortransacao($valortransacao)
    {
        $this->valortransacao = $valortransacao;
        return $this;
    }

    /**
     * @return float
     */
    public function getAreatrans()
    {
        return $this->areatrans;
    }

    /**
     * @param float $areatrans
     * @return Itbi
     */
    public function setAreatrans($areatrans)
    {
        $this->areatrans = $areatrans;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return Itbi
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * @param string $finalizado
     * @return Itbi
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrigem()
    {
        return $this->origem;
    }

    /**
     * @param int $origem
     * @return Itbi
     */
    public function setOrigem($origem)
    {
        $this->origem = $origem;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param int $id_usuario
     * @return Itbi
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        return $this;
    }

    /**
     * @return int
     */
    public function getCoddepto()
    {
        return $this->coddepto;
    }

    /**
     * @param int $coddepto
     * @return Itbi
     */
    public function setCoddepto($coddepto)
    {
        $this->coddepto = $coddepto;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorterreno()
    {
        return $this->valorterreno;
    }

    /**
     * @param float $valorterreno
     * @return Itbi
     */
    public function setValorterreno($valorterreno)
    {
        $this->valorterreno = $valorterreno;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorconstr()
    {
        return $this->valorconstr;
    }

    /**
     * @param float $valorconstr
     * @return Itbi
     */
    public function setValorconstr($valorconstr)
    {
        $this->valorconstr = $valorconstr;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvia()
    {
        return $this->envia;
    }

    /**
     * @param string $envia
     * @return Itbi
     */
    public function setEnvia($envia)
    {
        $this->envia = $envia;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercentualareatransmitida()
    {
        return $this->percentualareatransmitida;
    }

    /**
     * @param float $percentualareatransmitida
     * @return Itbi
     */
    public function setPercentualareatransmitida($percentualareatransmitida)
    {
        $this->percentualareatransmitida = $percentualareatransmitida;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotificado()
    {
        return $this->notificado;
    }

    /**
     * @param string $notificado
     * @return Itbi
     */
    public function setNotificado($notificado)
    {
        $this->notificado = $notificado;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcesso()
    {
        return $this->processo;
    }

    /**
     * @param int $processo
     * @return Itbi
     */
    public function setProcesso($processo)
    {
        $this->processo = $processo;
        return $this;
    }

    /**
     * @return string
     */
    public function getTituprocesso()
    {
        return $this->tituprocesso;
    }

    /**
     * @param string $tituprocesso
     * @return Itbi
     */
    public function setTituprocesso($tituprocesso)
    {
        $this->tituprocesso = $tituprocesso;
        return $this;
    }

    /**
     * @return string
     */
    public function getDtprocesso()
    {
        return $this->dtprocesso;
    }

    /**
     * @param string $dtprocesso
     * @return Itbi
     */
    public function setDtprocesso($dtprocesso)
    {
        $this->dtprocesso = $dtprocesso;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCartorioextra()
    {
        return $this->cartorioextra;
    }

    /**
     * @param integer $cartorioextra
     * @return Itbi
     */
    public function setCartorioextra($cartorioextra)
    {
        $this->cartorioextra = $cartorioextra;
        return $this;
    }
}
