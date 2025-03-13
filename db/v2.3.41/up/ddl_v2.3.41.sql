---------------------------------------------------------------------------------------------
-------------------------------- INCIO FINANCEIRO -------------------------------------------
---------------------------------------------------------------------------------------------

-- Manutenção de veículos

alter table veicmanut alter column ve62_vlrmobra type numeric,
                      alter column ve62_vlrpecas type numeric,
                      alter column ve62_medida   type numeric,
                      add column   ve62_situacao integer not null default 2,
                      add column   ve62_numero   integer not null default 0,
                      add column   ve62_anousu   integer not null default 0,
                      add column ve62_veicmotoristas integer default null references veicmotoristas(ve05_codigo);

alter table veicmanutitem alter column ve63_quant  type numeric,
                          alter column ve63_vlruni type numeric,
                          add column ve63_valortotalcomdesconto numeric not null default 0,
                          add column ve63_unidade               integer references matunid(m61_codmatunid),
                          add column ve63_tipoitem              integer not null default 1,
                          add column ve63_proximatroca numeric default null,
                          add column ve63_datanota date  default null,
                          add column ve63_numeronota varchar(10) default null;


alter table veicmanut alter column ve62_numero drop default;
alter table veicmanut alter column ve62_anousu drop default;

update veicmanut set ve62_numero = ve62_codigo,
                     ve62_anousu = extract(year from ve62_data);

update veicmanutitem set ve63_valortotalcomdesconto = ve63_vlruni * ve63_quant;

-- Procura 'Unidade', se não encontrar deixa nulo
update veicmanutitem set ve63_unidade = (select m61_codmatunid from matunid where m61_descr = 'UNIDADE' limit 1);

-- Autorização para circulação de veículos

create sequence autorizacaocirculacaoveiculo_ve13_sequencial_seq
increment 1
minvalue  1
maxvalue  9223372036854775807
start     1
cache     1;

create table autorizacaocirculacaoveiculo(
ve13_sequencial   int4 not null default nextval('autorizacaocirculacaoveiculo_ve13_sequencial_seq'),
ve13_instituicao  int4 not null,
ve13_veiculo      int4 not null,
ve13_motorista    int4 not null,
ve13_datainicial  date not null,
ve13_datafinal    date not null,
ve13_dataemissao  date not null,
ve13_observacao   text,
ve13_departamento int4 not null,
constraint autorizacaocirculacaoveiculo_sequ_pk primary key (ve13_sequencial));

alter table    autorizacaocirculacaoveiculo
add constraint autorizacaocirculacaoveiculo_instituicao_fk foreign key (ve13_instituicao)
references     db_config;

alter table    autorizacaocirculacaoveiculo
add constraint autorizacaocirculacaoveiculo_motorista_fk   foreign key (ve13_motorista)
references     veicmotoristas;

alter table    autorizacaocirculacaoveiculo
add constraint autorizacaocirculacaoveiculo_veiculo_fk     foreign key (ve13_veiculo)
references     veiculos;

alter table    autorizacaocirculacaoveiculo
add constraint autorizacaocirculacaoveiculo_departamento_fk foreign key (ve13_departamento)
references     db_depart;

create index autorizacaocirculacaoveiculo_veiculo_in on autorizacaocirculacaoveiculo(ve13_veiculo);
create index autorizacaocirculacaoveiculo_motorista_in on autorizacaocirculacaoveiculo(ve13_motorista);
create index autorizacaocirculacaoveiculo_instituicao_in on autorizacaocirculacaoveiculo(ve13_instituicao);
create index autorizacaocirculacaoveiculo_departamento_in on autorizacaocirculacaoveiculo(ve13_departamento);

alter table orcprogramahorizontetemp alter o17_valor type numeric;
update db_syscampo set tamanho = 20 where codcam = 13657;

-- Levantamento Patrimonial

create sequence levantamentopatrimonial_p13_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;

create sequence levantamentopatrimonialbens_p14_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;

create table levantamentopatrimonial(
p13_sequencial   int4 not null default nextval('levantamentopatrimonial_p13_sequencial_seq'),
p13_departamento int4 not null,
p13_data         date not null,
constraint levantamentopatrimonial_sequ_pk primary key (p13_sequencial));

create table levantamentopatrimonialbens(
p14_sequencial              int4        not null default nextval('levantamentopatrimonialbens_p14_sequencial_seq'),
p14_levantamentopatrimonial int4        not null,
p14_placa                   varchar(50) not null,
constraint levantamentopatrimonialbens_sequ_pk primary key (p14_sequencial));

alter table levantamentopatrimonial
add constraint levantamentopatrimonial_departamento_fk foreign key (p13_departamento)
references db_depart;

alter table levantamentopatrimonialbens
add constraint levantamentopatrimonialbens_levantamentopatrimonial_fk foreign key (p14_levantamentopatrimonial)
references levantamentopatrimonial;

create index levantamentopatrimonial_departamento_in on levantamentopatrimonial(p13_departamento);
create index levantamentopatrimonialbens_levantamentopatrimonial_in on levantamentopatrimonialbens(p14_levantamentopatrimonial);

-- Liquidação com Desconto
create sequence pagordemdescontoempanulado_e06_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;

create table pagordemdescontoempanulado(
e06_sequencial		   int4 not null default 0,
e06_empanulado		   int4 not null default 0,
e06_pagordemdesconto int4  not null default 0,
constraint pagordemdescontoempanulado_empa_pk primary key(e06_empanulado));

alter table pagordemdescontoempanulado
add constraint pagordemdescontoempanulado_empanulado_fk foreign key (e06_empanulado)
references empanulado;

alter table pagordemdescontoempanulado
add constraint pagordemdescontoempanulado_pagordemdesconto_fk foreign key (e06_pagordemdesconto)
references pagordemdesconto;

create index pagordemdescontoempanulado_pagordemdesconto_in on pagordemdescontoempanulado(e06_pagordemdesconto);
create index pagordemdescontoempanulado_empanulado_in on pagordemdescontoempanulado(e06_empanulado);
create index pagordemdescontoempanulado_sequencial_in on pagordemdescontoempanulado(e06_sequencial);
---------------------------------------------------------------------------------------------
-------------------------------- FIM FINANCEIRO---------------------------------------------
---------------------------------------------------------------------------------------------

---------------------------------------------------------------------------------------------
------------------------------ INÍCIO EDUCAÇÃO/SAÚDE ----------------------------------------
---------------------------------------------------------------------------------------------
CREATE SEQUENCE pontoparadaescolaproc_tre13_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE pontoparadaescolaproc(
tre13_sequencial   int4 NOT NULL default 0,
tre13_pontoparada  int4 NOT NULL default 0,
tre13_escolaproc   int4 default 0,
CONSTRAINT pontoparadaescolaproc_sequ_pk PRIMARY KEY (tre13_sequencial));

ALTER TABLE pontoparadaescolaproc
ADD CONSTRAINT pontoparadaescolaproc_pontoparada_fk FOREIGN KEY (tre13_pontoparada)
REFERENCES pontoparada;

ALTER TABLE pontoparadaescolaproc
ADD CONSTRAINT pontoparadaescolaproc_escolaproc_fk FOREIGN KEY (tre13_escolaproc)
REFERENCES escolaproc;

CREATE UNIQUE INDEX pontoparadaescolaproc_pontoparada_escolaproc_in ON pontoparadaescolaproc(tre13_pontoparada,tre13_escolaproc);

CREATE SEQUENCE dadoscompetenciadispensacao_fa61_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE dadoscompetenciaentrada_fa62_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE dadoscompetenciasaida_fa63_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE integracaohorusenvio_fa64_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE integracaohorusenviodadoscompetencia_fa65_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE situacaohorus_fa60_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

alter table integracaohorus add  column fa59_situacaohorus integer not null;
alter table integracaohorus drop column fa59_data;
alter table integracaohorus drop column fa59_protocolo;
alter table integracaohorus add  column fa59_db_depart integer not null;

CREATE TABLE dadoscompetenciadispensacao(
fa61_sequencial   int4 NOT NULL default 0,
fa61_far_retiradaitens    int4 NOT NULL default 0,
fa61_integracaohorus    int4 NOT NULL default 0,
fa61_enviar   bool NOT NULL default 'f',
fa61_validadohorus    bool NOT NULL default 'f',
fa61_unidade    int4 NOT NULL default 0,
fa61_cnes   varchar(10) NOT NULL ,
fa61_catmat   varchar(20) NOT NULL ,
fa61_tipo   char(1) NOT NULL default 'B',
fa61_valor    numeric NOT NULL default 0,
fa61_validade   date default null,
fa61_lote   varchar(50),
fa61_quantidade   int4 NOT NULL default 0,
fa61_dispensacao    date NOT NULL default null,
fa61_cns    varchar(15),
CONSTRAINT dadoscompetenciadispensacao_sequ_pk PRIMARY KEY (fa61_sequencial));

CREATE TABLE dadoscompetenciaentrada(
fa62_sequencial   int4 NOT NULL default 0,
fa62_integracaohorus    int4 NOT NULL default 0,
fa62_matestoqueinimei   int4 NOT NULL default 0,
fa62_unidade    int4 NOT NULL default 0,
fa62_enviar   bool NOT NULL default 'f',
fa62_validadohorus    bool NOT NULL default 'f',
fa62_cnes   varchar(10) NOT NULL ,
fa62_catmat   varchar(20) NOT NULL ,
fa62_tipo   char(1) NOT NULL default 'B',
fa62_valor    numeric NOT NULL default 0,
fa62_validade   date default null,
fa62_lote   varchar(50),
fa62_quantidade   int4 NOT NULL default 0,
fa62_recebimento    date NOT NULL default null,
fa62_movimentacao   varchar(15) ,
CONSTRAINT dadoscompetenciaentrada_sequ_pk PRIMARY KEY (fa62_sequencial));

CREATE TABLE dadoscompetenciasaida(
fa63_sequencial   int4 NOT NULL default 0,
fa63_integracaohorus    int4 NOT NULL default 0,
fa63_matestoqueinimei   int4 NOT NULL default 0,
fa63_unidade    int4 NOT NULL default 0,
fa63_enviar   bool NOT NULL default 'f',
fa63_validadohorus    bool NOT NULL default 'f',
fa63_catmat   varchar(20) NOT NULL ,
fa63_cnes   varchar(10) NOT NULL ,
fa63_tipo   char(1) NOT NULL default 'B',
fa63_valor    numeric NOT NULL default 0,
fa63_lote   varchar(50)  ,
fa63_validade   date default null,
fa63_quantidade   int4 NOT NULL default 0,
fa63_data   date NOT NULL default null,
fa63_movimentacao   varchar(15) ,
CONSTRAINT dadoscompetenciasaida_sequ_pk PRIMARY KEY (fa63_sequencial));

CREATE TABLE integracaohorusenvio(
fa64_sequencial   int4 NOT NULL default 0,
fa64_protocolo    text NOT NULL ,
fa64_hora         time NOT NULL ,
fa64_data         date NOT NULL default null,
fa64_integracaohorus    int4 default 0,
CONSTRAINT integracaohorusenvio_sequ_pk PRIMARY KEY (fa64_sequencial));

CREATE TABLE integracaohorusenviodadoscompetencia(
fa65_sequencial   int4 NOT NULL default 0,
fa65_integracaohorusenvio   int4 NOT NULL default 0,
fa65_dadoscompetencia   int4 default 0,
CONSTRAINT integracaohorusenviodadoscompetencia_sequ_pk PRIMARY KEY (fa65_sequencial));

CREATE TABLE situacaohorus(
fa60_sequencial   int4 NOT NULL default 0,
fa60_descricao    varchar(30) ,
CONSTRAINT situacaohorus_sequ_pk PRIMARY KEY (fa60_sequencial));

insert into situacaohorus
     values (nextval('situacaohorus_fa60_sequencial_seq'), 'SEM DADOS'),
            (nextval('situacaohorus_fa60_sequencial_seq'), 'AGUARDANDO ENVIO'),
            (nextval('situacaohorus_fa60_sequencial_seq'), 'PARCIALMENTE ENVIADO'),
            (nextval('situacaohorus_fa60_sequencial_seq'), 'AGUARDANDO HORUS'),
            (nextval('situacaohorus_fa60_sequencial_seq'), 'INCONSISTENTE'),
            (nextval('situacaohorus_fa60_sequencial_seq'), 'CONCLUÍDO');

ALTER TABLE dadoscompetenciadispensacao ADD CONSTRAINT dadoscompetenciadispensacao_unidade_fk FOREIGN KEY (fa61_unidade) REFERENCES unidades;
ALTER TABLE dadoscompetenciadispensacao ADD CONSTRAINT dadoscompetenciadispensacao_retiradaitens_fk FOREIGN KEY (fa61_far_retiradaitens) REFERENCES far_retiradaitens;
ALTER TABLE dadoscompetenciadispensacao ADD CONSTRAINT dadoscompetenciadispensacao_integracaohorus_fk FOREIGN KEY (fa61_integracaohorus) REFERENCES integracaohorus;
ALTER TABLE dadoscompetenciaentrada ADD CONSTRAINT dadoscompetenciaentrada_unidade_fk FOREIGN KEY (fa62_unidade) REFERENCES unidades;
ALTER TABLE dadoscompetenciaentrada ADD CONSTRAINT dadoscompetenciaentrada_integracaohorus_fk FOREIGN KEY (fa62_integracaohorus) REFERENCES integracaohorus;
ALTER TABLE dadoscompetenciaentrada ADD CONSTRAINT dadoscompetenciaentrada_matestoqueinimei_fk FOREIGN KEY (fa62_matestoqueinimei) REFERENCES matestoqueinimei;
ALTER TABLE dadoscompetenciasaida ADD CONSTRAINT dadoscompetenciasaida_unidade_fk FOREIGN KEY (fa63_unidade) REFERENCES unidades;
ALTER TABLE dadoscompetenciasaida ADD CONSTRAINT dadoscompetenciasaida_integracaohorus_fk FOREIGN KEY (fa63_integracaohorus) REFERENCES integracaohorus;
ALTER TABLE dadoscompetenciasaida ADD CONSTRAINT dadoscompetenciasaida_matestoqueinimei_fk FOREIGN KEY (fa63_matestoqueinimei) REFERENCES matestoqueinimei;
ALTER TABLE integracaohorus ADD CONSTRAINT integracaohorus_situacaohorus_fk FOREIGN KEY (fa59_situacaohorus) REFERENCES situacaohorus;
ALTER TABLE integracaohorusenvio ADD CONSTRAINT integracaohorusenvio_integracaohorus_fk FOREIGN KEY (fa64_integracaohorus) REFERENCES integracaohorus;
ALTER TABLE integracaohorusenviodadoscompetencia ADD CONSTRAINT integracaohorusenviodadoscompetencia_integracaohorusenvio_fk FOREIGN KEY (fa65_integracaohorusenvio) REFERENCES integracaohorusenvio;
ALTER TABLE integracaohorus ADD CONSTRAINT integracaohorus_depart_fk FOREIGN KEY (fa59_db_depart) REFERENCES db_depart;

CREATE  INDEX dadoscompetenciadispensacao_unidade_in ON dadoscompetenciadispensacao(fa61_unidade);
CREATE  INDEX dadoscompetenciadispensacao_integracaohorus_in ON dadoscompetenciadispensacao(fa61_integracaohorus);
CREATE  INDEX dadoscompetenciadispensacao_far_retiradaitens_in ON dadoscompetenciadispensacao(fa61_far_retiradaitens);
CREATE  INDEX dadoscompetenciaentrada_unidade_in ON dadoscompetenciaentrada(fa62_unidade);
CREATE  INDEX dadoscompetenciaentrada_matestoqueinimei_in ON dadoscompetenciaentrada(fa62_matestoqueinimei);
CREATE  INDEX dadoscompetenciaentrada_integracaohorus_in ON dadoscompetenciaentrada(fa62_integracaohorus);
CREATE  INDEX dadoscompetenciasaida_unidade_in ON dadoscompetenciasaida(fa63_unidade);
CREATE  INDEX dadoscompetenciasaida_matestoqueinimei_in ON dadoscompetenciasaida(fa63_matestoqueinimei);
CREATE  INDEX dadoscompetenciasaida_integracaohorus_in ON dadoscompetenciasaida(fa63_integracaohorus);
CREATE  INDEX integracaohorusenvio_integracaohorus_in ON integracaohorusenvio(fa64_integracaohorus);
CREATE  INDEX integracaohorusenviodadoscompetencia_dadoscompetencia_in ON integracaohorusenviodadoscompetencia(fa65_dadoscompetencia);
CREATE  INDEX integracaohorusenviodadoscompetencia_integracaohorusenvio_in ON integracaohorusenviodadoscompetencia(fa65_integracaohorusenvio);

---------------------------------------------------------------------------------------------
-------------------------------- FIM EDUCAÇÃO/SAÚDE -----------------------------------------
---------------------------------------------------------------------------------------------

---------------------------------------------------------------------------------------------
-------------------------------- INCIO TRIBUTARIO -------------------------------------------
---------------------------------------------------------------------------------------------

update db_itensmenu set descricao = 'Legista' where id_item = 6853;

---------------------------------------------------------------------------------------------
-------------------------------- FIM TRIBUTARIO----------------------------------------------
---------------------------------------------------------------------------------------------
