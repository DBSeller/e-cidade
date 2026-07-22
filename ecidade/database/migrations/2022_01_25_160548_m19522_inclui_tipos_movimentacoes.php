<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19522IncluiTiposMovimentacoes extends Migration
{
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
        insert into matestoquetipo select 999, 'AJUSTE ESTOQUE - ENTRADA', TRUE, 1 as x where not exists (select 1 from matestoquetipo where m81_codtipo = 999);
        insert into matestoquetipo select 998, 'AJUSTE ESTOQUE - SAIDA', false, 2 as x where not exists (select 1 from matestoquetipo where m81_codtipo = 998);
sql
        );
    }

    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
        insert into matestoquetipo select 999, 'AJUSTE ESTOQUE - ENTRADA', TRUE, 1 as x where not exists (select 1 from matestoquetipo where m81_codtipo = 999);
        insert into matestoquetipo select 998, 'AJUSTE ESTOQUE - SAIDA', false, 2 as x where not exists (select 1 from matestoquetipo where m81_codtipo = 998);
sql
        );
    }
}
