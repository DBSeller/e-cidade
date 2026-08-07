<?php

namespace Ecidade\Patrimonial\Licitacao\ComprasPublicas\Model;

use cl_licitaparam;
use db_utils;
use Exception;
use stdClass;

final class ComprasPublicasConfiguracao
{
    private $url;
    private $token;
    private $licitaparam;

    public function __construct()
    {
        $this->licitaparam = new cl_licitaparam();
        $sqlParametro = $this->licitaparam->sql_query(
            null,
            "l12_urlapi, l12_token",
            '',
            "l12_instit=" . db_getsession('DB_instit')
        );
        $rsParametro     = $this->licitaparam->sql_record($sqlParametro);
        if (!$rsParametro || ($rsParametro && $this->licitaparam->numrows == 0)) {
            throw new Exception('Não foi encontrado parâmetro do módulo Licitação. Configure para utilizar rotina');
        }

        $this->configuracao = db_utils::fieldsMemory($rsParametro, 0);
    }

    public function ler()
    {

        if (!isset($this->configuracao->l12_urlapi) || empty($this->configuracao->l12_urlapi)) {
            throw new Exception("Não foi encontrado configuração da URL");
        }

        if (!isset($this->configuracao->l12_token) || empty($this->configuracao->l12_token)) {
            throw new Exception("Não foi encontrado configuração de identificação do Comprador");
        }

        $this->setUrl($this->configuracao->l12_urlapi);
        $this->setToken($this->configuracao->l12_token);
    }

    public function salvar()
    {

        if (empty($this->getUrl())) {
            throw new Exception("Não encontrado dados para configuração da URL");
        }

        if (empty($this->getToken())) {
            throw new Exception("Não encontrado dados configuraçãoo para identificar comprador");
        }

        $this->licitaparam->l12_urlapi = $this->url;
        $this->licitaparam->l12_token  = $this->token;
        $this->licitaparam->l12_instit = db_getsession("DB_instit");
        $this->licitaparam->alterar($this->licitaparam->l12_instit);
        if ($this->licitaparam->numrows_alterar == 0) {
            throw new Exception("Não foi possível salvar configuração" . db_getsession("DB_instit"));
        }
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}
