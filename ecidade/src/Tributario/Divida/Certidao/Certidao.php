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

use DateTime;
use ECidade\Tributario\Divida\Certidao\CertidaoDivida as CertidaoDividaEntity;

/**
 * Class Certidao
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Certidao
{
    /**
     * @var Integer
     */
    private $codigo;

    /**
     * @var DateTime
     */
    private $dataEmissao;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var Integer
     */
    private $login;

    /**
     * @var Oid
     */
    private $memo;

    /** @var CertidaoDividaEntity[] */
    private $certidaoDividas;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Certidao
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataEmissao()
    {
        return $this->dataEmissao;
    }

    /**
     * @param \DateTime $dataEmissao
     * @return Certidao
     */
    public function setDataEmissao($dataEmissao)
    {
        $this->dataEmissao = $dataEmissao;
        return $this;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao
     * @return Certidao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
     * @return int
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param int $login
     * @return Certidao
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return oid
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * @param oid $memo
     * @return Certidao
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;
        return $this;
    }

    /**
     * @return Integer[]
     */
    public function getNumpres()
    {
        $data = array();

        foreach ($this->certidaoDividas as $certidaoDivida) {
            $data[] = $certidaoDivida->getDivida()->getNumpre();
        }

        return $data;
    }

    /**
     * @return CertidaoDividaEntity[]
     */
    public function getCertidaoDividas()
    {
        return $this->certidaoDividas;
    }

    /**
     * @param CertidaoDividaEntity[] $certidaoDividas
     * @return Certidao
     */
    public function setCertidaoDividas($certidaoDividas)
    {
        $this->certidaoDividas = $certidaoDividas;
        return $this;
    }

    /**
     * @param CertidaoDividaEntity $certidaoDivida
     * @return Certidao
     */
    public function addCertidaoDivida(CertidaoDividaEntity $certidaoDivida)
    {
        $this->certidaoDividas[] = $certidaoDivida;
        return $this;
    }

    /**
     * @param integer $divida
     * @return Certidao
     */
    public function removeCertidaoDivida($divida)
    {
        foreach ($this->certidaoDividas as $key => $certidaoDivida) {
            if ($divida == $certidaoDivida->getDivida()->getCodigoDivida()) {
                unset($this->certidaoDividas[$key]);
            }
        }
        return $this;
    }

    /**
     * @param  $state
     * @return Certidao
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('v13_certid', $state)) {
            $self->setCodigo($state['v13_certid']);
        }

        if (array_key_exists('v13_dtemis', $state)) {
            $dataEmissao = new DateTime($state['v13_dtemis']);
            $self->setDataEmissao($dataEmissao);
        }

        if (array_key_exists('v13_memo', $state)) {
            $self->setMemo($state['v13_memo']);
        }

        if (array_key_exists('v13_login', $state)) {
            $self->setLogin($state['v13_login']);
        }

        if (array_key_exists('v13_instit', $state)) {
            $instituicao = \InstituicaoRepository::getInstituicaoByCodigo($state['v13_instit']);
            $self->setInstituicao($instituicao);
        }

        return $self;
    }
}
