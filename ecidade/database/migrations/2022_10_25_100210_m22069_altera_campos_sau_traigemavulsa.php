<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22069AlteraCamposSauTraigemavulsa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            ALTER TABLE sau_triagemavulsa
                ALTER COLUMN s152_i_cintura TYPE numeric(5, 1),
                ALTER COLUMN s152_i_altura TYPE numeric(5, 1),
                ALTER COLUMN s152_perimetrocefalico TYPE numeric(4, 1),
                ALTER COLUMN s152_n_temperatura TYPE numeric(4, 1);

            update db_syscampo set conteudo = 'float4', tamanho = 6, aceitatipo = 4 where codcam = 17218;
            update db_syscampo set conteudo = 'float4', tamanho = 6, aceitatipo = 4 where codcam = 17220;
            update db_syscampo set conteudo = 'float4', tamanho = 5, aceitatipo = 4 where codcam = 22005;

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

            ALTER TABLE sau_triagemavulsa
                ALTER COLUMN s152_i_cintura TYPE integer,
                ALTER COLUMN s152_i_altura TYPE integer,
                ALTER COLUMN s152_perimetrocefalico TYPE integer,
                ALTER COLUMN s152_n_temperatura TYPE numeric(6, 3);

            update db_syscampo set conteudo = 'int4', tamanho = 3, aceitatipo = 1 where codcam = 17218;
            update db_syscampo set conteudo = 'int4', tamanho = 3, aceitatipo = 1 where codcam = 17220;
            update db_syscampo set conteudo = 'int4', tamanho = 10, aceitatipo = 1 where codcam = 22005;
SQL
        );

    }
}
