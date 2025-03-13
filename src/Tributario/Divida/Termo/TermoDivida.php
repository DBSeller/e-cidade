<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Divida\Termo;

/**
 * Entidade que modela a tabela certter do banco de dados.
 *
 * @author Matheus.lino <matheus.lino@dbseller.com.br>
 */
class TermoDivida
{
    /**
     * @var Integer
     */
    private $parcelamento;

    /**
     * @var Integer
     */
    private $codigoDivida;

    /**
     * @var Number
     */
    private $valor;

    /**
     * @var Number
     */
    private $juros;

    /**
     * @var Number
     */
    private $multa;

    /**
     * @var Number
     */
    private $desconto;

    /**
     * @var Number
     */
    private $total;

    /**
     * @var Integer
     */
    private $numpreAnterior;

    /**
     * @var Number
     */
    private $percentual;

    /**
     * @var Number
     */
    private $valorCorrigido;

    /**
     * @var Number
     */
    private $valorDescontoJuros;

    /**
     * @var Number
     */
    private $valorDescontoMulta;

    /**
     * @var Number
     */
    private $valorDescontoCor;

    /**
     * @return int
     */
    public function getParcelamento()
    {
        return $this->parcelamento;
    }

    /**
     * @param int $parcelamento
     * @return TermoDivida
     */
    public function setParcelamento($parcelamento)
    {
        $this->parcelamento = $parcelamento;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoDivida()
    {
        return $this->codigoDivida;
    }

    /**
     * @param int $codigoDivida
     * @return TermoDivida
     */
    public function setCodigoDivida($codigoDivida)
    {
        $this->codigoDivida = $codigoDivida;
        return $this;
    }

    /**
     * @return number
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param number $valor
     * @return TermoDivida
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return Number
     */
    public function getJuros()
    {
        return $this->juros;
    }

    /**
     * @param Number $juros
     * @return TermoDivida
     */
    public function setJuros($juros)
    {
        $this->juros = $juros;
        return $this;
    }

    /**
     * @return Number
     */
    public function getMulta()
    {
        return $this->multa;
    }

    /**
     * @param Number $multa
     * @return TermoDivida
     */
    public function setMulta($multa)
    {
        $this->multa = $multa;
        return $this;
    }

    /**
     * @return Number
     */
    public function getDesconto()
    {
        return $this->desconto;
    }

    /**
     * @param Number $desconto
     * @return TermoDivida
     */
    public function setDesconto($desconto)
    {
        $this->desconto = $desconto;
        return $this;
    }

    /**
     * @return Number
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param Number $total
     * @return TermoDivida
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return Integer
     */
    public function getNumpreAnterior()
    {
        return $this->numpreAnterior;
    }

    /**
     * @param Integer $numpreAnterior
     * @return TermoDivida
     */
    public function setNumpreAnterior($numpreAnterior)
    {
        $this->numpreAnterior = $numpreAnterior;
        return $this;
    }

    /**
     * @return Number
     */
    public function getPercentual()
    {
        return $this->percentual;
    }

    /**
     * @param Number $percentual
     * @return TermoDivida
     */
    public function setPercentual($percentual)
    {
        $this->percentual = $percentual;
        return $this;
    }

    /**
     * @return Number
     */
    public function getValorCorrigido()
    {
        return $this->valorCorrigido;
    }

    /**
     * @param Number $valorCorrigido
     * @return TermoDivida
     */
    public function setValorCorrigido($valorCorrigido)
    {
        $this->valorCorrigido = $valorCorrigido;
        return $this;
    }

    /**
     * @return Number
     */
    public function getValorDescontoJuros()
    {
        return $this->valorDescontoJuros;
    }

    /**
     * @param Number $valorDescontoJuros
     * @return TermoDivida
     */
    public function setValorDescontoJuros($valorDescontoJuros)
    {
        $this->valorDescontoJuros = $valorDescontoJuros;
        return $this;
    }

    /**
     * @return Number
     */
    public function getValorDescontoMulta()
    {
        return $this->valorDescontoMulta;
    }

    /**
     * @param Number $valorDescontoMulta
     * @return TermoDivida
     */
    public function setValorDescontoMulta($valorDescontoMulta)
    {
        $this->valorDescontoMulta = $valorDescontoMulta;
        return $this;
    }

    /**
     * @return Number
     */
    public function getValorDescontoCor()
    {
        return $this->valorDescontoCor;
    }

    /**
     * @param Number $valorDescontoCor
     * @return TermoDivida
     */
    public function setValorDescontoCor($valorDescontoCor)
    {
        $this->valorDescontoCor = $valorDescontoCor;
        return $this;
    }

    /**
     * @param  $state
     * @return TermoDivida
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('parcel', $state)) {
            $self->setParcelamento($state['parcel']);
        }

        if (array_key_exists('coddiv', $state)) {
            $self->setCodigoDivida($state['coddiv']);
        }

        if (array_key_exists('valor', $state)) {
            $self->setValor($state['valor']);
        }

        if (array_key_exists('juros', $state)) {
            $self->setJuros($state['juros']);
        }

        if (array_key_exists('multa', $state)) {
            $self->setMulta($state['multa']);
        }

        if (array_key_exists('desconto', $state)) {
            $self->setDesconto($state['desconto']);
        }

        if (array_key_exists('total', $state)) {
            $self->setTotal($state['total']);
        }

        if (array_key_exists('numpreant', $state)) {
            $self->setNumpreAnterior($state['numpreant']);
        }

        if (array_key_exists('v77_perc', $state)) {
            $self->setPercentual($state['v77_perc']);
        }

        if (array_key_exists('vlrcor', $state)) {
            $self->setValorCorrigido($state['vlrcor']);
        }

        if (array_key_exists('vlrdescjur', $state)) {
            $self->setValorDescontoJuros($state['vlrdescjur']);
        }

        if (array_key_exists('vlrdescmul', $state)) {
            $self->setValorDescontoMulta($state['vlrdescmul']);
        }

        if (array_key_exists('vlrdesccor', $state)) {
            $self->setValorDescontoCor($state['vlrdesccor']);
        }

        return $self;
    }
}
