<?php

use Classes\PostgresMigration;

class M12998Estrutura extends PostgresMigration
{
    public function up()
    {
        $this->upOrcParamSeqColuna();
        $this->upOrcParamSeqColunaEstruturais();
        $this->upOrcParamSeqInfoComplementarLancamento();
        $this->upOrcParamSeqInfoComplementar();
        $this->upMatrizSaldoContabil();
        $this->upMatrizSaldoContabilLancamentos();
        $this->upDBSysCampo();
        $this->upDBSysArquivo();
        $this->upDBSysArqMod();
        $this->upDBSysSequencia();
        $this->upDBSysArqCamp();
        $this->upDBSysPriKey();
        $this->upDBSysForKey();
        $this->upDBSysIndices();
        $this->upDBSysCadInd();
        $this->upDBItensMenu();
        $this->upDBMenu();
    }

    private function upOrcParamSeqColuna()
    {
        $sql = "
            ALTER TABLE configuracoes.orcparamseqcoluna
                ADD o115_formula VARCHAR;
            ALTER TABLE configuracoes.orcparamseqcoluna
                ADD o115_origem INT DEFAULT 0;
            ALTER TABLE configuracoes.orcparamseqcoluna
                ADD o115_relatorio INT;
        ";

        $this->execute($sql);
    }

    private function upOrcParamSeqColunaEstruturais()
    {
        $sql = "
            CREATE TABLE configuracoes.orcparamseqcolunaestruturais
            (
                o158_sequencial        SERIAL                NOT NULL,
                o158_exclusao          BOOLEAN DEFAULT FALSE NOT NULL,
                o158_estrutural        VARCHAR(255)          NOT NULL,
                o158_orcparamseqcoluna INT                   NOT NULL
                    CONSTRAINT orcparamseqcolunaestruturais_orcparamseqcoluna_o115_sequencial_fk REFERENCES configuracoes.orcparamseqcoluna,
                o158_ano               INT                   NOT NULL
            );
            
            CREATE UNIQUE INDEX orcparamseqcolunaestruturais_o158_sequencial_uindex ON configuracoes.orcparamseqcolunaestruturais (o158_sequencial);
            
            ALTER TABLE configuracoes.orcparamseqcolunaestruturais
                ADD CONSTRAINT orcparamseqcolunaestruturais_pk PRIMARY KEY (o158_sequencial);
        ";

        $this->execute($sql);
    }

    private function upOrcParamSeqInfoComplementarLancamento()
    {
        $sql = "
            CREATE TABLE configuracoes.orcparamseqinfocomplementarlancamento
            (
                o102_sequencial      SERIAL                NOT NULL,
                o102_exclusao        BOOLEAN DEFAULT FALSE
            );
            
            CREATE UNIQUE INDEX orcparamseqinfocomplementarlancamento_o102_sequencial_uindex ON configuracoes.orcparamseqinfocomplementarlancamento (o102_sequencial);
            
            ALTER TABLE configuracoes.orcparamseqinfocomplementarlancamento
                ADD CONSTRAINT orcparamseqinfocomplementarlancamento_pk PRIMARY KEY (o102_sequencial);
        ";

        $this->execute($sql);
    }

    private function upOrcParamSeqInfoComplementar()
    {
        $sql = "
            CREATE TABLE configuracoes.orcparamseqinfocomplementar
            (
                o157_sequencial                         SERIAL                NOT NULL,
                o157_valor                              TEXT                  NOT NULL,
                o157_conplanoinfocomplementar           INT                   NOT NULL
                    CONSTRAINT orcparamseqinfocomplementar_conplanoinfocomplementar_c121_sequencial_fk REFERENCES contabilidade.conplanoinfocomplementar,
                o157_relatorio                          INT                   NOT NULL,
                o157_linha                              INT                   NOT NULL,
                o157_padrao                             BOOLEAN DEFAULT FALSE NOT NULL,
                o157_infocomplementarlancamento         INT                   NOT NULL
                    CONSTRAINT orcparamseqinfocomplementar_orcparamseqinfocomplementarlancamento_o102_sequencial_fk REFERENCES configuracoes.orcparamseqinfocomplementarlancamento,
                CONSTRAINT orcparamseqinfocomplementar_orcparamseq_fk FOREIGN KEY (o157_relatorio, o157_linha) REFERENCES orcamento.orcparamseq (o69_codparamrel, o69_codseq)
            );
            
            CREATE UNIQUE INDEX orcparamseqinfocomplementar_o157_sequencial_uindex ON configuracoes.orcparamseqinfocomplementar (o157_sequencial);
            
            ALTER TABLE configuracoes.orcparamseqinfocomplementar
                ADD CONSTRAINT orcparamseqinfocomplementar_pk PRIMARY KEY (o157_sequencial);
        ";

        $this->execute($sql);
    }

    private function upDBSysCampo()
    {
        $sql = "
            INSERT INTO db_syscampo (
                codcam,
                nomecam,
                conteudo,
                descricao,
                valorinicial,
                rotulo,
                tamanho,
                nulo,
                maiusculo,
                autocompl,
                aceitatipo,
                tipoobj,
                rotulorel
            ) VALUES (1010348, 'o115_formula', 'varchar(255)', 'Fórmula', '', 'Fórmula', 255, 't', 'f', 'f', 0, 'text', 'Fórmula'),
                     (1010376, 'o115_origem', 'int8', 'Origem', '0', 'Origem', 8, 't', 'f', 'f', 1, 'text', 'Origem'),
                     (1010457, 'o115_relatorio', 'int8', 'Relatório', '0',  'Relatório', 8, 't', 'f', 'f', 1, 'text', 'Relatório'),
                     (1010350, 'o158_sequencial', 'int8', 'Sequencial', '0', 'Sequencial', 8, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
                     (1010351, 'o158_exclusao', 'bool', 'Exclusão', 'false', 'Exclusão', 1, 'f', 'f', 'f', 5, 'text', 'Exclusão'),
                     (1010352, 'o158_estrutural', 'varchar(255)', 'Estrutural', '', 'Estrutural', 255, 'f', 'f', 'f', 0, 'text', 'Estrutural'),
                     (1010353, 'o158_orcparamseqcoluna', 'int8', 'Coluna', '0', 'Coluna', 8, 'f', 'f', 'f', 1, 'text', 'Coluna'),
                     (1010358, 'o158_ano', 'int4', 'Ano', '0', 'Ano', 4, 'f', 'f', 'f', 1, 'text', 'Ano'),
                     (1010354, 'o157_sequencial', 'int8', 'Sequencial', '0', 'Sequencial', 8, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
                     (1010355, 'o157_valor', 'text', 'Valor', '', 'Valor', 1, 'f', 'f', 'f', 0, 'text', 'Valor'),
                     (1010356, 'o157_conplanoinfocomplementar', 'int8', 'Informação Complementar', '0', 'Informação Complementar', 8, 'f', 'f', 'f', 1, 'text', 'Informação Complementar'),
                     (1010357, 'o157_relatorio', 'int8', 'Relatório', '0', 'Relatório', 8, 'f', 'f', 'f', 1, 'text', 'Relatório'),
                     (1010364, 'o157_linha', 'int8', 'Linha', '0', 'Linha', 8, 'f', 'f', 'f', 1, 'text', 'Linha'),
                     (1010407, 'o157_padrao', 'bool', 'Informa se as informações complementares são as padrões para a linha.', 'f', 'Padrão', 1, 'f', 'f', 'f', 5, 'text', 'Padrão'),
                     (1010415, 'o157_infocomplementarlancamento', 'int8', 'Linha Informação Complementar', '0',  'Linha Informação Complementar', 8, 'f', 'f', 'f', 1, 'text', 'Linha Informação Complementar'),
                     (1010422, 'o102_exclusao', 'bool', 'Exclusão', 'f',  'Exclusão', 1, 'f', 'f', 'f', 5, 'text', 'Exclusão'),
                     (1010461, 'c132_sequencial', 'int8', 'Sequencial', '0',  'Sequencial', 8, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
                     (1010462, 'c132_mes', 'int8', 'Mês', '0',  'Mês', 8, 'f', 'f', 'f', 1, 'text', 'Mês'),
                     (1010463, 'c132_ano', 'int8', 'Ano', '0',  'Ano', 8, 'f', 'f', 'f', 1, 'text', 'Ano'),
                     (1010465, 'c133_sequencial', 'int8', 'Sequencial', '0',  'Sequencial', 8, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
                     (1010466, 'c133_matriz_saldo_contabil', 'int8', 'Matriz Saldo Contábil', '0',  'Matriz Saldo Contábil', 8, 'f', 'f', 'f', 1, 'text', 'Matriz Saldo Contábil'),
                     (1010467, 'c133_estrutural', 'varchar(15)', 'Estrutural', '',  'Estrutural', 15, 'f', 'f', 'f', 0, 'text', 'Estrutural'),
                     (1010468, 'c133_atributos', 'varchar(255)', 'Atributos', '',  'Atributos', 255, 'f', 'f', 'f', 0, 'text', 'Atributos'),
                     (1010469, 'c133_beginning_balance', 'float8', 'Beginning Balance', '0',  'Beginning Balance', 8, 'f', 'f', 'f', 4, 'text', 'Beginning Balance'),
                     (1010470, 'c133_period_change_debit', 'float8', 'Period Change Debit', '0',  'Period Change Debit', 8, 'f', 'f', 'f', 4, 'text', 'Period Change Debit'),
                     (1010471, 'c133_period_change_credit', 'float8', 'Period Change Credit', '0',  'Period Change Credit', 8, 'f', 'f', 'f', 4, 'text', 'Period Change Credit'),
                     (1010472, 'c133_ending_balance', 'float8', 'Ending Balance', '0',  'Ending Balance', 9, 'f', 'f', 'f', 4, 'text', 'Ending Balance'),
                     (1010473, 'c133_natureza', 'varchar(1)', 'Natureza', '',  'Natureza', 1, 'f', 'f', 'f', 0, 'text', 'Natureza');

            UPDATE configuracoes.db_syscampo
            SET nomecam  = 'o102_sequencial',
                conteudo = 'int8',
                tamanho  = 8
            WHERE codcam = 13912;
        ";

        $this->execute($sql);
    }

    private function upDBSysArquivo()
    {
        $sql = "
            INSERT INTO db_sysarquivo
            VALUES (1010423, 'orcparamseqcolunaestruturais', 'Contas da Coluna', 'o158', '2019-02-28', 'Contas da Coluna', 0, 'f', 'f', 'f', 'f'),
                   (1010424, 'orcparamseqinfocomplementar', 'Contas da Linha/Coluna', 'o157', '2019-02-28', 'Contas da Linha/Coluna', 0, 'f', 'f', 'f', 'f'),
                   (1010439, 'orcparamseqinfocomplementarlancamento', 'Linha Informação Complementar Conta Corrente', 'o102', '2019-04-16', 'Linha Informação Complementar Conta Corrente', 0, 'f', 'f', 'f', 'f'),
                   (1010444, 'matriz_saldo_contabil', 'Matriz Saldo Contábil', 'c132', '2019-05-06', 'Matriz Saldo Contábil', 0, 'f', 'f', 'f', 'f'),
                   (1010445, 'matriz_saldo_contabil_lancamentos', 'Matriz Saldo Contábil Lançamentos', 'c133', '2019-05-06', 'Matriz Saldo Contábil Lançamentos', 0, 'f', 'f', 'f', 'f' );

            UPDATE configuracoes.db_sysarquivo
            SET nomearq = 'orcparamseqfiltrousuario',
                sigla   = 'o72'
            WHERE codarq = 2743;

            UPDATE configuracoes.db_sysarquivo
            SET nomearq = 'pactoprograma',
                sigla   = 'o107'
            WHERE codarq = 2455;
        ";

        $this->execute($sql);
    }

    private function upDBSysArqMod()
    {
        $sql = "
            INSERT INTO db_sysarqmod
            VALUES (7, 1010423),
                   (7, 1010424),
                   (7, 1010439),
                   (32, 1010444),
                   (32, 1010445);
        ";

        $this->execute($sql);
    }

    private function upDBSysSequencia()
    {
        $sql = "
            INSERT INTO db_syssequencia
            VALUES (1000819, 'orcparamseqcolunaestruturais_o158_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000820, 'orcparamseqinfocomplementar_o157_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000828, 'orcparamseqinfocomplementarlancamento_o102_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000833, 'matriz_saldo_contabil_c132_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000834, 'matriz_saldo_contabil_lancamentos_c133_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        ";

        $this->execute($sql);
    }

    private function upDBSysArqCamp()
    {
        $sql = "
            INSERT INTO db_sysarqcamp
            VALUES (2482, 1010348, 7, 0),
                   (2482, 1010376, 8, 0),
                   (2482, 1010457, 9, 0),
                   (1010423, 1010353, 1, 0),
                   (1010423, 1010352, 2, 0),
                   (1010423, 1010351, 3, 0),
                   (1010423, 1010350, 4, 1000819),
                   (1010423, 1010358, 5, 0),
                   (1010424, 1010357, 1, 0),
                   (1010424, 1010356, 2, 0),
                   (1010424, 1010355, 3, 0),
                   (1010424, 1010354, 4, 1000820),
                   (1010424, 1010364, 5, 0),
                   (1010424, 1010407, 6, 0),
                   (1010424, 1010415, 7, 0),
                   (1010439, 13912, 1, 1000828),
                   (1010439, 1010422, 2, 0),
                   (1010444, 1010463, 2, 0),
                   (1010444, 1010462, 3, 0),
                   (1010444, 1010461, 4, 1000833),
                   (1010445, 1010472, 1, 0),
                   (1010445, 1010471, 2, 0),
                   (1010445, 1010470, 3, 0),
                   (1010445, 1010469, 4, 0),
                   (1010445, 1010468, 5, 0),
                   (1010445, 1010467, 6, 0),
                   (1010445, 1010466, 7, 0),
                   (1010445, 1010465, 8, 1000834),
                   (1010445, 1010473, 9, 0);
        ";

        $this->execute($sql);
    }

    private function upDBSysPriKey()
    {
        $sql = "
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden)
            VALUES (1010423, 1010350, 1, 1010350),
                   (1010424, 1010354, 1, 1010354),
                   (1010439, 13912, 1, 13912),
                   (1010444, 1010461, 1, 1010461),
                   (1010445, 1010465, 1, 1010465);
        ";

        $this->execute($sql);
    }

    private function upDBSysForKey()
    {
        $sql = "
            INSERT INTO db_sysforkey
            VALUES (1010423, 1010353, 1, 2482, 0),
                   (1010423, 1010358, 2, 774, 0),
                   (1010424, 1010357, 1, 1082, 0),
                   (1010424, 1010364, 2, 1082, 0),
                   (1010424, 1010356, 1, 1010256, 0),
                   (1010424, 1010415, 1, 1010439, 0),
                   (1010445, 1010466, 1, 1010444, 0);
        ";

        $this->execute($sql);
    }

    private function upDBSysIndices()
    {
        $sql = "
            INSERT INTO db_sysindices
            VALUES (1008432, 'orcparamseqcolunaestruturais_o158_sequencial_uindex', 1010423, '1'),
                   (1008433, 'orcparamseqinfocomplementar_o157_sequencial_uindex', 1010424, '1'),
                   (1008446, 'orcparamseqinfocomplementarlancamento_o102_sequencial_uindex', 1010439, '1'),
                   (1008456, 'matriz_saldo_contabil_c132_sequencial_uindex', 1010444, '1'),
                   (1008457, 'matriz_saldo_contabil_lancamentos_c133_sequencial_uindex', 1010445, '1');
        ";

        $this->execute($sql);
    }

    private function upDBSysCadInd()
    {
        $sql = "
            INSERT INTO db_syscadind
            VALUES (1008432, 1010350, 1),
                   (1008433, 1010354, 1),
                   (1008446, 13912, 1),
                   (1008456, 1010461, 1),
                   (1008457, 1010465, 1);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $this->downDBMenu();
        $this->downDBItensMenu();
        $this->downDBAcount();
        $this->downDBSysCadInd();
        $this->downDBSysIndices();
        $this->downDBSysForKey();
        $this->downDBSysPriKey();
        $this->downDBSysArqCamp();
        $this->downDBSysSequencia();
        $this->downDBSysArqMod();
        $this->downDBSysArquivo();
        $this->downDBSysCampo();
        $this->downMatrizSaldoContabilLancamentos();
        $this->downMatrizSaldoContabil();
        $this->downOrcParamSeqInfoComplementar();
        $this->downOrcParamSeqInfoComplementarLancamento();
        $this->downOrcParamSeqColunaEstruturais();
        $this->downOrcParamSeqColuna();
    }

    private function downDBSysCadInd()
    {
        $sql = "
            DELETE
            FROM db_syscadind
            WHERE codind IN (1008432, 1008433, 1008446, 1008456, 1008457)
              AND codcam IN (1010350, 1010354, 13912, 1010461, 1010465)
              AND sequen = 1;
        ";

        $this->execute($sql);
    }

    private function downDBSysIndices()
    {
        $sql = "
            DELETE
            FROM db_sysindices
            WHERE codind IN (1008432, 1008433, 1008446, 1008456, 1008457);
        ";

        $this->execute($sql);
    }

    private function downDBSysForKey()
    {
        $sql = "
            DELETE
            FROM db_sysforkey
            WHERE codarq IN (1010423, 1010424, 1010445)
              AND codcam IN (1010353, 1010358, 1010357, 1010364, 1010356, 1010415, 1010466)
              AND sequen IN (1, 2)
              AND referen IN (2482, 774, 1082, 1082, 1010256, 1010439, 1010444);
        ";

        $this->execute($sql);
    }

    private function downDBSysPriKey()
    {
        $sql = "
            DELETE
            FROM db_sysprikey
            WHERE codarq IN (1010423, 1010424, 1010439, 1010444, 1010445)
              AND codcam IN (1010350, 1010354, 13912, 1010461, 1010465)
              AND sequen = 1;
        ";

        $this->execute($sql);
    }

    private function downDBSysArqCamp()
    {
        $sql = "
            DELETE
            FROM db_sysarqcamp
            WHERE codcam IN (1010348, 1010376, 1010353, 1010352, 1010351, 1010350, 1010358, 1010357, 1010356, 1010355, 1010354, 1010364, 1010407, 13912, 1010415, 1010422, 1010457, 1010463, 1010462, 1010461, 1010472, 1010471, 1010470, 1010469, 1010468, 1010467, 1010466, 1010465, 1010473)
              AND codarq IN (2482, 1010423, 1010424, 1010439, 1010444, 1010445)
              AND seqarq IN (1, 2, 3, 4, 5, 6, 7, 8, 9);
        ";

        $this->execute($sql);
    }

    private function downDBSysSequencia()
    {
        $sql = "
            DELETE
            FROM db_syssequencia
            WHERE codsequencia IN (1000819, 1000820, 1000828, 1000833, 1000834);
        ";

        $this->execute($sql);
    }

    private function downDBSysArqMod()
    {
        $sql = "
            DELETE
            FROM db_sysarqmod
            WHERE codmod IN (7, 32) AND codarq IN (1010423, 1010424, 1010439, 1010444, 1010445);
        ";

        $this->execute($sql);
    }

    private function downDBSysArquivo()
    {
        $sql = "
            DELETE
            FROM db_sysarquivo
            WHERE codarq IN (1010423, 1010424, 1010439, 1010444, 1010445);

            UPDATE configuracoes.db_sysarquivo
            SET nomearq = 'pactoprograma',
                sigla   = 'o102'
            WHERE codarq = 2455;

            UPDATE configuracoes.db_sysarquivo
            SET nomearq = 'orcparamseqfiltrousuario',
                sigla   = 'o82'
            WHERE codarq = 2743;
        ";

        $this->execute($sql);
    }

    private function downDBSysCampo()
    {
        $sql = "
            UPDATE configuracoes.db_syscampo
            SET nomecam  = 'o102_sequencial',
                conteudo = 'int4',
                tamanho  = 10
            WHERE codcam = 13912;

            DELETE
            FROM db_syscampo
            WHERE codcam IN (1010348, 1010376, 1010350, 1010351, 1010352, 1010353, 1010358, 1010354, 1010355, 1010356, 1010357, 1010364, 1010407, 1010415, 1010422, 1010457, 1010461, 1010462, 1010463, 1010465, 1010466, 1010467, 1010468, 1010469, 1010470, 1010471, 1010472, 1010473);
        ";

        $this->execute($sql);
    }

    private function downOrcParamSeqInfoComplementar()
    {
        $sql = "
            DROP TABLE IF EXISTS configuracoes.orcparamseqinfocomplementar;
        ";

        $this->execute($sql);
    }

    private function downOrcParamSeqInfoComplementarLancamento()
    {
        $sql = "
            DROP TABLE IF EXISTS configuracoes.orcparamseqinfocomplementarlancamento;
        ";

        $this->execute($sql);
    }

    private function downOrcParamSeqColunaEstruturais()
    {
        $sql = "
            DROP TABLE IF EXISTS configuracoes.orcparamseqcolunaestruturais;
        ";

        $this->execute($sql);
    }

    private function downOrcParamSeqColuna()
    {
        $sql = "
            ALTER TABLE configuracoes.orcparamseqcoluna
                DROP COLUMN o115_relatorio;
            ALTER TABLE configuracoes.orcparamseqcoluna
                DROP COLUMN o115_formula;
            ALTER TABLE configuracoes.orcparamseqcoluna
                DROP COLUMN o115_origem;
        ";

        $this->execute($sql);
    }

    private function downDBAcount()
    {
        $sql = "
            DELETE
            FROM db_acount
            WHERE codarq IN (1010423, 1010424, 1010439, 1010444, 1010445);
        ";

        $this->execute($sql);
    }

    private function upDBItensMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            VALUES (228108,
                    'Duplicar Relatório Legal',
                    'Duplicar Relatório Legal',
                    'con4_gerarrelatoriolegal001.php',
                    '1',
                    '1',
                    'Duplicar Relatório Legal a partir de outro relatório legal',
                    'true');
        ";

        $this->execute($sql);
    }

    private function upDBMenu()
    {
        $sql = "
            INSERT INTO db_menu(id_item, id_item_filho, menusequencia, modulo)
            VALUES (7598, 228108, 7, 1);
        ";

        $this->execute($sql);
    }

    private function downDBItensMenu()
    {
        $sql = "
            DELETE
            FROM db_itensmenu
            WHERE id_item = 228108;
        ";

        $this->execute($sql);
    }

    private function downDBMenu()
    {
        $sql = "
            DELETE
            FROM db_menu
            WHERE id_item_filho = 228108;
        ";

        $this->execute($sql);
    }

    private function upMatrizSaldoContabil()
    {
        $sql = "
            CREATE TABLE contabilidade.matriz_saldo_contabil
            (
                c132_sequencial   SERIAL  NOT NULL,
                c132_mes          INT     NOT NULL,
                c132_ano          INT     NOT NULL
            );
            
            CREATE UNIQUE INDEX matriz_saldo_contabil_c132_sequencial_uindex ON contabilidade.matriz_saldo_contabil (c132_sequencial);
            
            ALTER TABLE contabilidade.matriz_saldo_contabil
                ADD CONSTRAINT matriz_saldo_contabil_pk PRIMARY KEY (c132_sequencial);
        ";

        $this->execute($sql);
    }

    private function upMatrizSaldoContabilLancamentos()
    {
        $sql = "
            CREATE TABLE contabilidade.matriz_saldo_contabil_lancamentos
            (
                c133_sequencial            SERIAL      NOT NULL,
                c133_matriz_saldo_contabil INT         NOT NULL
                    CONSTRAINT matriz_saldo_contabil_lancamentos_matriz_saldo_contabil_c132_sequencial_fk
                        REFERENCES contabilidade.matriz_saldo_contabil,
                c133_estrutural            VARCHAR(15) NOT NULL,
                c133_atributos             VARCHAR     NOT NULL,
                c133_beginning_balance     FLOAT       NOT NULL,
                c133_period_change_debit   FLOAT       NOT NULL,
                c133_period_change_credit  FLOAT       NOT NULL,
                c133_ending_balance        FLOAT       NOT NULL,
                c133_natureza              VARCHAR(1)  NOT NULL
            );
            
            CREATE UNIQUE INDEX matriz_saldo_contabil_lancamentos_c133_sequencial_uindex
                ON contabilidade.matriz_saldo_contabil_lancamentos (c133_sequencial);
            
            ALTER TABLE contabilidade.matriz_saldo_contabil_lancamentos
                ADD CONSTRAINT matriz_saldo_contabil_lancamentos_pk
                    PRIMARY KEY (c133_sequencial);
        ";

        $this->execute($sql);
    }

    private function downMatrizSaldoContabilLancamentos()
    {
        $sql = "
            DROP TABLE IF EXISTS contabilidade.matriz_saldo_contabil_lancamentos;
        ";

        $this->execute($sql);
    }

    private function downMatrizSaldoContabil()
    {
        $sql = "
            DROP TABLE IF EXISTS contabilidade.matriz_saldo_contabil;
        ";

        $this->execute($sql);
    }
}
