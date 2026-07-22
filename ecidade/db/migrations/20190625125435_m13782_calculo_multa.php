<?php

use Classes\PostgresMigration;

class M13782CalculoMulta extends PostgresMigration
{
    public function up()
    {
        $sql = <<<STRING

create or replace function fc_multa(integer,date,date,date,integer) returns real as 
$$
declare
  rece_multa     alias for $1;
  data_venc      alias for $2;
  data_hoje      alias for $3;
  dtoper         alias for $4;
  subdir         alias for $5;

  dias           integer;
  v_tabrec       record;
  v_carnes       char(10);
  retorno_multa  float8 default 0;
  datavenc       date;
  data_certa     date;
  nPercentual    float8 default 0;

begin
  
  if data_hoje <= data_venc then
    return 0;
  end if;

  /* Condicao implementada para Sapiranga */

  perform
  from db_config
  where codigo = fc_getsession('DB_instit')::int and db21_codcli = 4;

  if found then
     dtoper = data_venc;
  end if;

  select tabrecjm.*
    into v_tabrec
    from tabrec 
         inner join tabrecregrasjm on tabrecregrasjm.k04_receit = tabrec.k02_codigo
         inner join tabrecjm       on tabrecjm.k02_codjm        = tabrecregrasjm.k04_codjm
   where tabrec.k02_codigo = rece_multa
     and dtoper between tabrecregrasjm.k04_dtini and tabrecregrasjm.k04_dtfim;

  if not found then
    
    select tabrecjm.*
      into v_tabrec
      from tabrec
           inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
     where tabrec.k02_codigo = rece_multa;

    if not found then
      return 0;
    end if;

  end if;

  datavenc = data_hoje;
  if v_tabrec.k02_sabdom = true then
    loop
      datavenc = datavenc - 1 ; 
      select k13_data
        into data_certa
        from calend
       where k13_data = datavenc;
      if data_certa is null then
        data_certa = datavenc+1 ;
        exit;
      end if;
    end loop;
  else
    data_certa := data_hoje;
  end if;

  if data_venc < data_certa then 

    dias := date_mi(data_certa,data_venc);
    
    if v_tabrec.k02_dtfrac isnull or dtoper <= v_tabrec.k02_dtfrac then
    
      retorno_multa := 0;
      
      select k140_faixa
        into nPercentual
        from tabrecjmmulta
       where tabrecjmmulta.k140_tabrecjm = v_tabrec.k02_codjm
         and tabrecjmmulta.k140_multa   >= dias
       order by tabrecjmmulta.k140_multa, tabrecjmmulta.k140_sequencial
       limit 1;
      
      if not found then
        return 0;
      else
        return coalesce((nPercentual::float8 / 100::float8), 0);
      end if;
      
    else

      retorno_multa := (dias * v_tabrec.k02_mulfra);
      
      if not v_tabrec.k02_limmul isnull and v_tabrec.k02_limmul <> 0 then
      
        if retorno_multa > v_tabrec.k02_limmul then
          
          retorno_multa := v_tabrec.k02_limmul::float8;
          
        end if;
        
      end if;

      return retorno_multa::float8 / 100;
      
    end if;
    
  else 
  
    return 0;
    
  end if;
end;
$$ language 'plpgsql';

STRING;

    $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<STRING

create or replace function fc_multa(integer,date,date,date,integer) returns real as 
$$
declare
  rece_multa     alias for $1;
  data_venc      alias for $2;
  data_hoje      alias for $3;
  dtoper         alias for $4;
  subdir         alias for $5;

  dias           integer;
  v_tabrec       record;
  v_carnes       char(10);
  retorno_multa  float8 default 0;
  datavenc       date;
  data_certa     date;
  nPercentual    float8 default 0;

begin
  
  if data_hoje <= data_venc then
    return 0;
  end if;

  select tabrecjm.*
    into v_tabrec
    from tabrec 
         inner join tabrecregrasjm on tabrecregrasjm.k04_receit = tabrec.k02_codigo
         inner join tabrecjm       on tabrecjm.k02_codjm        = tabrecregrasjm.k04_codjm
   where tabrec.k02_codigo = rece_multa
     and dtoper between tabrecregrasjm.k04_dtini and tabrecregrasjm.k04_dtfim;

  if not found then
    
    select tabrecjm.*
      into v_tabrec
      from tabrec
           inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
     where tabrec.k02_codigo = rece_multa;

    if not found then
      return 0;
    end if;

  end if;

  datavenc = data_hoje;
  if v_tabrec.k02_sabdom = true then
    loop
      datavenc = datavenc - 1 ; 
      select k13_data
        into data_certa
        from calend
       where k13_data = datavenc;
      if data_certa is null then
        data_certa = datavenc+1 ;
        exit;
      end if;
    end loop;
  else
    data_certa := data_hoje;
  end if;

  if data_venc < data_certa then 

    dias := date_mi(data_certa,data_venc);
    
    if v_tabrec.k02_dtfrac isnull or dtoper <= v_tabrec.k02_dtfrac then
    
      retorno_multa := 0;
      
      select k140_faixa
        into nPercentual
        from tabrecjmmulta
       where tabrecjmmulta.k140_tabrecjm = v_tabrec.k02_codjm
         and tabrecjmmulta.k140_multa   >= dias
       order by tabrecjmmulta.k140_multa, tabrecjmmulta.k140_sequencial
       limit 1;
      
      if not found then
        return 0;
      else
        return coalesce((nPercentual::float8 / 100::float8), 0);
      end if;
      
    else

      retorno_multa := (dias * v_tabrec.k02_mulfra);
      
      if not v_tabrec.k02_limmul isnull and v_tabrec.k02_limmul <> 0 then
      
        if retorno_multa > v_tabrec.k02_limmul then
          
          retorno_multa := v_tabrec.k02_limmul::float8;
          
        end if;
        
      end if;

      return retorno_multa::float8 / 100;
      
    end if;
    
  else 
  
    return 0;
    
  end if;
end;
$$ language 'plpgsql';

STRING;

     $this->execute($sql);

    }
}