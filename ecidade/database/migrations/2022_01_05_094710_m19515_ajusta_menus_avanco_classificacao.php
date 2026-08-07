<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19515AjustaMenusAvancoClassificacao extends Migration
{
    public function up()
    {
        DB::connection()->getPdo()->exec("
            update db_itensmenu set id_item = 1101097 , descricao = 'Classificação' , help = 'Classificação' , funcao = 'edu1_trocaserie001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Classificação' , libcliente = 'false' where id_item = 1101097;
            update db_itensmenu set id_item = 1101096 , descricao = 'Avanço / Classificação' , help = 'Avanço / Classificação' , funcao = 'edu1_trocaserieav001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Avanço / Classificação' , libcliente = 'true' where id_item = 1101096;
        ");
    }

    public function down()
    {
        DB::connection()->getPdo()->exec("
            update db_itensmenu set id_item = 1101097 , descricao = 'Classificação' , help = 'Classificação' , funcao = 'edu1_trocaserie001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Classificação' , libcliente = 'true' where id_item = 1101097;
            update db_itensmenu set id_item = 1101096 , descricao = 'Avanço' , help = 'Avanço' , funcao = 'edu1_trocaserieav001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Avanço' , libcliente = 'true' where id_item = 1101096;
        ");
    }
}
