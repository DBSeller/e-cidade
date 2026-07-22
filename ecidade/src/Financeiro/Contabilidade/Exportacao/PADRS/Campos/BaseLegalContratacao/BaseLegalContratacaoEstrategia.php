<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\BaseLegalContratacao;

/**
 * Class BaseLegalContratacaoEstrategia
 * @package ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\BaseLegalContratacao
 */
class BaseLegalContratacaoEstrategia
{
    /**
     * @var null
     */
    protected $empenho;

    /**
     * BaseLegalContratacaoEstrategia constructor.
     * @param null $empenho
     */
    public function __construct($empenho = null)
    {
        $this->empenho = $empenho;
    }

    /**
     * @return string
     */
    public function getValor()
    {
        return '';
    }
}
