<?php

use Classes\PostgresMigration;

class M16360Pl05AuditoriaRemoveFuncao extends PostgresMigration
{
    public function up() {
        $sql = <<<SQL_UP
-- Funcao para remover trigger de auditoria das tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao(TEXT) RETURNS VOID AS
$$
DECLARE
    sEsquema    TEXT;
    sTabela     TEXT;
    aTabela     TEXT[];

    sSQL        TEXT;

    rTabela     RECORD;
    rTrigger    RECORD;
BEGIN

    -- Separa Esquema e Tabela
    IF position('.' in $1) > 0 THEN
        aTabela  := string_to_array($1, '.');
        sEsquema := aTabela[1];
        sTabela  := aTabela[2];
    ELSE
        sEsquema := 'public';
        sTabela  := $1;
    END IF;

    FOR rTabela IN
        SELECT  *
        FROM    configuracoes.vw_auditoria_lista_tabelas
        WHERE   esquema LIKE sEsquema
        AND     nome    LIKE sTabela
    LOOP
        RAISE DEBUG 'Removendo funcao e trigger de auditoria da tabela %.%',
            rTabela.esquema, rTabela.nome;

        FOR rTrigger IN
            SELECT  DISTINCT tgfoid::regproc::text AS funcao
            FROM    pg_trigger
            WHERE   tgisinternal IS FALSE
            AND     tgfoid::regproc::text ~ '^fc_auditoria_tabela_'
            AND     tgrelid = format('%I.%I', rTabela.esquema, rTabela.nome)::regclass
        LOOP
            -- Apaga Funcao e Trigger(s) de Auditoria
            RAISE DEBUG 'Removendo funcao %', rTrigger.funcao;
            sSQL := format('DROP FUNCTION IF EXISTS %s() CASCADE;', rTrigger.funcao);
            EXECUTE sSQL;
        END LOOP;
    END LOOP;

    RETURN;
END;
$$
LANGUAGE plpgsql;

-- Wrapper para remover funcoes para todas tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao() RETURNS VOID AS
$$
    SELECT configuracoes.fc_auditoria_remove_funcao('%.%');
$$
LANGUAGE sql;

SQL_UP;
        $this->execute($sql);
    }

    public function down() {
        $sql = <<<SQL_DOWN
-- Funcao para remover trigger de auditoria das tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao(TEXT) RETURNS VOID AS
$$
DECLARE
    sEsquema    TEXT;
    sTabela     TEXT;
    aTabela     TEXT[];

    sSQL        TEXT;

    rTabela     RECORD;
    rTrigger    RECORD;
BEGIN

    -- Separa Esquema e Tabela
    IF position('.' in $1) > 0 THEN
        aTabela  := string_to_array($1, '.');
        sEsquema := aTabela[1];
        sTabela  := aTabela[2];
    ELSE
        sEsquema := 'public';
        sTabela  := $1;
    END IF;

    FOR rTabela IN
        SELECT  *
        FROM    configuracoes.vw_auditoria_lista_tabelas
        WHERE   esquema LIKE sEsquema
        AND     nome    LIKE sTabela
    LOOP
        RAISE DEBUG 'Removendo funcao e trigger de auditoria da tabela %.%',
            rTabela.esquema, rTabela.nome;

        -- Apaga Funcao e Trigger(s) de Auditoria
        sSQL := 'DROP FUNCTION IF EXISTS '||rTabela.esquema||'.fc_auditoria_tabela_'||rTabela.oid::text||'() CASCADE;';
        EXECUTE sSQL;

    END LOOP;

    RETURN;
END;
$$
LANGUAGE plpgsql;

-- Wrapper para remover funcoes para todas tabelas
CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_remove_funcao() RETURNS VOID AS
$$
    SELECT configuracoes.fc_auditoria_remove_funcao('%.%');
$$
LANGUAGE sql;
SQL_DOWN;
        $this->execute($sql);   
    }
}
