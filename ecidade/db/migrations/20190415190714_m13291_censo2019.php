<?php

use Classes\PostgresMigration;

class M13291Censo2019 extends PostgresMigration
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

    public function up()
    {
      $this->cadastroDicionarioDados();
      $this->criarNovaCampoEscola();
      $this->criarTabelaLinguaIndigena();
      $this->migrateDados();
    }

    public function down()
    {
      $this->revertDicionarioDados();
      $this->revertMigrateDados();
      $this->dropNovaColunaEscola();
      $this->dropTabelaLiguaIndigena();
       
    }

    public function cadastroDicionarioDados()
    {
      $sSql  = "insert into db_syscampo values(1010423,'ed18_i_esferaadministrativa','int4','Esfera Administrativa','0', 'Esfera Administrativa',8,'t','f','f',1,'text','Esfera Administrativa');
                insert into db_sysarqcamp values(1010031,1010423,39,0);

                insert into db_sysarquivo values (1010440, 'escolacensolinguaindigena', 'Escola Censo Lingua Indigena', 'ed144', '2019-04-18', 'Escola Censo Lingua Indigena', 0, 'f', 'f', 'f', 'f' );
                insert into db_sysarqmod values (1008004,1010440);
                insert into db_syscampo values(1010424,'ed144_i_codigo','int4','Código','0', 'Código',8,'f','f','f',1,'text','Código');
                insert into db_syscampo values(1010425,'ed144_i_linguaindigena1','int4','Lingua Indigena 1','null', 'Lingua Indigena 1',8,'t','f','f',1,'text','Lingua Indigena 1');
                insert into db_syscampo values(1010426,'ed144_i_linguaindigena2','int4','Lingua Indigena 2','null', 'Lingua Indigena 2',8,'t','f','f',1,'text','Lingua Indigena 2');
                insert into db_syscampo values(1010427,'ed144_i_linguaindigena3','int4','Lingua Indigena 3','null', 'Lingua Indigena 3',8,'t','f','f',1,'text','Lingua Indigena 3');
                insert into db_syscampo values(1010428,'ed144_i_escola','int4','Escola','0', 'Escola',8,'f','f','f',1,'text','Escola');
                insert into db_sysarqcamp values(1010440,1010428,1,0);
                insert into db_sysarqcamp values(1010440,1010425,2,0);
                insert into db_sysarqcamp values(1010440,1010426,3,0);
                insert into db_sysarqcamp values(1010440,1010427,4,0);
                insert into db_sysarqcamp values(1010440,1010424,5,0);
                insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010440,1010424,1,1010424);
                insert into db_sysforkey values(1010440,1010428,1,1010031,0);
                insert into db_sysforkey values(1010440,1010425,1,2343,0);
                insert into db_sysforkey values(1010440,1010426,1,2343,0);
                insert into db_sysforkey values(1010440,1010427,1,2343,0);
                 

                insert into db_syssequencia values(1000829, 'escolacensolinguaindigena_ed144_i_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
                update db_sysarqcamp set codsequencia = 1000829 where codarq = 1010440 and codcam = 1010424;";
      $this->execute($sSql);
    }

    public function criarNovaCampoEscola()
    {
      $sSql = "ALTER TABLE escola.escola ADD COLUMN ed18_i_esferaadministrativa integer";
      $this->execute($sSql);        
    }

    public function criarTabelaLinguaIndigena()
    {
      $sSql = "CREATE TABLE escola.escolacensolinguaindigena(
                                    ed144_i_codigo integer PRIMARY KEY,
                                    ed144_i_linguaindigena1 INTEGER REFERENCES censolinguaindig (ed264_i_codigo),
                                    ed144_i_linguaindigena2 INTEGER REFERENCES censolinguaindig (ed264_i_codigo),
                                    ed144_i_linguaindigena3 INTEGER REFERENCES censolinguaindig (ed264_i_codigo),
                                    ed144_i_escola INTEGER REFERENCES escola (ed18_i_codigo)
                                   );

                CREATE SEQUENCE escolacensolinguaindigena_ed144_i_codigo_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;";
      $this->execute($sSql);
    }

    public function migrateDados()
    {
       $sSql  = "insert into escolacensolinguaindigena (select nextval('escolacensolinguaindigena_ed144_i_codigo_seq'), ed18_i_linguaindigena, null, null, ed18_i_codigo from escola where ed18_i_linguaindigena is not null);";
       $sSql .= "update escola set ed18_i_linguaindigena = null where ed18_i_linguaindigena is not null;";
       $this->execute($sSql);
    }

    public function revertDicionarioDados()
    {
       $sSql = "
                delete from db_sysarqcamp where codcam = 1010423;
                delete from db_syscampo where codcam = 1010423;

                delete from db_sysforkey where codarq = 1010440;
                delete from db_sysprikey where codcam = 1010424;
                delete from db_sysarqcamp where codarq = 1010440;
                                
                delete from db_syscampo where codcam = 1010424;
                delete from db_syscampo where codcam = 1010425;
                delete from db_syscampo where codcam = 1010426;
                delete from db_syscampo where codcam = 1010427;
                delete from db_syscampo where codcam = 1010428;

                delete from db_sysarqmod where codarq = 1010440;
                delete from db_sysarquivo where codarq = 1010440;

                delete from db_syssequencia where codsequencia = 1000829;
               ";
       $this->execute($sSql);
    
    }

    public function revertMigrateDados()
    {
       $sSql = "update escola set ed18_i_linguaindigena = ed144_i_linguaindigena1 from escolacensolinguaindigena where ed18_i_codigo = ed144_i_escola;";
       $this->execute($sSql);
    }

    public function dropTabelaLiguaIndigena()
    {
      $sSql = " DROP TABLE escola.escolacensolinguaindigena;";
      $sSql .= "DROP SEQUENCE escolacensolinguaindigena_ed144_i_codigo_seq";
      $this->execute($sSql);
    }

    public function dropNovaColunaEscola()
    {
      $sSql = "alter table escola drop column ed18_i_esferaadministrativa;";
      $this->execute($sSql);
    }


}
