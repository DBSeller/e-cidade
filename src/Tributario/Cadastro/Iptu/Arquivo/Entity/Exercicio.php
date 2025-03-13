<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;
use \DateTime;

final class Exercicio extends Entity
{
    const BRANCOS_1                = 'BRANCOS1';
    const BRANCOS_2                = 'BRANCOS2';
    const DESCRICAO_ISENCAO        = 'DESCRICAOISENCAO';
    const LANCAMENTO_ISENCAO       = 'LANCAMENTOISENCAO';
    const TOTAL_LANCADO            = 'TOTALLANCADO';
    const QUANTIDADE_LANCADO       = 'QUANTIDADELANCADO';
    const TOTAL_LANCADO_TAXAS      = 'TOTALLANCADOTAXAS';
    const QUANTIDADE_LANCADO_TAXAS = 'QUANTIDADELANCADOTAXAS';
    const VALOR_CORRIGIDO_IPTU     = 'VALORCORRIGIDOIPTU';
    const VALOR_JUROS_IPTU         = 'VALORJUROSIPTU';
    const VALOR_MULTA_IPTU         = 'VALORMULTAIPTU';
    const VALOR_DESCONTO_IPTU      = 'VALORDESCONTOIPTU';
    const VALOR_TOTAL_IPTU         = 'VALORTOTALIPTU';
    const CODIGO_FACE              = 'CODIGOFACE';
    const VALOR_M2_TERRENO_FACE    = 'VALORM2TERRENOFACE';
    const VALOR_M2_CONSTRUCAO_FACE = 'VALORM2CONSTRUCAOFACE';
    const VALOR_VENAL_TERRENO      = 'VALORVENALTERRENO';
    const VALOR_VENAL_EDIFICACAO   = 'VALORVENALEDIFICACAO';
    const VALOR_VENAL_TOTAL        = 'VALORVENALTOTAL';
    const ALIQUOTA                 = 'ALIQUOTA';

    /**
     * @var string|null DESCRICAO DO TIPO DE ISENCAO
     */
    private $isencaoDescricao = '';
    
    /**
     * @var DateTime|null DATA DE LANCAMENTO DA ISENCAO
     */
    private $isencaoDataLancamento = '';
    
    /**
     * @var float|null TOTAL DOS VALORES LANCADOS (IMPOSTO + TAXAS)
     */
    private $totalIptuTaxa = '';
    
    /**
     * @var integer|null QUANTIDADE DE LANCAMENTOS (IMPOSTO + TAXAS)
     */
    private $quantidadeIptuTaxa = '';
    
    /**
     * @var float|null TOTAL DOS VALORES LANCADOS (TAXAS
     */
    private $totalTaxa = '';
    
    /**
     * @var integer|null QUANTIDADE DE LANCAMENTOS (TAXAS
     */
    private $quantidadeTaxa = '';
    
    /**
     * @var float|null VALOR CORRIGIDO DA IPTU DESTA MATRICULA NO ANO 2018
     */
    private $valorCorrigidoIptu = '';
    
    /**
     * @var float|null VALOR DOS JUROS DA IPTU DESTA MATRICULA NO ANO 2018
     */
    private $valorJurosIptu = '';
    
    /**
     * @var float|null VALOR DA MULTA DA IPTU DESTA MATRICULA NO ANO 2018
     */
    private $valorMultaIptu = '';
    
    /**
     * @var float|null VALOR DO DESCONTO DA IPTU DESTA MATRICULA NO ANO 2018
     */
    private $valorDescontoIptu = '';
    
    /**
     * @var float|null VALOR TOTAL DA IPTU DESTA MATRICULA NO ANO 2018
     */
    private $valorTotalIptu = '';
    
    /**
     * @var sring|null CODIGO DA FACE
     */
    private $codigoFace = '';
    
    /**
     * @var float|null VALOR DO M2 DO TERRENO BASEADO NA FACE
     */
    private $valorM2TerrenoFace = '';
    
    /**
     * @var float|null VALOR DO M2 DAS EDIFICACOES BASEADO NA FACE
     */
    private $valorM2ConstrucaoFace = '';
    
    /**
     * @var float|null VALOR VENAL TERRENO
     */
    private $valorVenalTerreno = '';
    
    /**
     * @var float|null VALOR VENAL EDIFICACOES
     */
    private $valorVenalEdificacoes = '';
    
    /**
     * @var float|null VALOR VENAL TOTAL (TERRENO + EDIFICACOES
     */
    private $valorVenalTotal = '';
    
    /**
     * @var float|null ALIQUOTA
     */
    private $aliquota = '';

    /**
     * @return string|null
     *
     * Retorna os BRANCOS
     */
    public function getBrancos() {
        return $this->brancos;
    }

    /**
     * @return string|null
     *
     * Retorna a DESCRICAO DO TIPO DE ISENCAO
     */
    public function getIsencaoDescricao() {
        return $this->isencaoDescricao;
    }

    /**
     * @return DateTime|null
     *
     * Retorna a DATA DE LANCAMENTO DA ISENCAO
     */
    public function getIsencaoDataLancamento() {
        return $this->isencaoDataLancamento;
    }

    /**
     * @return float|null
     *
     * Retorna o TOTAL DOS VALORES LANCADOS (IMPOSTO + TAXAS)
     */
    public function getTotalIptuTaxa() {
        return $this->totalIptuTaxa;
    }

    /**
     * @return integer|null
     *
     * Retorna a QUANTIDADE DE LANCAMENTOS (IMPOSTO + TAXAS)
     */
    public function getQuantidadeIptuTaxa() {
        return $this->quantidadeIptuTaxa;
    }

    /**
     * @return float|null
     *
     * Retorna o TOTAL DOS VALORES LANCADOS (TAXAS
     */
    public function getTotalTaxa() {
        return $this->totalTaxa;
    }

    /**
     * @return integer|null
     *
     * Retorna a QUANTIDADE DE LANCAMENTOS (TAXAS
     */
    public function getQuantidadeTaxa() {
        return $this->quantidadeTaxa;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR CORRIGIDO DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function getValorCorrigidoIptu() {
        return $this->valorCorrigidoIptu;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR DOS JUROS DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function getValorJurosIptu() {
        return $this->valorJurosIptu;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR DA MULTA DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function getValorMultaIptu() {
        return $this->valorMultaIptu;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR DO DESCONTO DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function getValorDescontoIptu() {
        return $this->valorDescontoIptu;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR TOTAL DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function getValorTotalIptu() {
        return $this->valorTotalIptu;
    }

    /**
     * @return sring|null
     *
     * Retorna o CODIGO DA FACE
     */
    public function getCodigoFace() {
        return $this->codigoFace;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR DO M2 DO TERRENO BASEADO NA FACE
     */
    public function getValorM2TerrenoFace() {
        return $this->valorM2TerrenoFace;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR DO M2 DAS EDIFICACOES BASEADO NA FACE
     */
    public function getValorM2ConstrucaoFace() {
        return $this->valorM2ConstrucaoFace;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR VENAL TERRENO
     */
    public function getValorVenalTerreno() {
        return $this->valorVenalTerreno;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR VENAL EDIFICACOES
     */
    public function getValorVenalEdificacoes() {
        return $this->valorVenalEdificacoes;
    }

    /**
     * @return float|null
     *
     * Retorna o VALOR VENAL TOTAL (TERRENO + EDIFICACOES
     */
    public function getValorVenalTotal() {
        return $this->valorVenalTotal;
    }

    /**
     * @return float|null
     *
     * Retorna a ALIQUOTA
     */
    public function getAliquota() {
        return $this->aliquota;
    }

    /**
     * @param string|null $brancos
     *
     * Define os BRANCOS
     */
    public function setBrancos($brancos) {
        $this->brancos = $brancos;
        return $this;
    }
    
    /**
     * @param string|null $isencaoDescricao 
     *
     * Define a DESCRICAO DO TIPO DE ISENCAO
     */
    public function setIsencaoDescricao($isencaoDescricao) {
        $this->isencaoDescricao = $isencaoDescricao;
        return $this;
    }
    
    /**
     * @param DateTime|null $isencaoDataLancamento 
     *
     * Define a DATA DE LANCAMENTO DA ISENCAO
     */
    public function setIsencaoDataLancamento($isencaoDataLancamento) {
        $this->isencaoDataLancamento = $isencaoDataLancamento;
        return $this;
    }
    
    /**
     * @param float|null $totalIptuTaxa 
     *
     * Define o TOTAL DOS VALORES LANCADOS (IMPOSTO + TAXAS)
     */
    public function setTotalIptuTaxa($totalIptuTaxa) {
        $this->totalIptuTaxa = $totalIptuTaxa;
        return $this;
    }
    
    /**
     * @param integer|null $quantidadeIptuTaxa 
     *
     * Define a QUANTIDADE DE LANCAMENTOS (IMPOSTO + TAXAS)
     */
    public function setQuantidadeIptuTaxa($quantidadeIptuTaxa) {
        $this->quantidadeIptuTaxa = $quantidadeIptuTaxa;
        return $this;
    }
    
    /**
     * @param float|null $totalTaxa 
     *
     * Define o TOTAL DOS VALORES LANCADOS (TAXAS
     */
    public function setTotalTaxa($totalTaxa) {
        $this->totalTaxa = $totalTaxa;
        return $this;
    }
    
    /**
     * @param integer|null $quantidadeTaxa 
     *
     * Define a QUANTIDADE DE LANCAMENTOS (TAXAS
     */
    public function setQuantidadeTaxa($quantidadeTaxa) {
        $this->quantidadeTaxa = $quantidadeTaxa;
        return $this;
    }
    
    /**
     * @param float|null $valorCorrigidoIptu 
     *
     * Define o VALOR CORRIGIDO DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function setValorCorrigidoIptu($valorCorrigidoIptu) {
        $this->valorCorrigidoIptu = $valorCorrigidoIptu;
        return $this;
    }
    
    /**
     * @param float|null $valorJurosIptu 
     *
     * Define o VALOR DOS JUROS DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function setValorJurosIptu($valorJurosIptu) {
        $this->valorJurosIptu = $valorJurosIptu;
        return $this;
    }
    
    /**
     * @param float|null $valorMultaIptu 
     *
     * Define o VALOR DA MULTA DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function setValorMultaIptu($valorMultaIptu) {
        $this->valorMultaIptu = $valorMultaIptu;
        return $this;
    }
    
    /**
     * @param float|null $valorDescontoIptu 
     *
     * Define o VALOR DO DESCONTO DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function setValorDescontoIptu($valorDescontoIptu) {
        $this->valorDescontoIptu = $valorDescontoIptu;
        return $this;
    }
    
    /**
     * @param float|null $valorTotalIptu 
     *
     * Define o VALOR TOTAL DA IPTU DESTA MATRICULA NO ANO 2018
     */
    public function setValorTotalIptu($valorTotalIptu) {
        $this->valorTotalIptu = $valorTotalIptu;
        return $this;
    }
    
    /**
     * @param sring|null $codigoFace 
     *
     * Define o CODIGO DA FACE
     */
    public function setCodigoFace($codigoFace) {
        $this->codigoFace = $codigoFace;
        return $this;
    }
    
    /**
     * @param float|null $valorM2TerrenoFace 
     *
     * Define o VALOR DO M2 DO TERRENO BASEADO NA FACE
     */
    public function setValorM2TerrenoFace($valorM2TerrenoFace) {
        $this->valorM2TerrenoFace = $valorM2TerrenoFace;
        return $this;
    }
    
    /**
     * @param float|null $valorM2ConstrucaoFace 
     *
     * Define o VALOR DO M2 DAS EDIFICACOES BASEADO NA FACE
     */
    public function setValorM2ConstrucaoFace($valorM2ConstrucaoFace) {
        $this->valorM2ConstrucaoFace = $valorM2ConstrucaoFace;
        return $this;
    }
    
    /**
     * @param float|null $valorVenalTerreno 
     *
     * Define o VALOR VENAL TERRENO
     */
    public function setValorVenalTerreno($valorVenalTerreno) {
        $this->valorVenalTerreno = $valorVenalTerreno;
        return $this;
    }
    
    /**
     * @param float|null $valorVenalEdificacoes 
     *
     * Define o VALOR VENAL EDIFICACOES
     */
    public function setValorVenalEdificacoes($valorVenalEdificacoes) {
        $this->valorVenalEdificacoes = $valorVenalEdificacoes;
        return $this;
    }
    
    /**
     * @param float|null $valorVenalTotal 
     *
     * Define o VALOR VENAL TOTAL (TERRENO + EDIFICACOES
     */
    public function setValorVenalTotal($valorVenalTotal) {
        $this->valorVenalTotal = $valorVenalTotal;
        return $this;
    }
    
    /**
     * @param float|null $aliquota 
     *
     * Define a ALIQUOTA
     */
    public function setAliquota($aliquota) {
        $this->aliquota = $aliquota;
        return $this;
    }
}
