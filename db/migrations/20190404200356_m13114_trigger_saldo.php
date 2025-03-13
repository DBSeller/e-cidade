<?php

use Classes\PostgresMigration;

class M13114TriggerSaldo extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
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

SQL;
        $this->execute($sql);
    }
}

