<?php

use Classes\PostgresMigration;

class M13508DetalhamentoFonteUniao extends PostgresMigration
{

    public function up()
    {

        $this->execute($this->getSqlAtualizaTrigger());

        $this->execute($this->getSqlAtualizaFuncao());

    }

    private function getSqlAtualizaFuncao()
    {
        $sql = <<<SQL

        select fc_startsession();
create or replace function fc_detalhamento_fonte(integer) returns text as
$$
   select  coalesce(o15_loaidentificadoruso::varchar,'0') ||'.'||
    coalesce(o15_loatipo::varchar            ,'0') ||'.'||
    coalesce(o15_loagrupo::varchar           ,'0') ||'.'||
    (case
              when o15_loaespecificacao = null then '00'
              when trim(o15_loaespecificacao) = '' then '00'
              else o15_loaespecificacao
            end )
      from orctiporec
     where o15_codigo = $1;
$$
language 'sql';
SQL;
        return $sql;
    }

    private function getSqlAtualizaTrigger()
    {
        $sql = <<<SQL
create or replace function fc_orctiporec_inc_alt() returns trigger as
$$
declare

     descricao varchar;
     usaFonteUniao boolean;

begin

    usaFonteUniao := coalesce(fc_getsession('DB_use_fonte_recurso_uniao')::boolean, false)::boolean;

    if usaFonteUniao is false then
      return new;
    end if;

   if substr(new.o15_descr,10,1) = '-' then
      descricao := substr(new.o15_descr, 12, length(new.o15_descr));
   else
      descricao := new.o15_descr;
   end if;

   new.o15_descr := coalesce(new.o15_loaidentificadoruso::varchar,'0') ||'.'|| 
                    coalesce(new.o15_loatipo::varchar            ,'0') ||'.'|| 
                    coalesce(new.o15_loagrupo::varchar           ,'0') ||'.'||
                    (case
                        when new.o15_loaespecificacao       = null then '00'
                        when trim(new.o15_loaespecificacao) = '' then '00'
                        else new.o15_loaespecificacao
                     end ) ||' - '||
                    coalesce(descricao,'') ;

   return new;

end;
$$
language 'plpgsql';

drop   trigger tg_orctiporec_inc_alt ON orctiporec;
create trigger tg_orctiporec_inc_alt before INSERT OR UPDATE on orctiporec for each row execute procedure fc_orctiporec_inc_alt();
SQL;

        return $sql;

    }

    public function down()
    {


    }
}
