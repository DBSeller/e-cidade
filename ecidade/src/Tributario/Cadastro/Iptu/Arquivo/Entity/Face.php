<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class Face extends Entity
{
    const OUTRAS_INFORMACOES                = 'OUTRASINFORMACOES';
    const CODIGO_CGM                        = 'CODIGOCGM';
    const FRACAO_LOTE                       = 'FRACAOLOTE';
    const CEP_IMOVEL                        = 'CEPIMOVEL';
    const MUNICIPIO_IMOVEL                  = 'MUNICIPIOIMOVEL';
    const UF_IMOVEL                         = 'UFIMOVEL';
    const MENSAGEM_DEBITOS_ANOS_ANTERIORES  = 'MENSAGEMDEBITOSANOSANTERIORES';
    const NOME_BAIRRO                       = 'NOMEBAIRRO';
    const CODIGO_ISENCAO                    = 'CODIGOISENCAO';
    const CODIGO_TIPO_ISENCAO               = 'CODIGOTIPOISENCAO';
    
    /**
     * @var string|null OUTRAS INFORMACOES DA FACE
     */
    private $outrasInformacoes = '';
    
    /**
     * @var integer|null CODIGO DO CGM DO NOME A SER IMPRESSO NO CARNE
     */
    private $codigoCGM = '';
    
    /**
     * @var string|null FRACAO DO LOTE UTILIZADA NO CALCULO
     */
    private $fracaoLote = '';
    
    /**
     * @var string|null CEP DO IMOVEL
     */
    private $cepImovel = '';
    
    /**
     * @var string|null MUNICIPIO DO IMOVEL
     */
    private $municipioImovel = '';
    
    /**
     * @var string|null UF DO IMOVEL
     */
    private $UFImovel = '';
    
    /**
     * @var string|null MENSAGEM CASO A MATRICULA TENHA DEBITOS EM ANOS ANTERIORES
     */
    private $mensagemDebitosAnosAnteriores = '';
    
    /**
     * @var string|null BAIRRO DO CGM DO PROPRIETARIO
     */
    private $nomeBairro = '';
    
    /**
     * @var integer|null CODIGO DA ISENCAO
     */
    private $codigoIsencao = '';
    
    /**
     * @var integer|null CODIGO DO TIPO DE ISENCAO
     */
    private $codigoTipoIsencao = '';

    /**
     * @return string|null
     *
     * Retorna as OUTRAS INFORMACOES DA FACE
     */
    public function getOutrasInformacoes() {
        return $this->outrasInformacoes;
    }

    /**
     * @return integer|null
     *
     * Retorna o CODIGO DO CGM DO NOME A SER IMPRESSO NO CARNE
     */
    public function getCodigoCGM() {
        return $this->codigoCGM;
    }

    /**
     * @return string|null
     *
     * Retorna a FRACAO DO LOTE UTILIZADA NO CALCULO
     */
    public function getFracaoLote() {
        return $this->fracaoLote;
    }

    /**
     * @return string|null
     *
     * Retorna o CEP DO IMOVEL
     */
    public function getCEPImovel() {
        return $this->cepImovel;
    }

    /**
     * @return string|null
     *
     * Retorna o MUNICIPIO DO IMOVEL
     */
    public function getMunicipioImovel() {
        return $this->municipioImovel;
    }

    /**
     * @return string|null
     *
     * Retorna a UF DO IMOVEL
     */
    public function getUFImovel() {
        return $this->UFImovel;
    }

    /**
     * @return string|null
     *
     * Retorna a MENSAGEM CASO A MATRICULA TENHA DEBITOS EM ANOS ANTERIORES
     */
    public function getMensagemDebitosAnosAnteriores() {
        return $this->mensagemDebitosAnosAnteriores;
    }

    /**
     * @return string|null
     *
     * Retorna o BAIRRO DO CGM DO PROPRIETARIO
     */
    public function getNomeBairro() {
        return $this->nomeBairro;
    }

    /**
     * @return integer|null
     *
     * Retorna o CODIGO DA ISENCAO
     */
    public function getCodigoIsencao() {
        return $this->codigoIsencao;
    }

    /**
     * @return integer|null
     *
     * Retorna o CODIGO DO TIPO DE ISENCAO
     */
    public function getCodigoTipoIsencao() {
        return $this->codigoTipoIsencao;
    }

    
    /**
     * @param string|null $outrasInformacoes
     *
     * Define as OUTRAS INFORMACOES DA FACE
     */
    public function setOutrasInformacoes($outrasInformacoes) {
        $this->outrasInformacoes = $outrasInformacoes;
        return $this;
    }
    
    /**
     * @param integer|null $codigoCGM
     *
     * Define o CODIGO DO CGM DO NOME A SER IMPRESSO NO CARNE
     */
    public function setCodigoCGM($codigoCGM) {
        $this->codigoCGM = $codigoCGM;
        return $this;
    }
    
    /**
     * @param string|null $fracaoLote
     *
     * Define a FRACAO DO LOTE UTILIZADA NO CALCULO
     */
    public function setFracaoLote($fracaoLote) {
        $this->fracaoLote = $fracaoLote;
        return $this;
    }
    
    /**
     * @param string|null $cepImovel
     *
     * Define o CEP DO IMOVEL
     */
    public function setCEPImovel($cepImovel) {
        $this->cepImovel = $cepImovel;
        return $this;
    }
    
    /**
     * @param string|null $municipioImovel
     *
     * Define o MUNICIPIO DO IMOVEL
     */
    public function setMunicipioImovel($municipioImovel) {
        $this->municipioImovel = $municipioImovel;
        return $this;
    }
    
    /**
     * @param string|null $UFImovel
     *
     * Define a UF DO IMOVEL
     */
    public function setUFImovel($UFImovel) {
        $this->UFImovel = $UFImovel;
        return $this;
    }
    
    /**
     * @param string|null $mensagemDebitosAnosAnteriores
     *
     * Define a MENSAGEM CASO A MATRICULA TENHA DEBITOS EM ANOS ANTERIORES
     */
    public function setMensagemDebitosAnosAnteriores($mensagemDebitosAnosAnteriores) {
        $this->mensagemDebitosAnosAnteriores = $mensagemDebitosAnosAnteriores;
        return $this;
    }
    
    /**
     * @param string|null $nomeBairro
     *
     * Define o BAIRRO DO CGM DO PROPRIETARIO
     */
    public function setNomeBairro($nomeBairro) {
        $this->nomeBairro = $nomeBairro;
        return $this;
    }
    
    /**
     * @param integer|null $codigoIsencao
     *
     * Define o CODIGO DA ISENCAO
     */
    public function setCodigoIsencao($codigoIsencao) {
        $this->codigoIsencao = $codigoIsencao;
        return $this;
    }
    
    /**
     * @param integer|null $codigoTipoIsencao
     *
     * Define o CODIGO DO TIPO DE ISENCAO
     */
    public function setCodigoTipoIsencao($codigoTipoIsencao) {
        $this->codigoTipoIsencao = $codigoTipoIsencao;
        return $this;
    }
}
