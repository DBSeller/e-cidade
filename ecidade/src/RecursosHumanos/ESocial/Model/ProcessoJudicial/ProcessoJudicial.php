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

class ProcessoJudicial
{
    /**
     * @var int
     *
     */
    private $sequencial;

    /**
     * @var int
     *
     */
    private $origem;

    /**
     * @var string
     *
     */
    private $numeroProcesso;

    /**
     * @var string
     *
     */
    private $observacaoProcesso;

    /**
     * @var  \DBDate  $dataSentenca  | null
     */
    private $dataSentenca;

    /**
     * @var string
     *
     */
    private $ufVara;

    /**
     * @var int
     *
     */
    private $codigoMunicipio;

    /**
     * @var int
     *
     */
    private $identificacaoVara;

    /**
     * @var  \DBDate  $dataSentenca  | null
     */
    private $dataCelebracaoAcordo;

    /**
     * @var string
     *
     */
    private $cnpjSindicato;

    /**
     * @var string
     *
     */
    private $ambitoCelebracaoAcordo;

    /**
     * @var string
     *
     */
    private $matricula;

    /**
     * @var string
     *
     */
    private $cpfServidor;

    /**
     * @var string
     *
     */
    private $nomeServidor;

    /**
     * @var  \DBDate  $dataNascimento  | null
     */
    private $dataNascimento;

    /**
     * @var array
     */
    private $informacaoContratoTrabalho;

    /**
     * @param array $state
     * @return ProcessoJudicial
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $processo = new self();

        if (array_key_exists('rh270_sequencial', $state)) {
            $processo->setSequencial((int)$state['rh270_sequencial']);
        }

        if (array_key_exists('rh270_origem', $state)) {
            $processo->setOrigem($state['rh270_origem']);
        }

        if (array_key_exists('rh270_nrproctrab', $state)) {
            $processo->setNumeroProcesso($state['rh270_nrproctrab']);
        }

        if (array_key_exists('rh270_obsproctrab', $state)) {
            $processo->setObservacaoProcesso($state['rh270_obsproctrab']);
        }

        if (array_key_exists('rh270_dtsent', $state)) {
            $processo->setDataSentenca($state['rh270_dtsent']);
        }

        if (array_key_exists('rh270_ufvara', $state)) {
            $processo->setUfVara($state['rh270_ufvara']);
        }

        if (array_key_exists('rh270_codmunic', $state)) {
            $processo->setCodigoMunicipio($state['rh270_codmunic']);
        }

        if (array_key_exists('rh270_idvara', $state)) {
            $processo->setIdentificacaoVara($state['rh270_idvara']);
        }

        if (array_key_exists('rh270_dtccp', $state)) {
            $processo->setDataCelebracaoAcordo($state['rh270_dtccp']);
        }

        if (array_key_exists('rh270_tpccp', $state)) {
            $processo->setAmbitoCelebracaoAcordo($state['rh270_tpccp']);
        }

        if (array_key_exists('rh270_cnpjccp', $state)) {
            $processo->setCnpjSindicato($state['rh270_cnpjccp']);
        }

        return $processo;
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
     * Get the value of origem
     *
     * @return  int
     */
    public function getOrigem()
    {
        return $this->origem;
    }

    /**
     * Set the value of origem
     *
     * @param  int  $origem
     */
    public function setOrigem($origem)
    {
        $this->origem = $origem;
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
     * Set the value of numeroprocesso
     *
     * @param  string  $numeroProcesso
     *
     * @return  self
     */
    public function setNumeroProcesso($numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
    }

    /**
     * Get the value of observacaoProcesso
     *
     * @return  string
     */
    public function getObservacaoProcesso()
    {
        return $this->observacaoProcesso;
    }

    /**
     * Set the value of observacaoProcesso
     *
     * @param  string  $observacaoProcesso
     */
    public function setObservacaoProcesso($observacaoProcesso)
    {
        $this->observacaoProcesso = $observacaoProcesso;
    }

    /**
     * Get $dataSentenca | null
     *
     * @return  \DBDate
     */
    public function getDataSentenca()
    {
        return $this->dataSentenca;
    }

    /**
     * Set $dataSentenca | null
     *
     * @param  \DBDate  $dataSentenca | null
     */
    public function setDataSentenca($dataSentenca)
    {
        $this->dataSentenca = $dataSentenca;
    }

    /**
     * Get the value of ufVara
     *
     * @return  string
     */
    public function getUfVara()
    {
        return $this->ufVara;
    }

    /**
     * Set the value of ufVara
     *
     * @param  string  $ufVara
     */
    public function setUfVara($ufVara)
    {
        $this->ufVara = $ufVara;
    }

    /**
     * Get the value of codigoMunicipio
     *
     * @return  int
     */
    public function getCodigoMunicipio()
    {
        return $this->codigoMunicipio;
    }

    /**
     * Set the value of codigoMunicipio
     *
     * @param  int  $codigoMunicipio
     */
    public function setCodigoMunicipio($codigoMunicipio)
    {
        $this->codigoMunicipio = $codigoMunicipio;
    }

    /**
     * Get the value of identificacaoVara
     *
     * @return  int
     */
    public function getIdentificacaoVara()
    {
        return $this->identificacaoVara;
    }

    /**
     * Set the value of identificacaoVara
     *
     * @param  int  $identificacaoVara
     */
    public function setIdentificacaoVara($identificacaoVara)
    {
        $this->identificacaoVara = $identificacaoVara;
    }

    /**
     * Get $dataSentenca | null
     *
     * @return  \DBDate
     */
    public function getDataCelebracaoAcordo()
    {
        return $this->dataCelebracaoAcordo;
    }

    /**
     * Set $dataSentenca | null
     *
     * @param  \DBDate  $dataCelebracaoAcordo  $dataSentenca | null
     */
    public function setDataCelebracaoAcordo($dataCelebracaoAcordo)
    {
        $this->dataCelebracaoAcordo = $dataCelebracaoAcordo;
    }

    /**
     * Get the value of cnpjSindicato
     *
     * @return  string
     */
    public function getCnpjSindicato()
    {
        return $this->cnpjSindicato;
    }

    /**
     * Set the value of cnpjSindicato
     *
     * @param  string  $cnpjSindicato
     */
    public function setCnpjSindicato($cnpjSindicato)
    {
        $this->cnpjSindicato = $cnpjSindicato;
    }

    /**
     * Get the value of ambitoCelebracaoAcordo
     *
     * @return  string
     */
    public function getAmbitoCelebracaoAcordo()
    {
        return $this->ambitoCelebracaoAcordo;
    }

    /**
     * Set the value of ambitoCelebracaoAcordo
     *
     * @param  string  $ambitoCelebracaoAcordo
     */
    public function setAmbitoCelebracaoAcordo($ambitoCelebracaoAcordo)
    {
        $this->ambitoCelebracaoAcordo = $ambitoCelebracaoAcordo;
    }

    /**
     * Get the value of matricula
     *
     * @return  string
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set the value of matricula
     *
     * @param  string  $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * Get the value of informacaoContratoTrabalho
     *
     * @return  array
     */
    public function getInformacaoContratoTrabalho()
    {
        return $this->informacaoContratoTrabalho;
    }

    /**
     * Set the value of informacaoContratoTrabalho
     *
     * @param  array  $informacaoContratoTrabalho
     */
    public function setInformacaoContratoTrabalho($informacaoContratoTrabalho)
    {
        $this->informacaoContratoTrabalho = $informacaoContratoTrabalho;
    }

    /**
     * Get the value of cpfServidor
     *
     * @return  string
     */
    public function getCpfServidor()
    {
        return $this->cpfServidor;
    }

    /**
     * Set the value of cpfServidor
     *
     * @param  string  $cpfServidor
     */
    public function setCpfServidor($cpfServidor)
    {
        $this->cpfServidor = $cpfServidor;
    }

    /**
     * Get the value of nomeServidor
     *
     * @return  string
     */
    public function getNomeServidor()
    {
        return $this->nomeServidor;
    }

    /**
     * Set the value of nomeServidor
     *
     * @param  string  $nomeServidor
     */
    public function setNomeServidor($nomeServidor)
    {
        $this->nomeServidor = $nomeServidor;
    }

    /**
     * Get $dataNascimento | null
     *
     * @return  \DBDate
     */
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * Set $dataNascimento | null
     *
     * @param  \DBDate  $dataNascimento  $dataNascimento | null
     */
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }
}
