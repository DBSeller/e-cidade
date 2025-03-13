<?php

use Classes\PostgresMigration;

class M13114TriggerPlanoOrcamentarioLinhaPacto extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
      $this->execute(<<<SQL
create or replace function fc_planoorcamentario_po_inc_alt_del() returns trigger as
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
    return tipo;
  else
    tipo := new;
  end if;

  if TG_OP = 'UPDATE' THEN

    if old.o156_sequencial is not null then
      perform fc_atualiza_saldo_po(old.o156_sequencial, 7, dataSessao, cast(old.o156_valor as numeric), true);
    end if;
  end if;

  if tipo.o156_sequencial is not null then
    perform fc_atualiza_saldo_po(tipo.o156_sequencial, 7, dataSessao, cast(tipo.o156_valor as numeric), exclusao);
  end if;

  return tipo;

end;
$$
language 'plpgsql';

create trigger fc_planoorcamentario_po_inc_alt_del after INSERT OR UPDATE on planoorcamentariolinhapacto for each row execute procedure fc_planoorcamentario_po_inc_alt_del();

SQL
      );
    }

    public function down()
    {
        $this->execute("drop  trigger fc_planoorcamentario_po_inc_alt_del on planoorcamentariolinhapacto;");
        $this->execute("drop function fc_planoorcamentario_po_inc_alt_del()");
    }
}

