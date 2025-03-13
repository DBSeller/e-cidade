<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use \DateTime;
use ECidade\Tributario\Library\Entity;

final class ParcelaRecibo extends Entity
{
    const VENCIMENTO_PARCELA    = 'VENCIMENTOPARCELA';
    const VALOR_PARCELA         = 'VALORPARCELA';
    const VALORJURO_PARCELA     = 'VALORJUROPARCELA';
    const VALORMULTA_PARCELA    = 'VALORMULTAPARCELA';
    const NUMPRE_PARCELA        = 'NUMPREPARCELA';
    const CODIGOBARRAS_PARCELA  = 'CODIGOBARRASPARCELA';
    const PARCELA               = 'PARCELA';

    const MAXIMO_PARCELAS       = 12;

    private $vencimento;

    private $valor;

    private $juros;

    private $multa;

    private $codigoArrecadacao;

    private $codigoBarras;

    private $numero;

    public function setVencimento(DateTime $vencimento)
    {
        $this->vencimento = $vencimento;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function setJuros($juros)
    {
        $this->juros = $juros;
    }

    public function setMulta($multa)
    {
        $this->multa = $multa;
    }

    public function setCodigoArrecadacao($codigoArrecadacao)
    {
        $this->codigoArrecadacao = $codigoArrecadacao;
    }

    public function setCodigoBarras($codigoBarras)
    {
        $this->codigoBarras = $codigoBarras;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getVencimento()
    {
        return $this->vencimento;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getJuros()
    {
        return $this->juros;
    }

    public function getMulta()
    {
        return $this->multa;
    }

    public function getCodigoArrecadacao()
    {
        return $this->codigoArrecadacao;
    }

    public function getCodigoBarras()
    {
        return $this->codigoBarras;
    }

    public function getNumero()
    {
        return $this->numero;
    }
}
