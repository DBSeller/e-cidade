<?php

use Classes\PostgresMigration;

class M12258DombaTriggerContaBancaria extends PostgresMigration
{

    public function up()
    {
        $this->execute(
<<<SQL_UP

create or replace function fc_conplanoconta_inc_alt_del() returns trigger as
$$
declare 

  sNomeTabela          varchar;
  sOperacao            varchar;
                      
  sCodBancoOld         varchar;
  sCodAgenciaOld       varchar;
  sDigAgenciaOld       varchar;
                      
  sCodBancoNew         varchar;
  sCodAgenciaNew       varchar;
  sDigAgenciaNew       varchar;
                      
  sSql                 text;
 
  iCodigoBanco         integer;
  iCodigoAgencia       integer;
  iCodigoContaBancaria integer;
    
  rContaBancaria       record;
  
 
begin

  sNomeTabela := lower(TG_RELNAME);
  sOperacao   := upper(TG_OP);

  ---------
  -- Dispara UPDATE na  tabela de ligação do servidor com a contabancária.
  -----------
  if sNomeTabela in ('contabancaria') and sOperacao in ( 'INSERT', 'UPDATE' ) then 

    update rhpessoalmovcontabancaria
       set rh138_contabancaria = rh138_contabancaria
     where rh138_contabancaria = new.db83_sequencial;
  end if;

  perform *
     from conparametro
    where c90_utilcontabancaria is false;

  if found then
    if sOperacao = 'UPDATE' or  sOperacao = 'INSERT' then
      return new;
    else 
      return old;
    end if;
  end if;

  if sNomeTabela = 'contabancaria' then
  
    --
    -- CASO A CONTA SE TRATE DE UMA CONTA QUE NAO SEJA DA PREFEITURA, OU SEJA, QUE O CAMPO db83_contaprefeitura SEJA FALSE
    -- NAO EH EXECUTADO AS OPERACOES DA TRIGGER, RETORNANDO OS REGISTROS SEM ALTERACOES.
    --
    if sOperacao <> 'DELETE' then
    
      if new.db83_contaplano is false then
        return new; 
      end if;
    end if;

    -- Consulta codigo do banco, agencia e digito apartir do bancoagencia cadastrado  anteriormente
    if sOperacao = 'UPDATE' then 
    
      select db_bancos.db90_codban,
             bancoagencia.db89_codagencia,
             bancoagencia.db89_digito
        into sCodBancoOld,
             sCodAgenciaOld,
             sDigAgenciaOld
        from bancoagencia 
             inner join db_bancos on db_bancos.db90_codban = bancoagencia.db89_db_bancos
       where bancoagencia.db89_sequencial = old.db83_bancoagencia;
      
      
      -- Verifica se foi alterado db83_bancoagencia;

      if  old.db83_bancoagencia != new.db83_bancoagencia then 
      
        -- Consulta codigo do banco, agencia e digito apartir do bancoagencia novo
        select db_bancos.db90_codban,
               bancoagencia.db89_codagencia,
               bancoagencia.db89_digito
          into sCodBancoNew,
               sCodAgenciaNew,
               sDigAgenciaNew
          from bancoagencia 
               inner join db_bancos on db_bancos.db90_codban = bancoagencia.db89_db_bancos
         where bancoagencia.db89_sequencial = new.db83_bancoagencia;
      
      else       
        sCodBancoNew   := sCodBancoOld;  
        sCodAgenciaNew := sCodAgenciaOld;
        sDigAgenciaNew := sDigAgenciaOld;
      
      end if;    
      
      update conplanoconta
         set c63_banco          = sCodBancoNew,
             c63_agencia        = sCodAgenciaNew,
             c63_conta          = new.db83_conta,
             c63_dvconta        = new.db83_dvconta,
             c63_dvagencia      = sDigAgenciaNew,
             c63_identificador  = new.db83_identificador,
             c63_codigooperacao = new.db83_codigooperacao,
             c63_tipoconta      = new.db83_tipoconta
       where c63_banco          = sCodBancoOld
         and c63_agencia        = sCodAgenciaOld 
         and c63_conta          = old.db83_conta
         and c63_dvconta        = old.db83_dvconta
         and c63_dvagencia      = sDigAgenciaOld
         and c63_identificador  = old.db83_identificador
         and c63_codigooperacao = old.db83_codigooperacao
         and c63_tipoconta      = old.db83_tipoconta;
      
   end if;

  elsif sNomeTabela = 'bancoagencia' then  

     update conplanoconta 
        set c63_banco     = new.db89_db_bancos,
            c63_agencia   = new.db89_codagencia,
            c63_dvagencia = new.db89_digito            
      where c63_banco     = old.db89_db_bancos
        and c63_agencia   = old.db89_codagencia
        and c63_dvagencia = old.db89_digito;


  elsif sNomeTabela = 'conplanocontabancaria' then

    if sOperacao = 'DELETE' then
      iCodigoContaBancaria = old.c56_contabancaria;
    else
      iCodigoContaBancaria = new.c56_contabancaria;
    end if;
  
    --raise notice 'sOperacao :%', sOperacao;
    
    select *
      into rContaBancaria
      from contabancaria
           inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
     where contabancaria.db83_sequencial = iCodigoContaBancaria;
    
     --raise notice 'rContaBancaria.db83_contaplano :%',rContaBancaria.db83_contaplano;
     
    if rContaBancaria.db83_contaplano is true then

     -- raise notice 'sOperacao: %', sOperacao;
      if sOperacao = 'INSERT' then 
    
        perform * 
           from conplanoconta 
          where c63_codcon = new.c56_codcon
            and c63_reduz  = new.c56_reduz
            and c63_anousu = new.c56_anousu;
  
        if found then
  
          update conplanoconta
             set c63_banco          = rContaBancaria.db89_db_bancos,
                 c63_agencia        = rContaBancaria.db89_codagencia,
                 c63_conta          = rContaBancaria.db83_conta,
                 c63_dvconta        = rContaBancaria.db83_dvconta,
                 c63_dvagencia      = rContaBancaria.db89_digito,
                 c63_identificador  = rContaBancaria.db83_identificador,
                 c63_codigooperacao = rContaBancaria.db83_codigooperacao,
                 c63_reduz          = new.c56_reduz,
                 c63_tipoconta      = rContaBancaria.db83_tipoconta
           where c63_codcon         = new.c56_codcon
             and c63_anousu         = new.c56_anousu
             and c63_reduz          = new.c56_reduz;

        else

          insert into conplanoconta ( c63_codcon, 
                                      c63_anousu, 
                                      c63_banco,
                                      c63_agencia,
                                      c63_conta,
                                      c63_dvconta,
                                      c63_dvagencia,
                                      c63_identificador,
                                      c63_codigooperacao,
                                      c63_tipoconta,
                                      c63_reduz
                                    ) values (
                                      new.c56_codcon,
                                      new.c56_anousu,
                                      rContaBancaria.db89_db_bancos,
                                      rContaBancaria.db89_codagencia,
                                      rContaBancaria.db83_conta,
                                      rContaBancaria.db83_dvconta,
                                      rContaBancaria.db89_digito,
                                      rContaBancaria.db83_identificador,
                                      rContaBancaria.db83_codigooperacao,
                                      rContaBancaria.db83_tipoconta,
                                      new.c56_reduz
                                    );
        end if; 
  
      elsif sOperacao = 'UPDATE' then      
  
        perform 1 
           from conplanoconta
          where conplanoconta.c63_codcon = new.c56_codcon
            and conplanoconta.c63_anousu = new.c56_anousu
            and conplanoconta.c63_reduz  = new.c56_reduz;

        if found then
        
          sSql := ' select *
                      from contabancaria
                           inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
                     where contabancaria.db83_sequencial = '||new.c56_contabancaria;
          for rContaBancaria in execute sSql loop
           
            update conplanoconta
               set c63_codcon         = new.c56_codcon,
                   c63_anousu         = new.c56_anousu,
                   c63_reduz          = new.c56_reduz,
                   c63_banco          = rContaBancaria.db89_db_bancos,
                   c63_agencia        = rContaBancaria.db89_codagencia,
                   c63_conta          = rContaBancaria.db83_conta,
                   c63_dvconta        = rContaBancaria.db83_dvconta,
                   c63_dvagencia      = rContaBancaria.db89_digito,
                   c63_identificador  = rContaBancaria.db83_identificador,
                   c63_codigooperacao = rContaBancaria.db83_codigooperacao,
                   c63_tipoconta      = rContaBancaria.db83_tipoconta 
             where c63_codcon         = old.c56_codcon
               and c63_anousu         = old.c56_anousu
               and c63_reduz          = old.c56_reduz;

          end loop;
          
        else 
        
          sSql := ' select *
                      from contabancaria
                           inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia
                     where contabancaria.db83_sequencial = '||new.c56_contabancaria;
          for rContaBancaria in execute sSql loop
           
            insert into conplanoconta ( c63_codcon         , 
                                        c63_anousu         , 
                                        c63_banco          , 
                                        c63_agencia        , 
                                        c63_conta          , 
                                        c63_dvconta        , 
                                        c63_dvagencia      , 
                                        c63_identificador  , 
                                        c63_codigooperacao , 
                                        c63_reduz ,
                                        c63_tipoconta )
                               values ( new.c56_codcon,                         
                                        new.c56_anousu,                         
                                        rContaBancaria.db89_db_bancos,          
                                        rContaBancaria.db89_codagencia,         
                                        rContaBancaria.db83_conta,              
                                        rContaBancaria.db83_dvconta,            
                                        rContaBancaria.db89_digito,             
                                        rContaBancaria.db83_identificador,      
                                        rContaBancaria.db83_codigooperacao,     
                                        new.c56_reduz,
                                        rContaBancaria.db83_tipoconta );
               
          end loop;
                                   
        end if;
  
      elsif sOperacao = 'DELETE' then 
    
  
        delete from conplanoconta 
              where c63_codcon = old.c56_codcon
                and c63_anousu = old.c56_anousu
                and c63_reduz = old.c56_reduz;

      end if;
    
    end if;
   
  end if; 


return old;

end;
$$
language 'plpgsql';

/**
 * Antigo nome da trigger, alterado para "tg_contabancaria_inc_alt_del"
 */
drop   trigger if exists tg_contabancaria_alt on contabancaria;

drop   trigger if exists tg_contabancaria_inc_alt_del on contabancaria;
create trigger tg_contabancaria_inc_alt_del after INSERT OR DELETE OR UPDATE on contabancaria for each row execute procedure fc_conplanoconta_inc_alt_del();

drop   trigger if exists tg_bancoagencia_alt  on bancoagencia;
create trigger tg_bancoagencia_alt  after update on bancoagencia for each row execute procedure fc_conplanoconta_inc_alt_del();


drop   trigger if exists tg_conplanocontabancaria_inc_alt_del on conplanocontabancaria;
create trigger tg_conplanocontabancaria_inc_alt_del after insert or update or delete on conplanocontabancaria for each row execute procedure fc_conplanoconta_inc_alt_del();



SQL_UP
        );
    }


    public function down()
    {
    }
}
