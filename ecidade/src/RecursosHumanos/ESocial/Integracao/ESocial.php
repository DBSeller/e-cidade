<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao;

use ECidade\RecursosHumanos\ESocial\ESocialContextException;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use \ECidade\V3\Extension\Registry;
use \ECidade\Core\Config;
use DBHttpRequest;

/**
 * Classe responsável pelo envio dos dados do eSocial para a API do e-cidade
 */
class ESocial
{
    /**
     * Classe para requisição HTTP
     *
     * @var DBHttpRequest
     */
    private $httpRequest;

    /**
     * Configuração da aplicação
     *
     * @var Config
     */
    private $config;

    /**
     * Recurso para envio dos dados
     *
     * @var string
     */
    private $recurso;

    /**
     * Dados a ser enviados
     *
     * @var array|\stdClass
     */
    private $dados;

    public function __construct(Config $config, $recurso)
    {
        $this->config = $config;

        $this->validaConfiguracao();

        $dadosAPI = $this->config->get('app.api');
        $httpRequest = new DBHttpRequest(Registry::get('app.config'));
        $httpRequest->addOptions(array(
            'baseUrl' => $dadosAPI['esocial']['url'] ,
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            )
        ));
        $this->httpRequest = $httpRequest;

        $httpRequest->addOptions(array(
            'headers' => array(
                'X-Access-Token' => $this->login()
            )
        ));

        $this->recurso = $recurso;
    }

    /**
     * Seta os dados a ser enviados
     *
     * @param \stdClass[] $dados
     */
    public function setDados($dados)
    {
        $this->dados = $dados;
    }

    /**
     * Realiza a requisição enviando os dados para API
     *
     * @param string $method
     * @throws ESocialContextExceptionException
     * @return null|\stdClass
     */
    public function request($method = "POST")
    {
        $data = json_encode($this->dados);

        $this->httpRequest->send($this->recurso, $method, array(
            'body' => $data
        ));

        $result = json_decode($this->httpRequest->getBody());
        $code = $this->httpRequest->getResponseCode();

        if ($code >= 400) {
            $exception = new ESocialContextException($result->message, $code);
            $exception->setContext($result);
            throw $exception;
        }
        return $result;
    }

    /**
     * Retorna o código de resposta HTTP da requisição
     *
     * @return integer
     */
    public function getResponseCode()
    {
        return $this->httpRequest->getResponseCode();
    }

    /**
     * Valida se foi configurado o acesso a API.
     * @throws ESocialContextExceptionException
     * @return void
     */
    private function validaConfiguracao()
    {
        $dadosAPI = $this->config->get('app.api');
        if (empty($dadosAPI['esocial']['url']) ||
            empty($dadosAPI['esocial']['login']) ||
            empty($dadosAPI['esocial']['password'])) {
            throw new ESocialContextException("Entre em contato com o administrador do sistema para configurar acesso ao eSocial.");
        }
        return true;
    }

    /**
     * Efetua o login na API do eSocial
     *
     * @return string
     */
    private function login()
    {
        $dadosAPI = $this->config->get('app.api');
        unset($dadosAPI['esocial']['url']);

        try {
            $this->httpRequest->send('/auth/login', 'POST', array(
                'body' => \json_encode((object) $dadosAPI['esocial'])
            ));
        } catch (\Exception $e) {
            throw new ESocialContextException("Erro ao conectar na API do eSocial.");
        }

        $result = json_decode($this->httpRequest->getBody());
        $code = $this->httpRequest->getResponseCode();

        if (!isset($result->access_token)) {
            throw new ESocialContextException("Erro ao efetuar login na API.", $code);
        }

        return $result->access_token;
    }
}
