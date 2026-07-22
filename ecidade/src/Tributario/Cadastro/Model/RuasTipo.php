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

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Cadastro\Registry\RuasTipoRegistry;

/**
 * Class RuasTipo
 * @package ECidade\Tributario\Cadastro\Model
 */
class RuasTipo
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var string
     */
    private $sigla;
    /**
     * @var string
     */
    private $descricao;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return RuasTipo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     * @return RuasTipo
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
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
     * @return RuasTipo
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @param array $state
     * @return RuasTipo
     */
    public static function fromState($state = array())
    {
        $self = new self();
        if (array_key_exists('j88_codigo', $state)) {
            $self->setCodigo($state['j88_codigo']);
        }
        if (array_key_exists('j88_sigla', $state)) {
            $self->setSigla($state['j88_sigla']);
        }
        if (array_key_exists('j88_descricao', $state)) {
            $self->setDescricao($state['j88_descricao']);
        }

        RuasTipoRegistry::set($self);
        return $self;
    }
}
