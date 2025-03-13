<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20421AjusteTamanhoCnpjOrcunidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $sql = <<<SQL

update orcunidade set o41_cnpj = substr(o41_cnpj, 1, 14) where LENGTH(o41_cnpj) > 14;
alter table orcunidade ALTER COLUMN o41_cnpj TYPE varchar(14);
update db_syscampo set conteudo = 'varchar(14)' ,
                       tamanho = 14
 where codcam = 6424;

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

alter table orcunidade ALTER COLUMN o41_cnpj TYPE varchar(15);
update db_syscampo set conteudo = 'varchar(15)' ,
                       tamanho = 15
 where codcam = 6424;

SQL;

      DB::connection()->getPdo()->exec($sql);
    }

}
