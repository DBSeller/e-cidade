<?php

use Classes\PostgresMigration;

class M13342BalanceteVerificacaoContaCorrente extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function UP()
    {

        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228109 ,'Balancete de Verificação por Conta Corrente' ,' Balancete de Verificação por Conta Corrente' ,'con2_balanceteverificacaocontacorrente001.php' ,'1' ,'1' ,' Balancete de Verificação por Conta Corrente' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4065 ,228109 ,12 ,209 );
SQL

        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        
delete from db_menu where id_item_filho = 228109 AND modulo = 209;
delete from db_itensmenu where id_item = 228109;
SQL
);
    }
}
