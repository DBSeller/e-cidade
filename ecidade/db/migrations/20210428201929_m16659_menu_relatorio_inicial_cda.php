<?php

use Classes\PostgresMigration;

class M16659MenuRelatorioInicialCda extends PostgresMigration
{
    public function up(){

        $this->execute(<<<SQL
        insert into
            db_itensmenu(
                id_item,
                descricao,
                help,
                funcao,
                itemativo,
                manutencao,
                desctec,
                libcliente
            )
        values
            (
                228494,
                'Emissão de Inicial com CDA',
                'Relatórios > Emissão de Inicial com CDA',
                'div2_inicial_cda_001.php',
                '1',
                '1',
                'Relatórios > Emissão de Inicial com CDA',
                'true'
            );

        insert into
            db_menu(id_item, id_item_filho, menusequencia, modulo)
        values
            (1797, 228494, 59, 313);
SQL
        );
    }

    public function down(){
        
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228494;
            delete from db_itensmenu where id_item = 228494;
SQL
        );

    }
}
