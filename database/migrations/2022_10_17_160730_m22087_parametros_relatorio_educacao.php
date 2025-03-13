<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22087ParametrosRelatorioEducacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into db_syscampo values(1014548,'ed217_exibirmantenedora','bool','Exibir mantenedora no cabeçalho do Histórico Escolar','t', 'Exibir Mantenedora',1,'f','f','f',5,'text','Exibir Mantenedora');
insert into db_syscampo values(1014549,'ed217_exibirdistrito','bool','Exibir Distrito no cabeçalho do Histórico Escolar','t', 'Exibir Distrito',1,'f','f','f',5,'text','Exibir Distrito');
insert into db_syscampo values(1014554,'ed217_exibirperiodo','bool','Exibir Coluna Período no Histórico Escolar','t', 'Exibir Coluna Período',1,'f','f','f',5,'text','Exibir Coluna Período');
insert into db_sysarqcamp values(2571,1014548,16,0);
insert into db_sysarqcamp values(2571,1014549,17,0);
insert into db_sysarqcamp values(2571,1014554,18,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
        Schema::table('secretariadeeducacao.edu_relatmodel', function (Blueprint $table) {
            $table->boolean('ed217_exibirmantenedora')->default(true);
            $table->boolean('ed217_exibirdistrito')->default(true);
            $table->boolean('ed217_exibirperiodo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
delete from db_sysarqcamp where codcam in (1014548, 1014549, 1014554);
delete from db_syscampo where codcam in (1014548, 1014549, 1014554);
SQL;
        DB::connection()->getPdo()->exec($sql);

        Schema::table('secretariadeeducacao.edu_relatmodel', function (Blueprint $table) {
            $table->dropColumn('ed217_exibirmantenedora');
            $table->dropColumn('ed217_exibirdistrito');
            $table->dropColumn('ed217_exibirperiodo');
        });
    }
}
