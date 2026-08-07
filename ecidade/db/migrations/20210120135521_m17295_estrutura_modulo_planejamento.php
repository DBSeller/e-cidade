<?php

use Classes\PostgresMigration;

class M17295EstruturaModuloPlanejamento extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
create schema planejamento;

CREATE TYPE tipo_planejamento as enum (
  'PPA',
  'LDO',
  'LOA'
);

CREATE TYPE origem_valores AS ENUM (
  'PROGRAMA ESTRATEGICO',
  'INICIATIVA',
  'OBJETIVOS',
  'DETALHAMENTO INICIATIVA',
  'RECEITA'
);

create table planejamento.status(
    pl1_codigo int primary key,
    pl1_descricao varchar(255) not null
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.status');

insert into planejamento.status values
    (1, 'Em Desenvolvimento'),
    (2, 'Encaminhado ao Poder Legislativo'),
    (3, 'Aprovado com emendas'),
    (4, 'Aprovado'),
    (5, 'Retificado');


create table planejamento.ods (
    id serial primary key,
    pl26_codigo varchar(5),
    pl26_descricao varchar(60),
    created_at timestamp ,
    updated_at timestamp
);

insert into planejamento.ods(pl26_codigo, pl26_descricao) values
('1', 'Erradicação da pobreza'),
('2', 'Fome zero e agricultura sustentável'),
('3', 'Saúde e bem-estar'),
('4', 'Educação de qualidade'),
('5', 'Igualdade de gênero'),
('6', 'Água potável'),
('7', 'Energia Limpa e Acessível'),
('8', 'Trabalho decente e crescimento econômico'),
('9', 'Indústria, inovação e infraestrutura'),
('10', 'Redução das desigualdades'),
('11', 'Cidades e comunidades sustentáveis'),
('12', 'Consumo e produção responsável'),
('13', 'Ação contra a mudança global do clima'),
('14', 'Vida na água'),
('15', 'Vida terrestre'),
('16', 'Paz, justiça e instituições eficazes'),
('17', 'Parcerias e meios de implementação');

create index ods_codigo_in on planejamento.ods(pl26_codigo);
create index ods_descricao_in on planejamento.ods(pl26_descricao);
select configuracoes.fc_auditoria_cria_funcao('planejamento.ods');

CREATE TABLE planejamento.periodoacao (
  pl14_codigo SERIAL PRIMARY KEY,
  pl14_descricao varchar,
  created_at timestamp,
  updated_at timestamp
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.periodoacao');

create index periodoacao_descricao_in on planejamento.periodoacao(pl14_descricao);
insert into planejamento.periodoacao (pl14_descricao)
values ('Mensal'),
       ('Bimestral'),
       ('Trimestral'),
       ('Quadrimestral'),
       ('Semestral'),
       ('Anual');

CREATE TABLE planejamento.origeminiciativa (
  pl13_codigo SERIAL PRIMARY KEY,
  pl13_descricao varchar,
  created_at timestamp,
  updated_at timestamp
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.origeminiciativa');

insert into planejamento.origeminiciativa(pl13_descricao)
values ('Sociedade'),
       ('Poder Público'),
       ('Sociedade e Poder Público '),
       ('Poder Público e Emenda Parlamentar'),
       ('Sociedade e Emenda Parlamentar'),
       ('Emenda Parlamentar, Sociedade, Poder Público'),
       ('Emenda Parlamentar');

create index origeminiciativa_descricao_in on planejamento.origeminiciativa(pl13_descricao);

CREATE TABLE planejamento.abrangencia (
  pl18_codigo SERIAL PRIMARY KEY,
  pl18_descricao varchar(100),
  created_at timestamp,
  updated_at timestamp
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.abrangencia');

insert into planejamento.abrangencia (pl18_descricao)
values ('Norte'),
       ('Sul'),
       ('Leste'),
       ('Oeste'),
       ('Município');

create index abrangencia_descricao_in on planejamento.abrangencia(pl18_descricao);


CREATE TABLE planejamento.valores (
  pl10_codigo SERIAL PRIMARY KEY,
  pl10_origem origem_valores,
  pl10_chave int,
  pl10_ano int,
  pl10_valor numeric(15,2),
  pl10_editadomanual boolean DEFAULT false,
  created_at timestamp,
  updated_at timestamp
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.valores');

create table planejamento.planejamento(
    pl2_codigo serial primary key,
    pl2_tipo tipo_planejamento not null,
    pl2_codigo_pai int,
    pl2_ano_inicial int not null,
    pl2_ano_final int not null,
    pl2_ativo boolean default true,
    pl2_status int default 1,
    pl2_titulo varchar(255) not null,
    pl2_base_calculo int not null,
    pl2_base_despesa int,
    pl2_composicao int not null,
    pl2_ementa text,
    pl2_missao text,
    pl2_visao text,
    pl2_valores text,
    pl2_created_at timestamp,
    pl2_updated_at timestamp,
    foreign key (pl2_codigo_pai) references planejamento.planejamento on delete cascade,
    foreign key (pl2_status) references planejamento.status on delete cascade,
    constraint planejamento_check check (
        (pl2_base_calculo = 2::int and pl2_base_despesa is not null)
        or (pl2_base_calculo = 1::int and pl2_base_despesa is null)
    ),
    constraint planejamento_check_composicao check (
        pl2_composicao in (1,2,3)
    )
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.planejamento');

create table planejamento.comissao(
    pl3_codigo serial primary key,
    pl3_cgm int not null,
    pl3_planejamento int not null,
    pl3_created_at timestamp,
    pl3_updated_at timestamp,
    foreign key (pl3_cgm) references protocolo.cgm on delete cascade,
    foreign key (pl3_planejamento) references planejamento.planejamento on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.comissao');

create table planejamento.arearesultado(
    pl4_codigo serial primary key,
    pl4_planejamento int not null,
    pl4_titulo varchar(255) not null,
    pl4_contextualizacao text ,
    pl4_created_at timestamp,
    pl4_updated_at timestamp,
    foreign key (pl4_planejamento) references planejamento.planejamento on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.arearesultado');

create table planejamento.objetivoestrategico(
    pl5_codigo serial primary key,
    pl5_arearesultado int not null,
    pl5_titulo varchar(255) not null,
    pl5_contextualizacao text,
    pl5_fonte text,
    pl5_created_at timestamp,
    pl5_updated_at timestamp,
    foreign key (pl5_arearesultado) references planejamento.arearesultado on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.objetivoestrategico');

create index arearesultado_planejamento_in ON planejamento.arearesultado(pl4_planejamento);
create index comissao_cgm_in ON planejamento.comissao(pl3_cgm);
create index comissao_planejamento_in ON planejamento.comissao(pl3_planejamento);
create index objetivoestrategico_arearesultado_in ON planejamento.objetivoestrategico(pl5_arearesultado);
create index planejamento_codigo_pai_in ON planejamento.planejamento(pl2_codigo_pai);
create index planejamento_status_in ON planejamento.planejamento(pl2_status);

CREATE TABLE planejamento.fatorcorrecaodespesa (
  pl7_codigo SERIAL PRIMARY KEY,
  pl7_planejamento int  not null,
  pl7_orcelemento int  not null,
  pl7_anoorcamento int  not null,
  pl7_exercicio int  not null,
  pl7_percentual numeric(5,2),
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl7_planejamento) references planejamento.planejamento on delete cascade,
  foreign key (pl7_orcelemento, pl7_anoorcamento) references orcamento.orcelemento(o56_codele, o56_anousu)
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.fatorcorrecaodespesa');

CREATE TABLE planejamento.programaestrategico (
  pl9_codigo SERIAL PRIMARY KEY,
  pl9_planejamento int not null,
  pl9_orcprograma int not null,
  pl9_anoorcamento int not null,
  pl9_valorbase numeric(15, 2) default 0,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl9_planejamento) references planejamento.planejamento on delete cascade,
  foreign key (pl9_anoorcamento, pl9_orcprograma) references orcamento.orcprograma(o54_anousu, o54_programa)
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.programaestrategico');

create table planejamento.orgaoprogramaestregico (
    id serial primary key,
    pl27_programaestrategico int not null,
    pl27_orcorgao int not null,
    pl27_anoorcamento int not null,
    foreign key (pl27_programaestrategico) references planejamento.programaestrategico on delete cascade,
    foreign key (pl27_anoorcamento, pl27_orcorgao) references orcamento.orcorgao(o40_anousu, o40_orgao)
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.orgaoprogramaestregico');

CREATE TABLE planejamento.objetivoestrategicoprograma (
  pl6_codigo SERIAL PRIMARY KEY,
  pl6_objetivoestrategico int not null,
  pl6_programaestrategico int not null,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl6_objetivoestrategico) references planejamento.objetivoestrategico on delete cascade,
  foreign key (pl6_programaestrategico) references planejamento.programaestrategico on delete cascade
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.objetivoestrategicoprograma');

CREATE TABLE planejamento.objetivosprogramaestrategico (
  pl11_codigo SERIAL PRIMARY KEY,
  pl11_programaestrategico int not null,
  pl11_ods int,
  pl11_numero int,
  pl11_descricao text,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl11_programaestrategico) references planejamento.programaestrategico on delete cascade,
  foreign key (pl11_ods) references planejamento.ods on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.objetivosprogramaestrategico');

CREATE TABLE planejamento.metasobjetivoprogramaestrategico (
  pl21_codigo SERIAL PRIMARY KEY,
  pl21_objetivosprogramaestrategico int not null,
  pl21_texto text,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl21_objetivosprogramaestrategico) references planejamento.objetivosprogramaestrategico on delete cascade
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.metasobjetivoprogramaestrategico');

CREATE TABLE planejamento.iniciativaprojativ (
  pl12_codigo SERIAL PRIMARY KEY,
  pl12_orcprojativ int not null,
  pl12_anoorcamento int not null,
  pl12_programaestrategico int not null,
  pl12_origeminiciativa int,
  pl12_periodoacao int,
  pl12_valorbase numeric(15, 2) default 0,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl12_programaestrategico) references planejamento.programaestrategico on delete cascade,
  foreign key (pl12_origeminiciativa) references planejamento.origeminiciativa on delete cascade,
  foreign key (pl12_periodoacao) references planejamento.periodoacao on delete cascade,
  foreign key (pl12_anoorcamento, pl12_orcprojativ) references orcamento.orcprojativ(o55_anousu, o55_projativ)
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.iniciativaprojativ');

CREATE TABLE planejamento.abrangenciainiciativaprojativ (
  pl19_codigo SERIAL PRIMARY KEY,
  pl19_iniciativaprojativ int not null,
  pl19_abrangencia int not null,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl19_iniciativaprojativ) references planejamento.iniciativaprojativ on delete cascade,
  foreign key (pl19_abrangencia) references planejamento.abrangencia on delete cascade
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.abrangenciainiciativaprojativ');

CREATE TABLE planejamento.iniciativaobjetivosprogramaestrategico (
  pl16_codigo SERIAL PRIMARY KEY,
  pl16_iniciativaprojativ int not null,
  pl16_objetivosprogramaestrategico int not null,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl16_iniciativaprojativ) references planejamento.iniciativaprojativ on delete cascade,
  foreign key (pl16_objetivosprogramaestrategico) references planejamento.objetivosprogramaestrategico on delete cascade
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.iniciativaobjetivosprogramaestrategico');


CREATE TABLE planejamento.iniciativaprojativppasubtitulolocalizador (
  pl25_codigo SERIAL PRIMARY KEY,
  pl25_iniciativaprojativ int not null,
  pl25_ppasubtitulolocalizadorgasto int not null,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl25_iniciativaprojativ) references planejamento.iniciativaprojativ on delete cascade,
  foreign key (pl25_ppasubtitulolocalizadorgasto) references orcamento.ppasubtitulolocalizadorgasto on delete cascade
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.iniciativaprojativppasubtitulolocalizador');

CREATE TABLE planejamento.indicadoresprogramaestrategico (
  pl22_codigo SERIAL PRIMARY KEY,
  pl22_programaestrategico int not null,
  pl22_orcindica int not null,
  pl22_ano int,
  pl22_indice numeric(15,2),
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl22_programaestrategico) references planejamento.programaestrategico on delete cascade,
  foreign key (pl22_orcindica) references orcamento.orcindica
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.indicadoresprogramaestrategico');

CREATE TABLE planejamento.metasiniciativaprojativ (
  id SERIAL PRIMARY KEY,
  iniciativaprojativ_id int,
  exercicio int,
  meta_financeira numeric(15,2),
  unidade varchar(255),
  meta_fisica numeric(15,2),
  created_at timestamp,
  updated_at timestamp,
  foreign key (iniciativaprojativ_id) references planejamento.iniciativaprojativ on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.metasiniciativaprojativ');

create table planejamento.arearesultadoprograma (
    id serial PRIMARY KEY,
    arearesultado_id int,
    programaestrategico_id int,
    created_at timestamp,
    updated_at timestamp,
    foreign key (arearesultado_id) references planejamento.arearesultado on delete cascade,
    foreign key (programaestrategico_id) references planejamento.programaestrategico on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.arearesultadoprograma');
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

select configuracoes.fc_auditoria_remove_funcao('planejamento.configuracaoplanejamento');
select configuracoes.fc_auditoria_remove_funcao('planejamento.orgaoprogramaestregico');
select configuracoes.fc_auditoria_remove_funcao('planejamento.programaestrategico');
select configuracoes.fc_auditoria_remove_funcao('planejamento.objetivoestrategicoprograma');
select configuracoes.fc_auditoria_remove_funcao('planejamento.objetivosprogramaestrategico');
select configuracoes.fc_auditoria_remove_funcao('planejamento.metasobjetivoprogramaestrategico');
select configuracoes.fc_auditoria_remove_funcao('planejamento.ods');
select configuracoes.fc_auditoria_remove_funcao('planejamento.iniciativaprojativ');
select configuracoes.fc_auditoria_remove_funcao('planejamento.abrangenciainiciativaprojativ');
select configuracoes.fc_auditoria_remove_funcao('planejamento.iniciativaobjetivosprogramaestrategico');
select configuracoes.fc_auditoria_remove_funcao('planejamento.regionalizacao');
select configuracoes.fc_auditoria_remove_funcao('planejamento.iniciativaprojativregionalizacao');
select configuracoes.fc_auditoria_remove_funcao('planejamento.indicadoresprogramaestrategico');
select configuracoes.fc_auditoria_remove_funcao('planejamento.fatorcorrecaodespesa');
select configuracoes.fc_auditoria_remove_funcao('planejamento.objetivoestrategico');
select configuracoes.fc_auditoria_remove_funcao('planejamento.arearesultado');
select configuracoes.fc_auditoria_remove_funcao('planejamento.comissao');
select configuracoes.fc_auditoria_remove_funcao('planejamento.objetivoestrategico');
select configuracoes.fc_auditoria_remove_funcao('planejamento.objetivoestrategicoprograma');
select configuracoes.fc_auditoria_remove_funcao('planejamento.planejamento');
select configuracoes.fc_auditoria_remove_funcao('planejamento.status');

drop schema planejamento cascade;
drop type tipo_planejamento;
drop type origem_valores;
SQL
        );
    }
}
