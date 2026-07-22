<?php

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamrel;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use Exception;

/**
 * Class RelatorioRepositorio
 * @package ECidade\Configuracao\RelatorioLegal\Repositorio
 */
class RelatorioRepositorio extends Repositorio
{
    /**
     * @param int $id
     * @param array $columns
     * @return bool|Relatorio
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_orcparamrel();
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o relatório.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $result = pg_fetch_array($rs);

        return Relatorio::fromState($result);
    }

    /**
     * @param Relatorio $relatorio
     * @return Relatorio
     * @throws Exception
     */
    public function import(Relatorio $relatorio)
    {
        $dao = new cl_orcparamrel();

        $dao->o42_codparrel = $relatorio->getSequencial();
        $dao->o42_descrrel = $relatorio->getDescricao();
        $dao->o42_orcparamrelgrupo = $relatorio->getGrupo();
        $dao->o42_notapadrao = $relatorio->getNotaPadrao();

        RelatorioRegistry::get($relatorio->getSequencial())
            ? $dao->alterar($relatorio->getSequencial())
            : $dao->incluir($relatorio->getSequencial());

        if ($dao->erro_status === '0') {
            throw new Exception($dao->erro_msg);
        }

        return $relatorio;
    }

    /**
     * @return int
     * @throws Exception
     */
    public static function nextval()
    {
        $sql = "
            SELECT max(o42_codparrel) + 1 AS sequencial
            FROM orcparamrel
            WHERE o42_codparrel < 99999;
        ";
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o próximo sequencial.");
        }

        return pg_fetch_object($rs)->sequencial;
    }

    /**
     * @param Relatorio|null $relatorio
     * @throws Exception
     */
    public function delete(Relatorio $relatorio = null)
    {
        $id = $relatorio instanceof Relatorio ? $relatorio->getSequencial() : null;

        $dao = new cl_orcparamrel();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o relatório.\nContate o suporte.\n{$dao->erro_banco}");
        }
    }
}
