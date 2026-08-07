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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;
use DBDate;
use JSON;

class Mudanca
{
    /**
     * @var int
     */
    private $codigoCategoria;

    /**
     * @var int
     */
    private $naturezaAtividade;

    /**
     * @var \DBDate | null
     */
    private $dataMudancaCategoria;

    /**
     * @var int
     */
    private $sequencialProcessoContrato;

    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var array
     */
    private $processoContrato;

    /**
     * @param array $state
     * @return Mudanca
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $mudanca = new self();

        if (array_key_exists('rh280_sequencial', $state)) {
            $mudanca->setSequencial((int)$state['rh280_sequencial']);
        }

        if (array_key_exists('rh280_sequencialprocessocontrato', $state)) {
            $mudanca->setSequencialProcessoContrato((int)$state['rh280_sequencialprocessocontrato']);
        }

        if (array_key_exists('rh280_codcateg', $state)) {
            $mudanca->setCodigoCategoria($state['rh280_codcateg']);
        }

        if (array_key_exists('rh280_natividade', $state)) {
            $mudanca->setNaturezaAtividade($state['rh280_natividade']);
        }

        if (array_key_exists('rh280_dtmudcategativ', $state)) {
            $mudanca->setDataMudancaCategoria($state['rh280_dtmudcategativ']);
        }

        return $mudanca;
    }


    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    /**
     * Get the value of codigoCategoria
     *
     * @return  int
     */
    public function getCodigoCategoria()
    {
        return $this->codigoCategoria;
    }

    /**
     * Set the value of codigoCategoria
     *
     * @param  int  $codigoCategoria
     *
     */
    public function setCodigoCategoria($codigoCategoria)
    {
        $this->codigoCategoria = $codigoCategoria;
    }

    /**
     * Get the value of naturezaAtividade
     *
     * @return  int
     */
    public function getNaturezaAtividade()
    {
        return $this->naturezaAtividade;
    }

    /**
     * Set the value of naturezaAtividade
     *
     * @param  int  $naturezaAtividade
     *
     */
    public function setNaturezaAtividade($naturezaAtividade)
    {
        $this->naturezaAtividade = $naturezaAtividade;
    }

    /**
     * Get | null
     *
     * @return  \DBDate
     */
    public function getDataMudancaCategoria()
    {
        return $this->dataMudancaCategoria;
    }

    /**
     * Set | null
     *
     * @param  \DBDate  $dataMudancaCategoria  | null
     *
     */
    public function setDataMudancaCategoria($dataMudancaCategoria)
    {
        $this->dataMudancaCategoria = $dataMudancaCategoria;
    }

    /**
     * Get the value of sequencialProcessoContrato
     *
     * @return  int
     */
    public function getSequencialProcessoContrato()
    {
        return $this->sequencialProcessoContrato;
    }

    /**
     * Set the value of sequencialProcessoContrato
     *
     * @param  int  $sequencialProcessoContrato
     *
     */
    public function setSequencialProcessoContrato($sequencialProcessoContrato)
    {
        $contratoRepository = new ContratoRepository();
        $contrato = $contratoRepository
            ->scopeSequencial($sequencialProcessoContrato)
            ->get();

        $this->setProcessoContrato($contrato);
        
        $this->sequencialProcessoContrato = $sequencialProcessoContrato;
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
     *
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * Get the value of processoContrato
     *
     * @return  array
     */
    public function getProcessoContrato()
    {
        return $this->processoContrato;
    }

    /**
     * Set the value of processoContrato
     *
     * @param  array  $processoContrato
     */
    public function setProcessoContrato($processoContrato)
    {
        $this->processoContrato = $processoContrato;
    }
}
