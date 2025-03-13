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


use ECidade\Educacao\Escola\Registry\InstituicaoEnsinoRegistry;

class InstituicaoEnsino
{
    private $codigo;
    private $nome;
    private $dependencia;
    private $tipo;
    private $MunicipioCenso;
    private $situacao;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     * @return InstituicaoEnsino
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
     * @return InstituicaoEnsino
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDependencia()
    {
        return $this->dependencia;
    }

    /**
     * @param mixed $dependencia
     * @return InstituicaoEnsino
     */
    public function setDependencia($dependencia)
    {
        $this->dependencia = $dependencia;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     * @return InstituicaoEnsino
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMunicipioCenso()
    {
        return $this->MunicipioCenso;
    }

    /**
     * @param mixed $MunicipioCenso
     * @return InstituicaoEnsino
     */
    public function setMunicipioCenso($MunicipioCenso)
    {
        $this->MunicipioCenso = $MunicipioCenso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param mixed $situacao
     * @return InstituicaoEnsino
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }

    /**
     * @param array $state
     * @return InstituicaoEnsino
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed257_i_codigo', $state)) {
            $self->setCodigo($state['ed257_i_codigo']);
        }
        if (array_key_exists('ed257_c_nome', $state)) {
            $self->setNome(trim($state['ed257_c_nome']));
        }
        if (array_key_exists('ed257_i_dependencia', $state)) {
            $self->setDependencia($state['ed257_i_dependencia']);
        }
        if (array_key_exists('ed257_i_tipo', $state)) {
            $self->setTipo($state['ed257_i_tipo']);
        }
        if (array_key_exists('ed257_i_censomunic', $state)) {
            $self->setMunicipioCenso($state['ed257_i_censomunic']);
        }
        if (array_key_exists('ed257_c_situacao', $state)) {
            $self->setSituacao($state['ed257_c_situacao']);
        }

        InstituicaoEnsinoRegistry::set($self);

        return $self;
    }
}
