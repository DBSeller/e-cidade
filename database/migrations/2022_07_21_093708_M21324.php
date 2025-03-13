<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21324 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        insert into db_syscampo values(1014380,'p01_assinado','bool','Define se o documento foi assinado.','f', 'Assinado',1,'t','f','f',5,'text','Assinado');
        insert into db_syscampo values(1014381,'p01_assinado_por','int8','Usuário responsável pela assinatura','0', 'Assinado Por',127,'t','f','f',1,'text','Assinado Por');
        insert into db_sysarqcamp values(3649,1014381,11,0);
        insert into db_sysarqcamp values(3649,1014380,12,0);
        alter table protocolo.protprocessodocumento add column p01_assinado boolean default false;
        alter table protocolo.protprocessodocumento add column p01_assinado_por bigint;
        ALTER TABLE protocolo.protprocessodocumento
        ADD CONSTRAINT protprocessodocumento_db_usuarios_fk
        FOREIGN KEY (p01_assinado_por)
        REFERENCES configuracoes.db_usuarios (id_usuario);

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
        ALTER TABLE protocolo.protprocessodocumento
        DROP CONSTRAINT protprocessodocumento_db_usuarios_fk;
        ALTER TABLE protocolo.protprocessodocumento drop column p01_assinado;
        ALTER TABLE protocolo.protprocessodocumento drop column p01_assinado_por;
        DELETE FROM db_sysarqcamp WHERE codcam =  1014381;
        DELETE FROM db_sysarqcamp WHERE codcam =  1014380;
        DELETE FROM db_syscampo WHERE codcam = 1014380;
        DELETE FROM db_syscampo WHERE codcam = 1014381;
SQL
        );
    }
}
