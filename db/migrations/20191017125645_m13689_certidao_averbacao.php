<?php

use Classes\PostgresMigration;

class M13689CertidaoAverbacao extends PostgresMigration
{
    public function up() {
return true;
        $sql = "
        ALTER TABLE cadastro.certidaoexistencia ADD COLUMN j133_arealote double precision;
        ALTER TABLE cadastro.certidaoexistencia ADD COLUMN j133_areareallote double precision;
        ALTER TABLE cadastro.certidaoexistencia ADD COLUMN j133_areaconstruida double precision;
        ALTER TABLE cadastro.certidaoexistencia ADD COLUMN j133_arearealconstruida double precision;
        ALTER TABLE cadastro.certidaoexistencia ADD COLUMN j133_numhabite character varying(20);
        ALTER TABLE cadastro.certidaoexistencia ADD COLUMN j133_dathabite date;
        ALTER TABLE cadastro.certidaoexistencia ALTER COLUMN j133_observacao TYPE character varying(850);

        UPDATE db_syscampo SET conteudo = 'varchar(800)', tamanho = 800 where codcam = 18852;
        INSERT INTO configuracoes.db_syscampo VALUES (1010609,'j133_arealote','float4','Informação referente a Área do Lote.',0,'Área do Lote',10,'f','f','f',4,'text','Área do Lote');
        INSERT INTO configuracoes.db_syscampo VALUES (1010610,'j133_areareallote','float4','Informação referente a Área Real do Lote.',0,'Área Real do Lote',10,'f','f','f',4,'text','Área Real do Lote');
        INSERT INTO configuracoes.db_syscampo VALUES (1010611,'j133_areaconstruida','float4','Informação referente a Área Construída do Lote.',0,'Área Construída do Lote',10,'f','f','f',4,'text','Área Construída do Lote');
        INSERT INTO configuracoes.db_syscampo VALUES (1010612,'j133_arearealconstruida','float4','Informação referente a Área Real Construída do Lote.',0,'Área Real Construída do Lote',10,'f','f','f',4,'text','Área Real Construída do Lote');
        INSERT INTO configuracoes.db_syscampo VALUES (1010613,'j133_numhabite','varchar(20)','Informação referente ao Número do Habite-se.','','Número do Habite-se',20,'f','f','f',0,'text','Número do Habite-se');
        INSERT INTO configuracoes.db_syscampo VALUES (1010614,'j133_dathabite','date','Informação referente a Data do Habite-se.','','Data do Habite-se',20,'f','f','f',0,'text','Data do Habite-se');
        INSERT INTO configuracoes.db_sysarqcamp VALUES (3341,1010609,12,0);
        INSERT INTO configuracoes.db_sysarqcamp VALUES (3341,1010610,13,0);
        INSERT INTO configuracoes.db_sysarqcamp VALUES (3341,1010611,14,0);
        INSERT INTO configuracoes.db_sysarqcamp VALUES (3341,1010612,15,0);
        INSERT INTO configuracoes.db_sysarqcamp VALUES (3341,1010613,16,0);
        INSERT INTO configuracoes.db_sysarqcamp VALUES (3341,1010614,17,0);
        ";
        $this->execute($sql);
    }

    public function down() {
        $sql = "
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam IN (1010609,1010610,1010611,1010612,1010613,1010614);
            DELETE FROM configuracoes.db_syscampo WHERE codcam IN (1010609,1010610,1010611,1010612,1010613,1010614);
            UPDATE db_syscampo SET conteudo = 'varchar(255)', tamanho = 255 where codcam = 18852;
            ALTER TABLE cadastro.certidaoexistencia ALTER COLUMN j133_observacao TYPE character varying(255);
            ALTER TABLE cadastro.certidaoexistencia DROP COLUMN j133_arealote;
            ALTER TABLE cadastro.certidaoexistencia DROP COLUMN j133_areareallote;
            ALTER TABLE cadastro.certidaoexistencia DROP COLUMN j133_areaconstruida;
            ALTER TABLE cadastro.certidaoexistencia DROP COLUMN j133_arearealconstruida;
            ALTER TABLE cadastro.certidaoexistencia DROP COLUMN j133_numhabite;
            ALTER TABLE cadastro.certidaoexistencia DROP COLUMN j133_dathabite;
        ";
        $this->execute($sql);
    }
}
