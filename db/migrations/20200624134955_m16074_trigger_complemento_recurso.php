<?php

use Classes\PostgresMigration;

class M16074TriggerComplementoRecurso extends PostgresMigration
{

    public function up()
    {

        $sql = <<<SQL
create or replace function fc_orctiporec_inc_alt() returns trigger as
$$
declare

     descricao varchar;
     usaFonteUniao boolean;
     usaFonte2020 boolean;

begin

--    raise notice 'substr -> % ',substr(new.o15_descr,10,1);

    usaFonteUniao := coalesce(fc_getsession('DB_use_fonte_recurso_uniao')::boolean, false)::boolean;
    usaFonte2020  := coalesce(fc_getsession('DB_use_fonte_2020')::boolean, false)::boolean;

    if usaFonteUniao is false then
      return new;
    end if;

   -- retiramos o estrutural ou codigo do inicio da descricao
   descricao := substr(new.o15_descr,(strpos(new.o15_descr,' - ') +2), length(new.o15_descr));

   if usaFonte2020 is true then
--       raise notice ' descricao alterada : % ',descricao;
       new.o15_descr := (case
                            when new.o15_loaespecificacao       = null then new.o15_codigo::varchar
                            when trim(new.o15_loaespecificacao) = ''   then new.o15_codigo::varchar
                            else new.o15_loaespecificacao
                         end ) ||' - '||
                        coalesce(descricao,'') ;
--       raise notice ' descricao alterada novo : % ',new.o15_descr;
      return new;
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

--    raise notice 'Descricao com estrutural % ', new.o15_descr;

   return new;

end;
$$
language 'plpgsql';

drop   trigger tg_orctiporec_inc_alt ON orctiporec;
create trigger tg_orctiporec_inc_alt before INSERT OR UPDATE on orctiporec for each row execute procedure fc_orctiporec_inc_alt();

SQL;
        $this->execute($sql);

    }
}
