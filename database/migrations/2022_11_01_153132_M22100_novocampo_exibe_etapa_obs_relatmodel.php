<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22100NovocampoExibeEtapaObsRelatmodel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        ALTER TABLE
        edu_relatmodel
    ADD
        COLUMN ed217_exibir_etapa_obs BOOLEAN NOT NULL DEFAULT true;

    insert into db_syscampo values(1014576,'ed217_exibir_etapa_obs','bool','Flag que define se a Etapa deve aparecer ou não no cadastro de relatórios da Secretaria no módulo Educação','1', 'Exibir Etapa na Observação',1,'f','f','f',5,'text','Exibir Etapa na Observação');

    insert into db_sysarqcamp values(2571,1014576,19,0);
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
        DB::connection()->getPdo()->exec(<<<SQL
    ALTER TABLE edu_relatmodel DROP COLUMN ed217_exibir_etapa_obs;

    delete from db_sysarqcamp where codcam = 1014576;
    
    delete from db_syscampo where codcam = 1014576;
SQL
    );

    }
}
