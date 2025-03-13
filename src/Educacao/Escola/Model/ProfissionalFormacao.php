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
use ECidade\Educacao\Escola\Registry\InstituicaoEnsinoRegistry;

/**
 * Class ProfissionalFormacao
 * @package ECidade\Educacao\Escola\Model
 */
class ProfissionalFormacao
{
    private $codigo;
    private $codigoRecursoHumano;

    private $situacao;
    /**
     * @var boolean
     */
    private $licenciatura;
    /**
     * @var boolean
     */
    private $formacaoPedagogica;
    private $anoConclusao;
    private $anoInicio;

    /**
     * @var InstituicaoEnsino
     */
    private $censoInstsuperior;
    /**
     * @var CursoFormacao
     */
    private $cursoFormacao;

    /**
     * @var CensoDisciplina
     */
    private $formacaoComplementar;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     * @return ProfissionalFormacao
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoRecursoHumano()
    {
        return $this->codigoRecursoHumano;
    }

    /**
     * @param mixed $codigoRecursoHumano
     * @return ProfissionalFormacao
     */
    public function setCodigoRecursoHumano($codigoRecursoHumano)
    {
        $this->codigoRecursoHumano = $codigoRecursoHumano;
        return $this;
    }

    /**
     * @return CursoFormacao
     */
    public function getCursoFormacao()
    {
        return $this->cursoFormacao;
    }

    /**
     * @param CursoFormacao $cursoFormacao
     * @return ProfissionalFormacao
     */
    public function setCursoFormacao($cursoFormacao)
    {
        $this->cursoFormacao = $cursoFormacao;
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
     * @return ProfissionalFormacao
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getLicenciatura()
    {
        return $this->licenciatura;
    }

    /**
     * @param boolean $licenciatura
     * @return ProfissionalFormacao
     */
    public function setLicenciatura($licenciatura)
    {
        $this->licenciatura = $licenciatura;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnoConclusao()
    {
        return $this->anoConclusao;
    }

    /**
     * @param mixed $anoConclusao
     * @return ProfissionalFormacao
     */
    public function setAnoConclusao($anoConclusao)
    {
        $this->anoConclusao = $anoConclusao;
        return $this;
    }

    /**
     * @return InstituicaoEnsino
     */
    public function getCensoInstsuperior()
    {
        return $this->censoInstsuperior;
    }

    /**
     * @param InstituicaoEnsino $censoInstsuperior
     * @return ProfissionalFormacao
     */
    public function setCensoInstsuperior($censoInstsuperior)
    {
        $this->censoInstsuperior = $censoInstsuperior;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormacaoPedagogica()
    {
        return $this->formacaoPedagogica;
    }

    /**
     * @param mixed $formacaoPedagogica
     * @return ProfissionalFormacao
     */
    public function setFormacaoPedagogica($formacaoPedagogica)
    {
        $this->formacaoPedagogica = $formacaoPedagogica;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnoInicio()
    {
        return $this->anoInicio;
    }

    /**
     * @param mixed $anoInicio
     * @return ProfissionalFormacao
     */
    public function setAnoInicio($anoInicio)
    {
        $this->anoInicio = $anoInicio;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed27_i_codigo', $state)) {
            $self->setCodigo($state['ed27_i_codigo']);
        }
        if (array_key_exists('ed27_i_rechumano', $state)) {
            $self->setCodigoRecursoHumano($state['ed27_i_rechumano']);
        }
        if (array_key_exists('ed27_i_cursoformacao', $state)) {
            $self->setCursoFormacao(CursoFormacaoRegistry::get($state['ed27_i_cursoformacao']));
        }
        if (array_key_exists('ed27_c_situacao', $state)) {
            $self->setSituacao(trim($state['ed27_c_situacao']));
        }
        if (array_key_exists('ed27_i_licenciatura', $state)) {
            $self->setLicenciatura($state['ed27_i_licenciatura'] == 1);
        }
        if (array_key_exists('ed27_i_censoinstsuperior', $state)) {
            $self->setCensoInstsuperior(InstituicaoEnsinoRegistry::get($state['ed27_i_censoinstsuperior']));
        }
        if (array_key_exists('ed27_i_formacaopedag', $state)) {
            $self->setFormacaoPedagogica($state['ed27_i_formacaopedag'] == 1);
        }
        if (array_key_exists('ed27_i_anoinicio', $state)) {
            $self->setAnoInicio($state['ed27_i_anoinicio']);
        }
        if (array_key_exists('ed27_i_anoconclusao', $state)) {
            $self->setAnoConclusao($state['ed27_i_anoconclusao']);
        }

        return $self;
    }

    /**
     * @param CensoDisciplina $censoDisciplina
     */
    public function addFormacaoComplementar(CensoDisciplina $censoDisciplina)
    {
        $this->formacaoComplementar[] = $censoDisciplina;
    }

    /**
     * @return CensoDisciplina
     */
    public function getFormacaoComplementar()
    {
        return $this->formacaoComplementar;
    }
}
