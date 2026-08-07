<?php

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Cfiptu extends Model
{
    private $anousu;

    private $vlrref;

    private $dtoper;

    private $rterri;

    private $rpredi;

    private $vencim;

    private $logradauto;

    private $segundavia;

    private $infla;

    private $utilizasetfisc;

    private $utilizaareaprivativa;

    private $testadanumero;

    private $excconscalc;

    private $textoprom;

    private $calcvenc;

    private $utilizaloc;

    private $permvenc;

    private $utidadosdiver;

    private $dadoscertisen;

    private $formatsetor;

    private $formatquadra;

    private $formatlote;

    private $utilpontos;

    private $ordendent;

    private $iptuhistisen;

    private $db_sysfuncoes;

    private $tipoisen;

    private $perccorrepadrao;

    private $templatecertidaoexitencia;

    private $templatecertidaoisencao;

    private $receitacreditorecalculo;

    private $tipodebitorecalculo;

    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
    }

    public function setVlrref($vlrref)
    {
        $this->vlrref = $vlrref;
    }

    public function setDtoper($dtoper)
    {
        $this->dtoper = $dtoper;
    }

    public function setRterri($rterri)
    {
        $this->rterri = $rterri;
    }

    public function setRpredi($rpredi)
    {
        $this->rpredi = $rpredi;
    }

    public function setVencim($vencim)
    {
        $this->vencim = $vencim;
    }

    public function setLogradauto($logradauto)
    {
        $this->logradauto = $logradauto;
    }

    public function setSegundavia($segundavia)
    {
        $this->segundavia = $segundavia;
    }

    public function setInfla($infla)
    {
        $this->infla = $infla;
    }

    public function setUtilizasetfisc($utilizasetfisc)
    {
        $this->utilizasetfisc = $utilizasetfisc;
    }

    public function setUtilizaareaprivativa($utilizaareaprivativa)
    {
        $this->utilizaareaprivativa = $utilizaareaprivativa;
    }

    public function setTestadanumero($testadanumero)
    {
        $this->testadanumero = $testadanumero;
    }

    public function setExcconscalc($excconscalc)
    {
        $this->excconscalc = $excconscalc;
    }

    public function setTextoprom($textoprom)
    {
        $this->textoprom = $textoprom;
    }

    public function setCalcvenc($calcvenc)
    {
        $this->calcvenc = $calcvenc;
    }

    public function setUtilizaloc($utilizaloc)
    {
        $this->utilizaloc = $utilizaloc;
    }

    public function setPermvenc($permvenc)
    {
        $this->permvenc = $permvenc;
    }

    public function setUtidadosdiver($utidadosdiver)
    {
        $this->utidadosdiver = $utidadosdiver;
    }

    public function setDadoscertisen($dadoscertisen)
    {
        $this->dadoscertisen = $dadoscertisen;
    }

    public function setFormatsetor($formatsetor)
    {
        $this->formatsetor = $formatsetor;
    }

    public function setFormatquadra($formatquadra)
    {
        $this->formatquadra = $formatquadra;
    }

    public function setFormatlote($formatlote)
    {
        $this->formatlote = $formatlote;
    }

    public function setUtilpontos($utilpontos)
    {
        $this->utilpontos = $utilpontos;
    }

    public function setOrdendent($ordendent)
    {
        $this->ordendent = $ordendent;
    }

    public function setIptuhistisen($iptuhistisen)
    {
        $this->iptuhistisen = $iptuhistisen;
    }

    public function setDbsysfuncoes($db_sysfuncoes)
    {
        $this->db_sysfuncoes = $db_sysfuncoes;
    }

    public function setTipoisen($tipoisen)
    {
        $this->tipoisen = $tipoisen;
    }

    public function setPerccorrepadrao($perccorrepadrao)
    {
        $this->perccorrepadrao = $perccorrepadrao;
    }

    public function setTemplatecertidaoexitencia($templatecertidaoexitencia)
    {
        $this->templatecertidaoexitencia = $templatecertidaoexitencia;
    }

    public function setTemplatecertidaoisencao($templatecertidaoisencao)
    {
        $this->templatecertidaoisencao = $templatecertidaoisencao;
    }

    public function setReceitacreditorecalculo($receitacreditorecalculo)
    {
        $this->receitacreditorecalculo = $receitacreditorecalculo;
    }

    public function setTipodebitorecalculo($tipodebitorecalculo)
    {
        $this->tipodebitorecalculo = $tipodebitorecalculo;
    }

    public function getAnousu()
    {
        return $this->anousu;
    }

    public function getVlrref()
    {
        return $this->vlrref;
    }

    public function getDtoper()
    {
        return $this->dtoper;
    }

    public function getRterri()
    {
        return $this->rterri;
    }

    public function getRpredi()
    {
        return $this->rpredi;
    }

    public function getVencim()
    {
        return $this->vencim;
    }

    public function getLogradauto()
    {
        return $this->logradauto;
    }

    public function getSegundavia()
    {
        return $this->segundavia;
    }

    public function getInfla()
    {
        return $this->infla;
    }

    public function getUtilizasetfisc()
    {
        return $this->utilizasetfisc;
    }

    public function getUtilizaareaprivativa()
    {
        return $this->utilizaareaprivativa;
    }

    public function getTestadanumero()
    {
        return $this->testadanumero;
    }

    public function getExcconscalc()
    {
        return $this->excconscalc;
    }

    public function getTextoprom()
    {
        return $this->textoprom;
    }

    public function getCalcvenc()
    {
        return $this->calcvenc;
    }

    public function getUtilizaloc()
    {
        return $this->utilizaloc;
    }

    public function getPermvenc()
    {
        return $this->permvenc;
    }

    public function getUtidadosdiver()
    {
        return $this->utidadosdiver;
    }

    public function getDadoscertisen()
    {
        return $this->dadoscertisen;
    }

    public function getFormatsetor()
    {
        return $this->formatsetor;
    }

    public function getFormatquadra()
    {
        return $this->formatquadra;
    }

    public function getFormatlote()
    {
        return $this->formatlote;
    }

    public function getUtilpontos()
    {
        return $this->utilpontos;
    }

    public function getOrdendent()
    {
        return $this->ordendent;
    }

    public function getIptuhistisen()
    {
        return $this->iptuhistisen;
    }

    public function getDbsysfuncoes()
    {
        return $this->db_sysfuncoes;
    }

    public function getTipoisen()
    {
        return $this->tipoisen;
    }

    public function getPerccorrepadrao()
    {
        return $this->perccorrepadrao;
    }

    public function getTemplatecertidaoexitencia()
    {
        return $this->templatecertidaoexitencia;
    }

    public function getTemplatecertidaoisencao()
    {
        return $this->templatecertidaoisencao;
    }

    public function getReceitacreditorecalculo()
    {
        return $this->receitacreditorecalculo;
    }

    public function getTipodebitorecalculo()
    {
        return $this->tipodebitorecalculo;
    }
}
