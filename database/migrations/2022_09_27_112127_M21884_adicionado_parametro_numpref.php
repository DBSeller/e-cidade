<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21884AdicionadoParametroNumpref extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
      insert into db_syscampo 
      values(1014503,'k03_imprimecancdebitos','bool','Habilita botão para impressão do relatório de cancelamento de débitos','t', 'Permite Impressão Cancelamento Débitos',1,'f','f','f',5,'text','Permite Impressão Cancelamento Débitos');
      insert into db_sysarqcamp 
      values(318,1014503,77,0);

      alter table caixa.numpref 
        add column k03_imprimecancdebitos boolean not null default 't';
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
        delete 
          from db_sysarqcamp 
         where codcam = 1014503;

        delete 
          from db_syscampo 
         where codcam = 1014503;

         alter table caixa.numpref 
          drop column k03_imprimecancdebitos; 
SQL
);

    }
}
