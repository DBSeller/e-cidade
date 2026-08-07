<?php

use Classes\PostgresMigration;

class M12227Loa2019EstruturaDaDespesa extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP
insert into db_syscampo values(1010123,'o58_esferaorcamentaria','int4','Esfera Orçamentária','0', 'Esfera Orçamentária',10,'f','f','f',1,'text','Esfera Orçamentária');
insert into db_sysarqcamp values(758,1010123,16,0);

delete from db_sysindices where codind in (1823, 1008365);
insert into db_sysindices values(1008365,'orcdotacao_oufspae_in',758,'1');
delete from db_syscadind  where codind in (1823, 1008365);
insert into db_syscadind values(1008365,5285,1);
insert into db_syscadind values(1008365,5287,2);
insert into db_syscadind values(1008365,5288,3);
insert into db_syscadind values(1008365,5289,4);
insert into db_syscadind values(1008365,5290,5);
insert into db_syscadind values(1008365,5291,6);
insert into db_syscadind values(1008365,5292,7);
insert into db_syscadind values(1008365,5293,8);
insert into db_syscadind values(1008365,5294,9);
insert into db_syscadind values(1008365,14520,10);
insert into db_syscadind values(1008365,1010123,11);

alter table orcdotacao add column o58_esferaorcamentaria integer not null default 0;
update orcdotacao set o58_esferaorcamentaria = 0;

insert into db_sysarquivo values (1010345, 'orcdotacaoplanoorcamentario', 'orcdotacaoplanoorcamentario', 'o155', '2018-12-05', 'orcdotacaoplanoorcamentario', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010345);
insert into db_syscampo values(1010126,'o155_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1010127,'o155_coddot','int4','Código da Dotação','0', 'Código da Dotação',10,'f','f','f',1,'text','Código da Dotação');
insert into db_syscampo values(1010128,'o155_anousu','int4','Ano da Dotação','0', 'Ano da Dotação',4,'f','f','f',1,'text','Ano da Dotação');
insert into db_syscampo values(1010129,'o155_titulo','varchar(200)','Título','', 'Título',200,'f','t','f',0,'text','Título');
insert into db_syscampo values(1010130,'o155_valor','float4','Valor','0', 'Valor',10,'f','f','f',4,'text','Valor');
delete from db_sysarqcamp where codarq = 1010345;
insert into db_sysarqcamp values(1010345,1010126,1,0);
insert into db_sysarqcamp values(1010345,1010127,2,0);
insert into db_sysarqcamp values(1010345,1010128,3,0);
insert into db_sysarqcamp values(1010345,1010129,4,0);
insert into db_sysarqcamp values(1010345,1010130,5,0);
delete from db_sysforkey where codarq = 1010345 and referen = 0;
insert into db_sysforkey values(1010345,1010128,1,758,0);
insert into db_sysforkey values(1010345,1010127,2,758,0);
delete from db_sysprikey where codarq = 1010345;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010345,1010126,1,1010129);
insert into db_sysindices values(1008366,'orcdotacaoplanoorcamentario_coddot_in',1010345,'0');
insert into db_syscadind values(1008366,1010127,1);
insert into db_sysindices values(1008367,'orcdotacaoplanoorcamentario_anousu_in',1010345,'0');
insert into db_syscadind values(1008367,1010128,1);
insert into db_syssequencia values(1000787, 'orcdotacaoplanoorcamentario_o155_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000787 where codarq = 1010345 and codcam = 1010126;

insert into db_sysarquivo values (1010346, 'planoorcamentariolinhapacto', 'planoorcamentariolinhapacto', 'o156', '2018-12-05', 'planoorcamentariolinhapacto', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010346);
insert into db_syscampo values(1010131,'o156_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1010132,'o156_linhaspacto','int4','Linhas de Pacto','0', 'Linhas de Pacto',10,'f','f','f',1,'text','Linhas de Pacto');
insert into db_syscampo values(1010133,'o156_orcdotacaoplanoorcamentario','int4','Plano Orcamentário','0', 'Plano Orcamentário',10,'f','f','f',1,'text','Plano Orcamentário');
insert into db_syscampo values(1010134,'o156_valor','float4','Valor','0', 'Valor',10,'f','f','f',4,'text','Valor');
delete from db_sysarqcamp where codarq = 1010346;
insert into db_sysarqcamp values(1010346,1010131,1,0);
insert into db_sysarqcamp values(1010346,1010132,2,0);
insert into db_sysarqcamp values(1010346,1010133,3,0);
insert into db_sysarqcamp values(1010346,1010134,4,0);
delete from db_sysforkey where codarq = 1010346 and referen = 0;
insert into db_sysforkey values(1010346,1010133,1,1010345,0);
delete from db_sysforkey where codarq = 1010346 and referen = 0;
insert into db_sysforkey values(1010346,1010132,1,1010299,0);
insert into db_sysindices values(1008368,'planoorcamentariolinhapacto_linhaspacto_in',1010346,'0');
insert into db_syscadind values(1008368,1010132,1);
insert into db_sysindices values(1008369,'planoorcamentariolinhapacto_orcdotacaoplanoorcamentario_in',1010346,'0');
insert into db_syscadind values(1008369,1010133,1);
delete from db_sysprikey where codarq = 1010346;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010346,1010131,1,1010133);
insert into db_syssequencia values(1000788, 'planoorcamentariolinhapacto_o156_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000788 where codarq = 1010346 and codcam = 1010131;

create table orcdotacaoplanoorcamentario
(
    o155_sequencial serial primary key,
    o155_coddot integer not null,
    o155_anousu integer not null,
    o155_titulo varchar(200) not null,
    o155_valor numeric not null
);
       
create index orcdotacaoplanoorcamentario_coddot_in on orcdotacaoplanoorcamentario(o155_coddot);
create index orcdotacaoplanoorcamentario_anousu_in on orcdotacaoplanoorcamentario(o155_anousu);
alter table orcdotacaoplanoorcamentario 
      add constraint orcdotacaoplanoorcamentario_orcdotacao_fk 
      foreign key (o155_anousu, o155_coddot)
      references orcdotacao;  

create table planoorcamentariolinhapacto
(
    o156_sequencial serial primary key,
    o156_linhaspacto integer not null,
    o156_orcdotacaoplanoorcamentario integer not null,
    o156_valor numeric default 0 not null
);
create index planoorcamentariolinhapacto_linhaspacto_in on planoorcamentariolinhapacto(o156_linhaspacto);
create index planoorcamentariolinhapacto_orcdotacaoplanoorcamentario_in on planoorcamentariolinhapacto(o156_orcdotacaoplanoorcamentario);
alter table planoorcamentariolinhapacto
      add constraint planoorcamentariolinhapacto_linhapacto_fk
      foreign key (o156_linhaspacto)
      references linhaspacto;

alter table planoorcamentariolinhapacto
      add constraint planoorcamentariolinhapacto_orcdotacaoplanoorcamentario_fk
      foreign key (o156_orcdotacaoplanoorcamentario)
      references orcdotacaoplanoorcamentario;

SQL_UP
);
        $sSqlTrigger = <<<SQL
create or replace function fc_orcdotaocao_inc_alt() returns trigger as
$$
begin

  perform
    from orcdotacao
   where o58_orgao     =  new.o58_orgao
     and o58_unidade   =  new.o58_unidade
     and o58_anousu    =  new.o58_anousu
     and o58_funcao    =  new.o58_funcao
     and o58_subfuncao =  new.o58_subfuncao
     and o58_programa  =  new.o58_programa
     and o58_projativ  =  new.o58_projativ
     and o58_codele    =  new.o58_codele
     and o58_codigo    =  new.o58_codigo
     and o58_localizadorgastos  = new.o58_localizadorgastos
     and o58_concarpeculiar     = new.o58_concarpeculiar
     and o58_esferaorcamentaria = new.o58_esferaorcamentaria
     and o58_instit             = new.o58_instit;

  if exists then
    raise exception 'Dotação ja existente.';
  end if;

   return new;

end;
$$
language 'plpgsql';

create trigger tg_orcdotacao_inc_alt before INSERT OR UPDATE on orcdotacao for each row execute procedure fc_orcdotaocao_inc_alt();

SQL;
        $this->execute($sSqlTrigger);



    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

drop index orcdotacao_oufspae_in;
alter table orcdotacao drop column o58_esferaorcamentaria;
create unique index 
       orcdotacao_oufspae_in on orcdotacao 
       (o58_anousu, o58_orgao, o58_unidade, 
        o58_funcao, o58_subfuncao, o58_programa, 
        o58_projativ, o58_codele, o58_codigo, o58_localizadorgastos);

delete from db_syscadind where codind = 1008365;
delete from db_sysarqcamp where codcam = 1010123;
delete from db_syscampo where codcam = 1010123;

delete from db_sysindices where codind in (1823, 1008365);
insert into db_sysindices values(1823,'orcdotacao_oufspae_in',758,'1');
delete from db_syscadind where codind in (1823, 1008365);
insert into db_syscadind values (1823 , 5285,  1);
insert into db_syscadind values (1823 , 5287,  2);
insert into db_syscadind values (1823 , 5288,  3);
insert into db_syscadind values (1823 , 5289,  4);
insert into db_syscadind values (1823 , 5290,  5);
insert into db_syscadind values (1823 , 5291,  6);
insert into db_syscadind values (1823 , 5292,  7);
insert into db_syscadind values (1823 , 5293,  8);
insert into db_syscadind values (1823 , 5294,  9);
insert into db_syscadind values (1823 ,14520, 10);

delete from db_syssequencia where codsequencia in (1000787, 1000788);
delete from db_syscadind where codcam in (1010126,1010127,1010128,1010129,1010130, 1010131,1010132,1010133,1010134);
delete from db_sysindices where codind in (1008366, 1008367, 1008368, 1008369);
delete from db_sysprikey where codarq in (1010345, 1010346);
delete from db_sysforkey where codarq in (1010345, 1010346);
delete from db_sysarqcamp where codarq in (1010345, 1010346);
delete from db_syscampo where codcam in (1010126,1010127,1010128,1010129,1010130, 1010131,1010132,1010133,1010134);
delete from db_sysarqmod where codarq in (1010345, 1010346);
delete from db_sysarquivo where codarq in (1010345, 1010346);


drop table if exists planoorcamentariolinhapacto;
drop table if exists orcdotacaoplanoorcamentario;


SQL_DOWN
);

    }
}
