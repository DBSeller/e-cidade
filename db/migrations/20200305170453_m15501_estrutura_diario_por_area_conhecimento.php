<?php

use Classes\PostgresMigration;

class M15501EstruturaDiarioPorAreaConhecimento extends PostgresMigration
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
        insert into db_sysarquivo
        values (1010538, 'diarioarea', 'Diário de classe por área de conhecimento', 'ed162', '2020-03-05', 'Diário Área Conhecimento', 0, 'f', 'f', 'f', 'f' ),
               (1010539, 'diarioareaavaliacao', 'Avaliações do diário por área de conhecimento', 'ed163', '2020-03-05', 'Diário Avaliação', 0, 'f', 'f', 'f', 'f' ),
               (1010540, 'diarioarearesultado', 'Resultado do diário por área de conhecimento', 'ed164', '2020-03-05', 'Diário Resultado', 0, 'f', 'f', 'f', 'f' ),
               (1010541, 'diarioarearesultadofinal', 'Resultado final do diário por área de conhecimento', 'ed165', '2020-03-05', 'Diário resultado final', 0, 'f', 'f', 'f', 'f' );
        ");
        $this->execute("
        insert into db_sysarqmod
        values (1008004,1010538),
               (1008004,1010539),
               (1008004,1010540),
               (1008004,1010541);
        ");
        $this->execute("
        insert into db_syscampo
        values (1011116,'ed162_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011117,'ed162_areaconhecimento','int4','Área de conhecimento','0', 'Área de Conhecimento',10,'f','f','f',1,'text','Área de Conhecimento'),
               (1011118,'ed162_aluno','int4','Aluno','0', 'Aluno',10,'f','f','f',1,'text','Aluno'),
               (1011119,'ed162_turma','int4','Etapa','0', 'Turma',10,'f','f','f',1,'text','Turma'),
               (1011120,'ed162_serie','int4','Etapa','0', 'Etapa',10,'f','f','f',1,'text','Etapa'),
               (1011121,'ed163_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011122,'ed163_diarioarea','int4','Vínculo com diário área conhecimento','0', 'Diário Área Conhecimento',10,'f','f','f',1,'text','Diário Área Conhecimento'),
               (1011123,'ed163_areaprocedimentoavaliacao','int4','Elemento responsável pelo cálculo da avaliação do aluno.','0', 'Período de Avaliação',10,'f','f','f',1,'text','Período de Avaliação'),
               (1011124,'ed163_nota','float8','Avaliação por nota','0', 'Nota',10,'t','f','f',4,'text','Nota'),
               (1011125,'ed163_parecer','text','Se avaliado por Parecer','', 'Parecer',1,'t','f','f',0,'text','Parecer'),
               (1011126,'ed163_conceito','varchar(3)','Se avaliado por conceito','', 'Conceito',3,'t','t','f',0,'text','Conceito'),
               (1011127,'ed163_amparado','bool','Se período foi amaprado','false', 'Amparado',1,'f','f','f',5,'text','Amparado'),
               (1011128,'ed164_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011129,'ed164_diarioarea','int4','Diário Área Conhecimento','0', 'Diário Área Conhecimento',10,'f','f','f',1,'text','Diário Área Conhecimento'),
               (1011130,'ed164_areaprocedimentoresultado','int4','Elemento do procedimento responsável pelo calculo do resultado.','0', 'Resultado',10,'f','f','f',1,'text','Resultado'),
               (1011131,'ed164_nota','float8','Nota final da área se avaliado por nota.','0', 'Nota ',10,'t','f','f',4,'text','Nota '),
               (1011132,'ed164_parecer','text','Parecer final da área se avaliado por parecer','', 'Parecer',1,'t','f','f',0,'text','Parecer'),
               (1011133,'ed164_conceito','varchar(3)','Conceito da área se avaliado por conceito','', 'Conceito',3,'t','f','f',0,'text','Conceito'),
               (1011134,'ed164_resultado_avaliacao','varchar(1)','Resultado das avaliações da área.','', 'Resultado da Avaliação',1,'f','t','f',0,'text','Resultado da Avaliação'),
               (1011135,'ed164_resultado_frequencia','varchar(1)','Resultado da frequência na área de conhecimento.','', 'Resultado da Frequência',1,'f','t','f',0,'text','Resultado da Frequência'),
               (1011136,'ed165_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
               (1011137,'ed165_diarioarea','int4','Diário Área Conhecimento','0', 'Diário Área Conhecimento',10,'f','f','f',1,'text','Diário Área Conhecimento'),
               (1011138,'ed165_resultado_final','varchar(1)','Resultado final','', 'Resultado final',1,'f','t','f',0,'text','Resultado final'),
               (1011139,'ed165_resultado_avaliacao','varchar(1)','Resultado da avaliação','', 'Resultado da Avaliação',1,'f','t','f',0,'text','Resultado da Avaliação'),
               (1011140,'ed165_resultado_frequencia','varchar(1)','Resultado da Frequência','', 'Resultado da Frequência',1,'f','t','f',0,'text','Resultado da Frequência');
        ");
        $this->execute("
        insert into db_sysarqcamp
        values (1010538,1011116,1,0),
               (1010538,1011117,2,0),
               (1010538,1011118,3,0),
               (1010538,1011119,4,0),
               (1010538,1011120,5,0),
               (1010539,1011121,1,0),
               (1010539,1011122,2,0),
               (1010539,1011123,3,0),
               (1010539,1011124,4,0),
               (1010539,1011125,5,0),
               (1010539,1011126,6,0),
               (1010539,1011127,7,0),
               (1010540,1011128,1,0),
               (1010540,1011129,2,0),
               (1010540,1011130,3,0),
               (1010540,1011131,4,0),
               (1010540,1011132,5,0),
               (1010540,1011133,6,0),
               (1010540,1011134,7,0),
               (1010540,1011135,8,0),
               (1010541,1011136,1,0),
               (1010541,1011137,2,0),
               (1010541,1011138,3,0),
               (1010541,1011139,4,0),
               (1010541,1011140,5,0);
        ");
        $this->execute("
        insert into db_sysprikey (codarq,codcam,sequen,camiden)
        values (1010538,1011116,1,1011116),
               (1010539,1011121,1,1011121),
               (1010540,1011128,1,1011128),
               (1010541,1011136,1,1011136);
        ");
        $this->execute("
        insert into db_sysforkey
            values (1010538,1011117,1,3258,0),
                   (1010538,1011118,1,1010051,0),
                   (1010538,1011119,1,1010083,0),
                   (1010538,1011120,1,1010047,0),
                   (1010539,1011122,1,1010538,0),
                   (1010539,1011123,1,1010534,0),
                   (1010540,1011129,1,1010538,0),
                   (1010540,1011130,1,1010535,0),
                   (1010541,1011137,1,1010538,0);
        ");
        $this->execute("
        insert into db_sysindices
        values (1008554,'diarioarea_vinculo_aluno_turma_in',1010538,'1'),
               (1008555,'diarioareaavaliacao_diarioarea_in',1010539,'0'),
               (1008556,'diarioareaavaliacao_areaprocedimentoavaliacao_in',1010539,'0'),
               (1008557,'diarioarearesultado_diarioarea_in',1010540,'0'),
               (1008558,'diarioarearesultado_areaprocedimentoresultado_in',1010540,'0'),
               (1008559,'diarioarearesultadofinal_diarioarea_in',1010541,'0');
        ");
        $this->execute("
        insert into db_syscadind
        values (1008554,1011117,1),
               (1008554,1011118,2),
               (1008554,1011119,3),
               (1008554,1011120,4),
               (1008555,1011122,1),
               (1008556,1011123,1),
               (1008557,1011129,1),
               (1008558,1011130,1),
               (1008559,1011137,1);
        ");
        $this->execute("
        insert into db_syssequencia
        values (1000888, 'diarioarea_ed162_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000889, 'diarioareaavaliacao_ed163_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000890, 'diarioarearesultado_ed164_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
               (1000891, 'diarioarearesultadofinal_ed165_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
        ");
        $this->execute("
        update db_sysarqcamp set codsequencia = 1000888 where codarq = 1010538 and codcam = 1011116;
        update db_sysarqcamp set codsequencia = 1000889 where codarq = 1010539 and codcam = 1011121;
        update db_sysarqcamp set codsequencia = 1000890 where codarq = 1010540 and codcam = 1011128;
        update db_sysarqcamp set codsequencia = 1000891 where codarq = 1010541 and codcam = 1011136;
        ");
    }

    private function dicionarioDown()
    {
        $this->execute("
        delete from db_sysprikey where codarq in (1010538, 1010539, 1010540, 1010541);
        delete from db_sysforkey where codarq in (1010538, 1010539, 1010540, 1010541);
        delete from db_syscadind where codind in (1008554, 1008555, 1008556, 1008557, 1008558, 1008559);
        delete from db_sysindices where codind in (1008554, 1008555, 1008556, 1008557, 1008558, 1008559);
        delete from db_syssequencia where codsequencia in (1000888, 1000889, 1000890, 1000891);
        delete from db_sysarqcamp where codarq in (1010538, 1010539, 1010540, 1010541);
        delete from db_sysarqmod where codarq in (1010538, 1010539, 1010540, 1010541);
        delete from db_syscampo where codcam in (1011116, 1011117, 1011118, 1011119, 1011120, 1011121, 1011122, 1011123, 1011124, 1011125, 1011126, 1011127, 1011128, 1011129, 1011130, 1011131, 1011132, 1011133, 1011134, 1011135, 1011136, 1011137, 1011138, 1011139, 1011140);
        delete from db_sysarquivo where codarq in (1010538, 1010539, 1010540, 1010541);
        ");
    }

    private function estrutura()
    {
        $this->execute("
        create sequence escola.diarioarea_ed162_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
        create sequence escola.diarioareaavaliacao_ed163_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
        create sequence escola.diarioarearesultado_ed164_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
        create sequence escola.diarioarearesultadofinal_ed165_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
        ");

        $this->execute("
            CREATE TABLE escola.diarioarea(
                ed162_codigo int4 not null,
                ed162_areaconhecimento int4 not null,
                ed162_aluno int4 not null,
                ed162_turma int4 not null,
                ed162_serie int4 not null,
                CONSTRAINT diarioarea_codi_pk PRIMARY KEY (ed162_codigo)
            );

            CREATE TABLE escola.diarioareaavaliacao(
                ed163_codigo int4 not null,
                ed163_diarioarea int4 not null,
                ed163_areaprocedimentoavaliacao int4 not null,
                ed163_nota float8,
                ed163_parecer text,
                ed163_conceito varchar,
                ed163_amparado boolean default false,
                CONSTRAINT diarioareaavaliacao_codi_pk PRIMARY KEY (ed163_codigo)
            );

            CREATE TABLE escola.diarioarearesultado(
                ed164_codigo int4 not null,
                ed164_diarioarea int4 not null,
                ed164_areaprocedimentoresultado int4 not null,
                ed164_nota float8,
                ed164_parecer text,
                ed164_conceito varchar(3),
                ed164_amparado boolean default false,
                ed164_resultado_avaliacao char(1) not null,
                ed164_resultado_frequencia char(1) not null,
                CONSTRAINT diarioarearesultado_codi_pk PRIMARY KEY (ed164_codigo)
            );

            CREATE TABLE escola.diarioarearesultadofinal(
                ed165_codigo int4 not null,
                ed165_diarioarea int4 not null,
                ed165_resultado_final char(1) not null,
                ed165_resultado_avaliacao char(1) not null,
                ed165_resultado_frequencia char(1) not null,
                CONSTRAINT diarioarearesultadofinal_codi_pk PRIMARY KEY (ed165_codigo)
            );
        ");

        $this->execute("
            alter table escola.diarioarea add constraint diarioarea_turma_fk foreign key (ed162_turma) references turma;
            alter table escola.diarioarea add constraint diarioarea_areaconhecimento_fk foreign key (ed162_areaconhecimento) references areaconhecimento;
            alter table escola.diarioarea add constraint diarioarea_aluno_fk foreign key (ed162_aluno) references aluno;
            alter table escola.diarioarea add constraint diarioarea_serie_fk foreign key (ed162_serie) references serie;
            alter table escola.diarioareaavaliacao add constraint diarioareaavaliacao_areaprocedimentoavaliacao_fk foreign key (ed163_areaprocedimentoavaliacao) references areaprocedimentoavaliacao;
            alter table escola.diarioareaavaliacao add constraint diarioareaavaliacao_diarioarea_fk foreign key (ed163_diarioarea) references diarioarea;
            alter table escola.diarioarearesultado add constraint diarioarearesultado_diarioarea_fk foreign key (ed164_diarioarea) references diarioarea;
            alter table escola.diarioarearesultado add constraint diarioarearesultado_areaprocedimentoresultado_fk foreign key (ed164_areaprocedimentoresultado) references areaprocedimentoresultado;
            alter table escola.diarioarearesultadofinal add constraint diarioarearesultadofinal_diarioarea_fk foreign key (ed165_diarioarea) references diarioarea;
        ");
        $this->execute("
            create unique index diarioarea_vinculo_aluno_turma_in ON escola.diarioarea(ed162_areaconhecimento, ed162_aluno, ed162_turma, ed162_serie);
            create index diarioareaavaliacao_diarioarea_in ON escola.diarioareaavaliacao(ed163_diarioarea);
            create index diarioareaavaliacao_areaprocedimentoavaliacao_in ON escola.diarioareaavaliacao(ed163_areaprocedimentoavaliacao);
            create index diarioarearesultado_diarioarea_in ON escola.diarioarearesultado(ed164_diarioarea);
            create index diarioarearesultado_areaprocedimentoresultado_in ON escola.diarioarearesultado(ed164_areaprocedimentoresultado);
            create index diarioarearesultadofinal_diarioarea_in ON escola.diarioarearesultadofinal(ed165_diarioarea);
        ");
    }

    private function estruturaDown()
    {
        $this->execute("
        drop table if exists escola.diarioarea CASCADE;
        drop table if exists escola.diarioareaavaliacao CASCADE;
        drop table if exists escola.diarioarearesultado CASCADE;
        drop table if exists escola.diarioarearesultadofinal CASCADE;
        ");

        $this->execute("
        drop sequence if exists escola.diarioarea_ed162_codigo_seq;
        drop sequence if exists escola.diarioareaavaliacao_ed163_codigo_seq;
        drop sequence if exists escola.diarioarearesultado_ed164_codigo_seq;
        drop sequence if exists escola.diarioarearesultadofinal_ed165_codigo_seq;
        ");
    }
}
