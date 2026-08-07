<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M21593ProcedimentoAverbacao extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

        ALTER TABLE cadastro.averbacao ADD column j75_responsavel int4;
        ALTER TABLE cadastro.averbacao
        ADD CONSTRAINT averbacao_j75_responsavel_fk foreign key (j75_responsavel)
                    references db_usuarios (id_usuario);
        insert into db_syscampo values ('1014443', 'j75_responsavel', 'int4',
                                        'responsavel pela realização do procedimento de averbação.', '',
                                        'Servidor Responsável', '10', 'f', 'f', 'f', '1', 'text', 'Servidor Responsável');

        insert into db_sysarqcamp values(1649, 1014443, 9 ,0);
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
            ALTER TABLE cadastro.averbacao DROP COLUMN j75_responsavel;
            delete from db_sysarqcamp where codcam = '1014443';
            delete from db_syscampo where codcam = '1014443';        
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
