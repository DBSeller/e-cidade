<?php

use Classes\PostgresMigration;

class M13299CriaRelacaoTurmaAtividadeComplementar extends PostgresMigration
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
        $this->registraDicionarioDeDados();
        $this->criaTabela();
    }

    public function down()
    {
        $this->removeTabela();
        $this->removeDicionarioDeDados();
    }

    private function registraDicionarioDeDados()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values
                (1010443, 'turmaatividadecomplementar', 'Turma Atividade Complementar', 'ed146', '2019-04-25', 'Turma Atividade Complementar', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values
                (1008004,1010443);
            insert into db_syscampo values
                (1010449,'ed146_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
                (1010450,'ed146_turma','int4','Turma','0', 'Turma',10,'f','f','f',1,'text','Turma'),
                (1010451,'ed146_censoativcompl','int4','Turma Atividade Complementar','0', 'Turma Atividade Complementar',10,'f','f','f',1,'text','Turma Atividade Complementar'),
                (1010452,'ed146_funcaoatividade','int4','Função/Atividade','0', 'Função/Atividade',10,'f','f','f',1,'text','Função/Atividade'),
                (1010453,'ed146_rechumanoescola','int4','Professor','0', 'Professor',10,'f','f','f',1,'text','Professor'),
                (1010454,'ed146_diasemana','int4','Dia da semana','0', 'Dia da Semana',10,'f','f','f',1,'text','Dia da Semana'),
                (1010455,'ed146_horainicial','varchar(8)','Hora inicial','', 'Hora inicial',8,'f','f','f',0,'text','Hora inicial'),
                (1010456,'ed146_horafinal','varchar(8)','Hora final','', 'Hora final',8,'f','f','f',0,'text','Hora final');

            insert into db_sysarqcamp values
                (1010443,1010449,1,0),
                (1010443,1010450,2,0),
                (1010443,1010451,3,0),
                (1010443,1010452,4,0),
                (1010443,1010453,5,0),
                (1010443,1010454,6,0),
                (1010443,1010455,7,0),
                (1010443,1010456,8,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values
                (1010443,1010449,1,1010449);

            insert into db_sysforkey values
                (1010443,1010450,1,1010083,0),
                (1010443,1010451,1,2353,0),
                (1010443,1010452,1,3706,0),
                (1010443,1010453,1,1010094,0),
                (1010443,1010454,1,1010090,0);

            insert into db_sysindices values
                (1008450,'turmaatividadecomplementar_turma_in',1010443,'0'),
                (1008452,'turmaatividadecomplementar_atividadecomplementar_in',1010443,'0'),
                (1008453,'turmaatividadecomplementar_funcaoatividade_in',1010443,'0'),
                (1008454,'turmaatividadecomplementar_rechumanoescola_in',1010443,'0'),
                (1008455,'turmaatividadecomplementar_diasemana_in',1010443,'0');

            insert into db_syscadind values
                (1008450,1010450,1),
                (1008452,1010451,1),
                (1008453,1010452,1),
                (1008454,1010453,1),
                (1008455,1010454,1);

            insert into db_syssequencia values
                (1000832, 'turmaatividadecomplementar_ed146_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            update db_sysarqcamp set codsequencia = 1000832 where codarq = 1010443 and codcam = 1010449;
SQL
        );
    }

    private function criaTabela()
    {
        $this->execute(<<<SQL
            CREATE SEQUENCE turmaatividadecomplementar_ed146_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

            CREATE TABLE escola.turmaatividadecomplementar(
                ed146_sequencial int4 not null,
                ed146_turma int4 not null,
                ed146_censoativcompl int4 not null,
                ed146_funcaoatividade int4 not null,
                ed146_rechumanoescola int4 not null,
                ed146_diasemana int4 not null,
                ed146_horainicial time not null,
                ed146_horafinal time not null,
                CONSTRAINT turmaatividadecomplementar_sequ_pk PRIMARY KEY (ed146_sequencial)
            );

            ALTER TABLE turmaatividadecomplementar ADD CONSTRAINT turmaatividadecomplementar_turma_fk FOREIGN KEY (ed146_turma) REFERENCES turma;
            ALTER TABLE turmaatividadecomplementar ADD CONSTRAINT turmaatividadecomplementar_atividadecomplementar_fk FOREIGN KEY (ed146_censoativcompl) REFERENCES censoativcompl;
            ALTER TABLE turmaatividadecomplementar ADD CONSTRAINT turmaatividadecomplementar_rechumanoescola_fk FOREIGN KEY (ed146_rechumanoescola) REFERENCES rechumanoescola;
            ALTER TABLE turmaatividadecomplementar ADD CONSTRAINT turmaatividadecomplementar_diasemana_fk FOREIGN KEY (ed146_diasemana) REFERENCES diasemana;
            ALTER TABLE turmaatividadecomplementar ADD CONSTRAINT turmaatividadecomplementar_funcaoatividade_fk FOREIGN KEY (ed146_funcaoatividade) REFERENCES funcaoatividade;

            CREATE INDEX turmaatividadecomplementar_turma_in ON turmaatividadecomplementar(ed146_turma);
            CREATE INDEX turmaatividadecomplementar_atividadecomplementar_in ON turmaatividadecomplementar(ed146_censoativcompl);
            CREATE INDEX turmaatividadecomplementar_funcaoatividade_in ON turmaatividadecomplementar(ed146_funcaoatividade);
            CREATE INDEX turmaatividadecomplementar_rechumanoescola_in ON turmaatividadecomplementar(ed146_rechumanoescola);
            CREATE INDEX turmaatividadecomplementar_diasemana_in ON turmaatividadecomplementar(ed146_diasemana);
SQL
        );
    }

    private function removeTabela()
    {
        $this->execute(<<<SQL
            drop table escola.turmaatividadecomplementar;
            drop sequence turmaatividadecomplementar_ed146_sequencial_seq;
SQL
        );
    }

    private function removeDicionarioDeDados()
    {
        $this->execute(<<<SQL
            delete from db_syssequencia where codsequencia = 1000832;
            delete from db_syscadind where codind in (1008450, 1008452, 1008453, 1008454, 1008455) and codcam in (1010450, 1010451, 1010452, 1010453, 1010454);
            delete from db_sysindices where codind in (1008450, 1008452, 1008453, 1008454, 1008455);

            delete from db_sysforkey where
                codarq in (1010443, 1010443, 1010443, 1010443, 1010443) and
                codcam in (1010450, 1010451, 1010452, 1010453, 1010454) and
                sequen in (1) and
                referen in (1010083, 2353, 3706, 1010094, 1010090);

            delete from db_sysprikey where codarq = 1010443 and codcam = 1010449 and sequen = 1 and camiden = 1010449;

            delete from db_sysarqcamp where
                codarq in (1010443, 1010443, 1010443, 1010443, 1010443, 1010443, 1010443, 1010443) and
                codcam in (1010449, 1010450, 1010451, 1010452, 1010453, 1010454, 1010455, 1010456) and
                seqarq in (1, 2, 3, 4, 5, 6, 7, 8);

            delete from db_syscampo where codcam in (1010449, 1010450, 1010451, 1010452, 1010453, 1010454, 1010455, 1010456);

            delete from db_sysarqmod where codmod = 1008004 and codarq = 1010443;

            delete from db_sysarquivo where codarq = 1010443;
SQL
        );
    }
}
