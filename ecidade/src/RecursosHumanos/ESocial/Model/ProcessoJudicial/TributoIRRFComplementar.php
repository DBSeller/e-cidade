<?php
/**
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
 *  02111-1307, USA.save
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
namespace ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial;

use DBDate;
use JSON;

class TributoIRRFComplementar
{
    /**
     * @var int
     *
     */
    private $sequencial;

    /**
     * @var int
     *
     * Referencia a tabela RHPESSOALPROCESSOSERVIDOR
     */
    private $sequencialProcessoServidor;

    /**
     * @var \DBDate | null
     */
    private $dataLaudo;

    /**
     * @var string
     */
    private $cpfDependente;

    /**
     * @var \DBDate | null
     */
    private $dataNascimento;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $IRRFDependenteTributavel;

    /**
     * @var string
     */
    private $tipoDependente;

    /**
     * @var string
     */
    private $descricaoDependencia;


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
     * Get the value of sequencialProcessoServidor
     *
     * @return  int
     */
    public function getSequencialProcessoServidor()
    {
        return $this->sequencialProcessoServidor;
    }

    /**
     * Set the value of sequencialProcessoServidor
     *
     * @param  int  $sequencialProcessoServidor
     */
    public function setSequencialProcessoServidor($sequencialProcessoServidor)
    {
        $this->sequencialProcessoServidor = $sequencialProcessoServidor;
    }

    /**
     * Get | null
     *
     * @return  \DBDate
     */
    public function getDataLaudo()
    {
        return $this->dataLaudo;
    }

    /**
     * Set | null
     *
     * @param  \DBDate  $dataLaudo  | null
     */
    public function setDataLaudo($dataLaudo)
    {
        $this->dataLaudo = $dataLaudo;
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
     * Get | null
     *
     * @return  \DBDate
     */
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * Set | null
     *
     * @param  \DBDate  $dataNascimento  | null
     */
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }

    /**
     * Get the value of nome
     *
     * @return  string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @param  string  $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Get the value of IRRFDependenteTributavel
     *
     * @return  string
     */
    public function getIRRFDependenteTributavel()
    {
        return $this->IRRFDependenteTributavel;
    }

    /**
     * Set the value of IRRFDependenteTributavel
     *
     * @param  string  $IRRFDependenteTributavel
     */
    public function setIRRFDependenteTributavel($IRRFDependenteTributavel)
    {
        $this->IRRFDependenteTributavel = $IRRFDependenteTributavel;
    }

    /**
     * Get the value of tipoDependente
     *
     * @return  string
     */
    public function getTipoDependente()
    {
        return $this->tipoDependente;
    }

    /**
     * Set the value of tipoDependente
     *
     * @param  string  $tipoDependente
     */
    public function setTipoDependente($tipoDependente)
    {
        $this->tipoDependente = $tipoDependente;
    }

    /**
     * Get the value of descricaoDependencia
     *
     * @return  string
     */
    public function getDescricaoDependencia()
    {
        return $this->descricaoDependencia;
    }

    /**
     * Set the value of descricaoDependencia
     *
     * @param  string  $descricaoDependencia
     */
    public function setDescricaoDependencia($descricaoDependencia)
    {
        $this->descricaoDependencia = $descricaoDependencia;
    }

        /**
     * @param array $state
     * @return TributoIRRFComplementar
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $IRRFcomplementar = new self();

        if (array_key_exists('rh310_sequencial', $state)) {
            $IRRFcomplementar->setSequencial((int)$state['rh310_sequencial']);
        }

        if (array_key_exists('rh310_sequencialprocessoservidor', $state)) {
            $IRRFcomplementar->setSequencialProcessoServidor((int)$state['rh310_sequencialprocessoservidor']);
        }

        if (array_key_exists('rh310_dtlaudo', $state)) {
            $IRRFcomplementar->setDataLaudo($state['rh310_dtlaudo']);
        }

        if (array_key_exists('rh310_cpfdep', $state)) {
            $IRRFcomplementar->setCpfDependente($state['rh310_cpfdep']);
        }

        if (array_key_exists('rh310_dtnascto', $state)) {
            $IRRFcomplementar->setDataNascimento($state['rh310_dtnascto']);
        }

        if (array_key_exists('rh310_nome', $state)) {
            $IRRFcomplementar->setNome($state['rh310_nome']);
        }

        if (array_key_exists('rh310_depirrf', $state)) {
            $IRRFcomplementar->setIRRFDependenteTributavel($state['rh310_depirrf']);
        }

        if (array_key_exists('rh310_tpdep', $state)) {
            $IRRFcomplementar->setTipoDependente($state['rh310_tpdep']);
        }

        if (array_key_exists('rh310_descrdep', $state)) {
            $IRRFcomplementar->setDescricaoDependencia($state['rh310_descrdep']);
        }

        return $IRRFcomplementar;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }
}
