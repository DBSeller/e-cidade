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

use ECidade\RecursosHumanos\Pessoal\Model\AgenteNocivo;
use ECidade\RecursosHumanos\Pessoal\Model\LocalTrabalhoRegistroAmbiental as RegistroAmbiental;

class LocalTrabalho
{
    /**
     * @var int
     */
    private $codigo;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var string
     */
    private $estrutural;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var Integer
     */
    private $tipoInscricao;

    /**
     * @var string
     */
    private $numeroInscricao;

    /**
     * @var bool
     */
    private $principal = false;

    /**
     * @var DBDate|null
     */
    private $dataInicio = null;

    /**
     * @var DBDate|null
     */
    private $dataFim = null;

    /**
     * @var array \AgenteNocivo
     */
    private $agentesNocivos = [];

    /**
     * @var array \RegistroAmbiental
     */
    private $registrosAmbientais = [];

    /**
     * @var string
     */
    private $observacao;

    /**
     * @var string
     */
    private $lotacaoTributaria;

    /**
     * @return DBDate
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @param DBDate $dataInicio
     */
    public function setDataInicio(DBDate $dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    /**
     * @return DBDate
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    /**
     * @param DBDate $dataFim
     */
    public function setDataFim(DBDate $dataFim)
    {
        $this->dataFim = $dataFim;
    }


    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return string
     */
    public function getEstrutural()
    {
        return $this->estrutural;
    }

    /**
     * @param string $estrutural
     */
    public function setEstrutural($estrutural)
    {
        $this->estrutural = $estrutural;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getTipoInscricao() {
        return $this->tipoInscricao;
    }

    public function setTipoInscricao($tipoInscricao) {
        $this->tipoInscricao = $tipoInscricao;
    }

    public function getNumeroInscricao() {
        return $this->numeroInscricao;
    }

    public function setNumeroInscricao($numeroInscricao) {
        $this->numeroInscricao = $numeroInscricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return bool
     */
    public function isPrincipal()
    {
        if ($this->principal == 't') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param bool $principal
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;
    }

    public function getAgentesNocivos()
    {
        if (sizeof($this->agentesNocivos) == 0) {
            $this->agentesNocivos = AgenteNocivo::getAgentesByLocalTrabalho($this->codigo);
        }
        return $this->agentesNocivos;
    }

    /**
     * @return array
     */
    public function getRegistrosAmbientais()
    {
        if (sizeof($this->registrosAmbientais) == 0) {
            $this->registrosAmbientais = RegistroAmbiental::getRegistrosByLocalTrabalho($this->codigo);
        }
        return $this->registrosAmbientais;
    }

    /**
     * @param array $registrosAmbientais
     */
    public function setRegistrosAmbientais($registrosAmbientais)
    {
        $this->registrosAmbientais = $registrosAmbientais;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    /**
     * Get the value of lotacaoTributaria
     *
     * @return  string
     */ 
    public function getLotacaoTributaria()
    {
        return $this->lotacaoTributaria;
    }

    /**
     * Set the value of lotacaoTributaria
     *
     * @param  string  $lotacaoTributaria
     *
     * @return  self
     */ 
    public function setLotacaoTributaria($lotacaoTributaria)
    {
        $this->lotacaoTributaria = $lotacaoTributaria;
    }
}
