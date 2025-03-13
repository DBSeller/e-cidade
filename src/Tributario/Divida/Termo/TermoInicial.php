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

use ECidade\Tributario\Juridico\Inicial\Inicial as InicialEntity;

/**
 * Entidade para representação da tabela termoini.
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class TermoInicial
{
    /** @var InicialEntity */
    private $inicial;

    /** @var float numpreant */
    private $numpreAnterior;

    /** @var float valor */
    private $valor;

    /** @var float juros */
    private $juros;

    /** @var float multa */
    private $multa;

    /** @var float desconto */
    private $desconto;

    /** @var float total */
    private $total;

    /** @var float vlrcor */
    private $valorCorrigido;

    /** @var float v61_perc */
    private $percentual;

    /** @var float vlrdescjur */
    private $valorDescontoJuros;

    /** @var float vlrdescmul */
    private $valorDescontoMulta;

    /** @var float vlrdesccor */
    private $valorDescontoCorrigido;

    /**
     * @return InicialEntity
     */
    public function getInicial()
    {
        return $this->inicial;
    }

    /**
     * @param InicialEntity $inicial
     * @return TermoInicial
     */
    public function setInicial($inicial)
    {
        $this->inicial = $inicial;
        return $this;
    }

    /**
     * @return float
     */
    public function getNumpreAnterior()
    {
        return $this->numpreAnterior;
    }

    /**
     * @param float $numpreAnterior
     * @return TermoInicial
     */
    public function setNumpreAnterior($numpreAnterior)
    {
        $this->numpreAnterior = $numpreAnterior;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     * @return TermoInicial
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return float
     */
    public function getJuros()
    {
        return $this->juros;
    }

    /**
     * @param float $juros
     * @return TermoInicial
     */
    public function setJuros($juros)
    {
        $this->juros = $juros;
        return $this;
    }

    /**
     * @return float
     */
    public function getMulta()
    {
        return $this->multa;
    }

    /**
     * @param float $multa
     * @return TermoInicial
     */
    public function setMulta($multa)
    {
        $this->multa = $multa;
        return $this;
    }

    /**
     * @return float
     */
    public function getDesconto()
    {
        return $this->desconto;
    }

    /**
     * @param float $desconto
     * @return TermoInicial
     */
    public function setDesconto($desconto)
    {
        $this->desconto = $desconto;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return TermoInicial
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorCorrigido()
    {
        return $this->valorCorrigido;
    }

    /**
     * @param float $valorCorrigido
     * @return TermoInicial
     */
    public function setValorCorrigido($valorCorrigido)
    {
        $this->valorCorrigido = $valorCorrigido;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercentual()
    {
        return $this->percentual;
    }

    /**
     * @param float $percentual
     * @return TermoInicial
     */
    public function setPercentual($percentual)
    {
        $this->percentual = $percentual;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorDescontoJuros()
    {
        return $this->valorDescontoJuros;
    }

    /**
     * @param float $valorDescontoJuros
     * @return TermoInicial
     */
    public function setValorDescontoJuros($valorDescontoJuros)
    {
        $this->valorDescontoJuros = $valorDescontoJuros;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorDescontoMulta()
    {
        return $this->valorDescontoMulta;
    }

    /**
     * @param float $valorDescontoMulta
     * @return TermoInicial
     */
    public function setValorDescontoMulta($valorDescontoMulta)
    {
        $this->valorDescontoMulta = $valorDescontoMulta;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorDescontoCorrigido()
    {
        return $this->valorDescontoCorrigido;
    }

    /**
     * @param float $valorDescontoCorrigido
     * @return TermoInicial
     */
    public function setValorDescontoCorrigido($valorDescontoCorrigido)
    {
        $this->valorDescontoCorrigido = $valorDescontoCorrigido;
        return $this;
    }
}