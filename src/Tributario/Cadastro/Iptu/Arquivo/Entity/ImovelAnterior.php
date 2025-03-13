<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class ImovelAnterior extends Entity
{
    const TESTADA_LOTE          = 'TESTADALOTE';
    const AREA_LOTE             = 'AREALOTE';
    const AREA_TOTAL_CONSTRUIDA = 'AREATOTALCONSTRUIDA';
    const REFERENCIA_ANTERIOR   = 'REFERENCIAANTERIOR';
    const AREA_LOTE_CALCULO     = 'AREALOTECALCULO';
    const VALOR_M2_CALCULO      = 'VALORM2CALCULO';

    /**
     * @var double|null
     */
    private $testadaLote;

    /**
     * @var float|null
     */
    private $areaLote;

    /**
     * @var float|null
     */
    private $areaTotalConstruida;

    /**
     * @var string|null
     */
    private $referenciaAnterior;

    /**
     * @var float|null
     */
    private $areaLoteCalculo;

    /**
     * @var double|null
     */
    private $valorM2Calculo;

    /**
     * @param double|null $testadaLote
     *
     * Define a TESTADA PRINCIPAL DO LOTE
     */
    public function setTestadaLote($testadaLote) {
        $this->testadaLote = $testadaLote;
    }

    /**
     * @param float|null $areaLote
     *
     * Define a AREA DO LOTE
     */
    public function setAreaLote($areaLote) {
        $this->areaLote = $areaLote;
    }

    /**
     * @param float|null $areaTotalConstruida
     *
     * Define a AREA TOTAL CONSTRUIDA
     */
    public function setAreaTotalConstruida($areaTotalConstruida) {
        $this->areaTotalConstruida = $areaTotalConstruida;
    }

    /**
     * @param string|null $referenciaAnterior
     *
     * Define a REFERENCIA ANTERIOR
     */
    public function setReferenciaAnterior($referenciaAnterior) {
        $this->referenciaAnterior = $referenciaAnterior;
    }

    /**
     * @param float|null $areaLoteCalculo
     *
     * Define a AREA DO LOTE CONSIDERADA NO CALCULO
     */
    public function setAreaLoteCalculo($areaLoteCalculo) {
        $this->areaLoteCalculo = $areaLoteCalculo;
    }

    /**
     * @param double|null $valorM2Calculo
     *
     * Define o VALOR DO METRO QUADRADO DO TERRENO DO CALCULO
     */
    public function setValorM2Calculo($valorM2Calculo) {
        $this->valorM2Calculo = $valorM2Calculo;
    }

    /**
     * @return double|null
     *
     * Retorna a TESTADA PRINCIPAL DO LOTE
     */
    public function getTestadaLote() {
        return $this->testadaLote;
    }

    /**
     * @return float|null
     *
     * Retorna a AREA DO LOTE
     */
    public function getAreaLote() {
        return $this->areaLote;
    }

    /**
     * @return float|null
     *
     * Retorna a AREA TOTAL CONSTRUIDA
     */
    public function getAreaTotalConstruida() {
        return $this->areaTotalConstruida;
    }

    /**
     * @return string|null
     *
     * Retorna a REFERENCIA ANTERIOR
     */
    public function getReferenciaAnterior() {
        return $this->referenciaAnterior;
    }

    /**
     * @return float|null
     *
     * Retorna a AREA DO LOTE CONSIDERADA NO CALCULO
     */
    public function getAreaLoteCalculo() {
        return $this->areaLoteCalculo;
    }

    /**
     * @return double|null
     *
     * Retorna o VALOR DO METRO QUADRADO DO TERRENO DO CALCULO
     */
    public function getValorM2Calculo() {
        return $this->valorM2Calculo;
    }
}
