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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ServidorRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ProcessoJudicialRepository;
use JSON;
use DBDate;

class Vinculo
{

    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $sequencialServidor;

    /**
     * @var int
     */
    private $regimeTrabalhista;

    /**
     * @var int
     */
    private $regimePrevidenciario;

    /**
     * @var DBDate | null
     */
    private $dataAdmissao;

    /**
     * @var int
     */
    private $tempoParcial;

    /**
     * @var array
     */
    private $processoJudicial;

    /**
     * @var array
     */
    private $servidorProcesso;

        /**
     * @param array $state
     * @return Vinculo
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $vinculo = new self();

        if (array_key_exists('rh274_sequencial', $state)) {
            $vinculo->setSequencial((int)$state['rh274_sequencial']);
        }

        if (array_key_exists('rh274_sequencialprocessoservidor', $state)) {
            $vinculo->setSequencialServidor((int)$state['rh274_sequencialprocessoservidor']);
        }

        if (array_key_exists('rh274_tpregtrab', $state)) {
            $vinculo->setRegimeTrabalhista($state['rh274_tpregtrab']);
        }

        if (array_key_exists('rh274_tpregprev', $state)) {
            $vinculo->setRegimePrevidenciario($state['rh274_tpregprev']);
        }

        if (array_key_exists('rh274_dtadm', $state)) {
            $vinculo->setDataAdmissao($state['rh274_dtadm']);
        }

        if (array_key_exists('rh274_tmpparc', $state)) {
            $vinculo->setTempoParcial($state['rh274_tmpparc']);
        }

        return $vinculo;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    /**
     * Get the value of sequencial
     *
     * @return  number
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * Set the value of sequencial
     *
     * @param  number  $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * Get the value of sequencialServidor
     *
     * @return  number
     */
    public function getSequencialServidor()
    {
        return $this->sequencialServidor;
    }

    /**
     * Set the value of sequencialServidor
     *
     * @param  number  $sequencialServidor
     */
    public function setSequencialServidor($sequencialServidor)
    {
        $processoServidorRepository = new ServidorRepository();
        $processoServidor = $processoServidorRepository
            ->scopeSequencial($sequencialServidor)
            ->get();
        $this->setServidorProcesso($processoServidor);

        $processoServidor[0]->setTipoRegimeTrabalhista($this->getRegimeTrabalhista());
        
        $processoJudicialRepository = new ProcessoJudicialRepository;
        $processoJudicial = $processoJudicialRepository
            ->scopeSequencial($processoServidor[0]->getSequencialProcesso())
            ->get();
        $this->setProcessoJudicial($processoJudicial);

        $this->sequencialServidor = $sequencialServidor;
    }

    /**
     * Get the value of regimeTrabalhista
     *
     * @return  int
     */
    public function getRegimeTrabalhista()
    {
        return $this->regimeTrabalhista;
    }

    /**
     * Set the value of regimeTrabalhista
     *
     * @param  int  $regimeTrabalhista
     */
    public function setRegimeTrabalhista($regimeTrabalhista)
    {
        $this->regimeTrabalhista = $regimeTrabalhista;
    }

    /**
     * Get the value of regimePrevidenciario
     *
     * @return  int
     */
    public function getRegimePrevidenciario()
    {
        return $this->regimePrevidenciario;
    }

    /**
     * Set the value of regimePrevidenciario
     *
     * @param  int  $regimePrevidenciario
     */
    public function setRegimePrevidenciario($regimePrevidenciario)
    {
        $this->regimePrevidenciario = $regimePrevidenciario;
    }

    /**
     * Get the value of dataAdmissao
     *
     * @return  date
     */
    public function getDataAdmissao()
    {
        return $this->dataAdmissao;
    }

    /**
     * Set the value of dataAdmissao
     *
     * @param  date  $dataAdmissao
     */
    public function setDataAdmissao($dataAdmissao)
    {
        $this->dataAdmissao = $dataAdmissao;
    }

    /**
     * Get the value of tempoParcial
     *
     * @return  int
     */
    public function getTempoParcial()
    {
        return $this->tempoParcial;
    }

    /**
     * Set the value of tempoParcial
     *
     * @param  int  $tempoParcial
     */
    public function setTempoParcial($tempoParcial)
    {
        $this->tempoParcial = $tempoParcial;
    }

    /**
     * Get the value of processoJudicial
     *
     * @return  array
     */
    public function getProcessoJudicial()
    {
        return $this->processoJudicial;
    }

    /**
     * Set the value of processoJudicial
     *
     * @param  array  $processoJudicial
     */
    public function setProcessoJudicial($processoJudicial)
    {
        $this->processoJudicial = $processoJudicial;
    }

    /**
     * Get the value of servidorProcesso
     *
     * @return  array
     */
    public function getServidorProcesso()
    {
        return $this->servidorProcesso;
    }

    /**
     * Set the value of servidorProcesso
     *
     * @param  array  $servidorProcesso
     */
    public function setServidorProcesso($servidorProcesso)
    {
        $this->servidorProcesso = $servidorProcesso;
    }
}
