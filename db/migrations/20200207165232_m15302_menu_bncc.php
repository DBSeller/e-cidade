<?php

use Classes\PostgresMigration;

class M15302MenuBncc extends PostgresMigration
{
    public function up()
    {
        $this->menu();
        $this->dicionario();
        $this->ddl();
    }

    public function down()
    {
        $this->execute("update db_itensmenu set id_item = 1985519 , descricao = 'Lançamentos - Frequência/Conteúdo', help = 'Lançamentos - Frequência/Conteúdo',  desctec = 'Lançamentos - Frequência/Conteúdo' where id_item = 1985519;");
        $this->execute("delete from db_menu where id_item_filho = 228234");
        $this->execute("delete from db_itensmenu where id_item = 228234");

        $this->execute("
            DROP TABLE IF EXISTS diario_classe_bncc CASCADE;
            DROP TABLE IF EXISTS diario_classe_bncc_habilidade CASCADE;
            DROP SEQUENCE IF EXISTS diario_classe_bncc_ed155_codigo_seq;
            DROP SEQUENCE IF EXISTS diario_classe_bncc_habilidade_ed156_codigo_seq;
        ");

        $this->execute("
        delete from db_sysprikey where codarq in (1010520, 1010521);
        delete from db_sysforkey where codarq in (1010520, 1010521);
        delete from db_syscadind where codind in (1008521, 1008519, 1008520, 1008522);
        delete from db_sysindices where codind in (1008521, 1008519, 1008520, 1008522);
        delete from db_syssequencia where codsequencia in (1000877, 1000878);
        delete from db_sysarqcamp where codarq in (1010520, 1010521);
        delete from db_syscampo where codcam in (1011000, 1011003, 1011004, 1011005, 1011006, 1011007, 1011008, 1011009, 1011010);
        delete from db_sysarqmod where codarq in (1010520, 1010521);
        delete from db_sysarquivo where codarq in (1010520, 1010521);
        ");
    }

    private function menu()
    {
        $this->execute("
        update db_itensmenu set id_item = 1985519, descricao = 'Lançamentos de Frequência', help = 'Lançamentos de Frequência', desctec = 'Lançamentos de Frequência' where id_item = 1985519;

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values ( 228234 ,'Lançamento de Conteúdo' ,'Lançamento de Conteúdo' ,'edu4_lancamento_conteudo.php' ,'1' ,'1' ,'Lança o conteúdo desenvolvido no dia de aula. E permite lançar as habilidades da BNCC' ,'true' );

        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1100930 ,228234 ,7 ,1100747 );
        ");
    }

    private function ddl()
    {
        $this->execute("
            CREATE SEQUENCE diario_classe_bncc_ed155_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE SEQUENCE diario_classe_bncc_habilidade_ed156_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        ");

        $this->execute("
            CREATE TABLE diario_classe_bncc(
            ed155_codigo		int4 NOT NULL,
            ed155_regencia  	int4 NOT NULL,
            ed155_db_usuarios  	int4 NOT NULL,
            ed155_data  	    date NOT NULL,
            ed155_conteudo  	text NOT NULL,
            CONSTRAINT diario_classe_bncc_codi_pk PRIMARY KEY (ed155_codigo));
        ");

        $this->execute("
            CREATE TABLE diario_classe_bncc_habilidade(
            ed156_codigo		        int4 NOT NULL,
            ed156_diario_classe_bncc  	int4 NOT NULL,
            ed156_bnccdisciplinas  	    int4 NOT NULL,
            ed156_habilidade  	        varchar(10) NOT NULL,
            CONSTRAINT diario_classe_bncc_habilidade_codi_pk PRIMARY KEY (ed156_codigo));
        ");

        $this->execute("
            ALTER TABLE diario_classe_bncc ADD CONSTRAINT diario_classe_bncc_usuarios_fk FOREIGN KEY (ed155_db_usuarios) REFERENCES db_usuarios;
            ALTER TABLE diario_classe_bncc ADD CONSTRAINT diario_classe_bncc_regencia_fk FOREIGN KEY (ed155_regencia) REFERENCES regencia;
            ALTER TABLE diario_classe_bncc_habilidade ADD CONSTRAINT diario_classe_bncc_habilidade_classe_fk FOREIGN KEY (ed156_diario_classe_bncc) REFERENCES diario_classe_bncc ON DELETE CASCADE;
            ALTER TABLE diario_classe_bncc_habilidade ADD CONSTRAINT diario_classe_bncc_habilidade_bnccdisciplinas_fk FOREIGN KEY (ed156_bnccdisciplinas) REFERENCES bnccdisciplinas;

            CREATE INDEX diario_classe_bncc_regencia_in ON diario_classe_bncc(ed155_regencia);
            CREATE INDEX diario_classe_bncc_db_usuarios_in ON diario_classe_bncc(ed155_db_usuarios);
            CREATE INDEX diario_classe_bncc_habilidade_diario_classe_bnc_in ON diario_classe_bncc_habilidade(ed156_diario_classe_bncc);
            CREATE INDEX diario_classe_bncc_habilidade_bnccdisciplinas_in ON diario_classe_bncc_habilidade(ed156_bnccdisciplinas);
        ");
    }

    private function dicionario()
    {
        $this->execute("
        insert into db_sysarquivo
            values  (1010520, 'diario_classe_bncc', 'Guarda o conteúdo desenvolvido no dia', 'ed155', '2020-02-07', '', 0, 'f', 'f', 'f', 'f' ),
                    (1010521, 'diario_classe_bncc_habilidade', 'Guarda as habilidades dos conteudos desenvolvidos', 'ed156', '2020-02-07', '', 0, 'f', 'f', 'f', 'f' );

        insert into db_sysarqmod
        values (1008004,1010520),
               (1008004,1010521);

        insert into db_syscampo
            values  (1011000,'ed155_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
                    (1011003,'ed155_db_usuarios','int4','Código do professor','0', 'Professor',10,'f','f','f',1,'text','Professor'),
                    (1011004,'ed155_regencia','int4','Código da regência','0', 'Regência',10,'f','f','f',1,'text','Regência'),
                    (1011005,'ed155_data','date','Dia da aula','null', 'Data',10,'f','f','f',1,'text','Data'),
                    (1011006,'ed155_conteudo','text','Descrição do conteúdo desenvolvido','', 'Conteúdo',1,'f','f','f',0,'text','Conteúdo'),
                    (1011007,'ed156_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
                    (1011008,'ed156_diario_classe_bncc','int4','Fk diario_classe_bncc','0', 'Diário de Classe BNCC',10,'f','f','f',1,'text','Diário de Classe BNCC'),
                    (1011009,'ed156_bnccdisciplinas','int4','Disciplina BNCC','0', 'Disciplina BNCC',10,'f','f','f',1,'text','Disciplina BNCC'),
                    (1011010,'ed156_habilidade','varchar(10)','Habilidade desenvolvida','', 'Habilidade',10,'f','t','f',0,'text','Habilidade');

        insert into db_sysarqcamp
            values  (1010520,1011000,1,0),
                    (1010520,1011003,3,0),
                    (1010520,1011004,2,0),
                    (1010520,1011005,4,0),
                    (1010520,1011006,5,0),
                    (1010521,1011007,1,0),
                    (1010521,1011008,2,0),
                    (1010521,1011009,3,0),
                    (1010521,1011010,4,0);

        insert into db_sysprikey (codarq,codcam,sequen,camiden)
        values  (1010520,1011000,1,1011000),
                (1010521,1011007,1,1011007);

        insert into db_sysforkey
        values (1010520,1011003,1,109,0),
               (1010520,1011004,1,1010084,0),
               (1010521,1011008,1,1010520,0),
               (1010521,1011009,1,1010504,0);

        insert into db_sysindices
        values (1008519,'diario_classe_bncc_regencia_in',1010520,'0'),
               (1008520,'diario_classe_bncc_db_usuarios_in',1010520,'0'),
               (1008521,'diario_classe_bncc_habilidade_diario_classe_bnc_in',1010521,'0'),
               (1008522,'diario_classe_bncc_habilidade_bnccdisciplinas_in',1010521,'0');

        insert into db_syscadind
        values (1008521, 1011008, 1),
               (1008519, 1011004, 1),
               (1008520, 1011003, 1),
               (1008522, 1011009, 1);

        insert into db_syssequencia
        values  (1000877, 'diario_classe_bncc_ed155_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
                (1000878, 'diario_classe_bncc_habilidade_ed156_codigo_seq', 1, 1, 9223372036854775807, 1, 1);

        update db_sysarqcamp set codsequencia = 1000877 where codarq = 1010520 and codcam = 1011000;
        update db_sysarqcamp set codsequencia = 1000878 where codarq = 1010521 and codcam = 1011007;
        ");
    }
}
