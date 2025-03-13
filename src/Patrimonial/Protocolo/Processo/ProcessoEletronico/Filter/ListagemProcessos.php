<?php

namespace ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter;

use \DBDate;

/**
 * Filtro para consulta de processos de processo eletrônico
 */
class ListagemProcessos
{
    private $sequencial;
    private $numeroProcesso;
    private $anoProcesso;
    private $etapa;
    private $dataInicio;
    private $dataFim;
    private $codigoInstituicao;
    private $codigoDepartamento;
    private $situacaoOuvidoriaAtendimento;
    private $codigoProcessoProtocolo;
    private $codigoTipoProcesso;
    private $ultimoSequencial;

    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function setNumeroProcesso($numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
    }

    public function getNumeroProcesso()
    {
        return $this->numeroProcesso;
    }

    public function setAnoProcesso($anoProcesso)
    {
        $this->anoProcesso = $anoProcesso;
    }

    public function getAnoProcesso()
    {
        return $this->anoProcesso;
    }

    public function setEtapa($etapa)
    {
        $this->etapa = $etapa;
    }

    public function getEtapa()
    {
        return $this->etapa;
    }

    public function setDataInicio(DBDate $dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    public function setDataFim(DBDate $dataFim)
    {
        $this->dataFim = $dataFim;
    }

    public function getDataFim()
    {
        return $this->dataFim;
    }

    public function setCodigoInstituicao($codigoInstituicao)
    {
        $this->codigoInstituicao = $codigoInstituicao;
    }

    public function getCodigoInstituicao()
    {
        return $this->codigoInstituicao;
    }

    public function setCodigoDepartamento($codigoDepartamento)
    {
        $this->codigoDepartamento = $codigoDepartamento;
    }

    public function getCodigoDepartamento()
    {
        return $this->codigoDepartamento;
    }

    public function setSituacaoOuvidoriaAtendimento($situacaoOuvidoriaAtendimento)
    {
        $this->situacaoOuvidoriaAtendimento = $situacaoOuvidoriaAtendimento;
    }

    public function getSituacaoOuvidoriaAtendimento()
    {
        return $this->situacaoOuvidoriaAtendimento;
    }

    public function setCodigoProcessoProtocolo($codigoProcessoProtocolo)
    {
        $this->codigoProcessoProtocolo = $codigoProcessoProtocolo;
    }

    public function getCodigoProcessoProtocolo()
    {
        return $this->codigoProcessoProtocolo;
    }

    public function setCodigoTipoProcesso($codigoTipoProcesso)
    {
        $this->codigoTipoProcesso = $codigoTipoProcesso;
    }

    public function getCodigoTipoProcesso()
    {
        return $this->codigoTipoProcesso;
    }

    public function getUltimoSequencial()
    {
        return $this->ultimoSequencial;
    }

    public function setUltimoSequencial($ultimoSequencial)
    {
        $this->ultimoSequencial = $ultimoSequencial;
    }
}
