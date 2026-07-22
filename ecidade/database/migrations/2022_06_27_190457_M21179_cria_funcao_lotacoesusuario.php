<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21179CriaFuncaoLotacoesusuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

create type tp_lotacoesusuario  as ( lotacoes    integer[],
                                     estruturais integer[],
                                     matriculas  integer[] ) ;
                               
create or replace function fc_lotacoesusuario(integer,integer,integer,integer,varchar(1),boolean)
returns setof tp_lotacoesusuario
as
$$
declare                                                                                                                                                                                                 
                                                                                                                                                                                          
          iUsuario            alias for $1;                                     
          iInstituicao        alias for $2;                                     
          iAno                alias for $3;                                     
          iMes                alias for $4;                                     
          sTipoBusca          alias for $5;
          lRetornaMatriculas  alias for $6;
                              
          sMascaraLotacao     varchar(30);
          
          sSqlLotacoesUsuario text;
          sSqlMatriculas      text;
          
          rLotacoesUsuario    record;
          rMatriculas         record;
                                                                         
          lRaise              boolean default false;                         
          rtp_lotacoesusuario tp_lotacoesusuario%ROWTYPE;                  
  begin                                                                  

	 lRaise := cast(case when fc_getsession('db_debugon') is null then false else true end  as boolean);
	 
	 --
	 -- Tipo de BUsca:
	 -- S: por secretarias
	 -- Serao retornados os dados de acordo com o estrutural da lotação que o usuario possui acesso
	 -- Por exemplo, se o usuario possui permissao para a lotacao 99000 serao retornadas todas as lotacoes com 99***
	 --
	 -- U: por usuario
	 -- Serao retornadas somente as lotacoes ligadas ao usuario
	 --
     if sTipoBusca is null or trim(sTipoBusca) = '' then 
       sTipoBusca := 'U';
     end if;     
     
     if lRetornaMatriculas is null then 
       lRetornaMatriculas := false;
     end if;
     
     if lRaise is true then
         raise notice '';
         raise notice 'Parametros:';
         raise notice 'iUsuario: %',iUsuario;                                               
         raise notice 'iInstituicao: %',iInstituicao;                                            
         raise notice 'iAno: %',iAno;                                                   
         raise notice 'iMes: %',iMes;                                                   
         raise notice 'sTipoBusca: %',sTipoBusca;        
         raise notice 'lRetornaMatriculas: %',lRetornaMatriculas;
         raise notice '';
       end if;
	 
     if sTipoBusca = 'U' then
     
       sSqlLotacoesUsuario := 'select distinct r70_codigo, 
                                      r70_estrut 
                                 from db_usuariosrhlota 
                                      inner join rhlota on rhlota.r70_codigo = db_usuariosrhlota.rh157_lotacao
                                where rh157_usuario = '||iUsuario||' 
                                order by r70_codigo';
                              
     elseif sTipoBusca = 'S' then
     
       sSqlLotacoesUsuario := 'select distinct 
                                      r70_codigo, 
                                      r70_estrut 
                                 from rhlota 
                                      inner join (select distinct substr(r70_estrut, 1, 2) as secretaria 
                                                    from db_usuariosrhlota 
                                                         inner join rhlota on rhlota.r70_codigo = db_usuariosrhlota.rh157_lotacao
                                                   where rh157_usuario = '||iUsuario||') as secretarias on secretaria = substr(r70_estrut, 1, 2) 
                                order by r70_codigo';
     else 
       raise exception 'Parâmetro informado para o tipo de busca na chamada da função inválido. (U para buscar por usuário e S para buscar por secretaria)';
     end if;
     
     if lRaise is true then                                                   
       raise notice 'Sql para buscar as lotacoes: %',sSqlLotacoesUsuario;
     end if;     
     
     for rLotacoesUsuario in execute sSqlLotacoesUsuario loop
     
       if lRaise is true then                                                   
         raise notice '% - %',rLotacoesUsuario.r70_codigo, rLotacoesUsuario.r70_estrut;
       end if;       
       
       rtp_lotacoesusuario.lotacoes    := array_append(rtp_lotacoesusuario.lotacoes, rLotacoesUsuario.r70_codigo);
       rtp_lotacoesusuario.estruturais := array_append(rtp_lotacoesusuario.estruturais, cast(rLotacoesUsuario.r70_estrut as integer));
     
     end loop;
     
     if lRetornaMatriculas is true then 
     
       if lRaise is true then                                                   
         raise notice '%', rtp_lotacoesusuario.lotacoes;
       end if;       
       
       for rMatriculas in select rh02_regist
                            from rhpessoalmov 
                           where rh02_anousu = iAno
                             and rh02_mesusu = iMes
                             and rh02_instit = iInstituicao
                             and rh02_lota = ANY(rtp_lotacoesusuario.lotacoes) loop
       
         rtp_lotacoesusuario.matriculas := array_append(rtp_lotacoesusuario.matriculas, rMatriculas.rh02_regist);
       
       end loop;
     
     end if;
     
     return next rtp_lotacoesusuario;                                                                                          
     return;
     
 end;                                                                                                                      
$$
language 'plpgsql';

SQL;
        
        DB::connection()->getPdo()->exec($sql);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
drop function fc_lotacoesusuario;
drop type tp_lotacoesusuario;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
