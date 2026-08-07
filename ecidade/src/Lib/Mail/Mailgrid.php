<?php
namespace ECidade\Mail;

class Mailgrid
{
    /**
     * api
     * @var string
     */
    private $url = "https://painel.mailgrid.com.br/api2/";

    private $emailRemetente;
    private $nomeRemetente;
    private $emailDestino;
    private $assunto;
    private $mensagem;

    /**
     * @var array
     */
    private $config = array(
        'host_smtp' => "cloud79.mailgrid.net.br",
        'usuario_smtp' => "smtp1@dbseller.com.br",
        'senha_smtp' => "dsaj73nem34",
        'emailRemetente' => null,
        'nomeRemetente' => null,
        'emailDestino' => null,
        'assunto' => null,
        'mensagem' => null,
        'output' => "json",
    );

    /**
     * @var string[]
     */
    private $camposValidados = array(
        'emailRemetente' => 'email',
        'emailDestino' => 'email',
        'assunto' => 'obrigatorio',
        'mensagem' => 'obrigatorio',
    );

    /**
     * @var string[]
     */
    private $response = array(
        "status" => "error",
        "codigo_status" => "0",
        "mensagem_erro" => "",
        "criptokey" => "",
        "to" => ""
    );

    /**
     * @param $emailRemetente
     * @param null $nomeRemetente
     * @param $emailDestino
     * @param $assunto
     * @param $mensagem
     * @return string[status,codigo_status,mensagem_erro,criptokey,to]
     */
    public function __construct(
        $emailRemetente = null,
        $nomeRemetente = null,
        $emailDestino = null,
        $assunto = null,
        $mensagem = null
    ) {

        $this->setEmailRemetente($emailRemetente);
        $this->setNomeRemetente($nomeRemetente);
        $this->setEmailDestino($emailDestino);
        $this->setAssunto($assunto);
        $this->setMensagem($mensagem);
    }

    /**
     * @return mixed
     */
    public function getEmailRemetente()
    {
        return $this->emailRemetente;
    }


    /**
     * @param $emailRemetente
     * @return $this
     */
    public function setEmailRemetente($emailRemetente)
    {
        $this->emailRemetente = $emailRemetente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomeRemetente()
    {
        return $this->nomeRemetente;
    }

    /**
     * @param $nomeRemetente
     * @return $this
     */
    public function setNomeRemetente($nomeRemetente)
    {
        $this->nomeRemetente = $nomeRemetente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailDestino()
    {
        return $this->emailDestino;
    }


    /**
     * @param $emailDestino
     * @return $this
     */
    public function setEmailDestino($emailDestino)
    {
        $this->emailDestino = $emailDestino;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssunto()
    {
        return $this->assunto;
    }


    /**
     * @param $assunto
     * @return $this
     */
    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }


    /**
     * @param $mensagem
     * @return $this
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
        return $this;
    }

    /**
     * @return string[]
     */
    public function enviarEmail()
    {
        try {
            $this->config['emailRemetente'] = $this->getEmailRemetente();
            $this->config['nomeRemetente'] = $this->getNomeRemetente();
            $this->config['emailDestino'] = $this->getEmailDestino();
            $this->config['assunto'] = $this->getAssunto();
            $this->config['mensagem'] = $this->getMensagem();
            $this->validarParametros();
            $this->requestServer();
        } catch (\Exception $ex) {
            $this->response["mensagem_erro"] = $ex->getMessage();
        }

        return $this->response;
    }

    /**
     * @throws \Exception
     */
    private function validarParametros()
    {
        foreach ($this->camposValidados as $campo => $validacao) {
            switch ($validacao) {
                case "email":
                    $this->validarEmail($campo);
                    break;
                case "obrigatorio":
                    $this->validarObrigatorio($campo);
                    break;
            }
        }
    }

    /**
     * @return string
     */
    private function montarUrl()
    {

        $url = $this->url . "?";
        foreach ($this->config as $key => $value) {
            $url .= "{$key}={$value}";
            if ($key != "output") {
                $url .= "&";
            }
        }

        return $url;
    }

    /**
     * @param $campo
     * @throws \Exception
     */
    private function validarEmail($campo)
    {
        $email = $this->config[$campo];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Campo {$campo} tem formato de E-mail invalido ");
        }
    }

    /**
     * @param $campo
     * @throws \Exception
     */
    private function validarObrigatorio($campo)
    {
        $campoValue = $this->config[$campo];
        if (empty($campoValue)) {
            throw new \Exception("Campo {$campo} Ã© obrigatorio");
        }
    }


    private function requestServer()
    {


        $dados = http_build_query($this->config);
        $contexto = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'content' => $dados,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($dados) . "\r\n",
            )
        ));

        $result = file_get_contents($this->url, null, $contexto);
        $result = json_decode($result)[0];
        $this->response["status"] = $result->status;
        $this->response["codigo_status"] = (int)$result->codigo_status;

        if ($this->response["codigo_status"] == 1) {
            $this->response["criptokey"] = $result->criptokey;
            $this->response["to"] = $result->to;
        } else {
            $this->response["mensagem_erro"] = $result->mensagem_erro;
        }
    }
}
