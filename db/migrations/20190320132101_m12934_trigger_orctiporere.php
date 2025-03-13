<?php

use Classes\PostgresMigration;

class M12934TriggerOrctiporere extends PostgresMigration
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
        
create or replace function fc_orctiporec_inc_alt() returns trigger as
$$
declare

     descricao varchar;
    usaFonteUniao boolean default false;

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

  raise notice ' descricao alterada : % ',descricao;

   new.o15_descr := coalesce(new.o15_loaidentificadoruso::varchar,'0') ||'.'|| 
                    coalesce(new.o15_loatipo::varchar            ,'0') ||'.'|| 
                    coalesce(new.o15_loagrupo::varchar           ,'0') ||'.'||
                    coalesce(new.o15_loaespecificacao::varchar  ,'00') ||' - '||
                    coalesce(descricao,'') ;

   raise notice 'Descricao com estrutural % ', new.o15_descr;

   return new;

end;
$$
language 'plpgsql';

create trigger tg_orctiporec_inc_alt before INSERT OR UPDATE on orctiporec for each row execute procedure fc_orctiporec_inc_alt();

SQL
);
    }

    public function down()
    {
        $this->execute("drop trigger tg_orctiporec_inc_alt ON orctiporec;");
        $this->execute("drop function fc_orctiporec_inc_alt();");
    }
}
