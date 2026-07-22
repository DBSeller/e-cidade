<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Localidaderural;

class LocalidaderuralRepository
{
    private $localidaderural;

    public function __construct()
    {
        $this->localidaderural = new Localidaderural();
    }

    public function getAll()
    {
        return $this->localidaderural->get();
    }
}
