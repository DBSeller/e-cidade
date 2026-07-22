<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use phpDocumentor\Reflection\Types\Nullable;
use Illuminate\Support\Facades\DB;

class M19501AddColumnBens extends Migration
{
   
    public function upDictionary() {
     
        DB::statement("insert into db_syscampo values(1013490, 't52_dtinclusao', 'date', 'Campo para guardar a data da sessão do sistema para ser utilizado em consultas/relatórios', 'current_date', 'Data Inclusão', 10, 'f', 'f', 'f', 1, 'text', 'Data Inclusão');");
        DB::statement("insert into db_sysarqcamp values(914,1013490,15,0);");
    }
  
    public function upStruct() {
      
        Schema::table('patrimonio.bens', function (Blueprint $bens) {
            $bens->date('t52_dtinclusao')
             ->nullable(false)
             ->default(DB::raw('current_date'))
             ->comment('Campo para guardar a data da sessão do sistema');
        });

        DB::statement(<<<SQL
            WITH ajuste_data_inclusao AS (
                SELECT t52_bem as codbem,
                       t52_dtaqu as dtaqu,
                       min(t56_data) as dtinclusao
                  from patrimonio.bens 
                  left join patrimonio.histbem
                    on t52_bem = t56_codbem
                 group by 1,2                
            )
            UPDATE patrimonio.bens 
               SET t52_dtinclusao = case when dtinclusao is null then dtaqu else dtinclusao end 
              FROM ajuste_data_inclusao
             WHERE bens.t52_bem = ajuste_data_inclusao.codbem;  
SQL
);    

    }

    public function downDictionary() {
     
        
        DB::statement("delete from db_sysarqcamp where codcam = 1013490;");
        DB::statement("delete from db_syscampo where codcam = 1013490;");
    }

    public function downStruct() {
      
        Schema::table('patrimonio.bens', function (Blueprint $bens) {
            $bens->dropColumn('t52_dtinclusao');
        });

        

    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDictionary();
        $this->upStruct();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDictionary();
        $this->downStruct();       
    }
}