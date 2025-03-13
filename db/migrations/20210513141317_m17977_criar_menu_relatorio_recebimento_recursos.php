<?php

use Classes\PostgresMigration;

class M17977CriarMenuRelatorioRecebimentoRecursos extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (228501,
                        'Notificação de Recebimento de Recursos Federais',
                        'Notificação de Recebimento de Recursos Federais',
                        'cai2_recebimento_recursos_federais001.php',
                        '1',
                        '1',
                        'Relatório de Notificação de Recebimento de Recursos Federais',
                        'true');
            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
                values (3331, 228501, 54, 209);
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            delete from db_menu where id_item_filho = 228501;
            delete from db_itensmenu where id_item = 228501;
sql
        );
    }
}
