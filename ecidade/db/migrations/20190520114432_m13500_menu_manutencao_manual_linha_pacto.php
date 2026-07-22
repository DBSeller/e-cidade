<?php

use Classes\PostgresMigration;

class M13500MenuManutencaoManualLinhaPacto extends PostgresMigration
{

    public function up()
    {

        $sql = <<<SQL
insert into db_itensmenu values( 2002843, 'Inclusão Manual', 'Inclusão Manual', 'orc4_inclusaomanual001.php', '1', '1', 'Inclusão Manual', '0'	);
insert into db_itensfilho (id_item, codfilho) values(2002843,1);
update db_itensmenu set descricao = 'Inclusão Manual', help = 'Inclusão Manual', funcao = 'orc4_inclusaomanual001.php', itemativo = '1', desctec = 'Inclusão Manual', libcliente = '1' where id_item = 2002843;
update db_itensmenu set descricao = 'Manutenção Saldo Linha de Pacto', help = 'Manutenção Saldo Linha de Pacto', funcao = 'orc4_inclusaomanual001.php', itemativo = '1', desctec = 'Inclusão Manual', libcliente = '1' where id_item = 2002843;
delete from db_itensfilho where id_item = 2002843;
insert into db_itensfilho values(2002843,1);
insert into db_menu values(3215,2002843,5,116);
SQL;
        $this->execute($sql);

    }

    public function down()
    {
$sql = <<<SQL
        delete from db_itensfilho where id_item = 2002843;
        delete from db_itensmenu  where id_item = 2002843;
        delete from db_menu       where id_item = 2002843;
        delete from db_menu       where id_item_filho = 2002843;
SQL;
        $this->execute($sql);

    }

}

