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

namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use ECidade\Configuracao\RelatorioLegal\Registry\PeriodoRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use Exception;

class RelatorioPeriodo
{
    /**
     * @var integer
     */
    private $sequencial;
    /**
     * @var Periodo
     */
    private $periodo;
    /**
     * @var Relatorio
     */
    private $relatorio;

    /**
     * @param array $state
     * @return RelatorioPeriodo
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o113_sequencial', $state)) {
            $self->setSequencial($state['o113_sequencial']);
        }

        if (array_key_exists('o113_periodo', $state)) {
            $self->setPeriodo(PeriodoRegistry::get($state['o113_periodo']));
        }

        if (array_key_exists('o113_orcparamrel', $state)) {
            $self->setRelatorio(RelatorioRegistry::get($state['o113_orcparamrel']));
        }

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'relatorio' => $this->getRelatorio() instanceof Relatorio ? $this->getRelatorio()->getSequencial() : null,
            'periodo' => $this->getPeriodo() instanceof Periodo ? $this->getPeriodo()->toArray() : null,
        );
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return RelatorioPeriodo
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
        return $this;
    }

    /**
     * @return Relatorio
     */
    public function getRelatorio()
    {
        return $this->relatorio;
    }

    /**
     * @param Relatorio $relatorio
     * @return RelatorioPeriodo
     */
    public function setRelatorio(Relatorio $relatorio)
    {
        $this->relatorio = $relatorio;
        return $this;
    }

    /**
     * @return Periodo
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @param Periodo $periodo
     * @return RelatorioPeriodo
     */
    public function setPeriodo(Periodo $periodo)
    {
        $this->periodo = $periodo;
        return $this;
    }
}
