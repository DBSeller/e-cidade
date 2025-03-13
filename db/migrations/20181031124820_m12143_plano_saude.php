<?php

use Classes\PostgresMigration;

/**
 * Class M12143PlanoSaude
 */
class M12143PlanoSaude extends PostgresMigration
{
    /**
     *
     */
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
        $this->upMenu();
    }

    /**
     *
     */
    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
        $this->downMenu();
    }

    /**
     *
     */
    private function upEstrutura()
    {
        $sql = "      
            CREATE TABLE pessoal.operadorasaude
            (
              rh221_sequencial SERIAL PRIMARY KEY    NOT NULL,
              rh221_cgm        int                   not null,
              rh221_ans        NUMERIC(6)            NOT NULL,
              rh221_ativo      BOOLEAN DEFAULT TRUE  NOT NULL,
              CONSTRAINT operadorasaude_cgm_fk FOREIGN KEY (rh221_cgm) REFERENCES protocolo.cgm (z01_numcgm)
            );

            CREATE UNIQUE INDEX operadorasaude_cgm_in ON operadorasaude(rh221_cgm);
            CREATE UNIQUE INDEX operadorasaude_rh221_ans_uindex ON pessoal.operadorasaude (rh221_ans);
            
            CREATE TABLE pessoal.servidoroperadorasaude
            (
              rh222_sequencial     SERIAL PRIMARY KEY NOT NULL,
              rh222_valor          NUMERIC(15, 2)     NOT NULL,
              rh222_ano            NUMERIC(4)         NOT NULL,
              rh222_mes            NUMERIC(2)         NOT NULL,
              rh222_instituicao    INT                NOT NULL,
              rh222_operadorasaude INT                NOT NULL,
              rh222_servidor       INT                NOT NULL,
              rh222_rubrica        VARCHAR(4)         NOT NULL,
              CONSTRAINT servidoroperadorasaude_operadorasaude_rh221_sequencial_fk FOREIGN KEY (rh222_operadorasaude) REFERENCES pessoal.operadorasaude (rh221_sequencial),
              CONSTRAINT servidoroperadorasaude_rhpessoal_rh01_regist_fk FOREIGN KEY (rh222_servidor) REFERENCES pessoal.rhpessoal (rh01_regist),
              CONSTRAINT servidoroperadorasaude_rhrubricas_rh27_rubric_rh27_instit_fk FOREIGN KEY (rh222_rubrica, rh222_instituicao) REFERENCES pessoal.rhrubricas (rh27_rubric, rh27_instit)
            );
            CREATE UNIQUE INDEX servidoroperadorasaude_rh222_sequencial_uindex ON pessoal.servidoroperadorasaude (rh222_sequencial);
            
            CREATE TABLE pessoal.servidoroperadorasaudedependente
            (
              rh223_sequencial             SERIAL PRIMARY KEY NOT NULL,
              rh223_tipo                   VARCHAR(2)         NOT NULL,
              rh223_valor                  NUMERIC(15, 2)     NOT NULL,
              rh223_dependente             INT                NOT NULL,
              rh223_servidoroperadorasaude INT                NOT NULL,
              CONSTRAINT servidoroperadorasaudedependente_servidoroperadorasaude_rh222_sequencial_fk FOREIGN KEY (rh223_servidoroperadorasaude) REFERENCES pessoal.servidoroperadorasaude (rh222_sequencial),
              CONSTRAINT servidoroperadorasaudedependente_rhdepend_rh31_codigo_fk FOREIGN KEY (rh223_dependente) REFERENCES pessoal.rhdepend (rh31_codigo)
            );
            CREATE UNIQUE INDEX servidoroperadorasaudedependente_rh223_sequencial_uindex
              ON pessoal.servidoroperadorasaudedependente (rh223_sequencial);
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    private function upDicionario()
    {
        $sql = "
            INSERT INTO db_sysarquivo
            VALUES (1010333, 'operadorasaude', 'Operadora de Saúde', 'rh221', '2018-10-31', 'Operadora de Saúde', 0, 'f', 'f', 'f', 'f'),
                   (1010334, 'servidoroperadorasaude', 'Operadora de Saúde do Servidor ', 'rh222', '2018-10-31', 'Operadora de Saúde do Servidor ', 0, 'f', 'f', 'f', 'f'),
                   (1010335, 'servidoroperadorasaudedependente', 'Dependente do Servidor no Plano de Saúde', 'rh223', '2018-10-31', 'Dependente do Servidor no Plano de Saúde', 0, 'f', 'f', 'f', 'f');
            
            INSERT INTO db_sysarqmod
            VALUES (28, 1010333),
                   (28, 1010334),
                   (28, 1010335);
            
            INSERT INTO db_syscampo
            VALUES (1010048, 'rh221_sequencial', 'int4', 'Sequencial', '0', 'Sequencial', 8, 'f', 'f', 't', 1, 'text', 'Sequencial'),
                   (1010069, 'rh221_cgm', 'int4', 'Operadora', '0', 'Operadora', 10, 'f', 'f', 'f', 1, 'text', 'Operadora'),
                   (1010051, 'rh221_ans', 'int4', 'ANS', '0', 'ANS', 6, 'f', 'f', 'f', 1, 'text', 'ANS'),
                   (1010052, 'rh221_ativo', 'bool', 'Ativo', 'f', 'Ativo', 1, 'f', 'f', 'f', 5, 'text', 'Ativo'),
                   (1010053, 'rh222_sequencial', 'int4', 'Sequencial', '0', 'Sequencial', 8, 'f', 'f', 't', 1, 'text', 'Sequencial'),
                   (1010054, 'rh222_valor', 'float8', 'Valor', '0', 'Valor', 15, 'f', 'f', 'f', 4, 'text', 'Valor'),
                   (1010055, 'rh222_ano', 'int4', 'Ano', '0', 'Ano', 4, 'f', 'f', 'f', 1, 'text', 'Ano'),
                   (1010056, 'rh222_mes', 'int4', 'Mês', '0', 'Mês', 2, 'f', 'f', 'f', 1, 'text', 'Mês'),
                   (1010057, 'rh222_instituicao', 'int4', 'Instituição', '0', 'Instituição', 8, 'f', 'f', 'f', 1, 'text', 'Instituição'),
                   (1010058, 'rh222_operadorasaude', 'int4', 'Operadora de Saúde', '0', 'Operadora de Saúde', 8, 'f', 'f', 'f', 1, 'text', 'Operadora de Saúde'),
                   (1010059, 'rh222_servidor', 'int4', 'Servidor', '0', 'Servidor', 8, 'f', 'f', 'f', 1, 'text', 'Servidor'),
                   (1010060, 'rh222_rubrica', 'varchar(4)', 'Rubrica', '0', 'Rubrica', 4, 'f', 'f', 'f', 1, 'text', 'Rubrica'),
                   (1010061, 'rh223_sequencial', 'int4', 'Sequencial', '0', 'Sequencial', 8, 'f', 'f', 't', 1, 'text', 'Sequencial'),
                   (1010062, 'rh223_tipo', 'varchar(2)', 'Tipo', '0', 'Tipo', 2, 'f', 'f', 'f', 1, 'text', 'Tipo'),
                   (1010066, 'rh223_valor', 'float8', 'Valor', '0', 'Valor', 15, 'f', 'f', 'f', 4, 'text', 'Valor'),
                   (1010067, 'rh223_servidoroperadorasaude', 'int4', 'Operadora de Saúde do Servidor', '0', 'Operadora de Saúde do Servidor', 8, 'f', 'f', 'f', 1, 'text', 'Operadora de Saúde do Servidor'),
                   (1010068, 'rh223_dependente', 'int4', 'Dependente', '0', 'Dependente', 8, 'f', 'f', 'f', 1, 'text', 'Dependente');
            
            INSERT INTO db_syssequencia
            VALUES (1000777, 'operadorasaude_rh221_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000778, 'servidoroperadorasaude_rh222_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000779, 'servidoroperadorasaudedependente_rh223_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            
            INSERT INTO db_sysarqcamp
            VALUES (1010333,1010048,1,1000777),
                   (1010333,1010069,2,0),
                   (1010333,1010052,3,0),
                   (1010333,1010051,4,0),                   
                   (1010334, 1010053, 1, 1000778),
                   (1010334, 1010054, 2, 0),
                   (1010334, 1010060, 3, 0),
                   (1010334, 1010059, 4, 0),
                   (1010334, 1010058, 5, 0),
                   (1010334, 1010057, 6, 0),
                   (1010334, 1010056, 7, 0),
                   (1010334, 1010055, 8, 0),
                   (1010335, 1010068, 1, 0),
                   (1010335, 1010067, 2, 0),
                   (1010335, 1010066, 3, 0),
                   (1010335, 1010062, 4, 0),
                   (1010335, 1010061, 5, 1000779);
            
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden)
            VALUES (1010333, 1010048, 1, 1010048),
                   (1010334, 1010053, 1, 1010053),
                   (1010335, 1010061, 1, 1010067);
            
            INSERT INTO db_sysindices
            VALUES (1008341, 'operadorasaude_rh221_sequencial_uindex', 1010333, '1'),
                   (1008343, 'operadorasaude_rh221_ans_uindex', 1010333, '1'),
                   (1008344, 'servidoroperadorasaude_rh222_sequencial_uindex', 1010334, '1'),
                   (1008346, 'operadorasaude_cgm_in', 1010333, '1'),
                   (1008345, 'servidoroperadorasaudedependente_rh223_sequencial_uindex', 1010335, '1');
            
            INSERT INTO db_syscadind
            VALUES (1008341, 1010048, 1),
                   (1008343, 1010051, 1),
                   (1008344, 1010053, 1),
                   (1008346, 1010069, 1),
                   (1008345, 1010067, 1);
            
            INSERT INTO db_sysforkey
            VALUES (1010334, 1010058, 1, 1010333, 0),
                   (1010334, 1010059, 1, 1153, 0),
                   (1010334, 1010060, 1, 1177, 0),
                   (1010334, 1010057, 2, 1177, 0),
                   (1010335, 1010067, 1, 1010334, 0),
                   (1010335, 1010068, 1, 1186, 0),
                   (1010333, 1010069, 1, 42, 0);
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    private function upMenu()
    {

        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            VALUES (228057, 'Operadora de Plano de Saúde', 'Operadora de Plano de Saúde', 'pes1_operadora001.php', '1', '1', 'Operadora de Plano de Saúde', 'true'),
                   (228058, 'Plano de Saúde', 'Plano de Saúde', 'pes1_plano_saude_servidor001.php', '1', '1', 'Plano de Saúde do servidor', 'true');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
            VALUES ( 4374 ,228057 ,25 ,952 ),
                   (4354, 228058, 6, 952);
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    private function downEstrutura()
    {
        $sql = "
            DROP TABLE pessoal.servidoroperadorasaudedependente;
            DROP TABLE pessoal.servidoroperadorasaude;
            DROP TABLE pessoal.operadorasaude;         
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    private function downDicionario()
    {
        $sql = "
            DELETE FROM db_acount WHERE codarq IN (1010333, 1010334, 1010335);

            DELETE FROM db_sysforkey WHERE codarq IN (1010334, 1010335, 1010333);
            
            DELETE FROM db_syscadind WHERE codind IN (1008341, 1008346, 1008343, 1008344, 1008345);
            
            DELETE FROM db_sysindices WHERE codind IN (1008341, 1008346, 1008343, 1008344, 1008345);
            
            DELETE FROM db_sysprikey WHERE codarq IN (1010333, 1010334, 1010335);
            
            DELETE FROM db_sysarqcamp WHERE codarq IN (1010333, 1010334, 1010335);
            
            DELETE FROM db_syssequencia WHERE codsequencia IN (1000777, 1000778, 1000779);
            
            DELETE FROM db_syscampo WHERE codcam IN (1010048, 1010069, 1010051, 1010052, 1010053, 1010054, 1010055, 1010056, 1010057, 1010058, 1010059, 1010060, 1010061, 1010062, 1010066, 1010067, 1010068);
            
            DELETE FROM db_sysarqmod WHERE codarq IN (1010333, 1010334, 1010335);
            
            DELETE FROM db_sysarquivo WHERE codarq IN (1010333, 1010334, 1010335);
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    private function downMenu()
    {
        $sql = "
            DELETE 
            FROM db_menu 
            WHERE id_item_filho IN (228057, 228058);
            
            DELETE 
            FROM db_itensmenu 
            WHERE id_item IN (228057, 228058);
        ";

        $this->execute($sql);
    }
}
