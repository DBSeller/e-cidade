<?php

namespace App\Domain\Tributario\Cadastro\Traits;

use App\Domain\Tributario\Cadastro\Models\Ruas;

trait Logradouro
{
    public function getLogradouro()
    {
        $ruas = new Ruas();

        return $ruas->join(
            "ruastipo",
            "j88_codigo",
            "j14_tipo"
        )->join(
            "ruasbairro",
            "j16_lograd",
            "j14_codigo"
        )->join(
            "bairro",
            "j13_codi",
            "j16_bairro"
        )->join(
            "ruascep",
            "j29_codigo",
            "j14_codigo"
        );
    }
}
