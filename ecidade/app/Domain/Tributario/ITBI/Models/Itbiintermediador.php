<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbiintermediador extends Model
{
    protected $table = "itbiintermediador";

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Itbiintermediador
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getItbi()
    {
        return $this->itbi;
    }

    /**
     * @param int $itbi
     * @return Itbiintermediador
     */
    public function setItbi($itbi)
    {
        $this->itbi = $itbi;
        return $this;
    }

    /**
     * @return int
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param int $cgm
     * @return Itbiintermediador
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Itbiintermediador
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getCnpjCpf()
    {
        return $this->cnpj_cpf;
    }

    /**
     * @param string $cnpj_cpf
     * @return Itbiintermediador
     */
    public function setCnpjCpf($cnpj_cpf)
    {
        $this->cnpj_cpf = $cnpj_cpf;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreci()
    {
        return $this->creci;
    }

    /**
     * @param string $creci
     * @return Itbiintermediador
     */
    public function setCreci($creci)
    {
        $this->creci = $creci;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * @param string $principal
     * @return Itbiintermediador
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;
        return $this;
    }
}
