<?php

use Classes\PostgresMigration;

class M11558ConfirmacaoRematricula extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
        $this->upMenu();
    }

    private function upEstrutura()
    {
        $sql = "
            CREATE TABLE escola.confirmacaorematricula
            (
              edu01_sequencial SERIAL,
              edu01_escola     INT                     NOT NULL,
              edu01_calendario INT                     NOT NULL,
              edu01_turma      INT                     NOT NULL,
              edu01_aluno      INT                     NOT NULL,
              edu01_criado_em  TIMESTAMP DEFAULT now() NOT NULL,
              CONSTRAINT confirmacaorematricula_escola_ed18_i_codigo_fk FOREIGN KEY (edu01_escola) REFERENCES escola.escola (ed18_i_codigo),
              CONSTRAINT confirmacaorematricula_calendario_ed52_i_codigo_fk FOREIGN KEY (edu01_calendario) REFERENCES escola.calendario (ed52_i_codigo),
              CONSTRAINT confirmacaorematricula_turma_ed57_i_codigo_fk FOREIGN KEY (edu01_turma) REFERENCES escola.turma (ed57_i_codigo),
              CONSTRAINT confirmacaorematricula_aluno_ed47_i_codigo_fk FOREIGN KEY (edu01_aluno) REFERENCES escola.aluno (ed47_i_codigo)
            );        
        ";

        $this->execute($sql);
    }

    private function upDicionario()
    {
        $sql = "
            INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform)
            VALUES (1010330, 'confirmacaorematricula', 'Confirmação de Rematrícula', 'edu01', current_date, 'Confirmação de Rematrícula', 0, DEFAULT, DEFAULT, DEFAULT, DEFAULT);
            
            INSERT INTO db_sysarqmod (codmod, codarq)
            VALUES (1008004, 1010330);
            
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
            VALUES (1010033, 'edu01_sequencial', 'int4', 'Sequencial', 0, 'Sequencial', 11, FALSE, DEFAULT, DEFAULT, 1, 'text', 'Sequencial'),
                   (1010034, 'edu01_escola', 'int4', 'Escola', 0, 'Escola', 11, FALSE, DEFAULT, DEFAULT, 1, 'text', 'Escola'),
                   (1010035, 'edu01_calendario', 'int4', 'Calendário', 0, 'Calendário', 11, FALSE, DEFAULT, DEFAULT, 1, 'text', 'Calendário'),
                   (1010036, 'edu01_turma', 'int4', 'Turma', 0, 'Turma', 11, FALSE, DEFAULT, DEFAULT, 1, 'text', 'Turma'),
                   (1010037, 'edu01_aluno', 'int4', 'Aluno', 0, 'Aluno', 11, FALSE, DEFAULT, DEFAULT, 1, 'text', 'Aluno'),
                   (1010038, 'edu01_criado_em', 'date', 'Criado Em', 0, 'Criado Em', 11, FALSE, DEFAULT, DEFAULT, 1, 'text', 'Criado Em');
            
            INSERT INTO db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq)
            VALUES (1000774, 'escola.confirmacaorematricula_edu01_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden)
            VALUES (1010330, 1010033, 1, 1010033);
            
            INSERT INTO db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel)
            VALUES (1010330, 1010034, 1, 1010031, DEFAULT),
                   (1010330, 1010035, 2, 1010057, DEFAULT),
                   (1010330, 1010036, 3, 1010083, DEFAULT),
                   (1010330, 1010037, 4, 1010051, DEFAULT);
            
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
            VALUES (1010330, 1010033, 1, 1000774),
                   (1010330, 1010034, 2, DEFAULT),
                   (1010330, 1010035, 3, DEFAULT),
                   (1010330, 1010036, 4, DEFAULT),
                   (1010330, 1010037, 5, DEFAULT),
                   (1010330, 1010038, 6, DEFAULT);
        ";

        $this->execute($sql);
    }

    private function upMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            VALUES (228052, 'Confirmação de Rematrícula', 'Confirmação de Rematrícula', '', DEFAULT, '1', 'Confirmação de Rematrícula', TRUE),
                   (228053, 'Inclusão', 'Inclusão', 'edu1_confirmacao_rematricula_1.php', DEFAULT, '1', 'Inclusão', TRUE),
                   (228054, 'Exclusão', 'Exclusão', 'edu1_confirmacao_rematricula_3.php', DEFAULT, '1', 'Exclusão', TRUE),
                   (228055, 'Confirmação de Rematrícula', 'Confirmação de Rematrícula', 'edu2_confirmacao_rematricula_1.php', DEFAULT, '1', 'Confirmação de Rematrícula', TRUE),
                   (228056, 'Confirmação de Rematrícula', 'Confirmação de Rematrícula', 'edu2_confirmacao_rematricula_1.php', DEFAULT, '1', 'Confirmação de Rematrícula', TRUE);
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
            VALUES (3470, 228052, 14, 1100747),
                   (228052, 228053, 1, 1100747),
                   (228052, 228054, 2, 1100747),
                   (4964, 228055, 11, 1100747),
                   (4964, 228056, 13, 7159);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
        $this->downMenu();
    }

    private function downEstrutura()
    {
        $sql = "
            DROP TABLE escola.confirmacaorematricula;
        ";

        $this->execute($sql);
    }

    private function downDicionario()
    {
        $sql = "
            DELETE
            FROM db_sysarqcamp
            WHERE codarq = 1010330;
            
            DELETE
            FROM db_sysforkey
            WHERE codarq = 1010330;
            
            DELETE
            FROM db_sysprikey
            WHERE codarq = 1010330;
            
            DELETE
            FROM db_syssequencia
            WHERE codsequencia = 1000774;
            
            DELETE
            FROM db_syscampo
            WHERE codcam IN (1010033, 1010034, 1010035, 1010036, 1010037, 1010038);
            
            DELETE
            FROM db_sysarqmod
            WHERE codarq = 1010330;
            
            DELETE
            FROM db_sysarquivo
            WHERE codarq = 1010330;
        ";

        $this->execute($sql);
    }

    private function downMenu()
    {
        $sql = "
            DELETE
            FROM db_menu
            WHERE id_item_filho IN (228052, 228053, 228054, 228055, 228056);
            
            DELETE
            FROM db_itensmenu
            WHERE id_item IN (228052, 228053, 228054, 228055, 228056);
        ";

        $this->execute($sql);
    }
}
