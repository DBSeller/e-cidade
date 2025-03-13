<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers;

use Illuminate\Support\Facades\DB;

class ConcessaoCalculo
{
    //concessao para gerar portaria
    public static function concessao($matricula, $rh504_seqassentconf, $datainicio, $datafinal, array $assentamentos)
    {
        if (!empty($assentamentos)) {
            $where[] = "exists (
                select
                    1
                from
                    recursoshumanos.assenta
                where
                    h16_regist = rh01_regist
                and h16_assent in (" . implode(",", $assentamentos) . ")
                and '$datafinal' > h16_dtconc
            )";
        }

        if (!empty($matricula)) {
            $where[] = "rh504_regist = $matricula";
        }

        $where[] = "rh01_admiss < rh504_data";
        $where[] = "rh504_data between '$datainicio' and '$datafinal'";
        $where[] = "not exists (
            select 
                1 
            from 
                concessaoassent where concessaocalculo.rh504_sequencial = concessaoassent.rh505_concessaocalculo)";
        $where[] = "rh504_seqassentconf = $rh504_seqassentconf";

        $sql = "
        select
        concessaocalculo.*,assentperc.*
        from
            recursoshumanos.concessaocalculo
        inner join recursoshumanos.assentperc on
            rh501_sequencial = rh504_seqassentperc
            inner join rhpessoal on
            rh01_regist  = rh504_regist
            inner join cgm on
            rh01_numcgm  = z01_numcgm
            inner join recursoshumanos.assentconf on rh504_seqassentconf = rh500_sequencial
        where
            " . implode(" and ", $where) . "
            order by z01_nome,rh504_data";

        return DB::select(DB::raw($sql));
    }
}
