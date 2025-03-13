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

use ECidade\Educacao\Escola\Registry\CursoFormacaoRegistry;

class CursoFormacao
{
    const GRAU_ACADEMICO_TECNOLOGICO = 1;
    const GRAU_ACADEMICO_BACHARELADO = 2;
    const GRAU_ACADEMICO_LICENCIATURA = 3;

    private $codigo;
    private $nome;
    private $codigoClasse;
    private $codigoCenso;
    private $descricaoClasse;
    private $grauAcademico;
    private $ativo = true;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     * @return CursoFormacao
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return CursoFormacao
     */
    public function setNome($nome)
    {
        $this->nome = trim($nome);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoClasse()
    {
        return $this->codigoClasse;
    }

    /**
     * @param mixed $codigoClasse
     * @return CursoFormacao
     */
    public function setCodigoClasse($codigoClasse)
    {
        $this->codigoClasse = $codigoClasse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoCenso()
    {
        return $this->codigoCenso;
    }

    /**
     * @param mixed $codigoCenso
     * @return CursoFormacao
     */
    public function setCodigoCenso($codigoCenso)
    {
        $this->codigoCenso = $codigoCenso;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricaoClasse()
    {
        return $this->descricaoClasse;
    }

    /**
     * @param string $descricaoClasse
     * @return CursoFormacao
     */
    public function setDescricaoClasse($descricaoClasse)
    {
        $this->descricaoClasse = trim($descricaoClasse);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrauAcademico()
    {
        return $this->grauAcademico;
    }

    /**
     * @param mixed $grauAcademico
     * @return CursoFormacao
     */
    public function setGrauAcademico($grauAcademico)
    {
        $this->grauAcademico = $grauAcademico;
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
     * @return CursoFormacao
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @param array $state
     * @return CursoFormacao
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed94_i_codigo', $state)) {
            $self->setCodigo($state['ed94_i_codigo']);
        }
        if (array_key_exists('ed94_c_descr', $state)) {
            $self->setNome($state['ed94_c_descr']);
        }
        if (array_key_exists('ed94_i_codclasse', $state)) {
            $self->setCodigoClasse($state['ed94_i_codclasse']);
        }
        if (array_key_exists('ed94_c_codigocenso', $state)) {
            $self->setCodigoCenso($state['ed94_c_codigocenso']);
        }
        if (array_key_exists('ed94_c_descrclasse', $state)) {
            $self->setDescricaoClasse($state['ed94_c_descrclasse']);
        }
        if (array_key_exists('ed94_i_grauacademico', $state)) {
            $self->setGrauAcademico($state['ed94_i_grauacademico']);
        }
        if (array_key_exists('ed94_ativo', $state)) {
            $self->setAtivo($state['ed94_ativo'] === 't');
        }

        CursoFormacaoRegistry::set($self);
        return $self;
    }
}
