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

namespace ECidade\Tributario\Divida\Certidao;

/**
 * Entidade que modela a tabela certdiv do banco de dados.
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class CertidaoDivida
{
    /**
     * @var Integer
     */
    private $codigoCertidao;

    /**
     * @var Integer
     */
    private $divida;

    /**
     * @var Float
     */
    private $valorHistorico;

    /**
     * @var Float
     */
    private $valorCorrigido;

    /**
     * @var Float
     */
    private $valorJuro;

    /**
     * @var Float
     */
    private $valorMulta;

    /**
     * @return int
     */
    public function getCodigoCertidao()
    {
        return $this->codigoCertidao;
    }

    /**
     * @param int $codigoCertidao
     * @return CertidaoDivida
     */
    public function setCodigoCertidao($codigoCertidao)
    {
        $this->codigoCertidao = $codigoCertidao;
        return $this;
    }

    /**
     * @return int
     */
    public function getDivida()
    {
        return $this->divida;
    }

    /**
     * @param int $divida
     * @return CertidaoDivida
     */
    public function setDivida($divida)
    {
        $this->divida = $divida;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorHistorico()
    {
        return $this->valorHistorico;
    }

    /**
     * @param float $valorHistorico
     * @return CertidaoDivida
     */
    public function setValorHistorico($valorHistorico)
    {
        $this->valorHistorico = $valorHistorico;
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
     * @return CertidaoDivida
     */
    public function setValorCorrigido($valorCorrigido)
    {
        $this->valorCorrigido = $valorCorrigido;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorJuro()
    {
        return $this->valorJuro;
    }

    /**
     * @param float $valorJuro
     * @return CertidaoDivida
     */
    public function setValorJuro($valorJuro)
    {
        $this->valorJuro = $valorJuro;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorMulta()
    {
        return $this->valorMulta;
    }

    /**
     * @param float $valorMulta
     * @return CertidaoDivida
     */
    public function setValorMulta($valorMulta)
    {
        $this->valorMulta = $valorMulta;
        return $this;
    }

    /**
     * @param  $state
     * @return CertidaoDivida
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('v14_certid', $state)) {
            $self->setCodigoCertidao($state['v14_certid']);
        }

        if (array_key_exists('v14_coddiv', $state)) {
            $self->setDivida($state['v14_coddiv']);
        }

        if (array_key_exists('v14_vlrhis', $state)) {
            $self->setValorHistorico($state['v14_vlrhis']);
        }

        if (array_key_exists('v14_vlrcor', $state)) {
            $self->setValorCorrigido($state['v14_vlrcor']);
        }

        if (array_key_exists('v14_vlrjur', $state)) {
            $self->setValorJuro($state['v14_vlrjur']);
        }

        if (array_key_exists('v14_vlrmul', $state)) {
            $self->setValorMulta($state['v14_vlrmul']);
        }

        return $self;
    }
}
