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

class Dependente
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
    private $cpfDependente;

    /**
     * @var int
     */
    private $valorDeducao;

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
     * Get the value of cpfDependente
     *
     * @return  string
     */
    public function getCpfDependente()
    {
        return $this->cpfDependente;
    }

    /**
     * Set the value of cpfDependente
     *
     * @param  string  $cpfDependente
     */
    public function setCpfDependente($cpfDependente)
    {
        $this->cpfDependente = $cpfDependente;
    }

    /**
     * Get the value of valorDeducao
     *
     * @return  int
     */
    public function getValorDeducao()
    {
        return $this->valorDeducao;
    }

    /**
     * Set the value of valorDeducao
     *
     * @param  int  $valorDeducao
     */
    public function setValorDeducao($valorDeducao)
    {
        $this->valorDeducao = $valorDeducao;
    }

    /**
     * @param array $state
     * @return Dependente
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $dependente = new self();

        if (array_key_exists('rh304_sequencial', $state)) {
            $dependente->setSequencial((int)$state['rh304_sequencial']);
        }

        if (array_key_exists('rh304_sequencialtributoirrf', $state)) {
            $dependente->setSequencialTributoIRRF((int)$state['rh304_sequencialtributoirrf']);
        }

        if (array_key_exists('rh304_tprend', $state)) {
            $dependente->setTipoRendimento($state['rh304_tprend']);
        }

        if (array_key_exists('rh304_cpfdep', $state)) {
            $dependente->setCpfDependente($state['rh304_cpfdep']);
        }

        if (array_key_exists('rh304_vlrdeducao', $state)) {
            $dependente->setValorDeducao($state['rh304_vlrdeducao']);
        }

        return $dependente;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
