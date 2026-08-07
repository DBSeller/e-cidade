<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22547AtualizacaoProcedureFcIptuCalculavvtCampinagrande2022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::connection()->getPdo()->exec(<<<SQL
       create or replace function fc_iptu_calculavvt_campinagrande_2022(integer,integer,integer,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$

declare

  iIdbql                   alias for $1;
  iMatricula               alias for $2;
  iAnousu                  alias for $3;
  nFracao                  alias for $4;
  lMostrademo              alias for $5;
  lRaise                   alias for $6;
  
  lPredial                 boolean default false;
  --iRegra                   integer;
  nVm2t                    numeric default 0;
  nVm2tCalc                numeric default 0;
  nAreaLoteCorrigi         numeric default 0;
  nAreaTerreno             numeric default 0;
  nValor                   numeric default 0;
  nTestada                 numeric default 0;
  nFatorReducao            numeric default 0;
  nFatorSituacao           numeric default 0;
  nFatorTopografia         numeric default 0;
  nFatorPedologia          numeric default 0;
  
  rtp_iptu_calculavvt      tp_iptu_calculavvt%ROWTYPE;
  
begin
 
  rtp_iptu_calculavvt.rnAreaTotalC := 0;
  rtp_iptu_calculavvt.rnArea       := 0;
  rtp_iptu_calculavvt.rnTestada    := 0;
  rtp_iptu_calculavvt.riCoderro    := 0;
  rtp_iptu_calculavvt.rtDemo       := '';
  rtp_iptu_calculavvt.rtMsgerro    := '';
  rtp_iptu_calculavvt.rtErro       := '';
  rtp_iptu_calculavvt.rbErro       := 'f';

  perform fc_debug('< calculo vvt > INICIANDO CALCULO DO VALOR VENAL TERRITORIAL...', lRaise);

  select case 
         when j39_matric is not null
         then 't'::boolean
         else 
           'f'::boolean
         end 
    into lPredial
    from iptubase 
    left join iptuconstr
      on j39_matric = j01_matric
     and j39_dtdemo is null 
   where j01_matric = iMatricula;

  if not found
  then
    
    rtp_iptu_calculavvt.rbErro    := 't';
    rtp_iptu_calculavvt.riCoderro := 9;
    rtp_iptu_calculavvt.rtErro    := ' DADOS DA MATRICULA NAO ENCONTRADO';
   
    return rtp_iptu_calculavvt;
    
  end if;  

  /* verifica a area do lote */
  select case when j34_area = 0
              then j34_areal
              else j34_area
         end
    into nAreaTerreno
    from lote
   where j34_idbql = iIdbql;

  if nAreaTerreno is null or nAreaTerreno = 0 then
      
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 3;
      rtp_iptu_calculavvt.rtErro    := 'AREA DO LOTE NAO ENCONTRADA OU ZERADA';
   
      return rtp_iptu_calculavvt;
  end if;
    
  nAreaLoteCorrigi := ( nAreaTerreno * ( nFracao / 100::numeric ) );

  perform fc_debug('< calculo vvt > Area do terreno: '||nAreaTerreno, lRaise);

  /* busca valor do m2 do terreno */
    
  select coalesce(j36_testad,0), j81_valorterreno
    into nTestada, nVm2t
    from testada 
   inner join testpri   
      on j49_idbql  = j36_idbql
     and j49_face   = j36_face
     and j49_codigo = j36_codigo
   inner join facevalor 
      on j81_face   = j36_face
     and j81_anousu = iAnousu
    where j36_idbql = iIdbql;

    if not found then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 25;
      rtp_iptu_calculavvt.rtMsgErro := 'VERIFIQUE O VALOR DO M2 DO TERRENO PARA A FACE';

      return rtp_iptu_calculavvt;
    end if;
    

    perform fc_debug('< calculo vvt > Valor do m2 do terreno: '||nVm2t, lRaise);

    /*
      ImÛveis n„o edificados atÈ 250 m≤
      ImÛveis n„o edificados acima de 250 m≤
      ImÛveis edificados (apartamento) (caracter 131 e 140)
      ImÛveis edificados (demais tipos de constru√ß√µes)
    */

    case 
      when lPredial is false 
       and nAreaLoteCorrigi <= 250
      then
        case iAnousu 
        when 2017 then nFatorReducao := 0.1;
        when 2018 then nFatorReducao := 0.1;
        when 2019 then nFatorReducao := 0.15;
        when 2020 then nFatorReducao := 0.2;
        when 2021 then nFatorReducao := 0.25;
        else nFatorReducao := 0.3;
        end case;
      when lPredial is false 
       and nAreaLoteCorrigi > 250
      then
        
        case iAnousu 
        when 2017 then nFatorReducao := 0.1;
        when 2018 then nFatorReducao := 0.2;
        when 2019 then nFatorReducao := 0.25;
        when 2020 then nFatorReducao := 0.3;
        when 2021 then nFatorReducao := 0.35;
        else nFatorReducao := 0.4;
        end case;
        
      when lPredial is true 
       and exists(select 1
                    from iptuconstr
                   inner join carconstr 
                     on j48_matric = j39_matric
                    and j48_idcons = j39_idcons
                    and j39_dtdemo is null
                    and j39_idprinc is true
                  where j39_matric = iMatricula
                    and j48_caract in (4613, 4614))
      then 
        
        nFatorReducao := 1;      
      else
        case iAnousu 
        when 2017 then nFatorReducao := 0.1;
        when 2018 then nFatorReducao := 0.15;
        when 2019 then nFatorReducao := 0.2;
        when 2020 then nFatorReducao := 0.25;
        when 2021 then nFatorReducao := 0.3;
        else nFatorReducao := 0.35;
        end case;
    end case;
    
    if not found or nFatorReducao > 1
    then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 104;
      rtp_iptu_calculavvt.rtErro    := '';
     
      return rtp_iptu_calculavvt;
    end if;   
    
    nVm2tCalc     := nVm2t * nFatorReducao;
    
    /* busca fator pedologia */
    select j74_fator
      into nFatorPedologia
    from lote inner join carlote   on j35_idbql  = j34_idbql
              inner join caracter  on j31_codigo = j35_caract
              inner join carfator  on j74_anousu = iAnousu
                                  and j74_caract = j35_caract
    where j34_idbql = iIdbql
      and j31_grupo = 9;

    if nFatorPedologia = 0 or nFatorPedologia is null then
  
       rtp_iptu_calculavvt.rbErro    := 't';
       rtp_iptu_calculavvt.riCoderro := 24;
       rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 9 OU SEM VALOR PARA O ANO DE '||iAnousu;  
       rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 9 OU SEM VALOR PARA O ANO DE '||iAnousu;
       return rtp_iptu_calculavvt;
    end if;

    /* busca fator Situacao */
    select j74_fator
      into nFatorSituacao
      from lote 
     inner join carlote   
        on j35_idbql  = j34_idbql
     inner join caracter  
        on j31_codigo = j35_caract
     inner join carfator
        on j74_anousu = iAnousu
       and j74_caract = j35_caract
     where j34_idbql = iIdbql
       and j31_grupo = 13;

    if nFatorSituacao = 0 or nFatorSituacao is null 
    then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 24;
      rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 13 OU SEM VALOR PARA O ANO DE '||iAnousu;
      rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 13 OU SEM VALOR PARA O ANO DE '||iAnousu;
      return rtp_iptu_calculavvt;
    end if;

  /* busca fator topografia */

  select j74_fator
    into nFatorTopografia
    from lote 
   inner join carlote   
      on j35_idbql  = j34_idbql
   inner join caracter  
      on j31_codigo = j35_caract
   inner join carfator  
      on j74_anousu = iAnousu
     and j74_caract = j35_caract
   where j34_idbql = iIdbql
     and j31_grupo = 16;

   if nFatorTopografia = 0 or nFatorTopografia is null then
     
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 24;
      rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 16 OU SEM VALOR PARA O ANO DE '||iAnousu;
      rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 16 OU SEM VALOR PARA O ANO DE '||iAnousu;
   
      return rtp_iptu_calculavvt;
   end if;

   
	 nValor := round( nAreaLoteCorrigi * (nVm2t*nFatorReducao)::numeric * nFatorPedologia * nFatorSituacao * nFatorTopografia, 2);
      
   if nValor <= 0 or nValor is null then
   
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 113;
      rtp_iptu_calculavvt.rtErro    := '';
   
      return rtp_iptu_calculavvt;
   end if;

    /* formula de calculo do terreno */
   perform fc_debug('< calculo vvt > Formula: VVT = (nAreaLoteCorrigi * nVm2t * nFatorPedologia * nFatorSituacao * nFatorTopografia)', lRaise);
  --  raise notice '< calculo vvt > Formula: VVT = (% * % * % * % * %)', nAreaLoteCorrigi, nVm2tCalc, nFatorPedologia, nFatorSituacao, nFatorTopografia;
   perform fc_debug('', lRaise);
   perform fc_debug('< calculo vvt > Formula: '||nValor||' = ('||nAreaLoteCorrigi||' * ('||nVm2t||' * '||nFatorReducao||') * '||nFatorPedologia||' * '||nFatorSituacao||' * '||nFatorTopografia||')', lRaise, lRaise, lRaise);
    
   rtp_iptu_calculavvt.rnArea       := nAreaTerreno;
   rtp_iptu_calculavvt.rnVvt        := nValor;
   rtp_iptu_calculavvt.rnAreaTotalC := nAreaLoteCorrigi;
   rtp_iptu_calculavvt.rnTestada    := nTestada;
   rtp_iptu_calculavvt.rtDemo       := '';
   rtp_iptu_calculavvt.rtMsgerro    := '';
   rtp_iptu_calculavvt.rbErro       := 'f';

   update tmpdadosiptu 
      set vvt = rtp_iptu_calculavvt.rnVvt, 
          vm2t= nVm2t, 
          areat=nAreaLoteCorrigi;

   return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';
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
        create or replace function fc_iptu_calculavvt_campinagrande_2022(integer,integer,integer,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$

declare

  iIdbql                   alias for $1;
  iMatricula               alias for $2;
  iAnousu                  alias for $3;
  nFracao                  alias for $4;
  lMostrademo              alias for $5;
  lRaise                   alias for $6;
  
  lPredial                 boolean default false;
  --iRegra                   integer;
  nVm2t                    numeric default 0;
  nVm2tCalc                numeric default 0;
  nAreaLoteCorrigi         numeric default 0;
  nAreaTerreno             numeric default 0;
  nValor                   numeric default 0;
  nTestada                 numeric default 0;
  nFatorReducao            numeric default 0;
  nFatorSituacao           numeric default 0;
  nFatorTopografia         numeric default 0;
  nFatorPedologia          numeric default 0;
  
  rtp_iptu_calculavvt      tp_iptu_calculavvt%ROWTYPE;
  
begin
 
  rtp_iptu_calculavvt.rnAreaTotalC := 0;
  rtp_iptu_calculavvt.rnArea       := 0;
  rtp_iptu_calculavvt.rnTestada    := 0;
  rtp_iptu_calculavvt.riCoderro    := 0;
  rtp_iptu_calculavvt.rtDemo       := '';
  rtp_iptu_calculavvt.rtMsgerro    := '';
  rtp_iptu_calculavvt.rtErro       := '';
  rtp_iptu_calculavvt.rbErro       := 'f';

  perform fc_debug('< calculo vvt > INICIANDO CALCULO DO VALOR VENAL TERRITORIAL...', lRaise);

  select case 
         when j39_matric is not null
         then 't'::boolean
         else 
           'f'::boolean
         end 
    into lPredial
    from iptubase 
    left join iptuconstr
      on j39_matric = j01_matric
     and j39_dtdemo is null 
   where j01_matric = iMatricula;

  if not found
  then
    
    rtp_iptu_calculavvt.rbErro    := 't';
    rtp_iptu_calculavvt.riCoderro := 9;
    rtp_iptu_calculavvt.rtErro    := ' DADOS DA MATRICULA NAO ENCONTRADO';
   
    return rtp_iptu_calculavvt;
    
  end if;  

  /* verifica a area do lote */
  select case when j34_area = 0
              then j34_areal
              else j34_area
         end
    into nAreaTerreno
    from lote
   where j34_idbql = iIdbql;

  if nAreaTerreno is null or nAreaTerreno = 0 then
      
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 3;
      rtp_iptu_calculavvt.rtErro    := 'AREA DO LOTE NAO ENCONTRADA OU ZERADA';
   
      return rtp_iptu_calculavvt;
  end if;
    
  nAreaLoteCorrigi := ( nAreaTerreno * ( nFracao / 100::numeric ) );

  perform fc_debug('< calculo vvt > Area do terreno: '||nAreaTerreno, lRaise);

  /* busca valor do m2 do terreno */
    
  select coalesce(j36_testad,0), j81_valorterreno
    into nTestada, nVm2t
    from testada 
   inner join testpri   
      on j49_idbql  = j36_idbql
     and j49_face   = j36_face
     and j49_codigo = j36_codigo
   inner join facevalor 
      on j81_face   = j36_face
     and j81_anousu = iAnousu
    where j36_idbql = iIdbql;

    if not found then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 25;
      rtp_iptu_calculavvt.rtMsgErro := 'VERIFIQUE O VALOR DO M2 DO TERRENO PARA A FACE';

      return rtp_iptu_calculavvt;
    end if;
    

    perform fc_debug('< calculo vvt > Valor do m2 do terreno: '||nVm2t, lRaise);

    /*
      Im√≥veis n√£o edificados at√© 250 m¬≤
      Im√≥veis n√£o edificados acima de 250 m¬≤
      Im√≥veis edificados (apartamento) (caracter 131 e 140)
      Im√≥veis edificados (demais tipos de constru√ß√µes)
    */

    case 
      when lPredial is false 
       and nAreaLoteCorrigi <= 250
      then

        nFatorReducao := 0.3;
      when lPredial is false 
       and nAreaLoteCorrigi > 250
      then

        nFatorReducao := 0.4;
      when lPredial is true 
       and exists(select 1
                    from iptuconstr
                   inner join carconstr 
                     on j48_matric = j39_matric
                    and j48_idcons = j39_idcons
                    and j39_dtdemo is null
                    and j39_idprinc is true
                  where j39_matric = iMatricula
                    and j48_caract in (4613, 4614))
      then 
        
        nFatorReducao := 1;
      else
        
        nFatorReducao := 0.35;
    end case;
    
    if not found or nFatorReducao > 1
    then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 104;
      rtp_iptu_calculavvt.rtErro    := '';
     
      return rtp_iptu_calculavvt;
    end if;   
    
    nVm2tCalc     := nVm2t * nFatorReducao;
    
    /* busca fator pedologia */
    select j74_fator
      into nFatorPedologia
    from lote inner join carlote   on j35_idbql  = j34_idbql
              inner join caracter  on j31_codigo = j35_caract
              inner join carfator  on j74_anousu = iAnousu
                                  and j74_caract = j35_caract
    where j34_idbql = iIdbql
      and j31_grupo = 9;

    if nFatorPedologia = 0 or nFatorPedologia is null then
  
       rtp_iptu_calculavvt.rbErro    := 't';
       rtp_iptu_calculavvt.riCoderro := 24;
       rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 9 OU SEM VALOR PARA O ANO DE '||iAnousu;  
       rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 9 OU SEM VALOR PARA O ANO DE '||iAnousu;
       return rtp_iptu_calculavvt;
    end if;

    /* busca fator Situacao */
    select j74_fator
      into nFatorSituacao
      from lote 
     inner join carlote   
        on j35_idbql  = j34_idbql
     inner join caracter  
        on j31_codigo = j35_caract
     inner join carfator  
        on j74_anousu = iAnousu
       and j74_caract = j35_caract
     where j34_idbql = iIdbql
       and j31_grupo = 13;

    if nFatorSituacao = 0 or nFatorSituacao is null 
    then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 24;
      rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 13 OU SEM VALOR PARA O ANO DE '||iAnousu;
      rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 13 OU SEM VALOR PARA O ANO DE '||iAnousu;
      return rtp_iptu_calculavvt;
    end if;

  /* busca fator topografia */

  select j74_fator
    into nFatorTopografia
    from lote 
   inner join carlote   
      on j35_idbql  = j34_idbql
   inner join caracter  
      on j31_codigo = j35_caract
   inner join carfator  
      on j74_anousu = iAnousu
     and j74_caract = j35_caract
   where j34_idbql = iIdbql
     and j31_grupo = 16;

   if nFatorTopografia = 0 or nFatorTopografia is null then
     
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 24;
      rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 16 OU SEM VALOR PARA O ANO DE '||iAnousu;
      rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 16 OU SEM VALOR PARA O ANO DE '||iAnousu;
   
      return rtp_iptu_calculavvt;
   end if;

   
	 nValor := round( nAreaLoteCorrigi * (nVm2t*nFatorReducao)::numeric * nFatorPedologia * nFatorSituacao * nFatorTopografia, 2);
      
   if nValor <= 0 or nValor is null then
   
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 113;
      rtp_iptu_calculavvt.rtErro    := '';
   
      return rtp_iptu_calculavvt;
   end if;

    /* formula de calculo do terreno */
   perform fc_debug('< calculo vvt > Formula: VVT = (nAreaLoteCorrigi * nVm2t * nFatorPedologia * nFatorSituacao * nFatorTopografia)', lRaise);
  --  raise notice '< calculo vvt > Formula: VVT = (% * % * % * % * %)', nAreaLoteCorrigi, nVm2tCalc, nFatorPedologia, nFatorSituacao, nFatorTopografia;
   perform fc_debug('', lRaise);
   perform fc_debug('< calculo vvt > Formula: '||nValor||' = ('||nAreaLoteCorrigi||' * ('||nVm2t||' * '||nFatorReducao||') * '||nFatorPedologia||' * '||nFatorSituacao||' * '||nFatorTopografia||')', lRaise, lRaise, lRaise);
    
   rtp_iptu_calculavvt.rnArea       := nAreaTerreno;
   rtp_iptu_calculavvt.rnVvt        := nValor;
   rtp_iptu_calculavvt.rnAreaTotalC := nAreaLoteCorrigi;
   rtp_iptu_calculavvt.rnTestada    := nTestada;
   rtp_iptu_calculavvt.rtDemo       := '';
   rtp_iptu_calculavvt.rtMsgerro    := '';
   rtp_iptu_calculavvt.rbErro       := 'f';

   update tmpdadosiptu 
      set vvt = rtp_iptu_calculavvt.rnVvt, 
          vm2t= nVm2t, 
          areat=nAreaLoteCorrigi;

   return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';
SQL
    );  
    }
}
