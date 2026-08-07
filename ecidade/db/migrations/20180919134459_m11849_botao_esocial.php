<?php

use Classes\PostgresMigration;

class M11849BotaoEsocial extends PostgresMigration
{
    public function up()
    {
        $this->upMenu();
        $this->upDicionario();
        $this->upTabela();
    }

    public function down()
    {
        $this->downMenu();
        $this->downDicionario();
        $this->downTabela();
    }

    private function upMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            VALUES (10583, 'Configurações Gerais', 'Configurações gerais para o eSocial.', 'eso04_esocial_configuracao.php', '1', '1', 'Configurações gerais para o eSocial.', 'true');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
            VALUES (10475, 10583, 5, 10216);
        ";

        $this->execute($sql);
    }

    private function downMenu()
    {
        $sql = "
            DELETE
            FROM db_menu
            WHERE id_item_filho = 10583
              AND modulo = 10216;
            
            DELETE
            FROM db_itensmenu
            WHERE id_item = 10583;
        ";

        $this->execute($sql);
    }

    private function upDicionario()
    {
        $sql = "
            INSERT INTO db_sysarquivo
            VALUES (1010323, 'esocialconfiguracao', 'Configurações gerais do eSocial.', 'eso25', '2018-09-19', 'eSocial Configuração', 0, 'f', 'f', 'f', 'f');
            
            INSERT INTO db_sysarqmod
            VALUES (81, 1010323);
            
            INSERT INTO db_syscampo
            VALUES (1009963, 'eso25_sequencial', 'int4', 'Código sequencial da tabela.', '0', 'Código', 10, 'f', 'f', 'f', 1, 'text', 'Código'),
                   (1009964, 'eso25_exibirbotaoesocial', 'bool', 'Valida se deve ou não apresentar o botão do formulário S-2200 para o usuário logado.', 'f', 'Exibir botão eSocial para os usuários', 1, 'f', 'f', 'f', 5, 'text', 'Exibir botão eSocial para os usuários'),
                   (1009965, 'eso25_instit', 'int4', 'Instituição.', '0', 'Instituição', 10, 'f', 'f', 'f', 1, 'text', 'Instituição');
            
            INSERT INTO db_sysarqcamp
            VALUES (1010323, 1009963, 1, 0),
                   (1010323, 1009964, 2, 0),
                   (1010323, 1009965, 3, 0);
            
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden)
            VALUES (1010323, 1009963, 1, 1009963);
            
            INSERT INTO db_syssequencia
            VALUES (1000769, 'esocialconfiguracao_eso25_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            
            UPDATE db_sysarqcamp
            SET codsequencia = 1000769
            WHERE codarq = 1010323
              AND codcam = 1009963;
            
            INSERT INTO db_sysforkey
            VALUES (1010323, 1009965, 1, 83, 0);
            
            INSERT INTO db_sysindices
            VALUES (1008330, 'esocialconfiguracao_eso25_instit_in', 1010323, '0');
            
            INSERT INTO db_syscadind
            VALUES (1008330, 1009965, 1);
        ";

        $this->execute($sql);
    }

    private function downDicionario()
    {
        $sql = "
            DELETE
            FROM db_sysindices
            WHERE codind = 1008330;
            
            DELETE
            FROM db_syscadind
            WHERE codind = 1008330;
            
            DELETE
            FROM db_sysforkey
            WHERE codarq = 1010323;
            
            DELETE
            FROM db_syssequencia
            WHERE codsequencia = 1000769;
            
            DELETE
            FROM db_sysprikey
            WHERE codarq = 1010323;
            
            DELETE
            FROM db_sysarqcamp
            WHERE codarq = 1010323;
            
            DELETE
            FROM db_syscampo
            WHERE codcam IN (1009963, 1009964, 1009965);
            
            DELETE
            FROM db_sysarqmod
            WHERE codarq = 1010323;
            
            DELETE
            FROM db_sysarquivo
            WHERE codarq = 1010323;
        ";
        $this->execute($sql);
    }

    private function upTabela()
    {
        $sql = "
            CREATE SEQUENCE esocial.esocialconfiguracao_eso25_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            
            CREATE TABLE esocial.esocialconfiguracao (
              eso25_sequencial         INTEGER DEFAULT nextval('esocial.esocialconfiguracao_eso25_sequencial_seq') NOT NULL PRIMARY KEY,
              eso25_exibirbotaoesocial BOOL DEFAULT TRUE,
              eso25_instit             INTEGER,
              CONSTRAINT esocialconfiguracao_eso25_instit_fk FOREIGN KEY (eso25_instit) REFERENCES db_config (codigo)
            );
            
            CREATE UNIQUE INDEX esocialconfiguracao_eso25_instit_in
              ON esocial.esocialconfiguracao (eso25_instit);
        ";

        $this->execute($sql);
    }

    private function downTabela()
    {
        $sql = "
            DROP INDEX esocialconfiguracao_eso25_instit_in;
            DROP TABLE esocial.esocialconfiguracao;
            DROP SEQUENCE esocial.esocialconfiguracao_eso25_sequencial_seq;
        ";

        $this->execute($sql);
    }

}
