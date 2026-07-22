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
use ProcedimentoAvaliacao;
use ProcedimentoAvaliacaoRepository;

/**
 * Class AreaProcedimento
 * @package ECidade\Educacao\Escola\Model
 */
class AreaProcedimento
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var ProcedimentoAvaliacao
     */
    private $procedimento;
    /**
     * @var AreaProcedimentoAvaliacao[]
     */
    private $avaliacoes = [];
    /**
     * @var AreaProcedimentoResultado
     */
    private $resultado;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return AreaProcedimento
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return ProcedimentoAvaliacao
     */
    public function getProcedimento()
    {
        return $this->procedimento;
    }

    /**
     * @param ProcedimentoAvaliacao $procedimento
     * @return AreaProcedimento
     */
    public function setProcedimento(ProcedimentoAvaliacao $procedimento)
    {
        $this->procedimento = $procedimento;
        return $this;
    }

    /**
     * @return AreaProcedimentoAvaliacao[]
     */
    public function getAvaliacoes()
    {
        return $this->avaliacoes;
    }

    /**
     * @param AreaProcedimentoAvaliacao[] $avaliacoes
     * @return AreaProcedimento
     */
    public function setAvaliacoes($avaliacoes)
    {
        $this->avaliacoes = $avaliacoes;
        return $this;
    }

    /**
     * @return AreaProcedimentoResultado
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * @param AreaProcedimentoResultado $resultado
     * @return AreaProcedimento
     */
    public function setResultado(AreaProcedimentoResultado $resultado)
    {
        $this->resultado = $resultado;
        return $this;
    }

    /**
     * @param array $state
     * @return AreaProcedimento
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed157_codigo', $state)) {
            $self->setCodigo($state['ed157_codigo']);
        }
        if (array_key_exists('ed157_procedimento', $state)) {
            $self->setProcedimento(
                ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo($state['ed157_procedimento'])
            );
        }

        AreaProcedimentoRegistry::set($self);
        return $self;
    }

    /**
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     */
    public function addAvaliacao(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        foreach ($this->avaliacoes as $key => $avaliacao) {
            if ($avaliacao->getCodigo() == $areaProcedimentoAvaliacao->getCodigo()) {
                $this->avaliacoes[$key] = $areaProcedimentoAvaliacao;
                return;
            }
        }

        $this->avaliacoes[] = $areaProcedimentoAvaliacao;
    }

    /**
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     */
    public function removerAvaliacao(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        foreach ($this->avaliacoes as $key => $avaliacao) {
            if ($avaliacao->getCodigo() == $areaProcedimentoAvaliacao->getCodigo()) {
                unset($this->avaliacoes[$key]);
                return;
            }
        }
    }
}
