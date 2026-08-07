
/**
 * TIME B {
 */

/* {73195 - INICIO} */

/* {73195 - FIM} */

/**
 * } TIME B
 */


/**
 * TIME C - INICIO
 */

/* { 95317 - INICIO} */

CREATE SEQUENCE sau_triagemavulsaagravo_s167_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE sau_triagemavulsaagravo(
s167_sequencial int4 NOT NULL default 0,
s167_sau_triagemavulsa int4 NOT NULL default 0,
s167_sau_cid int4 NOT NULL default 0,
s167_datasintoma date NOT NULL default null,
s167_gestante bool default 'false',
CONSTRAINT sau_triagemavulsaagravo_sequ_pk PRIMARY KEY (s167_sequencial));

ALTER TABLE sau_triagemavulsaagravo
ADD CONSTRAINT sau_triagemavulsaagravo_triagemavulsa_fk FOREIGN KEY (s167_sau_triagemavulsa)
REFERENCES sau_triagemavulsa;

ALTER TABLE sau_triagemavulsaagravo
ADD CONSTRAINT sau_triagemavulsaagravo_cid_fk FOREIGN KEY (s167_sau_cid)
REFERENCES sau_cid;

/* { 95317 - FIM} */

/* { 94085B - INICIO} */
alter table aprovconselho add column ed253_alterarnotafinal int4;
alter table aprovconselho add column ed253_avaliacaoconselho varchar (10);
/* { 94085B - FIM} */

/* {96026 - INICIO } */
alter table escola add column ed18_codigoreferencia int4;
/* {96026 - FIM }*/

/**
 * TIME C - FIM
 */


/**
 * TIME FOLHA
 */
alter table afasta drop column r45_login;
alter table afasta add constraint afasta_rhpessoal_fk foreign key (r45_regist) references rhpessoal;

alter table rhpessoal add column rh01_reajusteparidade bool default 'false';

/**
 * Time Financeiro - OBN
 */
alter table caiparametro add column k29_orctiporecfundeb integer null;

ALTER TABLE caiparametro
ADD CONSTRAINT caiparametro_orctiporecfundeb_fk FOREIGN KEY (k29_orctiporecfundeb)
REFERENCES orctiporec;

CREATE SEQUENCE obnnumeracao_o150_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE obnnumeracao(
o150_sequencial   int4 NOT NULL default 0,
o150_instit   int4 NOT NULL default 0,
o150_proximonumero    int4 default 0,
CONSTRAINT obnnumeracao_sequ_pk PRIMARY KEY (o150_sequencial));

ALTER TABLE obnnumeracao
ADD CONSTRAINT obnnumeracao_instit_fk FOREIGN KEY (o150_instit)
REFERENCES db_config;

CREATE  INDEX obnnumeracao_instit_in ON obnnumeracao(o150_instit);

insert into obnnumeracao values (nextval('obnnumeracao_o150_sequencial_seq'), (select codigo from db_config where prefeitura = true limit 1), 1);
create table bkp_obnumeracao as select nextval('obnnumeracao_o150_sequencial_seq'), codigo, 1 from db_config where prefeitura is not true;
insert into obnnumeracao select * from bkp_obnumeracao ;
drop table bkp_obnumeracao;

CREATE SEQUENCE empagemovtipotransmissao_e25_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE empagemovtipotransmissao(
e25_sequencial int4 NOT NULL default 0,
e25_empagemov int4 NOT NULL default 0,
e25_empagetipotransmissao int4 default 0,
CONSTRAINT empagemovtipotransmissao_sequ_pk PRIMARY KEY (e25_sequencial));

ALTER TABLE empagemovtipotransmissao
ADD CONSTRAINT empagemovtipotransmissao_empagemov_fk FOREIGN KEY (e25_empagemov)
REFERENCES empagemov;
ALTER TABLE empagemovtipotransmissao
ADD CONSTRAINT empagemovtipotransmissao_empagetipotransmissao_fk FOREIGN KEY (e25_empagetipotransmissao)
REFERENCES empagetipotransmissao;

CREATE  INDEX empagemovtipotransmissao_empagetipotransmissao_in ON empagemovtipotransmissao(e25_empagetipotransmissao);
CREATE  INDEX empagemovtipotransmissao_empagemov_in ON empagemovtipotransmissao(e25_empagemov);

insert into empagemovtipotransmissao select nextval('empagemovtipotransmissao_e25_sequencial_seq'),
                                            e97_codmov,
                                            1
                                       from empagemovforma
                                      where e97_codforma = 3;


CREATE SEQUENCE empagemovdetalhetransmissao_e74_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE empagemovdetalhetransmissao(
e74_sequencial int4 NOT NULL default 0,
e74_empagemov int4 NOT NULL default 0,
e74_codigodebarra varchar(100)  ,
e74_valornominal numeric  default 0,
e74_datavencimento date  default null,
e74_valorjuros numeric  default 0,
e74_valordesconto numeric default 0,
e74_tipofatura  int4 null,
CONSTRAINT empagemovdetalhetransmissao_sequ_pk PRIMARY KEY (e74_sequencial));

ALTER TABLE empagemovdetalhetransmissao
ADD CONSTRAINT empagemovdetalhetransmissao_empagemov_fk FOREIGN KEY (e74_empagemov)
REFERENCES empagemov;
CREATE  INDEX empagemovdetalhetransmissao_empagemov_in ON empagemovdetalhetransmissao(e74_empagemov);

CREATE SEQUENCE empagegeraobn_e138_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE empagegeraobn(
e138_sequencial    int4 NOT NULL default 0,
e138_numeracaoobn  int4 NOT NULL default 0,
e138_empagegera    int4 default 0,
CONSTRAINT empagegeraobn_sequ_pk PRIMARY KEY (e138_sequencial));

ALTER TABLE empagegeraobn
ADD CONSTRAINT empagegeraobn_empagegera_fk FOREIGN KEY (e138_empagegera)
REFERENCES empagegera;

CREATE  INDEX empagegeraobn_empagegera_in ON empagegeraobn(e138_empagegera);


CREATE SEQUENCE slipfinalidadepagamentofundeb_e153_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE slipfinalidadepagamentofundeb(
e153_sequencial    int4 NOT NULL default 0,
e153_slip          int4 NOT NULL default 0,
e153_finalidadepagamentofundeb    int4 default 0,
CONSTRAINT slipfinalidadepagamentofundeb_sequ_pk PRIMARY KEY (e153_sequencial));

ALTER TABLE slipfinalidadepagamentofundeb
ADD CONSTRAINT slipfinalidadepagamentofundeb_finalidadepagamentofundeb_fk FOREIGN KEY (e153_finalidadepagamentofundeb)
REFERENCES finalidadepagamentofundeb;

ALTER TABLE slipfinalidadepagamentofundeb
ADD CONSTRAINT slipfinalidadepagamentofundeb_slip_fk FOREIGN KEY (e153_slip)
REFERENCES slip;

CREATE UNIQUE INDEX slipfinalidadepagamentofundeb_slip_in ON slipfinalidadepagamentofundeb(e153_slip);
CREATE INDEX slipfinalidadepagamentofundeb_finpagamentofundeb_in ON slipfinalidadepagamentofundeb(e153_finalidadepagamentofundeb);


CREATE SEQUENCE empempenhofinalidadepagamentofundeb_e152_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE empempenhofinalidadepagamentofundeb(
e152_sequencial    int4 NOT NULL default 0,
e152_numemp        int4 NOT NULL default 0,
e152_finalidadepagamentofundeb   int4 default 0,
CONSTRAINT empempenhofinalidadepagamentofundeb_sequ_pk PRIMARY KEY (e152_sequencial));


ALTER TABLE empempenhofinalidadepagamentofundeb
ADD CONSTRAINT empempenhofinalidadepagamentofundeb_finalidadepagamentofundeb_fk FOREIGN KEY (e152_finalidadepagamentofundeb)
REFERENCES finalidadepagamentofundeb;

ALTER TABLE empempenhofinalidadepagamentofundeb
ADD CONSTRAINT empempenhofinalidadepagamentofundeb_numemp_fk FOREIGN KEY (e152_numemp)
REFERENCES empempenho;

CREATE  INDEX empempenhofinalidadepagamentofundeb_finpagamentofundeb_in ON empempenhofinalidadepagamentofundeb(e152_finalidadepagamentofundeb);
CREATE UNIQUE INDEX empempenhofinalidadepagamentofundeb_numemp_in ON empempenhofinalidadepagamentofundeb(e152_numemp);

/**
 * Time Financeiro - FIM
 */