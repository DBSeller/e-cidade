<?php

use Classes\PostgresMigration;

class M14143ConsistenciaPlanoMovimentacaoMsc extends PostgresMigration
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
            delete from consistenciasistema where db160_json ilike '%5d7668987e027%';

insert into consistenciasistema values
    (nextval('consistenciasistema_db160_sequencial_seq'),
        10,
        '{
  "uuid": "5d7668987e027",
  "tipo": 10,
  "nome": "Contas Com movimentacao no E-cidade não existentes na Matriz de 2019",
  "descricao": "Demonstra as contas com movimentacao no E-cidade não existentes na Matriz de 2019",
  "ajuda" : "",
  "formulario": {
"campos": [
    {
    "propriedade": "conta_ecidade",
    "nome": "Conta E-cidade",
    "chave_primaria": true
    },
    {
    "propriedade": "conta_msc",
    "nome": "Conta na MSC"
    },
    {
    "propriedade": "problema",
    "nome": "Problema"
    }
]
},
  "filtros" : {
    "campos": [
      {
        "label": "Ano",
        "nome": "ano",
        "tipo": "texto"
      }
    ]

  },
  "sql": {
    "consistencia": "select distinct substr(c60_estrut, 1, 9) as conta_ecidade, conta as conta_msc, (case when (conta is null)
                                 then ''conta fora dos padrões do PCASP'' else ''-'' end) as problema
    from conplano
    inner join conplanoreduz on c61_codcon = c60_codcon and c61_anousu = c60_anousu
    left join public.atributos_padrao_msc2019 on conta = substr(c60_estrut, 1, 9)
    where c60_anousu = #ano#
    and exists(select 1 from conlancamval where c69_anousu = #ano# and (c69_credito = c61_reduz or c69_debito = c61_reduz))
    order by 1" ,
    "correcao" : ""
  }
}');
SQL
        );
    }

    public function down(){
        $this->execute("delete from consistenciasistema where db160_json ilike '%5d7668987e027%'");
    }
}
