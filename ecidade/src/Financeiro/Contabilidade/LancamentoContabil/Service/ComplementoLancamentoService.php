<?php


namespace ECidade\Financeiro\Contabilidade\LancamentoContabil\Service;

use cl_conlancamcompl;
use db_utils;

/**
 * Class ComplementoLancamentoService
 *
 * Essa classe surgiu para resolver um problema de lançamentos que não geram o complemento do lançamento.
 *
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil\Service
 */
class ComplementoLancamentoService
{
    public static function createIfNotExists($codigoLancamento)
    {
        $dao = new cl_conlancamcompl();
        $rs = db_query($dao->sql_query_file(null, '*', null, "c72_codlan = $codigoLancamento"));
        if ($rs && pg_num_rows($rs) > 0) {
            return;
        }

        $sql = "
            select c70_codlan,
                   c71_coddoc,
                   c53_descr,
                   c70_data
              from conlancam
              inner join conlancamdoc   on c70_codlan = c71_codlan
              inner join conhistdoc     on c71_coddoc = c53_coddoc
             where c70_codlan = {$codigoLancamento}
        ";
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar documento.");
        }

        $incluir = db_utils::fieldsMemory($rs, 0);
        $dao->c72_codlan = $codigoLancamento;
        $dao->c72_complem = sprintf(
            "Referente ao lançamento contábil realizado no evento %s - %s em %s",
            $incluir->c71_coddoc,
            $incluir->c53_descr,
            $incluir->c70_data
        );
        $dao->incluir($dao->c72_codlan);

        if ($dao->erro_status == "0") {
            throw new Exception(sprintf(
                "Erro ao Incluir Complemento para o Lançamento: %s.\n%s",
                $dao->c72_codlan,
                $dao->erro_msg
            ));
        }
    }
}
