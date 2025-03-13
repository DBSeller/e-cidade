<?php

namespace ECidade\Financeiro\Orcamento\Recurso;

/**
 * Class TipoDetalhamento
 *
 * @package ECidade\Financeiro\Orcamento\Recurso
 */
class TipoDetalhamento
{

    private static $itens = array();

    public static function getAll()
    {
        $instituicao = \InstituicaoRepository::getInstituicaoSessao();
        $estado = strtoupper($instituicao->getUf());
        $dao = new \cl_recursodetalhamento();
        $busca = $dao->sql_query_file(null, "*", 2, "upper(o203_estado) = '{$estado}'");
        $resBusca = db_query($busca);
        $totalRegistros = pg_num_rows($resBusca);
        self::$itens = [];
        if ($totalRegistros > 0) {
            for ($row = 0; $row < $totalRegistros; $row++) {
                $stdDados = \db_utils::fieldsMemory($resBusca, $row);
                $descricao = "{$stdDados->o203_codigo}  - {$stdDados->o203_descricao}";
                self::$itens[$stdDados->o203_codigo] = $descricao;
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
