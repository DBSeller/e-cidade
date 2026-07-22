<?php

use Classes\PostgresMigration;

class M12493ErroCadastroDotacao extends PostgresMigration
{
    public function up()
    {
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
     and o58_instit             = new.o58_instit
     and o58_coddot             <> new.o58_coddot ;

  if found then
    raise exception 'Dotação ja existente.';
  end if;

   return new;

end;
$$
language 'plpgsql';
SQL;
        $this->execute($sSqlTrigger);

    }

}
