<?php

namespace App\Domain\Tributario\Arrecadacao\Pix\Bancos;

use \GuzzleHttp\Client;

use \App\Domain\Tributario\Arrecadacao\Pix\Adapter\PixBanco;
use App\Domain\Tributario\Arrecadacao\Pix\PixInfo;
use \Swagger\Client\Model\ArrecadacaoqrcodesBody;
use \Swagger\Client\Api\Oauth2Api;
use \Swagger\Client\Configuration;
use \Swagger\Client\Api\QrCodesApi;
use \Swagger\Client\Model\InlineResponse201;

class BancoDoBrasil extends PixInfo implements PixBanco
{

    const BANK_CODE = '001';

    const BASE_AUTH_URL = [
        "production" => 'https://oauth.bb.com.br/oauth/token',
        "sandbox" => 'https://oauth.sandbox.bb.com.br/oauth/token'
    ];

    const BASE_URL = [
        "production" => 'https://api.bb.com.br/pix-bb/v1',
        "sandbox" => 'https://api.hm.bb.com.br/pix-bb/v1'
    ];

    private $config;

    public function __construct()
    {
        parent::__construct(BancoDoBrasil::BANK_CODE, BancoDoBrasil::BASE_AUTH_URL, BancoDoBrasil::BASE_URL);
    }

    public function gerarPix()
    {
        if (!$this->canGeneratePix()) {
            return;
        }

        $this->config = $this->preparaConfiguracao();

        $apiInstance = new QrCodesApi(
            new Client(['verify' => false]),
            $this->config
        );

        $envio = $this->preparaCorpo();

        $response = $apiInstance->criaBoletoBancarioId(
            $envio,
            $this->config->getAccessToken(),
            $this->config->getChaveAplicacaoBB()
        );

        if ($response instanceof InlineResponse201) {
            $this->savePixTransactionInfo(
                $response->getTimestampCriacaoSolicitacao(),
                $response->getEstadoSolicitacao(),
                $response->getCodigoConciliacaoSolicitante(),
                $response->getNumeroVersaoSolicitacaoPagamento(),
                $response->getLinkQrCode(),
                $response->getQrCode(),
                BancoDoBrasil::BANK_CODE
            );
        }
    }

    protected function preparaConfiguracao()
    {
        $config = Configuration::getDefaultConfiguration();
        $config->setAmbienteBB("T");
        $config->setHost($this->getBaseUrl());
        $config->setUrlAutenticacaoOauth2($this->getBaseAuthUrl());
        $config->setChaveAplicacaoBB($this->getAppKey());
        $config->setUsername($this->getAppUsername());
        $config->setPassword($this->getAppPassword());

        $oauth2Api = new Oauth2Api(
            new Client(['verify' => false]),
            $config
        );

        $token = $oauth2Api->gerarAccessToken();
        $config->setAccessToken($token);

        return $config;
    }

    protected function preparaCorpo()
    {
        $cpfCnpj = $this->cgm->z01_cgccpf;

        if ($this->isCountyCnpj() === true && empty($this->cgm->z01_cgccpf)) {
            $cpfCnpj = $this->getCnpj();
        }

        $modelBody = new ArrecadacaoqrcodesBody();
        $modelBody['numero_convenio'] = $this->getCovenantCode();
        $modelBody['indicador_codigo_barras'] = "S";
        $modelBody['codigo_guia_recebimento'] = $this->getCodigoBarras();
        $modelBody['email_devedor'] = (empty($this->cgm->z01_email) ? "": $this->cgm->z01_email);
        $modelBody['codigo_pais_telefone_devedor'] = "";
        $modelBody['ddd_telefone_devedor'] = "";
        $modelBody['numero_telefone_devedor'] = "";
        $modelBody['codigo_solicitacao_banco_central_brasil'] = $this->getPixKey();
        $modelBody['descricao_solicitacao_pagamento'] = utf8_encode("Arrecadacao Pix");
        $modelBody['valor_original_solicitacao'] = $this->getValor();
        $modelBody['cpf_devedor']  = (strlen($cpfCnpj) < 13 ? $cpfCnpj : "");
        $modelBody['cnpj_devedor'] = (strlen($cpfCnpj) > 13 ? $cpfCnpj : "");
        $modelBody['nome_devedor'] = utf8_encode($this->cgm->z01_nome);
        $modelBody['quantidade_segundo_expiracao'] = $this->getSegundosExpiracao();
        $modelBody['lista_informacao_adicional'] = null;

        return $modelBody;
    }

    protected function getSegundosExpiracao()
    {
        $dataInicial = new \DateTime();
        $dataFinal   = new \DateTime("{$this->getVencimento()} 23:59:59");
        $diferenca   = ($dataFinal->getTimestamp() - $dataInicial->getTimestamp());

        return $diferenca;
    }
}
