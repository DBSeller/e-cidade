<?php

use Classes\PostgresMigration;

class M10560ProgressaoAssenta extends PostgresMigration
{

    public function up()
    {

       $aSql = array();

       $aSql[] = "insert into db_sysarquivo values (1010278, 'assentaalteracadastroservidor', 'Tabela responsavel por quardar os servidores, que teram seu cargo alterao devido a progressao', '', '2018-04-21', 'assentaalteracadastroservidor', 0, 'f', 'f', 'f', 'f' );";
       $aSql[] = "insert into db_sysarqmod values (29,1010278);";
       $aSql[] = "insert into db_syscampo values(1009716,' h15_assent','int8','Quarda o assentamento ','0', ' h15_assent',11,'f','f','f',1,'text','');";
       $aSql[] = "insert into db_syscampo values(1009717,'h15_regist','int8','Matricula do servidor','0', 'h15_regist',11,'f','f','f',1,'text','');";
       $aSql[] = "insert into db_syscampo values(1009720,'h15_sequencial','int8','Sequencial da tabela assentaalteracadastroservidor','0', 'h15_sequencial',11,'f','f','f',1,'text','h15_sequencial');";

       $aSql[] = "insert into db_sysarqcamp values(1010278,1009716,1,0);";
       $aSql[] = "insert into db_sysarqcamp values(1010278,1009717,2,0);";

       $aSql[] = "insert into db_sysarqcamp values(1010278,1009720,4,0);";

       $aSql[] = "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010278,1009720,1,1009720);";

       $aSql[] = "insert into db_syssequencia values(1000730, 'assentaalteracadastroservidor_h15_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);";
       $aSql[] = "update db_sysarqcamp set codsequencia = 1000730 where codarq = 1010278 and codcam = 1009720;";

       $aSql[] = "CREATE TABLE recursoshumanos.assentaalteracadastroservidor();";
       $aSql[] = "ALTER TABLE recursoshumanos.assentaalteracadastroservidor ADD COLUMN h15_sequencial integer";
       $aSql[] = "ALTER TABLE recursoshumanos.assentaalteracadastroservidor ADD COLUMN h15_assent integer";
       $aSql[] = "ALTER TABLE recursoshumanos.assentaalteracadastroservidor ADD COLUMN h15_regist integer";


       $aSql[] = "CREATE SEQUENCE recursoshumanos.assentaalteracadastroservidor_h15_sequencial_seq START 1;";

       foreach ($aSql as $sql) {
           $this->execute($sql);
       }
    }

    public function down()
    {
        $aSql = array();

        $aSql[] = "DELETE FROM db_sysprikey  WHERE codarq =1010278;";

        $aSql[] = "DELETE FROM db_syssequencia  WHERE codsequencia =1000730;";
        $aSql[] = "DELETE FROM db_sysarqcamp where codarq = 1010278;";
        $aSql[] = "DELETE FROM db_syscampo  WHERE  codcam IN ( 1009716, 1009717 ,1009720) ;";
        $aSql[] = "DELETE FROM db_sysarqmod  WHERE codarq =1010278;";
        $aSql[] = "DELETE FROM db_sysarquivo  WHERE codarq =1010278;";
        $aSql[] = "DROP TABLE  assentaalteracadastroservidor";
        $aSql[] = "DROP SEQUENCE recursoshumanos.assentaalteracadastroservidor_h15_sequencial_seq;";

        foreach ($aSql as $sql) {
            $this->execute($sql);
        }

    }

}
