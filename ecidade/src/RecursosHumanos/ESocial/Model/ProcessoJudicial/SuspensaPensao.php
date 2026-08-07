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

class SuspensaPensao
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $sequencialDeducaoSuspensa;

    /**
     * @var string
     */
    private $cpfDependente;

    /**
     * @var numeric
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
     * Get the value of sequencialDeducaoSuspensa
     *
     * @return  int
     */
    public function getSequencialDeducaoSuspensa()
    {
        return $this->sequencialDeducaoSuspensa;
    }

    /**
     * Set the value of sequencialDeducaoSuspensa
     *
     * @param  int  $sequencialDeducaoSuspensa
     */
    public function setSequencialDeducaoSuspensa($sequencialDeducaoSuspensa)
    {
        $this->sequencialDeducaoSuspensa = $sequencialDeducaoSuspensa;
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
     * @return  numeric
     */
    public function getValorDeducao()
    {
        return $this->valorDeducao;
    }

    /**
     * Set the value of valorDeducao
     *
     * @param  numeric  $valorDeducao
     */
    public function setValorDeducao($valorDeducao)
    {
        $this->valorDeducao = $valorDeducao;
    }

    /**
     * @param array $state
     * @return Mudanca
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $suspensaPensao = new self();

        if (array_key_exists('rh309_sequencial', $state)) {
            $suspensaPensao->setSequencial((int)$state['rh309_sequencial']);
        }

        if (array_key_exists('rh309_sequencialreducaosuspensa', $state)) {
            $suspensaPensao->setSequencialDeducaoSuspensa((int)$state['rh309_sequencialreducaosuspensa']);
        }

        if (array_key_exists('rh309_cpfdep', $state)) {
            $suspensaPensao->setCpfDependente($state['rh309_cpfdep']);
        }

        if (array_key_exists('rh309_vlrdepensusp', $state)) {
            $suspensaPensao->setValorDeducao($state['rh309_vlrdepensusp']);
        }

        return $suspensaPensao;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
