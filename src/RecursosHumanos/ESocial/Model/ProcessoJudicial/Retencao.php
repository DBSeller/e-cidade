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

class Retencao
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
    private $tipoProcesso;

    /**
     * @var string
     */
    private $numeroProcesso;

    /**
     * @var int
     */
    private $codigoIndicativoSuspensao;

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
     * Get the value of tipoProcesso
     *
     * @return  int
     */
    public function getTipoProcesso()
    {
        return $this->tipoProcesso;
    }

    /**
     * Set the value of tipoProcesso
     *
     * @param  int  $tipoProcesso
     */
    public function setTipoProcesso($tipoProcesso)
    {
        $this->tipoProcesso = $tipoProcesso;
    }

    /**
     * Get the value of codigoIndicativoSuspensao
     *
     * @return  int
     */
    public function getCodigoIndicativoSuspensao()
    {
        return $this->codigoIndicativoSuspensao;
    }

    /**
     * Set the value of codigoIndicativoSuspensao
     *
     * @param  int  $codigoIndicativoSuspensao
     */
    public function setCodigoIndicativoSuspensao($codigoIndicativoSuspensao)
    {
        $this->codigoIndicativoSuspensao = $codigoIndicativoSuspensao;
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
     * @param array $state
     * @return Retencao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $retencao = new self();

        if (array_key_exists('rh306_sequencial', $state)) {
            $retencao->setSequencial((int)$state['rh306_sequencial']);
        }

        if (array_key_exists('rh306_sequencialtributoirrf', $state)) {
            $retencao->setSequencialTributoIRRF((int)$state['rh306_sequencialtributoirrf']);
        }

        if (array_key_exists('rh306_tpprocret', $state)) {
            $retencao->setTipoProcesso($state['rh306_tpprocret']);
        }

        if (array_key_exists('rh306_nrprocret', $state)) {
            $retencao->setNumeroProcesso($state['rh306_nrprocret']);
        }

        if (array_key_exists('rh306_codsusp', $state)) {
            $retencao->setCodigoIndicativoSuspensao($state['rh306_codsusp']);
        }

        return $retencao;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
