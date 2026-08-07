<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers;

use Illuminate\Support\Facades\DB;

class ConcessaoCalculoProviders
{
    public static function buscarconcessaocaluclo($matricula, $seqassentconf)
    {
        return DB::table('concessaocalculo')
            ->select(
                'rh504_sequencial',
                'rh504_data',
                'rh506_datanova',
                'rh501_perc',
                DB::raw("(CASE 
                    WHEN rh01_admiss  > rh504_data THEN 'Tempo Averbado'
                    ELSE h31_amparolegal
                    END) AS h31_amparolegal")
            )
            ->join('rhpessoal', 'rh01_regist', '=', 'rh504_regist')
            ->join('assentperc', 'rh504_seqassentperc', '=', 'rh501_sequencial')
            ->leftJoin('concessaocalculonovadata', 'rh504_sequencial', '=', 'rh506_concessaocalculo')
            ->leftJoin('concessaoassent', 'rh504_sequencial', '=', 'rh505_concessaocalculo')
            ->leftJoin('portariaassenta', 'h33_assenta', '=', 'rh505_codigo')
            ->leftJoin('portaria', 'h31_sequencial', '=', 'h33_portaria')
            ->where('rh504_regist', $matricula)
            ->where('rh504_seqassentconf', $seqassentconf)
            ->orderBy('rh504_data')
            
            ->get();
    }
}
