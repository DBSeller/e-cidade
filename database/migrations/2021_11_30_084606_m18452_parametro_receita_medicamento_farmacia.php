<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class M18452ParametroReceitaMedicamentoFarmacia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("insert into db_syscampo values(1013491,'fa02_informa_receita_medicamento','bool','Parâmetro responsável pela exibição do tipo de receita do medicamento!','f', 'Informa tipo de receita do medicamento',1,'f','f','f',5,'text','Informa tipo de receita do medicamento');");
        DB::statement("insert into db_sysarqcamp values(2103,1013491,20,0);");

        Schema::table('farmacia.far_parametros', function(Blueprint $table) {
            $table->boolean('fa02_informa_receita_medicamento')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('configuracoes.db_sysarqcamp')->where('codcam', '=', '1013491')->delete();
        DB::table('configuracoes.db_syscampo')->where('codcam', '=', '1013491')->delete();
        
        Schema::table('farmacia.far_parametros', function(Blueprint $table) {
            $table->dropColumn(['fa02_informa_receita_medicamento']);
        });
    }
}
