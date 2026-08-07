<?php

use Classes\PostgresMigration;

class M17661CriaMenusRelatoriosCadastrais extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (
                        228397,
                        'Relatório de Escolas Ativas por Calendário',
                        'Relatório de Escolas Ativas por Calendário',
                        'sec2_escolasativas001.php',
                        '1',
                        '1',
                        'RELATÓRIO DE ESCOLAS ATIVAS POR CALENDÁRIO',
                        'true'
                        ),
                       (
                        228398,
                        'Listagem de corpo gestor',
                        'Listagem de corpo gestor',
                        'rel_escola_gestor.php',
                        '1',
                        '1',
                        'Gera listagem de corpo gestor com filtros para escolas, bairros,corpo gestor e quadro funcional',
                        'true'
                        );

            delete from db_menu where id_item_filho = 228397 AND modulo = 7159;
            delete from db_menu where id_item_filho = 228398 AND modulo = 7159;

            insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
                values (9512, 228397, 3, 7159),
                       (9512, 228398, 4, 7159);
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            delete from db_menu where id_item in (228397, 228398);
            delete from db_itensmenu where id_item in (228397, 228398);
sql
        );
    }
}
