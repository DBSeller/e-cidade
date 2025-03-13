<?php


namespace ECidade\Financeiro\Orcamento\Registry;

use ECidade\Financeiro\Orcamento\Model\Especificacao;
use ECidade\Financeiro\Orcamento\Repository\EspecificacaoRepository;
use Exception;

/**
 * Class EspecificacaoRegistry
 * @package ECidade\Financeiro\Orcamento\Registry
 */
class EspecificacaoRegistry
{
    /**
     * @var Especificacao[]
     */
    private static $storage = array();

    /**
     * @param Especificacao $especificacao
     */
    public static function set(Especificacao $especificacao)
    {
        self::$storage[$especificacao->getId()] = $especificacao;
    }

    /**
     * @param $key
     * @return Especificacao|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $especificacao = EspecificacaoRepository::find($key);
            if (is_null($especificacao)) {
                return null;
            }

            self::set($especificacao);
        }

        return self::$storage[$key];
    }
}
