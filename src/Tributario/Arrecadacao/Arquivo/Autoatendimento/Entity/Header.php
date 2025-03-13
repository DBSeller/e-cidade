<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Entity;

use ECidade\Tributario\Arrecadacao\Convenio;
use \ECidade\Tributario\Library\Entity;

class Header extends Entity
{
    const TIPO_REGISTRO_HEADER  = 'TIPOREGISTRO';
    const NUMERO_CONVENIO       = 'NUMEROCONVENIO';
    const DATA_GERACAO          = 'DATAGERACAO';
    const IDENTIFICACAO_ARQUIVO = 'IDENTIFICACAOARQUIVO';
    const TIPO_ARQUIVO          = 'TIPOARQUIVO';
    const PREFIXO_AGENCIA       = 'PREFIXOAGENCIA';
    const ANO_REMESSA           = 'ANOREMESSA';

    private $tipoRegistro;
    private $convenio;
    private $dataGeracao;
    private $identificacaoArquivo;
    private $tipoArquivo;
    private $prefixoAgencia;
    private $anoRemessa;

    /**
     * @return Convenio
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @param Convenio $convenio
     */
    public function setConvenio(Convenio $convenio)
    {
        $this->convenio = $convenio;
    }

    /**
     * @return \DateTime
     */
    public function getDataGeracao()
    {
        return $this->dataGeracao;
    }

    /**
     * @param \DateTime $dataGeracao
     */
    public function setDataGeracao(\DateTime $dataGeracao)
    {
        $this->dataGeracao = $dataGeracao;
    }

    /**
     * @return mixed
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * @param mixed $tipoRegistro
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;
    }

    /**
     * @return mixed
     */
    public function getIdentificacaoArquivo()
    {
        return $this->identificacaoArquivo;
    }

    /**
     * @param mixed $identificacaoArquivo
     */
    public function setIdentificacaoArquivo($identificacaoArquivo)
    {
        $this->identificacaoArquivo = $identificacaoArquivo;
    }

    /**
     * @return mixed
     */
    public function getTipoArquivo()
    {
        return $this->tipoArquivo;
    }

    /**
     * @param mixed $tipoArquivo
     */
    public function setTipoArquivo($tipoArquivo)
    {
        $this->tipoArquivo = $tipoArquivo;
    }

    /**
     * @return mixed
     */
    public function getPrefixoAgencia()
    {
        return $this->prefixoAgencia;
    }

    /**
     * @param mixed $prefixoAgencia
     */
    public function setPrefixoAgencia($prefixoAgencia)
    {
        $this->prefixoAgencia = $prefixoAgencia;
    }

    /**
     * @return mixed
     */
    public function getAnoRemessa()
    {
        return $this->anoRemessa;
    }

    /**
     * @param mixed $anoRemessa
     */
    public function setAnoRemessa($anoRemessa)
    {
        $this->anoRemessa = $anoRemessa;
    }
}
