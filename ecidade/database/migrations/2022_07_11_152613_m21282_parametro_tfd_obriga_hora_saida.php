<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21282ParametroTfdObrigaHoraSaida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tfd.tfd_parametros', function (Blueprint $table) {
            $table->boolean('tf11_obriga_hora_saida')->default(true);
        });
        $this->upDicionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tfd.tfd_parametros', function (Blueprint $table) {
            $table->dropColumn('tf11_obriga_hora_saida');
        });
        $this->downDicionario();
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_syscampo values(1014316,'tf11_obriga_hora_saida','bool','NÃO: permiti deixar o campo em branco. SIM: campo é para o preenchimento. ','f', 'Hora Saída obrigatório no Agendamento',1,'f','f','f',5,'text','Hora Saída obrigatório no Agendamento');
            insert into db_sysarqcamp values(2867,1014316,5,0);
            update db_syscampo set nulo = 't' where codcam = 16409;
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            delete from db_sysarqcamp where codcam = 1014316;
            delete from db_syscampo where codcam = 1014316;
            update db_syscampo set nulo = 'f' where codcam = 16409;
SQL
        );
    }
}
