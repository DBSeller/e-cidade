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

use ECidade\Educacao\Escola\Registry\AreaProcedimentoAvaliacaoRegistry;
use ECidade\Educacao\Escola\Registry\AreaProcedimentoResultadoRegistry;
use Exception;

/**
 * Class AreaProcediementoComposicaoResultado
 * @package ECidade\Educacao\Escola\Model
 */
class AreaProcedimentoComposicaoResultado
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var AreaProcedimentoResultado
     */
    private $areaProcedimentoResultado;
    /**
     * @var AreaProcedimentoAvaliacao[
     */
    private $areaProcedimentoAvaliacao = [];

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return AreaProcedimentoComposicaoResultado
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return AreaProcedimentoResultado
     */
    public function getAreaProcedimentoResultado()
    {
        return $this->areaProcedimentoResultado;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return AreaProcedimentoComposicaoResultado
     */
    public function setAreaProcedimentoResultado($areaProcedimentoResultado)
    {
        $this->areaProcedimentoResultado = $areaProcedimentoResultado;
        return $this;
    }

    /**
     * @return AreaProcedimentoAvaliacao
     */
    public function getAreaProcedimentoAvaliacao()
    {
        return $this->areaProcedimentoAvaliacao;
    }

    /**
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return AreaProcedimentoComposicaoResultado
     */
    public function setAreaProcedimentoAvaliacao($areaProcedimentoAvaliacao)
    {
        $this->areaProcedimentoAvaliacao = $areaProcedimentoAvaliacao;
        return $this;
    }

    /**
     * @param array $state
     * @return AreaProcedimentoComposicaoResultado
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed160_codigo', $state)) {
            $self->setCodigo($state['ed160_codigo']);
        }
        if (array_key_exists('ed160_areaprocedimentoresultado', $state)) {
            $self->setAreaProcedimentoResultado(
                AreaProcedimentoResultadoRegistry::get($state['ed160_areaprocedimentoresultado'])
            );
        }
        if (array_key_exists('ed160_areaprocedimentoavaliacao', $state)) {
            $self->setAreaProcedimentoAvaliacao(
                AreaProcedimentoAvaliacaoRegistry::get($state['ed160_areaprocedimentoavaliacao'])
            );
        }

        return $self;
    }
}
