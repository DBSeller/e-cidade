<?php

namespace ECidade\Patrimonial\Licitacao\ComprasPublicas\Model;

use ECidade\Patrimonial\Protocolo\Servicos\InclusaoCgmLegacy;
use stdClass;
use cl_pcorcamforne;
use cl_pcorcamfornelic;
use Exception;

class ComprasPublicasFornecedor
{

    private $codigoFornecedor;
    private $cpfcnpj;

    public function __construct()
    {
    }

    public function importar($codigoOrcamento, $dadosFornecedor)
    {
        $pcorcamforne                        = new cl_pcorcamforne();
        $pcorcamfornelic                     = new cl_pcorcamfornelic();

        $cgm                                 = new InclusaoCgmLegacy();
        $dadosCGM                            = new stdClass();
        $dadosCGM->cpf_cnpj                  = empty($dadosFornecedor->CNPJ) ?
                                               $dadosFornecedor->CPF : $dadosFornecedor->CNPJ;
        $dadosCGM->cpf->value                = empty($dadosFornecedor->CNPJ) ? $dadosFornecedor->CPF : null;
        $dadosCGM->cnpj->value               = empty($dadosFornecedor->CNPJ) ? null : $dadosFornecedor->CNPJ;
        $dadosCGM->nome_fantasia->value      = $dadosFornecedor->NomeFantasia;
        $dadosCGM->razao_social->value       = $dadosFornecedor->RazaoSocial;
        $dadosCGM->nome->value               = $dadosFornecedor->RazaoSocial;
        $dadosCGM->inscricao_estadual->value = $dadosFornecedor->INSCRICAO_ESTADUAL;
        $dadosCGM->logradouro->value         = $dadosFornecedor->Endereco;
        $dadosCGM->cep->value                = preg_replace('/[^0-9]/', "", $dadosFornecedor->CEP);
        $dadosCGM->bairro->value             = $dadosFornecedor->Bairro;
        $dadosCGM->numero->value             = $dadosFornecedor->Numero;
        $dadosCGM->municipio->value          = $dadosFornecedor->Cidade;
        $dadosCGM->complemento->value        = $dadosFornecedor->Complemento;
        $dadosCGM->telefone->value           = preg_replace('/[^0-9]/', "", $dadosFornecedor->Telefone);
        $dadosCGM->estado->value             = $dadosFornecedor->UF;
        $cgmFornecedor                       = $cgm->processaDadosCgm($dadosCGM);

        db_query("select fc_delsession('DB_habilita_trigger_endereco')");
        $numCgmFornecedor = $cgmFornecedor->getCodigo();
        $sqlAlteraCgm  = "
            UPDATE cgm
            SET z01_numcgm = {$numCgmFornecedor}
            WHERE z01_numcgm = {$numCgmFornecedor}
        ";

        if (!db_query($sqlAlteraCgm)) {
            throw new Exception("Erro ao atualizar cgm", 1);
        }

        $pcorcamforne->pc21_codorc           = $codigoOrcamento;
        $pcorcamforne->pc21_numcgm           = $cgmFornecedor->getCodigo();
        $pcorcamforne->pc21_importado        = "true";
        $pcorcamforne->incluir(null);
        $erro_msg = $pcorcamforne->erro_msg;
        if ($pcorcamforne->erro_status == 0) {
            throw new Exception($erro_msg);
        }

        $pcorcamfornelic->pc31_orcamforne           = $pcorcamforne->pc21_orcamforne;
        $pcorcamfornelic->pc31_nomeretira           = null;
        $pcorcamfornelic->pc31_dtretira             = date('Y-m-d');
        $pcorcamfornelic->pc31_horaretira           = date('H:i');
        $pcorcamfornelic->pc31_liclicitatipoempresa = $dadosFornecedor->DeclaracaoME ? 2 : 1;
        $pcorcamfornelic->pc31_tipocondicao         = 1;
        $pcorcamfornelic->incluir($pcorcamforne->pc21_orcamforne);
        $erro_msg = $pcorcamfornelic->erro_msg;
        if ($pcorcamfornelic->erro_status == 0) {
            throw new Exception($erro_msg);
        }

        $this->codigoFornecedor                    = $pcorcamforne->pc21_orcamforne;
        $this->cpfcnpj                             = $dadosCGM->cpf_cnpj;
    }

    public function getFornecedor()
    {
        return $this->codigoFornecedor;
    }

    public function getCpfCnpj()
    {
        return $this->cpfcnpj;
    }
}
