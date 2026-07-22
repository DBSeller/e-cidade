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
namespace ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial;

use DBDate;
use JSON;

class Desligamento
{

    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $sequencialProcessoVinculo;

    /**
     * @var DBDate | null
     */
    private $dataDesligamento;

    /**
     * @var string
     */
    private $motivoDesligamento;

    /**
     * @var DBDate | null
     */
    private $dataFimAvisoPrevioIdenizado;

    /**
     * @var int
     */
    private $pensaoAlimenticia;

    /**
     * @var int
     */
    private $percentualPensaoAlimenticia;

    /**
     * @var float
     */
    private $valorPensao;

    /**
     * @var int
     */
    private $tipoRegimeTrabalho;

    /**
     * @var DBDate | null
     */
    private $dataSentencaAcordo;

    /**
     * @var string
     */
    private $matriculaServidor;

    /**
     * @var string
     */
    private $nomeServidor;

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
     * Get the value of sequencialProcessoVinculo
     *
     * @return  int
     */
    public function getSequencialProcessoVinculo()
    {
        return $this->sequencialProcessoVinculo;
    }

    /**
     * Set the value of sequencialProcessoVinculo
     *
     * @param  int  $sequencialProcessoVinculo
     */
    public function setSequencialProcessoVinculo($sequencialProcessoVinculo)
    {
        $this->sequencialProcessoVinculo = $sequencialProcessoVinculo;
    }

    /**
     * Get | null
     *
     * @return  DBDate
     */
    public function getDataDesligamento()
    {
        return $this->dataDesligamento;
    }

    /**
     * Set | null
     *
     * @param  DBDate  $dataDesligamento  | null
     */
    public function setDataDesligamento($dataDesligamento)
    {
        $this->dataDesligamento = $dataDesligamento;
    }

    /**
     * Get the value of motivoDesligamento
     *
     * @return  string
     */
    public function getMotivoDesligamento()
    {
        return $this->motivoDesligamento;
    }

    /**
     * Set the value of motivoDesligamento
     *
     * @param  string  $motivoDesligamento
     */
    public function setMotivoDesligamento($motivoDesligamento)
    {
        $this->motivoDesligamento = $motivoDesligamento;
    }

    /**
     * Get | null
     *
     * @return  DBDate
     */
    public function getDataFimAvisoPrevioIdenizado()
    {
        return $this->dataFimAvisoPrevioIdenizado;
    }

    /**
     * Set | null
     *
     * @param  DBDate  $dataFimAvisoPrevioIdenizado  | null
     */
    public function setDataFimAvisoPrevioIdenizado($dataFimAvisoPrevioIdenizado)
    {
        $this->dataFimAvisoPrevioIdenizado = $dataFimAvisoPrevioIdenizado;
    }

    /**
     * Get the value of pensaoAlimenticia
     *
     * @return  int
     */
    public function getPensaoAlimenticia()
    {
        return $this->pensaoAlimenticia;
    }

    /**
     * Set the value of pensaoAlimenticia
     *
     * @param  int  $pensaoAlimenticia
     */
    public function setPensaoAlimenticia($pensaoAlimenticia)
    {
        $this->pensaoAlimenticia = $pensaoAlimenticia;
    }

    /**
     * Get the value of percentualPensaoAlimenticia
     *
     * @return  int
     */
    public function getPercentualPensaoAlimenticia()
    {
        return $this->percentualPensaoAlimenticia;
    }

    /**
     * Set the value of percentualPensaoAlimenticia
     *
     * @param  int  $percentualPensaoAlimenticia
     */
    public function setPercentualPensaoAlimenticia($percentualPensaoAlimenticia)
    {
        $this->percentualPensaoAlimenticia = $percentualPensaoAlimenticia;
    }

    /**
     * Get the value of valorPensao
     *
     * @return  float
     */
    public function getValorPensao()
    {
        return $this->valorPensao;
    }

    /**
     * Set the value of valorPensao
     *
     * @param  float  $valorPensao
     */
    public function setValorPensao($valorPensao)
    {
        $this->valorPensao = $valorPensao;
    }

    /**
     * @param array $state
     * @return Deligamento
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $desligamento = new self();

        if (array_key_exists('rh279_sequencial', $state)) {
            $desligamento->setSequencial((int)$state['rh279_sequencial']);
        }

        if (array_key_exists('rh279_sequencialprocessovinculo', $state)) {
            $desligamento->setSequencialProcessoVinculo((int)$state['rh279_sequencialprocessovinculo']);
        }

        if (array_key_exists('rh279_dtdeslig', $state)) {
            $desligamento->setDataDesligamento($state['rh279_dtdeslig']);
        }

        if (array_key_exists('rh279_mtvdeslig', $state)) {
            $desligamento->setMotivoDesligamento($state['rh279_mtvdeslig']);
        }

        if (array_key_exists('rh279_dtprojfimapi', $state)) {
            $desligamento->setDataFimAvisoPrevioIdenizado($state['rh279_dtprojfimapi']);
        }

        if (array_key_exists('rh279_pensalim', $state)) {
            $desligamento->setPensaoAlimenticia($state['rh279_pensalim']);
        }

        if (array_key_exists('rh279_percaliment', $state)) {
            $desligamento->setPercentualPensaoAlimenticia($state['rh279_percaliment']);
        }

        if (array_key_exists('rh279_vlralim', $state)) {
            $desligamento->setValorPensao($state['rh279_vlralim']);
        }

        return $desligamento;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    /**
     * Get the value of tipoRegimeTrabalho
     *
     * @return  int
     */
    public function getTipoRegimeTrabalho()
    {
        return $this->tipoRegimeTrabalho;
    }

    /**
     * Set the value of tipoRegimeTrabalho
     *
     * @param  int  $tipoRegimeTrabalho
     */
    public function setTipoRegimeTrabalho($tipoRegimeTrabalho)
    {
        $this->tipoRegimeTrabalho = $tipoRegimeTrabalho;
    }

    /**
     * Get | null
     *
     * @return  DBDate
     */
    public function getDataSentencaAcordo()
    {
        return $this->dataSentencaAcordo;
    }

    /**
     * Set | null
     *
     * @param  DBDate  $dataSentencaAcordo  | null
     */
    public function setDataSentencaAcordo($dataSentencaAcordo)
    {
        $this->dataSentencaAcordo = $dataSentencaAcordo;
    }

    /**
     * Get the value of matriculaServidor
     *
     * @return  string
     */
    public function getMatriculaServidor()
    {
        return $this->matriculaServidor;
    }

    /**
     * Set the value of matriculaServidor
     *
     * @param  string  $matriculaServidor
     */
    public function setMatriculaServidor($matriculaServidor)
    {
        $this->matriculaServidor = $matriculaServidor;
    }

    /**
     * Get the value of nomeServidor
     *
     * @return  string
     */
    public function getNomeServidor()
    {
        return $this->nomeServidor;
    }

    /**
     * Set the value of nomeServidor
     *
     * @param  string  $nomeServidor
     */
    public function setNomeServidor($nomeServidor)
    {
        $this->nomeServidor = $nomeServidor;
    }
}
