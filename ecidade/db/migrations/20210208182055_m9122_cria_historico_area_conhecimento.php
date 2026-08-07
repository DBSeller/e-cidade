<?php

use Classes\PostgresMigration;

class M9122CriaHistoricoAreaConhecimento extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
        $this->migrarDados2020();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function upDicionario()
    {
        $this->execute(<<<sql
insert into db_sysarquivo
    values (1010677, 'historicompsarea', 'Histórico MPS Área MPS = Matricula por serie Grava o vínculo das etapas no histórico com uma área de conhecimento, quando cursada em uma escola da rede.', 'ed170', '2021-02-08', 'Histórico MPS Área', 0, 'f', 'f', 'f', 'f' ),
           (1010678, 'areahistmpsdisc', 'Grava o vinculo de historicompsarea com histmpsdisc, é a Area de Conhecimento de uma etapa no histórico com uma disciplina.', 'ed171', '2021-02-08', 'Histórico Area de Conhecimento por Etapa', 0, 'f', 'f', 'f', 'f' ),
           (1010679, 'historicompsforaarea', 'Histórico MPS (Matrícula por Série) Fora da Rede com Área de Conhecimento', 'ed172', '2021-02-08', 'Histórico MPS Fora Area', 0, 'f', 'f', 'f', 'f' ),
           (1010680, 'areahistmpsdiscfora', 'Disciplinas do Histórico por Area de Conhecimento de Etapa Cursada Fora da Rede', 'ed173', '2021-02-08', 'Histórico Area de Conhecimento por Etapa Fora', 0, 'f', 'f', 'f', 'f' );

insert into db_sysarqmod
    values
        (1008004,1010677),
        (1008004,1010678),
        (1008004,1010679),
        (1008004,1010680);

insert into db_syscampo
    values
        (1012045,'ed170_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
        (1012046,'ed170_historicomps','int4','Chave estrangeira que referencia historicomps','0', 'Histórico Mps',10,'f','f','f',1,'text','Histórico Mps'),
        (1012047,'ed170_areaconhecimento','int4','Chave estrangeira que referencia areaconhecimento','0', 'Área de Conhecimento',10,'f','f','f',1,'text','Área de Conhecimento'),
        (1012048,'ed170_resultadoobtido','text','Resultado obtido na área de conhecimento','', 'Resultado Obtido',1,'t','f','f',0,'text','Resultado Obtido'),
        (1012049,'ed170_resultadofinal','char(1)','Resultado final da Etapa na área de conhecimento','', 'Resultado Final',1,'t','f','f',0,'text','Resultado Final'),
        (1012050,'ed172_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
        (1012051,'ed172_historicompsfora','int4','Grava o vínculo com historicompsfora','0', 'Histórico MPS Fora',10,'f','f','f',1,'text','Histórico MPS Fora'),
        (1012052,'ed172_areaconhecimento','float4','Grava o vínculo com a Área de Conhecimento','0', 'Área de Conhecimento',10,'f','f','f',4,'text','Área de Conhecimento'),
        (1012053,'ed172_resultadoobtido','text','Resultado Obtido na Área de Conhecimento em escolas de Fora da Rede','', 'Resultado Obtido',1,'t','f','f',0,'text','Resultado Obtido'),
        (1012054,'ed172_resultadofinal','char(1)','Resultado Final Obtido na Área de Conhecimento','', 'Resultado FInal',1,'t','f','f',0,'text','Resultado FInal'),
        (1012055,'ed171_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
        (1012056,'ed171_historicompsarea','int4','Grava o vínculo com historicompsarea','0', 'Histórico MPS Área',10,'f','f','f',1,'text','Histórico MPS Área'),
        (1012057,'ed171_histmpsdisc','int4','Grava o vínculo com histmpsdisc','0', 'Histórico MPS Disciplina',10,'f','f','f',1,'text','Histórico MPS Disciplina'),
        (1012058,'ed173_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
        (1012059,'ed173_historicompsforaarea','int4','Grava o vínculo com historicompsforaarea','0', 'Histórico MPS Fora Área',10,'f','f','f',1,'text','Histórico MPS Fora Área'),
        (1012060,'ed173_histmpsdiscfora','int4','Grava o vínculo com histmpsdiscfora','0', 'Histórico MPS Disciplina Fora',10,'f','f','f',1,'text','Histórico MPS Disciplina Fora');

insert into db_sysarqcamp
    values
        (1010677,1012045,1,0),
        (1010677,1012046,2,0),
        (1010677,1012047,3,0),
        (1010677,1012048,4,0),
        (1010677,1012049,5,0),
        (1010679,1012050,1,0),
        (1010679,1012051,2,0),
        (1010679,1012052,3,0),
        (1010679,1012053,4,0),
        (1010679,1012054,5,0),
        (1010678,1012055,1,0),
        (1010678,1012056,2,0),
        (1010678,1012057,3,0),
        (1010680,1012058,1,0),
        (1010680,1012059,2,0),
        (1010680,1012060,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden)
    values
        (1010677,1012045,1,1012045),
        (1010679,1012050,1,1012050),
        (1010678,1012055,1,1012055),
        (1010680,1012058,1,1012058);

insert into db_sysforkey
    values
        (1010677,1012046,1,1010132,0),
        (1010677,1012047,1,3258,0),
        (1010679,1012051,1,1010157,0),
        (1010679,1012052,1,3258,0),
        (1010678,1012056,1,1010677,0),
        (1010678,1012057,1,1010133,0),
        (1010680,1012059,1,1010679,0),
        (1010680,1012060,1,1010159,0);

insert into db_sysindices
    values
        (1008632,'historicompsarea_historicomps_in',1010677,'0'),
        (1008633,'historicompsarea_areaconhecimento_in',1010677,'0'),
        (1008626,'historicompsforaarea_historicompsfora_in',1010679,'0'),
        (1008634,'historicompsforaarea_areaconhecimento_in',1010679,'0'),
        (1008628,'areahistmpsdisc_historicompsarea_in',1010678,'0'),
        (1008629,'areahistmpsdisc_histmpsdisc_in',1010678,'0'),
        (1008630,'areahistmpsdiscfora_historicompsarea_in',1010680,'0'),
        (1008631,'areahistmpsdiscfora_histmpsdiscfora_in',1010680,'0');

insert into db_syscadind
    values
        (1008632,1012046,1),
        (1008633,1012047,1),
        (1008626,1012051,1),
        (1008634,1012052,1),
        (1008628,1012056,1),
        (1008629,1012057,1),
        (1008630,1012059,1),
        (1008631,1012060,1);

insert into db_syssequencia
    values
        (1000990, 'historicompsarea_ed170_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
        (1000991, 'areahistmpsdisc_ed171_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
        (1000992, 'historicompsforaarea_ed172_codigo_seq', 1, 1, 9223372036854775807, 1, 1),
        (1000993, 'areahistmpsdiscfora_ed173_codigo_seq', 1, 1, 9223372036854775807, 1, 1);

update db_sysarqcamp set codsequencia = 1000990 where codarq = 1010677 and codcam = 1012045;
update db_sysarqcamp set codsequencia = 1000991 where codarq = 1010678 and codcam = 1012055;
update db_sysarqcamp set codsequencia = 1000992 where codarq = 1010679 and codcam = 1012050;
update db_sysarqcamp set codsequencia = 1000993 where codarq = 1010680 and codcam = 1012058;
sql
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<sql
delete from db_syssequencia where codsequencia in (1000990, 1000991, 1000992, 1000993);
delete from db_syscadind where codind in (1008632, 1008633, 1008626, 1008634, 1008628, 1008629, 1008630, 1008631);
delete from db_sysindices where codind in (1008632, 1008633, 1008626, 1008634, 1008628, 1008629, 1008630, 1008631);
delete from db_sysforkey where codarq in (1010677, 1010679, 1010678, 1010680);
delete from db_sysprikey where codarq in (1010677, 1010679, 1010678, 1010680);
delete from db_sysarqcamp where codarq in (1010677, 1010679, 1010678, 1010680);
delete from db_syscampo where codcam in (1012045, 1012046, 1012047, 1012048, 1012049, 1012050, 1012051, 1012052,
                                         1012053, 1012054, 1012055, 1012056, 1012057, 1012058, 1012059, 1012060);
delete from db_sysarqmod where codarq in (1010677, 1010679, 1010678, 1010680);
delete from db_sysarquivo where codarq in (1010677, 1010679, 1010678, 1010680);
sql
        );
    }

    private function upEstrutura()
    {
        $this->execute(<<<sql
CREATE TABLE escola.historicompsarea
(
    ed170_codigo serial,
    ed170_historicomps int4 not null,
    ed170_areaconhecimento int4 not null,
    ed170_resultadoobtido text,
    ed170_resultadofinal char(1),
    CONSTRAINT historicompsarea_codigo_pk PRIMARY KEY (ed170_codigo),
    CONSTRAINT historicompsarea_historicomps_fk FOREIGN KEY (ed170_historicomps)
        REFERENCES escola.historicomps(ed62_i_codigo) ON DELETE CASCADE,
    CONSTRAINT historicompsarea_areaconhecimento_fk FOREIGN KEY (ed170_areaconhecimento)
        REFERENCES escola.areaconhecimento(ed293_sequencial)
);
CREATE UNIQUE INDEX historicompsarea_historicomps_areaconhecimento_in
        ON historicompsarea (ed170_historicomps, ed170_areaconhecimento);
CREATE INDEX historicompsarea_historicomps_in ON escola.historicompsarea(ed170_historicomps);
CREATE INDEX historicompsarea_areaconhecimento_in ON escola.historicompsarea(ed170_areaconhecimento);

CREATE TABLE escola.areahistmpsdisc
(
    ed171_codigo serial,
    ed171_historicompsarea int4 not null,
    ed171_histmpsdisc int4 not null,
    CONSTRAINT areahistmpsdisc_codigo_pk PRIMARY KEY (ed171_codigo),
    CONSTRAINT areahistmpsdisc_historicompsarea_fk FOREIGN KEY (ed171_historicompsarea)
        REFERENCES escola.historicompsarea(ed170_codigo) ON DELETE CASCADE,
    CONSTRAINT areahistmpsdisc_histmpsdisc_fk FOREIGN KEY (ed171_histmpsdisc)
        REFERENCES escola.histmpsdisc(ed65_i_codigo)  ON DELETE CASCADE
);
CREATE INDEX areahistmpsdisc_historicompsarea_in ON escola.areahistmpsdisc(ed171_historicompsarea);
CREATE INDEX areahistmpsdisc_histmpsdisc_in ON escola.areahistmpsdisc(ed171_histmpsdisc);

CREATE TABLE escola.historicompsforaarea
(
    ed172_codigo serial,
    ed172_historicompsfora int4 not null,
    ed172_areaconhecimento int4 not null,
    ed172_resultadoobtido text,
    ed172_resultadofinal char(1),
    CONSTRAINT historicompsforaarea_codigo_pk PRIMARY KEY (ed172_codigo),
    CONSTRAINT historicompsforaarea_historicompsfora_fk FOREIGN KEY (ed172_historicompsfora)
        REFERENCES escola.historicompsfora(ed99_i_codigo) ON DELETE CASCADE,
    CONSTRAINT historicompsforaarea_areaconhecimento_fk FOREIGN KEY (ed172_areaconhecimento)
        REFERENCES escola.areaconhecimento(ed293_sequencial)
);
CREATE UNIQUE INDEX historicompsforaarea_historicompsfora_areaconhecimento_in
        ON historicompsforaarea (ed172_historicompsfora, ed172_areaconhecimento);
CREATE INDEX historicompsforaarea_historicompsfora_in ON escola.historicompsforaarea(ed172_historicompsfora);
CREATE INDEX historicompsforaarea_areaconhecimento_in ON escola.historicompsforaarea(ed172_areaconhecimento);

CREATE TABLE escola.areahistmpsdiscfora
(
    ed173_codigo serial,
    ed173_historicompsforaarea int4 not null,
    ed173_histmpsdiscfora int4 not null,
    CONSTRAINT areahistmpsdiscfora_codigo_pk PRIMARY KEY (ed173_codigo),
    CONSTRAINT areahistmpsdiscfora_historicompsforaarea_fk FOREIGN KEY (ed173_historicompsforaarea)
        REFERENCES escola.historicompsforaarea(ed172_codigo) ON DELETE CASCADE,
    CONSTRAINT areahistmpsdiscfora_histmpsdiscfora_fk FOREIGN KEY (ed173_histmpsdiscfora)
        REFERENCES escola.histmpsdiscfora(ed100_i_codigo) ON DELETE CASCADE
);
CREATE INDEX areahistmpsdiscfora_historicompsarea_in ON escola.areahistmpsdiscfora(ed173_historicompsforaarea);
CREATE INDEX areahistmpsdiscfora_histmpsdiscfora_in ON escola.areahistmpsdiscfora(ed173_histmpsdiscfora);
sql
        );
    }

    private function downEstrutura()
    {
        $this->execute(<<<sql
DROP INDEX IF EXISTS areahistmpsdiscfora_historicompsarea_in;
DROP INDEX IF EXISTS areahistmpsdiscfora_histmpsdiscfora_in;
DROP TABLE IF EXISTS escola.areahistmpsdiscfora;

DROP INDEX IF EXISTS historicompsforaarea_historicompsfora_in;
DROP INDEX IF EXISTS historicompsforaarea_areaconhecimento_in;
DROP TABLE IF EXISTS escola.historicompsforaarea;

DROP INDEX IF EXISTS areahistmpsdisc_historicompsarea_in;
DROP INDEX IF EXISTS areahistmpsdisc_histmpsdisc_in;
DROP TABLE IF EXISTS escola.areahistmpsdisc;

DROP INDEX IF EXISTS historicompsarea_historicomps_in;
DROP INDEX IF EXISTS historicompsarea_areaconhecimento_in;
DROP TABLE IF EXISTS escola.historicompsarea;
sql
        );
    }

    private function migrarDados2020()
    {
        $this->execute(<<<sql
create temporary table turmas2020 as (
    select  historicomps.ed62_i_codigo as historico_etapa,
            ed61_i_aluno as codigo_aluno,
            ed57_i_codigo as codigo_turma,
            ed62_i_serie as serie,
            ed59_i_disciplina as disciplina,
            ed59_areaconhecimento as areaconhecimento
    from historico
        join historicomps ON historicomps.ed62_i_historico = historico.ed61_i_codigo
        join turma ON turma.ed57_c_descr = historicomps.ed62_i_turma and historicomps.ed62_i_escola = turma.ed57_i_escola
        join calendario ON calendario.ed52_i_codigo = turma.ed57_i_calendario and calendario.ed52_i_ano = 2020
        join regencia ON regencia.ed59_i_turma = turma.ed57_i_codigo and ed62_i_serie = ed59_i_serie
        join procedimento ON procedimento.ed40_i_codigo = regencia.ed59_procedimento
        left join areaprocedimento ON areaprocedimento.ed157_procedimento = procedimento.ed40_i_codigo
    where historicomps.ed62_lancamentoautomatico is true
      and historicomps.ed62_i_anoref = calendario.ed52_i_ano
      and areaprocedimento.ed157_codigo is not null
);

create temporary table notas_area as (
    select turmas2020.*,
           case
               when ed37_c_tipo = 'NOTA'
                   then round(ed164_nota, 0)::text
                when ed37_c_tipo = 'PARECER'
                    then 'PD'
                when ed37_c_tipo = 'CONCEITO'
                    then ed164_conceito
                end as nota
    from turmas2020
        join diarioaluno on codigo_aluno = ed161_aluno
            and codigo_turma = ed161_turma
            and serie = ed161_serie
        join diarioarea on ed162_diarioaluno = ed161_codigo and ed162_areaconhecimento = areaconhecimento
        join diarioarearesultado on ed162_codigo = ed164_diarioarea
        join areaprocedimentoresultado on ed164_areaprocedimentoresultado = ed159_codigo
        join formaavaliacao on ed37_i_codigo = ed159_formaavaliacao
);

create temporary table notas as (
    select historico_etapa,
           areaconhecimento,
           nota,
           array_agg(disciplina)
    from notas_area
        where nota is not null
        group by historico_etapa, areaconhecimento, nota
);

create temporary table historicomps_area as (
    select distinct on (historico_etapa, areaconhecimento)
                    historico_etapa,
                    areaconhecimento,
                    case
                        when ed170_codigo is null
                            then nextval('historicompsarea_ed170_codigo_seq')
                        when ed170_codigo is not null
                            then ed170_codigo
                        end as codigo_historicompsarea
        from turmas2020
            left join historicompsarea on ed170_historicomps = historico_etapa
                                 and ed170_areaconhecimento = areaconhecimento
);

create temporary table inserts as (
    select historicomps_area.historico_etapa,
           codigo_historicompsarea,
           historicomps_area.areaconhecimento,
           nota,
           disciplina,
           ed65_i_codigo as codigo_histmpsdisc
    from notas_area
         join historicomps_area on notas_area.historico_etapa = historicomps_area.historico_etapa
            and notas_area.areaconhecimento = historicomps_area.areaconhecimento
         join histmpsdisc on ed65_i_historicomps = historicomps_area.historico_etapa
            and ed65_i_disciplina = disciplina
);

insert into historicompsarea select codigo_historicompsarea, historico_etapa, areaconhecimento, nota, null from (select distinct codigo_historicompsarea, historico_etapa, areaconhecimento, nota from inserts) as x;
insert into areahistmpsdisc select nextval('areahistmpsdisc_ed171_codigo_seq'), codigo_historicompsarea, codigo_histmpsdisc from inserts;

drop table if exists turmas2020;
drop table if exists notas_area;
drop table if exists notas;
drop table if exists historicomps_area;
drop table if exists inserts;
sql
);
    }
}
