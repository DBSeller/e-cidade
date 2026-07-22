<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class Operacoesteftipodebito extends Model
{
    protected $table = "operacoesteftipodebito";

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Operacoesteftipodebito
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getConfiguracoesteftipodebito()
    {
        return $this->configuracoesteftipodebito;
    }

    /**
     * @param int $configuracoesteftipodebito
     * @return Operacoesteftipodebito
     */
    public function setConfiguracoesteftipodebito($configuracoesteftipodebito)
    {
        $this->configuracoesteftipodebito = $configuracoesteftipodebito;
        return $this;
    }

    /**
     * @return int
     */
    public function getOperacoestef()
    {
        return $this->operacoestef;
    }

    /**
     * @param int $operacoestef
     * @return Operacoesteftipodebito
     */
    public function setOperacoestef($operacoestef)
    {
        $this->operacoestef = $operacoestef;
        return $this;
    }
}
