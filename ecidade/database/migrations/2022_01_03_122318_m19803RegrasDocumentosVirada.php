<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19803RegrasDocumentosVirada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $sql = <<<SQL

delete from conhistdocregra where c92_anousu = 2022;
insert into conhistdocregra
select nextval('conhistdocregra_c92_sequencial_seq'), *
from (
select c92_conhistdoc ,
       c92_descricao  ,
       c92_regra      ,
       2022  as c92_anousu
  from conhistdocregra
 where c92_anousu = 2021
  order by c92_conhistdoc
) as x ;

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

delete from conhistdocregra where c92_anousu = 2022;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
