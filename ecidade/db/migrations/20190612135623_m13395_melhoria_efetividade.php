<?php

use Classes\PostgresMigration;

class M13395MelhoriaEfetividade extends PostgresMigration
{
    public function up()
    {
        $this->upAssentamentosEncerramentoEfetividade();
        $this->upDBSysArquivo();
        $this->upDBSysArqMod();
        $this->upDBSysCampo();
        $this->upDBSysSequencia();
        $this->upDBSysArqCamp();
        $this->upDBSysPriKey();
        $this->upDBSysForKey();
        $this->upDBSysIndices();
        $this->upDBSysCadInd();
    }

    private function upAssentamentosEncerramentoEfetividade()
    {
        $sql = "
            CREATE TABLE recursoshumanos.assentamentosencerramentoefetividade
            (
                rh230_sequencial   SERIAL  NOT NULL
                    CONSTRAINT assentamentosencerramentoefetividade_pk
                        PRIMARY KEY,
                rh230_assentamento INTEGER NOT NULL
                    CONSTRAINT assentamentosencerramentoefetividade_assenta_h16_codigo_fk
                        REFERENCES recursoshumanos.assenta,
                rh230_ano          INTEGER NOT NULL,
                rh230_mes          CHAR(2) NOT NULL,
                rh230_instituicao  INTEGER NOT NULL
                    CONSTRAINT assentamentosencerramentoefetividade_db_config_codigo_fk
                        REFERENCES configuracoes.db_config,
                CONSTRAINT assentamentosencerramentoefetividade_configuracoesdatasefetivid
                    FOREIGN KEY (rh230_mes, rh230_ano, rh230_instituicao) REFERENCES recursoshumanos.configuracoesdatasefetividade (rh186_competencia, rh186_exercicio, rh186_instituicao)
            );
            
            COMMENT ON TABLE recursoshumanos.assentamentosencerramentoefetividade IS 'Assentamentos criados ao encerrar efetividade';
            
            CREATE INDEX assentamentosencerramentoefetividade_rh230_assentamento_index
                ON recursoshumanos.assentamentosencerramentoefetividade (rh230_assentamento);
            
            CREATE INDEX assentamentosencerramentoefetividade_rh230_mes_rh230_ano_rh230
                ON recursoshumanos.assentamentosencerramentoefetividade (rh230_mes, rh230_ano, rh230_instituicao);
            
            CREATE UNIQUE INDEX assentamentosencerramentoefetividade_rh230_sequencial_uindex
                ON recursoshumanos.assentamentosencerramentoefetividade (rh230_sequencial);
            
            CREATE INDEX assentamentosencerramentoefetividade_rh230_instituicao_index
                ON recursoshumanos.assentamentosencerramentoefetividade (rh230_instituicao);
        ";

        $this->execute($sql);
    }

    private function upDBSysArquivo()
    {
        $sql = "
            INSERT INTO db_sysarquivo
            VALUES (1010454, 'assentamentosencerramentoefetividade', 'Assentamentos criados ao encerrar efetividade', 'rh230', '2019-06-12', 'Assentamentos criados ao encerrar efetividade', 0, 'f', 'f', 'f', 'f');
        ";

        $this->execute($sql);
    }

    private function upDBSysArqMod()
    {
        $sql = "
            INSERT INTO db_sysarqmod
            VALUES (29, 1010454);
        ";

        $this->execute($sql);
    }

    private function upDBSysCampo()
    {
        $sql = "
            INSERT INTO db_syscampo
            VALUES (1010551, 'rh230_sequencial', 'int8', 'Sequencial', '0', 'Sequencial', 8, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
                   (1010552, 'rh230_assentamento', 'int8', 'Assentamento', '0', 'Assentamento', 8, 'f', 'f', 'f', 1, 'text', 'Assentamento'),
                   (1010553, 'rh230_ano', 'int4', 'Ano', '0', 'Ano', 4, 'f', 'f', 'f', 1, 'text', 'Ano'),
                   (1010554, 'rh230_mes', 'char(2)', 'Mês', '', 'Mês', 2, 'f', 'f', 'f', 0, 'text', 'Mês'),
                   (1010555, 'rh230_instituicao', 'int8', 'Instituição', '0', 'Instituição', 8, 'f', 'f', 'f', 1, 'text', 'Instituição');
        ";

        $this->execute($sql);
    }

    private function upDBSysSequencia()
    {
        $sql = "
            INSERT INTO db_syssequencia
            VALUES (1000841, 'assentamentosencerramentoefetividade_rh230_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        ";

        $this->execute($sql);
    }

    private function upDBSysArqCamp()
    {
        $sql = "
            INSERT INTO db_sysarqcamp
            VALUES (1010454, 1010555, 1, 0),
                   (1010454, 1010554, 2, 0),
                   (1010454, 1010553, 3, 0),
                   (1010454, 1010552, 4, 0),
                   (1010454, 1010551, 5, 1000841);
        ";

        $this->execute($sql);
    }

    private function upDBSysPriKey()
    {
        $sql = "
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden)
            VALUES (1010454, 1010551, 1, 1010551);
        ";

        $this->execute($sql);
    }

    private function upDBSysForKey()
    {
        $sql = "
            INSERT INTO db_sysforkey
            VALUES (1010454, 1010552, 1, 528, 0),
                   (1010454, 1010555, 1, 83, 0),
                   (1010454, 1010553, 1, 4003, 0),
                   (1010454, 1010554, 2, 4003, 0),
                   (1010454, 1010555, 3, 4003, 0);
        ";

        $this->execute($sql);
    }

    private function upDBSysIndices()
    {
        $sql = "
            INSERT INTO db_sysindices
            VALUES (1008473, 'assentamentosencerramentoefetividade_rh230_assentamento_index', 1010454, '0'),
                   (1008474, 'assentamentosencerramentoefetividade_rh230_mes_rh230_ano_rh230', 1010454, '0'),
                   (1008475, 'assentamentosencerramentoefetividade_rh230_sequencial_uindex', 1010454, '1'),
                   (1008476, 'assentamentosencerramentoefetividade_rh230_instituicao_index', 1010454, '0');
        ";

        $this->execute($sql);
    }

    private function upDBSysCadInd()
    {
        $sql = "
            INSERT INTO db_syscadind
            VALUES (1008473, 1010552, 1),
                   (1008474, 1010554, 1),
                   (1008474, 1010553, 2),
                   (1008474, 1010555, 3),
                   (1008475, 1010551, 1),
                   (1008476, 1010555, 1);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $this->downDBAcount();
        $this->downDBSysCadInd();
        $this->downDBSysIndices();
        $this->downDBSysForKey();
        $this->downDBSysPriKey();
        $this->downDBSysArqCamp();
        $this->downDBSysSequencia();
        $this->downDBSysCampo();
        $this->downDBSysArqMod();
        $this->downDBSysArquivo();
        $this->downAssentamentosEncerramentoEfetividade();
    }

    private function downDBAcount()
    {
        $sql = "
            DELETE 
            FROM db_acount
            WHERE codarq IN (1010454)
        ";

        $this->execute($sql);
    }

    private function downDBSysCadInd()
    {
        $sql = "
            DELETE 
            FROM db_syscadind
            WHERE codind IN (1008473, 1008474, 1008475, 1008476) AND
                  codcam IN (1010552, 1010554, 1010553, 1010555, 1010551) AND
                  sequen IN (1, 2, 3);
        ";

        $this->execute($sql);
    }

    private function downDBSysIndices()
    {
        $sql = "
            DELETE 
            FROM db_sysindices
            WHERE codind IN (1008473, 1008474, 1008475, 1008476);
        ";

        $this->execute($sql);
    }

    private function downDBSysForKey()
    {
        $sql = "
            DELETE 
            FROM db_sysforkey
            WHERE codarq IN (1010454) AND
                  codcam IN (1010552, 1010555, 1010553, 1010554, 1010555) AND
                  sequen IN (1, 2, 3) AND
                  referen IN (528, 83, 4003);
        ";

        $this->execute($sql);
    }

    private function downDBSysPriKey()
    {
        $sql = "
            DELETE 
            FROM db_sysprikey
            WHERE codarq IN (1010454) AND
                  codcam IN (1010551) AND
                  sequen IN (1);
        ";

        $this->execute($sql);
    }

    private function downDBSysArqCamp()
    {
        $sql = "
            DELETE 
            FROM db_sysarqcamp
            WHERE codarq IN (1010454) AND
                  codcam IN (1010555, 1010554, 1010553, 1010552, 1010551) AND
                  seqarq IN (1, 2, 3, 4, 5);
        ";

        $this->execute($sql);
    }

    private function downDBSysSequencia()
    {
        $sql = "
            DELETE 
            FROM db_syssequencia
            WHERE codsequencia IN (1000841);
        ";

        $this->execute($sql);
    }

    private function downDBSysCampo()
    {
        $sql = "
            DELETE 
            FROM db_syscampo
            WHERE codcam IN (1010551, 1010552, 1010553, 1010554, 1010555);
        ";

        $this->execute($sql);
    }

    private function downDBSysArqMod()
    {
        $sql = "
            DELETE 
            FROM db_sysarqmod
            WHERE codmod IN (29) AND
                  codarq IN (1010454)
        ";

        $this->execute($sql);
    }

    private function downDBSysArquivo()
    {
        $sql = "
            DELETE 
            FROM db_sysarquivo
            WHERE codarq IN (1010454)
        ";

        $this->execute($sql);
    }

    private function downAssentamentosEncerramentoEfetividade()
    {
        $sql = "
            DROP TABLE IF EXISTS recursoshumanos.assentamentosencerramentoefetividade;
        ";

        $this->execute($sql);
    }
}
