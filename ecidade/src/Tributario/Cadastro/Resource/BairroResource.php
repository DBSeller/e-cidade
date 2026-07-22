<?php


namespace ECidade\Tributario\Cadastro\Resource;

use ECidade\Tributario\Cadastro\Model\Bairro;
use stdClass;

/**
 * Class BairroResource
 * @package ECidade\Educacao\Escola\Resource
 */
class BairroResource
{
    /**
     * @param Bairro[] $bairros
     * @return stdClass[]
     */
    public static function toAttay(array $bairros)
    {
        $dados = [];
        foreach ($bairros as $bairro) {
            $dados[] = self::toObject($bairro);
        }

        return $dados;
    }

    /**
     * @param Bairro $bairro
     * @return stdClass
     */
    public static function toObject(Bairro $bairro)
    {
        return (object) [
            'codigo' => $bairro->getCodigo(),
            'nome' => $bairro->getNome(),
        ];
    }
}
