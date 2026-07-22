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

class Periodo
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $sequencialProcessoContrato;

    /**
     * @var string
     */
    private $periodo;

    /**
     * @var float
     */
    private $valorBasePrevidenciaMensal;

    /**
     * @var float
     */
    private $valorBasePrevidenciaMensal13;

    /**
     * @var int
     */
    private $grauExposicao;

    /**
     * @var float
     */
    private $valorBaseFGTSProcesso;

    /**
     * @var float
     */
    private $valorBaseFGTSSefip;

    /**
     * @var float
     */
    private $valorBaseFGTSDeclaradaAnteriormente;

    /**
     * @var array
     */
    private $processoContrato;

    /**
     * @var int
     */
    private $codigoCategoria;

    /**
     * @var float
     */
    private $valorFinsPrevidenciarios;

    /**
     * @param array $state
     * @return Periodo
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $periodo = new self();

        if (array_key_exists('rh282_sequencial', $state)) {
            $periodo->setSequencial((int)$state['rh282_sequencial']);
        }

        if (array_key_exists('rh282_sequencialprocessocontrato', $state)) {
            $periodo->setSequencialProcessoContrato((int)$state['rh282_sequencialprocessocontrato']);
        }

        if (array_key_exists('rh282_perref', $state)) {
            $periodo->setPeriodo($state['rh282_perref']);
        }

        if (array_key_exists('rh282_vrbccpmensal', $state)) {
            $periodo->setValorBasePrevidenciaMensal($state['rh282_vrbccpmensal']);
        }

        if (array_key_exists('rh282_vrbccp13', $state)) {
            $periodo->setValorBasePrevidenciaMensal13($state['rh282_vrbccp13']);
        }

        if (array_key_exists('rh282_grauexp', $state)) {
            $periodo->setGrauExposicao($state['rh282_grauexp']);
        }

        if (array_key_exists('rh282_codcateg', $state)) {
            $periodo->setCodigoCategoria($state['rh282_codcateg']);
        }

        if (array_key_exists('rh282_vrbccprev', $state)) {
            $periodo->setValorFinsPrevidenciarios($state['rh282_vrbccprev']);
        }

        if (array_key_exists('rh282_vrbcfgtsproctrab', $state)) {
            $periodo->setValorBaseFGTSProcesso($state['rh282_vrbcfgtsproctrab']);
        }

        if (array_key_exists('rh282_vrbcfgtssefip', $state)) {
            $periodo->setValorBaseFGTSSefip($state['rh282_vrbcfgtssefip']);
        }

        if (array_key_exists('rh282_vrbcfgtsdecant', $state)) {
            $periodo->setValorBaseFGTSDeclaradaAnteriormente($state['rh282_vrbcfgtsdecant']);
        }

        return $periodo;
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
        $contratoRepository = new ContratoRepository();
        $contrato = $contratoRepository
            ->scopeSequencial($sequencialProcessoContrato)
            ->get();
        
        $this->setProcessoContrato($contrato);

        $this->sequencialProcessoContrato = $sequencialProcessoContrato;
    }

    /**
     * Get the value of periodo
     *
     * @return  string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set the value of periodo
     *
     * @param  string  $periodo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    }

    /**
     * Get the value of valorBasePrevidenciaMensal
     *
     * @return  float
     */
    public function getValorBasePrevidenciaMensal()
    {
        return $this->valorBasePrevidenciaMensal;
    }

    /**
     * Set the value of valorBasePrevidenciaMensal
     *
     * @param  float  $valorBasePrevidenciaMensal
     */
    public function setValorBasePrevidenciaMensal($valorBasePrevidenciaMensal)
    {
        $this->valorBasePrevidenciaMensal = $valorBasePrevidenciaMensal;
    }

    /**
     * Get the value of valorBasePrevidenciaMensal13
     *
     * @return  float
     */
    public function getValorBasePrevidenciaMensal13()
    {
        return $this->valorBasePrevidenciaMensal13;
    }

    /**
     * Set the value of valorBasePrevidenciaMensal13
     *
     * @param  float  $valorBasePrevidenciaMensal13
     */
    public function setValorBasePrevidenciaMensal13($valorBasePrevidenciaMensal13)
    {
        $this->valorBasePrevidenciaMensal13 = $valorBasePrevidenciaMensal13;
    }

    /**
     * Get the value of grauExposicao
     *
     * @return  int
     */
    public function getGrauExposicao()
    {
        return $this->grauExposicao;
    }

    /**
     * Set the value of grauExposicao
     *
     * @param  int  $grauExposicao
     */
    public function setGrauExposicao($grauExposicao)
    {
        $this->grauExposicao = $grauExposicao;
    }

    /**
     * Get the value of valorBaseFGTSProcesso
     *
     * @return  float
     */
    public function getValorBaseFGTSProcesso()
    {
        return $this->valorBaseFGTSProcesso;
    }

    /**
     * Set the value of valorBaseFGTSProcesso
     *
     * @param  float  $valorBaseFGTSProcesso
     */
    public function setValorBaseFGTSProcesso($valorBaseFGTSProcesso)
    {
        $this->valorBaseFGTSProcesso = $valorBaseFGTSProcesso;
    }

    /**
     * Get the value of valorBaseFGTSSefip
     *
     * @return  float
     */
    public function getValorBaseFGTSSefip()
    {
        return $this->valorBaseFGTSSefip;
    }

    /**
     * Set the value of valorBaseFGTSSefip
     *
     * @param  float  $valorBaseFGTSSefip
     */
    public function setValorBaseFGTSSefip($valorBaseFGTSSefip)
    {
        $this->valorBaseFGTSSefip = $valorBaseFGTSSefip;
    }

    /**
     * Get the value of valorBaseFGTSDeclaradaAnteriormente
     *
     * @return  float
     */
    public function getValorBaseFGTSDeclaradaAnteriormente()
    {
        return $this->valorBaseFGTSDeclaradaAnteriormente;
    }

    /**
     * Set the value of valorBaseFGTSDeclaradaAnteriormente
     *
     * @param  float  $valorBaseFGTSDeclaradaAnteriormente
     */
    public function setValorBaseFGTSDeclaradaAnteriormente($valorBaseFGTSDeclaradaAnteriormente)
    {
        $this->valorBaseFGTSDeclaradaAnteriormente = $valorBaseFGTSDeclaradaAnteriormente;
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
     */
    public function setCodigoCategoria($codigoCategoria)
    {
        $this->codigoCategoria = $codigoCategoria;
    }

    /**
     * Get the value of valorFinsPrevidenciarios
     *
     * @return  float
     */
    public function getValorFinsPrevidenciarios()
    {
        return $this->valorFinsPrevidenciarios;
    }

    /**
     * Set the value of valorFinsPrevidenciarios
     *
     * @param  float  $valorFinsPrevidenciarios
     */
    public function setValorFinsPrevidenciarios($valorFinsPrevidenciarios)
    {
        $this->valorFinsPrevidenciarios = $valorFinsPrevidenciarios;
    }
}
