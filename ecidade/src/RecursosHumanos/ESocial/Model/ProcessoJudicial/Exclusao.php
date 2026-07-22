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
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
namespace ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoProcessualRepository;
use DBDate;
use JSON;

class Exclusao
{
    /**
     * @var string
     */
    private $tipoEvento;

    /**
     * @var string
     */
    private $recibo;

    /**
     * @var string
     */
    private $numeroProcesso;

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $periodoPagamento;

    /**
     * @var int
     */
    private $sequencialProcessoServidor;

    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var date
     */
    private $dataExclusao;

    /**
     * @var string
     */
    private $referencia;


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
     * Get the value of tipoEvento
     *
     * @return  string
     */
    public function getTipoEvento()
    {
        return $this->tipoEvento;
    }

    /**
     * Set the value of tipoEvento
     *
     * @param  string  $tipoEvento
     */
    public function setTipoEvento($tipoEvento)
    {
        $this->tipoEvento = $tipoEvento;
    }

    /**
     * Get the value of recibo
     *
     * @return  string
     */
    public function getRecibo()
    {
        return $this->recibo;
    }

    /**
     * Set the value of recibo
     *
     * @param  string  $recibo
     */
    public function setRecibo($recibo)
    {
        $this->recibo = $recibo;
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
     * @return  self
     */
    public function setNumeroProcesso($numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
    }

    /**
     * Get the value of cpf
     *
     * @return  string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set the value of cpf
     *
     * @param  string  $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * Get the value of periodoPagamento
     *
     * @return  string
     */
    public function getPeriodoPagamento()
    {
        return $this->periodoPagamento;
    }

    /**
     * Set the value of periodoPagamento
     *
     * @param  string  $periodoPagamento
     */
    public function setPeriodoPagamento($periodoPagamento)
    {
        $this->periodoPagamento = $periodoPagamento;
    }

       /**
     * @param array $state
     * @return ExclusaoProcesso
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $exclusaoProcesso = new self();

        if (array_key_exists('rh300_sequencial', $state)) {
            $exclusaoProcesso->setSequencial((int)$state['rh300_sequencial']);
        }

        if (array_key_exists('rh300_sequencialprocessoservidor', $state)) {
            $exclusaoProcesso->setSequencialProcessoServidor((int) $state['rh300_sequencialprocessoservidor']);
        }

        if (array_key_exists('rh300_tpevento', $state)) {
            $exclusaoProcesso->setTipoEvento($state['rh300_tpevento']);
        }

        if (array_key_exists('rh300_nrrecevt', $state)) {
            $exclusaoProcesso->setRecibo($state['rh300_nrrecevt']);
        }

        if (array_key_exists('rh300_nrproctrab', $state)) {
            $exclusaoProcesso->setNumeroProcesso($state['rh300_nrproctrab']);
        }

        if (array_key_exists('rh300_cpftrab', $state)) {
            $exclusaoProcesso->setCpf($state['rh300_cpftrab']);
        }

        if (array_key_exists('rh300_perapurpgto', $state)) {
            $exclusaoProcesso->setPeriodoPagamento($state['rh300_perapurpgto']);
        }

        if (array_key_exists('rh300_dataexclusao', $state)) {
            $exclusaoProcesso->setDataExclusao($state['rh300_dataexclusao']);
        }

        if (array_key_exists('rh300_referencia', $state)) {
            $exclusaoProcesso->setReferencia($state['rh300_referencia']);
        }

        return $exclusaoProcesso;
    }

    /**
     * Get the value of dataExclusao
     *
     * @return  date
     */
    public function getDataExclusao()
    {
        return $this->dataExclusao;
    }

    /**
     * Set the value of dataExclusao
     *
     * @param  date  $dataExclusao
     */
    public function setDataExclusao($dataExclusao)
    {
        $this->dataExclusao = $dataExclusao;
    }

    /**
     * Get the value of referencia
     *
     * @return  string
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set the value of referencia
     *
     * @param  string  $referencia
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    }
}
