<?php

use Classes\PostgresMigration;

class M17992AuditoriaAdicionaFila extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL_UP
        /*
        * $1 = id_acount apartir da contagem pra gerar mapas de migracao (NULL apartir inicio) 
        * $2 = tamanho do bloco do mapa de migracao (padrao usado = 1000)
        *
        */
       DROP FUNCTION IF EXISTS configuracoes.fc_auditoria_adiciona_acount_fila(INTEGER, INTEGER);
       CREATE FUNCTION configuracoes.fc_auditoria_adiciona_acount_fila(INTEGER, INTEGER)
       RETURNS void
       AS $$
           /* Controle de concorrencia para evitar execucoes simultaneas */
           SELECT pg_advisory_xact_lock(-123456789);
       
           /* Salva o proximo valor da sequence para ser usada posteriormente nos UPDATEs */
           SELECT fc_putsession('configuracoes.db_auditoria_migracao_sequencial_seq',
               (SELECT last_value+1 FROM configuracoes.db_auditoria_migracao_sequencial_seq)::text);
       
           /* Gera e insere os lotes de acordo com o tamanho passado por parametro */
           INSERT	INTO configuracoes.db_auditoria_migracao (sequencial, id_acount_ini, id_acount_fim, status)
           SELECT	NEXTVAL('db_auditoria_migracao_sequencial_seq'),
                   minimo + (soma * id) - soma + (
                   CASE
                       WHEN id = 1
                           THEN 0
                           ELSE 1
                       END
                   ) AS id_acount_ini,
                   CASE
                       WHEN (minimo + (soma * id)) > maximo
                       THEN maximo
                       ELSE (minimo + (soma * id))
                   END AS id_acount_fim,
                   cast('NAO INICIADO' AS TEXT)
           FROM 	(SELECT	(SELECT	min(id_acount)
                            FROM	ONLY db_acount
                            WHERE	id_acount > coalesce($1, 0)) AS minimo,
                           (SELECT	max(id_acount)
                            FROM	ONLY db_acount
                            WHERE	id_acount > coalesce($1, 0)) AS maximo,
                           id,
                           $2 AS soma
                    FROM	generate_series(1, (
                               SELECT	ceil((max(id_acount) - min(id_acount) + 1) / $2::float8)
                               FROM	ONLY db_acount
                               WHERE	id_acount > coalesce($1, 0)
                           )::integer) AS id LIMIT 10) AS x
           WHERE (minimo+maximo) > 0;
       
           /* Finaliza LOTES que por acaso nao existam nenhum registro na db_acount */
           UPDATE 	db_auditoria_migracao
           SET 	status = 'FINALIZADO',
                   inicio = now(),
                   fim = clock_timestamp(),
                   registros_processados = 0,
                   observacoes = 'LOTE DESCARTADO PELA PL fc_auditoria_adiciona_acount_fila() POIS NAO EXISTE NENHUM db_acount PARA MIGRAR'
           WHERE 	sequencial >= fc_getsession('configuracoes.db_auditoria_migracao_sequencial_seq')::integer
           AND 	status = 'NAO INICIADO'
           AND 	NOT EXISTS (SELECT 1 FROM db_acount WHERE id_acount BETWEEN id_acount_ini AND id_acount_fim);
       
           /* Seta Data/Hora inicio e fim dos lotes */
           UPDATE	db_auditoria_migracao
           SET		datahora_ini = COALESCE((dhi).datahora_ini, NOW() - interval '6 months'),
                   datahora_fim = COALESCE((dhi).datahora_fim, NOW()),
                   instit       = COALESCE((dhi).instit, (SELECT array_agg(codigo) FROM db_config))
           FROM	(SELECT	sequencial,
                           fc_auditoria_busca_datahora_e_instit((current_date - interval '6 months')::date, id_acount_ini, id_acount_fim) AS dhi
                    FROM	db_auditoria_migracao
                    WHERE	sequencial >= fc_getsession('configuracoes.db_auditoria_migracao_sequencial_seq')::integer
                    AND 	status = 'NAO INICIADO') AS x
           WHERE	db_auditoria_migracao.sequencial = x.sequencial;
       $$
       LANGUAGE sql;
       
       /* Adiciona na fila de migracao apartir do ultimo ID_ACOUNT inserido na mesma */
       DROP FUNCTION IF EXISTS configuracoes.fc_auditoria_adiciona_acount_fila();
       CREATE FUNCTION configuracoes.fc_auditoria_adiciona_acount_fila()
       RETURNS void
       AS $$
           SELECT configuracoes.fc_auditoria_adiciona_acount_fila(
                       (SELECT	id_acount_fim
                        FROM	configuracoes.db_auditoria_migracao
                        ORDER	BY id_acount_fim DESC
                        LIMIT	1), 1000 );
       $$
       LANGUAGE sql;
       
       /* Adiciona na fila de migracao TODOS existentes na DB_ACOUNT */
       DROP FUNCTION IF EXISTS configuracoes.fc_auditoria_adiciona_todos_acount_fila();
       CREATE FUNCTION configuracoes.fc_auditoria_adiciona_todos_acount_fila()
       RETURNS void
       AS $$
           SELECT configuracoes.fc_auditoria_adiciona_acount_fila(NULL, 1000);
       $$
       LANGUAGE sql;
       
SQL_UP;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL_DOWN
        /*
        * $1 = id_acount apartir da contagem pra gerar mapas de migracao (NULL apartir inicio) 
        * $2 = tamanho do bloco do mapa de migracao (padrao usado = 1000)
        *
        */
       CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_adiciona_acount_fila(INTEGER, INTEGER)
       RETURNS void
       AS $$
           /* Controle de concorrencia para evitar execucoes simultaneas */
           SELECT pg_advisory_xact_lock(-123456789);
       
           SELECT fc_putsession('configuracoes.db_auditoria_migracao_sequencial_seq',
               (SELECT last_value+1 FROM configuracoes.db_auditoria_migracao_sequencial_seq)::text);
       
           INSERT	INTO configuracoes.db_auditoria_migracao (sequencial, id_acount_ini, id_acount_fim, status)
           SELECT	NEXTVAL('db_auditoria_migracao_sequencial_seq'),
                   minimo + (soma * id) - soma + (
                   CASE
                       WHEN id = 1
                           THEN 0
                           ELSE 1
                       END
                   ) AS id_acount_ini,
                   CASE
                       WHEN (minimo + (soma * id)) > maximo
                       THEN maximo
                       ELSE (minimo + (soma * id))
                   END AS id_acount_fim,
                   cast('NAO INICIADO' AS TEXT)
           FROM 	(SELECT	(SELECT	min(id_acount)
                            FROM	ONLY db_acount
                            WHERE	id_acount > coalesce($1, 0)) AS minimo,
                           (SELECT	max(id_acount)
                            FROM	ONLY db_acount
                            WHERE	id_acount > coalesce($1, 0)) AS maximo,
                           id,
                           $2 AS soma
                    FROM	generate_series(1, (
                               SELECT	ceil((max(id_acount) - min(id_acount) + 1) / $2::float8)
                               FROM	ONLY db_acount
                               WHERE	id_acount > coalesce($1, 0)
                           )::integer) AS id LIMIT 10) AS x
           WHERE (minimo+maximo) > 0;
       
           UPDATE	db_auditoria_migracao
           SET		datahora_ini = COALESCE((dhi).datahora_ini, NOW() - interval '6 months'),
                   datahora_fim = COALESCE((dhi).datahora_fim, NOW()),
                   instit       = COALESCE((dhi).instit, (SELECT array_agg(codigo) FROM db_config))
           FROM	(SELECT	sequencial,
                           fc_auditoria_busca_datahora_e_instit((current_date - interval '6 months')::date, id_acount_ini, id_acount_fim) AS dhi
                    FROM	db_auditoria_migracao
                    WHERE	sequencial >= fc_getsession('configuracoes.db_auditoria_migracao_sequencial_seq')::integer) AS x
           WHERE	db_auditoria_migracao.sequencial = x.sequencial;
       $$
       LANGUAGE sql;
       
       DROP FUNCTION IF EXISTS configuracoes.fc_auditoria_adiciona_acount_fila();
       CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_adiciona_acount_fila()
       RETURNS void
       AS $$
           SELECT configuracoes.fc_auditoria_adiciona_acount_fila(
                       (SELECT	id_acount_fim
                        FROM	configuracoes.db_auditoria_migracao
                        ORDER	BY id_acount_fim DESC
                        LIMIT	1), 1000 );
       $$
       LANGUAGE sql;
       
       CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_adiciona_todos_acount_fila()
       RETURNS void
       AS $$
           SELECT configuracoes.fc_auditoria_adiciona_acount_fila(NULL, 1000);
       $$
       LANGUAGE sql;
       
SQL_DOWN;
    
        $this->execute($sql);
    }

}
