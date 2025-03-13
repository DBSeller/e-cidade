<?php

use Classes\PostgresMigration;

class M18752AtualizandoCamposTabelaCgmAlt extends PostgresMigration
{
    public function up() {
        $sql = '
            ALTER TABLE cgmalt
            ADD COLUMN z05_nomecomple character varying(100),
            ADD COLUMN z05_identorgao character varying(50),
            ADD COLUMN z05_cnh character varying(20),
            ADD COLUMN z05_categoria character varying(2),
            ADD COLUMN z05_dtemissao date,
            ADD COLUMN z05_dthabilitacao date,
            ADD COLUMN z05_dtvencimento date,
            ADD COLUMN z05_dtfalecimento date,
            ADD COLUMN z05_escolaridade character varying(50),
            ADD COLUMN z05_naturalidade character varying(100),
            ADD COLUMN z05_identdtexp date,
            ADD COLUMN z05_trabalha boolean,
            ADD COLUMN z05_renda double precision,
            ADD COLUMN z05_localtrabalho character varying(100),
            ADD COLUMN z05_pis character varying(11),
            ADD COLUMN z05_obs text
        ';

        $this->execute($sql);
    }

    public function down() {
        $sql = '
            ALTER TABLE cgmalt
            DROP COLUMN z05_nomecomple,
            DROP COLUMN z05_identorgao,
            DROP COLUMN z05_cnh,
            DROP COLUMN z05_categoria,
            DROP COLUMN z05_dtemissao,
            DROP COLUMN z05_dthabilitacao,
            DROP COLUMN z05_dtvencimento,
            DROP COLUMN z05_dtfalecimento,
            DROP COLUMN z05_escolaridade,
            DROP COLUMN z05_naturalidade,
            DROP COLUMN z05_identdtexp,
            DROP COLUMN z05_trabalha,
            DROP COLUMN z05_renda,
            DROP COLUMN z05_localtrabalho,
            DROP COLUMN z05_pis,
            DROP COLUMN z05_obs
        ';

        $this->execute($sql);
    }
}
