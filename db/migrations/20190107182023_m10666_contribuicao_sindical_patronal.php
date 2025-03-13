<?php

use Classes\PostgresMigration;

class M10666ContribuicaoSindicalPatronal extends PostgresMigration
{
    public function up()
    {
        $this->menu();
        $this->dicionario();
        $this->estrutura();
    }

    private function menu()
    {
        $this->execute("
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228091 ,'Contribuição Sindical Patronal' ,'Contribuição Sindical Patronal' ,'eso1_contribuicaosindicalpatronal001.php' ,'1' ,'1' ,'Contribuição Sindical Patronal' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,228091 ,16 ,10216 );
        ");
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_sysarquivo 
            values (1010401, 'contribuicaosindicalperiodo', 'contribuicaosindicalperiodo', 'eso30', '2019-01-07', '', 0, 'f', 'f', 'f', 'f' ),
                   (1010402, 'contribuicaosindicalperiodosidicatos', 'Valor pago ao sindicato no período', 'eso31', '2019-01-07', '', 0, 'f', 'f', 'f', 'f' );

            insert into db_sysarqmod 
            values (81,1010401),
                  (81,1010402);   

            insert into db_syscampo
            values (1010271,'eso30_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
                   (1010272,'eso30_empregador','int4','Empregador','0', 'Empregador',10,'f','f','f',1,'text','Empregador'),
                   (1010273,'eso30_indicativo_periodo','int4','Indicativo de período','0', 'Indicativo de período',10,'f','f','f',1,'text','Indicativo de período'),
                   (1010274,'eso30_periodo','varchar(7)','Período','', 'Período',7,'f','t','f',0,'text','Período'),
                   (1010275,'eso31_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
                   (1010276,'eso31_rhsindicato','int4','Sindicato','0', 'Sindicato',10,'f','f','f',1,'text','Sindicato'),
                   (1010277,'eso31_tipo','int4','Tipo de contribuição sindical.','0', 'Tipo',10,'f','f','f',1,'text','Tipo'),
                   (1010278,'eso31_valor','float8','Valor da contribuição sindical a ser paga.','0', 'Valor',10,'f','f','f',4,'text','Valor'),
                   (1010279,'eso31_contribuicaosindicalperiodo','int4','Período','0', 'Período',10,'f','f','f',1,'text','Período');

            insert into db_sysarqcamp 
            values (1010401,1010271,1,0),
                   (1010401,1010272,2,0),
                   (1010401,1010273,3,0),
                   (1010401,1010274,4,0),
                   (1010402,1010275,1,0),
                   (1010402,1010276,2,0),
                   (1010402,1010277,3,0),
                   (1010402,1010278,4,0),
                   (1010402,1010279,5,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010401,1010271,1,1010271);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010402,1010275,1,1010275);

            insert into db_sysforkey 
            values (1010401,1010272,1,42,0),
                   (1010402,1010276,1,3481,0),
                   (1010402,1010279,1,1010401,0);

            insert into db_sysindices 
            values (1008418,'contribuicaosindicalperiodo_empregador_indicativo_periodo_in',1010401,'1'),
                   (1008419,'contribuicaosindicalperiodosidicatos_rhsindicato_in',1010402,'0'),
                   (1008420,'contribuicaosindicalperiodosidicatos_contribuicaosindicalperiodo_in',1010402,'0'),
                   (1008421,'contribuicaosindicalperiodosidicatos_contribuicaosindicalperiodo_rhsindicato_tipo_in',1010402,'1');

            insert into db_syscadind 
            values (1008418,1010272,1),
                   (1008418,1010273,2),
                   (1008418,1010274,3),
                   (1008419,1010276,1),
                   (1008420,1010279,1),
                   (1008421,1010279,1),
                   (1008421,1010276,2),
                   (1008421,1010277,3);

            insert into db_syssequencia 
            values (1000809, 'contribuicaosindicalperiodo_eso30_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000810, 'contribuicaosindicalperiodosidicatos_eso31_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            update db_sysarqcamp set codsequencia = 1000809 where codarq = 1010401 and codcam = 1010271;
            update db_sysarqcamp set codsequencia = 1000810 where codarq = 1010402 and codcam = 1010275;
        ");
    }

    private function estrutura()
    {
        $this->execute("
            CREATE SEQUENCE contribuicaosindicalperiodo_eso30_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE SEQUENCE contribuicaosindicalperiodosidicatos_eso31_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        ");

        $this->execute("
            CREATE TABLE contribuicaosindicalperiodo(
                eso30_sequencial int4 not null,
                eso30_empregador int4 not null,
                eso30_indicativo_periodo int4 not null,
                eso30_periodo varchar(7) not null,
                CONSTRAINT contribuicaosindicalperiodo_sequ_pk PRIMARY KEY (eso30_sequencial)
            );
            
            CREATE TABLE contribuicaosindicalperiodosidicatos(
                eso31_sequencial int4 not null,
                eso31_rhsindicato int4 not null,
                eso31_tipo int4 not null,
                eso31_valor numeric,
                eso31_contribuicaosindicalperiodo int4 not null,
                CONSTRAINT contribuicaosindicalperiodosidicatos_sequ_pk PRIMARY KEY (eso31_sequencial)
            );
        ");

        $this->execute("
            ALTER TABLE contribuicaosindicalperiodo ADD CONSTRAINT contribuicaosindicalperiodo_empregador_fk FOREIGN KEY (eso30_empregador) REFERENCES cgm;
            ALTER TABLE contribuicaosindicalperiodosidicatos ADD CONSTRAINT contribuicaosindicalperiodosidicatos_rhsindicato_fk FOREIGN KEY (eso31_rhsindicato) REFERENCES rhsindicato;
            ALTER TABLE contribuicaosindicalperiodosidicatos ADD CONSTRAINT contribuicaosindicalperiodosidicatos_contribuicaosindicalperiodo_fk FOREIGN KEY (eso31_contribuicaosindicalperiodo) REFERENCES contribuicaosindicalperiodo;
        ");
//
        $this->execute("
            CREATE UNIQUE INDEX contribuicaosindicalperiodo_empregador_indicativo_periodo_in ON contribuicaosindicalperiodo(eso30_empregador,eso30_indicativo_periodo,eso30_periodo);
            CREATE INDEX contribuicaosindicalperiodosidicatos_rhsindicato_in ON contribuicaosindicalperiodosidicatos(eso31_rhsindicato);
            CREATE INDEX contribuicaosindicalperiodosidicatos_contribuicaosindicalperiodo_in ON contribuicaosindicalperiodosidicatos(eso31_contribuicaosindicalperiodo);
            CREATE UNIQUE INDEX contribuicaosindicalperiodosidicatos_tipo_contribuicaosindicalperiodo_rhsindicato_in ON contribuicaosindicalperiodosidicatos(eso31_contribuicaosindicalperiodo,eso31_rhsindicato,eso31_tipo);
        ");
    }

    public function down()
    {
        // menu
        $this->execute("
            delete from db_menu where id_item_filho = 228091 AND modulo = 10216;
            delete from db_itensmenu where id_item = 228091;
        ");
        // dicionario
        $this->execute("
            delete from db_syssequencia where codsequencia in (1000809, 1000810);
            delete from db_syscadind where codind in (1008418, 1008419, 1008420, 1008421);
            delete from db_sysindices where codind in (1008418, 1008419, 1008420, 1008421);
            delete from db_sysprikey where codarq in (1010401, 1010402);
            delete from db_sysforkey where codarq in (1010401, 1010402);
            delete from db_sysarqcamp where codarq in (1010401, 1010402);
            delete from db_sysarqmod where codarq in (1010401, 1010402);
            delete from db_sysarquivo where codarq in (1010401, 1010402);
            delete from db_syscampo where codcam in (1010271, 1010272, 1010273, 1010274, 1010275, 1010276, 1010277, 1010278, 1010279);
        ");
        // estrutura
        $this->execute("
            DROP TABLE IF EXISTS contribuicaosindicalperiodo CASCADE;
            DROP TABLE IF EXISTS contribuicaosindicalperiodosidicatos CASCADE;
            DROP SEQUENCE IF EXISTS contribuicaosindicalperiodo_eso30_sequencial_seq;
            DROP SEQUENCE IF EXISTS contribuicaosindicalperiodosidicatos_eso31_sequencial_seq;
        ");
    }
}
