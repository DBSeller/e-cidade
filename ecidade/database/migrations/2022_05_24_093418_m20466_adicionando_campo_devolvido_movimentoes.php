<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20466AdicionandoCampoDevolvidoMovimentoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
insert into db_syscampo values(1014160,'p117_devolucao','bool','Se a execução da atividade é uma devolução para antividade anterior','f', 'Devolucao',1,'f','f','f',5,'text','Devolucao');
insert into db_syscampo values(1014165,'p117_invalida','bool','Significa que a movimentação é inválida, em algum momento a atividade executada foi devolvida invalidando a atividade anterior.','f', 'Inválida',1,'f','f','f',5,'text','Inválida');
insert into db_sysarqcamp values(1010904,1014160,7,0);
insert into db_sysarqcamp values(1010904,1014165,8,0);

alter table documentos_movimentacao add column p117_devolucao boolean default false;
alter table documentos_movimentacao add column p117_invalida boolean default false;
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
delete from db_sysarqcamp where codcam in(1014160, 1014165);
delete from db_syscampo where codcam in(1014160, 1014165);

alter table documentos_movimentacao drop column p117_devolucao;
alter table documentos_movimentacao drop column p117_invalida;
sql
        );
    }
}
