<?php

use Classes\PostgresMigration;

class M13822ConsistenciaDocumento1019 extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $this->execute(<<<SQL
delete
from configuracoes.consistenciasistema
where db160_sequencial = 1000003;

insert into configuracoes.consistenciasistema
values (1000003,
        100,
        '{
           "tipo": 100,
           "uid": "5dd80c4d3f739",
           "nome": "Encerramento - Doc. 1019",
           "descricao": "Contas com inconsistência no documento 1019",
           "formulario": {
             "campos": [
                 {
                   "propriedade": "conta",
                   "nome": "Reduzido",
                   "chave_primaria": true
                 },
                 {
                   "propriedade": "estrutural",
                   "nome": "Estrutural"
                 },
                 {
                   "propriedade": "descricao",
                   "nome": "Descrição"
                 },
                 {
                   "propriedade": "valor_encerramento",
                   "nome": "Valor Encerramento"
                 },
                 {
                   "propriedade": "mensagem",
                   "nome": "Mensagem"
                 }
               ]
             },
             "sql": {
               "consistencia": "select c60_estrut as estrutural,
       c60_descr as descricao,
       conta,
       valor_encerramento,
       saldo_conta_balancete,
       comparacao
from (select valor_encerramento,
             case when comparacao = 2 then conta_credito else conta_debito end as conta,
             comparacao,
             (select case when natureza_saldo_final = ''C'' then saldo_final * -1 else saldo_final end
              from fc_planosaldonovo_record(2019, case when comparacao = 2 then conta_credito else conta_debito end,
                                            cast(fc_getsession(''DB_anousu'')||''-01-01'' as date), cast(fc_getsession(''DB_anousu'')||''-12-31'' as date), true)
             )                                                                 as saldo_conta_balancete
      from (
               select conta_Credito, conta_debito, comparacao, sum(valor) as valor_encerramento
               from fc_encerramento_doc_1019()
               group by conta_Credito, conta_debito, comparacao
           ) as saldo
     ) as saldos
         inner join contabilidade.conplanoreduz on c61_reduz = conta
    and conplanoreduz.c61_anousu = fc_getsession(''DB_anousu'')::int
         inner join contabilidade.conplano on c61_codcon = c60_codcon
    and c60_anousu = c61_anousu
 where abs(saldo_conta_balancete) <> abs(valor_encerramento)",
                    "correcao": ""
                  }
              }'
);

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete
from configuracoes.consistenciasistema
where db160_sequencial = 1000003;
SQL
        );
    }
}
