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

class ValorRetencao
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $sequencialRetencao;

    /**
     * @var int
     */
    private $indicativoApuracao;

    /**
     * @var numeric
     */
    private $valorRetencao;

    /**
     * @var numeric
     */
    private $valorDepositoJudicial;

    /**
     * @var numeric
     */
    private $valorCompensacaoAno;

    /**
     * @var numeric
     */
    private $valorCompensacaoAnoAnterior;

    /**
     * @var numeric
     */
    private $valorRendimentoSuspenso;


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
     * Get the value of sequencialRetencao
     *
     * @return  int
     */
    public function getSequencialRetencao()
    {
        return $this->sequencialRetencao;
    }

    /**
     * Set the value of sequencialRetencao
     *
     * @param  int  $sequencialRetencao
     */
    public function setSequencialRetencao($sequencialRetencao)
    {
        $this->sequencialRetencao = $sequencialRetencao;
    }

    /**
     * Get the value of indicativoApuracao
     *
     * @return  int
     */
    public function getIndicativoApuracao()
    {
        return $this->indicativoApuracao;
    }

    /**
     * Set the value of indicativoApuracao
     *
     * @param  int  $indicativoApuracao
     */
    public function setIndicativoApuracao($indicativoApuracao)
    {
        $this->indicativoApuracao = $indicativoApuracao;
    }

    /**
     * Get the value of valorRetencao
     *
     * @return  numeric
     */
    public function getValorRetencao()
    {
        return $this->valorRetencao;
    }

    /**
     * Set the value of valorRetencao
     *
     * @param  numeric  $valorRetencao
     */
    public function setValorRetencao($valorRetencao)
    {
        $this->valorRetencao = $valorRetencao;
    }

    /**
     * Get the value of valorDepositoJudicial
     *
     * @return  numeric
     */
    public function getValorDepositoJudicial()
    {
        return $this->valorDepositoJudicial;
    }

    /**
     * Set the value of valorDepositoJudicial
     *
     * @param  numeric  $valorDepositoJudicial
     */
    public function setValorDepositoJudicial($valorDepositoJudicial)
    {
        $this->valorDepositoJudicial = $valorDepositoJudicial;
    }

    /**
     * Get the value of valorCompensacaoAno
     *
     * @return  numeric
     */
    public function getValorCompensacaoAno()
    {
        return $this->valorCompensacaoAno;
    }

    /**
     * Set the value of valorCompensacaoAno
     *
     * @param  numeric  $valorCompensacaoAno
     */
    public function setValorCompensacaoAno($valorCompensacaoAno)
    {
        $this->valorCompensacaoAno = $valorCompensacaoAno;
    }

    /**
     * Get the value of valorCompensacaoAnoAnterior
     *
     * @return  numeric
     */
    public function getValorCompensacaoAnoAnterior()
    {
        return $this->valorCompensacaoAnoAnterior;
    }

    /**
     * Set the value of valorCompensacaoAnoAnterior
     *
     * @param  numeric  $valorCompensacaoAnoAnterior
     */
    public function setValorCompensacaoAnoAnterior($valorCompensacaoAnoAnterior)
    {
        $this->valorCompensacaoAnoAnterior = $valorCompensacaoAnoAnterior;
    }

    /**
     * Get the value of valorRendimentoSuspenso
     *
     * @return  numeric
     */
    public function getValorRendimentoSuspenso()
    {
        return $this->valorRendimentoSuspenso;
    }

    /**
     * Set the value of valorRendimentoSuspenso
     *
     * @param  numeric  $valorRendimentoSuspenso
     */
    public function setValorRendimentoSuspenso($valorRendimentoSuspenso)
    {
        $this->valorRendimentoSuspenso = $valorRendimentoSuspenso;
    }


    /**
     * @param array $state
     * @return ValorRetencao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $valorRetencao = new self();

        if (array_key_exists('rh307_sequencial', $state)) {
            $valorRetencao->setSequencial((int)$state['rh307_sequencial']);
        }

        if (array_key_exists('rh307_sequencialretencao', $state)) {
            $valorRetencao->setSequencialRetencao((int)$state['rh307_sequencialretencao']);
        }

        if (array_key_exists('rh307_indapuracao', $state)) {
            $valorRetencao->setIndicativoApuracao($state['rh307_indapuracao']);
        }

        if (array_key_exists('rh307_vlrnretido', $state)) {
            $valorRetencao->setValorRetencao($state['rh307_vlrnretido']);
        }


        if (array_key_exists('rh307_vlrdepjud', $state)) {
            $valorRetencao->setValorDepositoJudicial($state['rh307_vlrdepjud']);
        }


        if (array_key_exists('rh307_vlrcmpanocal', $state)) {
            $valorRetencao->setValorCompensacaoAno($state['rh307_vlrcmpanocal']);
        }


        if (array_key_exists('rh307_vlrcmpanoant', $state)) {
            $valorRetencao->setValorCompensacaoAnoAnterior($state['rh307_vlrcmpanoant']);
        }


        if (array_key_exists('rh307_vlrrendsusp', $state)) {
            $valorRetencao->setValorRendimentoSuspenso($state['rh307_vlrrendsusp']);
        }

        return $valorRetencao;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
