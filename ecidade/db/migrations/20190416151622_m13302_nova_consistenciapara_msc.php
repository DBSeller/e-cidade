<?php

use Classes\PostgresMigration;

class M13302NovaConsistenciaparaMsc extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

delete from consistenciasistema where db160_json ilike '%5cb5f2914b491%';

insert into consistenciasistema values
(nextval('consistenciasistema_db160_sequencial_seq'),
 10,
 '{
"uuid": "5cb5f2914b491",
"tipo": 1,
"nome": "Consistência analítica Balancetes X MSC (Despesa)",
"descricao": "Consistência analítica Balancetes X MSC (Despesa)",
"ajuda" : "",
"formulario": {
"campos": [
    {
      "propriedade": "codigo_lancamento",
      "nome": "Lançamento",
      "chave_primaria": true,
      "width": "10"
    },
    {
      "propriedade": "codigo_documento",
      "nome": "Documento",
      "width": "10"
    },
    {
      "propriedade": "estrutural",
      "nome": "Estrutural",
      "width": "10"
    },
    {
      "propriedade": "natureza",
      "nome": "Natureza",
      "width": "10"
    },
    {
      "propriedade": "atributos_lancamentos",
      "nome": "Atributos do Lançamento",
      "width": "20"
    },
    {
      "propriedade": "atributos_lancamentos_po",
      "nome": "Atributo PO",
      "width": "10"
    },
    {
      "propriedade": "atributos_msc",
      "nome": "Atributos da MSC",
      "width": "20"
    },
    {
      "propriedade": "valor_monetario",
      "nome": "Valor",
      "width": "10"
    }
  ]
},

"filtros": {                      
  "campos": [                     
    {                             
      "label": "Data Inicial",    
      "nome" : "data_inicial",    
      "tipo": "data"              
    },                            
    {                             
      "label": "Data Final",      
      "nome" : "data_final",      
      "tipo": "data"              
    }                             
  ]                               
},                                

"sql": {
    "consistencia": "



with lancamentos_despesa as (select c69_codlan as codigo_lancamento,
                                    c60_estrut as estrutural,
                                    c71_coddoc as codigo_documento,
                                    \'C\' as natureza,
                                    codtrib||\'#PO|\'||o58_codigo||\'#FR|\'||o56_elemento||\'#ND|\'||lpad(o58_funcao, 2, \'0\')||o58_subfuncao||\'#FS\' as atributos_lancamentos,
                                    codtrib||\'#PO\' as atributos_lancamentos_po,
                                    c69_valor as valor_monetario,
                                    array_to_string(
                                      (select array_accum(c123_valor||\'#\'||c121_sigla order by c121_sequencial) as atributo
                                       from conplanoatributolancamentos
                                              inner join infocomplementarvalor
                                                on c123_conplanoatributolancamentos = c124_sequencial
                                              inner join conplanoinfocomplementar
                                                on c121_sequencial = c123_infocomplementar
                                       where c124_lancamento = c69_codlan
                                         and c124_natureza = \'C\'
                                         and c123_reduzido = c61_reduz
                                         and c124_conplanosistema = 1
                                        and c123_infocomplementar not in(50)
                                       group by c124_lancamento, c124_valor),
                                      \'|\') as atributos_msc

                             from conlancamval
                                    inner join conlancamdoc on c69_codlan = c71_codlan
                                    inner join conlancamdot on c73_codlan = c69_codlan
                                    inner join conlancaminstit on c02_codlan = c69_codlan
                                    inner join db_config on codigo = c02_instit
                                    inner join orcdotacao on c73_coddot = o58_coddot
                                                               AND c73_anousu = o58_anousu
                                    INNER JOIN orcamento.orcelemento ON o56_codele = o58_Codele
                                                                          AND o56_anousu = o58_anousu
                                    inner join conplanoreduz on c69_credito = c61_reduz
                                                                  and c61_anousu = c69_anousu
                                    inner join conplano on c61_codcon = c60_codcon
                                                             and c61_anousu = c69_anousu
                             where c69_anousu = fc_getsession(\'DB_anousu\')::integer
                               and c69_data between \'#data_inicial#\'::date and \'#data_final#\'::date
                               and c60_estrut IN (\'622130100000000\',
                                                  \'622130300000000\',
                                                  \'622130400000000\',
                                                  \'622920101000000\',
                                                  \'622920103000000\',
                                                  \'622920104000000\')
    union all
    select c69_codlan as codigo_lancamento,
           c60_estrut as estrutural,
           c71_coddoc as codigo_documento,
           \'D\' as natureza,
           codtrib||\'#PO|\'||o58_codigo||\'#FR|\'||o56_elemento||\'#ND|\'||lpad(o58_funcao, 2, \'0\')||o58_subfuncao||\'#FS\' as atributos_lancamentos,
           codtrib||\'#PO\' as atributos_lancamentos_po,
           c69_valor as valor_monetario,
           array_to_string((select array_accum(c123_valor||\'#\'||c121_sigla order by c121_sequencial) as atributo
                            from conplanoatributolancamentos
                                   inner join infocomplementarvalor
                                     on c123_conplanoatributolancamentos = c124_sequencial
                                   inner join conplanoinfocomplementar on c121_sequencial = c123_infocomplementar
                            where c124_lancamento = c69_codlan
                              and c124_natureza = \'D\'
                              and c123_reduzido = c61_reduz
                              and c124_conplanosistema = 1
                              and c123_infocomplementar not in(50)
                            group by c124_lancamento, c124_valor),
                           \'|\') as atributos_msc

    from conlancamval
           inner join conlancamdoc on c69_codlan = c71_codlan
           inner join conlancamdot on c73_codlan = c69_codlan
           inner join conlancaminstit on c02_codlan = c69_codlan
           inner join db_config on codigo = c02_instit
           inner join orcdotacao on c73_coddot = o58_coddot
                                      AND c73_anousu = o58_anousu
           INNER JOIN orcamento.orcelemento ON o56_codele = o58_Codele
                                                 AND o56_anousu = o58_anousu
           inner join conplanoreduz on c69_debito = c61_reduz
                                         and c61_anousu = c69_anousu
           inner join conplano on c61_codcon = c60_codcon
                                    and c61_anousu = c69_anousu
    where c69_anousu = fc_getsession(\'DB_anousu\')::integer
      and c69_data between \'#data_inicial#\'::date and \'#data_final#\'::date
      and c60_estrut IN (\'622130100000000\',
                         \'622130300000000\',
                         \'622130400000000\',
                         \'622920101000000\',
                         \'622920103000000\',
                         \'622920104000000\'))
select DISTINCT *
from lancamentos_despesa
where  case when estrutural  IN (\'622920101000000\', \'622920103000000\', \'622920104000000\') then TRIM(atributos_lancamentos_po) <> TRIM(atributos_msc)
      when  estrutural IN (\'622130100000000\', \'622130300000000\', \'622130400000000\') then  atributos_lancamentos <> atributos_msc end

    ",
    "correcao" : ""
  }
}');

SQL_UP
);

    }


    public function down()
    {
        $this->execute("delete from consistenciasistema where db160_json ilike '%5cb5f2914b491%';");
    }
}
