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

class Pensao
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $sequencialTributoIRRF;

    /**
     * @var int
     */
    private $tipoRendimento;

    /**
     * @var string
     */
    private $cpfPensao;

    /**
     * @var int
     */
    private $valorPensao;

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
     * Get the value of sequencialTributoIRRF
     *
     * @return  int
     */
    public function getSequencialTributoIRRF()
    {
        return $this->sequencialTributoIRRF;
    }

    /**
     * Set the value of sequencialTributoIRRF
     *
     * @param  int  $sequencialTributoIRRF
     */
    public function setSequencialTributoIRRF($sequencialTributoIRRF)
    {
        $this->sequencialTributoIRRF = $sequencialTributoIRRF;
    }

    /**
     * Get the value of tipoRendimento
     *
     * @return  int
     */
    public function getTipoRendimento()
    {
        return $this->tipoRendimento;
    }

    /**
     * Set the value of tipoRendimento
     *
     * @param  int  $tipoRendimento
     */
    public function setTipoRendimento($tipoRendimento)
    {
        $this->tipoRendimento = $tipoRendimento;
    }

    /**
     * Get the value of cpfPensao
     *
     * @return  string
     */
    public function getCpfPensao()
    {
        return $this->cpfPensao;
    }

    /**
     * Set the value of cpfPensao
     *
     * @param  string  $cpfPensao
     */
    public function setCpfPensao($cpfPensao)
    {
        $this->cpfPensao = $cpfPensao;
    }

    /**
     * Get the value of valorPensao
     *
     * @return  int
     */
    public function getValorPensao()
    {
        return $this->valorPensao;
    }

    /**
     * Set the value of valorPensao
     *
     * @param  int  $valorPensao
     *
     * @return  self
     */
    public function setValorPensao($valorPensao)
    {
        $this->valorPensao = $valorPensao;
    }

    /**
     * @param array $state
     * @return Pensao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $pensao = new self();

        if (array_key_exists('rh305_sequencial', $state)) {
            $pensao->setSequencial((int)$state['rh305_sequencial']);
        }

        if (array_key_exists('rh305_sequencialtributoirrf', $state)) {
            $pensao->setSequencialTributoIRRF((int)$state['rh305_sequencialtributoirrf']);
        }

        if (array_key_exists('rh305_tprend', $state)) {
            $pensao->setTipoRendimento($state['rh305_tprend']);
        }

        if (array_key_exists('rh305_cpfdep', $state)) {
            $pensao->setCpfPensao($state['rh305_cpfdep']);
        }

        if (array_key_exists('rh305_vlrpensao', $state)) {
            $pensao->setValorPensao($state['rh305_vlrpensao']);
        }

        return $pensao;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
