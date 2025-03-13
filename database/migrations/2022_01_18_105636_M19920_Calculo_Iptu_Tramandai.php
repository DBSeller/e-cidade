<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M19920CalculoIptuTramandai extends Migration
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
            CREATE OR REPLACE FUNCTION fc_iptu_taxalixo_tramandai_2022(
              integer,
              numeric,
              integer,
              numeric,
              numeric,
              boolean)
                RETURNS boolean
                LANGUAGE 'plpgsql'
            
                COST 100
                VOLATILE 
            AS $$
            
            declare
            
              iReceita                 alias for $1;
              iAliquota                alias for $2;
              iHistCalc                alias for $3;
              iPercIsen                alias for $4;
              nValpar                  alias for $5;
              lRaise                   alias for $6;
            
              nValorTotalTaxa          numeric(15,2) default 0;
            
              debug_caracteristicas    text;
            
              rDadosTaxa               record;
            
              pode_calcular_taxa_lixo  boolean;
            
            begin
            
              perform fc_debug( ' <iptu_taxalixo> CALCULANDO TAXA DE COLETA DE LIXO - PARAMETROS RECEBIDOS ...',lRaise);
              perform fc_debug( '',lRaise);
              perform fc_debug( ' <iptu_taxalixo> receita            - ' || iReceita  ,       lRaise);
              perform fc_debug( ' <iptu_taxalixo> aliq               - ' || iAliquota ,       lRaise);
              perform fc_debug( ' <iptu_taxalixo> historico          - ' || iHistCalc ,       lRaise);
              perform fc_delsession(' <iptu_taxalixo> ERRO_CALCULO_TAXA');
              perform fc_debug( ' <iptu_taxalixo> BUSCA INFORMACOES DA TABELA TMPDADOSTAXA ...',lRaise);
            
              select *
                into rDadosTaxa
                from tmpdadostaxa;
            
              perform fc_debug( ' <iptu_taxalixo> BUSCA O VALOR DA TAXA DE LIXO NA TABELA CARVALOR ...',lRaise);
            
              /* conforme resolucao CSR 01/2021 da AGESAN, sera cobrado taxa de lixo */
              if rDadosTaxa.totareaconst > 0 then
                 perform fc_debug( ' <iptu_taxalixo> CALCULA TAXA DE LIXO PREDIAL'||rDadosTaxa.matric, lRaise);
            
                 select j71_valor,       array_to_string(array_agg(distinct j48_caract::text),',')
                   into nValorTotalTaxa, debug_caracteristicas
                   from carconstr inner join caracter on j31_codigo = j48_caract
                                  inner join carvalor on j71_caract = j48_caract
                                                     and j71_anousu = rDadosTaxa.anousu
                  where j48_matric = rDadosTaxa.matric
                    and j31_grupo  = 62
                  group by j71_valor
                  order by j71_valor desc limit 1;
            
                 if nValorTotalTaxa is null or nValorTotalTaxa = 0 or not found then
                    perform fc_debug(' <iptu_taxalixo> Caracteristica da tarifa de limpeza nao encontrada ou valor nao encontrado na carvalor.', lRaise);
                    return false;
                 end if;
            
                 perform fc_debug( ' <iptu_taxalixo> Caracteristicas encontradas para o Grupo 62: '||debug_caracteristicas, lRaise);
              else
                 perform fc_debug( ' <iptu_taxalixo> CALCULA TAXA DE LIXO TERRITORIAL'||rDadosTaxa.matric, lRaise);
                 nValorTotalTaxa = nValpar::numeric;
            
                 if nValorTotalTaxa is null or nValorTotalTaxa = 0 then
                    perform fc_debug(' <iptu_taxalixo> Valor da tarifa de limpeza nao encontrado ou zerado. Verifique o cadastro de taxas.', lRaise);
                    return false;
                 end if;
            
              end if;
            
              perform fc_debug( ' <iptu_taxalixo> CALCULA TAXA DE LIXO:', lRaise);
              perform fc_debug( '',lRaise);
              perform fc_debug( ' <iptu_taxalixo> Valor da Taxa de Lixo: ' ||nValorTotalTaxa, lRaise);
            
              insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValorTotalTaxa);
              insert into tmprecval       values (iReceita, nValorTotalTaxa, iHistCalc, true);
            
              return true;
            
            end;
            $$;

SQL
        );

    }

    private function dicionarioUp()
    {

    DB::statement("update db_sysfuncoes set corpofuncao = 
                    'CREATE OR REPLACE FUNCTION fc_iptu_taxalixo_tramandai_2022(
                        integer,
                        numeric,
                        integer,
                        numeric,
                        numeric,
                        boolean)
                        RETURNS boolean
                        LANGUAGE \'plpgsql\'
                        
                        COST 100
                        VOLATILE 
                        AS $$
                        
                        declare
                        
                        iReceita                 alias for $1;
                        iAliquota                alias for $2;
                        iHistCalc                alias for $3;
                        iPercIsen                alias for $4;
                        nValpar                  alias for $5;
                        lRaise                   alias for $6;
                        
                        nValorTotalTaxa          numeric(15,2) default 0;
                        
                        debug_caracteristicas    text;
                        
                        rDadosTaxa               record;
                        
                        pode_calcular_taxa_lixo  boolean;
                        
                        begin
                        
                        perform fc_debug( \' <iptu_taxalixo> CALCULANDO TAXA DE COLETA DE LIXO - PARAMETROS RECEBIDOS ...\',lRaise);
                        perform fc_debug( \'\',lRaise);
                        perform fc_debug( \' <iptu_taxalixo> receita            - \' || iReceita  ,       lRaise);
                        perform fc_debug( \' <iptu_taxalixo> aliq               - \' || iAliquota ,       lRaise);
                        perform fc_debug( \' <iptu_taxalixo> historico          - \' || iHistCalc ,       lRaise);
                        perform fc_delsession(\' <iptu_taxalixo> ERRO_CALCULO_TAXA\');
                        perform fc_debug( \' <iptu_taxalixo> BUSCA INFORMACOES DA TABELA TMPDADOSTAXA ...\',lRaise);
                        
                        select *
                        into rDadosTaxa
                        from tmpdadostaxa;
                        
                        perform fc_debug( \' <iptu_taxalixo> BUSCA O VALOR DA TAXA DE LIXO NA TABELA CARVALOR ...\',lRaise);
                        
                        /* conforme resolucao CSR 01/2021 da AGESAN, sera cobrado taxa de lixo */
                        if rDadosTaxa.totareaconst > 0 then
                           perform fc_debug( \' <iptu_taxalixo> CALCULA TAXA DE LIXO PREDIAL\'||rDadosTaxa.matric, lRaise);
                           
                           select j71_valor,       array_to_string(array_agg(distinct j48_caract::text),\',\')
                           into nValorTotalTaxa, debug_caracteristicas
                           from carconstr inner join caracter on j31_codigo = j48_caract
                                  inner join carvalor on j71_caract = j48_caract
                                                     and j71_anousu = rDadosTaxa.anousu
                           where j48_matric = rDadosTaxa.matric
                           and j31_grupo  = 62
                           group by j71_valor
                           order by j71_valor desc limit 1;
                           
                           if nValorTotalTaxa is null or nValorTotalTaxa = 0 or not found then
                           perform fc_debug(\' <iptu_taxalixo> Caracteristica da tarifa de limpeza nao encontrada ou valor nao encontrado na carvalor.\', lRaise);
                           return false;
                           end if;
                           
                           perform fc_debug( \' <iptu_taxalixo> Caracteristicas encontradas para o Grupo 62: \'||debug_caracteristicas, lRaise);
                        else
                           perform fc_debug( \' <iptu_taxalixo> CALCULA TAXA DE LIXO TERRITORIAL\'||rDadosTaxa.matric, lRaise);
                           nValorTotalTaxa = nValpar::numeric;
                           
                           if nValorTotalTaxa is null or nValorTotalTaxa = 0 then
                           perform fc_debug(\' <iptu_taxalixo> Valor da tarifa de limpeza nao encontrado ou zerado. Verifique o cadastro de taxas.\', lRaise);
                           return false;
                           end if;
                        
                        end if;
                        
                        perform fc_debug( \' <iptu_taxalixo> CALCULA TAXA DE LIXO:\', lRaise);
                        perform fc_debug( \'\',lRaise);
                        perform fc_debug( \' <iptu_taxalixo> Valor da Taxa de Lixo: \' ||nValorTotalTaxa, lRaise);
                        
                        insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValorTotalTaxa);
                        insert into tmprecval       values (iReceita, nValorTotalTaxa, iHistCalc, true);
                        
                        return true;
                        
                        end;
                        $$;'
                   where codfuncao = 209");

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
            CREATE OR REPLACE FUNCTION fc_iptu_taxalixo_tramandai_2022(
              integer,
              numeric,
              integer,
              numeric,
              numeric,
              boolean)
                RETURNS boolean
                LANGUAGE 'plpgsql'
            
                COST 100
                VOLATILE 
            AS $$
            
            declare
            
              iReceita                 alias for $1;
              iAliquota                alias for $2;
              iHistCalc                alias for $3;
              iPercIsen                alias for $4;
              nValpar                  alias for $5;
              lRaise                   alias for $6;
            
              nValorTotalTaxa          numeric(15,2) default 0;
            
              debug_caracteristicas    text;
            
              rDadosTaxa               record;
            
              pode_calcular_taxa_lixo  boolean;
            
            begin
            
              perform fc_debug( ' <iptu_taxalixo> CALCULANDO TAXA DE COLETA DE LIXO - PARAMETROS RECEBIDOS ...',lRaise);
              perform fc_debug( '',lRaise);
              perform fc_debug( ' <iptu_taxalixo> receita            - ' || iReceita  ,       lRaise);
              perform fc_debug( ' <iptu_taxalixo> aliq               - ' || iAliquota ,       lRaise);
              perform fc_debug( ' <iptu_taxalixo> historico          - ' || iHistCalc ,       lRaise);
              perform fc_delsession(' <iptu_taxalixo> ERRO_CALCULO_TAXA');
              perform fc_debug( ' <iptu_taxalixo> BUSCA INFORMACOES DA TABELA TMPDADOSTAXA ...',lRaise);
            
              select *
                into rDadosTaxa
                from tmpdadostaxa;
            
              perform fc_debug( ' <iptu_taxalixo> BUSCA O VALOR DA TAXA DE LIXO NA TABELA CARVALOR ...',lRaise);
            
              /* conforme resolucao CSR 01/2021 da AGESAN, sera cobrado taxa de lixo */
              if rDadosTaxa.totareaconst > 0 then
                 perform fc_debug( ' <iptu_taxalixo> CALCULA TAXA DE LIXO PREDIAL'||rDadosTaxa.matric, lRaise);
            
                 select j71_valor,       array_to_string(array_agg(distinct j48_caract::text),',')
                   into nValorTotalTaxa, debug_caracteristicas
                   from carconstr inner join caracter on j31_codigo = j48_caract
                                  inner join carvalor on j71_caract = j48_caract
                                                     and j71_anousu = rDadosTaxa.anousu
                  where j48_matric = rDadosTaxa.matric
                    and j31_grupo  = 62
                  group by j71_valor;
            
                 if nValorTotalTaxa is null or nValorTotalTaxa = 0 or not found then
                    perform fc_debug(' <iptu_taxalixo> Caracteristica da tarifa de limpeza nao encontrada ou valor nao encontrado na carvalor.', lRaise);
                    return false;
                 end if;
            
                 perform fc_debug( ' <iptu_taxalixo> Caracteristicas encontradas para o Grupo 62: '||debug_caracteristicas, lRaise);
              else
                 perform fc_debug( ' <iptu_taxalixo> CALCULA TAXA DE LIXO TERRITORIAL'||rDadosTaxa.matric, lRaise);
                 nValorTotalTaxa = nValpar::numeric;
            
                 if nValorTotalTaxa is null or nValorTotalTaxa = 0 then
                    perform fc_debug(' <iptu_taxalixo> Valor da tarifa de limpeza nao encontrado ou zerado. Verifique o cadastro de taxas.', lRaise);
                    return false;
                 end if;
            
              end if;
            
              perform fc_debug( ' <iptu_taxalixo> CALCULA TAXA DE LIXO:', lRaise);
              perform fc_debug( '',lRaise);
              perform fc_debug( ' <iptu_taxalixo> Valor da Taxa de Lixo: ' ||nValorTotalTaxa, lRaise);
            
              insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValorTotalTaxa);
              insert into tmprecval       values (iReceita, nValorTotalTaxa, iHistCalc, true);
            
              return true;
            
            end;
            $$;

SQL
        );
    }

    private function dicionarioDown()
    {
        DB::statement("update db_sysfuncoes set corpofuncao = 
        'CREATE OR REPLACE FUNCTION fc_iptu_taxalixo_tramandai_2022(
            integer,
            numeric,
            integer,
            numeric,
            numeric,
            boolean)
            RETURNS boolean
            LANGUAGE \'plpgsql\'
            
            COST 100
            VOLATILE 
            AS $$
            
            declare
            
            iReceita                 alias for $1;
            iAliquota                alias for $2;
            iHistCalc                alias for $3;
            iPercIsen                alias for $4;
            nValpar                  alias for $5;
            lRaise                   alias for $6;
            
            nValorTotalTaxa          numeric(15,2) default 0;
            
            debug_caracteristicas    text;
            
            rDadosTaxa               record;
            
            pode_calcular_taxa_lixo  boolean;
            
            begin
            
            perform fc_debug( \' <iptu_taxalixo> CALCULANDO TAXA DE COLETA DE LIXO - PARAMETROS RECEBIDOS ...\',lRaise);
            perform fc_debug( \'\',lRaise);
            perform fc_debug( \' <iptu_taxalixo> receita            - \' || iReceita  ,       lRaise);
            perform fc_debug( \' <iptu_taxalixo> aliq               - \' || iAliquota ,       lRaise);
            perform fc_debug( \' <iptu_taxalixo> historico          - \' || iHistCalc ,       lRaise);
            perform fc_delsession(\' <iptu_taxalixo> ERRO_CALCULO_TAXA\');
            perform fc_debug( \' <iptu_taxalixo> BUSCA INFORMACOES DA TABELA TMPDADOSTAXA ...\',lRaise);
            
            select *
            into rDadosTaxa
            from tmpdadostaxa;
            
            perform fc_debug( \' <iptu_taxalixo> BUSCA O VALOR DA TAXA DE LIXO NA TABELA CARVALOR ...\',lRaise);
            
            /* conforme resolucao CSR 01/2021 da AGESAN, sera cobrado taxa de lixo */
            if rDadosTaxa.totareaconst > 0 then
               perform fc_debug( \' <iptu_taxalixo> CALCULA TAXA DE LIXO PREDIAL\'||rDadosTaxa.matric, lRaise);
               
               select j71_valor,       array_to_string(array_agg(distinct j48_caract::text),\',\')
               into nValorTotalTaxa, debug_caracteristicas
               from carconstr inner join caracter on j31_codigo = j48_caract
                      inner join carvalor on j71_caract = j48_caract
                                         and j71_anousu = rDadosTaxa.anousu
               where j48_matric = rDadosTaxa.matric
               and j31_grupo  = 62
               group by j71_valor;
               
               if nValorTotalTaxa is null or nValorTotalTaxa = 0 or not found then
               perform fc_debug(\' <iptu_taxalixo> Caracteristica da tarifa de limpeza nao encontrada ou valor nao encontrado na carvalor.\', lRaise);
               return false;
               end if;
               
               perform fc_debug( \' <iptu_taxalixo> Caracteristicas encontradas para o Grupo 62: \'||debug_caracteristicas, lRaise);
            else
               perform fc_debug( \' <iptu_taxalixo> CALCULA TAXA DE LIXO TERRITORIAL\'||rDadosTaxa.matric, lRaise);
               nValorTotalTaxa = nValpar::numeric;
               
               if nValorTotalTaxa is null or nValorTotalTaxa = 0 then
               perform fc_debug(\' <iptu_taxalixo> Valor da tarifa de limpeza nao encontrado ou zerado. Verifique o cadastro de taxas.\', lRaise);
               return false;
               end if;
            
            end if;
            
            perform fc_debug( \' <iptu_taxalixo> CALCULA TAXA DE LIXO:\', lRaise);
            perform fc_debug( \'\',lRaise);
            perform fc_debug( \' <iptu_taxalixo> Valor da Taxa de Lixo: \' ||nValorTotalTaxa, lRaise);
            
            insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValorTotalTaxa);
            insert into tmprecval       values (iReceita, nValorTotalTaxa, iHistCalc, true);
            
            return true;
            
            end;
            $$;'
       where codfuncao = 209");
    }

}
