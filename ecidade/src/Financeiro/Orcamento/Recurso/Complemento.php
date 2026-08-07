<?php


namespace ECidade\Financeiro\Orcamento\Recurso;

/**
 * Class Especificacao
 *
 * @package ECidade\Financeiro\Orcamento\Recurso
 */
class Complemento
{

    private static $itens = [];

    public static function getAll()
    {
        $dao = new \cl_complementofonterecurso();
        $busca = $dao->sql_query_file(null, "*", 2);
        $resBusca = db_query($busca);
        $totalRegistros = pg_num_rows($resBusca);
        self::$itens = [];
        if ($totalRegistros > 0) {
            for ($row = 0; $row < $totalRegistros; $row++) {
                $stdDados = \db_utils::fieldsMemory($resBusca, $row);
                $descricao = "{$stdDados->o200_sequencial} - {$stdDados->o200_descricao}";
                self::$itens[$stdDados->o200_sequencial] = $descricao;
            }
        }
        return self::$itens;
    }

    public static function getById($id)
    {
        $itens = self::getAll();
        if (!array_key_exists($id, $itens)) {
            return false;
        }
        return $itens[$id];
    }
}
