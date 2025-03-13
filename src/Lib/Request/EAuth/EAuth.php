<?php
namespace ECidade\Lib\Request\EAuth;

use \GuzzleHttp\Client;
use ECidade\V3\Extension\Registry;

class EAuth
{

    private $urlApi;
    private $municipio;
    private $grant_type;
    private $client_id;
    private $client_secret;
    private $token;

    public function __construct()
    {
        $this->getAuthorization();
    }

    /**
     * @return mixed
     */
    public function getUrlApi()
    {
        return $this->urlApi;
    }

    public function getRouteToken()
    {
        return $this->getUrlApi() . "/oauth/token";
    }

    public function getRouteValidaExsite()
    {
        return $this->getUrlApi() . "/users/valida";
    }

    public function getRouteUserSave()
    {
        return $this->getUrlApi() . "/users/save";
    }

    public function getRouteEnviaPush()
    {
        return $this->getUrlApi() . "/api/push/save";
    }

    public function getRouteEmailMessage()
    {
        return $this->getUrlApi() . "/users/message";
    }

    public function getRouteUserCpf()
    {
        return $this->getUrlApi() . "/users/cpfcnpj";
    }

    /**
     * @param mixed $urlApi
     */
    public function setUrlApi($urlApi)
    {
        $this->urlApi = $urlApi;
    }

    /**
     * @return mixed
     */
    public function getGrantType()
    {
        return $this->grant_type;
    }

    /**
     * @param mixed $grant_type
     */
    public function setGrantType($grant_type)
    {
        $this->grant_type = $grant_type;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param mixed $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     * @param mixed $client_secret
     */
    public function setClientSecret($client_secret)
    {
        $this->client_secret = $client_secret;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @param mixed $municipio
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    }

    private function validateConfig()
    {

        if (!Registry::get('app.config')->has('app.api')) {
            $msg = "Erro ao buscar as credencias das api's";
            $msg .= "\nVerifique o arquivo de configuração (application).";
            throw new \Exception($msg);
        }

        $configApi = (object)Registry::get('app.config')->get('app.api');

        if (empty($configApi) || empty($configApi->eauth)) {
            $msg = "Erro ao buscar as credencias do eauth";
            $msg .= "\nVerifique o arquivo de configuração (application).";
            throw new \Exception($msg);
        }

        $eauth = (object)$configApi->eauth;

        $this->setUrlApi($eauth->url);
        $this->setClientId($eauth->client_id);
        $this->setClientSecret($eauth->client_secret);
        $this->setGrantType($eauth->grant_type);
        $this->setMunicipio($eauth->municipio);

        if (empty($this->getUrlApi())) {
            throw new \Exception("eauth url NÃO CONFIGURADO");
        }

        if (empty($this->getGrantType())) {
            throw new \Exception("eauth grant_type NÃO CONFIGURADO");
        }

        if (empty($this->getClientId())) {
            throw new \Exception("eauth client_id NÃO CONFIGURADO");
        }

        if (empty($this->getClientSecret())) {
            throw new \Exception("eauth client_secret NÃO CONFIGURADO");
        }

        if (empty($this->getMunicipio())) {
            throw new \Exception("eauth municipio NÃO CONFIGURADO");
        }
    }

    private function getAuthorization()
    {
        try {
            $this->validateConfig();
            $request = new Client();
            $options = [
                'form_params' => [
                    'grant_type' => $this->getGrantType(),
                    'client_id' => $this->getClientId(),
                    'client_secret' => $this->getClientSecret(),
                ]
            ];

            $response = $request->request('POST', $this->getRouteToken(), $options);
            $obj = (object)json_decode($response->getBody(), true);

            if (empty($obj->access_token)) {
                throw new \Exception("Erro ao buscar token");
            }
            $this->token = $obj->access_token;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    private function getHeaders()
    {
        return array(
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        );
    }

    public function validarUsuarioExiste($cpfcnpj)
    {
        $request = new Client();
        $options = array(
            'headers' => $this->getHeaders(),
            'form_params' => array(
                'cpfcnpj' => $cpfcnpj,
                'municipio' => $this->getMunicipio()
            )
        );

        return $request->request('POST', $this->getRouteValidaExsite(), $options);
    }

    public function salvarUsuarioEauth($nome, $email, $cpfcnpj, $client_app_id)
    {
        if (!mb_detect_encoding($nome, 'UTF-8', true)) {
            $nome = mb_convert_encoding($nome, "UTF-8");
        }

        if (!mb_detect_encoding($email, 'UTF-8', true)) {
            $email = mb_convert_encoding($email, "UTF-8");
        }

        if (!mb_detect_encoding($cpfcnpj, 'UTF-8', true)) {
            $cpfcnpj = mb_convert_encoding($cpfcnpj, "UTF-8");
        }

        $request = new Client();
        $options = array(
            'headers' => $this->getHeaders(),
            'form_params' => array(
                'name' => $nome,
                'email' => $email,
                'cpfcnpj' => $cpfcnpj,
                'municipio' => $this->getMunicipio(),
                'client_id' => $client_app_id
            )
        );
        $response = $request->request('POST', $this->getRouteUserSave(), $options);
        return (object)json_decode($response->getBody(), true);
    }

    public function consultaUserCpf($cpfcnpj)
    {
        $request = new Client();
        $options = array(
            'headers' => $this->getHeaders(),
        );
        $params = http_build_query(array(
            'cpfcnpj' => str_replace(array(".", "-", "/"), "", $cpfcnpj),
            'municipio' => $this->getMunicipio()
        ));
        $response = $request->get($this->getRouteUserCpf()."?{$params}", $options);
        return (object)json_decode($response->getBody(), true);
    }

    public function sendMessage($cpfCnpj, $client_app_id, $message, $email = null, $anonimo = false)
    {
        $request = new Client();
        $message = \DBString::utf8_encode_all($message);
        $options = array(
            'headers' => $this->getHeaders(),
            'form_params' => array(
                'cpfcnpj' => $cpfCnpj,
                'client_id' => $client_app_id,
                'message' => $message,
                'email' => $email,
                'anonimo' => $anonimo
            )
        );
        $response = $request->request('POST', $this->getRouteEmailMessage(), $options);
        return (object)json_decode($response->getBody(), true);
    }
}
