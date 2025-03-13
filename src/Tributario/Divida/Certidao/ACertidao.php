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

/**
 * Entidade que modela a tabela acertid do banco de dados.
 *
 * @author Matheus.lino <matheus.lino@dbseller.com.br>
 */
class ACertidao
{
    /**
     * @var Integer
     */
    private $codigo = null;

    /**
     * @var Integer
     */
    private $certidao;

    /**
     * @var DateTime
     */
    private $data;

    /**
     * @var String
     */
    private $hora;

    /**
     * @var integer
     */
    private $usuario;

    /**
     * @var Boolean
     */
    private $parcial;

    /**
     * @var Instituicao
     */
    private $instituicao;

     /**
     * @var String
     */
    private $observacao;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return ACertidao
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCertidao()
    {
        return $this->certidao;
    }

    /**
     * @param int $certidao
     * @return ACertidao
     */
    public function setCertidao($certidao)
    {
        $this->certidao = $certidao;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param DateTime $data
     * @return ACertidao
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return String
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param String $hora
     * @return ACertidao
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
        return $this;
    }

    /**
     * @return integer
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param int $usuario
     * @return ACertidao
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @return Boolean
     */
    public function getParcial()
    {
        return $this->parcial;
    }

    /**
     * @param bool $parcial
     * @return ACertidao
     */
    public function setParcial($parcial)
    {
        $this->parcial = $parcial;
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
     * @param Instituicao $instituicao
     * @return ACertidao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
     * @return String
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param String $observacao
     * @return ACertidao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }

    /**
     * @param  $state
     * @return ACertidao
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('v15_codigo', $state)) {
            $self->setCodigo($state['v15_codigo']);
        }

        if (array_key_exists('v15_certid', $state)) {
            $self->setCertidao($state['v15_certid']);
        }

        if (array_key_exists('v15_data', $state)) {
            $data = new DateTime($state['v15_data']);
            $self->setData($data);
        }

        if (array_key_exists('v15_hora', $state)) {
            $self->setHora($state['v15_hora']);
        }

        if (array_key_exists('v15_usuario', $state)) {
            $self->setUsuario($state['v15_usuario']);
        }

        if (array_key_exists('v15_parcial', $state)) {
            $self->setParcial($state['v15_parcial']);
        }

        if (array_key_exists('v15_instit', $state)) {
            $instituicao = \InstituicaoRepository::getInstituicaoByCodigo($state['v15_instit']);
            $self->setInstituicao($instituicao);
        }

        if (array_key_exists('v15_observacao', $state)) {
            $self->setObservacao($state['v15_observacao']);
        }

        return $self;
    }
}
