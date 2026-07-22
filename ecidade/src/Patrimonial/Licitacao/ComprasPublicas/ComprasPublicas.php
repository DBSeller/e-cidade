<?php

namespace Ecidade\Patrimonial\Licitacao\ComprasPublicas;

use ECidade\Lib\Request\Curl;
use ECidade\Patrimonial\Licitacao\ComprasPublicas\Model\ComprasPublicasLicitacao;
use ECidade\Patrimonial\Licitacao\ComprasPublicas\Model\ComprasPublicasConfiguracao;
use Exception;

class ComprasPublicas
{
    private $urlApi;
    private $acessKey;
    private $urlEnviaDados;
    private $urlBuscaProcessos;
    private $urlBuscaUnidades;
    
    public function __construct()
    {
        /**
         * To do
         * buscar dos parametros o hash/token do Municipio e a Url
         */
        $configuracao   = new ComprasPublicasConfiguracao();
        $configuracao->ler();
        $this->urlApi   = $configuracao->getUrl();
        //$this->acesskey = "5db216a443cf282889df9883dda04138";
        $this->acesskey = $configuracao->getToken();
        
        /**
         * Rotas da Api utilizadas no e-cidade
         */
        $this->urlEnviaDados       = "{$this->urlApi}/comprador/{$this->acesskey}/processo/pregao";
        $this->urlListaProcessos   = "{$this->urlApi}/comprador/{$this->acesskey}/processos";
        $this->urlBuscaProcesso    = "{$this->urlApi}/comprador/{$this->acesskey}/processo";
        $this->urlListaDocumentos  = "{$this->urlApi}/comprador/{$this->acesskey}/documentos";
        //$this->urlBuscaUnidades  = "{$this->urlApi}/comprador/{$this->acesskey}/unidades";
    }

    public function getProcessos($ano, $numero = null, $pagina = 1)
    {

        if (empty($ano)) {
            throw new Exception("Ano do processo é obrigatório para pesquisa");
        }

        $parametroPesquisa = "{$ano}";
        if ($numero != null) {
            $parametroPesquisa .= "/{$numero}";
        }

        $url     = $this->urlListaProcessos . "/{$parametroPesquisa}?pagina={$pagina}";
        $curl    = new Curl();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20
        );
        $curl->setOptions($options);
        $curl->execute();
        if ($curl->getErro() || $curl->getCodeReturn() == "404") {
            $mensagem = "Erro na comunicação com {$this->urlApi} - ";
            $mensagem.= "código retorno (".$curl->getInfo(CURLINFO_HTTP_CODE).")\n";
            $mensagem.= "Verifique se a URL {$this->urlApi} é válida ou se há conectividade";
            throw new Exception($mensagem);
        }
        $response = $curl->getResponse();
        $curl->close();
        return $response;
    }
    
    public function getDocumentos()
    {
        $curl    = new Curl();
        $options = array(
            CURLOPT_URL => $this->urlListaDocumentos,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20
        );
        $curl->setOptions($options);
        $curl->execute();
        if ($curl->getErro() || $curl->getCodeReturn() == "404") {
            $mensagem = "Erro na comunicação com {$this->urlApi} - ";
            $mensagem.= "código retorno (".$curl->getInfo(CURLINFO_HTTP_CODE).")\n";
            $mensagem.= "Verifique se a URL {$this->urlApi} é válida ou se há conectividade";
            throw new Exception($mensagem);
        }
        $response = $curl->getResponse();
        $curl->close();
        return $response;
    }

    public function getDadosProcessos($processo)
    {
        if (empty($processo)) {
            throw new Exception("Número do processo é obrigatório para pesquisa");
        }
        $parametroPesquisa = "{$processo}";

        $url     = $this->urlBuscaProcesso . "/{$parametroPesquisa}";
        $curl    = new Curl();
       
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20
        );
        $curl->setOptions($options);
        $curl->execute();
        if ($curl->getErro() || $curl->getCodeReturn() == "404") {
            $mensagem = "Erro na comunicação com {$this->urlApi} - ";
            $mensagem.= "código retorno (".$curl->getInfo(CURLINFO_HTTP_CODE).")\n";
            $mensagem.= "Verifique se a URL {$this->urlApi} é válida ou se há conectividade";
            throw new Exception($mensagem);
        }
        $response = $curl->getResponse();
        $curl->close();

        return $response;
    }

    public function getRankingProcesso($processo)
    {

        if (empty($processo)) {
            throw new Exception("Número do processo é obrigatório para pesquisa");
        }

        $parametroPesquisa = "{$processo}/ranking";
        $url     = $this->urlBuscaProcesso . "/{$parametroPesquisa}";
        $curl    = new Curl();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20
        );
        $curl->setOptions($options);
        $curl->execute();
        if ($curl->getErro() || $curl->getCodeReturn() == "404") {
            $mensagem = "Erro na comunicação com {$this->urlApi} - ";
            $mensagem.= "código retorno (".$curl->getInfo(CURLINFO_HTTP_CODE).")\n";
            $mensagem.= "Verifique se a URL {$this->urlApi} é válida ou se há conectividade";
            throw new Exception($mensagem);
        }
        $response = $curl->getResponse();
        $curl->close();
        return $response;
    }

    public function enviaDadosPregao($licitacao = null, $documentos = [], $configuracao = [])
    {
        if ($licitacao == null) {
            throw new \Exception("Licitação não foi informada");
        }

        $dadosLicitacao = new ComprasPublicasLicitacao($licitacao, $documentos);
        $dadosEnvio     = $dadosLicitacao->processarDados($configuracao);
        $url            = $this->urlEnviaDados;
        $data           = json_encode($dadosEnvio);
        if (!$data) {
            throw new Exception("Não foi possível gerar os dados para envio");
        }

        $curl           = new Curl();
        $options        = array(
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            CURLOPT_RETURNTRANSFER => true
        );
        $curl->setOptions($options);
        $curl->execute();

        if ($curl->getErro() || $curl->getCodeReturn() == "404") {
            $mensagem = "Erro na comunicação com {$this->urlApi} - código retorno (".$curl->getCodeReturn().")\n";
            $mensagem.= "Verifique se a URL {$this->urlApi} é válida ou se há conectividade";
            throw new Exception($mensagem);
        }

        $response = $curl->getResponse();
        $curl->close();
        
        return $response;
    }

    // public function getUnidades()
    // {
    //     $url     = $this->urlBuscaUnidades;
    //     $curl    = new Curl();
    //     $options = array(CURLOPT_URL => $url,
    //                      CURLOPT_RETURNTRANSFER => true,
    //                      CURLOPT_TIMEOUT => 20
    //                     );
    //     $curl->setOptions($options);
    //     $curl->execute();
    //     $response = $curl->getResponse();
    //     $curl->close();
    //     return $response;
    // }

    // public function cadastraUnidade($sigla, $descricao)
    // {
    //     $dadosUnidade = new stdClass();
    //     $dadosUnidade->sigla     = $sigla;
    //     $dadosUnidade->descricao = $descricao;
    //     $data            = json_encode($dadosUnidade);
    //     $curl            = new Curl();
    //     $options         = array(CURLOPT_URL => $url,
    //                             CURLOPT_POSTFIELDS => $data,
    //                             CURLOPT_TIMEOUT => 20,
    //                             CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
    //                             CURLOPT_RETURNTRANSFER => true
    //                             );
    //     $curl->setOptions($options);
    //     $curl->execute();
    //     if ($curl->getErro()) {
    //         throw new Exception("[Erro na comunicação] - ".$curl->getErro());
    //     }

    //     $response = $curl->getResponse();
    //     $curl->close();

    //     return $response;
    // }
}
