<?php

use Classes\PostgresMigration;

class M17600MenusAssinaturaDigitalGenerica extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228605 ,'Assinatura Digital' ,'Menu para manutenção de permissões de assinantes de documentos digitais' ,'' ,'1' ,'1' ,'Árvore de menu para manutenção de permissão dos assinantes de documentos digitais' ,'true' );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228606,'Assinantes' ,'Menu para manutenção de permissões de assinantes' ,'con4_assinaturadigital_administradores.php' ,'1' ,'1' ,'Menu de manutenção das permissões dos assinantes de documentos digitais' ,'true' );
            insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (32,     228605, 548, 1);
            insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (228605, 228605, 1, 1);
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            DELETE FROM db_menu WHERE id_item IN (228605, 228606);
            DELETE FROM db_itensmenu WHERE id_item IN (228605, 228606);
SQL;

        $this->execute($sql);
    }
}
