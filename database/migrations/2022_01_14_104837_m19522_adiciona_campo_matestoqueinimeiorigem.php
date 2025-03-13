<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19522AdicionaCampoMatestoqueinimeiorigem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Dicionario de dados
         */
        DB::connection()->getPdo()->exec(<<<sql
            insert into db_syscampo values(1013609,'m82_matestoqueinimeiorigem','int8','Guarda vinculo de movimentação que gerou essa movimentação no caso de devoluções','0', 'Movimentação de Origem',10,'t','f','f',1,'text','Movimentação de Origem');
            insert into db_sysarqcamp values(1135,1013609,5,0);
            insert into db_sysforkey values(1135,1013609,1,1135,0);
sql
        );

        /**
         * Cria estrutura
         */
        DB::connection()->getPdo()->exec(<<<sql
            alter table material.matestoqueinimei add column m82_matestoqueinimeiorigem int8;
            alter table material.matestoqueinimei
            add constraint matestoqueinimei_m82_matestoqueinimeiorigem_fk
                foreign key (m82_matestoqueinimeiorigem)
                references material.matestoqueinimei (m82_codigo);
sql
        );

        /**
         * Faz vinculo com dados anteriores a alteração
         */
        DB::connection()->getPdo()->exec(<<<sql
            alter table material.matestoqueinimei disable trigger all;

            with vinculo_devolucoes as (
                select ma.m82_codigo       as inimei_atendimento,
                       md.m82_codigo       as inimei_devolucao
                from matestoquedev
                         join matestoquedevitem on m46_codmatestoquedev = m45_codigo
                         join atendrequiitem on m43_codigo = m46_codatendrequiitem
                         join matrequiitem on m43_codmatrequiitem = m41_codigo
                         join matrequi on m41_codmatrequi = m40_codigo
                         join matestoqueinimeimdi on m50_codmatestoquedevitem = m46_codigo
                         join matestoqueinimei md on m50_codmatestoqueinimei = md.m82_codigo
                         join atendrequiitemmei on m44_codatendreqitem   = m43_codigo
                                        and m44_codmatestoqueitem = md.m82_matestoqueitem
                         join matestoqueinimei ma on m44_codmatestoqueitem = ma.m82_matestoqueitem
                         join matestoqueinimeiari on m49_codatendrequiitem   = m43_codigo
                                        and m49_codmatestoqueinimei = ma.m82_codigo
                         join matestoqueinimeipm pmd on pmd.m89_matestoqueinimei = md.m82_codigo
                         join matestoqueinimeipm pma on pma.m89_matestoqueinimei = ma.m82_codigo
            ) update matestoqueinimei set m82_matestoqueinimeiorigem = inimei_atendimento
                from vinculo_devolucoes where m82_codigo = inimei_devolucao;

            alter table material.matestoqueinimei enable trigger all;
sql
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
            delete from db_sysforkey where codcam = 1013609;
            delete from db_sysarqcamp where codcam = 1013609;
            delete from db_syscampo where codcam = 1013609;
            alter table material.matestoqueinimei drop column m82_matestoqueinimeiorigem;
sql
        );
    }
}
