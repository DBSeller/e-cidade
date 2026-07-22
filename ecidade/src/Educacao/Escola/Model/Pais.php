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


use ECidade\Educacao\Escola\Registry\PaisRegistry;

class Pais
{
    private $codigo;
    private $nome;
    private $codigoOnu;
    private $sigla;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     * @return Pais
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     * @return Pais
     */
    public function setNome($nome)
    {
        $this->nome = trim($nome);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoOnu()
    {
        return $this->codigoOnu;
    }

    /**
     * @param mixed $codigoOnu
     * @return Pais
     */
    public function setCodigoOnu($codigoOnu)
    {
        $this->codigoOnu = $codigoOnu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param mixed $sigla
     * @return Pais
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
        return $this;
    }

    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('ed228_i_codigo', $state)) {
            $self->setCodigo($state['ed228_i_codigo']);
        }
        if (array_key_exists('ed228_c_descr', $state)) {
            $self->setNome($state['ed228_c_descr']);
        }
        if (array_key_exists('ed228_i_paisonu', $state)) {
            $self->setCodigoOnu($state['ed228_i_paisonu']);
        }
        if (array_key_exists('ed228_c_abrev', $state)) {
            $self->setSigla($state['ed228_c_abrev']);
        }

        PaisRegistry::set($self);
        return $self;
    }
}
