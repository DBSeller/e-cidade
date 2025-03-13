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

class Advogado
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
    private $tipoInscricao;

    /**
     * @var string
     */
    private $numeroInscricao;

    /**
     * @var numeric
     */
    private $valorDespesa;

    /**
     * @param array $state
     * @return Advogado
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $advogado = new self();

        if (array_key_exists('rh303_sequencial', $state)) {
            $advogado->setSequencial((int)$state['rh303_sequencial']);
        }

        if (array_key_exists('rh303_sequencialtributoirrf', $state)) {
            $advogado->setSequencialTributoIRRF((int)$state['rh303_sequencialtributoirrf']);
        }

        if (array_key_exists('rh303_tpinsc', $state)) {
            $advogado->setTipoInscricao($state['rh303_tpinsc']);
        }

        if (array_key_exists('rh303_nrinsc', $state)) {
            $advogado->setNumeroInscricao($state['rh303_nrinsc']);
        }

        if (array_key_exists('rh303_vlradv', $state)) {
            $advogado->setValorDespesa($state['rh303_vlradv']);
        }

        return $advogado;
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
     * Get the value of numeroInscricao
     *
     * @return  string
     */
    public function getNumeroInscricao()
    {
        return $this->numeroInscricao;
    }

    /**
     * Set the value of numeroInscricao
     *
     * @param  string  $numeroInscricao
     */
    public function setNumeroInscricao($numeroInscricao)
    {
        $this->numeroInscricao = $numeroInscricao;
    }

    /**
     * Get the value of valorDespesa
     *
     * @return  numeric
     */
    public function getValorDespesa()
    {
        return $this->valorDespesa;
    }

    /**
     * Set the value of valorDespesa
     *
     * @param  numeric  $valorDespesa
     */
    public function setValorDespesa($valorDespesa)
    {
        $this->valorDespesa = $valorDespesa;
    }
}
