<?php

namespace ECidade\Financeiro\Orcamento\Recurso;

/**
 * Class Grupo
 *
 * @package ECidade\Financeiro\Orcamento\Recurso
 */
class Grupo
{

    private static $itens = array();

    public static function getAll()
    {
        $instituicao = \InstituicaoRepository::getInstituicaoSessao();
        $estado = strtoupper($instituicao->getUf());
        $dao = new \cl_recursogrupo();
        $busca = $dao->sql_query_file(null, "*", 2, "upper(o204_estado) = '{$estado}'");
        $resBusca = db_query($busca);
        $totalRegistros = pg_num_rows($resBusca);
        self::$itens = [];
        if ($totalRegistros > 0) {
            for ($row = 0; $row < $totalRegistros; $row++) {
                $stdDados = \db_utils::fieldsMemory($resBusca, $row);
                $descricao = "{$stdDados->o204_codigo}  - {$stdDados->o204_descricao}";
                self::$itens[$stdDados->o204_codigo] = $descricao;
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
