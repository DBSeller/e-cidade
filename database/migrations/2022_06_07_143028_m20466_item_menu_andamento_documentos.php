<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20466ItemMenuAndamentoDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
    values (
        228677,
        'Andamento de Documentos',
        'Andamento de Documentos',
        'con4_atividades_documentos.php',
        '1',
        '1',
        'Andamento de Documentos',
        'true'
        );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228677 ,552 ,604 );
sql
                );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
delete from db_menu where id_item_filho = 228677;
delete from db_itensmenu where id_item = 228677;
sql
        );
    }
}
