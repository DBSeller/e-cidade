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

class Remuneracao
{
    /**
     * @var int
     *
     */
    private $sequencial;

    /**
     * @var int
     *
     * Referencia a tabela RHPESSOALPROCESSOCONTRATO
     */
    private $sequencialProcessoContrato;

    /**
     * @var \DBDate
     *
     */
    private $dataRemuneracao;

    /**
     * @var float
     *
     */
    private $valorRemuneracao;

    /**
     * @var int
     *
     */
    private $unidadeSalarioFixo;

    /**
     * @var string
     *
     */
    private $descricaoSalarioVariavel;

    /**
     * @var array
     */
    private $processoContrato;

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
     * Get the value of dataRemuneracao
     *
     * @return  \DBDate
     */
    public function getDataRemuneracao()
    {
        return $this->dataRemuneracao;
    }

    /**
     * Set the value of dataRemuneracao
     *
     * @param  \DBDate  $dataRemuneracao
     */
    public function setDataRemuneracao($dataRemuneracao)
    {
        $this->dataRemuneracao = $dataRemuneracao;
    }

    /**
     * Get the value of valorRemuneracao
     *
     * @return  float
     */
    public function getValorRemuneracao()
    {
        return $this->valorRemuneracao;
    }

    /**
     * Set the value of valorRemuneracao
     *
     * @param  float  $valorRemuneracao
     */
    public function setValorRemuneracao($valorRemuneracao)
    {
        $this->valorRemuneracao = $valorRemuneracao;
    }

    /**
     * Get the value of unidadeSalarioFixo
     *
     * @return  int
     */
    public function getUnidadeSalarioFixo()
    {
        return $this->unidadeSalarioFixo;
    }

    /**
     * Set the value of unidadeSalarioFixo
     *
     * @param  int  $unidadeSalarioFixo
     */
    public function setUnidadeSalarioFixo($unidadeSalarioFixo)
    {
        $this->unidadeSalarioFixo = $unidadeSalarioFixo;
    }

    /**
     * Get the value of descricaoSalarioVariavel
     *
     * @return  string
     */
    public function getDescricaoSalarioVariavel()
    {
        return $this->descricaoSalarioVariavel;
    }

    /**
     * Set the value of descricaoSalarioVariavel
     *
     * @param  string  $descricaoSalarioVariavel
     *
     * @return  self
     */
    public function setDescricaoSalarioVariavel($descricaoSalarioVariavel)
    {
        $this->descricaoSalarioVariavel = $descricaoSalarioVariavel;
    }

        /**
     * @param array $state
     * @return Remuneracao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $remuneracao = new self();

        if (array_key_exists('rh272_sequencial', $state)) {
            $remuneracao->setSequencial((int)$state['rh272_sequencial']);
        }

        if (array_key_exists('rh272_sequencialprocessocontrato', $state)) {
            $remuneracao->setSequencialProcessoContrato($state['rh272_sequencialprocessocontrato']);
        }

        if (array_key_exists('rh272_dtremun', $state)) {
            $remuneracao->setDataRemuneracao($state['rh272_dtremun']);
        }

        if (array_key_exists('rh272_vrsalfx', $state)) {
            $remuneracao->setValorRemuneracao($state['rh272_vrsalfx']);
        }

        if (array_key_exists('rh272_undsalfix', $state)) {
            $remuneracao->setUnidadeSalarioFixo($state['rh272_undsalfix']);
        }

        if (array_key_exists('rh272_dscsalvar', $state)) {
            $remuneracao->setDescricaoSalarioVariavel($state['rh272_dscsalvar']);
        }
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
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
}
