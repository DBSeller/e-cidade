<?php

use Classes\PostgresMigration;

class M12874ConsistenciaDespesaMsc extends PostgresMigration
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
  "uuid": "5c6acff05123qdqwdx5a5a",
  "nome": "Conferência entre Balancete de Despesas e MSC",
  "descricao": "Conferência dos saldos finais entre Balancete de Despesas e MSC",
  "formulario": {
    "campos": [
      {
        "propriedade": "estrutural",
        "nome": "Estrutural da conta",
        "chave_primaria": true
      },
      {
        "propriedade": "atributos",
        "nome": "Atributos da Despesa"
      },
      {
        "propriedade": "saldo_final_msc",
        "nome": "Saldo MSC"
      },
      {
        "propriedade": "saldo_validar",
        "nome": "Saldo da Despesa *"
      },
      {
        "propriedade": "diferenca",
        "nome": "Diferença"
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
    "consistencia": "SELECT estrutural,
       nome_conta,
       funcao,
       subfuncao,
       instituicao,
       elemento,
       recurso,
       atributos,
       debitos,
       creditos,
       abs(saldo_final_msc) as saldo_final_msc,
       saldo_validar,
       abs(saldo_final_msc) - saldo_validar as diferenca
FROM
     (SELECT estrutural,
             nome_conta,
             instituicao,
             funcao,
             atributos,
             subfuncao,
             elemento,
             recurso,
             ((val_ant_deb - val_ant_cre)) AS saldo_anterior,
             valor_debito AS debitos,
             valor_credito AS creditos,
             ((val_ant_deb - val_ant_cre) + valor_debito) - valor_credito AS saldo_final_msc,
             round((CASE
                      WHEN estrutural IN (''622130100000000'', ''622130300000000'', ''622130400000000'') THEN
                 (SELECT (CASE
                            WHEN estrutural = ''622130100000000'' THEN sum(substr(dados_dot, 172, 12) :: float8 - substr(dados_dot, 185, 12) :: float8) - sum(substr(dados_dot, 198, 12) :: float8)
                            WHEN estrutural = ''622130300000000'' THEN sum(substr(dados_dot, 198, 12) :: float8) - sum(substr(dados_dot, 211, 12) :: float8)
                            WHEN estrutural = ''622130400000000'' THEN sum(substr(dados_dot, 211, 12) :: float8)
                     END) AS valor
                  FROM
                       (SELECT fc_dotacaosaldo(2019, o58_coddot, 3, ''#data_inicial#'', ''#data_final#'') AS dados_dot
                        FROM configuracoes.db_config
                               INNER JOIN orcamento.orcelemento ON o56_elemento = elemento
                                                                     AND o56_anousu = 2019
                               INNER JOIN orcamento.orcdotacao ON codigo = o58_instit
                                                                    AND o58_anousu = 2019
                                                                    AND o58_codele = o56_codele
                                                                    AND o58_funcao = funcao :: int
                                                                    AND o58_subfuncao = subfuncao :: int
                                                                    AND o58_codigo = recurso :: int
                        WHERE codtrib = instituicao) AS valor_dotacao)
                      WHEN estrutural IN (''622920101000000'', ''622920103000000'', ''622920104000000'') THEN
                 (SELECT (CASE
                            WHEN estrutural = ''622920101000000'' THEN sum(substr(dados_dot, 172, 12) :: float8 - substr(dados_dot, 185, 12) :: float8) - sum(substr(dados_dot, 198, 12) :: float8)
                            WHEN estrutural = ''622920103000000'' THEN sum(substr(dados_dot, 198, 12) :: float8) - sum(substr(dados_dot, 211, 12) :: float8)
                            WHEN estrutural = ''622920104000000'' THEN sum(substr(dados_dot, 211, 12) :: float8)
                     END) AS valor
                  FROM
                       (SELECT fc_dotacaosaldo(2019, o58_coddot, 3, ''#data_inicial#'', ''#data_final#'') AS dados_dot
                        FROM configuracoes.db_config
                               INNER JOIN orcamento.orcdotacao ON codigo = o58_instit
                                                                    AND o58_anousu = 2019
                        WHERE codtrib = instituicao) AS valor_dotacao)
                 END), 2) AS saldo_validar
      FROM
           (WITH lancamentos AS
           (SELECT c124_sequencial AS codigo,
                   c124_data AS DATA,
                   c124_natureza AS natureza,
                   c124_valor AS valor,
                   c124_lancamento AS codigo_lancamento,
                   c71_coddoc AS documento,
                   c123_reduzido AS reduzido,
                   c60_estrut AS estrutural,
                   c60_descr AS nome_conta,
                   c123_valor AS valor_atributo,
                   c121_sigla AS sigla_atributo,
                   c121_sequencial AS ordem,
                   c124_tipo AS tipo,
                   c53_tipo AS tipo_documento
            FROM infocomplementarvalor
                   INNER JOIN conplanoatributolancamentos ON c124_sequencial = c123_conplanoatributolancamentos
                   INNER JOIN conplanoinfocomplementar ON c121_sequencial = c123_infocomplementar
                   INNER JOIN conplanoreduz ON c61_reduz = c123_reduzido
                                                 AND extract(YEAR
                                                             FROM c124_data) :: int = c61_anousu
                   INNER JOIN conplano ON c61_codcon = c60_codcon
                                            AND c60_anousu = c61_anousu
                   LEFT JOIN conlancam ON c70_codlan = c124_lancamento
                   LEFT JOIN conlancamdoc ON c71_codlan = c70_codlan
                   LEFT JOIN conhistdoc ON c71_coddoc = c53_coddoc
            WHERE c124_data >= ''#data_inicial#''
              AND c124_data <= ''#data_final#''
              AND c123_conplanosistema = 1

              AND c60_estrut IN (''622130100000000'',
                                 ''622130300000000'',
                                 ''622130400000000'',
                                 ''622920101000000'',
                                 ''622920103000000'',
                                 ''622920104000000'')
            ORDER BY c124_sequencial,
                     c71_coddoc,
                     c124_data,
                     c123_reduzido,
                     c121_sequencial,
                     c124_lancamento,
                     c60_estrut),
               conta_corrente AS
             (SELECT codigo,
                     DATA,
                     natureza,
                     valor,
                     codigo_lancamento,
                     reduzido,
                     estrutural,
                     nome_conta,
                     tipo,
                     array_to_string( array_agg(valor_atributo||''#''||sigla_atributo
                                          ORDER BY ordem), ''|'') AS atributos
              FROM lancamentos
              GROUP BY codigo,
                       DATA,
                       natureza,
                       valor,
                       codigo_lancamento,
                       reduzido,
                       estrutural,
                       nome_conta,
                       tipo
              ORDER BY codigo,
                       DATA,
                       natureza,
                       valor,
                       codigo_lancamento,
                       reduzido,
                       estrutural) SELECT estrutural,
                                          nome_conta,
                                          atributos,
                                          substring(atributos, position(''#PO'' IN atributos) - 4, 4) AS instituicao,
                                          substring(atributos, position(''#ND'' IN atributos) - 13, 13) AS elemento,
                                          (CASE
                                             WHEN position(''#FR'' IN atributos) > 0 THEN substring(atributos, position(''#PO'' IN atributos) + 4, position(''#FR'' IN atributos) - 9)
                                             ELSE NULL
                                              END) AS recurso,
                                          substring(atributos, position(''#FS'' IN atributos) - 5, 2) AS funcao,
                                          substring(atributos, position(''#FS'' IN atributos) - 3, 3) AS subfuncao,
                                          round(coalesce(sum(CASE
                                                               WHEN (DATA < ''#data_inicial#''
                                                                       OR tipo = ''1'')
                                                                      AND natureza = ''D'' THEN valor
                                                                 END), 0), 2) AS val_ant_deb,
                                          round(coalesce(sum(CASE
                                                               WHEN (DATA < ''#data_inicial#''
                                                                       OR tipo = ''1'')
                                                                      AND natureza = ''C'' THEN valor
                                                                 END), 0), 2) AS val_ant_cre,
                                          round(coalesce(sum(CASE
                                                               WHEN DATA >= ''#data_inicial#''
                                                                      AND tipo = ''2''
                                                                      AND natureza = ''D'' THEN valor
                                                                 END), 0), 2) AS valor_debito,
                                          round(coalesce(sum(CASE
                                                               WHEN DATA >= ''#data_inicial#''
                                                                      AND tipo = ''2''
                                                                      AND natureza = ''C'' THEN valor
                                                                 END), 0), 2) AS valor_credito
                                   FROM conta_corrente
                                   WHERE DATA BETWEEN ''#data_inicial#'' AND ''#data_final#''

                                   GROUP BY estrutural,
                                            nome_conta,
                                            atributos
                                   ORDER BY estrutural,
                                            atributos) AS x)
         AS w

where abs(w.saldo_final_msc) <> saldo_validar;"
  }
}' );
SQL
        );
    }

    public function down()
    {

        $this->execute("delete from consistenciasistema where db160_json ilike '%5c6acff05123qdqwdx5a5a%'");
    }
}
