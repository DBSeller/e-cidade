<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampoAgrupaRubricaCfpess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        insert into db_syscampo values(1014192,'r11_rubagrupaadiantamento','varchar(4)','Rubrica de Agrupamento do Adiantamento do 13o Salario','', 'Rubrica que agrupa as rubricas de Adiant. 13o Sal',4,'t','t','f',0,'text','Rubrica de Agrup. Adiant. do 13o Salario');
        insert into db_sysarqcamp values(536,1014192,104,0);
        alter table pessoal.cfpess add column r11_rubagrupaadiantamento varchar(4);

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
        delete from db_sysarqcamp where codcam = 1014192;
        delete from db_syscampo where codcam = 1014192; 
        alter table pessoal.cfpess drop column r11_rubagrupaadiantamento;

SQL
        );
    }
}