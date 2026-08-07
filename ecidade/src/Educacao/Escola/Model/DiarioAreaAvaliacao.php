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
use ECidade\Educacao\Escola\Registry\DiarioAreaRegistry;
use Exception;

/**
 * Class DiarioAreaAvaliacao
 * @package ECidade\Educacao\Escola\Model
 */
class DiarioAreaAvaliacao implements AvaliacaoAreaConhecimento
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var DiarioArea
     */
    private $diarioArea;
    /**
     * @var AreaProcedimentoAvaliacao
     */
    private $areaProcedimentoAvaliacao;
    /**
     * @var float
     */
    private $nota;
    /**
     * @var string
     */
    private $parecer;
    /**
     * @var string
     */
    private $conceito;
    /**
     * @var boolean
     */
    private $amparado = false;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return DiarioAreaAvaliacao
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return DiarioArea
     */
    public function getDiarioArea()
    {
        return $this->diarioArea;
    }

    /**
     * @param DiarioArea $diarioArea
     * @return DiarioAreaAvaliacao
     */
    public function setDiarioArea(DiarioArea $diarioArea)
    {
        $this->diarioArea = $diarioArea;
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
     * @return DiarioAreaAvaliacao
     */
    public function setAreaProcedimentoAvaliacao($areaProcedimentoAvaliacao)
    {
        $this->areaProcedimentoAvaliacao = $areaProcedimentoAvaliacao;
        return $this;
    }

    /**
     * @return float
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * @param float $nota
     * @return DiarioAreaAvaliacao
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
        return $this;
    }

    /**
     * @return string
     */
    public function getParecer()
    {
        return $this->parecer;
    }

    /**
     * @param string $parecer
     * @return DiarioAreaAvaliacao
     */
    public function setParecer($parecer)
    {
        $this->parecer = $parecer;
        return $this;
    }

    /**
     * @return string
     */
    public function getConceito()
    {
        return $this->conceito;
    }

    /**
     * @param string $conceito
     * @return DiarioAreaAvaliacao
     */
    public function setConceito($conceito)
    {
        $this->conceito = $conceito;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAmparado()
    {
        return $this->amparado;
    }

    /**
     * @param bool $amparado
     * @return DiarioAreaAvaliacao
     */
    public function setAmparado($amparado)
    {
        $this->amparado = $amparado;
        return $this;
    }

    /**
     * @param array $state
     * @return DiarioAreaAvaliacao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed163_codigo', $state)) {
            $self->setCodigo($state['ed163_codigo']);
        }
        if (array_key_exists('ed163_diarioarea', $state)) {
            $self->setDiarioArea(DiarioAreaRegistry::get($state['ed163_diarioarea']));
        }
        if (array_key_exists('ed163_areaprocedimentoavaliacao', $state)) {
            $self->setAreaProcedimentoAvaliacao(
                AreaProcedimentoAvaliacaoRegistry::get($state['ed163_areaprocedimentoavaliacao'])
            );
        }
        if (array_key_exists('ed163_nota', $state)) {
            $self->setNota($state['ed163_nota']);
        }
        if (array_key_exists('ed163_parecer', $state)) {
            $self->setParecer($state['ed163_parecer']);
        }
        if (array_key_exists('ed163_conceito', $state)) {
            $self->setConceito($state['ed163_conceito']);
        }
        if (array_key_exists('ed163_amparado', $state)) {
            $self->setAmparado($state['ed163_amparado'] === 't');
        }

        return $self;
    }

    /**
     * @return AreaProcedimentoAvaliacao
     */
    public function getElementoAvaliacao()
    {
        return $this->getAreaProcedimentoAvaliacao();
    }
}
