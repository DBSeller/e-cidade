<?php

use Classes\PostgresMigration;

class M11905CamposTabelaRubricas extends PostgresMigration
{
    public function up()
    {
        $sql = "

            CREATE SEQUENCE esocial.esocialrubricas_eso26_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;

            CREATE TABLE esocial.esocialrubricas
            (
              eso26_sequencial                         INTEGER DEFAULT nextval('esocial.esocialrubricas_eso26_sequencial_seq') NOT NULL PRIMARY KEY,
              eso26_rubrica                            CHARACTER(4) NOT NULL,
              eso26_instituicao                        INT NOT NULL,
              eso26_avaliacaoperguntaopcaocodinccp     INT,
              eso26_avaliacaoperguntaopcaocodincirrf   INT,
              eso26_avaliacaoperguntaopcaocodincfgts   INT,
              eso26_avaliacaoperguntaopcaocodincsind   INT,
              eso26_natureza                           INT,
              eso26_datainicial                        DATE NOT NULL,
              eso26_datafinal                          DATE,
              CONSTRAINT esocialrubricas_rhrubricas_rh27_rubric_rh27_instit_fk FOREIGN KEY (eso26_rubrica, eso26_instituicao) REFERENCES pessoal.rhrubricas (rh27_rubric, rh27_instit),
              CONSTRAINT esocialrubricas_avaliacaoperguntaopcao_db104_sequencial_fk FOREIGN KEY (eso26_avaliacaoperguntaopcaocodinccp) REFERENCES habitacao.avaliacaoperguntaopcao (db104_sequencial),
              CONSTRAINT esocialrubricas_avaliacaoperguntaopcao_db104_sequencial_fk_3 FOREIGN KEY (eso26_avaliacaoperguntaopcaocodincirrf) REFERENCES habitacao.avaliacaoperguntaopcao (db104_sequencial),
              CONSTRAINT esocialrubricas_avaliacaoperguntaopcao_db104_sequencial_fk_4 FOREIGN KEY (eso26_avaliacaoperguntaopcaocodincfgts) REFERENCES habitacao.avaliacaoperguntaopcao (db104_sequencial),
              CONSTRAINT esocialrubricas_avaliacaoperguntaopcao_db104_sequencial_fk_5 FOREIGN KEY (eso26_avaliacaoperguntaopcaocodincsind) REFERENCES habitacao.avaliacaoperguntaopcao (db104_sequencial)
            );
            
            INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo)
            VALUES (1010325, 'esocialrubricas', 'Informações da Rubrica', 'eso26', current_date, 'Informações da Rubrica');
            
            INSERT INTO db_sysarqmod
            VALUES (81, 1010325);
            
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, aceitatipo, tipoobj, rotulorel)
            VALUES (1009986, 'eso26_sequencial', 'int4', 'Sequencial', '0', 'Sequencial', 10, false, 1, 'text', 'Sequencial'),
                   (1009987, 'eso26_rubrica', 'varchar(4)', 'Rubrica', '0', 'Rubrica', 10, false, 1, 'text', 'Rubrica'),
                   (1009988, 'eso26_instituicao', 'int4', 'Instituição', '0', 'Instituição', 10, false, 1, 'text', 'Instituição'),
                   (1009989, 'eso26_avaliacaoperguntaopcaocodinccp', 'int4', 'codIncCP', '0', 'codIncCP', 10, true, 1, 'text', 'codIncCP'),
                   (1009991, 'eso26_avaliacaoperguntaopcaocodincirrf', 'int4', 'codIncIRRF', '0', 'codIncIRRF', 10, true, 1, 'text', 'codIncIRRF'),
                   (1009992, 'eso26_avaliacaoperguntaopcaocodincfgts', 'int4', 'codIncFGTS', '0', 'codIncFGTS', 10, true, 1, 'text', 'codIncFGTS'),
                   (1009993, 'eso26_avaliacaoperguntaopcaocodincsind', 'int4', 'codIncSIND', '0', 'codIncSIND', 10, true, 1, 'text', 'codIncSIND'),
                   (1009994, 'eso26_natureza', 'int4', 'Natureza', '0', 'Natureza', 10, true, 1, 'text', 'Natureza');
                   
            insert into db_syscampo values(1010003,'eso26_datainicial','date','Data de início de validade.','null', 'Início de validade',10,'f','f','f',1,'text','Início de validade');
            insert into db_syscampo values(1010004,'eso26_datafinal','date','Data final da validade.','null', 'Fim de validade',10,'t','f','f',1,'text','Fim de validade');

            
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq)
            VALUES (1010325, 1009986, 1),
                   (1010325, 1009987, 2),
                   (1010325, 1009988, 3),
                   (1010325, 1009989, 4),
                   (1010325, 1009991, 5),
                   (1010325, 1009992, 6),
                   (1010325, 1009993, 7),
                   (1010325, 1009994, 8),
                   (1010325, 1010003, 9),
                   (1010325, 1010004, 10);
            
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden)
            VALUES (1010325, 1009986, 1, 1009986);
            
            INSERT INTO db_syssequencia
            VALUES (1000770, 'esocialrubricas_eso26_sequencial_seq', 1, 1, 9000000000000000000, 1, 1);
            

            UPDATE db_sysarqcamp
            SET codsequencia = 1000770
            WHERE codarq = 1010325
              AND codcam = 1009986;
              
            INSERT INTO db_sysforkey (codarq, codcam, sequen, referen)
            VALUES (1010325, 1009987, 1, 1177),
                   (1010325, 1009988, 2, 1177),
                   (1010325, 1009989, 3, 2985),
                   (1010325, 1009991, 4, 2985),
                   (1010325, 1009992, 5, 2985),
                   (1010325, 1009993, 6, 2985);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE
            FROM db_sysforkey
            WHERE codarq = 1010325;
            
            DELETE
            FROM db_syssequencia
            WHERE codsequencia = 1000770;
            
            DELETE
            FROM db_sysprikey
            WHERE codarq = 1010325;
            
            DELETE
            FROM db_sysarqcamp
            WHERE codarq = 1010325;
            
            DELETE
            FROM db_syscampo
            WHERE codcam IN (1009986, 1009987, 1009988, 1009989, 1009991, 1009992, 1009993, 1009994, 1010003, 1010004);
            
            DELETE
            FROM db_sysarqmod
            WHERE codarq = 1010325;
            
            DELETE
            FROM db_sysarquivo
            WHERE codarq = 1010325;
            
            DROP TABLE esocial.esocialrubricas;
            DROP SEQUENCE esocial.esocialrubricas_eso26_sequencial_seq;
        ";

        $this->execute($sql);
    }
}
