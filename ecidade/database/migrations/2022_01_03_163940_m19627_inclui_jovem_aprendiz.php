<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19627IncluiJovemAprendiz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upInsereRegistro();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downExcluiRegistro();
    }

    private function upInsereRegistro() {
        $sql = <<<SQL
        INSERT INTO pessoal.rhnaturezaregime
        (rh71_sequencial, rh71_descricao)
         VALUES(7, 'JOVEM APRENDIZ');

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downExcluiRegistro() {
        $sql = <<<SQL
        DELETE FROM pessoal.rhnaturezaregime
        WHERE rh71_sequencial=7;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
