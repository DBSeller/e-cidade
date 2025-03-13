<?php

use Classes\PostgresMigration;

class M17620CriaMenusTransferencias extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (
                        228392,
                        'Encaminhamento de Transferência',
                        'Encaminhamento de Transferência',
                        'edu2_encaminhamentotransferencia001.php',
                        '1',
                        '1',
                        'edu2_encaminhamentotransferencia001.php',
                        'false'
                        ),
                       (
                        228394,
                        'Declaração de Transferência',
                        'Declaração de Transferência',
                        'edu2_declaracaotransf001.php',
                        '1',
                        '1',
                        'Guia de Transferência, antigo guia de transferência, o classe edu2_guiatransf001.php permanecerá no sistema mas será substituída por edu2_declaracaotransf001.php',
                        'false'
                        );
            delete from db_menu where id_item_filho in (228392, 228394) AND modulo = 1100747;
            delete from db_menu where id_item_filho = 228394 AND modulo = 7159;

            insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
                values (1101189, 228392, 6, 1100747),
                       (1101189, 228394, 7,1100747 ),
                       (1101189, 228394, 8,7159);
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            delete from db_menu where id_item in (228392, 228394);
            delete from db_itensmenu where id_item in (228392, 228394);
sql
        );
    }
}
