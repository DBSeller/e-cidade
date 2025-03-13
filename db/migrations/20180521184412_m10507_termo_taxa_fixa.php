<?php

use Classes\PostgresMigration;

/**
 * Class M10507TermoTaxaFixa
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M10507TermoTaxaFixa extends PostgresMigration
{
    private $tableName = 'termotaxafixa';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $table = $this->table($this->tableName, array(
            'id' => 'ar42_sequencial',
            'schema' => 'arrecadacao'
        ));

        $table
            ->addColumn('ar42_parcel', 'integer', array('null' => false))
            ->addColumn('ar42_processoforo', 'integer', array('null' => false))
            ->addColumn('ar42_fixa', 'boolean', array('null' => false))
            ->save();

        $this->upPremenu();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->table($this->tableName, array('schema' => 'arrecadacao'))
            ->drop();

        $this->downPremenu();
    }

    private function upPremenu()
    {
        $sql = "
            insert into db_syscampo values(1009734,'ar42_sequencial','int8','Código sequencial','0', 'Sequencial',8,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1009735,'ar42_parcel','int8','Código do termo do parcelamento','0', 'Parcelamento',8,'f','f','f',1,'text','Parcelamento');
            insert into db_syscampo values(1009736,'ar42_processoforo','int8','Código do processo do foro','0', 'Processo do Foro',8,'f','f','f',1,'text','Processo do Foro');
            insert into db_syscampo values(1009737,'ar42_fixa','bool','Define se a taxa do parcelamento do termo é fixa','f', 'Fixa',1,'f','f','f',5,'text','Fixa');
            insert into db_sysarquivo values (1010281, 'termotaxafixa', 'Taxa fixa do termo do parcelamento', 'ar42', '2018-05-22', 'termotaxafixa', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (54,1010281);
            delete from db_sysarqcamp where codarq = 1010281;
            insert into db_sysarqcamp values(1010281,1009734,1,0);
            insert into db_sysarqcamp values(1010281,1009735,2,0);
            insert into db_sysarqcamp values(1010281,1009736,3,0);
            insert into db_sysarqcamp values(1010281,1009737,4,0);
        ";

        $this->execute($sql);
    }

    private function downPremenu()
    {
        $sql = "
            delete from db_sysarqcamp where codarq = 1010281;
            delete from db_sysarqmod where codarq = 1010281;
            delete from db_sysarquivo where codarq = 1010281;
            delete from db_syscampo where codcam in(1009734,1009735, 1009736, 1009737);
        ";

        $this->execute($sql);
    }
}
