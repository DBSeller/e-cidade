<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22648NovoscamposRelatmodel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

insert into db_syscampo values(1014613,'ed217_exibircertidao','bool','Exibir informações da certidao de nascimento','f', 'Exibir informações da certidao de nasc.',1,'f','f','f',5,'text','Exibir informações da certidao de nasc.');
insert into db_syscampodef values(1014613,'f','Não');
insert into db_syscampodef values(1014613,'t','Sim');
insert into db_syscampo values(1014614,'ed217_exibiridentidade','bool','Exibir informações do RG','f', 'Exibir informações do RG',1,'f','f','f',5,'text','Exibir informações do RG');
insert into db_syscampodef values(1014614,'f','Não');
insert into db_syscampodef values(1014614,'t','Sim');
insert into db_sysarqcamp values(2571,1014613,20,0);
insert into db_sysarqcamp values(2571,1014614,21,0);

alter table secretariadeeducacao.edu_relatmodel add column ed217_exibircertidao boolean default false;
alter table secretariadeeducacao.edu_relatmodel add column ed217_exibiridentidade boolean default false;

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

delete from db_sysarqcamp where codcam in (1014613,1014614);
delete from db_syscampodef where codcam in (1014613,1014614);
delete from db_syscampo where codcam in (1014613,1014614);

alter table secretariadeeducacao.edu_relatmodel drop column ed217_exibircertidao;
alter table secretariadeeducacao.edu_relatmodel drop column ed217_exibiridentidade;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
