<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19735IndicesParaEmissaoGeralIptu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE INDEX IF NOT EXISTS iptucalhconf_codhis_in on cadastro.iptucalhconf(j89_codhis);");
        DB::statement("CREATE INDEX IF NOT EXISTS iptucalv_codhis_in on cadastro.iptucalv(j21_codhis);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP INDEX IF EXISTS cadastro.iptucalhconf_codhis_in;");
        DB::statement("DROP INDEX IF EXISTS cadastro.iptucalv_codhis_in;");
    }
}
