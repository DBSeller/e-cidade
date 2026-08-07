<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19613CorrecaoRegistroAulas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $registrosErrados = DB::select("SELECT 
                ed155_codigo,
                ed155_regencia, 
                turmaturnoreferente2.ed336_codigo as turno_certo, 
                ed155_conteudo, 
                ed155_data
            from escola.diario_classe_bncc
            join regencia ON regencia.ed59_i_codigo = diario_classe_bncc.ed155_regencia
            join turmaturnoreferente ON turmaturnoreferente.ed336_codigo = diario_classe_bncc.ed155_turmaturnoreferente
            join escola.turmaturnoreferente as turmaturnoreferente2 ON ed59_i_turma = turmaturnoreferente2.ed336_turma
                and turmaturnoreferente2.ed336_turnoreferente = turmaturnoreferente.ed336_turnoreferente
            where ed155_turmaturnoreferente is not null
                and turmaturnoreferente.ed336_turma <> ed59_i_turma;");

        foreach ($registrosErrados as $registroErrado) {
            $diarioClasse = DB::table('escola.diario_classe_bncc')
                ->where('ed155_regencia', $registroErrado->ed155_regencia)
                ->where('ed155_turmaturnoreferente', $registroErrado->turno_certo)
                ->where('ed155_data', $registroErrado->ed155_data)
                ->first();

            if (count($diarioClasse) > 0) {
                $diarioClasse->ed155_conteudo .= "\n{$registroErrado->ed155_conteudo}";
                $conteudo = pg_escape_string($diarioClasse->ed155_conteudo);

                DB::statement("UPDATE diario_classe_bncc set ed155_conteudo = '{$conteudo}' where ed155_codigo = {$diarioClasse->ed155_codigo}");
                DB::statement("DELETE FROM diario_classe_bncc where ed155_codigo = {$registroErrado->ed155_codigo}");

                continue;
            }

            DB::statement("UPDATE diario_classe_bncc set ed155_turmaturnoreferente = {$registroErrado->turno_certo} where ed155_codigo = {$registroErrado->ed155_codigo}");
        }        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return true;
    }
}
