<?php

namespace ECidade\Tributario\Divida\Registry;

use ECidade\Tributario\Caixa\Model\Arrepaga;
use ECidade\Tributario\Divida\Model\Disbanco;
use ECidade\Tributario\Divida\Repository\DisbancoRepository;
use Exception;

class DisbancoRegistry
{
    private static $instance;

    private $registry;

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->registry = array();
    }

    private function key(Arrepaga $arrepaga)
    {
        return $arrepaga->getNumpre() . " || " . $arrepaga->getNumeroParcela();
    }

    /**
     * @param Arrepaga $arrepaga
     * @return Disbanco|null
     * @throws Exception
     */
    public function disbancoPorArrepaga(Arrepaga $arrepaga)
    {
        $key = $this->key($arrepaga);

        if (empty($this->registry[$key])) {
            $this->registry[$key] = DisbancoRepository::getInstance()->disbancoPorArrepaga($arrepaga);
        }

        return $this->registry[$key];
    }
}
