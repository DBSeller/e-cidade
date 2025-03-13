<?php

namespace ECidade\Patrimonial\Licitacao\ComprasPublicas\Model;

use licitacao;
use stdClass;
use Exception;
use cl_pcorcam;
use cl_pcorcamforne;
use cl_pcorcamjulgamentolog;

class ComprasPublicasOrcamento
{
    private $codigoOrcamento;
    private $codigoLogJulgamento;
    private $listaFornecedor = [];

    public function __construct()
    {
    }

    public function importar($dadosFornecedores)
    {
        $pcorcam              = new cl_pcorcam();
        $pcorcam->pc20_dtate  = date("Y-m-d"); // verificar se a data deve vir da integração
        $pcorcam->pc20_hrate  = date("H:i");
        $pcorcam->pc20_obs    = "Orçamento automático (Compras Públicas)";
        $pcorcam->incluir(null);
        $this->codigoOrcamento = $pcorcam->pc20_codorc;
        $erro_msg = $pcorcam->erro_msg;
        if ($pcorcam->erro_status == 0) {
            throw new Exception($erro_msg);
        }
        
        $pcorcamjulgamentolog                      = new cl_pcorcamjulgamentolog();
        $pcorcamjulgamentolog->pc92_usuario        = db_getsession("DB_id_usuario");
        $pcorcamjulgamentolog->pc92_datajulgamento = date("Y-m-d"); // verificar se a data deve vir da integração
        $pcorcamjulgamentolog->pc92_hora           = date("H:i");
        $pcorcamjulgamentolog->pc92_ativo          = "t";
        $pcorcamjulgamentolog->incluir(null);
        $this->codigoLogJulgamento                 =  $pcorcamjulgamentolog->pc92_sequencial;
        if ($pcorcamjulgamentolog->erro_status == 0) {
            throw new Exception($pcorcamjulgamentolog->erro_msg);
        }
        
        foreach ($dadosFornecedores as $dadoFornecedor) {
            $fornecedor = new ComprasPublicasFornecedor();
            $fornecedor->importar($this->codigoOrcamento, $dadoFornecedor);
            $this->listaFornecedor[$fornecedor->getCpfCnpj()] = $fornecedor->getFornecedor();
        }
    }

    public function getDeParaFornecedores()
    {
        return $this->listaFornecedor;
    }

    public function getCodigoOrcamento()
    {
        return $this->codigoOrcamento;
    }
    
    public function getCodigoLogJulgamento()
    {
        return $this->codigoLogJulgamento;
    }
}
