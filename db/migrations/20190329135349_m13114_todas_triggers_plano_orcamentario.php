<?php

use Classes\PostgresMigration;

class M13114TodasTriggersPlanoOrcamentario extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP
create or replace function fc_linhapactosaldomovimentacao_inc_alt() returns trigger as
$$
declare
  nSaldo      numeric;
  nSaldoGeral numeric;
  dataSessao  date;
  tipo        record;

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

  dataSessao = tipo.o162_data;
  nSaldo := ( select coalesce(sum(o162_valor), 0)
                from linhapactosaldomovimentacao
               where o162_data       = tipo.o162_data
                 and o162_linhapacto = tipo.o162_linhapacto );

nSaldoGeral := ( select coalesce(sum(o162_valor), 0)
              from linhapactosaldomovimentacao
             where o162_linhapacto = tipo.o162_linhapacto );


 if nSaldoGeral  < 0 then
   raise exception 'Saldo da linha do plano orcamentário negativo. Operação cancelada';
 end if;

  perform 1
     from linhapactosaldo
    where o161_data       = tipo.o162_data
      and o161_linhapacto = tipo.o162_linhapacto;

  if found then

    delete
      from linhapactosaldo
     where o161_data  = tipo.o162_data
       and linhapactosaldo.o161_linhapacto = tipo.o162_linhapacto;

  end if;

  raise notice 'Saldo: %', nSaldo;
  if nSaldo <> 0 then
   insert into linhapactosaldo values (nextval('linhapactosaldo_o161_sequencial_seq'), tipo.o162_linhapacto, dataSessao, nSaldo);
  end if;
  return tipo;

end;
$$
language 'plpgsql';

drop trigger if exists tg_linhapactosaldomovimentacao_inc_alt_del on linhapactosaldomovimentacao;
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
   * 3 - Movimentacao
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

drop trigger if exists fc_pcdotac_po_inc_alt_del on pcdotac;
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

drop trigger if exists fc_pempautidot_po_inc_alt_del on empautidot;
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

drop trigger if exists fc_orcreserva_po_inc_alt_del on orcreserva;
create trigger fc_orcreserva_po_inc_alt_del after INSERT OR UPDATE OR DELETE on orcreserva for each row execute procedure fc_orcreserva_po_inc_alt_del();

/* trigger para a solicitaanulada */
create or replace function fc_solicitaanulada_po_inc_alt_del() returns trigger as
$$
declare

  tipo     record;
  itens    record;

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


  perform  1
     from solicitem
          inner join pcprocitem on pc81_solicitem = pc11_codigo
          inner join empautitempcprocitem on e73_pcprocitem = pc81_codprocitem
    where pc11_solicita = tipo.pc67_solicita;

  if found then
    return tipo;
  end if;

  for itens in select p.pc13_valor,
                             pc13_planoorcamentariolinhapacto
                        from solicitem
                             inner join pcdotac p on p.pc13_codigo = pc11_codigo
                       where pc11_numero = tipo.pc67_solicita
  loop

  if itens.pc13_planoorcamentariolinhapacto is not null then
    perform fc_atualiza_saldo_po(itens.pc13_planoorcamentariolinhapacto, 3, dataSessao, cast(itens.pc13_valor as numeric), true);
  end if;

  end loop;

  return tipo;

end;
$$
language 'plpgsql';

drop trigger if exists fc_solicitaanulada_po_inc_alt_del on solicitaanulada;
create trigger fc_solicitaanulada_po_inc_alt_del after INSERT OR UPDATE OR DELETE on solicitaanulada for each row execute procedure fc_solicitaanulada_po_inc_alt_del();



/* trigger para a empanuladoitem */
create or replace function fc_empanulado_po_inc_alt_del() returns trigger as
$$
declare

  tipo     record;
  itens    record;

  linhapacto integer;

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

  select case when not exists (select 1 from empautitempcprocitem where e73_autori = e61_autori)
                then e56_planoorcamentariolinhapacto
              else (select pc13_planoorcamentariolinhapacto
                    from pcprocitem
                           join solicitem on pc11_codigo = pc81_solicitem
                           join pcdotac on pc13_codigo = pc11_codigo
                           join empautitempcprocitem on e73_autori = e61_autori
                    where pc81_codprocitem = e73_pcprocitem
                      and pc13_coddot = e56_coddot
                      and pc13_anousu = e56_anousu limit 1)
           end as linhapacto
         into linhapacto
  from empanulado
         inner join empempaut    on e61_numemp = e94_numemp
         inner join empautidot   on e56_autori = e61_autori
  where e61_numemp = tipo.e94_numemp;

  if linhapacto is not null then
    perform fc_atualiza_saldo_po(linhapacto, 4, dataSessao, cast(tipo.e94_valor as numeric) );
  end if;

  return tipo;

end;
$$
  language 'plpgsql';

drop   trigger if exists  fc_empanulado_po_inc_alt_del on empanulado;
create trigger fc_empanulado_po_inc_alt_del after INSERT OR UPDATE OR DELETE on empanulado for each row execute procedure fc_empanulado_po_inc_alt_del();


SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

drop trigger if exists tg_linhapactosaldomovimentacao_inc_alt_del on linhapactosaldomovimentacao;
drop trigger if exists fc_pcdotac_po_inc_alt_del on pcdotac;
drop trigger if exists fc_pempautidot_po_inc_alt_del on empautidot;
drop trigger if exists fc_orcsuplemval_po_inc_alt_del on orcsuplemval;
drop trigger if exists fc_orcreserva_po_inc_alt_del on orcreserva;
drop trigger if exists fc_solicitaanulada_po_inc_alt_del on solicitaanulada;
drop trigger if exists  fc_empanulado_po_inc_alt_del on empanulado;

SQL_DOWN
);

    }
}

