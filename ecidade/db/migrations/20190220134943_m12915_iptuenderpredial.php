<?php

use Classes\PostgresMigration;

class M12915Iptuenderpredial extends PostgresMigration
{
    public function up()
    {
        $sql  = <<<SQL

create or replace function fc_iptuenderpredial(integer)
returns setof tp_iptuender as 
$$
declare

   iMatric alias for $1;
   lRaise boolean default false;
   rtp_IptuEnder tp_iptuender%ROWTYPE;
   iNumCgm integer default 0;
   iAnoUsu integer default null;
   iInstit integer default null;
   rsPredial record;
   lPredial boolean default false;

begin
  
   iAnoUsu := cast(fc_getsession('DB_anousu') as integer);
   iInstit := cast(fc_getsession('DB_instit') as integer);
   lRaise := (case when fc_getsession('DB_debugon') is null then false else true end);
  
   select case 
          when count > 0 
              then true 
              else false 
          end 
        into lPredial 
        from (select count(*) 
                from iptuconstr 
               where j39_matric = iMatric 
                 and j39_dtdemo is null) as x;
  if lPredial is true then     
    
      for rsPredial in 
         select j14_nome,
                j13_descr, 
                j39_numero, 
                j39_compl, 
                munic,
                uf,
                case 
                 when j29_cep is null then cep
                 else j29_cep 
                end as cep
           from iptuconstr 
                inner join ruas on j39_codigo = j14_codigo
                inner join iptubase on j01_matric = j39_matric
                inner join lote on j01_idbql = j34_idbql
                inner join bairro on j13_codi = j34_bairro
                inner join db_config on codigo = iInstit
                left  join ruascep on j29_codigo = j14_codigo
          where j39_matric = iMatric
            and j39_dtdemo is null 
            and j39_idprinc is true
      loop

         rtp_IptuEnder.riMatric   := iMatric;
         rtp_IptuEnder.rsCompl    := rsPredial.j39_compl; 
         rtp_IptuEnder.rsEndereco := rsPredial.j14_nome; 
         rtp_IptuEnder.rsNumero   := rsPredial.j39_numero; 
         rtp_IptuEnder.rsBairro   := rsPredial.j13_descr; 
         rtp_IptuEnder.rsUf       := rsPredial.uf; 
         rtp_IptuEnder.rsCep      := rsPredial.cep; 
         rtp_IptuEnder.rsMunic    := rsPredial.munic; 

         return next rtp_IptuEnder; 

      end loop; 
   end if; 

   return; 
end;
$$
language 'plpgsql';

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql  = <<<SQL

create or replace function fc_iptuenderpredial(integer)
returns setof tp_iptuender as 
$$
declare

   iMatric        alias for $1;
   lRaise         boolean default false;
   rtp_IptuEnder  tp_iptuender%ROWTYPE;
   iNumCgm        integer default 0;
   iAnoUsu        integer default null;
   iInstit        integer default null;
   rsPredial      record;
   lPredial       boolean default false;
   sSQL           text;

begin
  
   --Dados da sessao.
   iAnoUsu := cast(fc_getsession('DB_anousu') as integer);
   iInstit := cast(fc_getsession('DB_instit') as integer);
   lRaise  := (case when fc_getsession('DB_debugon') is null then false else true end); 
--   perform fc_debug('Iniciando Consulta do endereco Predial',lRaise,false, false);  
  
   select case 
          when count > 0 
              then true 
              else false 
          end 
        into lPredial 
        from (select count(*) 
                from iptuconstr 
               where j39_matric = iMatric 
                 and j39_dtdemo is null) as x;
  if lPredial is true then     
    
      sSQL := 'select j14_nome,
                      j13_descr, 
                      j39_numero, 
                      j39_compl, 
                      munic,
                      uf,
                      case when j29_cep is null then cep
                           else j29_cep end as cep
                 from iptuconstr 
                      inner join ruas      on j39_codigo = j14_codigo
                      inner join iptubase  on j01_matric = j39_matric
                      inner join lote      on j01_idbql  = j34_idbql
                      inner join bairro    on j13_codi   = j34_bairro
                      inner join db_config on codigo     = '||iInstit||'
                      left  join ruascep   on j29_codigo = j14_codigo
                where j39_matric =  '|| iMatric ||'
               and j39_dtdemo  is null 
               and j39_idprinc is true';

       for rsPredial in execute sSQL loop

          rtp_IptuEnder.riMatric       := iMatric;
          rtp_IptuEnder.rsCompl        := rsPredial.j39_compl; 
          rtp_IptuEnder.rsEndereco     := rsPredial.j14_nome; 
          rtp_IptuEnder.rsNumero       := rsPredial.j39_numero; 
          rtp_IptuEnder.rsBairro       := rsPredial.j13_descr; 
          rtp_IptuEnder.rsUf           := rsPredial.uf; 
          rtp_IptuEnder.rsCep          := rsPredial.cep; 
          rtp_IptuEnder.rsMunic        := rsPredial.munic; 
          return next rtp_IptuEnder; 
       end loop;  
   end if;     
  -- perform fc_debug('fim da consulta do endereco da construcao',lRaise,false, true);  
   return; 
end;
$$
language 'plpgsql';

SQL;
        $this->execute($sql);
    }
}
