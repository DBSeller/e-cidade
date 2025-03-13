<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19815MenusManutencaoRecurso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upMenus();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downMenus();
    }

    private function downMenus()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where modulo = 116 and id_item_filho in (3177, 3178, 3179, 228616, 228617, 228618, 228621, 228620);
delete from db_itensmenu where id_item in (228618, 228617, 228616, 228620, 228621);

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values (3176, 3177, 1, 116),
       (3176, 3178, 2, 116),
       (3176, 3179, 3, 116);
SQL
        );
    }

    private function upMenus()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values (228616, 'Recursos antes 2022' ,'Recursos antes 2022' ,'' ,'1' ,'1' ,'Manutenção dos recursos antes 2022' ,'true' ),
       (228617, 'Recursos a partir 2022' ,'Recursos a partir 2022' ,'' ,'1' ,'1' ,'Manutenção dos Recursos após o exercício de 2021' ,'true' ),
       (228618, 'De Para Recursos 2021 - 2022' ,'De Para Recursos 2021 - 2022' ,'orc1_depara_recursos.php' ,'1' ,'1' ,'De Para Recursos 2021 - 2022' ,'true' ),
       (228620, 'Inclusão / Alteração', 'Inclusão / Alteração' ,'orc1_manutencao_recursos001.php' ,'1' ,'1' ,'Inclui e altera os recursos' ,'true' ),
       (228621, 'Inativa / Excluir', 'Inativa / Excluir' ,'orc1_exclusao_recursos001.php' ,'1' ,'1' ,'Inativa / Excluir' ,'true' );

delete from db_menu where modulo = 116 and id_item_filho in (3177, 3178, 3179);

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values (3176, 228616, 6, 116),
       (228616, 3178, 2, 116),
       (228616, 3179, 3, 116),
       (3176, 228617, 7, 116),
       (3176, 228618, 8, 116),
       (228617, 228621, 2, 116),
       (228617, 228620, 1, 116);
SQL
        );
    }
}
