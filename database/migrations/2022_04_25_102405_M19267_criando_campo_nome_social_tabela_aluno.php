<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19267CriandoCampoNomeSocialTabelaAluno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_syscampo values
                (1014008,'ed47_v_nomesocial','varchar(70)','Nome Social','null', 
                'Nome Social',70,'t','t','f',0,'text','Nome Social');
            insert into db_sysarqcamp values(1010051,1014008,3,0);

            alter table aluno add column ed47_v_nomesocial varchar(70) default null;
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
            delete from db_sysarqcamp where codcam = 1014008;;
            delete from db_syscampo where codcam = 1014008;

            alter table aluno drop column ed47_v_nomesocial;
SQL
    );
    }
}
