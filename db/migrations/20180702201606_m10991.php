<?php

use Classes\PostgresMigration;

class M10991 extends PostgresMigration
{
    public function up()
    {
        $aSql = array();

        $aSql[] = "insert into db_sysarquivo values (1010293, 'tomador', 'Tabela responsavel por guardar a empresa tomadora onde o servidor esta alocado', '', '2018-07-02', 'Tomador', 0, 'f', 'f', 'f', 'f' );";
        $aSql[] = "insert into db_sysarqmod values (28,1010293);";
        $aSql[] = "insert into db_syscampo values(1009807,'rh216_seqpes','int8','Sequencial da tabela Rhpessoalmov','0', 'rhpessoalmov',11,'f','f','f',1,'text','');";
        $aSql[] = "insert into db_syscampo values(1009809,'rh216_numcgm','int8','Numero do Cgm da tabela cgm','0', 'numcgm',11,'f','f','f',1,'text','numcgm');";
        $aSql[] = "insert into db_syscampo values(1009810,'rh216_instit','int8','Instituição do servidor','0', 'z18_instit',11,'f','f','f',1,'text','z18_instit');";
        $aSql[] = "insert into db_sysarqcamp values(1010293,1009807,1,0);";
        $aSql[] = "insert into db_sysarqcamp values(1010293,1009809,2,0);";
        $aSql[] = "insert into db_sysarqcamp values(1010293,1009810,3,0);";
        $aSql[] = "insert into db_sysforkey values(1010293,1009807,1,1158,0);";
        $aSql[] = "insert into db_sysforkey values(1010293,1009810,2,1158,0);";
        $aSql[] = "insert into db_sysforkey values(1010293,1009809,1,42,0);";
        $aSql[] = "insert into db_sysforkey values(1010293,1009810,1,83,0);";
        $aSql[] = "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010293,1009807,1,1009809);";
        $aSql[] = "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010293,1009810,2,1009809);";

        $aSql[] = "CREATE TABLE pessoal.tomador(
          rh216_seqpes		int8 NOT NULL default 0,
          rh216_numcgm		int8 NOT NULL default 0,
          rh216_instit		int8 default 0,
          CONSTRAINT tomador_seqp_inst_pk PRIMARY KEY (rh216_seqpes,rh216_instit));";

        $aSql[] = "ALTER TABLE tomador ADD CONSTRAINT tomador_numcgm_fk FOREIGN KEY (rh216_numcgm) REFERENCES cgm; ";
        $aSql[] = "ALTER TABLE tomador ADD CONSTRAINT tomador_instit_fk FOREIGN KEY (rh216_instit) REFERENCES db_config; ";
        $aSql[] = "ALTER TABLE tomador ADD CONSTRAINT tomador_seqpes_instit_fk FOREIGN KEY (rh216_seqpes,rh216_instit) REFERENCES rhpessoalmov; ";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }

    }

    public function down()
    {
        $aSql = array();

        $aSql[] = "delete from db_sysprikey where codarq = 1010293;";
        $aSql[] = "DELETE FROM db_sysforkey  WHERE codarq IN (1010293);";
        $aSql[] = "DELETE FROM db_sysarqcamp  WHERE codarq IN (1010293);";
        $aSql[] = "DELETE FROM db_sysarqmod  WHERE codarq IN (1010293);";
        $aSql[] = "DELETE FROM db_syscampo  WHERE codcam IN (1009809,  1009807, 1009810);";
        $aSql[] = "DELETE FROM db_sysarquivo  WHERE  codarq = 1010293;";
        $aSql[] = "DROP TABLE tomador;";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }
    }
}
