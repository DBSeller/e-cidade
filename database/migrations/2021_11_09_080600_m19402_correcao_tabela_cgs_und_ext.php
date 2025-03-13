<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19402CorrecaoTabelaCgsUndExt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('drop index if exists cgs_und_ext_i_cgsund_in;');

        $repetidos = DB::table('ambulatorial.cgs_und_ext')
            ->select('z01_i_cgsund')
            ->havingRaw('count(z01_i_cgsund) > 1')
            ->groupBy('z01_i_cgsund')
            ->get();

        foreach ($repetidos as $cgs) {
            $this->apagaRepetido($cgs->z01_i_cgsund);
        }

        DB::statement('create unique index cgs_und_ext_i_cgsund_in on cgs_und_ext (z01_i_cgsund);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('drop index cgs_und_ext_i_cgsund_in;');
    }

    private function apagaRepetido($idCgs) 
    {
        $registros = DB::table('ambulatorial.cgs_und_ext')
            ->where('z01_i_cgsund', '=', $idCgs)
            ->orderByRaw('z01_i_id desc')
            ->get();
        
        $registros->shift();

        foreach ($registros as $cgs) {
            DB::table('ambulatorial.cgs_und_ext')
                ->where('z01_i_id', '=', $cgs->z01_i_id)
                ->delete();
        }
    }
}
