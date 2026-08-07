<?php

use Classes\PostgresMigration;

class M10996ImplementacaoNovaConsistenciaContabilidade extends PostgresMigration
{
    public function up()
    {
        $sql = "
            DELETE FROM consistenciasistema
            WHERE db160_sequencial = 1002;
            
            INSERT INTO consistenciasistema
            VALUES (
              nextval('consistenciasistema_db160_sequencial_seq'),
              1,
              '
                {
                  \"tipo\": 1,
                  \"nome\": \"Consistência de Lançamentos Contábeis\",
                  \"descricao\": \"Verifica lançamentos em classes e grupos incompatíveis nos movimentos a débito e a crédito.\",
                  \"formulario\": {
                    \"campos\": [
                      {
                        \"propriedade\": \"data_lancto\",
                        \"nome\": \"Data do lançamento\"
                      },
                      {
                        \"propriedade\": \"codigo_lancto\",
                        \"nome\": \"Lançamento\",
                        \"chave_primaria\": true
                      },
                      {
                        \"propriedade\": \"debito\",
                        \"nome\": \"Débito\"
                      },
                      {
                        \"propriedade\": \"credito\",
                        \"nome\": \"Crédito\"
                      },
                      {
                        \"propriedade\": \"documento\",
                        \"nome\": \"Documento\"
                      },
                      {
                        \"propriedade\": \"seq_empenho\",
                        \"nome\": \"Sequencial Empenho\"
                      },
                      {
                        \"propriedade\": \"valor\",
                        \"nome\": \"Valor\"
                      }
                    ]
                  },
                  \"sql\": {
                    \"consistencia\": \"select data_lancto,               codigo_lancto,               debito,               credito,               documento,               seq_empenho,               valor           from (  select a.c69_data           as data_lancto,    a.c69_codlan           as codigo_lancto,    d.c60_estrut:: varchar      as debito,    e.c60_estrut:: varchar      as credito,    c71_coddoc || \' - \' || c53_descr as documento,    c75_numemp            as seq_empenho,    round(c69_valor,2)   as valor  from conlancamval a     inner join conplanoreduz b on a.c69_debito = b.c61_reduz  and b.c61_instit = fc_getsession(\'DB_instit\')::int and b.c61_anousu = fc_getsession(\'DB_anousu\')::int    inner join conplanoreduz c on a.c69_credito = c.c61_reduz  and c.c61_instit = fc_getsession(\'DB_instit\')::int and c.c61_anousu = fc_getsession(\'DB_anousu\')::int    inner join conplano d   on b.c61_anousu = d.c60_anousu  and b.c61_codcon = d.c60_codcon     inner join conplano e   on c.c61_anousu = e.c60_anousu  and c.c61_codcon = e.c60_codcon     inner join conlancamdoc  on a.c69_codlan = c71_codlan     inner join conhistdoc   on c71_coddoc  = c53_coddoc    left join conlancamemp  on a.c69_codlan = c75_codlan where extract(year from a.c69_data) = fc_getsession(\'DB_anousu\')::int  and c53_tipo <> 1000 ) as x where    (substr(debito,1,1) = \'3\' and substr(credito,1,1) in (\'5\',\'6\',\'7\',\'8\'))  or (substr(credito,1,1) = \'3\' and substr(debito,1,1) in (\'5\',\'6\',\'7\',\'8\'))  or (substr(debito,1,1) = \'4\' and substr(credito,1,1) in (\'5\',\'6\',\'7\',\'8\'))  or (substr(credito,1,1) = \'4\' and substr(debito,1,1) in (\'5\',\'6\',\'7\',\'8\'))  or (substr(debito,1,1) = \'1\' and substr(credito,1,1) in (\'5\',\'6\',\'7\',\'8\'))  or (substr(credito,1,1) = \'1\' and substr(debito,1,1) in (\'5\',\'6\',\'7\',\'8\'))  or (substr(debito,1,1) = \'2\' and substr(credito,1,1) in (\'5\',\'6\',\'7\',\'8\'))  or (substr(credito,1,1) = \'2\' and substr(debito,1,1) in (\'5\',\'6\',\'7\',\'8\'))   or (substr(debito,1,1) = \'5\' and substr(credito,1,1) not in (\'5\',\'6\'))  or (substr(credito,1,1) = \'5\' and substr(debito,1,1) not in (\'5\',\'6\'))  or (substr(debito,1,1) = \'6\' and substr(credito,1,1) not in (\'5\',\'6\'))  or (substr(credito,1,1) = \'6\' and substr(debito,1,1) not in (\'5\',\'6\'))   or (substr(debito,1,1) = \'7\' and substr(credito,1,1) not in (\'7\',\'8\'))  or (substr(credito,1,1) = \'7\' and substr(debito,1,1) not in (\'7\',\'8\'))  or (substr(debito,1,1) = \'8\' and substr(credito,1,1) not in (\'7\',\'8\'))  or (substr(credito,1,1) = \'8\' and substr(debito,1,1) not in (\'7\',\'8\'))   or (substr(debito,1,2) = \'51\' and substr(credito,1,2) not in (\'51\',\'61\'))  or (substr(credito,1,2) = \'51\' and substr(debito,1,2) not in (\'51\',\'61\'))  or (substr(debito,1,2) = \'61\' and substr(credito,1,2) not in (\'51\',\'61\'))  or (substr(credito,1,2) = \'61\' and substr(debito,1,2) not in (\'51\',\'61\'))   or (substr(debito,1,2) = \'71\' and substr(credito,1,2) not in (\'71\',\'81\'))  or (substr(credito,1,2) = \'71\' and substr(debito,1,2) not in (\'71\',\'81\'))  or (substr(debito,1,2) = \'81\' and substr(credito,1,2) not in (\'71\',\'81\'))  or (substr(credito,1,2) = \'81\' and substr(debito,1,2) not in (\'71\',\'81\'))   or (substr(debito,1,2) = \'52\' and substr(credito,1,2) not in (\'52\',\'62\'))  or (substr(credito,1,2) = \'52\' and substr(debito,1,2) not in (\'52\',\'62\'))  or (substr(debito,1,2) = \'62\' and substr(credito,1,2) not in (\'52\',\'62\'))  or (substr(credito,1,2) = \'62\' and substr(debito,1,2) not in (\'52\',\'62\'))   or (substr(debito,1,2) = \'72\' and substr(credito,1,2) not in (\'72\',\'82\'))  or (substr(credito,1,2) = \'72\' and substr(debito,1,2) not in (\'72\',\'82\'))  or (substr(debito,1,2) = \'82\' and substr(credito,1,2) not in (\'72\',\'82\'))  or (substr(credito,1,2) = \'82\' and substr(debito,1,2) not in (\'72\',\'82\'))   or (substr(debito,1,2) = \'53\' and substr(credito,1,2) not in (\'53\',\'63\'))  or (substr(credito,1,2) = \'53\' and substr(debito,1,2) not in (\'53\',\'63\'))  or (substr(debito,1,2) = \'63\' and substr(credito,1,2) not in (\'53\',\'63\'))  or (substr(credito,1,2) = \'63\' and substr(debito,1,2) not in (\'53\',\'63\'))   or (substr(debito,1,2) = \'73\' and substr(credito,1,2) not in (\'73\',\'83\'))  or (substr(credito,1,2) = \'73\' and substr(debito,1,2) not in (\'73\',\'83\'))  or (substr(debito,1,2) = \'83\' and substr(credito,1,2) not in (\'73\',\'83\'))  or (substr(credito,1,2) = \'83\' and substr(debito,1,2) not in (\'73\',\'83\'))   or (substr(debito,1,3) = \'511\' and substr(credito,1,3) not in (\'511\',\'611\'))  or (substr(credito,1,3) = \'511\' and substr(debito,1,3) not in (\'511\',\'611\'))  or (substr(debito,1,3) = \'611\' and substr(credito,1,3) not in (\'511\',\'611\'))  or (substr(credito,1,3) = \'611\' and substr(debito,1,3) not in (\'511\',\'611\'))   or (substr(debito,1,3) = \'711\' and substr(credito,1,3) not in (\'711\',\'811\'))  or (substr(credito,1,3) = \'711\' and substr(debito,1,3) not in (\'711\',\'811\'))  or (substr(debito,1,3) = \'811\' and substr(credito,1,3) not in (\'711\',\'811\'))  or (substr(credito,1,3) = \'811\' and substr(debito,1,3) not in (\'711\',\'811\'))   or (substr(debito,1,3) = \'521\' and substr(credito,1,3) not in (\'521\',\'621\'))  or (substr(credito,1,3) = \'521\' and substr(debito,1,3) not in (\'521\',\'621\'))  or (substr(debito,1,3) = \'621\' and substr(credito,1,3) not in (\'521\',\'621\'))  or (substr(credito,1,3) = \'621\' and substr(debito,1,3) not in (\'521\',\'621\'))   or (substr(debito,1,3) = \'522\' and substr(credito,1,3) not in (\'522\',\'622\'))  or (substr(credito,1,3) = \'522\' and substr(debito,1,3) not in (\'522\',\'622\'))  or (substr(debito,1,3) = \'622\' and substr(credito,1,3) not in (\'522\',\'622\'))  or (substr(credito,1,3) = \'622\' and substr(debito,1,3) not in (\'522\',\'622\'))   or (substr(debito,1,3) = \'531\' and substr(credito,1,3) not in (\'531\',\'631\'))  or (substr(credito,1,3) = \'531\' and substr(debito,1,3) not in (\'531\',\'631\'))  or (substr(debito,1,3) = \'631\' and substr(credito,1,3) not in (\'531\',\'631\'))  or (substr(credito,1,3) = \'631\' and substr(debito,1,3) not in (\'531\',\'631\'))   or (substr(debito,1,3) = \'532\' and substr(credito,1,3) not in (\'532\',\'632\'))  or (substr(credito,1,3) = \'532\' and substr(debito,1,3) not in (\'532\',\'632\'))  or (substr(debito,1,3) = \'632\' and substr(credito,1,3) not in (\'532\',\'632\'))  or (substr(credito,1,3) = \'632\' and substr(debito,1,3) not in (\'532\',\'632\'))   or (substr(debito,1,3) = \'721\' and substr(credito,1,3) not in (\'721\',\'821\'))  or (substr(credito,1,3) = \'721\' and substr(debito,1,3) not in (\'721\',\'821\'))  or (substr(debito,1,3) = \'821\' and substr(credito,1,3) not in (\'721\',\'821\'))  or (substr(credito,1,3) = \'821\' and substr(debito,1,3) not in (\'721\',\'821\'))   or (substr(debito,1,3) = \'722\' and substr(credito,1,3) not in (\'722\',\'822\'))  or (substr(credito,1,3) = \'722\' and substr(debito,1,3) not in (\'722\',\'822\'))  or (substr(debito,1,3) = \'822\' and substr(credito,1,3) not in (\'722\',\'822\'))  or (substr(credito,1,3) = \'822\' and substr(debito,1,3) not in (\'722\',\'822\'))   or (substr(debito,1,3) = \'712\' and substr(credito,1,3) not in (\'712\',\'812\'))  or (substr(credito,1,3) = \'712\' and substr(debito,1,3) not in (\'712\',\'812\'))  or (substr(debito,1,3) = \'812\' and substr(credito,1,3) not in (\'712\',\'812\'))  or (substr(credito,1,3) = \'812\' and substr(debito,1,3) not in (\'712\',\'812\'))   or (substr(debito,1,4) = \'5229\' and substr(credito,1,4) not in (\'5229\',\'6229\'))  or (substr(credito,1,4) = \'5229\' and substr(debito,1,4) not in (\'5229\',\'6229\'))  or (substr(debito,1,4) = \'6229\' and substr(credito,1,4) not in (\'5229\',\'6229\'))  or (substr(credito,1,4) = \'6229\' and substr(debito,1,4) not in (\'5229\',\'6229\'))   or (substr(debito,1,4) = \'7211\' and substr(credito,1,4) not in (\'7211\',\'8211\'))  or (substr(credito,1,4) = \'7211\' and substr(debito,1,4) not in (\'7211\',\'8211\'))  or (substr(debito,1,4) = \'8211\' and substr(credito,1,4) not in (\'7211\',\'8211\'))  or (substr(credito,1,4) = \'8211\' and substr(debito,1,4) not in (\'7211\',\'8211\'));\",
                    \"correcao\": \"select 1\"
                  }
                }
              '
            );  
        ";

        $this->execute($sql);
    }
}
