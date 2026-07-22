<?php

use Classes\PostgresMigration;

class M11400Verificacalculo extends PostgresMigration
{
    public function up()
    {
        $sql = "
            drop   function if exists fc_iptu_verificacalculo(integer, integer);
            drop   function if exists fc_iptu_verificacalculo(integer, integer, integer, integer);
            
            drop   type if exists iptu_verificacalc;
            drop   type if exists tp_iptu_verificacalc;
            create type tp_iptu_verificacalc as (rbErro    boolean,
                                                 riCodErro integer);
            
            /**
             * @deprecated
             * Removido campos parcelaini e parcelafim
             *
             * Utilizar fc_iptu_verificacalculo( iMatricula, iAnousu )
             */
            CREATE FUNCTION fc_iptu_verificacalculo(INTEGER, INTEGER, INTEGER, INTEGER)
              RETURNS TP_IPTU_VERIFICACALC
            LANGUAGE plpgsql
            AS $$
            declare
            
              iMatricula  alias for $1;
              iAnousu     alias for $2;
              iParcini ALIAS FOR $3; --Não utilizado no escopo
              iParcfim ALIAS FOR $4; --Não utilizado no escopo
            
              iNumpre           integer default 0;
              iParcArrecad      integer default 0;
              iTotParcArrecad   integer default 0;
              iParcArrepaga     integer default 0;
              iTotParcArrepaga  integer default 0;
              iParcArrecant     integer default 0;
              iTotParcArrecant  integer default 0;
              iDivold           integer default 0;
              iNumpreVerifica   integer default 0;
            
              lRaise            boolean default false;
              rtp_Retorno       tp_iptu_verificacalc%ROWTYPE;
              lAbatimento       boolean default false;
            
            begin
            
              lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
            
              rtp_Retorno.rbErro    := false;
              rtp_Retorno.riCodErro := 0::integer;
            
              /*
               * Verifica a situação do Calculo de IPTU
               *  Situações de bloqueio:
               *   1 - Importado para Divida
               *   2 - Totalmente Pago
               *   3 - Totalmente Cancelado
               */
              SELECT DISTINCT arrecad.k00_numpre,
                              arrecad.k00_numtot,
                              (SELECT coalesce(count(k00_numpar), 0) FROM arrecad WHERE k00_numpre = iptunump.j20_numpre),
                              (SELECT coalesce(count(k00_numpar), 0) FROM arrepaga WHERE k00_numpre = iptunump.j20_numpre),
                              (SELECT coalesce(count(k00_numpar), 0) FROM arrecant WHERE k00_numpre = iptunump.j20_numpre),
                              coalesce((SELECT divold.k10_numpre FROM divold WHERE k10_numpre = iptunump.j20_numpre LIMIT 1), 0)
                  INTO iNumpre,
                    iParcArrecad,
                    iTotParcArrecad,
                    iParcArrepaga,
                    iParcArrecant,
                    iDivold
              FROM iptubase
                     INNER JOIN iptunump ON iptunump.j20_matric = iptubase.j01_matric
                                              AND iptunump.j20_anousu = iAnousu
                     LEFT JOIN arrecad ON arrecad.k00_numpre = iptunump.j20_numpre
              WHERE iptubase.j01_matric = iMatricula
                AND j20_numpre IS NOT NULL;
            
              IF found
              THEN
            
                IF iDivold <> 0
                THEN -- Com Importação para a Dívida
            
                  rtp_Retorno.rbErro := TRUE;
                  rtp_Retorno.riCodErro := 32 :: INTEGER;
                ELSIF iNumpre IS NULL AND iParcArrepaga <> 0
                  THEN -- Em processo de Pagamento
            
                    rtp_Retorno.rbErro := FALSE;
                    rtp_Retorno.riCodErro := 27 :: INTEGER;
                ELSIF iParcArrecant <> 0 AND iParcArrepaga = 0
                  THEN -- Calculo Cancelado
            
                    rtp_Retorno.rbErro := TRUE;
                    rtp_Retorno.riCodErro := 34 :: INTEGER;
                END IF;
            
              END IF;
            
              /**
               * Verifica se existe Pagamento Parcial para o débito informado
               */
              SELECT j20_numpre
                  INTO iNumpreVerifica
              FROM iptunump
              WHERE j20_matric = iMatricula
                AND j20_anousu = iAnousu
              LIMIT 1;
            
              if found then
            
                select fc_verifica_abatimento( 1, (select j20_numpre
                                                   FROM iptunump
                                                   WHERE j20_matric = iMatricula
                                                     AND j20_anousu = iAnousu
                                                   LIMIT 1)) :: BOOLEAN INTO lAbatimento;
            
                if lAbatimento then
            
                  rtp_Retorno.rbErro    := true;
                  rtp_Retorno.riCodErro := 114::integer;
                end if;
            
              end if;
            
              perform fc_debug ( '' , lRaise);
              perform fc_debug (' <iptu_verificacalculo> ERRO        - ' || rtp_Retorno.rbErro   , lRaise);
              perform fc_debug (' <iptu_verificacalculo> CODIGO ERRO - ' || rtp_Retorno.riCodErro, lRaise);
              perform fc_debug ( '' , lRaise);
            
            
            
              create temp table if not exists tmpipturecalculo (
                matricula     integer,
                anousu        integer,
                valor         numeric,
                valor_isencao numeric,
                receita       INTEGER,
                historico     INTEGER
              );
            
              delete from tmpipturecalculo;
            
              if rtp_Retorno.riCodErro = 27 then
            
                insert into tmpipturecalculo
                SELECT iMatricula, iAnousu, j21_valor, abs(j21_valorisen), j21_receit, j21_codhis
                FROM (SELECT j21_valor,
                             CASE
                               WHEN iptucalhconf.j89_codhis IS NOT NULL THEN (SELECT CASE
                                                                                       WHEN sum(x.j21_valor) IS NOT NULL
                                                                                               THEN sum(x.j21_valor)
                                                                                       ELSE 0
                                                                                         END
                                                                              FROM iptucalv x
                                                                              WHERE x.j21_anousu = iptucalv.j21_anousu
                                                                                AND x.j21_matric = iptucalv.j21_matric
                                                                                AND x.j21_receit = iptucalv.j21_receit
                                                                                AND x.j21_codhis = iptucalhconf.j89_codhis)
                               ELSE 0
                                 END AS j21_valorisen,
                             j21_receit,
                             j21_codhis
                      FROM iptucalv
                             INNER JOIN iptucalh ON iptucalh.j17_codhis = j21_codhis
                             LEFT JOIN iptucalhconf ON iptucalhconf.j89_codhispai = j21_codhis
                             INNER JOIN tabrec ON tabrec.k02_codigo = j21_receit
                             LEFT JOIN iptucadtaxaexe ON iptucadtaxaexe.j08_tabrec = j21_receit
                                                           AND iptucadtaxaexe.j08_anousu = j21_anousu
                      WHERE j21_matric = iMatricula
                        AND j21_anousu = iAnousu
                        AND j17_codhis NOT IN (SELECT j89_codhis FROM iptucalhconf)
                      ORDER BY iptucalh.j17_codhis) AS x;
            
              end if;
            
              return rtp_Retorno;
            
            end;
            
            $$;
            
            /**
             * Wrapper para fc_iptu_verificacalculo original passando apenas matricula e anousu
             */
            create or replace function fc_iptu_verificacalculo(integer, integer) returns tp_iptu_verificacalc as
            $$
            declare
            
                iMatricula  alias for $1;
                iAnousu     alias for $2;
            
                rRetorno    record;
            
            begin
            
                for rRetorno in
                  select * from fc_iptu_verificacalculo(iMatricula, iAnousu, 0, 0)
                loop
                  return rRetorno;
                end loop;
            
            end;
            $$ language 'plpgsql';
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            drop   function if exists fc_iptu_verificacalculo(integer, integer);
            drop   function if exists fc_iptu_verificacalculo(integer, integer, integer, integer);
            
            drop   type if exists iptu_verificacalc;
            drop   type if exists tp_iptu_verificacalc;
            create type tp_iptu_verificacalc as (rbErro    boolean,
                                                 riCodErro integer);
            
            /**
             * @deprecated
             * Removido campos parcelaini e parcelafim
             *
             * Utilizar fc_iptu_verificacalculo( iMatricula, iAnousu )
             */
            create or replace function fc_iptu_verificacalculo(integer, integer, integer, integer) returns tp_iptu_verificacalc as
            $$
            declare
            
              iMatricula  alias for $1;
              iAnousu     alias for $2;
              iParcini    alias for $3; --Não utilizado no escopo
              iParcfim    alias for $4; --Não utilizado no escopo
            
              iNumpre           integer default 0;
              iParcArrecad      integer default 0;
              iTotParcArrecad   integer default 0;
              iParcArrepaga     integer default 0;
              iTotParcArrepaga  integer default 0;
              iParcArrecant     integer default 0;
              iTotParcArrecant  integer default 0;
              iDivold           integer default 0;
              iNumpreVerifica   integer default 0;
            
              lRaise            boolean default false;
              rtp_Retorno       tp_iptu_verificacalc%ROWTYPE;
              lAbatimento       boolean default false;
            
            begin
            
              lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
            
              rtp_Retorno.rbErro    := false;
              rtp_Retorno.riCodErro := 0::integer;
            
              /*
               * Verifica a situação do Calculo de IPTU
               *  Situações de bloqueio:
               *   1 - Importado para Divida
               *   2 - Totalmente Pago
               *   3 - Totalmente Cancelado
               */
               select distinct arrecad.k00_numpre,
                               arrecad.k00_numtot,
                               (select coalesce(count(k00_numpar),0) from arrecad  where k00_numpre = iptunump.j20_numpre),
                               (select coalesce(count(k00_numpar),0) from arrepaga where k00_numpre = iptunump.j20_numpre),
                               (select coalesce(count(k00_numpar),0) from arrecant where k00_numpre = iptunump.j20_numpre),
                               coalesce((select divold.k10_numpre from divold where k10_numpre = iptunump.j20_numpre limit 1),0)
                 into iNumpre,
                      iParcArrecad,
                      iTotParcArrecad,
                      iParcArrepaga,
                      iParcArrecant,
                      iDivold
                 from iptubase
                      inner join iptunump on iptunump.j20_matric = iptubase.j01_matric
                                         and iptunump.j20_anousu = iAnousu
                      left join  arrecad  on arrecad.k00_numpre  = iptunump.j20_numpre
               where iptubase.j01_matric = iMatricula
                 and j20_numpre is not null;
            
               if found then
            
                 if iDivold <> 0 then -- Com Importação para a Dívida
            
                    rtp_Retorno.rbErro    := true;
                    rtp_Retorno.riCodErro := 32::integer;
                 elsif iNumpre is null and iParcArrepaga <> 0 then -- Em processo de Pagamento
            
                    rtp_Retorno.rbErro    := false;
                    rtp_Retorno.riCodErro := 27::integer;
                 elsif iParcArrecant <> 0 and iParcArrepaga = 0 then -- Calculo Cancelado
            
                    rtp_Retorno.rbErro    := true;
                    rtp_Retorno.riCodErro := 34::integer;
                 end if;
            
               end if;
            
               /**
                * Verifica se existe Pagamento Parcial para o débito informado
                */
               select j20_numpre
                 into iNumpreVerifica
                 from iptunump
                where j20_matric = iMatricula
                  and j20_anousu = iAnousu
                 limit 1;
            
              if found then
            
                select fc_verifica_abatimento( 1, ( select j20_numpre
                                                      from iptunump
                                                     where j20_matric = iMatricula
                                                       and j20_anousu = iAnousu
                                                     limit 1 ))::boolean into lAbatimento;
            
                if lAbatimento then
            
                  rtp_Retorno.rbErro    := true;
                  rtp_Retorno.riCodErro := 114::integer;
                end if;
            
              end if;
            
              perform fc_debug ( '' , lRaise);
              perform fc_debug (' <iptu_verificacalculo> ERRO        - ' || rtp_Retorno.rbErro   , lRaise);
              perform fc_debug (' <iptu_verificacalculo> CODIGO ERRO - ' || rtp_Retorno.riCodErro, lRaise);
              perform fc_debug ( '' , lRaise);
            
            
            
              create temp table if not exists tmpipturecalculo (
                matricula     integer,
                anousu        integer,
                valor         numeric,
                valor_isencao numeric,
                receita       integer
              );
            
              delete from tmpipturecalculo;
            
              if rtp_Retorno.riCodErro = 27 then
            
                insert into tmpipturecalculo
                     select iMatricula,
                            iAnousu,
                            j21_valor,
                            abs(j21_valorisen),
                            j21_receit
                       from ( select j21_valor,
                                     case
                                       when iptucalhconf.j89_codhis is not null then
                                         (select case
                                                   when sum(x.j21_valor) is not null then
                                                     sum(x.j21_valor)
                                                   else 0
                                                 end
                                            from iptucalv x
                                           where x.j21_anousu = iptucalv.j21_anousu
                                             and x.j21_matric = iptucalv.j21_matric
                                             and x.j21_receit = iptucalv.j21_receit
                                             and x.j21_codhis = iptucalhconf.j89_codhis)
                                       else 0
                                     end as j21_valorisen,
                                     j21_receit
                                from iptucalv
                                     inner join iptucalh        on iptucalh.j17_codhis        = j21_codhis
                                     left  join iptucalhconf    on iptucalhconf.j89_codhispai = j21_codhis
                                     inner join tabrec          on tabrec.k02_codigo          = j21_receit
                                     left  join iptucadtaxaexe  on iptucadtaxaexe.j08_tabrec  = j21_receit
                                                               and iptucadtaxaexe.j08_anousu  = j21_anousu
                               where j21_matric = iMatricula
                                 and j21_anousu = iAnousu
                                 and j17_codhis not in (select j89_codhis from iptucalhconf)
                               order by iptucalh.j17_codhis) as x;
            
              end if;
            
              return rtp_Retorno;
            
            end;
            
            $$ language 'plpgsql';
            
            /**
             * Wrapper para fc_iptu_verificacalculo original passando apenas matricula e anousu
             */
            create or replace function fc_iptu_verificacalculo(integer, integer) returns tp_iptu_verificacalc as
            $$
            declare
            
                iMatricula  alias for $1;
                iAnousu     alias for $2;
            
                rRetorno    record;
            
            begin
            
                for rRetorno in
                  select * from fc_iptu_verificacalculo(iMatricula, iAnousu, 0, 0)
                loop
                  return rRetorno;
                end loop;
            
            end;
            $$ language 'plpgsql';
        ";
        $this->execute($sql);
    }
}
