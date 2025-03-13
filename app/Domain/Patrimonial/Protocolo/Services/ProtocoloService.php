<?php

namespace App\Domain\Patrimonial\Protocolo\Services;

use App\Domain\Patrimonial\Protocolo\Repository\CadenderruacepRepository;

class ProtocoloService
{
    private $cadenderruacepRepository;

    public function __construct()
    {
        $this->cadenderruacepRepository = new CadenderruacepRepository();
    }

    public function getRuaByCep($cep)
    {
        $oRuaCep = $this->cadenderruacepRepository->getByCep($cep, [
            "cadenderruacep.db86_cep",
            "cadenderestado.db71_sigla",
            "cadendermunicipio.db72_descricao",
            "cadenderbairro.db73_descricao",
            "cadenderrua.db74_descricao",
            "ruastipo.j88_sigla"
        ]);

        if (empty($oRuaCep->db86_cep)) {
            throw new \Exception("Não foi encontrado rua para o CEP: {$cep}");
        }

        return [
            "cep" => $oRuaCep->db86_cep,
            "endereco" => "{$oRuaCep->j88_sigla}. {$oRuaCep->db74_descricao}",
            "bairro" => $oRuaCep->db73_descricao,
            "municipio" => $oRuaCep->db72_descricao,
            "uf" => $oRuaCep->db71_sigla
        ];
    }

    public function getRuaByCepMunicipio($cep)
    {
        $oRuaCep = $this->cadenderruacepRepository->getByCepMunicipio($cep, [
            "cadenderruacep.db86_cep",
            "cadenderestado.db71_sigla",
            "cadendermunicipio.db72_descricao",
            "cadenderbairro.db73_descricao",
            "cadenderrua.db74_descricao",
            "ruastipo.j88_sigla"
        ]);

        if (empty($oRuaCep->db86_cep)) {
            throw new \Exception("Não foi encontrado rua para o CEP: {$cep}");
        }

        return [
            "cep" => $oRuaCep->db86_cep,
            "endereco" => "{$oRuaCep->j88_sigla}. {$oRuaCep->db74_descricao}",
            "bairro" => $oRuaCep->db73_descricao,
            "municipio" => $oRuaCep->db72_descricao,
            "uf" => $oRuaCep->db71_sigla
        ];
    }
}
