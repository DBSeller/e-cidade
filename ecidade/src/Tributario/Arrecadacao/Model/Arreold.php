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

namespace ECidade\Tributario\Arrecadacao\Model;

use DateTime;

/**
 * Entidade que modela a tabela arreold do banco de dados.
 *
 * @author Matheus.lino <matheus.lino@dbseller.com.br>
 */

class Arreold
{
    /**
     * @var Integer
     */
    private $numpre;

    /**
     * @var Integer
     */
    private $numpar;

    /**
     * @var Integer
     */
    private $numcgm;

    /**
     * @var DateTime
     */
    private $dataOperacao;

    /**
     * @var Integer
     */
    private $receita;

    /**
     * @var Integer
     */
    private $historico;

    /**
     * @var Number
     */
    private $valor;

    /**
     * @var DateTime
     */
    private $dataVencimento;

    /**
     * @var Integer
     */
    private $numtot;

    /**
     * @var Integer
     */
    private $numdig;

    /**
     * @var Integer
     */
    private $tipo;

    /**
     * @var Integer
     */
    private $tipojm;

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @param int $numpre
     * @return Arreold
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumpar()
    {
        return $this->numpar;
    }

    /**
     * @param int $numpar
     * @return Arreold
     */
    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumCgm()
    {
        return $this->numcgm;
    }

    /**
     * @param int $numcgm
     * @return Arreold
     */
    public function setNumCgm($numcgm)
    {
        $this->numcgm = $numcgm;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDataOperacao()
    {
        return $this->dataOperacao;
    }

    /**
     * @param DateTime $dataOperacao
     * @return Arreold
     */
    public function setDataOperacao($dataOperacao)
    {
        $this->dataOperacao = $dataOperacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getReceita()
    {
        return $this->receita;
    }

    /**
     * @param int $receita
     * @return Arreold
     */
    public function setReceita($receita)
    {
        $this->receita = $receita;
        return $this;
    }

    /**
     * @return int
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * @param int $historico
     * @return Arreold
     */
    public function setHistorico($historico)
    {
        $this->historico = $historico;
        return $this;
    }

    /**
     * @return Number
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param Number $valor
     * @return Arreold
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @param DateTime $dataVencimento
     * @return Arreold
     */
    public function setDataVencimento($dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumTot()
    {
        return $this->numtot;
    }

    /**
     * @param int $numtot
     * @return Arreold
     */
    public function setNumTot($numtot)
    {
        $this->numtot = $numtot;
        return $this;
    }

    /**
     * @return Integer
     */
    public function getNumDig()
    {
        return $this->numdig;
    }

    /**
     * @param Integer $numdig
     * @return Arreold
     */
    public function setNumDig($numdig)
    {
        $this->numdig = $numdig;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param int $tipo
     * @return Arreold
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipoJM()
    {
        return $this->tipojm;
    }

    /**
     * @param int $tipojm
     * @return Arreold
     */
    public function setTipoJM($tipojm)
    {
        $this->tipojm = $tipojm;
        return $this;
    }

    /**
     * @param  $state
     * @return Arreold
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('k00_numpre', $state)) {
            $self->setNumpre($state['k00_numpre']);
        }

        if (array_key_exists('k00_numpar', $state)) {
            $self->setNumpar($state['k00_numpar']);
        }

        if (array_key_exists('k00_numcgm', $state)) {
            $self->setNumCgm($state['k00_numcgm']);
        }

        if (array_key_exists('k00_dtoper', $state)) {
            $dataOperacao = new DateTime($state['k00_dtoper']);
            $self->setDataOperacao($dataOperacao);
        }

        if (array_key_exists('k00_receit', $state)) {
            $self->setReceita($state['k00_receit']);
        }

        if (array_key_exists('k00_hist', $state)) {
            $self->setHistorico($state['k00_hist']);
        }

        if (array_key_exists('k00_valor', $state)) {
            $self->setValor($state['k00_valor']);
        }

        if (array_key_exists('k00_dtvenc', $state)) {
            $dataOperacao = new DateTime($state['k00_dtvenc']);
            $self->setDataVencimento($dataOperacao);
        }

        if (array_key_exists('k00_numtot', $state)) {
            $self->setNumTot($state['k00_numtot']);
        }

        if (array_key_exists('k00_numdig', $state)) {
            $self->setNumDig($state['k00_numdig']);
        }

        if (array_key_exists('k00_tipo', $state)) {
            $self->setTipo($state['k00_tipo']);
        }

        if (array_key_exists('k00_tipojm', $state)) {
            $self->setTipoJM($state['k00_tipojm']);
        }

        return $self;
    }
}
