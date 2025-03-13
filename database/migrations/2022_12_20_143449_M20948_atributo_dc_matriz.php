<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20948AtributoDcMatriz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update conplanoinfocomplementar
set c121_sql = 'select (CASE WHEN c60_codsis = 9 THEN 1 ELSE 0 END) AS infocomplementar_valor  from conplano where c60_codcon =  conta and  c60_anousu = anousu limit 1 '
where c121_sigla = 'DC';

reindex table conplanoinfocomplementar;
reindex table conplanoatributos;
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
update conplanoinfocomplementar
set c121_sql = 'select (CASE WHEN c60_codsis = 9 THEN 0 ELSE 1 END) AS infocomplementar_valor  from conplano where c60_codcon =  conta and  c60_anousu = anousu limit 1 '
where c121_sigla = 'DC';

reindex table conplanoinfocomplementar;
reindex table conplanoatributos;
SQL
        );
    }
}
