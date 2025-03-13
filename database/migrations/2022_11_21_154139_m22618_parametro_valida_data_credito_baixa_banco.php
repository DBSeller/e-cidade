<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22618ParametroValidaDataCreditoBaixaBanco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

alter table caiparametro add COLUMN k29_validadatacreditobaixabanco boolean default false;
insert into db_syscampo values(1014618,'k29_validadatacreditobaixabanco','bool','Validar data credito da baixa de banco','f', ' Validar data credito da baixa de banco',1,'f','f','f',5,'text',' Validar data credito da baixa de banco');
insert into db_sysarqcamp values(1503,1014618,15,0);


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

delete from db_sysarqcamp where codcam = 1014618;
delete from db_syscampo where codcam = 1014618;
alter table caiparametro drop COLUMN k29_validadatacreditobaixabanco;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
