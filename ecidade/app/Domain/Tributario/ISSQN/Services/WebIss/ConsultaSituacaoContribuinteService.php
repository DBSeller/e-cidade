<?php

namespace App\Domain\Tributario\ISSQN\Services\WebIss;

use Exception;
use SimpleXMLElement;
use GuzzleHttp\Client;

class ConsultaSituacaoContribuinteService
{

    const ROTACONSULTA             = "ws/integracao/consulta-certidao-negativa/";
    const POSITIVA                 = "POSITIVA";
    const NEGATIVA                 = "NEGATIVA";
    const POSITIVA_EFEITO_NEGATIVA = "POSITIVA_EFEITO_NEGATIVA";
    const NAO_ENCONTRADO           = "NAO_ENCONTRADO";
    const NOME_SERVICO             = "WEBISS";
  /**
   * @var Object
   * dados de configuração do webservice
   */
    private $oConfigWebservice;
  /**
   * @var Boolean
   */
    private $lUtilizaWebservice = false;
  
  /**
   * @var String
   */
    private $sUrlValidacao;
  
    public function __construct()
    {
    
        if (Autenticacao::getWebIssConfig()) {
            $this->lUtilizaWebservice = true;
            $this->oConfigWebservice  = Autenticacao::getWebIssConfig();
        }
    }

    public function getUrlValidacao($iCpfCnpj, $iInscricao = null)
    {
    
        if (!$this->utilizaWebservice()) {
            throw new Exception("Não existe configuração para este serviço({$this->oConfigWebservice->url})");
        }

        if ($iCpfCnpj == null || $iCpfCnpj == "") {
            throw new Exception("CPF/CNPJ inválido");
        }
    
        $this->sUrlValidacao  = $this->oConfigWebservice->url;
        $this->sUrlValidacao .= ConsultaSituacaoContribuinteService::ROTACONSULTA.$iCpfCnpj;
        if ($iInscricao != null && $iInscricao != "") {
            $this->sUrlValidacao .= DIRECTORY_SEPARATOR.$iInscricao;
        }

        return $this->sUrlValidacao;
    }
   
    public function getValidacaoContribuinte($iCpfCnpj, $iInscricao = null)
    {
    
        $oRequest = new Client();
        $aOptions = [
        'headers' => [
                    'autenticacao' => $this->oConfigWebservice->autenticacao,
                    'Accept'       => 'application/xml',
                    'timeout'      => 20
                   ]
        ];
    
        $oResponse = $oRequest->request('GET', $this->getUrlValidacao($iCpfCnpj, $iInscricao), $aOptions);
        if (!in_array($oResponse->getStatusCode(), [200, 201, 202])) {
            throw new Exception("Serviço Indisponível({$this->oConfigWebservice->url})");
        }
        
        $oResponseXml = $this->parseResponse($oResponse);
        return $oResponseXml;
    }
  
    private function parseResponse($oResponse)
    {
    
        return new SimpleXMLElement($oResponse->getBody()->getContents());
    }

    public function utilizaWebservice()
    {

        return $this->lUtilizaWebservice;
    }
}
