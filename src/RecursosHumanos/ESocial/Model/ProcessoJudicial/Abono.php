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

class Abono
{
    /**
     * @var string
     */
    private $anoAbono;

    /**
     * @var int
     */
    private $sequencialProcessoContrato;

    /**
     * @var int
     */
    private $sequencial;

    /**
     * Get the value of anoAbono
     *
     * @return  string
     */
    public function getAnoAbono()
    {
        return $this->anoAbono;
    }

    /**
     * Set the value of anoAbono
     *
     * @param  string  $anoAbono
     */
    public function setAnoAbono($anoAbono)
    {
        $this->anoAbono = $anoAbono;
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
     */
    public function setSequencialProcessoContrato($sequencialProcessoContrato)
    {
        $this->sequencialProcessoContrato = $sequencialProcessoContrato;
    }

    /**
     * @param array $state
     * @return Mudanca
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $anoAbono = new self();

        if (array_key_exists('rh302_sequencial', $state)) {
            $anoAbono->setSequencial((int)$state['rh302_sequencial']);
        }

        if (array_key_exists('rh302_sequencialprocessocontrato', $state)) {
            $anoAbono->setSequencialProcessoContrato((int)$state['rh302_sequencialprocessocontrato']);
        }

        if (array_key_exists('rh302_anobase', $state)) {
            $anoAbono->setAnoAbono($state['rh302_anobase']);
        }
        return $anoAbono;
    }


    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
