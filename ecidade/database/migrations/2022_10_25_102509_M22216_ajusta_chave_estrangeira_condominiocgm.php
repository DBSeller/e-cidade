<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22216AjustaChaveEstrangeiraCondominiocgm extends Migration
{
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

    private function upEstrutura() {
        $sql = <<<SQL
        alter table condominiocgm drop constraint if exists condominiocgm_sequencial_fk;
        alter table condominiocgm drop constraint if exists condominiocgm_j106_numcgm_fkey;
        alter table condominiocgm add constraint condominiocgm_j106_numcgm_fkey foreign key (j106_numcgm) references cgm (z01_numcgm) deferrable;
SQL;
    DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura() {
        return true;
    }

    private function upDicionario() {
        $sql = <<<SQL
            delete from db_sysforkey where codarq = 2532 and codcam = 14375;
            delete from db_sysforkey where codarq = 2532 and codcam = 14377;
            insert into db_sysforkey values(2532,14377,1,42,0);

SQL;
    DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
            delete from db_sysforkey where codarq = 2532 and codcam = 14377;

SQL;
    DB::connection()->getPdo()->exec($sql);

    }
}
