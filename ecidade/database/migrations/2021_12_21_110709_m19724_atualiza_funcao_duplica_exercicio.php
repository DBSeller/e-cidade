<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19724AtualizaFuncaoDuplicaExercicio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL

create or replace function fc_duplica_exercicio(text, text, integer, integer, text) returns boolean as
$$
declare
  sTabela       alias for $1;
  sCampoAnousu  alias for $2;
  iExercOrigem  alias for $3;
  iExercDestino alias for $4;
  sWhere        alias for $5;

  sSql        text;
  sSqlWhere   text default '';
  sCamposOrig text;
  sCampos     text;
  lRaise      boolean default false;
begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

  if sWhere is not null and sWhere <> '' then
    sSqlWhere := ' and '||sWhere;
  end if;

  select array_to_string(array_accum(column_name::text), ', '),
         array_to_string(array_accum(case when column_name = sCampoAnousu then iExercDestino::text||'::integer' else column_name end), ', ')
    into sCamposOrig, sCampos
    from information_schema.columns
   where table_name = sTabela
     and table_schema not ilike 'importacao_%';

  if lRaise is true then
    raise notice '%', sCamposOrig;
    raise notice '%', sCampos;
  end if;

  sSql := 'insert into '||sTabela||' ('|| sCamposOrig ||') select '||sCampos||' from '||sTabela||' where '||sCampoAnousu||' = '||iExercOrigem||' '||sSqlWhere||';';
  execute sSql;

  if lRaise is true then
    raise notice '%', sSql;
  end if;

  return true;
end;
$$
language 'plpgsql';

SQL

);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(<<<SQL
create or replace function fc_duplica_exercicio(text, text, integer, integer, text) returns boolean as
$$
declare
  sTabela       alias for $1;
  sCampoAnousu  alias for $2;
  iExercOrigem  alias for $3;
  iExercDestino alias for $4;
  sWhere        alias for $5;

  sSql       text;
  sSqlWhere  text default '';
  sCampos    text;
  lRaise     boolean default false;
begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

  if sWhere is not null and sWhere <> '' then
    sSqlWhere := ' and '||sWhere;
  end if;

  select array_to_string(array_accum(case when column_name = sCampoAnousu then iExercDestino::text||'::integer' else column_name end), ', ')
    into sCampos
    from information_schema.columns
   where table_name = sTabela
     and table_schema not ilike 'importacao_%';

  if lRaise is true then
    raise notice '%', sCampos;
  end if;

  sSql := 'insert into '||sTabela||' select '||sCampos||' from '||sTabela||' where '||sCampoAnousu||' = '||iExercOrigem||' '||sSqlWhere||';';
  execute sSql;

  if lRaise is true then
    raise notice '%', sSql;
  end if;

  return true;
end;
$$
language 'plpgsql';

SQL
);

    }
}
