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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\VinculoRepository;

use DBDate;
use JSON;

class Duracao
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
     * @var string
     */
    private $tipoContrato;

    /**
     * @var string
     */
    private $dataTerminoContrato;

    /**
     * @var string
     */
    private $clausulaAssecuratoria;

    /**
     * @var string
     */
    private $objetoDeterminante;

    /**
     * @var array
     */
    private $vinculoProcesso;

     /**
     * @var int
     */
    private $tipoRegimeTrabalho;
    
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
     * Get the value of tipoContrato
     *
     * @return  string
     */
    public function getTipoContrato()
    {
        return $this->tipoContrato;
    }

    /**
     * Set the value of tipoContrato
     *
     * @param  string  $tipoContrato
     */
    public function setTipoContrato($tipoContrato)
    {
        $this->tipoContrato = $tipoContrato;
    }

    /**
     * Get the value of dataTerminoContrato
     *
     * @return  string
     */
    public function getDataTerminoContrato()
    {
        return $this->dataTerminoContrato;
    }

    /**
     * Set the value of dataTerminoContrato
     *
     * @param  DBDate  $dataTerminoContrato  | null
     */
    public function setDataTerminoContrato($dataTerminoContrato)
    {
        $this->dataTerminoContrato = $dataTerminoContrato;
    }

    /**
     * Get the value of clausulaAssecuratoria
     *
     * @return  string
     */
    public function getClausulaAssecuratoria()
    {
        return $this->clausulaAssecuratoria;
    }

    /**
     * Set the value of clausulaAssecuratoria
     *
     * @param  string  $clausulaAssecuratoria
     */
    public function setClausulaAssecuratoria($clausulaAssecuratoria)
    {
        $this->clausulaAssecuratoria = $clausulaAssecuratoria;
    }

    /**
     * @param array $state
     * @return Duracao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $duracao = new self();

        if (array_key_exists('rh276_sequencial', $state)) {
            $duracao->setSequencial((int)$state['rh276_sequencial']);
        }

        if (array_key_exists('rh276_sequencialprocessovinculo', $state)) {
            $duracao->setSequencialProcessoVinculo((int)$state['rh276_sequencialprocessovinculo']);
        }

        if (array_key_exists('rh276_tpcontr', $state)) {
            $duracao->setTipoContrato($state['rh276_tpcontr']);
        }

        if (array_key_exists('rh276_dtterm', $state)) {
            $duracao->setDataTerminoContrato($state['rh276_dtterm']);
        }

        if (array_key_exists('rh276_clauassec', $state)) {
            $duracao->setClausulaAssecuratoria($state['rh276_clauassec']);
        }

        if (array_key_exists('rh276_objdet', $state)) {
            $duracao->setDataTerminoContrato($state['rh276_objdet']);
        }

        return $duracao;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    /**
     * Get the value of objetoDeterminante
     *
     * @return  string
     */
    public function getObjetoDeterminante()
    {
        return $this->objetoDeterminante;
    }

    /**
     * Set the value of objetoDeterminante
     *
     * @param  string  $objetoDeterminante
     */
    public function setObjetoDeterminante($objetoDeterminante)
    {
        $this->objetoDeterminante = $objetoDeterminante;
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
     *
     * @return  self
     */
    public function setSequencialProcessoVinculo($sequencialProcessoVinculo)
    {
        $vinculoRepository = new VinculoRepository();
        $vinculo = $vinculoRepository
            ->scopeSequencial($sequencialProcessoVinculo)
            ->get();
        $this->setVinculoProcesso($vinculo);
        $this->sequencialProcessoVinculo = $sequencialProcessoVinculo;
    }

    /**
     * Get the value of vinculoProcesso
     *
     * @return  array
     */
    public function getVinculoProcesso()
    {
        return $this->vinculoProcesso;
    }

    /**
     * Set the value of vinculoProcesso
     *
     * @param  array  $vinculoProcesso
     */
    public function setVinculoProcesso($vinculoProcesso)
    {
         $this->vinculoProcesso = $vinculoProcesso;
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
}
