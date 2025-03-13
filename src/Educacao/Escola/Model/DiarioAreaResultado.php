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

use ECidade\Educacao\Escola\Registry\AreaProcedimentoResultadoRegistry;
use ECidade\Educacao\Escola\Registry\DiarioAreaRegistry;
use Exception;

/**
 * Class DiarioAreaResultado
 * @package ECidade\Educacao\Escola\Model
 */
class DiarioAreaResultado implements AvaliacaoAreaConhecimento
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
     * @var AreaProcedimentoResultado
     */
    private $areaProcedimentoResultado;
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
     * @var string
     */
    private $resultado_avaliacao = 'R';
    /**
     * @var string
     */
    private $resultado_frequencia = 'R';

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return DiarioAreaResultado
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
     * @return DiarioAreaResultado
     */
    public function setDiarioArea(DiarioArea $diarioArea)
    {
        $this->diarioArea = $diarioArea;
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
     * @return DiarioAreaResultado
     */
    public function setAreaProcedimentoResultado($areaProcedimentoResultado)
    {
        $this->areaProcedimentoResultado = $areaProcedimentoResultado;
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
     * @return DiarioAreaResultado
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
     * @return DiarioAreaResultado
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
     * @return DiarioAreaResultado
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
     * @return DiarioAreaResultado
     */
    public function setAmparado($amparado)
    {
        $this->amparado = $amparado;
        return $this;
    }

    /**
     * @return string
     */
    public function getResultadoAvaliacao()
    {
        return $this->resultado_avaliacao;
    }

    /**
     * @param string $resultado_avaliacao
     * @return DiarioAreaResultado
     */
    public function setResultadoAvaliacao($resultado_avaliacao)
    {
        $this->resultado_avaliacao = $resultado_avaliacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getResultadoFrequencia()
    {
        return $this->resultado_frequencia;
    }

    /**
     * @param string $resultado_frequencia
     * @return DiarioAreaResultado
     */
    public function setResultadoFrequencia($resultado_frequencia)
    {
        $this->resultado_frequencia = $resultado_frequencia;
        return $this;
    }

    /**
     * @param array $state
     * @return DiarioAreaResultado
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed164_codigo', $state)) {
            $self->setCodigo($state['ed164_codigo']);
        }
        if (array_key_exists('ed164_diarioarea', $state)) {
            $self->setDiarioArea(DiarioAreaRegistry::get($state['ed164_diarioarea']));
        }
        if (array_key_exists('ed164_areaprocedimentoresultado', $state)) {
            $self->setAreaProcedimentoResultado(
                AreaProcedimentoResultadoRegistry::get($state['ed164_areaprocedimentoresultado'])
            );
        }
        if (array_key_exists('ed164_nota', $state)) {
            $self->setNota($state['ed164_nota']);
        }
        if (array_key_exists('ed164_parecer', $state)) {
            $self->setParecer($state['ed164_parecer']);
        }
        if (array_key_exists('ed164_conceito', $state)) {
            $self->setConceito($state['ed164_conceito']);
        }
        if (array_key_exists('ed164_amparado', $state)) {
            $self->setAmparado($state['ed164_amparado'] === 't');
        }
        if (array_key_exists('ed164_resultado_avaliacao', $state)) {
            $self->setResultadoAvaliacao($state['ed164_resultado_avaliacao']);
        }
        if (array_key_exists('ed164_resultado_frequencia', $state)) {
            $self->setResultadoFrequencia($state['ed164_resultado_frequencia']);
        }

        return $self;
    }

    /**
     * @return AreaProcedimentoResultado
     */
    public function getElementoAvaliacao()
    {
        return $this->getAreaProcedimentoResultado();
    }
}
