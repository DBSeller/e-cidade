<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21394AddCampoObservacaoTfdAgendamentoprestadora extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tfd.tfd_agendamentoprestadora', function (Blueprint $table) {
            $table->string('tf16_observacoes', 60)->nullable();
        });

        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_syscampo values(1014416,'tf16_observacoes','varchar(60)','Observações do agendamento/consulta.','', 'Observações',60,'t','f','f',0,'text','Observações');
            insert into db_sysarqcamp values(2872,1014416,16,0);
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tfd.tfd_agendamentoprestadora', function (Blueprint $table) {
            $table->dropColumn('tf16_observacoes');
        });

        DB::connection()->getPdo()->exec(<<<SQL
            delete from db_sysarqcamp where codcam = 1014416;
            delete from db_syscampo where codcam = 1014416;
SQL
        );
    }
}
