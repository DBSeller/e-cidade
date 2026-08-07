<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Siconfi;

use ECidade\File\Csv\Dumper\Dumper;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Atualizacao\Importacao;

/**
 * Clase para a exportação dos Recursos para o SICONFI
 * @author Augusto  Oliveira <augusto.oliveira@dbseller.com.br>
 * @package ECidade\Financeiro\Contabilidade\Importacao\Siconfi
 *
 */
class TipoRecursos
{
    public function export($ano = null)
    {
        $oDao = new \cl_orctiporec();
        $campos = "distinct o15_recurso, o15_descr,o15_codigosiconfi";
        $sSql = $oDao->sql_query_file(null, $campos, "o15_recurso asc");

        $rs = db_query($sSql);

        if (!$rs) {
            throw new \DBException("Erro ao buscar Tipos de Recursos;");
        }

        if (pg_num_rows($rs) == 0) {
            throw new \DBException("Não foi localizado nenhum Tipo de Recurso.");
        }

        $dados = array();
        $dados[] = array("Código no e-cidade", "Descrição do Recurso", "Código do SICONFI");

        for ($i=0; $i < pg_num_rows($rs); $i++) {
            $dado = array();
            $oStd = \db_utils::fieldsMemory($rs, $i);
            $dado[]  = $oStd->o15_recurso;
            $dado[]  = $oStd->o15_descr;
            $dado[]  = $oStd->o15_codigosiconfi;
            $dados[] = $dado;
        }
        $arquivo = 'tmp/SICONFI_recursos_' . date('Y-m-d_Hi', time()) . '.csv';

        $cvs = new Dumper();
        $cvs->dumpToFile($dados, $arquivo);
        return $arquivo;
    }
}
