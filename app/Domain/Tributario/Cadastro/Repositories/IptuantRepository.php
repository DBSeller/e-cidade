<?php

namespace App\Domain\Tributario\Cadastro\Repositories;

use App\Domain\Tributario\Cadastro\Models\Iptuant;

class IptuantRepository
{
    /**
     * @var Iptuant
     */
    private $iptuant;

    public function __construct()
    {
        $this->iptuant = new Iptuant();
    }

    /**
     * Busca os dados com base na matricula
     * @param $matric
     * @return Iptuant|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function getByMatric($matric)
    {
        return $this->iptuant->where(
            "j40_matric",
            "=",
            $matric
        )->first();
    }
}
