<?php

namespace ECidade\Patrimonial\Compras\Repository;

use cl_pccampanhapublicitaria;
use cl_pcmater;
use ECidade\Patrimonial\Compras\Model\CampanhaPublicitaria;
use Exception;

/**
 * Class CampanhaPublicitariaRepository
 * @package Ecidade\Patrimonial\Compras\Repository
 */
class CampanhaPublicitariaRepository
{


    public static function find($pcmater)
    {
        $dao = new cl_pccampanhapublicitaria();
        $sql = $dao->sql_query(null, "*", null, "pc94_pcmater = {$pcmater}");
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Não foi possível buscar essa campanha publicitária");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return CampanhaPublicitaria::fromState($resultado);
    }

    /**
     * @throws Exception
     */
    public static function save($campanhaPublicitaria)
    {
        $dao = new cl_pccampanhapublicitaria();
        $dao->pc94_cgm = $campanhaPublicitaria->cgm;
        $dao->pc94_valorcampanha = $campanhaPublicitaria->valorCampanha;
        $dao->pc94_datainicio = $campanhaPublicitaria->dataInicio;
        $dao->pc94_datafim = $campanhaPublicitaria->dataFim;
        $dao->pc94_comissaoproducao = $campanhaPublicitaria->comissaoProducao;
        $dao->pc94_comissaoveiculacao = $campanhaPublicitaria->comissaoVeiculacao;
        $dao->pc94_pctipocampanhapublicitaria = $campanhaPublicitaria->tipoCampanha;
        $dao->pc94_pcmater = $campanhaPublicitaria->codigoMater;
        $campanha = CampanhaPublicitariaRepository::find($campanhaPublicitaria->codigoMater);
        if ($campanha) {
            $dao->alterar($campanha->getCodigo());
        } else {
            $dao->incluir();
        }
        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar a campanha publicitária.\nContate o suporte.");
        }
    }
}
