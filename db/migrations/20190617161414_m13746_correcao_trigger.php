<?php

use Classes\PostgresMigration;

class M13746CorrecaoTrigger extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
  CREATE OR REPLACE FUNCTION public.fc_solicitaanulada_po_inc_alt_del()
   RETURNS trigger
   LANGUAGE plpgsql
  AS $$
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
      where pc11_numero = tipo.pc67_solicita;
  
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
SQL;
        $this->execute($sql);
    }

    public function down()
    {
      $sql = <<<SQL
      CREATE OR REPLACE FUNCTION public.fc_solicitaanulada_po_inc_alt_del()
       RETURNS trigger
       LANGUAGE plpgsql
      AS $$
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
SQL;
        $this->execute($sql);
    }

}
