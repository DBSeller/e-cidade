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

use JSON;

class Observacao
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
    private $observacao;


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
     * @return  self
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
     * @param array $state
     * @return Observacao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $observacao = new self();

        if (array_key_exists('rh277_sequencial', $state)) {
            $observacao->setSequencial((int)$state['rh277_sequencial']);
        }

        if (array_key_exists('rh277_sequencialprocessovinculo', $state)) {
            $observacao->setSequencialProcessoVinculo((int)$state['rh277_sequencialprocessovinculo']);
        }

        if (array_key_exists('rh277_observacao', $state)) {
            $observacao->setObservacao($state['rh277_observacao']);
        }
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
