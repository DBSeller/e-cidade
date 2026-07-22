<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M19469CalculoTaxaLixoTramandai extends Migration
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

    private function dicionarioUp()
    {
        DB::statement("insert into db_sysfuncoes(codfuncao,nomefuncao,nomearquivo,obsfuncao,corpofuncao,triggerfuncao)
                                         values (209 ,'fc_iptu_taxalixo_tramandai_2022' ,'iptu_taxalixo_tramandai_2022.sql',
                                                 'Função de cálculo da Taxa de Lixo de Tramandaí',
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
                      $$;', '0')");
        DB::statement("insert into db_sysfuncoesparam(db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome,
                                                      db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default,
                                                      db42_descricao )
                                              values (1142 ,209 ,1 ,'iReceita' ,'int4' ,0 ,0 ,'' ,'RECEITA' );");
        DB::statement("insert into db_sysfuncoesparam(db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome,
                                                      db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default,
                                                      db42_descricao )
                                              values (1143 ,209 ,2 ,'iAliquota' ,'numeric' ,0 ,0 ,'' ,'ALIQUOTA' );");
        DB::statement("insert into db_sysfuncoesparam(db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome,
                                                      db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default,
                                                      db42_descricao )
                                              values (1144 ,209 ,3 ,'iHistCalc' ,'int4' ,0 ,0 ,'' ,
                                                      'HISTÓRICO DE CÁLCULO' );");
        DB::statement("insert into db_sysfuncoesparam(db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome,
                                                      db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default,
                                                      db42_descricao )
                                              values (1145 ,209 ,4 ,'iPercIsen' ,'numeric' ,0 ,0 ,'' ,
                                                      'PERCENTUAL DE ISENÇÃO' );");
        DB::statement("insert into db_sysfuncoesparam(db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome,
                                                      db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default,
                                                      db42_descricao )
                                              values (1146 ,209 ,5 ,'nValpar' ,'numeric' ,0 ,0 ,'' ,
                                                      'VALOR POR PARÂMETRO' );");
        DB::statement("insert into db_sysfuncoesparam(db42_sysfuncoesparam ,db42_funcao ,db42_ordem,
                                                      db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao,
                                                      db42_valor_default ,db42_descricao )
                                              values ( 1147 ,209 ,6 ,'bRaise' ,'bool' ,0 ,0 ,'FALSE' ,'DEBUG' );");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("delete from db_sysfuncoesparam where db42_funcao = 209");
        DB::statement("delete from db_sysfuncoes where codfuncao = 209");
        DB::statement("DROP FUNCTION fc_iptu_taxalixo_tramandai_2022");
    }
}
