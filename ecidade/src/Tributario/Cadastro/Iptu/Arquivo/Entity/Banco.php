<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;
use \DateTime;

final class Banco extends Entity
{
    const TOTAL_BOM_PAGADOR  = 'TOTALBOMPAGADOR';
    const AGENCIA            = 'AGENCIA';
    const DIGITO_AGENCIA     = 'DIGITOAGENCIA';
    const OPERACAO           = 'OPERACAO';
    const CEDENTE            = 'CEDENTE';
    const DIGITO_CEDENTE     = 'DIGITOCEDENTE';
    const CARTEIRA           = 'CARTEIRA';
    const CONVENIO           = 'CONVENIO';
    const DATA_PROCESSAMENTO = 'DATAPROCESSAMENTO';
    const DESCRICAO_CONVENIO = 'DESCRICAOCONVENIO';

    /**
     * @var float|null VALOR TOTAL DO BOM PAGADOR 
     */
    private $totalBomPagador = '';

    /**
     * @var string|null AGENCIA DO CONVENIO 
     */
    private $agencia = '';

    /**
     * @var string|null DIGITO DA AGENCIA 
     */
    private $digitoAgencia = '';

    /**
     * @var string|null OPERACAO DO CONVENIO 
     */
    private $operacao = '';

    /**
     * @var string|null CEDENTE DO CONVENIO 
     */
    private $cedente = '';

    /**
     * @var string|null DIGITO DO CEDENTE 
     */
    private $digitoCedente = '';

    /**
     * @var string|null CARTEIRA DO CONVENIO 
     */
    private $carteira = '';

    /**
     * @var string|null CONVENIO 
     */
    private $convenio = '';

    /**
     * @var DateTime|null DATA DO PROCESSAMENTO 
     */
    private $dataProcessamento = '';

    /**
     * @var string|null DESCRICAO DO CONVENIO 
     */
    private $descricaoConvenio = '';

    /** 
     * @return float Retorna o VALOR TOTAL DO BOM PAGADOR
     */
    public function getTotalBomPagador() {
        return $this->totalBomPagador;;
    }
    
    /** 
     * @return string Retorna a AGENCIA DO CONVENIO
     */
    public function getAgencia() {
        return $this->agencia;;
    }
    
    /** 
     * @return string Retorna o DIGITO DA AGENCIA
     */
    public function getDigitoAgencia() {
        return $this->digitoAgencia;;
    }
    
    /** 
     * @return string Retorna a OPERACAO DO CONVENIO
     */
    public function getOperacao() {
        return $this->operacao;;
    }
    
    /** 
     * @return string Retorna o CEDENTE DO CONVENIO
     */
    public function getCedente() {
        return $this->cedente;;
    }
    
    /** 
     * @return string Retorna o DIGITO DO CEDENTE
     */
    public function getDigitoCedente() {
        return $this->digitoCedente;;
    }
    
    /** 
     * @return string Retorna a CARTEIRA DO CONVENIO
     */
    public function getCarteira() {
        return $this->carteira;;
    }
    
    /** 
     * @return string Retorna o CONVENIO
     */
    public function getConvenio() {
        return $this->convenio;;
    }
    
    /** 
     * @return DateTime Retorna a DATA DO PROCESSAMENTO
     */
    public function getDataProcessamento() {
        return $this->dataProcessamento;;
    }
    
    /** 
     * @return string Retorna a DESCRICAO DO CONVENIO
     */
    public function getDescricaoConvenio() {
        return $this->descricaoConvenio    ;;
    }

    /**
     * Define o VALOR TOTAL DO BOM PAGADOR
     *
     * @param float $totalBomPagador
     */
    public function setTotalBomPagador($totalBomPagador) {
        $this->totalBomPagador = $totalBomPagador;
        return $this;;
    }

    /**
     * Define a AGENCIA DO CONVENIO
     *
     * @param string $agencia
     */
    public function setAgencia($agencia) {
        $this->agencia = $agencia;
        return $this;;
    }

    /**
     * Define o DIGITO DA AGENCIA
     *
     * @param string $digitoAgencia
     */
    public function setDigitoAgencia($digitoAgencia) {
        $this->digitoAgencia = $digitoAgencia;
        return $this;
    }

    /**
     * Define a OPERACAO DO CONVENIO
     *
     * @param string $operacao
     */
    public function setOperacao($operacao) {
        $this->operacao = $operacao;
        return $this;
    }

    /**
     * Define o CEDENTE DO CONVENIO
     *
     * @param string $cedente
     */
    public function setCedente($cedente) {
        $this->cedente = $cedente;
        return $this;
    }

    /**
     * Define o DIGITO DO CEDENTE
     *
     * @param string $digitoCedente
     */
    public function setDigitoCedente($digitoCedente) {
        $this->digitoCedente = $digitoCedente;
        return $this;
    }

    /**
     * Define a CARTEIRA DO CONVENIO
     *
     * @param string $carteira
     */
    public function setCarteira($carteira) {
        $this->carteira = $carteira;
        return $this;
    }

    /**
     * Define o CONVENIO
     *
     * @param string $convenio
     */
    public function setConvenio($convenio) {
        $this->convenio = $convenio;
        return $this;
    }

    /**
     * Define a DATA DO PROCESSAMENTO
     *
     * @param DateTime $dataProcessamento
     */
    public function setDataProcessamento(DateTime $dataProcessamento) {
        $this->dataProcessamento = $dataProcessamento;
        return $this;
    }

    /**
     * Define a DESCRICAO DO CONVENIO
     *
     * @param string $descricaoConvenio
     */
    public function setDescricaoConvenio($descricaoConvenio) {
        $this->descricaoConvenio = $descricaoConvenio;
        return $this;
    }
}
