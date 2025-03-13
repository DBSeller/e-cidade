<?php

namespace ECidade\Tributario\Grm\WebService;

use ECidade\V3\Extension\Registry;
use ECidade\Tributario\Cadastro\Repository\SetorFiscalRepository;

class Contribuinte
{
    /**
     * @var string
     */
    private $sCpfCnpj;

    /**
     * @var string
     */
    private $tipoOrigem;
    
    public function getDadosContribuinte()
    {
        $oCgm = \CgmFactory::getCgmByCnpjCpf($this->sCpfCnpj);

        if (!$oCgm) {
            throw new \Exception("Contribuinte não encontrado.");
        }

        $oContribuinte = new \stdClass();
        $oContribuinte->cgm = $oCgm->getCodigo();
        $oContribuinte->nome = $oCgm->getNomeCompleto();
        $oContribuinte->logradouro = $oCgm->getLogradouro();
        $oContribuinte->numero = $oCgm->getNumero();
        $oContribuinte->bairro = $oCgm->getBairro();
        $oContribuinte->cidade = $oCgm->getMunicipio();
        $oContribuinte->cep = $oCgm->getCep();
        
        $oContainer = Registry::get('app.container')->get('tributario.container');

        if (in_array($this->tipoOrigem, ["M", "T"])) {
            $iptubaseRepository = $oContainer->get('IptubaseRepository');
            $aMatriculas = $iptubaseRepository->findAll(" j01_numcgm = {$oContribuinte->cgm} AND j01_baixa IS NULL");
            $aMatriculasContrib = array();

            foreach ($aMatriculas as $oMatricula) {
                $aMatriculasContrib[] = $oMatricula->getMatric();
            }

            $oContribuinte->aMatriculas = $aMatriculasContrib;
        } else {
            $oContribuinte->aMatriculas = array();
        }

        if (in_array($this->tipoOrigem, ["I", "T"])) {
            $issbaseRepository = $oContainer->get('IssbaseRepository');
            $aInscricoes = $issbaseRepository->findAll(" q02_numcgm = {$oContribuinte->cgm} AND q02_dtbaix IS NULL");
            $aInscricoesContrib = array();

            foreach ($aInscricoes as $oInscricoes) {
                $aInscricoesContrib[] = $oInscricoes->getInscr();
            }
            
            $oContribuinte->aInscricoes = $aInscricoesContrib;
        } else {
            $oContribuinte->aInscricoes = array();
        }

        return utf8_encode_all($oContribuinte);
    }

    public function getDadosContribuinteSetorFiscal($matricula)
    {
        $oFiscalRepository = SetorFiscalRepository::getInstance();

        return $oFiscalRepository->getSetorFiscalByMatric($matricula);
    }

    /**
     * Set the value of sCpfCnpj
     *
     * @param  string  $sCpfCnpj
     *
     * @return  self
     */
    public function setCpfCnpj($sCpfCnpj)
    {
        $this->sCpfCnpj = $sCpfCnpj;

        return $this;
    }

    /**
     * Set the value of tipoOrigem
     *
     * @param  string  $tipoOrigem
     *
     * @return  self
     */
    public function setTipoOrigem($tipoOrigem)
    {
        $this->tipoOrigem = $tipoOrigem;

        return $this;
    }
}
