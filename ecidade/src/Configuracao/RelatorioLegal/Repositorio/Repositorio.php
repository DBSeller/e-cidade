<?php

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

abstract class Repositorio
{
    /**
     * @var array
     */
    protected $scopes = [];

    /**
     * @return $this
     */
    public function resetScopes()
    {
        $this->scopes = [];

        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }
}
