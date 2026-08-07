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

class Estatutario
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
     * @var int
     */
    private $tipoInscricao;

    /**
     * @var string
     */
    private $inscricao;

    /**
     * @var string
     */
    private $matriculaAnterior;

    /**
     * @var DBDate | null
     */
    private $dataTransferencia;

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
     * Get the value of tipoInscricao
     *
     * @return  int
     */
    public function getTipoInscricao()
    {
        return $this->tipoInscricao;
    }

    /**
     * Set the value of tipoInscricao
     *
     * @param  int  $tipoInscricao
     */
    public function setTipoInscricao($tipoInscricao)
    {
        $this->tipoInscricao = $tipoInscricao;
    }

    /**
     * Get the value of inscricao
     *
     * @return  string
     */
    public function getInscricao()
    {
        return $this->inscricao;
    }

    /**
     * Set the value of inscricao
     *
     * @param  string  $inscricao
     */
    public function setInscricao($inscricao)
    {
        $this->inscricao = $inscricao;
    }

    /**
     * Get the value of matriculaAnterior
     *
     * @return  string
     */
    public function getMatriculaAnterior()
    {
        return $this->matriculaAnterior;
    }

    /**
     * Set the value of matriculaAnterior
     *
     * @param  string  $matriculaAnterior
     */
    public function setMatriculaAnterior($matriculaAnterior)
    {
        $this->matriculaAnterior = $matriculaAnterior;
    }

    /**
     * Get | null
     *
     * @return  DBDate
     */
    public function getDataTransferencia()
    {
        return $this->dataTransferencia;
    }

    /**
     * Set | null
     *
     * @param  DBDate  $dataTransferencia  | null
     */
    public function setDataTransferencia(DBDate $dataTransferencia)
    {
        $this->dataTransferencia = $dataTransferencia;
    }

    /**
     * @param array $state
     * @return Estatutario
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $estatutario = new self();

        if (array_key_exists('rh278_sequencial', $state)) {
            $estatutario->setSequencial((int)$state['rh278_sequencial']);
        }

        if (array_key_exists('rh278_sequencialprocessovinculo', $state)) {
            $estatutario->setSequencialProcessoVinculo((int)$state['rh278_sequencialprocessovinculo']);
        }

        if (array_key_exists('rh278_tplnsc', $state)) {
            $estatutario->setTipoInscricao($state['rh278_tplnsc']);
        }

        if (array_key_exists('rh278_nrlnsc', $state)) {
            $estatutario->setInscricao($state['rh278_nrlnsc']);
        }

        if (array_key_exists('rh278_matricant', $state)) {
            $estatutario->setMatriculaAnterior($state['rh278_matricant']);
        }

        if (array_key_exists('rh278_dttransf', $state)) {
            $estatutario->setDataTransferencia($state['rh278_dttransf']);
        }

        return $estatutario;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
