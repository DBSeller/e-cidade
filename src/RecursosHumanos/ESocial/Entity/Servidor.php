<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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

namespace ECidade\RecursosHumanos\ESocial\Entity;

/**
 * Class Servidor
 * @package ECidade\RecursosHumanos\ESocial\Entity
 */
class Servidor
{
    const DOCUMENTOS_CTPS = 'CTPS';

    /**
     * @var array
     */
    private $dadosTrabalhador;

    /**
     * @var array
     */
    private $documentos;

    /**
     * @var array
     */
    private $endereco;

    /**
     * @var array
     */
    private $vinculoTrabalho;

    /**
     * @var array
     */
    private $fgts;

    /**
     * @var array
     */
    private $celetista;

    /**
     * @var array
     */
    private $contratoTrabalho;

    /**
     * @var array
     */
    private $horaContratual;

    /**
     * @var array
     */
    private $remuneracao;

    /**
     * @var array
     */
    private $filiacaoSindical = null;

    /**
     * @var array
     */
    private $desligamento;

    /**
     * @var array
     */
    private $duracaoContrato;

    /**
     * @var array
     */
    private $localTrabalhoContrato;

    /**
     * @var array
     */
    private $observacaoContratoTrabalho;

    /**
     * @var array
     */
    private $dependentes;

    /**
     * @var array
     */
    private $imigrante;

    /**
     * @var array
     */
    private $contato;

    /**
     * @var array
     */
    private $estatutario;

    /**
     * @var array
     */
    private $deficiente;

    /**
     * @var array
     */
    private $mudancacpf;

    /**
     * @var array
     */
    private $afastamento;

    /**
     * @var array
     */
    private $cessao;

    /**
     * @var array
     */
    private $estagiario;
 
    /**
     * @var array
     */
    private $cedencia;

    /**
     * @var array
     */
    private $cargoFuncaoSemVinculo;
    
    /**
     * @var array
     */
    private $alteracaoContratualSemVinculo;

    /**
     * @return array
     */
    public function getDadosTrabalhador()
    {
        return $this->dadosTrabalhador;
    }

    /**
     * @param array $dadosTrabalhador
     */
    public function setDadosTrabalhador($dadosTrabalhador)
    {
        $this->dadosTrabalhador = $dadosTrabalhador;
    }

    /**
     * @return array
     */
    public function getDocumentos()
    {
        return $this->documentos;
    }

    /**
     * @param array $documentos
     * @param string $chave
     */
    public function setDocumentos($documentos, $chave)
    {
        if (!is_array($this->documentos)) {
            $this->documentos = array();
        }

        $this->documentos[$chave] = $documentos;
    }

    /**
     * @return array
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @param array $endereco
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    /**
     * @return array
     */
    public function getVinculoTrabalho()
    {
        return $this->vinculoTrabalho;
    }

    /**
     * @param array $vinculoTrabalho
     */
    public function setVinculoTrabalho($vinculoTrabalho)
    {
        $this->vinculoTrabalho = $vinculoTrabalho;
    }

    /**
     * @return array
     */
    public function getFgts()
    {
        return $this->fgts;
    }

    /**
     * @param array $fgts
     */
    public function setFgts($fgts)
    {
        $this->fgts = $fgts;
    }

    /**
     * @return array
     */
    public function getCeletista()
    {
        return $this->celetista;
    }

    /**
     * @param array $celetista
     */
    public function setCeletista($celetista)
    {
        $this->celetista = $celetista;
    }

    /**
     * @return array
     */
    public function getContratoTrabalho()
    {
        return $this->contratoTrabalho;
    }

    /**
     * @param array $contratoTrabalho
     */
    public function setContratoTrabalho($contratoTrabalho)
    {
        $this->contratoTrabalho = $contratoTrabalho;
    }

    /**
     * @return array
     */
    public function getHoraContratual()
    {
        return $this->horaContratual;
    }

    /**
     * @param array $horaContratual
     */
    public function setHoraContratual($horaContratual)
    {
        $this->horaContratual = $horaContratual;
    }

    /**
     * @return array
     */
    public function getRemuneracao()
    {
        return $this->remuneracao;
    }

    /**
     * @param array $remuneracao
     */
    public function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;
    }

    /**
     * @return array
     */
    public function getFiliacaoSindical()
    {
        return $this->filiacaoSindical;
    }

    /**
     * @param array $filiacaoSindical
     */
    public function setFiliacaoSindical($filiacaoSindical)
    {
        $this->filiacaoSindical = $filiacaoSindical;
    }

    /**
     * @return array
     */
    public function getDesligamento()
    {
        return $this->desligamento;
    }

    /**
     * @param array $desligamento
     */
    public function setDesligamento($desligamento)
    {
        $this->desligamento = $desligamento;
    }

    /**
     * @return array
     */
    public function getDuracaoContrato()
    {
        return $this->duracaoContrato;
    }

    /**
     * @param array $duracaoContrato
     */
    public function setDuracaoContrato($duracaoContrato)
    {
        $this->duracaoContrato = $duracaoContrato;
    }

    /**
     * @return array
     */
    public function getLocalTrabalhoContrato()
    {
        return $this->localTrabalhoContrato;
    }

    /**
     * @param array $localTrabalhoContrato
     */
    public function setLocalTrabalhoContrato($localTrabalhoContrato)
    {
        $this->localTrabalhoContrato = $localTrabalhoContrato;
    }

    /**
     * @return array
     */
    public function getObservacaoContratoTrabalho()
    {
        return $this->observacaoContratoTrabalho;
    }

    /**
     * @param array $observacaoContratoTrabalho
     */
    public function setObservacaoContratoTrabalho($observacaoContratoTrabalho)
    {
        $this->observacaoContratoTrabalho = $observacaoContratoTrabalho;
    }

    /**
     * @return array
     */
    public function getDependentes()
    {
        return $this->dependentes;
    }

    /**
     * @param array $dependentes
     */
    public function setDependentes($dependentes)
    {
        $this->dependentes = $dependentes;
    }

    /**
     * @return array
     */
    public function getImigrante()
    {
        return $this->imigrante;
    }

    /**
     * @param array $imigrante
     */
    public function setImigrante($imigrante)
    {
        $this->imigrante = $imigrante;
    }

    /**
     * @return array
     */
    public function getContato()
    {
        return $this->contato;
    }

    /**
     * @param array $contato
     */
    public function setContato($contato)
    {
        $this->contato = $contato;
    }

    /**
     * @return array
     */
    public function getEstatutario()
    {
        return $this->estatutario;
    }

    /**
     * @param array $estatutario
     */
    public function setEstatutario($estatutario)
    {
        $this->estatutario = $estatutario;
    }

    /**
     * @return array
     */
    public function getDeficiente()
    {
        return $this->deficiente;
    }

    /**
     * @param array $deficiente
     */
    public function setDeficiente($deficiente)
    {
        $this->deficiente = $deficiente;
    }

    /**
     * @return array
     */
    public function getSucessao()
    {
        return $this->sucessao;
    }

    /**
     * @param array $sucessao
     */
    public function setSucessao($sucessao)
    {
        $this->sucessao = $sucessao;
    }

    /**
     * @return array
     */
    public function getMudancaCPF()
    {
        return $this->mudancacpf;
    }

    /**
     * @param array $mudancacpf
     */
    public function setMudancaCPF($mudancacpf)
    {
        $this->mudancacpf = $mudancacpf;
    }

    /**
     * @return array
     */
    public function getAfastamento()
    {
        return $this->afastamento;
    }

    /**
     * @param array $afastamento
     */
    public function setAfastamento($afastamento)
    {
        $this->afastamento = $afastamento;
    }

    /**
     * @return array
     */
    public function getCessao()
    {
        return $this->cessao;
    }

    /**
     * @param array $cessao
     */
    public function setCessao($cessao)
    {
        $this->cessao = $cessao;
    }

    /**
     * @return array
     */
    public function getEstagiario()
    {
        return $this->estagiario;
    }

    /**
     * @param array $estagiario
     */
    public function setEstagiario($estagiario)
    {
        $this->estagiario = $estagiario;
    }

    /**
     * @return array
     */
    public function getCedencia()
    {
        return $this->cedencia;
    }

    /**
     * @param array $cedencia
     */
    public function setCedencia($cedencia)
    {
        $this->cedencia = $cedencia;
    }
    
    /**
     * @return array
     */
    public function getCargoFuncaoSemVinculo()
    {
        return $this->cargoFuncaoSemVinculo;
    }

    /**
     * @param array $cargoFuncaoSemVinculo
     */
    public function setCargoFuncaoSemVinculo($cargoFuncaoSemVinculo)
    {
        $this->cargoFuncaoSemVinculo = $cargoFuncaoSemVinculo;
    }

        /**
     * @return array
     */
    public function getAlteracaoContratualSemVinculo()
    {
        return $this->alteracaoContratualSemVinculo;
    }

    /**
     * @param array $alteracaoContratualSemVinculo
     */
    public function setAlteracaoContratualSemVinculo($alteracaoContratualSemVinculo)
    {
        $this->alteracaoContratualSemVinculo = $alteracaoContratualSemVinculo;
    }
}
