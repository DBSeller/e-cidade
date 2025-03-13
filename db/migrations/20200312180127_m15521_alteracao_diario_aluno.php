<?php

use Classes\PostgresMigration;

class M15521AlteracaoDiarioAluno extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();
        $this->estrutura();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    private function dicionario()
    {
        $this->execute("
        insert into db_sysarquivo values (1010543, 'diarioaluno', 'Representa o diário do aluno ', 'ed161', '2020-03-12', 'Diário Aluno', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (1008004, 1010543);

        update db_sysarquivo
           set nomearq = 'diarioalunoresultadofinal',
               descricao = 'Resultado final do diário do aluno',
               rotulo = 'Diário resultado final'
         where codarq = 1010541;

        update db_syscampo
           set nomecam = 'ed165_diarioaluno',
               descricao = 'Diário Aluno',
               rotulo = 'Diário Aluno',
               rotulorel = 'Diário Aluno'
         where codcam = 1011137;

        -- remove campos da diarioarea
        delete from db_sysforkey where codcam in (1011118, 1011119, 1011120, 1011137);
        delete from db_syscadind where codind in (1008554, 1008559);
        delete from db_sysindices where codind in (1008554, 1008559);
        delete from db_sysarqcamp where codcam in (1011118, 1011119, 1011120, 1011139, 1011140);
        delete from db_syscampo where codcam in (1011118, 1011119, 1011120, 1011139, 1011140);
        delete from db_sysforkey where codcam in (1011118, 1011119, 1011120, 1011137);

        insert into db_syscampo
            values (1011151,'ed161_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
                   (1011152,'ed161_aluno','int4','Aluno','0', 'Aluno',10,'f','f','f',1,'text','Aluno'),
                   (1011153,'ed161_turma','int4','Turma','0', 'Turma',10,'f','f','f',1,'text','Turma'),
                   (1011154,'ed161_serie','int4','Etapa','0', 'Etapa',10,'f','f','f',1,'text','Etapa'),
                   (1011155,'ed161_encerrado','bool','Se o diário esta encerrado','f', 'Encerrado',1,'f','f','f',5,'text','Encerrado'),
                   (1011156,'ed162_diarioaluno','int4','Diário do aluno','0', 'Diário Aluno',10,'f','f','f',1,'text','Diário Aluno');

        insert into db_sysarqcamp
            values (1010543,1011151,1,0),
                   (1010543,1011152,2,0),
                   (1010543,1011153,3,0),
                   (1010543,1011154,4,0),
                   (1010543,1011155,5,0),
                   (1010538,1011156,3,0);

        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010543,1011151,1,1011151);

        insert into db_sysforkey
            values (1010543,1011152,1,1010051,0),
                   (1010543,1011153,1,1010083,0),
                   (1010543,1011154,1,1010047,0),
                   (1010541,1011137,1,1010543,0),
                   (1010538,1011156,1,1010543,0);

        insert into db_sysindices
            values (1008561,'diarioaluno_aluno_turma_serie_in',1010543,'1'),
                   (1008562,'diarioalunoresultadofinal_diarioaluno_in',1010541,'0'),
                   (1008563,'diarioarea_diarioaluno_in',1010538,'0');

        insert into db_syscadind
            values (1008561,1011152,1),
                   (1008561,1011153,2),
                   (1008561,1011154,3),
                   (1008562,1011137,1),
                   (1008563,1011156,1);

        insert into db_syssequencia values(1000895, 'diarioaluno_ed161_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000895 where codarq = 1010543 and codcam = 1011151;
        ");
    }

    private function dicionarioDown()
    {
        $this->execute("
            insert into db_syscampo
                values (1011118,'ed162_aluno','int4','Aluno','0', 'Aluno',10,'f','f','f',1,'text','Aluno'),
                       (1011119,'ed162_turma','int4','Etapa','0', 'Turma',10,'f','f','f',1,'text','Turma'),
                       (1011120,'ed162_serie','int4','Etapa','0', 'Etapa',10,'f','f','f',1,'text','Etapa'),
                       (1011139,'ed165_resultado_avaliacao','varchar(1)','Resultado da avaliação','', 'Resultado da Avaliação',1,'f','t','f',0,'text','Resultado da Avaliação'),
                       (1011140,'ed165_resultado_frequencia','varchar(1)','Resultado da Frequência','', 'Resultado da Frequência',1,'f','t','f',0,'text','Resultado da Frequência');

            insert into db_sysarqcamp
                values (1010538, 1011118, 3, 0),
                       (1010538, 1011119, 4, 0),
                       (1010538, 1011120, 5, 0),
                       (1010541, 1011139, 4, 0),
                       (1010541, 1011140, 5, 0);


            insert into db_sysforkey
                values (1010538, 1011118, 1, 1010051,0),
                       (1010538, 1011119, 1, 1010083,0),
                       (1010538, 1011120, 1, 1010047,0),
                       (1010541, 1011137, 1, 1010538,0);

            -- devolve indices
            insert into db_sysindices
                values (1008554,'diarioarea_vinculo_aluno_turma_in',1010538,'1'),
                       (1008559,'diarioarearesultadofinal_diarioarea_in',1010541,'0');

            insert into db_syscadind
                values (1008554,1011117,1),
                       (1008559,1011137,1);

            update db_syscampo
               set nomecam = 'ed165_diarioarea',
                   descricao = 'Diário Área Conhecimento',
                   rotulo = 'Diário Área Conhecimento',
                   rotulorel = 'Diário Área Conhecimento'
             where codcam = 1011137;

            update db_sysarquivo
               set nomearq = 'diarioarearesultadofinal',
                   descricao = 'Resultado final do diário por área de conhecimento',
                   rotulo = 'Diário resultado final'
             where codarq = 1010541;


            delete from db_sysforkey where codcam in (1011137, 1011156, 1011152, 1011153, 1011154);
            delete from db_sysprikey where codcam in (1011151);
            delete from db_syscadind where codind in (1008561, 1008562, 1008563);
            delete from db_sysindices where codind in (1008561, 1008562, 1008563);

            delete from db_sysarqcamp where codcam in (1011151, 1011152, 1011153, 1011154, 1011155, 1011156);
            delete from db_syscampo where codcam in (1011151, 1011152, 1011153, 1011154, 1011155, 1011156);

            delete from db_syssequencia where codsequencia = 1000895;
            delete from db_sysarqmod where codarq in (1010543);
            delete from db_sysarquivo where codarq in (1010543);
        ");
    }

    private function estruturaDown()
    {
        $this->execute("
            DROP TABLE IF EXISTS escola.diarioaluno CASCADE;
            DROP TABLE IF EXISTS escola.diarioalunoresultadofinal CASCADE;
            DROP SEQUENCE IF EXISTS escola.diarioaluno_ed161_codigo_seq;
            DROP SEQUENCE IF EXISTS escola.diarioarearesultadofinal_ed165_codigo_seq;
        ");
        $this->execute("
            alter table escola.diarioarea drop column ed162_diarioaluno;
            alter table escola.diarioarea add column ed162_aluno int4 not null;
            alter table escola.diarioarea add column ed162_turma int4 not null;
            alter table escola.diarioarea add column ed162_serie int4 not null;
        ");
    }

    private function estrutura()
    {
        $this->execute("
            create table escola.diarioaluno(
                ed161_codigo int4 not null,
                ed161_aluno int4 not null,
                ed161_turma int4 not null,
                ed161_serie int4 not null,
                ed161_encerrado bool default false,
                constraint diarioaluno_codi_pk primary key (ed161_codigo)
            );
        ");

        $this->execute("
            create table escola.diarioalunoresultadofinal(
                ed165_codigo  int4 not null,
                ed165_diarioaluno int4 not null,
                ed165_resultado_final char(1) not null,
                constraint diarioalunoresultadofinal_codi_pk primary key (ed165_codigo)
            );
        ");

        $this->execute("
            alter table escola.diarioarea add column ed162_diarioaluno int4 not null;
            alter table escola.diarioarea drop column ed162_aluno;
            alter table escola.diarioarea drop column ed162_turma;
            alter table escola.diarioarea drop column ed162_serie;
        ");

        $this->execute("
            create sequence escola.diarioaluno_ed161_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
        ");

        $this->execute("
            alter table escola.diarioaluno add constraint diarioaluno_turma_fk foreign key (ed161_turma) references escola.turma;
            alter table escola.diarioaluno add constraint diarioaluno_serie_fk foreign key (ed161_serie) references escola.serie;
            alter table escola.diarioaluno add constraint diarioaluno_aluno_fk foreign key (ed161_aluno) references escola.aluno;
            alter table escola.diarioalunoresultadofinal add constraint diarioalunoresultadofinal_diarioaluno_fk foreign key (ed165_diarioaluno) references escola.diarioaluno on delete cascade;
            alter table escola.diarioarea add constraint diarioarea_diarioaluno_fk foreign key (ed162_diarioaluno) references escola.diarioaluno on delete cascade;
        ");

        $this->execute("
            create unique index diarioaluno_aluno_turma_serie_in on escola.diarioaluno(ed161_aluno,ed161_turma,ed161_serie);
            create index diarioalunoresultadofinal_diarioaluno_in on escola.diarioalunoresultadofinal(ed165_diarioaluno);
            create index diarioarea_diarioaluno_in on escola.diarioarea(ed162_diarioaluno);
        ");

        $this->execute("drop table if exists escola.diarioarearesultadofinal;");
        $this->execute("drop sequence if exists escola.diarioarearesultadofinal_ed165_codigo_seq;");

        $this->execute("
        alter table escola.diarioareaavaliacao
            drop constraint diarioareaavaliacao_areaprocedimentoavaliacao_fk,
            add constraint diarioareaavaliacao_areaprocedimentoavaliacao_fk
                foreign key (ed163_areaprocedimentoavaliacao) references escola.areaprocedimentoavaliacao on delete cascade;

        alter table escola.diarioareaavaliacao
            drop constraint diarioareaavaliacao_diarioarea_fk,
            add constraint diarioareaavaliacao_diarioarea_fk
                foreign key (ed163_diarioarea) references escola.diarioarea on delete cascade;

        alter table diarioarearesultado
            drop constraint diarioarearesultado_diarioarea_fk,
            add constraint diarioarearesultado_diarioarea_fk
                foreign key (ed164_diarioarea) references diarioarea on delete cascade;

        alter table escola.diarioarearesultado
            drop constraint diarioarearesultado_areaprocedimentoresultado_fk,
            add constraint diarioarearesultado_areaprocedimentoresultado_fk
                foreign key (ed164_areaprocedimentoresultado) references escola.areaprocedimentoresultado on delete cascade;
        ");
    }
}
