<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22182AjustaTabelaIssconfiguracaogruposervico  extends Migration
{
    /** 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL1

update issconfiguracaogruposervico set q136_deducao = true
where q136_issgruposervico in
(
select distinct q136_issgruposervico
from ativid
inner join issgruposervicoativid on q03_ativ = q127_ativid
inner join issgruposervico on q127_issgruposerviso = q126_sequencial
inner join issconfiguracaogruposervico on q126_sequencial = q136_issgruposervico
where q03_deducao = 't'
);


SQL1
    );

        DB::statement(<<<SQL2

update issconfiguracaogruposervico set q136_retencao = true
where q136_issgruposervico in
(
select distinct q136_issgruposervico
from ativid
inner join issgruposervicoativid on q03_ativ = q127_ativid
inner join issgruposervico on q127_issgruposerviso = q126_sequencial
inner join issconfiguracaogruposervico on q126_sequencial = q136_issgruposervico
where q03_tributacao_municipio = 't'
);

SQL2
    );

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return true;
    }
}
