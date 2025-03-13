<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20547AdicionandoCampoQ60PortepadraomeiTabelaParissqn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

insert into db_syscampo values(1014162,'q60_portepadraomei','int8','Porte padrão utilizado durante o processamento de competência do ISSQN MEI','0', 'Porte Padrão MEI',10,'f','f','f',1,'text','Porte Padrão MEI');
insert into db_sysarqcamp values(664,1014162,33,0);
insert into db_sysforkey values(664,1014162,1,1047,0);

alter table issqn.parissqn add column q60_portepadraomei bigint;

alter table issqn.parissqn add constraint parissqn_codporte_fk FOREIGN KEY (q60_portepadraomei)
references issqn.issporte;

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

delete from db_sysforkey where codarq = 664 and codcam = 1014162;
delete from db_sysarqcamp where codarq = 664 and codcam = 1014162;
delete from db_syscampo where codcam = 1014162;

alter table issqn.parissqn drop constraint parissqn_codporte_fk;
alter table issqn.parissqn drop column q60_portepadraomei;

SQL
        );

    }
}
