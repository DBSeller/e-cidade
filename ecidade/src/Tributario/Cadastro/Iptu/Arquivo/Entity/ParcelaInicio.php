<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;
use Ecidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaReceita;

final class ParcelaInicio extends Entity
{
    const TOTAL_PARCELAS                  = 'TOTALPARCELAS';
    const EXPRESAO_PARCELADOS             = 'EXPRESAOPARCELADOS';
    const PERCENTUAL_MES_JURO_ATRASO      = 'PERCENTUALMESJUROATRASO';
    const PERCENTUAL_GERAL_MULTA_ATRASO   = 'PERCENTUALGERALMULTAATRASO';

    /**
     * @var integer|null
     *
     * QUANTIDADE TOTAL DE PARCELAS
     */
    private $totalParcelas = 0;
    
    /**
     * @var string|null
     *
     * EXPRESSAO PARCELADOS
     */
    private $expresaoParcelados = 'PARCELADOS';
    
    /**
     * @var double|null
     *
     * PERCENTUAL POR MES DE JUROS POR ATRASO
     */
    private $percentualMesJuroAtraso = 0;
    
    /**
     * @var double|null
     *
     * PERCENTUAL GERAL DE MULTA POR ATRASO
     */
    private $percentualGeralMultaAtraso = 0;

    /**
     * @return integer|null
     *
     * Retorna a QUANTIDADE TOTAL DE PARCELAS
     */
    public function getTotalParcelas()
    {
        return $this->totalParcelas;
    }
    
    /**
     * @return string|null
     *
     * Retorna a EXPRESSAO PARCELADOS
     */
    public function getExpresaoParcelados()
    {
        return $this->expresaoParcelados;
    }
    
    /**
     * @return double|null
     *
     * Retorna o PERCENTUAL POR MES DE JUROS POR ATRASO
     */
    public function getPercentualMesJuroAtraso()
    {
        return $this->percentualMesJuroAtraso;
    }
    
    /**
     * @return double|null
     *
     * Retorna o PERCENTUAL GERAL DE MULTA POR ATRASO
     */
    public function getPercentualGeralMultaAtraso()
    {
        return $this->percentualGeralMultaAtraso;
    }

    /**
     * @param integer|null $totalParcelas
     *
     * Define a QUANTIDADE TOTAL DE PARCELAS
     */
    public function setTotalParcelas($totalParcelas)
    {
        $this->totalParcelas = $totalParcelas;
        return $this;
    }
    
    /**
     * @param string|null $expresaoParcelados
     *
     * Define a EXPRESSAO PARCELADOS
     */
    public function setExpresaoParcelados($expresaoParcelados)
    {
        $this->expresaoParcelados = $expresaoParcelados;
        return $this;
    }
    
    /**
     * @param double|null $percentualMesJuroAtraso
     *
     * Define o PERCENTUAL POR MES DE JUROS POR ATRASO
     */
    public function setPercentualMesJuroAtraso($percentualMesJuroAtraso)
    {
        $this->percentualMesJuroAtraso = $percentualMesJuroAtraso;
        return $this;
    }
    
    /**
     * @param double|null $percentualGeralMultaAtraso
     *
     * Define o PERCENTUAL GERAL DE MULTA POR ATRASO
     */
    public function setPercentualGeralMultaAtraso($percentualGeralMultaAtraso)
    {
        $this->percentualGeralMultaAtraso = $percentualGeralMultaAtraso;
        return $this;
    }

}
