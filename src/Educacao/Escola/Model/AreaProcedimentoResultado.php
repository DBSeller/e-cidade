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

use ECidade\Educacao\Escola\Registry\AreaProcedimentoRegistry;
use ECidade\Educacao\Escola\Registry\AreaProcedimentoResultadoRegistry;
use ECidade\Enum\Educacao\Escola\FormaObtencaoEnum;
use Exception;
use FormaAvaliacao;
use FormaAvaliacaoRepository;
use TipoResultado;

/**
 * Class AreaProcedimentoResultado
 * @package ECidade\Educacao\Escola\Model
 */
class AreaProcedimentoResultado
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var AreaProcedimento
     */
    private $areaProcedimento;
    /**
     * @var FormaAvaliacao
     */
    private $formaAvaliacao;
    /**
     * @var TipoResultado
     */
    private $tipoResultado;
    /**
     * @var FormaObtencaoEnum
     */
    private $formaObtencao;
    /**
     * @var array
     */
    private $composicao;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return AreaProcedimentoResultado
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return AreaProcedimento
     */
    public function getAreaProcedimento()
    {
        return $this->areaProcedimento;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return AreaProcedimentoResultado
     */
    public function setAreaProcedimento(AreaProcedimento $areaProcedimento)
    {
        $this->areaProcedimento = $areaProcedimento;
        return $this;
    }

    /**
     * @return FormaAvaliacao
     */
    public function getFormaAvaliacao()
    {
        return $this->formaAvaliacao;
    }

    /**
     * @param FormaAvaliacao $formaAvaliacao
     * @return AreaProcedimentoResultado
     */
    public function setFormaAvaliacao(FormaAvaliacao $formaAvaliacao)
    {
        $this->formaAvaliacao = $formaAvaliacao;
        return $this;
    }

    /**
     * @return TipoResultado
     */
    public function getTipoResultado()
    {
        return $this->tipoResultado;
    }

    /**
     * @param TipoResultado $tipoResultado
     * @return AreaProcedimentoResultado
     */
    public function setTipoResultado(TipoResultado $tipoResultado)
    {
        $this->tipoResultado = $tipoResultado;
        return $this;
    }

    /**
     * @return FormaObtencaoEnum
     */
    public function getFormaObtencao()
    {
        return $this->formaObtencao;
    }

    /**
     * @param FormaObtencaoEnum $formaObtencao
     * @return AreaProcedimentoResultado
     */
    public function setFormaObtencao(FormaObtencaoEnum $formaObtencao)
    {
        $this->formaObtencao = $formaObtencao;
        return $this;
    }

    /**
     * @return AreaProcedimentoComposicaoResultado[]
     */
    public function getComposicao()
    {
        return $this->composicao;
    }

    /**
     * @param AreaProcedimentoComposicaoResultado[] $composicao
     * @return AreaProcedimentoResultado
     */
    public function setComposicao(array $composicao)
    {
        $this->composicao = $composicao;
        return $this;
    }

    /**
     * @param array $state
     * @return AreaProcedimentoResultado
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed159_codigo', $state)) {
            $self->setCodigo($state['ed159_codigo']);
        }
        if (array_key_exists('ed159_areaprocedimento', $state)) {
            $self->setAreaProcedimento(AreaProcedimentoRegistry::get($state['ed159_areaprocedimento']));
        }
        if (array_key_exists('ed159_formaavaliacao', $state)) {
            $self->setFormaAvaliacao(FormaAvaliacaoRepository::getByCodigo($state['ed159_formaavaliacao']));
        }
        if (array_key_exists('ed159_resultado', $state)) {
            $self->setTipoResultado(new TipoResultado($state['ed159_resultado']));
        }
        if (array_key_exists('ed159_formaobtencao', $state)) {
            $self->setFormaObtencao(new FormaObtencaoEnum($state['ed159_formaobtencao']));
        }

        AreaProcedimentoResultadoRegistry::set($self);

        return $self;
    }
}
