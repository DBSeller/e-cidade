<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22220EstruturaCertificadosususariosPfx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dicionarioUp();

        Schema::create('configuracoes.certificadosusuarios', function (Blueprint $table) {
            $table->bigIncrements('c142_codigo');
            $table->bigInteger('c142_usuario');
            $table->unsignedBigInteger('c142_arquivopfx');
            $table->date('c142_data');
            $table->date('c142_validade');
            $table->foreign('c142_usuario', 'certificadosusuarios_c142_usuario_fk')
                ->references('id_usuario')
                ->on('db_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dicionarioDown();

        Schema::drop('configuracoes.certificadosusuarios');
    }

    private function dicionarioUp()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo values (1010994, 'certificadosusuarios', 'Guarda vinculo de certificados com usuarios', 'c142', '2022-10-25', 'Certificados PFX', 0, 't', 't', 't', 't');
insert into db_sysarqmod values (7, 1010994);

insert into db_syscampo values(1014562,'c142_codigo','int8','Código sequencial','0', 'Código',11,'f','f','f',1,'text','Código');
insert into db_syscampo values(1014563,'c142_usuario','int8','Vinculo com db_usuarios','0', 'Usuário',11,'f','f','f',1,'text','Usuário');
insert into db_syscampo values(1014564,'c142_arquivopfx','oid','Guarda o código Oid do certificado PFX','', 'Arquivo Certificado PFX',1,'f','f','f',1,'text','Arquivo Certificado PFX');
insert into db_syscampo values(1014565,'c142_data','date','Data de criação do certificado','null', 'Data',10,'f','f','f',1,'text','Data');
insert into db_syscampo values(1014566,'c142_validade','date','Data de validade do certificado','null', 'Validade',10,'f','f','f',1,'text','Validade');

insert into db_sysarqcamp values(1010994,1014562,1,0);
insert into db_sysarqcamp values(1010994,1014563,2,0);
insert into db_sysarqcamp values(1010994,1014564,3,0);
insert into db_sysarqcamp values(1010994,1014565,4,0);
insert into db_sysarqcamp values(1010994,1014566,5,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010994,1014562,1,1014562);

insert into db_sysforkey values(1010994,1014563,1,109,0);
insert into db_sysindices values(1008809,'certificadosusuarios_c142_codigo_pkey',1010994,'0');
insert into db_syscadind values(1008809,1014562,1);
SQL
        );
    }

    private function dicionarioDown()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_syscadind where codind = 1008809;
delete from db_sysindices where codind = 1008809;
delete from db_sysforkey where codarq = 1010994;
delete from db_sysprikey where codarq = 1010994;
delete from db_sysarqcamp where codarq = 1010994;
delete from db_syscampo where codcam in (1014562, 1014563, 1014564, 1014565, 1014566);
delete from db_sysarqmod where codarq = 1010994;
delete from db_sysarquivo where codarq = 1010994;
SQL
        );
    }
}
