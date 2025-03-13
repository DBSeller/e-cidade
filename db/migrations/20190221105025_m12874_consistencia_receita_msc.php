<?php

use Classes\PostgresMigration;

class M12874ConsistenciaReceitaMsc extends PostgresMigration
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
        
insert into consistenciasistema values
                                       (nextval('consistenciasistema_db160_sequencial_seq'),
                                        10,
                                        '{
  "tipo": 1,
  "uuid": "15c6db5bc43e7d",
  "nome": "Conferência entre Balancete de receita e MSC",
  "descricao": "Conferência dos Valores Arrecadados entre Balancete de Receita e MSC",
  "formulario": {
    "campos": [
      {
        "propriedade": "estrutural",
        "nome": "Estrutural da conta",
        "chave_primaria": true
      },
      {
        "propriedade": "nome_receita",
        "nome": "Receita"
      },
      {
        "propriedade": "saldo_matriz",
        "nome": "Arrecadação MSC"
      },
      {
        "propriedade": "saldo_balancete_receita",
        "nome": "Arrecadação Receita"
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
    "consistencia": "select estrutural, nome_receita, saldo_matriz, saldo_balancete_receita
from (select estrutural,
             receita,
             o70_codrec                                                                                 as codigo_receita,
             o57_descr                                                                                  as nome_receita,
             ((val_ant_deb - val_ant_cre) + valor_debito)                                               as saldo_anterior,
             valor_debito                                                                               as debitos,
             valor_credito                                                                              as valor_credito,
             abs(((val_ant_deb - val_ant_cre) + valor_debito) - valor_credito)                          as saldo_matriz,
             abs((select round(sum(valor_bal_rec [ 6 ]), 2)
                  from (select fc_receitasaldo_array(o70_anousu, o70_codrec, 3, ''#data_inicial#'',
                                                     ''#data_final#'') as valor_bal_rec) as valor_bal_rec)) as saldo_balancete_receita
      from (with lancamentos as (select c124_sequencial as codigo,
                                        c124_data       as data,
                                        c124_natureza   as natureza,
                                        c124_valor      as valor,
                                        c124_lancamento as codigo_lancamento,
                                        c71_coddoc      as documento,
                                        c123_reduzido   as reduzido,
                                        c60_estrut      as estrutural,
                                        c60_descr       as nome_conta,
                                        c123_valor      as valor_atributo,
                                        c121_sigla      as sigla_atributo,
                                        c121_sequencial as ordem,
                                        c124_tipo       as tipo
                                 from infocomplementarvalor
                                        inner join conplanoatributolancamentos
                                          on c124_sequencial = c123_conplanoatributolancamentos
                                        inner join conplanoinfocomplementar on c121_sequencial = c123_infocomplementar
                                        inner join conplanoreduz on c61_reduz = c123_reduzido
                                                                      and
                                                                    extract(year from c124_data) :: int = c61_anousu
                                        inner join conplano on c61_codcon = c60_codcon
                                                                 and c60_anousu = c61_anousu
                                        left join conlancam on c70_codlan = c124_lancamento
                                        left join conlancamdoc on c71_codlan = c70_codlan

                                 where c124_data >= ''#data_inicial#''
                                   and c124_data <= ''#data_final#''
                                   and c123_conplanosistema = 1
                                   and c61_instit = 1
                                   and c121_sigla = ''NR''
                                 order by c124_sequencial, c71_coddoc, c124_data, c123_reduzido, c121_sequencial,
                                          c124_lancamento,
                                          c60_estrut),
          conta_corrente as (select codigo,
                                    data,
                                    natureza,
                                    valor,
                                    codigo_lancamento,
                                    reduzido,
                                    estrutural,
                                    nome_conta,
                                    tipo,
                                    valor_atributo as atributos
                             from lancamentos
                             order by codigo,
                                      data,
                                      natureza,
                                      valor,
                                      codigo_lancamento,
                                      reduzido,
                                      estrutural)


      select estrutural,
             nome_conta,
             atributos as receita,

             round(coalesce(sum(case
                                  when (data < ''#data_inicial#'' or tipo = ''1'') and natureza = ''D'' then valor end), 0),
                   2)  AS val_ant_deb,
             round(coalesce(sum(case
                                  when (data < ''#data_inicial#'' or tipo = ''1'') and natureza = ''C'' then valor end), 0),
                   2)  as val_ant_cre,
             round(coalesce(sum(case
                                  when data >= ''#data_inicial#'' and tipo = ''2'' and natureza = ''D'' then valor end), 0),
                   2)  as valor_debito,
             round(coalesce(sum(case
                                  when data >= ''#data_inicial#'' and tipo = ''2'' and natureza = ''C'' then valor end), 0),
                   2)  as valor_credito
      from conta_corrente
      where estrutural ilike ''6212%''
        and data between ''#data_inicial#'' and ''#data_final#''
      group by estrutural, nome_conta, atributos
      order by estrutural,
               atributos) as receitas_msc
             inner join orcamento.orcfontes on receitas_msc.receita = o57_fonte
                                                 and o57_anousu = 2019
             inner join orcamento.orcreceita on o57_codfon = o70_codfon
                                                  and o57_anousu = o70_anousu) as tabelao
where saldo_matriz <> saldo_balancete_receita;
"
  }
}' );
SQL
        );
    }
    
    public function down()
    {

      $this->execute("delete from consistenciasistema where db160_json ilike '%15c6db5bc43e7d%'");
    }
}
