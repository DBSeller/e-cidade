<?php


namespace ECidade\Tributario\Cadastro\Resource;

use ECidade\Tributario\Cadastro\Model\Rua;
use stdClass;

/**
 * Class RuaResource
 * @package ECidade\Tributario\Cadastro\Resource
 */
class RuaResource
{
    /**
     * @param Rua[] $ruas
     * @return stdClass[]
     */
    public static function toAttay(array $ruas)
    {
        $dados = [];
        foreach ($ruas as $rua) {
            $dados[] = self::toObject($rua);
        }

        return $dados;
    }

    /**
     * @param Rua $rua
     * @return stdClass
     */
    public static function toObject(Rua $rua)
    {
        return (object) [
            'codigo' => $rua->getCodigo(),
            'nome' => $rua->getNome(),
        ];
    }
}
