<?php
/**
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
 *  02111-1307, USA.save
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
namespace ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial;

use DBDate;
use JSON;

class TributoBase
{
    /**
     * @var int
     *
     */
    private $sequencial;

    /**
     * @var int
     *
     * Referencia a tabela RHPESSOALPROCESSOSERVIDOR
     */
    private $sequencialProcessoServidor;

    /**
     * @var string
     *
     */
    private $competencia;

    /**
     * @var numeric
     *
     */
    private $valorBaseMensal;

    /**
     * @var numeric
     *
     */
    private $valorBaseMensal13;

    /**
     * @var numeric
     *
     */
    private $valorBaseIRRF;

    /**
     * @var numeric
     *
     */
    private $valorBaseIRRF13;

    /**
     * @var string
     *
     */
    private $pagamento;

    /**
     * @var string
     *
     */
    private $observacao;

    /**
     * @var string
     *
     */
    private $numeroProcesso;

    /**
     * @param array $state
     * @return TributoBase
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $tributoBase = new self();

        if (array_key_exists('rh288_sequencial', $state)) {
            $tributoBase->setSequencial((int)$state['rh288_sequencial']);
        }

        if (array_key_exists('rh288_sequencialprocessoservidor', $state)) {
            $tributoBase->setSequencialProcessoServidor((int)$state['rh288_sequencialprocessoservidor']);
        }

        if (array_key_exists('rh288_peref', $state)) {
            $tributoBase->setCompetencia($state['rh288_peref']);
        }

        if (array_key_exists('rh288_vrbccpmensal', $state)) {
            $tributoBase->setValorBaseMensal($state['rh288_vrbccpmensal']);
        }

        if (array_key_exists('rh288_vrbccp13', $state)) {
            $tributoBase->setValorBaseMensal13($state['rh288_vrbccp13']);
        }

        if (array_key_exists('rh288_vrrendirrf', $state)) {
            $tributoBase->setValorBaseIRRF($state['rh288_vrrendirrf']);
        }

        if (array_key_exists('rh288_vrrendirrf13', $state)) {
            $tributoBase->setValorBaseIRRF13($state['rh288_vrrendirrf13']);
        }

        if (array_key_exists('rh288_pagamento', $state)) {
            $tributoBase->setPagamento($state['rh288_pagamento']);
        }

        if (array_key_exists('rh288_observacao', $state)) {
            $tributoBase->setObservacao($state['rh288_observacao']);
        }

        if (array_key_exists('rh270_nrproctrab', $state)) {
            $tributoBase->setNumeroProcesso($state['rh270_nrproctrab']);
        }

        return $tributoBase;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    /**
     * Get the value of sequencial
     *
     * @return  int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * Set the value of sequencial
     *
     * @param  int  $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * Get the value of sequencialProcessoServidor
     *
     * @return  int
     */
    public function getSequencialProcessoServidor()
    {
        return $this->sequencialProcessoServidor;
    }

    /**
     * Set the value of sequencialProcessoServidor
     *
     * @param  int  $sequencialProcessoServidor
     */
    public function setSequencialProcessoServidor($sequencialProcessoServidor)
    {
        $this->sequencialProcessoServidor = $sequencialProcessoServidor;
    }

    /**
     * Get the value of competencia
     */
    public function getCompetencia()
    {
        return $this->competencia;
    }

    /**
     * Set the value of competencia
     *
     * @return  self
     */
    public function setCompetencia($competencia)
    {
        $this->competencia = $competencia;
    }

    /**
     * Get the value of valorBaseMensal
     *
     * @return  numeric
     */
    public function getValorBaseMensal()
    {
        return $this->valorBaseMensal;
    }

    /**
     * Set the value of valorBaseMensal
     *
     * @param  numeric  $valorBaseMensal
     */
    public function setValorBaseMensal($valorBaseMensal)
    {
        $this->valorBaseMensal = $valorBaseMensal;
    }

    /**
     * Get the value of valorBaseMensal13
     *
     * @return  numeric
     */
    public function getValorBaseMensal13()
    {
        return $this->valorBaseMensal13;
    }

    /**
     * Set the value of valorBaseMensal13
     *
     * @param  numeric  $valorBaseMensal13
     *
     * @return  self
     */
    public function setValorBaseMensal13($valorBaseMensal13)
    {
        $this->valorBaseMensal13 = $valorBaseMensal13;
    }

    /**
     * Get the value of valorBaseIRRF
     *
     * @return  numeric
     */
    public function getValorBaseIRRF()
    {
        return $this->valorBaseIRRF;
    }

    /**
     * Set the value of valorBaseIRRF
     *
     * @param  numeric  $valorBaseIRRF
     *
     * @return  self
     */
    public function setValorBaseIRRF($valorBaseIRRF)
    {
        $this->valorBaseIRRF = $valorBaseIRRF;
    }

    /**
     * Get the value of valorBaseIRRF13
     *
     * @return  numeric
     */
    public function getValorBaseIRRF13()
    {
        return $this->valorBaseIRRF13;
    }

    /**
     * Set the value of valorBaseIRRF13
     *
     * @param  numeric  $valorBaseIRRF13
     *
     * @return  self
     */
    public function setValorBaseIRRF13($valorBaseIRRF13)
    {
        $this->valorBaseIRRF13 = $valorBaseIRRF13;
    }

    /**
     * Get the value of pagamento
     *
     * @return  string
     */
    public function getPagamento()
    {
        return $this->pagamento;
    }

    /**
     * Set the value of pagamento
     *
     * @param  string  $pagamento
     */
    public function setPagamento($pagamento)
    {
        $this->pagamento = $pagamento;
    }

    /**
     * Get the value of observacao
     *
     * @return  string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * Set the value of observacao
     *
     * @param  string  $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    /**
     * Get the value of numeroProcesso
     *
     * @return  string
     */
    public function getNumeroProcesso()
    {
        return $this->numeroProcesso;
    }

    /**
     * Set the value of numeroProcesso
     *
     * @param  string  $numeroProcesso
     */
    public function setNumeroProcesso($numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
    }
}
