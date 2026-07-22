<?php

use Classes\PostgresMigration;

class M12874ConsistenciaPadContratoLiquidacao extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

delete from consistenciasistema where db160_json ilike '%"5c6edbe948913"%';

insert into consistenciasistema
values (nextval('consistenciasistema_db160_sequencial_seq'), 1 ,
        '{
           "tipo": 1,
           "uuid" : "5c6edbe948913",
           "nome": "Liquidações sem número de contrato.",
           "descricao": "Empenhos liquidados sem número de contrato ou com o mesmo inválido.",
           "formulario": {
             "campos": [
               {
                 "propriedade": "numero_empenho",
                 "nome": "Número do Empenho"
               },
               {
                 "propriedade": "lancamento",
                 "nome": "Código do Lançamento",
                 "chave_primaria" : true
               },
               {
                 "propriedade": "numero_encontrado",
                 "nome": "Número Encontrado"
               },
               {
                 "propriedade": "numero",
                 "nome": "Número de Contrato",
                 "tipo": "input"
               }
             ]
           },
           "sql": {
             "consistencia": "
                select e60_codemp || \'/\' || e60_anousu as numero_empenho,
                       c66_codlan  as lancamento,
                       numero as numero_encontrado,
                       numero as numero
                  from empempenho
                       inner join empnota on empnota.e69_numemp = empempenho.e60_numemp
                       inner join conlancamnota on conlancamnota.c66_codnota = empnota.e69_codnota
                       inner join plugins.contratospadrs on plugins.contratospadrs.lancamento = conlancamnota.c66_codlan
                 where e60_anousu = fc_getsession(''db_anousu'')::integer
                   and regexp_replace (contratospadrs.numero, \'([^1-9])\', \'\', \'g\') = \'\'
                 order by numero_empenho, lancamento;",
             "correcao" : "update plugins.contratospadrs set numero = \'[numero]\' where lancamento = [lancamento];"
                }
              }'
       );

SQL_UP
);
    }


    public function down()
    {

        $this->execute("delete from consistenciasistema where db160_json ilike '%\"5c6edbe948913\"%';");
    }
}
