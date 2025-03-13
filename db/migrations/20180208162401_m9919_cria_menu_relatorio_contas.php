<?php

use Classes\PostgresMigration;

/**
 * Class M9919CriaMenuRelatorioContas
 *
 * @author Ieso Rocha <ieso@dbseller.com.br>
 */
class M9919CriaMenuRelatorioContas extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sSql = <<<SQL
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10499 ,'Validação Plano de Contas MSC' ,'Validação Plano de Contas MSC' ,'con2_validacaoplanocontasmsc001.php' ,'1' ,'1' ,'Menu para relatório de validação de plano de contas da Matriz de Saldo Contábil (SICONFI)' ,'true' );
        delete from db_menu where id_item_filho = 10499 AND modulo = 209;
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3331 ,10499 ,51 ,209 );
SQL;
        $this->execute($sSql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $sSql = <<<SQL
        delete from db_menu where id_item_filho = 10499 AND modulo = 209;
        delete from db_itensmenu where id_item = 10499;
SQL;
        $this->execute($sSql);
    }
}
