<?php

use Classes\PostgresMigration;

class M12821Pad extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
        $this->tabelaUp();
        $this->migrarSistemaContasUp();
        $this->consistenciaUp();
    }

    public function down()
    {
        $this->consistenciaDown();
        $this->tabelaDown();
        $this->migrarSistemaContasDown();
        $this->dicionarioDown();
    }

    private function dicionarioUp()
    {
        $sql = <<<SQL
        INSERT INTO db_syscampo VALUES(1010335,'c65_sigla','char(1)','Sigla que deve ser enviada para o PAD.','', 'Sigla',1,'f','t','f',0,'text','Sigla');
        INSERT INTO db_sysarqcamp VALUES(3269,1010335,3,0);
SQL;
        $this->execute($sql);
    }

    private function migrarSistemaContasUp()
    {
        $sql = <<<SQL
        CREATE TABLE w_m12821_conplano AS 
            SELECT * FROM conplano WHERE c60_consistemaconta = 4;
        UPDATE conplano SET c60_consistemaconta = 3 WHERE c60_consistemaconta = 4;

        CREATE TABLE w_m12821_conplanoorcamento AS 
            SELECT * FROM conplanoorcamento WHERE c60_consistemaconta = 4;
        UPDATE conplanoorcamento SET c60_consistemaconta = 3 WHERE c60_consistemaconta = 4;

        CREATE TABLE w_m12821_planocontadetalhe AS 
            SELECT * FROM planocontadetalhe WHERE c95_sistema = 4;
        UPDATE planocontadetalhe SET c95_sistema = 3 WHERE c95_sistema = 4;

        DELETE FROM consistemaconta WHERE c65_sequencial = 4;

        UPDATE consistemaconta SET c65_sigla = 'N' WHERE c65_sequencial = 0;
        UPDATE consistemaconta SET c65_descricao = 'Orçamentária', c65_sigla = 'O' WHERE c65_sequencial = 1;
        UPDATE consistemaconta SET c65_descricao = 'Patrimonial', c65_sigla = 'P' WHERE c65_sequencial = 2;
        UPDATE consistemaconta SET c65_descricao = 'Controle', c65_sigla = 'C' WHERE c65_sequencial = 3;
        ALTER TABLE consistemaconta ALTER COLUMN c65_sigla SET NOT NULL;

SQL;
        $this->execute($sql);
    }

    private function tabelaUp()
    {
        $sql = <<<SQL
        ALTER TABLE consistemaconta ADD COLUMN c65_sigla character(1);
        UPDATE db_cadattdinamicoatributos SET db109_tipo = 2 WHERE db109_nome = 'cnpjorgaogerenciador';
SQL;
        $this->execute($sql);
    }

    private function dicionarioDown() 
    {
        $sql = <<<SQL
        DELETE FROM db_sysarqcamp WHERE codcam = 1010335;
        DELETE FROM db_syscampo WHERE codcam = 1010335;
SQL;
        $this->execute($sql);
    }

    private function tabelaDown()
    {
        $sql = <<<SQL
        UPDATE db_cadattdinamicoatributos SET db109_tipo = 1 WHERE db109_nome = 'cnpjorgaogerenciador';
        ALTER TABLE consistemaconta DROP COLUMN c65_sigla;
SQL;
        $this->execute($sql);       
    }

    private function migrarSistemaContasDown()
    {
        $sql = <<<SQL
        INSERT INTO consistemaconta VALUES(4, 'Subsistema de Compensação');

        UPDATE consistemaconta SET c65_descricao = 'Subsistema de Informações Orçamentárias' WHERE c65_sequencial = 1;
        UPDATE consistemaconta SET c65_descricao = 'Subsistema de informações Patrimoniais' WHERE c65_sequencial = 2;
        UPDATE consistemaconta SET c65_descricao = 'Subsistema de Compensação' WHERE c65_sequencial = 3;

        UPDATE conplano SET c60_consistemaconta = 4 WHERE c60_codcon in (SELECT c60_codcon FROM w_m12821_conplano);
        UPDATE conplanoorcamento SET c60_consistemaconta = 4 WHERE c60_codcon in (SELECT c60_codcon FROM w_m12821_conplanoorcamento);
        UPDATE planocontadetalhe SET c95_sistema = 4 WHERE c95_sequencial in (SELECT c95_sequencial FROM w_m12821_planocontadetalhe);

        DROP TABLE w_m12821_conplano;
        DROP TABLE w_m12821_conplanoorcamento;
        DROP TABLE w_m12821_planocontadetalhe;
SQL;
        $this->execute($sql);
    }

    private function consistenciaUp()
    {
        $sql = <<<SQL
            INSERT INTO consistenciasistema
            VALUES (nextval('consistenciasistema_db160_sequencial_seq'),
                    1,
                    '{
                      "tipo": 1,
                      "nome": "Superávit Financeiro",
                      "descricao": "Tipo de contas analíticas que não aplicam indicador superávit",
                      "formulario": {
                        "campos": [
                          {
                            "propriedade": "codigo_plano_contas",
                            "nome": "Plano de Contas",
                            "chave_primaria" : true
                          },
                          {
                            "propriedade": "estrutural",
                            "nome": "Estrutural"
                          },
                          {
                            "propriedade": "titulo",
                            "nome": "Título"
                          },
                          {
                            "propriedade": "identificador",
                            "nome": "Indicador Superávit",
                            "tipo": "select",
                            "nulo": false,
                            "opcoes": [
                                {
                                    "codigo" : "F",
                                    "descricao" : "F - Financeiro" 
                                },
                                {
                                    "codigo" : "P",
                                    "descricao" : "P - Permanente" 
                                }
                            ]
                          }
                        ]
                      },
                      "sql": {
                        "consistencia": "SELECT DISTINCT c60_codcon AS codigo_plano_contas, \'\' as identificador, c60_descr AS titulo, c60_estrut as estrutural FROM conplano INNER JOIN conplanoreduz ON conplanoreduz.c61_codcon = conplano.c60_codcon AND conplanoreduz.c61_anousu = conplano.c60_anousu WHERE c60_consistemaconta = 2 AND c60_identificadorfinanceiro = \'N\' AND c60_anousu = fc_getsession(\'DB_anousu\')::int AND c61_instit = fc_getsession(\'DB_instit\')::int ORDER BY 1;",
                        "correcao": "UPDATE conplano SET c60_identificadorfinanceiro = \'[identificador]\' WHERE c60_codcon = [codigo_plano_contas] AND c60_anousu >= fc_getsession(\'DB_anousu\')::int;"
                      }
                    }'
            );
SQL;
        $this->execute($sql);        
    }
    private function consistenciaDown()
    {
        $sql = <<<SQL
            DELETE FROM consistenciasistema WHERE db160_json ilike '%"nome": "Superávit Financeiro"%';
SQL;
        $this->execute($sql);    
    }
}
