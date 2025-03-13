<?php

use Classes\PostgresMigration;

class M13576MenuRealizarPartilha extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL
insert into db_itensmenu values( 228112, 'Realizar Transferências de Taxas', 'Realizar Transferências de Taxas', '', '1', '1', 'Realizar Transferências de Taxas', '0'	);
update db_itensmenu set descricao = 'Realizar Transferências de Partilhas', help = 'Realizar Transferências de Partilhas', funcao = 'cai4_transferenciapartilha001.php', itemativo = '1', desctec = 'Realizar Transferências de Partilhas', libcliente = '1' where id_item = 228112;
insert into db_menu values(7791,228112,4,39);
insert into db_itensfilho values(228112,1);
delete from db_itensfilho where id_item = 228112;

SQL;

        $this->execute($sql);
    }

    public function down()
    {

        $sql = <<<SQL
delete from db_itensfilho where id_item = 228112;
delete from db_menu       where id_item = 228112;
delete from db_itensmenu  where id_item = 228112;

SQL;
        $this->execute($sql);

    }
}
