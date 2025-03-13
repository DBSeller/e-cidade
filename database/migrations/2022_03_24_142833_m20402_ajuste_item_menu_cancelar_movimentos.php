<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20402AjusteItemMenuCancelarMovimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

            update db_itensmenu set id_item = 9787 ,
                                    descricao = 'Cancelar Arquivo de Remessa - OBN / CNAB' ,
                                    help = 'Cancelar Arquivo de Remessa - OBN / CNAB' ,
                                    funcao = 'cai4_cancelararquivo001.php' ,
                                    itemativo = '1' ,
                                    manutencao = '1' ,
                                    desctec = 'Cancelar arquivo OBN / CNAB' ,
                                    libcliente = 'true'
                              where id_item = 9787;



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


            update db_itensmenu set id_item = 9787 ,
                                    descricao = 'Cancelar Arquivo de Remessa - OBN' ,
                                    help = 'Cancelar Arquivo de Remessa - OBN' ,
                                    funcao = 'cai4_cancelararquivo001.php' ,
                                    itemativo = '1' ,
                                    manutencao = '1' ,
                                    desctec = 'Cancelar arquivo OBN' ,
                                    libcliente = 'true'
                              where id_item = 9787;


SQL;

              DB::connection()->getPdo()->exec($sql);
    }
}
