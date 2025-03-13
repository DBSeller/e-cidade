<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21443HabilitaEdicaoManualRelatorio242 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

UPDATE orcparamseq SET  o69_manual = 't'
 where o69_codparamrel = 242
   and o69_codseq in (13, 18);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL

UPDATE orcparamseq SET  o69_manual = 'f'
 where o69_codparamrel = 242
   and o69_codseq in (13, 18);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
