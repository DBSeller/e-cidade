<?php
/*
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

namespace ECidade\Financeiro\Contabilidade\Sigap\Model;

/**
 * Class MeioComunicacao
 * @package ECidade\Financeiro\Contabilidade\Sigap\Model
 */
class MeioComunicacao
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var string
     */
    private $codigoSigap;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $uf;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return MeioComunicacao
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigoSigap()
    {
        return $this->codigoSigap;
    }

    /**
     * @param string $codigoSigap
     * @return MeioComunicacao
     */
    public function setCodigoSigap($codigoSigap)
    {
        $this->codigoSigap = $codigoSigap;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     * @return MeioComunicacao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param string $uf
     * @return MeioComunicacao
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
        return $this;
    }


    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('c49_sequencial', $state)) {
            $self->setCodigo($state['c49_sequencial']);
        }
        if (array_key_exists('c49_codigocomunicacao', $state)) {
            $self->setCodigoSigap($state['c49_codigocomunicacao']);
        }
        if (array_key_exists('c49_descricao', $state)) {
            $self->setDescricao($state['c49_descricao']);
        }
        if (array_key_exists('c49_uf', $state)) {
            $self->setUf($state['c49_uf']);
        }

        return $self;
    }
}
