<?php

use Classes\PostgresMigration;

class M18242CriaTabelaCgmCamposObrigatorios extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            INSERT INTO db_sysarquivo values (1010803, 'cgm_campos_obrigatorios', 'Tabela que identifica quais campos do CGM são obrigatórios.', 'p73', '2021-05-26', 'Campos Obrigatórios CGM', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod values (4,1010803);
            INSERT INTO db_syscampo values(1013268,'p73_sequencial','int4','Sequencial da tabela.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo values(1013269,'p73_label','varchar(60)','Label do campo.','', 'Label',60,'f','f','f',0,'text','Label');
            INSERT INTO db_syscampo values(1013270,'p73_obrigatorio','bool','Coluna indica se o campo é obrigatório.','f', 'Obrigatório',1,'f','f','f',5,'text','Obrigatório');
            insert INTO db_syscampo values(1013271,'p73_html_id','varchar(60)','ID do input HTML para fazer a validação dos campos obrigatórios.','', 'ID HTML',60,'f','f','f',0,'text','ID HTML');
            insert into db_syscampo values(1013272,'p73_tipo_pessoa','varchar(60)','Indica se é pessoa física ou jurídica.','', 'Tipo de Pessoa',60,'f','f','f',0,'text','Tipo de Pessoa');
            INSERT INTO db_sysarqcamp values(1010803,1013268,1,0);
            INSERT INTO db_sysarqcamp values(1010803,1013269,2,0);
            INSERT INTO db_sysarqcamp values(1010803,1013270,3,0);
            insert INTO db_sysarqcamp values(1010803,1013271,4,0);
            insert into db_sysarqcamp values(1010803,1013272,5,0);
            DELETE FROM db_sysprikey where codarq = 1010803;
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) values(1010803,1013268,1,1013268);

            CREATE TABLE protocolo.cgm_campos_obrigatorios(
                p73_sequencial INT,
                p73_label VARCHAR(60) NOT NULL,
                p73_obrigatorio BOOLEAN NOT NULL DEFAULT false,
                p73_html_id VARCHAR(60) NOT NULL,
                p73_tipo_pessoa VARCHAR(60) NOT NULL,
                CONSTRAINT cgm_campos_obrigatorios_p73_sequencial_seq_pk PRIMARY KEY (p73_sequencial)
            );

            CREATE SEQUENCE protocolo.cgm_campos_obrigatorios_p73_sequencial_seq
                            INCREMENT 1
                            MINVALUE 1 
                            MAXVALUE 9223372036854775807 
                            START 1
                            CACHE 1;

            -- CAMPOS OBRIGATÓRIOS PESSOA FÍSICA
            INSERT INTO cgm_campos_obrigatorios
                (p73_sequencial, p73_label, p73_obrigatorio, p73_html_id, p73_tipo_pessoa)
            VALUES
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Nome Completo', 'f', 'z01_nomecomple', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Nascimento', 'f', 'z01_nasc', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Nome da Mãe', 'f', 'z01_mae', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Nome do Pai', 'f', 'z01_pai', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Telefone', 'f', 'z01_telef', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Identidade', 'f', 'z01_ident', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Órgão Emissor', 'f', 'z01_identorgao', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Naturalidade', 'f', 'z01_naturalidade', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Estado Civil', 'f', 'z01_estciv', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Escolaridade', 'f', 'z01_escolaridade', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Fax', 'f', 'z01_fax', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Email', 'f', 'z01_email', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Caixa Postal', 'f', 'z01_cxpostal', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Profissão', 'f', 'z01_profis', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'CBO', 'f', 'z04_rhcbo', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'PIS/PASEP/CI', 'f', 'z01_pis', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Renda', 'f', 'z01_renda', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Local de Trabalho', 'f', 'z01_localtrabalho', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Telefone Comercial', 'f', 'z01_telcon', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Celular Comercial', 'f', 'z01_celcon', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Celular', 'f', 'z01_telcel', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Email Comercial', 'f', 'z01_emailc', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Observações', 'f', 'z01_obs', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Data Expedição', 'f', 'z01_identdtexp', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Bairro', 'f', 'txtDescrBairropri', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Rua', 'f', 'txtDescrRuapri', 'fisica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'CEP', 'f', 'txtCepEndpri', 'fisica');

            -- CAMPOS OBRIGATÓRIOS PESSOA JURÍDICA
            INSERT INTO cgm_campos_obrigatorios
                (p73_sequencial, p73_label, p73_obrigatorio, p73_html_id, p73_tipo_pessoa)
            VALUES
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Nome Completo', 'f', 'z01_nomecomple', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Nome Fantasia', 'f', 'z01_nomefanta', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Contato', 'f', 'z01_contato', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Tipo Empresa', 'f', 'z03_tipoempresa', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Inscrição Estadual', 'f', 'z01_incest', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Nire', 'f', 'z08_nire', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Telefone', 'f', 'z01_telef', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Celular', 'f', 'z01_telcel', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Fax', 'f', 'z01_fax', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Email', 'f', 'z01_email', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Telefone Comercial', 'f', 'z01_telcon', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Celular Comercial', 'f', 'z01_celcon', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Email Comercial', 'f', 'z01_emailc', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Observações', 'f', 'z01_obs', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Bairro', 'f', 'txtDescrBairropri', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'Rua', 'f', 'txtDescrRuapri', 'juridica'),
                (nextval('cgm_campos_obrigatorios_p73_sequencial_seq'), 'CEP', 'f', 'txtCepEndpri', 'juridica');
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            DELETE FROM db_sysarqcamp where codarq = 1010803;
            DELETE FROM db_sysprikey WHERE codarq = 1010803;
            DELETE FROM db_sysarqmod WHERE codarq = 1010803;
            DELETE FROM db_sysarquivo WHERE codarq = 1010803;
            DELETE FROM db_syscampo WHERE codcam IN (1013268, 1013269, 1013270, 1013271, 1013272);
            DROP SEQUENCE IF EXISTS cgm_campos_obrigatorios_p73_sequencial_seq;
            DROP TABLE cgm_campos_obrigatorios;
SQL;

        $this->execute($sql);
    }
}