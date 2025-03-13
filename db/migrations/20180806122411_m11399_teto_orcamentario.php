<?php

use Classes\PostgresMigration;

class M11399TetoOrcamentario extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, manutencao, desctec)
            VALUES (10558, 'Teto Orçamentário', 'Teto Orçamentário', '', '1', 'Teto Orçamentário'),
                   (10563, 'Inclusão', 'Inclusão', 'con1_teto_orcamentario_001.php', '1', 'Inclusão'),
                   (10564, 'Alteração', 'Alteração', 'con1_teto_orcamentario_002.php', '1', 'Alteração');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
            VALUES (29, 10558, 282, 209),
                   (10558, 10563, 1, 209),
                   (10558, 10564, 2, 209);
            
            CREATE SEQUENCE teto_orcamentario_c40_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9000000000000000000
              START 1
              CACHE 1;
            
            CREATE TABLE contabilidade.teto_orcamentario (
              c40_sequencial             INTEGER,
              c40_ano                    INTEGER    NOT NULL,
              c40_orgao                  INTEGER    NOT NULL,
              c40_unidade                INTEGER    NOT NULL,
              c40_funcao                 INTEGER    NOT NULL,
              c40_subfuncao              INTEGER    NOT NULL,
              c40_programa               INTEGER    NOT NULL,
              c40_grupo_natureza_despesa INTEGER    NOT NULL,
              c40_identificador_uso      INTEGER    NOT NULL,
              c40_tipo_detalhamento      VARCHAR(2) NOT NULL,
              c40_grupo_fonte_recursos   VARCHAR(2) NOT NULL,
              c40_especificacao_fonte    VARCHAR(2) NOT NULL,
              c40_valor_teto             FLOAT DEFAULT 0,
              c40_valor_disponivel       FLOAT DEFAULT 0,
            
              PRIMARY KEY (c40_sequencial, c40_ano),
              FOREIGN KEY (c40_ano, c40_orgao) REFERENCES orcorgao (o40_anousu, o40_orgao),
              FOREIGN KEY (c40_ano, c40_orgao, c40_unidade) REFERENCES orcunidade (o41_anousu, o41_orgao, o41_unidade),
              FOREIGN KEY (c40_funcao) REFERENCES orcfuncao (o52_funcao),
              FOREIGN KEY (c40_subfuncao) REFERENCES orcsubfuncao (o53_subfuncao),
              FOREIGN KEY (c40_ano, c40_programa) REFERENCES orcprograma (o54_anousu, o54_programa)
            );
            
            INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo)
            VALUES (1010300, 'teto_orcamentario', 'Teto Orçamentário', 'c40', now(), 'teto_orcamentario');
            
            INSERT INTO db_sysarqmod
            VALUES (32, 1010300);
            
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial,rotulo, tamanho, nulo, aceitatipo, tipoobj, rotulorel)
            VALUES (1009864, 'c40_sequencial', 'int4', 'Sequencial', NULL, 'Sequencial', 10, FALSE, 1, 'text', 'Sequencial'),
                   (1009865, 'c40_ano', 'int4', 'Ano', NULL, 'Ano', 10, FALSE, 1, 'text', 'Ano'),
                   (1009866, 'c40_orgao', 'int4', 'Órgão', NULL, 'Órgão', 10, FALSE, 1, 'text', 'Órgão'),
                   (1009867, 'c40_unidade', 'int4', 'Unidade', NULL, 'Unidade', 10, FALSE, 1, 'text', 'Unidade'),
                   (1009868, 'c40_funcao', 'int4', 'Função', NULL, 'Função', 10, FALSE, 1, 'text', 'Função'),
                   (1009869, 'c40_subfuncao', 'int4', 'Subfunção', NULL, 'Subfunção', 10, FALSE, 1, 'text', 'Subfunção'),
                   (1009870, 'c40_programa', 'int4', 'Programa', NULL, 'Programa', 10, FALSE, 1, 'text', 'Programa'),
                   (1009871, 'c40_grupo_natureza_despesa', 'int4', 'Grupo da Natureza da Despesa', NULL, 'Grupo da Natureza da Despesa', 10, FALSE, 1, 'text', 'Grupo da Natureza da Despesa'),
                   (1009872, 'c40_identificador_uso', 'int4', 'Identificador de Uso', NULL, 'Identificador de Uso', 10, FALSE, 1, 'text', 'Identificador de Uso'),
                   (1009873, 'c40_tipo_detalhamento', 'int4', 'Tipo de Detalhamento', NULL, 'Tipo de Detalhamento', 10, FALSE, 1, 'text', 'Tipo de Detalhamento'),
                   (1009874, 'c40_grupo_fonte_recursos', 'int4', 'Grupo de Fonte de Recursos', NULL, 'Grupo de Fonte de Recursos', 10, FALSE, 1, 'text', 'Grupo de Fonte de Recursos'),
                   (1009875, 'c40_especificacao_fonte', 'int4', 'Especificação da Fonte', NULL, 'Especificação da Fonte', 10, FALSE, 1, 'text', 'Especificação da Fonte'),
                   (1009876, 'c40_valor_teto', 'float8', 'Valor do Teto', NULL, 'Valor do Teto', 10, FALSE, 1, 'text', 'Valor do Teto'),
                   (1009877, 'c40_valor_disponivel', 'float8', 'Valos Disponível', NULL, 'Valos Disponível', 10, FALSE, 1, 'text', 'Valos Disponível');
            
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq)
            VALUES (1010300, 1009864, 1),
                   (1010300, 1009865, 2),
                   (1010300, 1009866, 3),
                   (1010300, 1009867, 4),
                   (1010300, 1009868, 5),
                   (1010300, 1009869, 6),
                   (1010300, 1009870, 7),
                   (1010300, 1009871, 8),
                   (1010300, 1009872, 9),
                   (1010300, 1009873, 10),
                   (1010300, 1009874, 11),
                   (1010300, 1009875, 12),
                   (1010300, 1009876, 13),
                   (1010300, 1009877, 14);
            
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden)
            VALUES (1010300, 1009864, 1, 1009864);
            
            INSERT INTO db_sysforkey 
            VALUES (1010300, 1009865, 1, 756),
                   (1010300, 1009866, 1, 756),
                   (1010300, 1009865, 1, 757),
                   (1010300, 1009866, 1, 757),
                   (1010300, 1009867, 1, 757),
                   (1010300, 1009868, 1, 750),
                   (1010300, 1009869, 1, 751),
                   (1010300, 1009870, 1, 752);
            
            INSERT INTO db_syssequencia
            VALUES (1000750, 'teto_orcamentario_c40_sequencial_seq', 1, 1, 9000000000000000000, 1, 1);

            UPDATE db_sysarqcamp SET codsequencia = 1000750 WHERE codarq = 1010300 AND codcam = 1009864;

            UPDATE db_syscampo SET nomecam = 'c40_tipo_detalhamento', conteudo = 'varchar(2)', descricao = 'Tipo de Detalhamento', valorinicial = '', rotulo = 'Tipo de Detalhamento', nulo = 'f', tamanho = 2, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Tipo de Detalhamento' WHERE codcam = 1009873;
            UPDATE db_syscampo SET nomecam = 'c40_grupo_fonte_recursos', conteudo = 'varchar(2)', descricao = 'Grupo de Fonte de Recursos', valorinicial = '', rotulo = 'Grupo de Fonte de Recursos', nulo = 'f', tamanho = 2, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Grupo de Fonte de Recursos' WHERE codcam = 1009874;
            UPDATE db_syscampo SET nomecam = 'c40_especificacao_fonte', conteudo = 'varchar(2)', descricao = 'Especificação da Fonte', valorinicial = '', rotulo = 'Especificação da Fonte', nulo = 'f', tamanho = 2, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Especificação da Fonte' WHERE codcam = 1009875;
        
            CREATE INDEX teto_orcamentario_todos_in
              ON teto_orcamentario (c40_ano, c40_orgao, c40_unidade, c40_funcao, c40_subfuncao, c40_programa, c40_grupo_natureza_despesa, c40_identificador_uso, c40_tipo_detalhamento, c40_grupo_fonte_recursos, c40_especificacao_fonte);
            
            INSERT INTO db_sysindices
            VALUES (1008310, 'teto_orcamentario_todos_in', 1010300, '0');
            
            INSERT INTO db_syscadind
            VALUES (1008310, 1009865, 1),
                   (1008310, 1009866, 2),
                   (1008310, 1009867, 3),
                   (1008310, 1009868, 4),
                   (1008310, 1009869, 5),
                   (1008310, 1009870, 6),
                   (1008310, 1009871, 7),
                   (1008310, 1009872, 8),
                   (1008310, 1009873, 9),
                   (1008310, 1009874, 10),
                   (1008310, 1009875, 11);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE 
            FROM db_syscadind 
            WHERE codind = 1008310;
            
            DELETE 
            FROM db_sysindices 
            WHERE codind = 1008310;

            DELETE
            FROM db_syssequencia
            WHERE codsequencia = 1000750;
            
            DELETE
            FROM db_sysforkey
            WHERE codarq = 1010300;
            
            DELETE
            FROM db_sysprikey
            WHERE codarq = 1010300;
            
            DELETE
            FROM db_sysarqcamp
            WHERE codarq = 1010300;
            
            DELETE
            FROM db_syscampo
            WHERE codcam IN
                  (1009864, 1009865, 1009866, 1009867, 1009868, 1009869, 1009870, 1009871, 1009872, 1009873, 1009874, 1009875, 1009876, 1009877);
            
            DELETE
            FROM db_sysarqmod
            WHERE codarq = 1010300;
            
            DELETE
            FROM db_sysarquivo
            WHERE codarq = 1010300;
            
            DELETE
            FROM db_menu
            WHERE id_item_filho IN (10558, 10563, 10564);
            
            DELETE
            FROM db_itensmenu
            WHERE id_item IN (10558, 10563, 10564);
            
            DROP TABLE contabilidade.teto_orcamentario;
            DROP SEQUENCE teto_orcamentario_c40_sequencial_seq;
            
            DROP INDEX IF EXISTS teto_orcamentario_todos_in;
        ";

        $this->execute($sql);
    }
}
