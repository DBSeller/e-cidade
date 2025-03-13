<?php

namespace ECidade\Financeiro\Orcamento\Recurso;

/**
 * Class Especificacao
 *
 * @package ECidade\Financeiro\Orcamento\Recurso
 */
class Especificacao
{

    private static $itens = [];

    public static function getAll()
    {
        /*
        if (FONTE_RECURSO_UNIAO === true and FONTE_RECURSO_2020 === false) {
            $instituicao = \InstituicaoRepository::getInstituicaoSessao();
            $estado = strtoupper($instituicao->getUf());
            $dao = new \cl_recursoespecificacao();
            $busca = $dao->sql_query_file(null, "*", 2, "upper(o205_estado) = '{$estado}'");
            $resBusca = db_query($busca);
            $totalRegistros = pg_num_rows($resBusca);
            self::$itens = [];
            if ($totalRegistros > 0) {
                for ($row = 0; $row < $totalRegistros; $row++) {
                    $stdDados = \db_utils::fieldsMemory($resBusca, $row);
                    $descricao = "{$stdDados->o205_codigo}  - {$stdDados->o205_descricao}";
                    self::$itens[$stdDados->o205_codigo] = $descricao;
                }
            }
        }
        */

        if (FONTE_RECURSO_UNIAO === true and FONTE_RECURSO_2020 === true || 1 == 1) {
            $dao = new \cl_orctiporec();
            $sWhere  = " o15_codigo != 0 ";
            $campos = "distinct on (o15_recurso) o15_recurso, o15_descr";
            $busca = $dao->sql_query_file(null, $campos, 1, $sWhere);
            $resBusca = db_query($busca);
            $totalRegistros = pg_num_rows($resBusca);
            self::$itens = [];
            if ($totalRegistros > 0) {
                for ($row = 0; $row < $totalRegistros; $row++) {
                    $stdDados = \db_utils::fieldsMemory($resBusca, $row);
                    $descricao = "{$stdDados->o15_recurso}  - {$stdDados->o15_descr}";
                    self::$itens[$stdDados->o15_recurso] = $descricao;
                }
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
