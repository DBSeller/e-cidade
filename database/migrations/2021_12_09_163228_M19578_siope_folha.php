<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M19578SiopeFolha extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dicionarioUp();
        DB::connection()->getPdo()->exec(<<<SQL
           
          /*
           * fc_hash_int(text, integer)
           *
           *   . responsavel por gerar um hash em INT4 apartir de um text
           *   . se usar 32, o retorno deve ser bigint, senao ira retornar
           *   . numeros negativos, devera ser alterado a funcao
           *
           * Parametros:
           *  $1 - texto a ser processado.
           *  $2 - quantidade de bits para cálculo (default: 32)
           *
           * Retorno:
           *  INT4 - hash gerado apartir do algoritmo
           *
           * Referencias:
           *   https://stackoverflow.com/questions/9809381/hashing-a-string-to-a-numeric-value-in-postgresql
           *   https://stackoverflow.com/questions/8316164/convert-hex-in-text-representation-to-decimal-number/8316731#8316731
           *
           */
          CREATE OR REPLACE FUNCTION fc_hash_int(text, integer DEFAULT 32) RETURNS integer AS
          $$
          DECLARE
          	hash_int INTEGER;
          BEGIN
          	EXECUTE format(E'SELECT (\'x\'||substr(md5(%L),1,8))::bit(%s)::int;', $1, $2)
          	INTO 	hash_int;
          
          	RETURN hash_int;
          END;
          $$
          LANGUAGE plpgsql;
          
          /*
           *
           * fc_dicionario_salt()
           *
           *   . retorna o 'salt' para ser usado na geração do hash dos IDs do dicionário
           *
           */
          CREATE OR REPLACE FUNCTION fc_dicionario_salt()
          RETURNS INTEGER AS
          $$
          	SELECT 50000000;
          $$
          LANGUAGE sql IMMUTABLE;
          
          /*
           *
           * fc_remove_dicionario_tabela(text, text)
           *
           *   . responsavel por limpar dicionario de dados (tabelas db_sys*) apartir de um DROP TABLE
           *
           * Parametros:
           *  $1 - nome do esquema para buscar do PostgreSQL.
           *  $2 - nome da tabela para buscar do PostgreSQL.
           *
           *  Exemplos:
           *     SELECT fc_remove_dicionario_tabela('caixa', 'arrecad'); -- Tabela caixa.arrecad
           *
           */
          CREATE OR REPLACE FUNCTION fc_remove_dicionario_tabela(text, text)
          RETURNS void AS
          $$
          DECLARE
          	sysarquivo 		INTEGER;
          	sysmodulo 		INTEGER;
          	syscampos 		INTEGER[];
          	relid			REGCLASS;
          BEGIN
          	-- Salva o "relation id" do catálogo do PostgreSQL
          	relid := format('%I.%I', $1, $2)::regclass;
          
          	SELECT 	a.codarq
          	INTO 	sysarquivo
          	FROM	configuracoes.db_sysarquivo a
          			JOIN configuracoes.db_sysarqmod am ON am.codarq = a.codarq
          			JOIN configuracoes.db_sysmodulo m  ON am.codmod = m.codmod
          	WHERE 	regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g') = $1
          	AND 	a.nomearq = $2;
          
          	IF sysarquivo IS NULL THEN
          		RAISE INFO 'Tabela %.% não encontrada no dicionário de dados!', $1, $2;
          	END IF;
          
          	RAISE DEBUG 'sysarquivo: %', sysarquivo;
          
          	SELECT 	codmod
          	INTO 	sysmodulo
          	FROM 	configuracoes.db_sysmodulo
          	WHERE 	regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g') = $1;
          
          	SELECT 	array_agg(codcam)
          	INTO 	syscampos
          	FROM 	configuracoes.db_sysarqcamp
          	WHERE 	codarq = sysarquivo;
          
          	-- 0) Cria tabelas temporárias para salvar conteúdo anterior
          	DROP TABLE IF EXISTS tmp_sysarquivo;
          	DROP TABLE IF EXISTS tmp_syscampo;
          	DROP TABLE IF EXISTS tmp_syscampodef;
          	DROP TABLE IF EXISTS tmp_syscampodep;
          
          	CREATE TEMP TABLE tmp_sysarquivo AS
          		SELECT *, pg_catalog.obj_description(relid, 'pg_class') AS comentario
          		FROM db_sysarquivo WHERE codarq = sysarquivo;
          
          	CREATE TEMP TABLE tmp_syscampo AS
          		SELECT *, pg_catalog.col_description(relid, (SELECT attnum FROM pg_attribute WHERE attrelid = relid AND attname = nomecam)) AS comentario
          		FROM db_syscampo WHERE codcam = ANY(syscampos);
          
          	CREATE TEMP TABLE tmp_syscampodef AS
          		SELECT db_syscampodef.*,
          		       pg_catalog.col_description(relid, (SELECT attnum FROM pg_attribute WHERE attrelid = relid AND attname = nomecam)) AS comentario
          		FROM db_syscampodef INNER JOIN db_syscampo ON db_syscampo.codcam = db_syscampodef.codcam
          		WHERE db_syscampodef.codcam = ANY(syscampos);
          
          	CREATE TEMP TABLE tmp_syscampodep AS
          		SELECT db_syscampodep.*,
          		       pg_catalog.col_description(relid, (SELECT attnum FROM pg_attribute WHERE attrelid = relid AND attname = nomecam)) AS comentario
          		FROM db_syscampodep INNER JOIN db_syscampo ON db_syscampo.codcam = db_syscampodep.codcam
          		WHERE db_syscampodep.codcam = ANY(syscampos);
          
          	-- 1) Remove tudo
          	DELETE FROM configuracoes.db_sysarqmod WHERE codarq = sysarquivo AND codmod = sysmodulo;
          	DELETE FROM configuracoes.db_sysforkey WHERE codarq = sysarquivo;
          	DELETE FROM configuracoes.db_sysprikey WHERE codarq = sysarquivo;
          	DELETE FROM configuracoes.db_sysarqcamp WHERE codarq = sysarquivo AND codcam = ANY(syscampos);
          	DELETE FROM configuracoes.db_syscampodef WHERE codcam = ANY(syscampos);
          	DELETE FROM configuracoes.db_syscampodep WHERE codcam = ANY(syscampos);
          	DELETE FROM configuracoes.db_syscampo c WHERE c.codcam = ANY(syscampos)
          	AND NOT EXISTS (SELECT 1 FROM configuracoes.db_sysarqcamp ac WHERE ac.codcam = c.codcam AND ac.codarq IS DISTINCT FROM sysarquivo);
          
          	DELETE FROM configuracoes.db_sysarquivo WHERE codarq = sysarquivo;
          
              RETURN;
          END;
          $$
          LANGUAGE plpgsql;
          
          /*
           *
           * fc_gera_dicionario_apartir_tabela(text, text)
           *
           *   . responsavel por gerar dicionario de dados (tabelas db_sys*) apartir de uma tabela existente no PostgreSQL
           *
           * Parametros:
           *  $1 - nome do esquema para buscar do PostgreSQL.
           *  $2 - nome da tabela para buscar do PostgreSQL.
           *
           *  Exemplos:
           *     SELECT fc_gera_dicionario_apartir_tabela('caixa', 'arrecad'); -- Tabela caixa.arrecad
           *
           */
          CREATE OR REPLACE FUNCTION fc_gera_dicionario_apartir_tabela(text, text)
          RETURNS void AS
          $$
          DECLARE
          	sysarquivo 		INTEGER;
          	sysarquivofk	INTEGER;
          	syscampo 		INTEGER;
          	sysmodulo 		INTEGER;
          	syssequencia 	INTEGER;
          	r 				RECORD;
          	relid			REGCLASS;
          
              tDescricao       TEXT;
              sRotulo          VARCHAR;
              bMaiusculo       BOOLEAN;
              bAutocompl       BOOLEAN;
              iAceitatipo      INTEGER;
              sTipoobj         VARCHAR;
              sRotulorel       VARCHAR;
          BEGIN
          	SELECT 	codmod
          	INTO 	sysmodulo
          	FROM 	configuracoes.db_sysmodulo
          	WHERE 	regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g') = $1;
          
              -- Salva o "relation id" do catálogo do PostgreSQL
          	relid := format('%I.%I', $1, $2)::regclass;
          
          	-- 0) Remove tudo
          	PERFORM fc_remove_dicionario_tabela($1, $2);
          
          	SELECT 	codarq
          	INTO 	sysarquivo
          	FROM	tmp_sysarquivo
          	WHERE 	nomearq = $2;
          
          	IF sysarquivo IS NULL THEN
          		sysarquivo := fc_hash_int($2, 28) + fc_dicionario_salt();
          	END IF;
          
          	-- 1) db_sysarquivo
          	INSERT INTO configuracoes.db_sysarquivo(codarq, nomearq)
          	VALUES (sysarquivo, $2);
          
          	-- 2) db_sysarqmod
          	INSERT INTO configuracoes.db_sysarqmod(codmod, codarq)
          	VALUES (sysmodulo, sysarquivo);
          
          	-- 3) db_syscampo, db_syssequencia, db_sysarqcamp
          	FOR r IN
          		SELECT 	columns.table_catalog,
          				columns.table_schema,
          				columns.table_name,
          				columns.column_name,
          				columns.column_default,
          				columns.udt_name,
          				(columns.is_nullable='YES') AS is_nullable,
          				columns.character_maximum_length AS size,
          				columns.ordinal_position AS position,
          				sequences.sequence_name,
          				sequences.increment::integer AS increment,
          				sequences.start_value::integer AS start_value,
          				sequences.minimum_value::integer AS minimum_value,
          				sequences.maximum_value::bigint AS maximum_value,
          				kc.constraint_catalog,
          				kc.constraint_schema,
          				kc.constraint_name,
          				kc.ordinal_position AS position_in_constraint,
          				kc.position_in_unique_constraint,
          				(SELECT 	array_agg(constraint_type::text)
          				 FROM 		information_schema.table_constraints tc
          				 WHERE 		tc.constraint_catalog = kc.constraint_catalog
          				 AND 		tc.constraint_schema = kc.constraint_schema
          				 AND 		tc.constraint_name = kc.constraint_name) AS constraint_type
          				
          		FROM 	information_schema.columns
          				LEFT JOIN information_schema.sequences 	ON  sequences.sequence_catalog = columns.table_catalog
          														AND sequences.sequence_schema = columns.table_schema
          														AND format('nextval(%L::regclass)', sequences.sequence_name) = columns.column_default
          
          				LEFT JOIN information_schema.key_column_usage kc			ON 	kc.table_catalog = columns.table_catalog
          																			AND kc.table_schema = columns.table_schema
          																			AND	kc.table_name = columns.table_name
          																			AND kc.column_name = columns.column_name
          		WHERE 	columns.table_schema = $1
          		AND 	columns.table_name = $2
          	LOOP
          		-- 3.1) db_syscampo
          		SELECT 	codcam
          		INTO 	syscampo
          		FROM	tmp_syscampo
          		WHERE 	nomecam = r.column_name;
          
                  -- Implementado essa verificação, pois pode haver o mesmo campo em mais de 1 tabela
          		IF syscampo IS NULL THEN
                     SELECT codcam
                     INTO   syscampo
                     FROM	db_syscampo
                     WHERE nomecam = r.column_name;
          
                     INSERT INTO tmp_syscampo
                     SELECT *, pg_catalog.col_description(relid, (SELECT attnum FROM pg_attribute WHERE attrelid = relid AND attname = nomecam)) AS comentario
                     FROM db_syscampo WHERE nomecam = r.column_name;
          
          		END IF;
          
          		IF syscampo IS NULL THEN
          			syscampo := fc_hash_int(r.column_name, 28) + fc_dicionario_salt();
          		END IF;
          
                  SELECT coalesce(descricao, ''),
                         coalesce(rotulo, ''),
                         coalesce(maiusculo, false),
                         coalesce(autocompl, false),
                         coalesce(aceitatipo, 0),
                         coalesce(tipoobj, ''),
                         coalesce(rotulorel, '')
                    INTO tDescricao,
                         sRotulo,
                         bMaiusculo,
                         bAutocompl,
                         iAceitatipo,
                         sTipoobj,
                         sRotulorel
                  FROM tmp_syscampo
          		WHERE codcam = syscampo
          		  AND (comentario = '' OR comentario IS NULL);
          
          		IF NOT FOUND THEN
                     tDescricao  = '';
                     sRotulo     = '';
                     bMaiusculo  = false;
                     bAutocompl  = false;
                     iAceitatipo = 0;
                     sTipoobj    = '';
                     sRotulorel  = '';
                  END IF;
          
          		INSERT INTO configuracoes.db_syscampo(codcam, nomecam, conteudo, nulo, tamanho, valorinicial, 
          		                                      descricao, rotulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
          		VALUES (syscampo, r.column_name, r.udt_name || coalesce('('||r.size||')', ''), r.is_nullable, r.size, r.column_default,
          		        tDescricao, sRotulo, bMaiusculo, bAutocompl, iAceitatipo, sTipoobj, sRotulorel)
          		ON CONFLICT (nomecam)
          			DO UPDATE SET conteudo = EXCLUDED.conteudo, nulo = EXCLUDED.nulo, tamanho = EXCLUDED.tamanho;
          		
          		-- 3.2) db_syssequencia
          		IF r.sequence_name IS NOT NULL THEN
          			syssequencia := fc_hash_int(r.sequence_name, 28) + fc_dicionario_salt();
          
          			INSERT INTO configuracoes.db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq)
          			VALUES (syssequencia, r.sequence_name, r.increment, r.minimum_value, r.maximum_value, r.start_value)
          			ON CONFLICT (nomesequencia)
          				DO UPDATE SET
          					incrseq = EXCLUDED.incrseq, minvalueseq = EXCLUDED.minvalueseq,
          					maxvalueseq = EXCLUDED.maxvalueseq, startseq = EXCLUDED.startseq;
          		ELSE
          			syssequencia := NULL;
          		END IF;
          
          		-- 3.3) db_sysarqcamp
          		INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
          		VALUES (sysarquivo, syscampo, r.position, syssequencia)
          		ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk
          			DO NOTHING;
          
          		-- 3.4) db_sysprikey
          		IF 'PRIMARY KEY' = ANY(r.constraint_type) THEN
          			INSERT INTO configuracoes.db_sysprikey (codarq, codcam, sequen, camiden)
          			VALUES (sysarquivo, syscampo, r.position_in_constraint, syscampo);
          		END IF;
          
          		-- 3.5) db_sysforkey
          		IF 'FOREIGN KEY' = ANY(r.constraint_type) THEN
          			SELECT 	a.codarq
          			INTO 	sysarquivofk
          			FROM 	information_schema.referential_constraints rc
          					JOIN information_schema.table_constraints tc 	ON  tc.constraint_catalog = rc.unique_constraint_catalog
          																	AND tc.constraint_schema = rc.unique_constraint_schema
          																	AND tc.constraint_name = rc.unique_constraint_name
          
          					JOIN configuracoes.db_sysmodulo m ON regexp_replace(lower(to_ascii(m.nomemod)), '[^A-Za-z]' , '', 'g') = tc.table_schema
          					JOIN configuracoes.db_sysarquivo a ON a.nomearq = tc.table_name
          					JOIN configuracoes.db_sysarqmod am ON am.codmod = m.codmod AND am.codarq = a.codarq
          
          			WHERE 	rc.constraint_catalog = r.constraint_catalog
          			AND 	rc.constraint_schema = r.constraint_schema
          			AND 	rc.constraint_name = r.constraint_name;
          
          			IF sysarquivofk IS NOT NULL THEN
          				INSERT INTO configuracoes.db_sysforkey(codarq, codcam, sequen, referen)
          				VALUES (sysarquivo, syscampo, r.position_in_constraint, sysarquivofk);
          			END IF;
          		ELSE
          			sysarquivofk := NULL;
          		END IF;
          
          		-- 3.6) Atualiza metadados no dicionario da COLUNA apartir do comentario salvo
          		PERFORM fc_atualiza_dicionario_apartir_comentario('table column', format('%I.%I.%I', $1, $2, r.column_name), comentario)
          		FROM 	tmp_syscampo
          		WHERE 	nomecam = r.column_name
          		AND 	comentario IS NOT NULL;
          
          	END LOOP;
          
          	-- 4) Atualiza metadados no dicionario da TABELA apartir do comentario salvo
          	PERFORM fc_atualiza_dicionario_apartir_comentario('table', format('%I.%I', $1, $2), comentario)
          	FROM 	tmp_sysarquivo
          	WHERE 	nomearq = $2
          	AND 	comentario IS NOT NULL;
          
          	RETURN;
          END;
          $$
          LANGUAGE plpgsql;
          
          /*
           *
           * fc_atualiza_dicionario_apartir_comentario(text, text, text)
           *
           *   . responsavel por gerar dicionario de dados (tabelas db_sys*) apartir de uma tabela existente no PostgreSQL
           *
           * Parametros;
           *  $1 - tipo de objeto atualizar (valores: schema, table, table column)
           *  $2 - nome do objeto (schema: foo, table: foo.bar, table column: foo.bar.baz)
           *  $3 - comentário no formato JSON
           *
           *  Exemplos:
           *     SELECT fc_atualiza_dicionario_apartir_comentario(
           * 				'table', 'caixa.arrecad', '{"descricao": "Debitos do Contribuinte"}'); -- Tabela caixa.arrecad
           *
           */
          CREATE OR REPLACE FUNCTION fc_atualiza_dicionario_apartir_comentario(tipo_obj text, nome_obj text, comentario_obj text)
          RETURNS void AS
          $$
          DECLARE
          	sysmodulo 		TEXT;
          	sysarquivo 		TEXT;
          	syscampo 		TEXT;
          	systabela   	TEXT;
          	comentario 		JSON;
          	sql_update		TEXT;
          	sql_where 		TEXT;
          	lista_from 		TEXT;
          	lista_join 		TEXT;
          	campos_update 	TEXT[];
          	linhas			INTEGER;
          BEGIN
          	comentario := comentario_obj::json;
          	sysmodulo  := split_part(nome_obj, '.', 1);
          	sql_where  := format('%s = %L',
          						E'regexp_replace(lower(to_ascii(db_sysmodulo.nomemod)), \'[^A-Za-z]\' , \'\', \'g\')', sysmodulo);
          
          	RAISE DEBUG 'tipo_obj: %  nome_obj: %  comentario: %', tipo_obj, nome_obj, comentario_obj;
          
          	CASE tipo_obj
          		WHEN 'schema' THEN
          			systabela := 'configuracoes.db_sysmodulo';
          			campos_update := ARRAY['descricao',  'dataincl', 'ativo'];
          		WHEN 'table' THEN
          			sysarquivo := split_part(nome_obj, '.', 2);
          			systabela  := 'configuracoes.db_sysarquivo';
          			
          			lista_from := 'configuracoes.db_sysarqmod, configuracoes.db_sysmodulo';
          			lista_join := 'db_sysarquivo.codarq = db_sysarqmod.codarq AND db_sysarqmod.codmod = db_sysmodulo.codmod';
          			sql_where  := format('%s AND %s = %L', sql_where, 'db_sysarquivo.nomearq', sysarquivo);
          
          			campos_update := ARRAY['descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela',
          								   'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform'];
          		WHEN 'table column' THEN
          			sysarquivo := split_part(nome_obj, '.', 2);
          			syscampo   := split_part(nome_obj, '.', 3);
          			systabela  := 'configuracoes.db_syscampo';
          			
          			lista_from := 'configuracoes.db_sysarqcamp, configuracoes.db_sysarquivo, configuracoes.db_sysarqmod, configuracoes.db_sysmodulo';
          			lista_join := 'db_syscampo.codcam = db_sysarqcamp.codcam';
          			lista_join := lista_join ||' AND db_sysarqcamp.codarq = db_sysarquivo.codarq';
          			lista_join := lista_join ||' AND db_sysarqmod.codarq = db_sysarquivo.codarq';
          			lista_join := lista_join ||' AND db_sysarqmod.codmod = db_sysmodulo.codmod';
          
          			sql_where  := format('%s AND %s = %L AND %s = %L', sql_where, 
          							'db_sysarquivo.nomearq', sysarquivo,
          							'db_syscampo.nomecam', syscampo);
          			
          			campos_update := ARRAY['conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho',
          								   'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel'];
          
          			-- Atualizar db_syscampodef
          			DELETE FROM db_syscampodef
          			WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = syscampo);
          
          			INSERT INTO db_syscampodef(codcam, defcampo, defdescr)
          			SELECT 	codcam, value->>'defcampo', value->>'defdescr'
          			FROM 	json_array_elements(comentario->'syscampodef'),
          					db_syscampo
          			WHERE 	nomecam = syscampo;
          		ELSE
          			RAISE EXCEPTION 'Tipo de objeto % inválido!', tipo_obj;
          	END CASE;
          
          	SELECT 	format('UPDATE %s SET %s', systabela, string_agg(format('%I = %L', key, value), ', '))
          	INTO 	sql_update
          	FROM 	json_each_text(comentario)
          	WHERE 	lower(key) = ANY(campos_update);
          
          	IF lista_from IS NULL THEN
          		sql_update := format('%s WHERE %s;', sql_update, sql_where);
          	ELSE
          		sql_update := format('%s FROM %s WHERE %s AND %s;', sql_update, lista_from, lista_join, sql_where);
          	END IF;
          
          	RAISE DEBUG 'sysmodulo: %, sysarquivo: %, syscampo: %, comentario: %',
          		sysmodulo, sysarquivo, syscampo, comentario;
          
          	RAISE DEBUG 'sql_update: %', sql_update;
          
          	EXECUTE sql_update;
          	GET DIAGNOSTICS linhas = ROW_COUNT;
          
          	RAISE DEBUG 'linhas: %', linhas;
          
          	RETURN;
          END;
          $$
          LANGUAGE plpgsql;
          
          CREATE OR REPLACE FUNCTION configuracoes.fc_dicionario_gatilho_ddl()
          RETURNS event_trigger AS
          $$
          DECLARE
          	r 				RECORD;
          	_schema_name 	TEXT;
          	_current_query 	TEXT;
          BEGIN
          	IF fc_getsession('__disable_trigger_dicionario__') IS NOT NULL THEN
          		RAISE DEBUG 'Event Trigger do Dicionario de Dados Desabilitada';
          		RETURN;
          	END IF;
          
          	FOR r IN
          		SELECT	objid, objsubid, schema_name, pg_class.relname::text AS table_name, command_tag, object_type, object_identity
          		FROM	pg_event_trigger_ddl_commands()
          				LEFT JOIN pg_class ON pg_class.oid = objid
          		WHERE 	command_tag IN ('CREATE TABLE', 'ALTER TABLE', 'COMMENT')
          	LOOP
          		RAISE DEBUG 'pg_event_trigger_ddl_commands: %', r;
          
          		-- Apenas algums subcomandos do ALTER TABLE são permitidos como RENAME, ADD, DROP, SET SCHEMA e ALTER
          		IF r.command_tag = 'ALTER TABLE' THEN
          			_current_query := trim(regexp_replace(replace(upper(current_query()), 'ALTER TABLE ', ''), '\s+', ' ', 'g'));
          			CONTINUE WHEN _current_query !~ '(RENAME|ADD|DROP|SET SCHEMA|ALTER) ';
          		END IF;
          
          		IF r.object_type = 'schema' AND r.command_tag = 'COMMENT' THEN
          			_schema_name = r.object_identity;
          		ELSE
          			_schema_name = r.schema_name;
          		END IF;
          
          		-- Processa geracao do dicionario de dados apenas se o "esquema" existir na "db_sysmodulo"
          		IF EXISTS (SELECT 1 FROM configuracoes.db_sysmodulo WHERE regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g') = _schema_name) THEN
          
          			-- Processa comandos de tabela
          			IF r.object_type = 'table' AND r.command_tag <> 'COMMENT' THEN
          				-- Particoes de tabelas particionadas (db_logsacessa, db_auditoria e debitos) devem ser ignoradas
          				CONTINUE WHEN r.table_name ~ '^(db_logsacessa|db_auditoria|debitos)_[0-9]' OR r.table_name ~ '^(db_logsacessa|db_auditoria|debitos)$';
          				RAISE DEBUG '%', format('fc_gera_dicionario_apartir_tabela(%L, %L)', r.schema_name, r.table_name);
          				PERFORM	fc_gera_dicionario_apartir_tabela(r.schema_name, r.table_name);
          
          			-- Processa comandos de comentarios
          			ELSIF r.command_tag = 'COMMENT' THEN
          				IF r.object_type = 'table column' THEN
          					PERFORM fc_atualiza_dicionario_apartir_comentario(
          								r.object_type, r.object_identity, pg_catalog.col_description(r.objid, r.objsubid));
          				ELSIF r.object_type = 'table' THEN
          					PERFORM fc_atualiza_dicionario_apartir_comentario(
          								r.object_type, r.object_identity, pg_catalog.obj_description(r.objid, 'pg_class'));
          				ELSIF r.object_type = 'schema' THEN
          					PERFORM fc_atualiza_dicionario_apartir_comentario(
          								r.object_type, r.object_identity, pg_catalog.obj_description(r.objid, 'pg_namespace'));
          				END IF;
          			END IF;
          
          			IF r.object_type <> 'schema' THEN
          				-- Salva na sessao tabelas que foram processadas
          				PERFORM fc_putsession('__evtg_dicionario_gatilho_ddl_tables__', array_agg(distinct i)::text)
          				FROM 	unnest(array_append(fc_getsession('__evtg_dicionario_gatilho_ddl_tables__')::text[], format('%s.%s', r.schema_name, r.table_name))) AS i;
          			END IF;
          		END IF;
          	END LOOP;
          
              RETURN;
          END;
          $$
          SECURITY DEFINER
          LANGUAGE plpgsql;
          
          DROP EVENT TRIGGER IF EXISTS evtg_dicionario_gatilho_ddl;
          CREATE EVENT TRIGGER evtg_dicionario_gatilho_ddl
          	ON ddl_command_end
          	WHEN tag IN ('CREATE TABLE', 'ALTER TABLE', 'COMMENT')
          	EXECUTE FUNCTION configuracoes.fc_dicionario_gatilho_ddl();
          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
          
          CREATE OR REPLACE FUNCTION configuracoes.fc_dicionario_gatilho_ddl_drop()
          RETURNS event_trigger AS
          $$
          DECLARE
          	r 	RECORD;
          BEGIN
          	IF fc_getsession('__disable_trigger_dicionario__') IS NOT NULL THEN
          		RAISE DEBUG 'Event Trigger do Dicionario de Dados Desabilitada';
          		RETURN;
          	END IF;
          
          	FOR r IN
          		SELECT	schema_name, object_name AS table_name
          		FROM	pg_event_trigger_dropped_objects()
          		WHERE 	object_type = 'table'
          	LOOP
          		-- Processa geracao do dicionario de dados apenas se o "esquema" existir na "db_sysmodulo"
          		IF EXISTS (SELECT 1 FROM configuracoes.db_sysmodulo WHERE regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g') = r.schema_name) THEN
          			RAISE DEBUG 'pg_event_trigger_dropped_objects: %', r;
          			PERFORM fc_remove_dicionario_tabela(r.schema_name, r.table_name);
          
          			-- Salva na sessao tabelas que foram processadas
          			PERFORM fc_putsession('__evtg_dicionario_gatilho_ddl_tables__', array_agg(distinct i)::text)
          			FROM 	unnest(array_append(fc_getsession('__evtg_dicionario_gatilho_ddl_tables__')::text[], format('%s.%s', r.schema_name, r.table_name))) AS i;
          		END IF;
          	END LOOP;
          
              RETURN;
          END;
          $$
          SECURITY DEFINER
          LANGUAGE plpgsql;
          
          DROP EVENT TRIGGER IF EXISTS evtg_dicionario_gatilho_ddl_drop;
          CREATE EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop
          	ON sql_drop
          	EXECUTE FUNCTION configuracoes.fc_dicionario_gatilho_ddl_drop();
          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

SQL
);

        DB::connection()->getPdo()->exec(<<<SQL

       ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
       ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;

       DROP SEQUENCE IF EXISTS pessoal.siopesituacao_id_seq;
       CREATE SEQUENCE pessoal.siopesituacao_id_seq
          INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
       
       create table IF NOT EXISTS pessoal.siopesituacao (
       
          si01_id        int4 NOT NULL DEFAULT nextval('pessoal.siopesituacao_id_seq'),
          si01_descricao varchar NOT NULL,
          CONSTRAINT siopesituacao_id_pk PRIMARY KEY (si01_id)
       );

       COMMENT ON TABLE pessoal.siopesituacao IS '{"descricao": "Tabela de cadastro de situações",
                                                   "sigla": "si01",
                                                   "dataincl": "2021-12-09",
                                                   "rotulo": "siopesituacao",
                                                   "tipotabela": "0",
                                                   "naolibclass": "false",
                                                   "naolibfunc": "false",
                                                   "naolibprog": "false",
                                                   "naolibform": "false"
                                                  }';

       COMMENT ON COLUMN pessoal.siopesituacao.si01_id IS '{ "descricao": "Código da Situação",
                                                             "rotulo": "Código da Situação",
                                                             "rotulorel": "Código da Situação",
                                                             "maiusculo": false,
                                                             "autocompl": false,
                                                             "aceitatipo": 1,
                                                             "tamanho": 10,
                                                             "tipoobj": "text"
                                                           }' ;

       COMMENT ON COLUMN pessoal.siopesituacao.si01_descricao IS '{ "descricao": "Situação",
                                                                    "rotulo": " Situação",
                                                                    "rotulorel": " Situação",
                                                                    "maiusculo": true,
                                                                    "autocompl": false,
                                                                    "aceitatipo": 3,
                                                                    "tamanho": 100,
                                                                    "tipoobj": "text"
                                                                  }' ;

       DROP SEQUENCE IF EXISTS pessoal.siopecategoriatipo_id_seq;
       CREATE SEQUENCE IF NOT EXISTS pessoal.siopecategoriatipo_id_seq
          INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

       create table IF NOT EXISTS pessoal.siopecategoriatipo (
       
          si02_id        int4 NOT NULL DEFAULT nextval('pessoal.siopecategoriatipo_id_seq'),
          si02_descricao varchar,
          CONSTRAINT siopecategoriatipo_id_pk PRIMARY KEY (si02_id)
       );

       COMMENT ON TABLE pessoal.siopecategoriatipo IS '{"descricao": "Tabela de cadastro dos tipos de categoria",
                                                        "sigla": "si02",
                                                        "dataincl": "2021-12-09",
                                                        "rotulo": "siopecategoriatipo",
                                                        "tipotabela": "0",
                                                        "naolibclass": "false",
                                                        "naolibfunc": "false",
                                                        "naolibprog": "false",
                                                        "naolibform": "false"
                                                       }';

       COMMENT ON COLUMN pessoal.siopecategoriatipo.si02_id IS '{ "descricao": "Código do Tipo de Categoria",
                                                                  "rotulo": "Código do Tipo de Categoria",
                                                                  "rotulorel": "Código do Tipo de Categoria",
                                                                  "maiusculo": false,
                                                                  "autocompl": false,
                                                                  "aceitatipo": 1,
                                                                  "tamanho": 10,
                                                                  "tipoobj": "text"
                                                                }' ;

       COMMENT ON COLUMN pessoal.siopecategoriatipo.si02_descricao IS '{ "descricao": "Tipo Categoria",
                                                                         "rotulo": " Tipo Categoria",
                                                                         "rotulorel": " Tipo Categoria",
                                                                         "maiusculo": true,
                                                                         "autocompl": false,
                                                                         "aceitatipo": 3,
                                                                         "tamanho": 255,
                                                                         "tipoobj": "text"
                                                                       }' ;

       DROP SEQUENCE IF EXISTS pessoal.siopecategoria_id_seq;
       CREATE SEQUENCE pessoal.siopecategoria_id_seq
          INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

       create table IF NOT EXISTS pessoal.siopecategoria (
      
          si03_id                 int4 NOT NULL DEFAULT nextval('pessoal.siopecategoria_id_seq'),
          si03_siopecategoriatipo int4 NOT NULL,
          si03_descricao          varchar,
          CONSTRAINT siopecategoria_id_pk PRIMARY KEY (si03_id),
          constraint siopecategoria_fk foreign key (si03_siopecategoriatipo) references pessoal.siopecategoriatipo
       );       
       
       COMMENT ON TABLE pessoal.siopecategoria IS '{"descricao": "Tabela de cadastro das categorias",
                                                    "sigla": "si03",
                                                    "dataincl": "2021-12-09",
                                                    "rotulo": "siopecategoria",
                                                    "tipotabela": "0",
                                                    "naolibclass": "false",
                                                    "naolibfunc": "false",
                                                    "naolibprog": "false",
                                                    "naolibform": "false"
                                                   }';

       COMMENT ON COLUMN pessoal.siopecategoria.si03_id IS '{ "descricao": "Código da Categoria",
                                                              "rotulo": "Código da Categoria",
                                                              "rotulorel": "Código da Categoria",
                                                              "maiusculo": false,
                                                              "autocompl": false,
                                                              "aceitatipo": 1,
                                                              "tamanho": 10,
                                                              "tipoobj": "text"
                                                            }' ;

       COMMENT ON COLUMN pessoal.siopecategoria.si03_siopecategoriatipo IS '{ "descricao": "Código do Tipo de Categoria",
                                                                              "rotulo": "Código do Tipo de Categoria",
                                                                              "rotulorel": "Código do Tipo de Categoria",
                                                                              "maiusculo": false,
                                                                              "autocompl": false,
                                                                              "aceitatipo": 1,
                                                                              "tamanho": 10,
                                                                              "tipoobj": "text"
                                                                            }' ;

       COMMENT ON COLUMN pessoal.siopecategoria.si03_descricao IS '{ "descricao": "Categoria",
                                                                     "rotulo": " Categoria",
                                                                     "rotulorel": " Categoria",
                                                                     "maiusculo": true,
                                                                     "autocompl": false,
                                                                     "aceitatipo": 3,
                                                                     "tamanho": 255,
                                                                     "tipoobj": "text"
                                                                   }' ;

       DROP SEQUENCE IF EXISTS pessoal.siopequalificacao_id_seq;
       CREATE SEQUENCE pessoal.siopequalificacao_id_seq
          INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

       create table IF NOT EXISTS pessoal.siopequalificacao (

          si04_id        int4 NOT NULL DEFAULT nextval('pessoal.siopequalificacao_id_seq'),
          si04_descricao text NOT NULL,
          CONSTRAINT siopequalificacao_id_pk PRIMARY KEY (si04_id)
       );

       COMMENT ON TABLE pessoal.siopequalificacao IS '{"descricao": "Tabela de cadastro de qualificações do servidor",
                                                       "sigla": "si04",
                                                       "dataincl": "2021-12-09",
                                                       "rotulo": "siopequalificacao",
                                                       "tipotabela": "0",
                                                       "naolibclass": "false",
                                                       "naolibfunc": "false",
                                                       "naolibprog": "false",
                                                       "naolibform": "false"
                                                      }';

       COMMENT ON COLUMN pessoal.siopequalificacao.si04_id IS '{ "descricao": "Código da Qualificação",
                                                                 "rotulo": "Código da Qualificação",
                                                                 "rotulorel": "Código da Qualificação",
                                                                 "maiusculo": false,
                                                                 "autocompl": false,
                                                                 "aceitatipo": 1,
                                                                 "tamanho": 10,
                                                                 "tipoobj": "text"
                                                               }' ;

       COMMENT ON COLUMN pessoal.siopequalificacao.si04_descricao IS '{ "descricao": "Qualificação",
                                                                        "rotulo": " Qualificação",
                                                                        "rotulorel": " Qualificação",
                                                                        "maiusculo": true,
                                                                        "autocompl": false,
                                                                        "aceitatipo": 3,
                                                                        "tamanho": 1,
                                                                        "tipoobj": "text"
                                                                      }' ;

       alter table pessoal.rhlocaltrab add column if not exists rh55_inep integer not null default 0;

       COMMENT ON COLUMN pessoal.rhlocaltrab.rh55_inep IS '{ "descricao": "Código do Inep",
                                                             "rotulo": "Código do Inep",
                                                             "rotulorel": "Código do Inep",
                                                             "maiusculo": false,
                                                             "autocompl": false,
                                                             "aceitatipo": 1,
                                                             "tamanho": 10,
                                                             "tipoobj": "text"
                                                           }' ;

       DROP SEQUENCE IF EXISTS pessoal.siopesegmentoatuacao_segmento_seq;
       CREATE SEQUENCE pessoal.siopesegmentoatuacao_segmento_seq
          INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

       CREATE TABLE IF NOT EXISTS pessoal.siopesegmentoatuacao (
          si07_segmento  int4 NOT NULL DEFAULT nextval('pessoal.siopesegmentoatuacao_segmento_seq'),
          si07_descricao varchar(255) NOT NULL,
          CONSTRAINT siopesegmentoatuacao_segmento_pk PRIMARY KEY (si07_segmento)
       );
       
       COMMENT ON TABLE pessoal.siopesegmentoatuacao IS '{"descricao": "Tabela de cadastro do Segmento de Atuação",
                                                          "sigla": "si07",
                                                          "dataincl": "2021-12-09",
                                                          "rotulo": "siopesegmentoatuacao",
                                                          "tipotabela": "0",
                                                          "naolibclass": "false",
                                                          "naolibfunc": "false",
                                                          "naolibprog": "false",
                                                          "naolibform": "false"
                                                         }';

       COMMENT ON COLUMN pessoal.siopesegmentoatuacao.si07_segmento IS '{ "descricao": "Código do Segmento de Atuação",
                                                                          "rotulo": "Código do Segmento de Atuação",
                                                                          "rotulorel": "Código do Segmento de Atuação",
                                                                          "maiusculo": false,
                                                                          "autocompl": false,
                                                                          "aceitatipo": 1,
                                                                          "tamanho": 10,
                                                                          "tipoobj": "text"
                                                                        }' ;

       COMMENT ON COLUMN pessoal.siopesegmentoatuacao.si07_descricao IS '{ "descricao": "Segmento de Atuação",
                                                                           "rotulo": " Segmento de Atuação",
                                                                           "rotulorel": " Segmento de Atuação",
                                                                           "maiusculo": true,
                                                                           "autocompl": false,
                                                                           "aceitatipo": 3,
                                                                           "tamanho": 100,
                                                                           "tipoobj": "text"
                                                                         }' ;

       create table IF NOT EXISTS pessoal.siopeservidormanutencao (

          si06_servidor int4 NOT NULL,
          si06_categoria int4 NOT NULL,
          si06_situacao int4 NOT NULL,
          si06_segmento int4 NOT NULL,
          constraint siopeservidormanutencao_servidor_pk PRIMARY KEY (si06_servidor),
          constraint siopeservidormanutencao_servidor_fk foreign key (si06_servidor) references pessoal.rhpessoal,
          constraint siopeservidormanutencao_categoria_fk foreign key (si06_categoria) references pessoal.siopecategoria,
          constraint siopeservidormanutencao_situacao_fk foreign key (si06_situacao) references pessoal.siopesituacao,
          constraint siopeservidormanutencao_segmento_fk foreign key (si06_segmento) references pessoal.siopesegmentoatuacao
       );

       COMMENT ON TABLE pessoal.siopeservidormanutencao IS '{"descricao": "Tabela com a matricula do servidor e suas caracteristicas para o siope",
                                                             "sigla": "si06",
                                                             "dataincl": "2021-12-09",
                                                             "rotulo": "siopeservidormanutencao",
                                                             "tipotabela": "2",
                                                             "naolibclass": "false",
                                                             "naolibfunc": "false",
                                                             "naolibprog": "false",
                                                             "naolibform": "false"
                                                            }';

       COMMENT ON COLUMN pessoal.siopeservidormanutencao.si06_servidor IS '{ "descricao": "Matricula do servidor",
                                                                             "rotulo": "Matricula do servidor",
                                                                             "rotulorel": "Matricula do servidor",
                                                                             "maiusculo": false,
                                                                             "autocompl": false,
                                                                             "aceitatipo": 1,
                                                                             "tamanho": 10,
                                                                             "tipoobj": "text"
                                                                           }' ;

       COMMENT ON COLUMN pessoal.siopeservidormanutencao.si06_categoria IS '{ "descricao": "Categoria do Servidor",
                                                                              "rotulo": "Categoria do Servidor",
                                                                              "rotulorel": "Categoria do Servidor",
                                                                              "maiusculo": false,
                                                                              "autocompl": false,
                                                                              "aceitatipo": 1,
                                                                              "tamanho": 10,
                                                                              "tipoobj": "text"
                                                                            }' ;

       COMMENT ON COLUMN pessoal.siopeservidormanutencao.si06_situacao IS '{ "descricao": "Situação do Servidor",
                                                                             "rotulo": "Situação do Servidor",
                                                                             "rotulorel": "Situação do Servidor",
                                                                             "maiusculo": false,
                                                                             "autocompl": false,
                                                                             "aceitatipo": 1,
                                                                             "tamanho": 10,
                                                                             "tipoobj": "text"
                                                                           }' ;

       COMMENT ON COLUMN pessoal.siopeservidormanutencao.si06_segmento IS '{ "descricao": "Segmento de Atuação do Servidor",
                                                                             "rotulo": "Segmento de Atuação do Servidor",
                                                                             "rotulorel": "Segmento de Atuação do Servidor",
                                                                             "maiusculo": false,
                                                                             "autocompl": false,
                                                                             "aceitatipo": 1,
                                                                             "tamanho": 10,
                                                                             "tipoobj": "text"
                                                                           }' ;

       create table IF NOT EXISTS pessoal.siopeservidorqualificacao (

          si08_servidor int4 NOT NULL,
          si08_qualificacao int4 NOT NULL,
          constraint siopeservidorqualificacao_servidor_qualific_pk primary key (si08_servidor, si08_qualificacao),
          constraint siopeservidorqualificacao_servidor_fk foreign key (si08_servidor) references pessoal.rhpessoal,
          constraint siopeservidorqualificacao_qualificacao_fk foreign key (si08_qualificacao) references pessoal.siopequalificacao
       );

       COMMENT ON TABLE pessoal.siopeservidorqualificacao IS '{"descricao": "Tabela com a matricula do servidor e suas qualificações",
                                                               "sigla": "si08",
                                                               "dataincl": "2021-12-09",
                                                               "rotulo": "siopeservidorqualificacao",
                                                               "tipotabela": "2",
                                                               "naolibclass": "false",
                                                               "naolibfunc": "false",
                                                               "naolibprog": "false",
                                                               "naolibform": "false"
                                                              }';

       COMMENT ON COLUMN pessoal.siopeservidorqualificacao.si08_servidor IS '{ "descricao": "Matricula do servidor",
                                                                               "rotulo": "Matricula do servidor",
                                                                               "rotulorel": "Matricula do servidor",
                                                                               "maiusculo": false,
                                                                               "autocompl": false,
                                                                               "aceitatipo": 1,
                                                                               "tamanho": 10,
                                                                               "tipoobj": "text"
                                                                             }' ;

       COMMENT ON COLUMN pessoal.siopeservidorqualificacao.si08_qualificacao IS '{ "descricao": "Qualificação do Servidor",
                                                                                   "rotulo": "Qualificação do Servidor",
                                                                                   "rotulorel": "Qualificação do Servidor",
                                                                                   "maiusculo": false,
                                                                                   "autocompl": false,
                                                                                   "aceitatipo": 1,
                                                                                   "tamanho": 10,
                                                                                   "tipoobj": "text"
                                                                                 }' ;

       insert into pessoal.siopesituacao (si01_descricao) values ('Efetivo');
       insert into pessoal.siopesituacao (si01_descricao) values ('Temporário');
       insert into pessoal.siopesituacao (si01_descricao) values ('Profissional da educação em atividade alheia à MDE');
       insert into pessoal.siopesituacao (si01_descricao) values ('Outros');

       insert into pessoal.siopecategoriatipo (si02_descricao) values ('Profissionais do Magistério');
       insert into pessoal.siopecategoriatipo (si02_descricao) values ('Outros Profissionais da Educação');

       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente habilitado em curso de nível médio');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente habilitado em curso de pedagogia');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente habilitado em curso de licenciatura plena');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente habilitado em programa especial de formação pedagógica de docentes');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente pós-graduado em cursos de especialização para formação de docentes para educação profissional técnica de nível médio');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente graduado bacharel e tecnólogo com diploma de mestrado ou doutorado na área do componente curricular da educação profissional técnica de nível médio');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente professor indígena sem prévia formação pedagógica');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente instrutor, tradutor e intérprete de Libras');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Docente professor de comunidade quilombola');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Profissionais não habilitados, porém  autorizados a exercer a docência em caráter precário e provisório na educação infantil e nos anos iniciais do ensino fundamental');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Profissionais graduados, bacharéis e tecnólogos autorizados a atuar como docentes, em caráter   precário e provisório, nos anos finais do ensino fundamental e no ensino médio e médio integrado à educação');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Profissionais experientes, não graduados, autorizados a atuar como docentes, em caráter precário e provisório, no ensino médio e médio integrado à educação profissional técnica de nível médio');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (1, 'Profissionais em efetivo exercício no âmbito da educação infantil e ensino fundamental');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (2, 'Auxiliar/Assistente Educacional');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (2, 'Profissionais que exercem funções de secretaria escolar, alimentação escolar (merendeiras), multimeios didáticos e infraestrutura');
       insert into pessoal.siopecategoria (si03_siopecategoriatipo, si03_descricao)
          values (2, 'Profissionais que atuam na realização das atividades requeridos nos ambientes de secretaria, de manutenção em geral');

       insert into pessoal.siopequalificacao (si04_descricao)
          values ('Art. 61 da LBD - Professores habilitados em nível médio ou superior para a docência na educação infantil e nos ensinos fundamental e médio.');
       insert into pessoal.siopequalificacao (si04_descricao)
          values ('Art. 61 da LBD - Trabalhadores em educação portadores de diploma de pedagogia, com habilitação em administração, planejamento, supervisão, inspeção e orientação educacional, bem como com títulos de mestrado ou doutorado nas mesmas áreas.');
       insert into pessoal.siopequalificacao (si04_descricao)
          values ('Art. 61 da LBD - Trabalhadores em educação, portadores de diploma de curso técnico ou superior em área pedagógica ou afim.');
       insert into pessoal.siopequalificacao (si04_descricao)
          values ('Art. 61 da LBD - Profissionais com notório saber reconhecido pelos respectivos sistemas de ensino, para ministrar conteúdos de áreas afins à sua formação ou experiência profissional, atestados por titulação específica ou prática de ensino em unidades educacionais da rede pública ou privada ou das corporações privadas em que tenham atuado, exclusivamente para atender ao inciso V do caput do art. 36.');
       insert into pessoal.siopequalificacao (si04_descricao)
          values ('Art. 61 da LBD - Profissionais graduados que tenham feito complementação pedagógica, conforme disposto pelo Conselho Nacional de Educação.');
       insert into pessoal.siopequalificacao (si04_descricao)
          values ('Art. 1 da Lei nº 13.935/2019 - Prestação de serviços em psicologia.');
       insert into pessoal.siopequalificacao (si04_descricao)
          values ('Art. 1 da Lei nº 13.935/2019 - Prestação de serviços em serviço social');

       INSERT INTO pessoal.siopesegmentoatuacao (si07_descricao) VALUES ('Creche');
       INSERT INTO pessoal.siopesegmentoatuacao (si07_descricao) VALUES ('Pre-escola');
       INSERT INTO pessoal.siopesegmentoatuacao (si07_descricao) VALUES ('Fundamental 1');
       INSERT INTO pessoal.siopesegmentoatuacao (si07_descricao) VALUES ('Fundamental 2');
       INSERT INTO pessoal.siopesegmentoatuacao (si07_descricao) VALUES ('Medio');
       INSERT INTO pessoal.siopesegmentoatuacao (si07_descricao) VALUES ('Profissional');
       INSERT INTO pessoal.siopesegmentoatuacao (si07_descricao) VALUES ('Administrativo');

       select  fc_gera_dicionario_apartir_tabela('pessoal', 'siopesituacao');
       select  fc_gera_dicionario_apartir_tabela('pessoal', 'siopecategoriatipo');
       select  fc_gera_dicionario_apartir_tabela('pessoal', 'siopecategoria');
       select  fc_gera_dicionario_apartir_tabela('pessoal', 'siopequalificacao');
       select  fc_gera_dicionario_apartir_tabela('pessoal', 'siopesegmentoatuacao');
       select  fc_gera_dicionario_apartir_tabela('pessoal', 'siopeservidormanutencao');
       select  fc_gera_dicionario_apartir_tabela('pessoal', 'siopeservidorqualificacao');

       ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
       ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

SQL
);

    }

    private function dicionarioUp()
    {
        DB::statement("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                                        values ( 228603 ,'Informações SIOPE' ,'Informações SIOPE' ,'pes1_manutencaosiope.php' ,
                                                 '1' ,'1' ,'Rotina para cadastrar as informações do Siope' ,'true' );");
        DB::statement("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4354 ,228603 ,9 ,952 );");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dicionarioDown();
        DB::connection()->getPdo()->exec(<<<SQL

          select fc_remove_dicionario_tabela('pessoal', 'siopesituacao');
          select fc_remove_dicionario_tabela('pessoal', 'siopecategoriatipo');
          select fc_remove_dicionario_tabela('pessoal', 'siopecategoria');
          select fc_remove_dicionario_tabela('pessoal', 'siopequalificacao');
          select fc_remove_dicionario_tabela('pessoal', 'siopesegmentoatuacao');
          select fc_remove_dicionario_tabela('pessoal', 'siopeservidormanutencao');
          select fc_remove_dicionario_tabela('pessoal', 'siopeservidorqualificacao');
          
          drop table if exists pessoal.siopeservidorqualificacao;
          drop table if exists pessoal.siopeservidormanutencao;
          drop table if exists pessoal.siopecategoria;
          drop table if exists pessoal.siopecategoriatipo;
          drop table if exists pessoal.siopesituacao;
          drop table if exists pessoal.siopequalificacao;
          drop table if exists pessoal.siopesegmentoatuacao;
          
          alter table pessoal.rhlocaltrab drop column if exists rh55_inep;

SQL
);
    }

    private function dicionarioDown()
    {
        DB::statement("delete from db_menu where id_item_filho = 228603");
        DB::statement("delete from db_itensmenu where id_item = 228603");
    }

}
