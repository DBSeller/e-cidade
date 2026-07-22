<?php

use Classes\PostgresMigration;

class M13114EstruturaPlanoSaldoOrcamentario extends PostgresMigration
{


    public function up(){

        $this->dd();

        $this->estrutura();

        $this->atualizaTriggers();
    }

    public function dd()
    {

        $sql = <<<SQL
insert into db_syscampo values(1010366,'o161_sequencial','int4','Codigo sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1010367,'o161_linhapacto','int4','Linha do pacto','0', 'Linha do pacto',10,'f','f','f',1,'text','Linha do pacto');
insert into db_syscampo values(1010368,'o161_data','date','Data do saldo','null', 'Data do saldo',10,'f','f','f',1,'text','Data do saldo');
insert into db_syscampo values(1010369,'o161_saldo','float8','Saldo da movimentação no dia','0', 'Saldo',10,'f','f','f',4,'text','Saldo');
insert into db_syscampo values(1010370,'o162_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1010371,'o162_linhapacto','int4','Linha do pacto da movimentacao','0', 'Linha do pacto',10,'f','f','f',1,'text','Linha do pacto');
insert into db_syscampo values(1010372,'o162_tipo','int4','Tipo de movimentação','0', 'Tipo de movimentação',10,'f','f','f',1,'text','Tipo de movimentação');
insert into db_syscampo values(1010373,'o162_data','date','Data da movimentação','null', 'Data da movimentação',10,'f','f','f',1,'text','Data da movimentação');
insert into db_syscampo values(1010374,'o162_valor','float8','Valor','0', 'Valor',10,'f','f','f',4,'text','Valor');
insert into db_sysarquivo values (1010426, 'linhapactosaldo', 'saldo do pacto', 'o161', '2019-03-25', 'Saldo do pacto', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010426);
insert into db_sysarqcamp values(1010426,1010366,1,0);
insert into db_sysarqcamp values(1010426,1010367,2,0);
insert into db_sysarqcamp values(1010426,1010368,3,0);
insert into db_sysarqcamp values(1010426,1010369,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010426,1010366,1,1010367);
insert into db_sysforkey values(1010426,1010367,1,1010346,0);
insert into db_sysindices values(1008435,'linhapactosaldo_linhapacto_in',1010426,'0');
insert into db_syscadind values(1008435,1010367,1);
insert into db_syssequencia values(1000822, 'linhapactosaldo_o161_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000822 where codarq = 1010426 and codcam = 1010366;
insert into db_sysarquivo values (1010427, 'linhapactosaldomovimentacao', 'Movimentações da linha de pacto', '0162', '2019-03-25', 'Movimentações da linha de pacto', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010427);
delete from db_sysarqcamp where codarq = 1010427;
insert into db_sysarqcamp values(1010427,1010370,1,0);
insert into db_sysarqcamp values(1010427,1010371,2,0);
insert into db_sysarqcamp values(1010427,1010372,3,0);
insert into db_sysarqcamp values(1010427,1010373,4,0);
insert into db_sysarqcamp values(1010427,1010374,5,0);
delete from db_sysprikey where codarq = 1010427;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010427,1010370,1,1010371);
delete from db_sysforkey where codarq = 1010427 and referen = 0;
insert into db_sysforkey values(1010427,1010371,1,1010346,0);
insert into db_sysindices values(1008436,'linhapactosaldomovimentacao_linhapacto_in',1010427,'0');
insert into db_syscadind values(1008436,1010371,1);
insert into db_syssequencia values(1000823, 'linhapactosaldomovimentacao_o162_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000823 where codarq = 1010427 and codcam = 1010370;
SQL;

        $this->execute($sql);

    }

    public function estrutura()
    {

        $sql = <<<SQL
CREATE SEQUENCE linhapactosaldo_o161_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE SEQUENCE linhapactosaldomovimentacao_o162_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE linhapactosaldo(
o161_sequencial		int4 default 0,
o161_linhapacto    int4 default 0,
o161_data          date,
o161_saldo     float8,
CONSTRAINT linhapactosaldo_sequ_pk PRIMARY KEY (o161_sequencial));

CREATE TABLE linhapactosaldomovimentacao(
o162_sequencial		int4 default 0,
o162_linhapacto     int4 default 0,
o162_tipo           int4 default 0,
o162_data           date,
o162_valor          float8,
CONSTRAINT linhapactosaldomovimentacao_sequ_pk PRIMARY KEY (o162_sequencial));

ALTER TABLE linhapactosaldo
ADD CONSTRAINT linhapactosaldo_linhapacto_fk FOREIGN KEY (o161_linhapacto)
REFERENCES planoorcamentariolinhapacto;

ALTER TABLE linhapactosaldomovimentacao
ADD CONSTRAINT linhapactosaldomovimentacao_linhapacto_fk FOREIGN KEY (o162_linhapacto)
REFERENCES planoorcamentariolinhapacto;

CREATE  INDEX linhapactosaldo_linhapacto_in ON linhapactosaldo(o161_linhapacto);
CREATE  INDEX linhapactosaldomovimentacao_linhapacto_in ON linhapactosaldomovimentacao(o162_linhapacto);

        
        
        

SQL;
        $this->execute($sql);
    }

    public function atualizaTriggers()
    {
$sql = <<<SQL
create or replace function fc_linhapactosaldomovimentacao_inc_alt() returns trigger as
$$
declare
  nSaldo     numeric;
  dataSessao date;
  tipo       record;

begin

  dataSessao := cast( fc_getsession('DB_datausu') as date);
  if dataSessao is null then
    raise exception 'Data da sessão não encontrada.';
  end if;

  if TG_OP = 'DELETE' then
    tipo := old;
  else
    tipo := new;
  end if;

  nSaldo := ( select sum(o162_valor)
                from linhapactosaldomovimentacao
               where o162_data       = tipo.o162_data
                 and o162_linhapacto = tipo.o162_linhapacto );

  perform 1
     from linhapactosaldo
    where o161_data       = tipo.o162_data
      and o161_linhapacto = tipo.o162_linhapacto;

  if found then

    update linhapactosaldo
       set o161_saldo = nSaldo
     where o161_data  = tipo.o162_data
       and linhapactosaldo.o161_linhapacto = tipo.o162_linhapacto;

    return tipo;

  end if;

  insert into linhapactosaldo values (nextval('linhapactosaldo_o161_sequencial_seq'), tipo.o162_linhapacto, dataSessao, nSaldo);
  return tipo;

end;
$$
language 'plpgsql';

create trigger tg_linhapactosaldomovimentacao_inc_alt_del after INSERT OR UPDATE OR DELETE on linhapactosaldomovimentacao for each row execute procedure fc_linhapactosaldomovimentacao_inc_alt();

create or replace function fc_atualiza_saldo_po(linhapacto integer, tipo integer, data date, valor numeric, exclusao boolean default false) returns boolean as
$$
declare


  valorPO  numeric default 0;
  begin

  if linhapacto is null then
      return false;
   end if;

  valorPO := valor;

  /*Tipos de saldos:
   * 1 - reducao
   * 2 - suplementacao
   * 3 - Movimenacao
   * 4 - Anulacao
   * 5 - anulacao reducao
   * 6 - anulacao Suplementacao
   */
  raise notice 'Tipo da Movimentacao: %, Valor: %', tipo, valor;
  valorPO := ( case
                 when tipo in (1,3)
                   then valor * -1
                 when tipo in (2,4) then valor
               else valor
               end );

  raise notice 'valor do PO: %', valorPO;
  if exclusao is true then
    valorPO := valorPO *-1;
  end if;
  insert
    into linhapactosaldomovimentacao
        (o162_sequencial,
         o162_linhapacto,
         o162_tipo,
         o162_data,
         o162_valor
      )
  values (
          nextval('linhapactosaldomovimentacao_o162_sequencial_seq'),
          linhapacto,
          tipo,
          data,
          valorPo
         );
  return true;
end;
$$
language 'plpgsql';


create or replace function fc_pcdotac_po_inc_alt_del() returns trigger as
$$
declare

  tipo  record;
  dataSessao date;
  exclusao boolean default false;

begin

  dataSessao := cast( fc_getsession('DB_datausu') as date);
  if dataSessao is null then
    raise exception 'Data da sessão não encontrada.';
  end if;

  if TG_OP = 'DELETE' then
    tipo := old;
    exclusao := true;
  else
    tipo := new;
  end if;

  if TG_OP = 'UPDATE' THEN

    if old.pc13_planoorcamentariolinhapacto is not null then
      perform fc_atualiza_saldo_po(old.pc13_planoorcamentariolinhapacto, 3, dataSessao, cast(old.pc13_valor as numeric), true);
    end if;
  end if;

  if tipo.pc13_planoorcamentariolinhapacto is not null then
     perform fc_atualiza_saldo_po(tipo.pc13_planoorcamentariolinhapacto, 3, dataSessao, cast(tipo.pc13_valor as numeric), exclusao);
  end if;

  return tipo;

end;
$$
language 'plpgsql';

create trigger fc_pcdotac_po_inc_alt_del after INSERT OR UPDATE OR DELETE on pcdotac for each row execute procedure fc_pcdotac_po_inc_alt_del();

create or replace function fc_pempautidot_po_inc_alt_del() returns trigger as
$$
declare

  tipo  record;
  dataSessao date;
  exclusao boolean default false;
  valor numeric default 0;
  codigoAutorizacao int;
begin

  dataSessao := cast( fc_getsession('DB_datausu') as date);
  if dataSessao is null then
    raise exception 'Data da sessão não encontrada.';
  end if;

  if TG_OP = 'DELETE' then
    tipo := old;
    exclusao := true;
  else
    tipo := new;
  end if;

  select e54_valor
     into valor
     from empautoriza
    where e54_autori = tipo.e56_autori;

    if TG_OP = 'UPDATE' THEN
      if old.e56_planoorcamentariolinhapacto is not null then
        perform fc_atualiza_saldo_po(old.e56_planoorcamentariolinhapacto, 3, dataSessao, cast(valor as numeric), true);
      end if;
  end if;

  if tipo.e56_planoorcamentariolinhapacto is not null then
    perform fc_atualiza_saldo_po(tipo.e56_planoorcamentariolinhapacto, 3, dataSessao, cast(valor as numeric), exclusao);
  end if;

  return tipo;

end;
$$
language 'plpgsql';

create trigger fc_pempautidot_po_inc_alt_del after INSERT OR UPDATE OR DELETE on empautidot for each row execute procedure fc_pempautidot_po_inc_alt_del();


create or replace function fc_orcreserva_po_inc_alt_del() returns trigger as
$$
declare

  tipo  record;
  dataSessao date;
  exclusao boolean default false;

begin

  dataSessao := cast( fc_getsession('DB_datausu') as date);
  if dataSessao is null then
    raise exception 'Data da sessão não encontrada.';
  end if;

  if TG_OP = 'DELETE' then
    tipo := old;
    exclusao := true;
  else
    tipo := new;
  end if;

  if TG_OP = 'UPDATE' THEN

    if old.o80_planoorcamentariolinhapacto is not null then
      perform fc_atualiza_saldo_po(old.o80_planoorcamentariolinhapacto, 3, dataSessao, cast(old.o80_valor as numeric), true);
    end if;
  end if;

  if tipo.o80_planoorcamentariolinhapacto is not null then
    perform fc_atualiza_saldo_po(tipo.o80_planoorcamentariolinhapacto, 3, dataSessao, cast(tipo.o80_valor as numeric), exclusao);
  end if;

  return tipo;

end;
$$
language 'plpgsql';
create trigger fc_orcreserva_po_inc_alt_del after INSERT OR UPDATE OR DELETE on orcreserva for each row execute procedure fc_orcreserva_po_inc_alt_del();
SQL;

$this->execute($sql);

    }

    public function down()
    {
        $sql = <<<SQL
                drop trigger fc_orcsuplemval_po_inc_alt_del on orcsuplemval;
                drop trigger fc_pempautidot_po_inc_alt_del  on empautidot;
                drop trigger fc_pcdotac_po_inc_alt_del      on pcdotac;
                drop trigger tg_linhapactosaldomovimentacao_inc_alt_del on linhapactosaldomovimentacao;
                drop function fc_linhapactosaldomovimentacao_inc_alt();
                drop function fc_atualiza_saldo_po(integer,integer,date,numeric,boolean);
                drop function fc_pcdotac_po_inc_alt_del();
                drop function fc_pempautidot_po_inc_alt_del();   
                drop function fc_orcsuplemval_po_inc_alt_del();
SQL;

        $this->execute($sql);

        $sql = <<<SQL
                drop table linhapactosaldomovimentacao;
                drop table linhapactosaldo;
                drop sequence linhapactosaldo_o161_sequencial_seq;            
                drop sequence linhapactosaldomovimentacao_o162_sequencial_seq;
SQL;
        $this->execute($sql);

        $sql = <<<SQL
delete from db_syscadind    where codcam in (1010366,1010367,1010368,1010369,1010370,1010371,1010372,1010373,1010374);
delete from db_sysarqmod    where codarq = 1010426;
delete from db_sysprikey    where codarq = 1010426;
delete from db_sysprikey    where codarq = 1010427;
delete from db_sysforkey    where codarq = 1010426;
delete from db_sysindices   where codarq = 1010426;
delete from db_syssequencia where codsequencia = 1000822;
delete from db_sysarqcamp where codarq = 1010427;
delete from db_sysarqmod  where codarq = 1010427;
delete from db_sysprikey  where codarq = 1010427;
delete from db_sysforkey  where codarq = 1010427;
delete from db_sysindices where codarq = 1010427;
delete from db_syssequencia where codsequencia in (1000822, 1000823) ;
delete from db_sysarqcamp   where codarq in (1010426, 1010427);
delete from db_sysarquivo   where codarq in (1010426, 1010427);
delete from db_syscampo     where codcam in (1010366,1010367,1010368,1010369,1010370,1010371,1010372,1010373,1010374);
SQL;
        $this->execute($sql);

    }

}

