<?php

use Classes\PostgresMigration;

class M15203ConsistenciaEncerramentoPatrimonial extends PostgresMigration
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
delete from consistenciasistema where db160_json ilike '%5dd71123a05ef67a%';
insert into consistenciasistema
values (10000011, 100, '{
  "tipo": 100,
  "uid": "5dd71123a05ef67a",
  "nome": "Conferência Superávit/déficit do exercício",
  "descricao": "Conferência Superávit/déficit do exercício",
  "formulario": {
    "campos": [
      {
        "propriedade": "saldo_patrimonial",
        "nome": "Saldo Contas VPD/VPA", "chave_primaria": true
      },
      {
        "propriedade": "saldo_passivo",
        "nome": "Saldo Contas Superávit/defícit do exercício"
      },
     {
        "propriedade": "diferenca",
        "nome": "Diferença"
      }
    ]
  },
  "sql": {
    "consistencia": "select saldo_patrimonial, saldo_passivo , ( saldo_patrimonial -  saldo_passivo) as diferenca
from (
         select (select abs(round(sum(
                                          (select sum(case
                                                          when natureza_saldo_final = ''C'' then saldo_final * -1
                                                          else saldo_final end) as superavit
                                           from fc_planosaldonovo_record(c61_anousu, c61_reduz,
                                                                         cast(fc_getsession(''DB_anousu'') || ''-01-01'' as date),
                                                                         cast(fc_getsession(''DB_anousu'') || ''-12-31'' as date),
                                                                         false)
                                          )), 2)) as saldo_variacao_patrimonial
                 from contabilidade.conplano
                          inner join contabilidade.conplanoreduz
                                     on conplano.c60_codcon = conplanoreduz.c61_codcon
                                         and conplano.c60_anousu = conplanoreduz.c61_anousu
                 where c61_instit = fc_getsession(''DB_instit'')::integer
                   and c61_anousu = fc_getsession(''DB_anousu'')::integer
                   and substr(c60_estrut, 1, 1) in (''3'', ''4''))                                              as saldo_patrimonial,

                (select abs(round(sum(
                                          (select sum(case
                                                          when natureza_saldo_final = ''C'' then saldo_final * -1
                                                          else saldo_final end) as superavit
                                           from fc_planosaldonovo_record(c61_anousu, c61_reduz,
                                                                         cast(fc_getsession(''DB_anousu'') || ''-01-01'' as date),
                                                                         cast(fc_getsession(''DB_anousu'') || ''-12-31'' as date),
                                                                         true)
                                          )), 2)) as saldo_passivo
                 from contabilidade.conplano
                          inner join contabilidade.conplanoreduz
                                     on conplano.c60_codcon = conplanoreduz.c61_codcon
                                         and conplano.c60_anousu = conplanoreduz.c61_anousu
                 where c61_instit = fc_getsession(''DB_instit'')::integer
                   and c61_anousu = fc_getsession(''DB_anousu'')::integer
                   and substr(c60_estrut, 1, 7) in
                       (''2371101'', ''2371201'', ''2371301'', ''2371401'', ''2371501''))                             as saldo_passivo
     ) as x;

",
    "correcao": ""
  }
}
');


SQL
        );

    }

    public function down()
    {
        $this->execute(<<<SQL
delete from consistenciasistema where db160_json ilike '%5dd71123a05ef67a%';
SQL
        );
    }
}
