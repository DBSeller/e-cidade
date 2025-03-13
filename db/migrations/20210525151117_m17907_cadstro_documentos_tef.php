<?php

use Classes\PostgresMigration;

class M17907CadstroDocumentosTef extends PostgresMigration
{

    public function up()
    {

        $sSql = <<<SQL


insert into db_sysarquivo values (1010801, 'conlancamtef', 'lancamentos do TEF', 'c137', '2021-05-20', 'conlancamtef', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010801);
insert into db_syscampo values(1013258,'c137_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1013259,'c137_codlan','int4','Codigo do lancamento codlan','0', 'Codigo do lancamento',10,'f','f','f',1,'text','Codigo do lancamento');
insert into db_syscampo values(1013260,'c137_operacoesrealizadastef','int4','Operação TEF','0', 'Operação TEF',10,'f','f','f',1,'text','Operação TEF');
insert into db_sysarqcamp values(1010801,1013258,1,0);
insert into db_sysarqcamp values(1010801,1013259,2,0);
insert into db_sysarqcamp values(1010801,1013260,3,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010801,1013258,1,1013258);
insert into db_sysindices values(1008687,'conlancamtef_c137_sequencial_in',1010801,'0');
insert into db_syscadind values(1008687,1013258,1);
insert into db_sysindices values(1008688,'conlancamtef_c137_codlan_in',1010801,'0');
insert into db_syscadind values(1008688,1013259,1);
insert into db_sysindices values(1008689,'conlancamtef_c137_operacoesrealizadastef_in',1010801,'0');
insert into db_syscadind values(1008689,1013260,1);
insert into db_sysforkey values(1010801,1013259,1,760,0);
insert into db_sysforkey values(1010801,1013260,1,1010796,0);
insert into db_syssequencia values(1001006, 'conlancamtef_c137_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001006 where codarq = 1010801 and codcam = 1013258;


CREATE SEQUENCE conlancamtef_c137_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE conlancamtef(
c137_sequencial		int4 default 0,
c137_codlan int4 default 0,
c137_operacoesrealizadastef int4 default 0,
CONSTRAINT conlancamtef_sequ_pk PRIMARY KEY (c137_sequencial));


ALTER TABLE conlancamtef
ADD CONSTRAINT conlancamtef_codlan_fk FOREIGN KEY (c137_codlan)
REFERENCES conlancam;

ALTER TABLE conlancamtef
ADD CONSTRAINT conlancamtef_operacoesrealizadastef_fk FOREIGN KEY (c137_operacoesrealizadastef)
REFERENCES operacoesrealizadastef;


CREATE  INDEX conlancamtef_c137_sequencial_in ON conlancamtef(c137_sequencial);
CREATE  INDEX conlancamtef_c137_codlan_in ON conlancamtef(c137_codlan);
CREATE  INDEX conlancamtef_c137_operacoesrealizadastef_in ON conlancamtef(c137_operacoesrealizadastef);




--NOVOS DOCUMENTOS
insert into conhistdoctipo (c57_sequencial,c57_descricao) values (112,'Controle TEF');
insert into conhistdoctipo (c57_sequencial,c57_descricao) values (113,'Controle TEF Estorno');

insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (165,'ARRECADAÇÃO DE RECEITA TEF',100);
insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (166,'ESTORNO DE ARRECADAÇÃO DE RECEITA TEF',101);
insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (167,'RECEBIMENTO TEF',112);
insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (168,'ESTORNO DE RECEBIMENTO TEF',113);

--NOVOS HISTÓRICOS
insert into conhist (c50_codhist,c50_compl,c50_descr) values (9800,'f','ARRECADAÇÃO DE RECEITA TEF');
insert into conhist (c50_codhist,c50_compl,c50_descr) values (9801,'f','ESTORNO DE ARRECADAÇÃO DE RECEITA TEF');



insert into conplanosistema select 32, 'TEF', 2;


insert into conplanoinfocomplementar
 (c121_sequencial, c121_sigla, c121_descricao, c121_sql, c121_ajuda, c121_nomepropriedade,c121_valorpadrao) values
 (54, 'NSU','Identificador NSU', '','Identificador NSU da transação', 'cod_nsu','NI'),
 (55, 'NUMPR', 'Código de Arrecadação', '', 'Numpre da Arrecadação', 'cod_numnov', 'NI'),
 (56, 'OPTEF', 'Tipo da Operação TEF', '', 'Tipo da Operação TEF', 'cod_tipo_operacao_tef', 'NI'),
 (57, 'PARC', 'Indicador de Parcela', '', 'Indicador de Parcela', 'cod_indicador_parcela', 'NI');


update conplanoinfocomplementar set c121_sql =
'select k198_nsu
 from conlancamtef
 inner join operacoesrealizadastef on c137_operacoesrealizadastef = k198_sequencial
 where c137_codlan = codigo_lancamento'
 where c121_sequencial = 54;


update conplanoinfocomplementar set c121_sql =
'select k198_numnov
  from conlancamtef
 inner join operacoesrealizadastef on c137_operacoesrealizadastef = k198_sequencial
 where c137_codlan = codigo_lancamento'
 where c121_sequencial = 55;


update conplanoinfocomplementar set c121_sql =
'select k195_descricao
  from conlancamtef
 inner join operacoesrealizadastef on c137_operacoesrealizadastef = k198_sequencial
 inner join operacoestef on k198_operacaotef = k195_sequencial
 where c137_codlan = codigo_lancamento'
 where c121_sequencial = 56;

update conplanoinfocomplementar set c121_sql =
'select k198_parcela
 from conlancamtef
 inner join operacoesrealizadastef on c137_operacoesrealizadastef = k198_sequencial
 where c137_codlan = codigo_lancamento'
 where c121_sequencial = 57;


insert into conplanosistemaatributos
(c129_sequencial, c129_conplanosistema, c129_conplanoinfocomplementar, c129_ordem) values
(122, 32, 54, 1),
(123, 32, 55, 2),
(124, 32, 56, 3),
(125, 32, 57, 4);





--REGRA DOS DOCUMENTOS
insert into conhistdocregra (c92_sequencial,c92_conhistdoc,c92_descricao,c92_regra,c92_anousu) values (nextval('conhistdocregra_c92_sequencial_seq'),165,'RECEITAS TEF','select 1 from orcreceita inner join orcfontes on o70_codfon = o57_codfon and o70_anousu = o57_anousu inner join conplanoorcamento on o57_codfon = conplanoorcamento.c60_codcon and o57_anousu = conplanoorcamento.c60_anousu inner join conplanoorcamentogrupo on c21_codcon = conplanoorcamento.c60_codcon and c21_anousu = conplanoorcamento.c60_anousu where o70_codrec = [codigoreceita] and o70_anousu = [anousureceita] and c21_congrupo = 16 and conplanoorcamentogrupo.c21_instit = [instituicaogrupoconta]',2021);
insert into conhistdocregra (c92_sequencial,c92_conhistdoc,c92_descricao,c92_regra,c92_anousu) values (nextval('conhistdocregra_c92_sequencial_seq'),166,'RECEITAS TEF','select 1 from orcreceita inner join orcfontes on o70_codfon = o57_codfon and o70_anousu = o57_anousu inner join conplanoorcamento on o57_codfon = conplanoorcamento.c60_codcon and o57_anousu = conplanoorcamento.c60_anousu inner join conplanoorcamentogrupo on c21_codcon = conplanoorcamento.c60_codcon and c21_anousu = conplanoorcamento.c60_anousu where o70_codrec = [codigoreceita] and o70_anousu = [anousureceita] and c21_congrupo = 16',2021);
insert into conhistdocregra (c92_sequencial,c92_conhistdoc,c92_descricao,c92_regra,c92_anousu) values (nextval('conhistdocregra_c92_sequencial_seq'),167,'RECEITAS TEF','select 1 from conhistdoc where c53_coddoc = 167',2021);
insert into conhistdocregra (c92_sequencial,c92_conhistdoc,c92_descricao,c92_regra,c92_anousu) values (nextval('conhistdocregra_c92_sequencial_seq'),168,'RECEITAS TEF','select 1 from conhistdoc where c53_coddoc = 168',2021);


--VINCULOS DOS DOCUMENTOS
insert into vinculoeventoscontabeis (c115_sequencial,c115_conhistdocinclusao,c115_conhistdocestorno) values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'),165,166);
insert into vinculoeventoscontabeis (c115_sequencial,c115_conhistdocinclusao,c115_conhistdocestorno) values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'),167,168);



SQL;

      $this->execute($sSql);

    }

    public function down()
    {
        $sSql = <<<SQL


        delete from conhistdoc where c53_coddoc in (165, 166, 167, 168);
        delete from conhistdoctipo where c57_sequencial in (112,113);
        delete from conhist where c50_codhist in (9800, 9801);

        delete from conplanoinfocomplementar where c121_sequencial in (54,55,56,57);
        delete from conplanosistemaatributos where c129_conplanoinfocomplementar = 32;
        delete from conplanosistema where c122_sequencial = 32;
        delete from db_sysarqcamp where codarq = 1010801;
        delete from db_sysprikey where codarq = 1010801;
        delete from db_sysforkey where codarq = 1010801 and referen = 0;
        delete from db_sysforkey where codarq = 1010801 and referen = 0;
        DROP TABLE IF EXISTS conlancamtef CASCADE;
        DROP SEQUENCE IF EXISTS conlancamtef_c137_sequencial_seq;


SQL;

        $this->execute($sSql);
    }
}
