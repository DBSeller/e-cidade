<?php

use Classes\PostgresMigration;

class M14516FcGeraComentariosDicionario extends PostgresMigration
{
        /**
         *
         * Adiciona novas PLs (fc_gera_comentarios_dicionario_{esquema|tabela|coluna})
         * responsáveis por gerar comentários em formato JSON (COMMENTs do PostgreSQL)
         * com informações do Dicionário de Dados do e-cidade
         */
        public function up()
        {
           $sSql = <<<SQL_UP
                /*
                *
                * fc_gera_comentarios_dicionario_esquema(text)
                *
                *   . responsável por gerar COMMENTs nos 'schemas' do e-cidade baseado no dicionário de dados, mais especificamente
                *     apartir da tabela 'db_sysmodulo'
                *
                * Parâmetros;
                *  $1 - nome do esquema ou expressão regular para buscar do PostgreSQL, caso use NULL então irá gerar comentário
                *       para TODOS esquemas da base de dados.
                *
                *  Exemplos:
                *     SELECT fc_gera_comentarios_dicionario_esquema(); -- Todos esquemas
                *     SELECT fc_gera_comentarios_dicionario_esquema('caixa'); -- Apenas esquema 'caixa'
                *     SELECT fc_gera_comentarios_dicionario_esquema('^c'); -- Todos esquemas que começam com as letras 'c'
                *
                */
               CREATE OR REPLACE FUNCTION fc_gera_comentarios_dicionario_esquema(text DEFAULT NULL)
               RETURNS void AS
               $$
               DECLARE
                   r	RECORD;
               BEGIN
                   FOR r IN EXECUTE
                       format(
                           \$SQL_QQ$
                               WITH schemas AS (
                                   SELECT  regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g') AS schema_name,
                                           descricao AS description
                                   FROM    configuracoes.db_sysmodulo
                                   WHERE   EXISTS (SELECT	1
                                                   FROM	information_schema.schemata
                                                   WHERE	schema_name = regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g'))
                               )
                            SELECT * FROM schemas WHERE schema_name ~ %L;
                           \$SQL_QQ$,
                           COALESCE($1, '.*')
                       )
                   LOOP
                       EXECUTE format('COMMENT ON SCHEMA %I IS %L;', r.schema_name, r.description);
                   END LOOP;

                   RETURN;
               END;
               $$
               LANGUAGE plpgsql;


               /*
                *
                * fc_gera_comentarios_dicionario_tabela(text)
                *
                *   . responsável por gerar COMMENTs nas 'tabelas' do e-cidade baseado no dicionário de dados, mais especificamente
                *     apartir das tabelas 'db_sysarquivo', 'db_sysarqmod' e 'db_sysmodulo'
                *
                * Parâmetros;
                *  $1 - nome do esquema ou expressão regular para buscar do PostgreSQL (NULL irá processar todos esquemas).
                *  $2 - nome da tabela ou expressão regular para buscar do PostgreSQL, (NULL irá gerar comentário para TODAS tabelas).
                *
                *  Exemplos:
                *     SELECT fc_gera_comentarios_dicionario_tabela(); -- Todos esquemas e tabelas
                *     SELECT fc_gera_comentarios_dicionario_tabela('caixa'); -- Todas tabelas do esquema 'caixa'
                *     SELECT fc_gera_comentarios_dicionario_tabela('caixa', '^a'); -- Todas tabelas que começam com 'a' do esquema 'caixa'
                *
                */
               CREATE OR REPLACE FUNCTION fc_gera_comentarios_dicionario_tabela(text DEFAULT NULL, text DEFAULT NULL)
               RETURNS void AS
               $$
               DECLARE
                   r	RECORD;
               BEGIN
                   FOR r IN EXECUTE
                       format(
                        \$SQL_QQ$
                               WITH tables AS (
                                   SELECT  regexp_replace(lower(to_ascii(db_sysmodulo.nomemod)), '[^A-Za-z]' , '', 'g') AS table_schema,
                                           regexp_replace(lower(to_ascii(db_sysarquivo.nomearq)), '[^A-Za-z_]' , '', 'g') AS table_name,
                                           db_sysarquivo.descricao AS description
                                   FROM    db_sysarquivo
                                           JOIN db_sysarqmod ON db_sysarqmod.codarq = db_sysarquivo.codarq
                                           JOIN db_sysmodulo ON db_sysmodulo.codmod = db_sysarqmod.codmod
                                   WHERE   EXISTS (SELECT 1
                                                   FROM   information_schema.tables
                                                   WHERE  table_schema = regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g')
                                                   AND    table_name   = regexp_replace(lower(to_ascii(db_sysarquivo.nomearq)), '[^A-Za-z]' , '', 'g'))
                               )
                               SELECT * FROM tables WHERE table_schema ~ %L AND table_name ~ %L;
                        \$SQL_QQ$,
                           COALESCE($1, '.*'),
                           COALESCE($2, '.*')
                       )
                   LOOP
                       EXECUTE format('COMMENT ON TABLE %I.%I IS %L;', r.table_schema, r.table_name, r.description);
                   END LOOP;

                   RETURN;
               END;
               $$
               LANGUAGE plpgsql;

               /*
                *
                * fc_gera_comentarios_dicionario_coluna(text)
                *
                *   . responsável por gerar COMMENTs nas 'colunas' do e-cidade baseado no dicionário de dados, mais especificamente
                *     apartir das tabelas 'db_syscampo', 'db_sysarqcamp', 'db_sysarquivo', 'db_sysarqmod' e 'db_sysmodulo'
                *
                * Parâmetros;
                *  $1 - nome do esquema ou expressão regular para buscar do PostgreSQL (NULL irá processar todos esquemas).
                *  $2 - nome da tabela ou expressão regular para buscar do PostgreSQL, (NULL irá gerar comentário para TODAS tabelas).
                *  $3 - nome da coluna ou expressão regular para buscar do PostgreSQL, (NULL irá gerar comentário para TODAS colunas).
                *
                *  Exemplos:
                *     SELECT fc_gera_comentarios_dicionario_coluna(); -- Todos esquemas, tabelas e colunas
                *     SELECT fc_gera_comentarios_dicionario_coluna('caixa'); -- Todas colunas de todas tabelas do esquema 'caixa'
                *     SELECT fc_gera_comentarios_dicionario_coluna('caixa', 'arrecad'); -- Todas colunas da tabela 'arrecad' do esquema 'caixa'
                *     SELECT fc_gera_comentarios_dicionario_coluna('caixa', 'arrecad', 'k00_numpre'); -- Coluna 'k00_numpre' da tabela 'arrecad' do esquema 'caixa'
                *     SELECT fc_gera_comentarios_dicionario_coluna('caixa', 'arrecad', '^k00'); -- Todas colunas que começam com 'k00' da tabela 'arrecad' do esquema 'caixa'
                *     SELECT fc_gera_comentarios_dicionario_coluna('caixa', '^a'); -- Todas colunas das tabelas que começam com 'a' do esquema 'caixa'
                *
                */
               CREATE OR REPLACE FUNCTION fc_gera_comentarios_dicionario_coluna(text DEFAULT NULL, text DEFAULT NULL, text DEFAULT NULL)
               RETURNS void AS
               $$
               DECLARE
                   r	RECORD;
               BEGIN
                   FOR r IN EXECUTE
                       format(
                        \$SQL_QQ$
                               WITH columns AS (
                                   SELECT	regexp_replace(lower(to_ascii(db_sysmodulo.nomemod)), '[^A-Za-z]' , '', 'g') AS table_schema,
                                           regexp_replace(lower(to_ascii(db_sysarquivo.nomearq)), '[^A-Za-z_]' , '', 'g') AS table_name,
                                           regexp_replace(lower(to_ascii(db_syscampo.nomecam)), '[^A-Za-z0-9_]' , '', 'g') AS column_name,
                                           db_syscampo.descricao AS description
                                   FROM	db_sysarquivo
                                           JOIN db_sysarqmod  ON db_sysarqmod.codarq  = db_sysarquivo.codarq
                                           JOIN db_sysmodulo  ON db_sysmodulo.codmod  = db_sysarqmod.codmod
                                           JOIN db_sysarqcamp ON db_sysarqcamp.codarq = db_sysarquivo.codarq
                                           JOIN db_syscampo   ON db_syscampo.codcam   = db_sysarqcamp.codcam
                                   WHERE   EXISTS (SELECT 1
                                                   FROM   information_schema.columns
                                                   WHERE  table_schema = regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g')
                                                   AND    table_name   = regexp_replace(lower(to_ascii(db_sysarquivo.nomearq)), '[^A-Za-z_]' , '', 'g')
                                                   AND    column_name  = regexp_replace(lower(to_ascii(db_syscampo.nomecam)), '[^A-Za-z0-9_]' , '', 'g'))
                               )
                               SELECT * FROM columns WHERE table_schema ~ %L AND table_name ~ %L AND column_name ~ %L;
                        \$SQL_QQ$,
                           COALESCE($1, '.*'),
                           COALESCE($2, '.*'),
                           COALESCE($3, '.*')
                       )
                   LOOP
                       EXECUTE format('COMMENT ON COLUMN %I.%I.%I IS %L;', r.table_schema, r.table_name, r.column_name, r.description);
                   END LOOP;

                   RETURN;
               END;
               $$
               LANGUAGE 'plpgsql';
SQL_UP;

        /**
         *  Executa as PLs que criam (fc_gera_comentarios_dicionario_{esquema|tabela|coluna})
         */
        $this->execute($sSql);

        /**
         * responsável por gerar COMMENTs nos 'schemas'
         */

        $this->execute("
           SELECT fc_gera_comentarios_dicionario_esquema('farmacia');
           SELECT fc_gera_comentarios_dicionario_esquema('material');
           SELECT fc_gera_comentarios_dicionario_esquema('compras');
           SELECT fc_gera_comentarios_dicionario_esquema('vacinas');
           SELECT fc_gera_comentarios_dicionario_esquema('ambulatorial');
           SELECT fc_gera_comentarios_dicionario_esquema('patrimonio');
           SELECT fc_gera_comentarios_dicionario_esquema('ouvidoria');
           SELECT fc_gera_comentarios_dicionario_esquema('agendamento');
           SELECT fc_gera_comentarios_dicionario_esquema('protocolo');
           SELECT fc_gera_comentarios_dicionario_esquema('tfd');
           SELECT fc_gera_comentarios_dicionario_esquema('veiculos');
        ");


        }

        public function down()
        {
        $this->execute("
            DROP FUNCTION IF EXISTS fc_gera_comentarios_dicionario_coluna();
            DROP FUNCTION IF EXISTS fc_gera_comentarios_dicionario_tabela();
            DROP FUNCTION IF EXISTS fc_gera_comentarios_dicionario_esquema();
        ");
        }
}
