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

namespace ECidade\Educacao\Escola\Model;

/**
 * Class AtividadeComplementar
 * @package ECidade\Educacao\Escola\Model
 */
class AtividadeComplementar
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var integer
     */
    private $tipo;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var boolean
     */
    private $ativo;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return AtividadeComplementar
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
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
     * @return AtividadeComplementar
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
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
     * @return AtividadeComplementar
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param bool $ativo
     * @return AtividadeComplementar
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @param array $state
     * @return AtividadeComplementar
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed133_i_codigo', $state)) {
            $self->setCodigo($state['ed133_i_codigo']);
        }
        if (array_key_exists('ed133_i_tipo', $state)) {
            $self->setTipo($state['ed133_i_tipo']);
        }
        if (array_key_exists('ed133_c_descr', $state)) {
            $self->setDescricao($state['ed133_c_descr']);
        }
        if (array_key_exists('ed133_ativo', $state)) {
            $self->setAtivo($state['ed133_ativo'] == 't');
        }
        return $self;
    }
}
