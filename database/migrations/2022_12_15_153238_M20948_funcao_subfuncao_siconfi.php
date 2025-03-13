<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20948FuncaoSubfuncaoSiconfi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    /**
     * @return void
     */
    protected function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_syscampo
values (1014659,'o52_siconfi','char(2)','Código Siconfi vai na matríz de saldo contábil','', 'Código Siconfi',2,'f','f','f',1,'text','Código Siconfi'),
       (1014660,'o53_siconfi','char(3)','Código Siconfi vai na matríz de saldo contábil','', 'Código Siconfi',3,'f','f','f',1,'text','Código Siconfi');

insert into db_sysarqcamp
values (750,1014659,5,0),
       (751,1014660,5,0);
SQL
        );

    }

    /**
     * @return void
     */
    protected function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_sysarqcamp where codcam in (1014659, 1014660);
delete from db_syscampo where codcam in (1014659, 1014660);
SQL
        );
    }

    /**
     * @return void
     */
    protected function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
alter table orcamento.orcfuncao add column o52_siconfi char(2);
alter table orcamento.orcsubfuncao add column o53_siconfi char(3);

update orcamento.orcfuncao set o52_siconfi = lpad(trim(o52_codtri), 2, '0');
update orcamento.orcsubfuncao set o53_siconfi = lpad(trim(o53_codtri), 3, '0');
SQL
        );
    }

    /**
     * @return void
     */
    protected function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
alter table orcamento.orcfuncao drop column o52_siconfi;
alter table orcamento.orcsubfuncao drop column o53_siconfi;
SQL
        );
    }
}
