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

namespace ECidade\Financeiro\Orcamento\Model;

/**
 * Class FonteReceita
 * @package ECidade\Financeiro\Orcamento\Model
 */
class FonteRecurso
{
    private $id;
    private $orctiporecId;
    private $exercicio;
    private $codigoSiconfi;
    private $gestao;
    private $classificacaofrId;
    private $tipoDetalhamento;
    private $descricao;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return FonteRecurso
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrctiporecId()
    {
        return $this->orctiporecId;
    }

    /**
     * @param mixed $orctiporecId
     * @return FonteRecurso
     */
    public function setOrctiporecId($orctiporecId)
    {
        $this->orctiporecId = $orctiporecId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExercicio()
    {
        return $this->exercicio;
    }

    /**
     * @param mixed $exercicio
     * @return FonteRecurso
     */
    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoSiconfi()
    {
        return $this->codigoSiconfi;
    }

    /**
     * @param mixed $codigoSiconfi
     * @return FonteRecurso
     */
    public function setCodigoSiconfi($codigoSiconfi)
    {
        $this->codigoSiconfi = $codigoSiconfi;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGestao()
    {
        return $this->gestao;
    }

    /**
     * @param mixed $gestao
     * @return FonteRecurso
     */
    public function setGestao($gestao)
    {
        $this->gestao = $gestao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClassificacaofrId()
    {
        return $this->classificacaofrId;
    }

    /**
     * @param mixed $classificacaofrId
     * @return FonteRecurso
     */
    public function setClassificacaofrId($classificacaofrId)
    {
        $this->classificacaofrId = $classificacaofrId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoDetalhamento()
    {
        return $this->tipoDetalhamento;
    }

    /**
     * @param mixed $tipoDetalhamento
     * @return FonteRecurso
     */
    public function setTipoDetalhamento($tipoDetalhamento)
    {
        $this->tipoDetalhamento = $tipoDetalhamento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     * @return FonteRecurso
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('id', $state)) {
            $self->setId($state['id']);
        }
        if (array_key_exists('orctiporec_id', $state)) {
            $self->setOrctiporecId($state['orctiporec_id']);
        }
        if (array_key_exists('exercicio', $state)) {
            $self->setExercicio($state['exercicio']);
        }
        if (array_key_exists('codigo_siconfi', $state)) {
            $self->setCodigoSiconfi($state['codigo_siconfi']);
        }
        if (array_key_exists('gestao', $state)) {
            $self->setGestao($state['gestao']);
        }
        if (array_key_exists('classificacaofr_id', $state)) {
            $self->setClassificacaofrId($state['classificacaofr_id']);
        }
        if (array_key_exists('tipo_detalhamento', $state)) {
            $self->setTipoDetalhamento($state['tipo_detalhamento']);
        }
        if (array_key_exists('descricao', $state)) {
            $self->setDescricao($state['descricao']);
        }

        return $self;
    }
}
