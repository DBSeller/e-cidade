<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\IdentificadorDespesaFuncionario;

class IdentificadorDespesaFuncionarioEstrategia
{
    protected $empenho;

    public function __construct($empenho)
    {
        $this->empenho = $empenho;
    }

    public function getValor()
    {
        return '';
    }

    public function setValor($valor)
    {
    }

    public function getDescricao()
    {
        return '';
    }
}
