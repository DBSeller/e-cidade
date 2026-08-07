<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22473AtualizacaoProcedureFcParcOrigemCompleto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        create or replace function fc_parc_origem_completo(integer) returns setof tp_origemparcelamento as
$$
declare

    iNumpre        alias   for $1;
    
    iSequencial    integer default 0;
    iParcel        integer default 0;
    iParcelOrigem  integer default 0;
    iNumpreOrigem  integer default 0;

    dDtlanc        date;

    lFinal         boolean default false;
    lRaise         boolean default false;
    lSair          boolean default false;

		tSql           text    default '';
    v_record       record;
    rParcelamentos record;
    sParcel        varchar;
    aParcelamentos integer[];
    iParcelamento  integer;      
    rtp_origemparcelamento tp_origemparcelamento%ROWTYPE;

begin

/*---------------------------------------------------------------------------*/

    lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

    select v07_parcel
      into iParcel
      from termo 
     where v07_numpre = iNumpre;

     if lRaise then
       raise notice 'Parcelamento do parametro : % ',iParcel;
     end if;   
    
     while not lSair
     loop

       select v08_parcel,
              v08_parcelorigem
         from termoreparc
        inner join termo
           on v08_parcel = v07_parcel
          and v07_situacao <> 2  
         into v_record
        where v08_parcelorigem = iParcel;

       if not found then
         lSair := true;
         continue;
       end if;

       if lRaise then        
         raise notice 'Buscando parcelamento mais atual - Parcel : % Origem -- %',v_record.v08_parcel,v_record.v08_parcelorigem;
       end if;

       iParcel := v_record.v08_parcel;

     end loop;

    select v07_parcel,
           v07_numpre,
           v07_dtlanc           
      into iParcel,
           iNumpreOrigem,
           dDtlanc
      from termo
     where v07_parcel = iParcel;

    if lRaise then        
      raise notice '2 -- Parcel Novo -- %',iParcel;
    end if;

    iSequencial := ( iSequencial + 1 );
    rtp_origemparcelamento.riSeq     := iSequencial;
  	rtp_origemparcelamento.riNumpre  := iNumpreOrigem;
  	rtp_origemparcelamento.riParcel  := iParcel;
  	rtp_origemparcelamento.rDtLanc   := dDtlanc;
    return next rtp_origemparcelamento;
    
  --
  --
  -- Buscamos todos os parcelamentos que nÃ£o possuem origem outro parcelamento, isto Ã©, que nÃ£o seja um reparcelamento  
  -- 
  --  
  for v_record in select v08_parcel,
                         v08_parcelorigem 
                    from termoreparc 
                   where v08_parcel = iParcel
                     and not exists (select 1 
                                       from termoreparc as reparc
                                      where reparc.v08_parcel = termoreparc.v08_parcelorigem)
  loop

    if lRaise then        
      raise notice '1 -- Parcel : % Origem -- %',v_record.v08_parcel,v_record.v08_parcelorigem;
    end if;

    iSequencial := (iSequencial+1);

    select v07_numpre,
           v07_dtlanc
      into iNumpreOrigem,
           dDtlanc
      from termo
     where v07_parcel = v_record.v08_parcelorigem and v07_situacao <> 2;

    --iParcel := iParcelOrigem;
    if found then
       rtp_origemparcelamento.riSeq     := iSequencial;
       rtp_origemparcelamento.riNumpre  := iNumpreOrigem;
       rtp_origemparcelamento.riParcel  := v_record.v08_parcelorigem;
       rtp_origemparcelamento.rDtLanc   := dDtlanc;
       return next rtp_origemparcelamento;
    end if;

   end loop;
   
   --
   --
   -- Buscamos todos os parcelamentos que tenha a sua origem outro parcelamento
   --
   --
    lSair := false;
    
    sParcel := iParcel;
    while not lSair 
    loop
      
      execute 'select array_agg(v08_parcelorigem) 
                 from termoreparc 
                where v08_parcel in ('||sParcel||')'
                into aParcelamentos;

      if aParcelamentos is null  then
        lSair := true;
        continue;
      end if;
      
      sParcel := array_to_string(aParcelamentos, ',');
      FOREACH iParcelamento in ARRAY aParcelamentos
      LOOP
        
        if lRaise then        
          raise notice '3 -- Parcel encontrado -- % %', iParcel, iParcelamento;
        end if;
      
        select v07_numpre,
              v07_dtlanc              
         into iNumpreOrigem,
              dDtlanc
         from termo
        where v07_parcel = iParcelamento;
      
        iSequencial := ( iSequencial + 1 );
        rtp_origemparcelamento.riSeq     := iSequencial;
  	    rtp_origemparcelamento.riNumpre  := iNumpreOrigem;
  	    rtp_origemparcelamento.riParcel  := iParcelamento;
  	    rtp_origemparcelamento.rDtLanc   := dDtlanc;
        return next rtp_origemparcelamento;
        
       END LOOP;  
      
	end loop;
	
	return ;
   
end;
$$ language 'plpgsql';
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
      DB::connection()->getPdo()->exec(<<<SQL
        
        create or replace function fc_parc_origem_completo(integer) returns setof tp_origemparcelamento as
        $$
        declare
        
            iNumpre        alias   for $1;
            
            iSequencial    integer default 0;
            iParcel        integer default 0;
            iParcelOrigem  integer default 0;
            iNumpreOrigem  integer default 0;
        
            dDtlanc        date;
        
            lFinal         boolean default false;
            lRaise         boolean default false;
            lSair          boolean default false;
        
                tSql           text    default '';
            v_record       record;
            rParcelamentos record;
        
            rtp_origemparcelamento tp_origemparcelamento%ROWTYPE;
        
        begin
        
        /*---------------------------------------------------------------------------*/
        
            lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
        
            select v07_parcel
              into iParcel
              from termo 
             where v07_numpre = iNumpre;
        
             if lRaise then
               raise notice 'Parcelamento do parametro : % ',iParcel;
             end if;   
            
             while not lSair
             loop
        
               select v08_parcel,
                      v08_parcelorigem
                 from termoreparc
                 into v_record
                where v08_parcelorigem = iParcel;
        
               if not found then
                 lSair := true;
                 continue;
               end if;
        
               if lRaise then        
                 raise notice 'Buscando parcelamento mais atual - Parcel : % Origem -- %',v_record.v08_parcel,v_record.v08_parcelorigem;
               end if;
        
               iParcel := v_record.v08_parcel;
        
             end loop;
        
            select v07_parcel,
                   v07_numpre,
                   v07_dtlanc           
              into iParcel,
                   iNumpreOrigem,
                   dDtlanc
              from termo
             where v07_parcel = iParcel;
        
            if lRaise then        
              raise notice '2 -- Parcel Novo -- %',iParcel;
            end if;
        
            iSequencial := ( iSequencial + 1 );
            rtp_origemparcelamento.riSeq     := iSequencial;
              rtp_origemparcelamento.riNumpre  := iNumpreOrigem;
              rtp_origemparcelamento.riParcel  := iParcel;
              rtp_origemparcelamento.rDtLanc   := dDtlanc;
            return next rtp_origemparcelamento;
            
          --
          --
          -- Buscamos todos os parcelamentos que n�o possuem origem outro parcelamento, isto �, que n�o seja um reparcelamento  
          -- 
          --  
          for v_record in select v08_parcel,
                                 v08_parcelorigem 
                            from termoreparc 
                           where v08_parcel = iParcel
                             and not exists (select 1 
                                               from termoreparc as reparc
                                              where reparc.v08_parcel = termoreparc.v08_parcelorigem)
          loop
        
            if lRaise then        
              raise notice '1 -- Parcel : % Origem -- %',v_record.v08_parcel,v_record.v08_parcelorigem;
            end if;
        
            iSequencial := (iSequencial+1);
        
            select v07_numpre,
                   v07_dtlanc
              into iNumpreOrigem,
                   dDtlanc
              from termo
             where v07_parcel = v_record.v08_parcelorigem and v07_situacao <> 2;
        
            --iParcel := iParcelOrigem;
            if found then
               rtp_origemparcelamento.riSeq     := iSequencial;
               rtp_origemparcelamento.riNumpre  := iNumpreOrigem;
               rtp_origemparcelamento.riParcel  := v_record.v08_parcelorigem;
               rtp_origemparcelamento.rDtLanc   := dDtlanc;
               return next rtp_origemparcelamento;
            end if;
        
           end loop;
           
           --
           --
           -- Buscamos todos os parcelamentos que tenha a sua origem outro parcelamento
           --
           --
            lSair := false;
            
            while not lSair 
            loop
        
              select v08_parcelorigem 
                into v_record
                from termoreparc 
               where v08_parcel = iParcel ;
        
              if not found then
                lSair := true;
                continue;
              end if;
            
              if lRaise then        
                raise notice '3 -- Parcel encontrado -- %',v_record.v08_parcelorigem;
              end if;
        
              select v07_numpre,
                     v07_dtlanc
                into iNumpreOrigem,
                     dDtlanc
                from termo
               where v07_parcel = v_record.v08_parcelorigem;
        
              iSequencial := ( iSequencial + 1 );
              rtp_origemparcelamento.riSeq     := iSequencial;
                rtp_origemparcelamento.riNumpre  := iNumpreOrigem;
                rtp_origemparcelamento.riParcel  := v_record.v08_parcelorigem;
                rtp_origemparcelamento.rDtLanc   := dDtlanc;
                
              iParcel := v_record.v08_parcelorigem;
        
              return next rtp_origemparcelamento;
        
            end loop;
            
            return ;
           
        end;
        $$ language 'plpgsql';
SQL
      );
    }
}