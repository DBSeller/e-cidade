<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriacaoCampoCessacaoBeneficiosTabelaRescisao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        insert into db_syscampo values(1014354,'r59_cessacaobeneficios','int4','Códigos Cessação Benefícios-Motivo eSocial','0', 'Códigos Cessação Benefícios',10,'t','f','f',1,'text','Códigos Cessação Benefícios');
        insert into db_sysarqcamp values(589,1014354,31,0);
        alter table pessoal.rescisao add column IF NOT EXISTS r59_cessacaobeneficios int;

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
        delete from db_sysarqcamp where codcam = 1014354;
        delete from db_syscampo where codcam = 1014354; 
        alter table pessoal.rescisao drop column IF EXISTS r59_cessacaobeneficios;

SQL
        );
    }
}
