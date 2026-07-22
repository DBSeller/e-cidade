<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Setorregimovel;

class SetorregimovelRepository
{
    private $setorregimovel;

    public function __construct()
    {
        $this->setorregimovel = new Setorregimovel();
    }

    public function get()
    {
        return $this->setorregimovel->get();
    }
}
