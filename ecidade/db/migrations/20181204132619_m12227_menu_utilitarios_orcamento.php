<?php

use Classes\PostgresMigration;

class M12227MenuUtilitariosOrcamento extends PostgresMigration
{

    public function up(){

        $sql = <<<SQL
insert into db_itensmenu values( 228070, 'Utilitários do Orçamento', 'Utilitários do Orçamento', '', '1', '1', 'Utilitários do Orçamento', '0'	);
insert into db_itensmenu values( 228071, 'Ativar Orçamento Padrão da União', 'Ativar Orçamento Padrão da União', 'orc4_ativarrecursouniao001.php', '1', '1', 'Ativar Orçamento Padrão da União', '0'	);
insert into db_itensfilho (id_item, codfilho) values(228071,1);
update db_itensmenu set id_item = 228070 , descricao = 'Utilitários do Orçamento' , help = 'Utilitários do Orçamento' , itemativo = '1' , manutencao = '1' , desctec = 'Utilitários do Orçamento' , libcliente = 'true' where id_item = 228070;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228070 ,505 ,116 );
update db_itensmenu set id_item = 228071 , descricao = 'Ativar Orçamento Padrão da União' , help = 'Ativar Orçamento Padrão da União' , funcao = 'orc4_ativarrecursouniao001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Ativar Orçamento Padrão da União' , libcliente = 'true' where id_item = 228071;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228070 ,228071 ,1 ,116 );
SQL;

        $this->execute($sql);

    }

    public function down()
    {

        $sql = <<<SQL
delete from db_menu where id_item_filho = 228070 AND modulo = 116;
delete from db_menu where id_item_filho = 228071 AND modulo = 116;
delete from db_itensfilho where id_item = 228071 and codfilho = 1;
delete from db_itensmenu  where id_item = 228070;
delete from db_itensmenu  where id_item = 228071;
SQL;
        $this->execute($sql);

    }
}
