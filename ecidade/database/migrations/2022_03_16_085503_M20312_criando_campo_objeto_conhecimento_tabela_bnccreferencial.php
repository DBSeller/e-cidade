<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20312CriandoCampoObjetoConhecimentoTabelaBnccreferencial extends Migration
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
(
    1013803,
    'ed168_objeto_conhecimento',
    'text',
    'Objeto de Conhecimento',
    '',
    'Objeto de Conhecimento',
    200,
    't',
    't',
    'f',
    0,
    'text',
    'Objeto de Conhecimento'
);
insert into db_sysarqcamp values(1010614,1013803,8,0);
insert into db_sysindices values(1008736,'bnccreferencial_objeto_conhecimento_in',1010614,'0');
insert into db_syscadind values(1008736,1013803,1);


alter table bnccreferencial add column ed168_objeto_conhecimento text default null;

CREATE INDEX bnccreferencial_objeto_conhecimento_in ON bnccreferencial(ed168_objeto_conhecimento);

update bnccreferencial set ed168_objeto_conhecimento = ed148_objeto_conhecimento
    from bnccensinofundamental where ed168_codigohabilidade = ed148_codigo;

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

delete from db_sysarqcamp where codcam = 1013803;
delete from db_syscampo where codcam = 1013803;
delete from db_sysindices where codind = 1008736;
delete from db_syscadind where codind = 1008736;

-- DROP INDEX bnccreferencial_objeto_conhecimento_in;
alter table bnccreferencial drop column ed168_objeto_conhecimento;

SQL
        );
    }
}
