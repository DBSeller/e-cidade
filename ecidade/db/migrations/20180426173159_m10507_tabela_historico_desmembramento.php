<?php

use Classes\PostgresMigration;

/**
 * Class M10507TabelaHistoricoDesmembramento
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M10507TabelaHistoricoDesmembramento extends PostgresMigration
{
    private $tableName = 'desmembramentoinicialhistorico';

    public function up()
    {
        $table = $this->table($this->tableName, array(
            'id' => 'v37_sequencial',
            'schema' => 'juridico'
        ));

        $table
            ->addColumn('v37_inicial_old', 'integer', array('null' => false))
            ->addColumn('v37_inicial', 'integer', array('null' => false))
            ->addColumn('v37_cda_old', 'integer', array('null' => false))
            ->addColumn('v37_cda', 'integer', array('null' => false))
            ->addColumn('v37_data', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
            ->addColumn('v37_usuario', 'integer', array('null' => false))
            ->addForeignKey('v37_inicial_old', 'inicial', 'v50_inicial')
            ->addForeignKey('v37_inicial', 'inicial', 'v50_inicial')
            ->addForeignKey('v37_cda_old', 'certid', 'v13_certid')
            ->addForeignKey('v37_cda', 'certid', 'v13_certid')
            ->save();

        $this->upPremenu();
    }

    public function down()
    {
        $this->table($this->tableName, array('schema' => 'juridico'))
            ->drop();

        $this->downPremenu();
    }

    private function upPremenu()
    {
        $sql = "
            insert into db_sysarquivo values (1010280, 'desmembramentoinicialhistorico', 'Histórico dos desmembramentos de inicial do foro.', 'v37', '2018-04-26', 'desmembramentoinicialhistorico', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (21,1010280);
            insert into db_syscampo values(1009725,'v37_sequencial','int4','Código sequencial da tabela','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009726,'v37_inicial_old','int4','Inicial antiga do desmembramento','0', 'Inicial Original',10,'f','f','f',1,'text','Inicial antiga');
            insert into db_syscampo values(1009727,'v37_inicial','int4','Inicial atual do desmembramento','0', 'Inicial',10,'f','f','f',1,'text','Inicial');
            insert into db_syscampo values(1009728,'v37_cda_old','int4','CDA antiga do desmembramento','0', 'CDA Original',10,'f','f','f',1,'text','CDA Antiga');
            insert into db_syscampo values(1009729,'v37_cda','int4','CDA nova do desmembramento','0', 'CDA',10,'f','f','f',1,'text','CDA');
            insert into db_syscampo values(1009730,'v37_data','date','Data do desmembramento','null', 'Data do Desmembramento',10,'f','f','f',1,'text','Data');
            insert into db_syscampo values(1009731,'v37_usuario','int4','Usuário que efetuou o desmembramento','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
            insert into db_sysarqcamp values(1010280,1009725,1,0);
            insert into db_sysarqcamp values(1010280,1009727,2,0);
            insert into db_sysarqcamp values(1010280,1009729,3,0);
            insert into db_sysarqcamp values(1010280,1009731,4,0);
            insert into db_sysarqcamp values(1010280,1009726,5,0);
            insert into db_sysarqcamp values(1010280,1009728,6,0);
            insert into db_sysarqcamp values(1010280,1009730,7,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010280,1009725,1,1009725);
            insert into db_sysforkey values(1010280,1009727,1,108,0);
            insert into db_sysforkey values(1010280,1009726,1,108,0);
            insert into db_sysforkey values(1010280,1009729,1,100,0);
            insert into db_sysforkey values(1010280,1009728,1,100,0);
            insert into db_sysindices values(1008276,'desmembramentoinicialhistorico_v37_inicial_old_in',1010280,'0');
            insert into db_syscadind values(1008276,1009726,1);
            insert into db_sysindices values(1008277,'desmembramentoinicialhistorico_v37_inicial_in',1010280,'0');
            insert into db_syscadind values(1008277,1009727,1);
            insert into db_sysindices values(1008278,'desmembramentoinicialhistorico_v37_cda_old_in',1010280,'0');
            insert into db_syscadind values(1008278,1009728,1);
            insert into db_sysindices values(1008279,'desmembramentoinicialhistorico_v37_cda_in',1010280,'0');
            insert into db_syscadind values(1008279,1009729,1);
            insert into db_syssequencia values(1000732, 'desmembramentoinicialhistorico_v37_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000732 where codarq = 1010280 and codcam = 1009725;
        ";

        $this->execute($sql);
    }

    private function downPremenu()
    {
        $sql = " delete from db_syscadind where codcam in (1009726, 1009727, 1009728, 1009729); ";
        $sql .= " delete from db_sysindices where codarq = 1010280; ";
        $sql .= " delete from db_sysforkey where codarq = 1010280; ";
        $sql .= " delete from db_sysprikey where codarq = 1010280; ";
        $sql .= " delete from db_sysarqcamp where codarq = 1010280; ";
        $sql .= " delete from db_syssequencia where codsequencia = 1000732; ";
        $sql .= " delete from db_syscampo where codcam in (1009725, 1009726, 1009727, 1009728, 1009729, 1009730, 1009731); ";
        $sql .= " delete from db_sysarqmod where codarq = 1010280; ";
        $sql .= " delete from db_sysarquivo where codarq = 1010280; ";

        $this->execute($sql);
    }
}
