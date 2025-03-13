<?php

use Classes\PostgresMigration;

class M13323LogDosLancamentosContabeis extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP


create table lancamentoscontabeislog (
    sequencial serial primary key not null,
    codlan integer not null,
    id_usuario integer not null,
    data timestamp not null,
    tipo_movimento integer not null
);

create index lancamentoscontabeislog_codlan_in on lancamentoscontabeislog(codlan);
create index lancamentoscontabeislog_id_usuario_in on lancamentoscontabeislog(id_usuario);
create index lancamentoscontabeislog_id_data_in on lancamentoscontabeislog(data);
create index lancamentoscontabeislog_id_anodata_in on lancamentoscontabeislog(extract(year from data));



create or replace function fc_lancamentoscontabeislog_inc_alt_del() returns trigger as
$$
declare

  codigoLancamento integer;
  codigoUsuario integer;

  /**
   * Tipo do movimento contabil
   * 1 - inclusao
   * 2 - alteracao/reprocessamento
   * 3 - exclusao
   */
  tipoMovimento integer;

begin

  codigoUsuario := cast( fc_getsession('DB_id_usuario') as integer);
  if codigoUsuario is null then
    raise exception 'Código do usuário não encontrado.';
  end if;


  tipoMovimento := 1;

  if TG_OP = 'UPDATE' THEN
    tipoMovimento := 2;
  end if;

  if TG_OP = 'DELETE' then
    tipoMovimento := 3;
  end if;


  insert into lancamentoscontabeislog
       (sequencial, codlan, id_usuario, data, tipo_movimento)
       values (
           nextval('lancamentoscontabeislog_sequencial_seq'),
           new.c70_codlan,
           codigoUsuario,
           now(),
           tipoMovimento
       );

  return new;

end;
$$
language 'plpgsql';

drop   trigger if exists tg_lancamentoscontabeislog_inc_alt_del on conlancam;
create trigger tg_lancamentoscontabeislog_inc_alt_del after INSERT OR UPDATE  on conlancam for each row execute procedure fc_lancamentoscontabeislog_inc_alt_del();


SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

drop table if exists lancamentoscontabeislog;
drop   trigger if exists tg_lancamentoscontabeislog_inc_alt_del on conlancam;

SQL_DOWN
);
    }
}
