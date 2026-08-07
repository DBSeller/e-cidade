<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//teste
class M19133ItensLicitacaoBloqueados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 228595 ,
        'Itens Bloqueados',
        'Itens Bloqueados',
        'lic2_itensBloqueados001.php',
        '1',
        '1',
        'Relatório de Itens Bloqueados da Licitação',
        'true'
        );

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1797 ,228595 ,62 ,381 );
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
delete from db_menu where id_item_filho = 228595 AND modulo = 381;
delete from db_itensmenu where id_item = 228595;
SQL
    );
}
}
