<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19403CalculotaxalixoTaquari extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dicionarioUp();
        DB::statement(<<<SQL

create or replace function fc_iptu_taxacoletalixo_taquari_2021(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

  iReceita             alias for $1;
  iAliquota            alias for $2;
  iHistoricoCalculo    alias for $3;
  iPercentualIsencao   alias for $4;
  nValorParcela        alias for $5;
  lRaise               alias for $6;

  bPredial             boolean default false;

  iAnousu              integer default 0;
  iMatricula           integer default 0;
  iCaracteristica      integer default 0;

  nValorTaxa           numeric(15,2) default 0;
  nValorTaxaComIsencao numeric(15,2) default 0;

  rConstrucoes         record;

  begin

    lRaise := true;
    perform fc_debug('CALCULANDO TAXA DE COLETA DE LIXO ...',lRaise,false,false);
    perform fc_debug('<calculotaxa> receita - '||iReceita||' aliq - '||iAliquota||' historico - '||iHistoricoCalculo,lRaise,false,false);

    select anousu,
           matric
      into iAnousu,
           iMatricula
      from tmpdadostaxa;

    if not found then
      perform fc_debug('<calculotaxa> Tabela temporaria tmpdadostaxa, vazia',lRaise,false,false);
      return false;
    end if;

    select predial
      into bPredial
      from tmpdadosiptu;

    if bPredial is true then

      for rConstrucoes in select iptuconstr.j39_idcons,
                                 iptuconstr.j39_area,
                                 iptuconstr.j39_areap
                            from iptuconstr
                           where iptuconstr.j39_matric = iMatricula
                             and j39_dtdemo is null
                             and j39_idprinc = true loop

        perform fc_debug('<calculotaxa> Matricula: '||iMatricula||' receita - '||iReceita,lRaise,false,false);

        select j48_caract
          into iCaracteristica
          from carconstr
               inner join caracter on j48_caract = j31_codigo
               inner join cargrup  on j31_grupo = j32_grupo
         where j48_matric = iMatricula
           and j48_idcons = rConstrucoes.j39_idcons
           and j32_grupo  = 19;

        if not found then
          --@todo retornar erro para quando nao encontrar caracteristica
          perform fc_debug('<calculotaxa> Caracteristica do grupo 19 nao encontrada, Matricula: '||iMatricula,lRaise,false,false);
          return false;
        end if;

        -- busca o valor da taxa de lixo
        select j119_valor into nValorTaxa
        from carcaractervalor
        where j119_anousu = iAnousu
          and j119_caracteristica1 = iCaracteristica;

        if not found then
          --@todo retornar erro para quando nao encontrar valor da taxa
          perform fc_debug('<calculotaxa> Caracteristica '||iCaracteristica||' nao encontrada na tabela carcaractervalor, Matricula: '||iMatricula,lRaise,false,false);
          return false;
        end if;

        perform fc_debug('<calculotaxa> Caracteristica: '||iCaracteristica,lRaise);
        perform fc_debug('<calculotaxa> nValorTaxa: '||nValorTaxa,lRaise);

        insert into tmptaxapercisen values (iReceita,iPercentualIsencao,0,nValorTaxa);

        nValorTaxaComIsencao := nValorTaxa;

        if iPercentualIsencao > 0 then
          nValorTaxaComIsencao := round((nValorTaxa * (100 - iPercentualIsencao) / 100), 2);
        end if;

        perform fc_debug('<calculotaxa> nValorTaxaComIsencao: '||nValorTaxaComIsencao,lRaise);
        perform fc_debug('<calculotaxa> iPercentualIsencao: '||iPercentualIsencao,lRaise);

        insert into tmprecval values (iReceita,nValorTaxaComIsencao,iHistoricoCalculo,true);

      end loop;

    end if;

    return true;

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
        $this->dicionarioDown();
        DB::statement(<<<SQL
            drop function fc_iptu_taxacoletalixo_taquari_2021;
SQL
        );

    }

    private function dicionarioUp()
    {
        DB::statement("insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 208 ,'fc_iptu_taxacoletalixo_taquari_2021' ,'iptu_taxacoletalixo_taquari_2021.sql' ,'Função de cálculo da taxa de lixo',
        'create or replace function fc_iptu_taxacoletalixo_taquari_2021(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
        $$
        declare
        
          iReceita             alias for $1;
          iAliquota            alias for $2;
          iHistoricoCalculo    alias for $3;
          iPercentualIsencao   alias for $4;
          nValorParcela        alias for $5;
          lRaise               alias for $6;
        
          bPredial             boolean default false;
        
          iAnousu              integer default 0;
          iMatricula           integer default 0;
          iCaracteristica      integer default 0;
        
          nValorTaxa           numeric(15,2) default 0;
          nValorTaxaComIsencao numeric(15,2) default 0;
        
          rConstrucoes         record;
        
          begin
        
            lRaise := true;
            perform fc_debug(\'CALCULANDO TAXA DE COLETA DE LIXO ...\',lRaise,false,false);
            perform fc_debug(\'<calculotaxa> receita - \'||iReceita||\' aliq - \'||iAliquota||\' historico - \'||iHistoricoCalculo,lRaise,false,false);
        
            select anousu,
                   matric
              into iAnousu,
                   iMatricula
              from tmpdadostaxa;
        
            if not found then
              perform fc_debug(\'<calculotaxa> Tabela temporaria tmpdadostaxa, vazia\',lRaise,false,false);
              return false;
            end if;
        
            select predial
              into bPredial
              from tmpdadosiptu;
        
            if bPredial is true then
        
              for rConstrucoes in select iptuconstr.j39_idcons,
                                         iptuconstr.j39_area,
                                         iptuconstr.j39_areap
                                    from iptuconstr
                                   where iptuconstr.j39_matric = iMatricula
                                     and j39_dtdemo is null
                                     and j39_idprinc = true loop
        
                perform fc_debug(\'<calculotaxa> Matricula: \'||iMatricula||\' receita - \'||iReceita,lRaise,false,false);
        
                select j48_caract
                  into iCaracteristica
                  from carconstr
                       inner join caracter on j48_caract = j31_codigo
                       inner join cargrup  on j31_grupo = j32_grupo
                 where j48_matric = iMatricula
                   and j48_idcons = rConstrucoes.j39_idcons
                   and j32_grupo  = 19;
        
                if not found then
                  --@todo retornar erro para quando nao encontrar caracteristica
                  perform fc_debug(\'<calculotaxa> Caracteristica do grupo 19 nao encontrada, Matricula: \'||iMatricula,lRaise,false,false);
                  return false;
                end if;
        
                -- busca o valor da taxa de lixo
                select j119_valor into nValorTaxa
                from carcaractervalor
                where j119_anousu = iAnousu
                  and j119_caracteristica1 = iCaracteristica;
        
                if not found then
                  --@todo retornar erro para quando nao encontrar valor da taxa
                  perform fc_debug(\'<calculotaxa> Caracteristica \'||iCaracteristica||\' nao encontrada na tabela carcaractervalor, Matricula: \'||iMatricula,lRaise,false,false);
                  return false;
                end if;
        
                perform fc_debug(\'<calculotaxa> Caracteristica: \'||iCaracteristica,lRaise);
                perform fc_debug(\'<calculotaxa> nValorTaxa: \'||nValorTaxa,lRaise);
        
                insert into tmptaxapercisen values (iReceita,iPercentualIsencao,0,nValorTaxa);
        
                nValorTaxaComIsencao := nValorTaxa;
        
                if iPercentualIsencao > 0 then
                  nValorTaxaComIsencao := round((nValorTaxa * (100 - iPercentualIsencao) / 100), 2);
                end if;
        
                perform fc_debug(\'<calculotaxa> nValorTaxaComIsencao: \'||nValorTaxaComIsencao,lRaise);
                perform fc_debug(\'<calculotaxa> iPercentualIsencao: \'||iPercentualIsencao,lRaise);
        
                insert into tmprecval values (iReceita,nValorTaxaComIsencao,iHistoricoCalculo,true);
        
              end loop;
        
            end if;
        
            return true;
        
          end;
        $$ language \'plpgsql\';',
        '0' );");
        DB::statement("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1136 ,208 ,1 ,'iReceita' ,'int4' ,0 ,0 ,'' ,'RECEITA' );");
        DB::statement("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1137 ,208 ,2 ,'iAliquota' ,'numeric' ,0 ,0 ,'' ,'ALIQUOTA' );");
        DB::statement("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1138 ,208 ,3 ,'iHistCalc' ,'int4' ,0 ,0 ,'' ,'HISTÓRICO DE CÁLCULO' );");
        DB::statement("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1139 ,208 ,4 ,'iPercIsen' ,'numeric' ,0 ,0 ,'' ,'PERCENTUAL DE ISENÇÃO' );");
        DB::statement("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1140 ,208 ,5 ,'nValpar' ,'numeric' ,0 ,0 ,'' ,'VALOR PARCELA' );");
        DB::statement("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1141 ,208 ,6 ,'bRaise' ,'bool' ,0 ,0 ,'' ,'VARIÁVEL DE ERRO' );");
    }

    private function dicionarioDown()
    {
        DB::statement("delete from db_sysfuncoesparam where db42_funcao = 208");
        DB::statement("delete from db_sysfuncoes where codfuncao = 208");
    }

}
