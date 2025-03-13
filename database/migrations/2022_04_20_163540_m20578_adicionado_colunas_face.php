<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20578AdicionadoColunasFace extends Migration
{   
    
    public function upDicionario() {

        DB::connection()->getPdo()->exec(<<<SQL
        insert into db_syscampo values(1014006,'j37_segmento','int4','Segmento da face','0', 'Segmento',10,'t','f','f',1,'text','Segmento');
        insert into db_syscampo values(1014007,'j37_sequencia','int4','Sequencia da face de quadra','0', 'Sequencia',10,'t','f','f',1,'text','Sequencia');
        
        insert into db_sysarqcamp values(15,1014006,12,0);
        insert into db_sysarqcamp values(15,1014007,13,0);
 
SQL
                );
    }

    public function downDicionario() {

        DB::connection()->getPdo()->exec(<<<SQL
        delete 
          from db_sysarqcamp 
         where codcam in (1014006, 1014007); 

        delete 
          from db_syscampo 
         where codcam in (1014006, 1014007);        
SQL
                );
    }

    public function upEstrutura() {

        DB::connection()->getPdo()->exec(<<<SQL
        
        alter table cadastro.face add column j37_segmento  integer;
        alter table cadastro.face add column j37_sequencia  integer;
SQL
                );
    }

    public function downEstrutura() {

        DB::connection()->getPdo()->exec(<<<SQL
        
        alter table cadastro.face drop column j37_segmento;
        alter table cadastro.face drop column j37_sequencia;
SQL
                );
    }
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
}
