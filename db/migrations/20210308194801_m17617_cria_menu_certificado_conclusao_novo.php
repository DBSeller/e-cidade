<?php

use Classes\PostgresMigration;

class M17617CriaMenuCertificadoConclusaoNovo extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (
                        228386,
                        'Certificado de Conclusão (Novo)',
                        'Certificado de Conclusão (Novo)',
                        'edu2_cerconclusao001.php',
                        '1',
                        '1', 'CERTIFICADO DE CONCLUSÃO - Modelo novo',
                        'true'
                        );

            delete from db_menu where id_item_filho = 228386 AND modulo = 1100747;

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
                values (1101109, 228386, 20, 1100747);
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            delete from db_menu where id_item_filho = 228386;
            delete from db_itensmenu where id_item = 228386;
sql
        );
    }
}
