<?php

use Classes\PostgresMigration;

/**
 * Class AlteraMenuProcessoForo
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class AlteraMenuProcessoForo extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
          DELETE FROM db_menu WHERE id_item_filho = 8919 AND modulo = 313;
          INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 10469 ,8919 ,2 ,313 );
          UPDATE db_itensmenu SET id_item = 8919 , descricao = 'Processo do Foro' , help = 'Processo do Foro' , funcao = 'jur4_processoforopartilhacusta001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Manutenção de Custas para o Processo, lançar custas ou isentar um processo manualmente' , libcliente = 'true' WHERE id_item = 8919;
SQL;

        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $sql = <<<SQL
          DELETE FROM db_menu WHERE id_item_filho = 8919 AND modulo = 313;
          INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 1818 ,8919 ,6 ,313 );
          UPDATE db_itensmenu SET id_item = 8919 , descricao = 'Manutenção de Custas' , help = 'Manutenção de Custas' , funcao = 'jur4_processoforopartilhacusta001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Manutenção de Custas para o Processo, lançar custas ou isentar um processo manualmente' , libcliente = 'true' WHERE id_item = 8919;
SQL;

        $this->execute($sql);
    }
}
