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
use ECidade\Educacao\Escola\Registry\AreaProcedimentoRegistry;
use ECidade\Enum\Educacao\Escola\FormaObtencaoEnum;
use Exception;
use FormaAvaliacao;
use FormaAvaliacaoRepository;
use PeriodoAvaliacao;
use PeriodoAvaliacaoRepository;

/**
 * Class AreaProcedimentoAvaliacao
 * @package ECidade\Educacao\Escola\Model
 */
class AreaProcedimentoAvaliacao
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
     * @var PeriodoAvaliacao
     */
    private $periodoAvaliacao;
    /**
     * @var string
     */
    private $tipo;
    /**
     * Ordem do elemento de avaliação em uma procedimento para calculo da avaliação
     * @var integer
     */
    private $ordemElemento;
    /**
     * @var FormaObtencaoEnum
     */
    private $formaObtencao;
    /**
     * @var integer
     */
    private $peso;
    /**
     * @var integer
     */
    private $ordem;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return AreaProcedimentoAvaliacao
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
     * @return AreaProcedimentoAvaliacao
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
     * @return AreaProcedimentoAvaliacao
     */
    public function setFormaAvaliacao(FormaAvaliacao $formaAvaliacao)
    {
        $this->formaAvaliacao = $formaAvaliacao;
        return $this;
    }

    /**
     * @return PeriodoAvaliacao
     */
    public function getPeriodoAvaliacao()
    {
        return $this->periodoAvaliacao;
    }

    /**
     * @param PeriodoAvaliacao $periodoAvaliacao
     * @return AreaProcedimentoAvaliacao
     */
    public function setPeriodoAvaliacao(PeriodoAvaliacao $periodoAvaliacao)
    {
        $this->periodoAvaliacao = $periodoAvaliacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     * @return AreaProcedimentoAvaliacao
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdemElemento()
    {
        return $this->ordemElemento;
    }

    /**
     * @param int $ordemElemento
     * @return AreaProcedimentoAvaliacao
     */
    public function setOrdemElemento($ordemElemento)
    {
        $this->ordemElemento = $ordemElemento;
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
     * @return AreaProcedimentoAvaliacao
     */
    public function setFormaObtencao(FormaObtencaoEnum $formaObtencao)
    {
        $this->formaObtencao = $formaObtencao;
        return $this;
    }

    /**
     * @return int
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param int $peso
     * @return AreaProcedimentoAvaliacao
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @param int $ordem
     * @return AreaProcedimentoAvaliacao
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * @param array $state
     * @return AreaProcedimentoAvaliacao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed158_codigo', $state)) {
            $self->setCodigo($state['ed158_codigo']);
        }
        if (array_key_exists('ed158_areaprocedimento', $state)) {
            $self->setAreaProcedimento(AreaProcedimentoRegistry::get($state['ed158_areaprocedimento']));
        }
        if (array_key_exists('ed158_formaavaliacao', $state)) {
            $self->setFormaAvaliacao(FormaAvaliacaoRepository::getByCodigo($state['ed158_formaavaliacao']));
        }
        if (array_key_exists('ed158_periodoavaliacao', $state)) {
            $self->setPeriodoAvaliacao(
                PeriodoAvaliacaoRepository::getPeriodoAvaliacaoByCodigo($state['ed158_periodoavaliacao'])
            );
        }
        if (array_key_exists('ed158_tipo', $state)) {
            $self->setTipo($state['ed158_tipo']);
        }
        if (array_key_exists('ed158_ordem_elemento', $state)) {
            $self->setOrdemElemento($state['ed158_ordem_elemento']);
        }
        if (array_key_exists('ed158_formaobtencao', $state)) {
            $self->setFormaObtencao(new FormaObtencaoEnum($state['ed158_formaobtencao']));
        }
        if (array_key_exists('ed158_peso', $state)) {
            $self->setPeso($state['ed158_peso']);
        }
        if (array_key_exists('ed158_ordem', $state)) {
            $self->setOrdem($state['ed158_ordem']);
        }

        AreaProcedimentoAvaliacaoRegistry::set($self);

        return $self;
    }
}
