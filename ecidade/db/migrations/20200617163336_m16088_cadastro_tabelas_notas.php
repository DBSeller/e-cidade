<?php

use Classes\PostgresMigration;

class M16088CadastroTabelasNotas extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->upDicionarioSchema();
        $this->upSchema();
        $this->upDDLUsuariosContribuintes();
        $this->upDDLImportacaoDesif();
        $this->upDDLImportacaoDms();
        $this->upDDLImportacaoDmsNota();
        $this->upGuias();
        $this->upDms();
        $this->upDDLGuiasDms();
        $this->upDDLGuiasNota();
        $this->upDDLGuiasNumpre();
        $this->upDicionario();
        $this->upNotas();
        $this->upNotasServicos();
        $this->upDmsNota();
        $this->upDDLDmsNotaServicos();
        $this->upCompetencias();
    }

    public function down()
    {
        $this->downCompetencias();
        $this->downDDLDmsNotaServicos();
        $this->downDmsNota();
        $this->downNotasServicos();
        $this->downDicionario();
        $this->downNotas();
        $this->downDDLGuiasNumpre();
        $this->downDDLGuiasNota();
        $this->downDDLGuiasDms();
        $this->downDms();
        $this->downGuias();
        $this->downDDLImportacaoDmsNota();
        $this->downDDLImportacaoDms();
        $this->downDDLImportacaoDesif();
        $this->downDDLUsuariosContribuintes();
        $this->downSchema();
    }

    public function upSchema()
    {
    $this->execute(
    <<<SQL
        CREATE SCHEMA nfse;
SQL
        );
    }

    public function upDicionarioSchema()
    {
        $this->execute(
        <<<SQL
        insert into db_sysmodulo values (83,'nfse','Esquema para guardar tabelas do Notas','2020-06-17','t');
SQL
        );
    }

    public function upNotas()
    {
        $this->execute(
        <<<SQL
        CREATE SEQUENCE nfse.notas_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

        CREATE TABLE nfse.notas (
            id                           BIGINT NOT NULL,
            id_contribuinte              BIGINT NOT NULL,
            id_usuario                   BIGINT NOT NULL,
            nota                         BIGINT NOT NULL,
            dt_nota                      DATE,
            hr_nota                      TIME WITHOUT TIME ZONE,
            n_rps                        BIGINT,
            data_rps                     DATE,
            cod_verificacao              CHARACTER VARYING(10),
            mes_comp                     INTEGER NOT NULL,
            ano_comp                     INTEGER NOT NULL,
            tipo_nota                    INTEGER,
            grupo_nota                   INTEGER NOT NULL,
            natureza_operacao            INTEGER NOT NULL,
            emite_guia                   BOOLEAN DEFAULT FALSE NOT NULL,
            cancelada                    BOOLEAN DEFAULT FALSE,
            cancelamento_justificativa   TEXT,
            id_nota_substituta           BIGINT, CHECK(id_nota_substituta  <> id),
            id_nota_substituida          BIGINT, CHECK(id_nota_substituida <> id),
            outras_informacoes           CHARACTER VARYING(255),
            importada                    BOOLEAN DEFAULT FALSE NOT NULL,
            vl_liquido_nfse              NUMERIC(15,2) DEFAULT 0,
            vl_credito                   NUMERIC(15,2) DEFAULT 0,
            s_codigo_obra                CHARACTER VARYING,
            s_art                        CHARACTER VARYING,
            s_informacoes_complementares TEXT,
            s_dados_discriminacao        TEXT,
            s_vl_outras_retencoes        NUMERIC(15,2) DEFAULT 0,
            s_vl_desc_incondicionado     NUMERIC(15,2) DEFAULT 0,
            s_vl_condicionado            NUMERIC(15,2) DEFAULT 0,
            s_dados_iss_retido           NUMERIC(1,0) DEFAULT 2,
            s_dados_resp_retencao        NUMERIC(1,0) DEFAULT 1,
            s_dados_item_lista_servico   CHARACTER VARYING(5),
            s_dados_cod_municipio        NUMERIC(7,0),
            s_dados_cod_pais             CHARACTER VARYING(5),
            s_dados_exigibilidadeiss     NUMERIC(2,0) DEFAULT 1,
            s_dados_municipio_incidencia NUMERIC(7,0),
            s_dados_num_processo         CHARACTER VARYING(30),
            s_dec_cc_cod_obra            CHARACTER VARYING(30),
            s_dec_cc_art                 CHARACTER VARYING(30),
            s_dec_reg_esp_tributacao     NUMERIC(5,0) DEFAULT 0,
            s_dec_incentivo_fiscal       NUMERIC(5,0) DEFAULT 2,
            s_dec_simples_nacional       NUMERIC(5,0) DEFAULT 2,
            s_dados_cod_tributacao       BIGINT,
            s_dados_cod_cnae             CHARACTER VARYING,
            s_vl_servicos                NUMERIC(15,2),
            s_vl_deducoes                NUMERIC(15,2) DEFAULT 0,
            s_vl_bc                      NUMERIC(15,2),
            s_vl_aliquota                NUMERIC(4,2),
            s_vl_iss                     NUMERIC(15,2) DEFAULT 0,
            s_vl_pis                     NUMERIC(15,2) DEFAULT 0,
            s_vl_cofins                  NUMERIC(15,2) DEFAULT 0,
            s_vl_inss                    NUMERIC(15,2) DEFAULT 0,
            s_vl_ir                      NUMERIC(15,2) DEFAULT 0,
            s_vl_csll                    NUMERIC(15,2) DEFAULT 0,
            p_cnpjcpf                    CHARACTER VARYING(14),
            p_im character               VARYING(20),
            p_ie character               VARYING(20),
            p_razao_social               CHARACTER VARYING,
            p_nome_fantasia              CHARACTER VARYING(255),
            p_endereco                   CHARACTER VARYING(125),
            p_endereco_numero            CHARACTER VARYING(30),
            p_endereco_comp              CHARACTER VARYING,
            p_bairro                     CHARACTER VARYING(60),
            p_cod_municipio              NUMERIC(7,0),
            p_uf                         CHARACTER(2),
            p_cod_pais                   CHARACTER(5),
            p_cep                        CHARACTER(8),
            p_telefone                   CHARACTER VARYING(20),
            p_email                      CHARACTER VARYING(80),
            t_cnpjcpf                    CHARACTER VARYING(14),
            t_im                         CHARACTER VARYING(20),
            t_ie                         CHARACTER VARYING(20),
            t_razao_social               CHARACTER VARYING(150),
            t_nome_fantasia              CHARACTER VARYING,
            t_endereco                   CHARACTER VARYING(125),
            t_endereco_numero            CHARACTER VARYING(30),
            t_endereco_comp              CHARACTER VARYING,
            t_bairro character           VARYING(60),
            t_cod_municipio              NUMERIC(7,0),
            t_uf                         CHARACTER(2),
            t_cod_pais                   CHARACTER(5),
            t_cep                        CHARACTER(8),
            t_telefone                   CHARACTER VARYING(20),
            t_email                      CHARACTER VARYING(80)
        );

        ALTER TABLE ONLY nfse.notas
                ADD CONSTRAINT notas_pkey PRIMARY KEY (id),
                ADD CONSTRAINT notas_cod_verificacao_key UNIQUE      (cod_verificacao),
                ADD CONSTRAINT notas_nota_p_cnpjcpf_key  UNIQUE      (nota, p_cnpjcpf),
                ADD CONSTRAINT notas_id_contribuinte_fk  FOREIGN KEY (id_contribuinte) REFERENCES nfse.usuarios_contribuintes(id);

        ALTER TABLE ONLY nfse.notas ALTER COLUMN id SET DEFAULT nextval('nfse.notas_id_seq'::regclass);
SQL
        );
    }

    public function upDicionario()
    {
        $this->execute(
        <<<SQL
        ------ Cria tabelas ------
        insert into db_sysarquivo values (1010575, 'notas', 'tabela notas', '', '2020-06-17', 'tabela notas', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010575);
        insert into db_sysarquivo values (1010576, 'notas_servicos', 'tabela notas_servicos', '', '2020-06-17', 'tabela notas_servicos', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010576);
        insert into db_sysarquivo values (1010577, 'dms', 'tabela dms', '', '2020-06-17', 'tabela dms', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010577);
        insert into db_sysarquivo values (1010578, 'dms_nota', 'tabela dms_nota', '', '2020-06-17', 'tabela dms_nota', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010578);
        insert into db_sysarquivo values (1010589, 'dms_nota_servicos', 'tabela dms_nota_servicos', '', '2020-06-17', 'tabela dms_nota_servicos', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010589);
        insert into db_sysarquivo values (1010579, 'competencias', 'tabela competencias', '', '2020-06-17', 'tabela competencias', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010579);
        insert into db_sysarquivo values (1010580, 'guias', 'tabela guias', '', '2020-06-17', 'tabela guias', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010580);
        insert into db_sysarquivo values (1010582, 'guias_numpre', 'Tabela guias_numpre do NFSE.', '', '2020-06-17', 'Tabela guias_numpre', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010582);
        insert into db_sysarquivo values (1010583, 'guias_dms', 'Tabela guias_dms do NFSE.', '', '2020-06-17', 'Tabela guias_dms', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010583);
        insert into db_sysarquivo values (1010584, 'guias_notas', 'Tabela guias_notas do NFSE.', '', '2020-06-17', 'Tabela guias_notas', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010584);
        insert into db_sysarquivo values (1010585, 'importacao_desif', 'Tabela importacao_desif do NFSE.', '', '2020-06-17', 'Tabela importacao_desif', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010585);
        insert into db_sysarquivo values (1010586, 'importacao_dms', 'Tabela importacao_dms do NFSE.', '', '2020-06-17', 'Tabela importacao_dms', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010586);
        insert into db_sysarquivo values (1010587, 'importacao_dms_nota', 'Tabela importacao_dms_nota do NFSE.', '', '2020-06-17', 'Tabela importacao_dms_nota', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010587);
        insert into db_sysarquivo values (1010588, 'usuarios_contribuintes', 'Tabela usuarios_contribuintes do NFSE.', '', '2020-06-17', 'Tabela usuarios_contribuintes', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (83,1010588);

        ------ Cria os campos ------
        insert into db_syscampo values (1011501, 's_vl_cofins', 'float', 's_vl_cofins', '0', 's_vl_cofins', 11, 'f', 'f', 'f', 1, 'text', 's_vl_cofins');
        insert into db_syscampo values (1011503, 's_vl_csll', 'float', 's_vl_csll', '0', 's_vl_csll', 11, 'f', 'f', 'f', 1, 'text', 's_vl_csll');
        insert into db_syscampo values (1011599, 'codigo_escritorio', 'int8', 'codigo_escritorio', '0', 'codigo_escritorio', 11, 'f', 'f', 'f', 1, 'text', 'codigo_escritorio');
        insert into db_syscampo values (1011409, 'p_uf', 'varchar', 'p_uf', '0', 'p_uf', 11, 'f', 'f', 'f', 1, 'text', 'p_uf');
        insert into db_syscampo values (1011392, 's_vl_aliquota', 'float', 's_vl_aliquota', '0', 's_vl_aliquota', 11, 'f', 'f', 'f', 1, 'text', 's_vl_aliquota');
        insert into db_syscampo values (1011436, 'emite_guia', 'bool', 'emite_guia', '0', 'emite_guia', 11, 'f', 'f', 'f', 1, 'text', 'emite_guia');
        insert into db_syscampo values (1011414, 't_cnpjcpf', 'varchar', 't_cnpjcpf', '0', 't_cnpjcpf', 11, 'f', 'f', 'f', 1, 'text', 't_cnpjcpf');
        insert into db_syscampo values (1011486, 'id_nota_substituida', 'int8', 'id_nota_substituida', '0', 'id_nota_substituida', 11, 'f', 'f', 'f', 1, 'text', 'id_nota_substituida');
        insert into db_syscampo values (1011489, 'vl_credito', 'float', 'vl_credito', '0', 'vl_credito', 11, 'f', 'f', 'f', 1, 'text', 'vl_credito');
        insert into db_syscampo values (1011383, 's_dados_discriminacao', 'varchar', 's_dados_discriminacao', '0', 's_dados_discriminacao', 11, 'f', 'f', 'f', 1, 'text', 's_dados_discriminacao');
        insert into db_syscampo values (1011385, 's_dados_cod_cnae', 'char', 's_dados_cod_cnae', '0', 's_dados_cod_cnae', 11, 'f', 'f', 'f', 1, 'text', 's_dados_cod_cnae');
        insert into db_syscampo values (1011594, 'competencia_inicial', 'varchar', 'competencia_inicial', '0', 'competencia_inicial', 11, 'f', 'f', 'f', 1, 'text', 'competencia_inicial');
        insert into db_syscampo values (1011345, 'id', 'int8', 'id', '0', 'id', 11, 'f', 'f', 'f', 1, 'text', 'id');
        insert into db_syscampo values (1011591, 'id_guia', 'int8', 'id_guia', '0', 'id_guia', 11, 'f', 'f', 'f', 1, 'text', 'id_guia');
        insert into db_syscampo values (1011441, 'id_nota', 'int', 'id_nota', '0', 'id_nota', 11, 'f', 'f', 'f', 1, 'text', 'id_nota');
        insert into db_syscampo values (1011440, 's_informacoes_complementares', 'varchar', 's_informacoes_complementares', '0', 's_informacoes_complementares', 11, 'f', 'f', 'f', 1, 'text', 's_informacoes_complementares');
        insert into db_syscampo values (1011431, 'codigo_nota_planilha', 'int8', 'codigo_nota_planilha', '0', 'codigo_nota_planilha', 11, 'f', 'f', 'f', 1, 'text', 'codigo_nota_planilha');
        insert into db_syscampo values (1011413, 'p_email', 'varchar', 'p_email', '0', 'p_email', 11, 'f', 'f', 'f', 1, 'text', 'p_email');
        insert into db_syscampo values (1011488, 'importada', 'bool', 'importada', '0', 'importada', 11, 'f', 'f', 'f', 1, 'text', 'importada');
        insert into db_syscampo values (1011580, 'im', 'int8', 'im', '0', 'im', 11, 'f', 'f', 'f', 1, 'text', 'im');
        insert into db_syscampo values (1011481, 'tipo_nota', 'int4', 'tipo_nota', '0', 'tipo_nota', 11, 'f', 'f', 'f', 1, 'text', 'tipo_nota');
        insert into db_syscampo values (1011444, 'vl_deducao', 'float8', 'vl_deducao', '0', 'vl_deducao', 11, 'f', 'f', 'f', 1, 'text', 'vl_deducao');
        insert into db_syscampo values (1011577, 'situacao', 'int8', 'situacao', '0', 'situacao', 11, 'f', 'f', 'f', 1, 'text', 'situacao');
        insert into db_syscampo values (1011358, 'n_rps', 'int8', 'n_rps', '0', 'n_rps', 10, 'f', 'f', 'f', 1, 'text', 'n_rps');
        insert into db_syscampo values (1011493, 's_dec_cc_cod_obra', 'varchar', 's_dec_cc_cod_obra', '0', 's_dec_cc_cod_obra', 11, 'f', 'f', 'f', 1, 'text', 's_dec_cc_cod_obra');
        insert into db_syscampo values (1011382, 'id_dms', 'int8', 'id_dms', '0', 'id_dms', 11, 'f', 'f', 'f', 1, 'text', 'id_dms');
        insert into db_syscampo values (1011487, 'outras_informacoes', 'varchar', 'outras_informacoes', '0', 'outras_informacoes', 11, 'f', 'f', 'f', 1, 'text', 'outras_informacoes');
        insert into db_syscampo values (1011450, 'item_servico', 'varchar', 'item_servico', '0', 'item_servico', 11, 'f', 'f', 'f', 1, 'text', 'item_servico');
        insert into db_syscampo values (1011502, 's_vl_ir', 'float', 's_vl_ir', '0', 's_vl_ir', 11, 'f', 'f', 'f', 1, 'text', 's_vl_ir');
        insert into db_syscampo values (1011576, 'arquivo_guia', 'varchar', 'arquivo_guia', '0', 'arquivo_guia', 11, 'f', 'f', 'f', 1, 'text', 'arquivo_guia');
        insert into db_syscampo values (1011386, 's_dados_iss_retido', 'bool', 's_dados_iss_retido', '0', 's_dados_iss_retido', 11, 'f', 'f', 'f', 1, 'text', 's_dados_iss_retido');
        insert into db_syscampo values (1011417, 't_ie', 'varchar', 't_ie', '0', 't_ie', 11, 'f', 'f', 'f', 1, 'text', 't_ie');
        insert into db_syscampo values (1011579, 's_dec_reg_esp_tributacao', 'float', 's_dec_reg_esp_tributacao', '0', 's_dec_reg_esp_tributacao', 11, 'f', 'f', 'f', 1, 'text', 's_dec_reg_esp_tributacao');
        insert into db_syscampo values (1011355, 'nota', 'int8', 'nota', '0', 'nota', 10, 'f', 'f', 'f', 1, 'text', 'nota');
        insert into db_syscampo values (1011410, 'p_cod_pais', 'varchar', 'p_cod_pais', '0', 'p_cod_pais', 11, 'f', 'f', 'f', 1, 'text', 'p_cod_pais');
        insert into db_syscampo values (1011415, 't_nome_fantasia', 'varchar', 't_nome_fantasia', '0', 't_nome_fantasia', 11, 'f', 'f', 'f', 1, 'text', 't_nome_fantasia');
        insert into db_syscampo values (1011423, 't_cod_municipio', 'float8', 't_cod_municipio', '0', 't_cod_municipio', 11, 'f', 'f', 'f', 1, 'text', 't_cod_municipio');
        insert into db_syscampo values (1011401, 'p_ie', 'varchar', 'p_ie', '0', 'p_ie', 11, 'f', 'f', 'f', 1, 'text', 'p_ie');
        insert into db_syscampo values (1011424, 't_uf', 'varchar', 't_uf', '0', 't_uf', 11, 'f', 'f', 'f', 1, 'text', 't_uf');
        insert into db_syscampo values (1011420, 't_endereco_numero', 'varchar', 't_endereco_numero', '0', 't_endereco_numero', 11, 'f', 'f', 'f', 1, 'text', 't_endereco_numero');
        insert into db_syscampo values (1011394, 's_vl_outras_retencoes', 'float8', 's_vl_outras_retencoes', '0', 's_vl_outras_retencoes', 11, 'f', 'f', 'f', 1, 'text', 's_vl_outras_retencoes');
        insert into db_syscampo values (1011393, 's_vl_inss', 'float8', 's_vl_inss', '0', 's_vl_inss', 11, 'f', 'f', 'f', 1, 'text', 's_vl_inss');
        insert into db_syscampo values (1011391, 's_vl_servicos', 'float8', 's_vl_servicos', '0', 's_vl_servicos', 11, 'f', 'f', 'f', 1, 'text', 's_vl_servicos');
        insert into db_syscampo values (1011447, 'qnt_servico', 'int8', 'qnt_servico', '0', 'qnt_servico', 11, 'f', 'f', 'f', 1, 'text', 'qnt_servico');
        insert into db_syscampo values (1011445, 'vl_desc_incondicional', 'float8', 'vl_desc_incondicional', '0', 'vl_desc_incondicional', 11, 'f', 'f', 'f', 1, 'text', 'vl_desc_incondicional');
        insert into db_syscampo values (1011607, 'operacao_nota', 'varchar(2)', 'operacao_nota', '0', 'operacao_nota', 11, 'f', 'f', 'f', 1, 'text', 'operacao_nota');
        insert into db_syscampo values (1011600, 'codigo_dms', 'int8', 'codigo_dms', '0', 'codigo_dms', 11, 'f', 'f', 'f', 1, 'text', 'codigo_dms');
        insert into db_syscampo values (1011480, 'ano_comp', 'date', 'ano_comp', '0', 'ano_comp', 11, 'f', 'f', 'f', 1, 'text', 'ano_comp');
        insert into db_syscampo values (1011485, 'id_nota_substituta', 'int8', 'id_nota_substituta', '0', 'id_nota_substituta', 11, 'f', 'f', 'f', 1, 'text', 'id_nota_substituta');
        insert into db_syscampo values (1011384, 's_dados_cod_tributacao', 'int8', 's_dados_cod_tributacao', '0', 's_dados_cod_tributacao', 11, 'f', 'f', 'f', 1, 'text', 's_dados_cod_tributacao');
        insert into db_syscampo values (1011497, 's_vl_deducoes', 'float', 's_vl_deducoes', '0', 's_vl_deducoes', 11, 'f', 'f', 'f', 1, 'text', 's_vl_deducoes');
        insert into db_syscampo values (1011359, 'data_rps', 'date', 'data_rps', 'null', 'data_rps', 10, 'f', 'f', 'f', 3, 'text', 'data_rps');
        insert into db_syscampo values (1011490, 's_dados_resp_retencao', 'float', 's_dados_resp_retencao', '0', 's_dados_resp_retencao', 11, 'f', 'f', 'f', 1, 'text', 's_dados_resp_retencao');
        insert into db_syscampo values (1011428, 't_email', 'varchar', 't_email', '0', 't_email', 11, 'f', 'f', 'f', 1, 'text', 't_email');
        insert into db_syscampo values (1011586, 'tipo_contribuinte', 'int8', 'tipo_contribuinte', '0', 'tipo_contribuinte', 11, 'f', 'f', 'f', 1, 'text', 'tipo_contribuinte');
        insert into db_syscampo values (1011482, 'grupo_nota', 'int4', 'grupo_nota', '0', 'grupo_nota', 11, 'f', 'f', 'f', 1, 'text', 'grupo_nota');
        insert into db_syscampo values (1011430, 's_codigo_servico', 'int8', 's_codigo_servico', '0', 's_codigo_servico', 11, 'f', 'f', 'f', 1, 'text', 's_codigo_servico');
        insert into db_syscampo values (1011449, 'codigo_tributacao', 'int8', 'codigo_tributacao', '0', 'codigo_tributacao', 11, 'f', 'f', 'f', 1, 'text', 'codigo_tributacao');
        insert into db_syscampo values (1011573, 'juros_multa', 'float8', 'juros_multa', '0', 'juros_multa', 11, 'f', 'f', 'f', 1, 'text', 'juros_multa');
        insert into db_syscampo values (1011572, 'valor_historico', 'float8', 'valor_historico', '0', 'valor_historico', 11, 'f', 'f', 'f', 1, 'text', 'valor_historico');
        insert into db_syscampo values (1011434, 'tipo_documento_descricao', 'varchar', 'tipo_documento_descricao', '0', 'tipo_documento_descricao', 11, 'f', 'f', 'f', 1, 'text', 'tipo_documento_descricao');
        insert into db_syscampo values (1011494, 's_dec_cc_art', 'varchar', 's_dec_cc_art', '0', 's_dec_cc_art', 11, 'f', 'f', 'f', 1, 'text', 's_dec_cc_art');
        insert into db_syscampo values (1011421, 't_endereco_comp', 'varchar', 't_endereco_comp', '0', 't_endereco_comp', 11, 'f', 'f', 'f', 1, 'text', 't_endereco_comp');
        insert into db_syscampo values (1011427, 't_telefone', 'varchar', 't_telefone', '0', 't_telefone', 11, 'f', 'f', 'f', 1, 'text', 't_telefone');
        insert into db_syscampo values (1011609, 'data_emissao_nota', 'date', 'data_emissao_nota', '0', 'data_emissao_nota', 11, 'f', 'f', 'f', 1, 'text', 'data_emissao_nota');
        insert into db_syscampo values (1011611, 'competencia', 'varchar(10)', 'competencia', '0', 'competencia', 11, 'f', 'f', 'f', 1, 'text', 'competencia');
        insert into db_syscampo values (1011425, 't_cod_pais', 'varchar', 't_cod_pais', '0', 't_cod_pais', 11, 'f', 'f', 'f', 1, 'text', 't_cod_pais');
        insert into db_syscampo values (1011582, 'habilitado', 'boolean', 'habilitado', '0', 'habilitado', 11, 'f', 'f', 'f', 1, 'text', 'habilitado');
        insert into db_syscampo values (1011442, 'vl_servico', 'float8', 'vl_servico', '0', 'vl_servico', 11, 'f', 'f', 'f', 1, 'text', 'vl_servico');
        insert into db_syscampo values (1011492, 's_dados_num_processo', 'varchar', 's_dados_num_processo', '0', 's_dados_num_processo', 11, 'f', 'f', 'f', 1, 'text', 's_dados_num_processo');
        insert into db_syscampo values (1011388, 's_dados_cod_pais', 'char', 's_dados_cod_pais', '0', 's_dados_cod_pais', 11, 'f', 'f', 'f', 1, 'text', 's_dados_cod_pais');
        insert into db_syscampo values (1011448, 'estrutural_cnae', 'varchar', 'estrutural_cnae', '0', 'estrutural_cnae', 11, 'f', 'f', 'f', 1, 'text', 'estrutural_cnae');
        insert into db_syscampo values (1011590, 'cnpj_cpf', 'varchar', 'cnpj_cpf', '0', 'cnpj_cpf', 11, 'f', 'f', 'f', 1, 'text', 'cnpj_cpf');
        insert into db_syscampo values (1011429, 'vl_liquido_nfse', 'float8', 'vl_liquido_nfse', '0', 'vl_liquido_nfse', 11, 'f', 'f', 'f', 1, 'text', 'vl_liquido_nfse');
        insert into db_syscampo values (1011484, 'cancelamento_justificativa', 'varchar', 'cancelamento_justificativa', '0', 'cancelamento_justificativa', 11, 'f', 'f', 'f', 1, 'text', 'cancelamento_justificativa');
        insert into db_syscampo values (1011403, 'p_nome_fantasia', 'varchar', 'p_nome_fantasia', '0', 'p_nome_fantasia', 11, 'f', 'f', 'f', 1, 'text', 'p_nome_fantasia');
        insert into db_syscampo values (1011356, 'dt_nota', 'date', 'dt_nota', 'null', 'dt_nota', 10, 'f', 'f', 'f', 3, 'text', 'dt_nota');
        insert into db_syscampo values (1011443, 'vl_aliquota', 'float8', 'vl_aliquota', '0', 'vl_aliquota', 11, 'f', 'f', 'f', 1, 'text', 'vl_aliquota');
        insert into db_syscampo values (1011439, 's_art', 'varchar', 's_art', '0', 's_art', 11, 'f', 'f', 'f', 1, 'text', 's_art');
        insert into db_syscampo values (1011496, 's_dec_simples_nacional', 'float', 's_dec_simples_nacional', '0', 's_dec_simples_nacional', 11, 'f', 'f', 'f', 1, 'text', 's_dec_simples_nacional');
        insert into db_syscampo values (1011587, 'id_dms_nota', 'int8', 'id_dms_nota', '0', 'id_dms_nota', 11, 'f', 'f', 'f', 1, 'text', 'id_dms_nota');
        insert into db_syscampo values (1011598, 'quantidade_notas', 'int8', 'quantidade_notas', '0', 'quantidade_notas', 11, 'f', 'f', 'f', 1, 'text', 'quantidade_notas');
        insert into db_syscampo values (1011404, 'p_endereco', 'varchar', 'p_endereco', '0', 'p_endereco', 11, 'f', 'f', 'f', 1, 'text', 'p_endereco');
        insert into db_syscampo values (1011437, 'natureza_operacao', 'int8', 'natureza_operacao', '0', 'natureza_operacao', 11, 'f', 'f', 'f', 1, 'text', 'natureza_operacao');
        insert into db_syscampo values (1011390, 's_dados_municipio_incidencia', 'float8', 's_dados_municipio_incidencia', '0', 's_dados_municipio_incidencia', 11, 'f', 'f', 'f', 1, 'text', 's_dados_municipio_incidencia');
        insert into db_syscampo values (1011408, 'p_cod_municipio', 'float8', 'p_cod_municipio', '0', 'p_cod_municipio', 11, 'f', 'f', 'f', 1, 'text', 'p_cod_municipio');
        insert into db_syscampo values (1011452, 'tributacao_municipio', 'int8', 'tributacao_municipio', '0', 'tributacao_municipio', 11, 'f', 'f', 'f', 1, 'text', 'tributacao_municipio');
        insert into db_syscampo values (1011605, 'numero_nota', 'int4', 'numero_nota', '0', 'numero_nota', 11, 'f', 'f', 'f', 1, 'text', 'numero_nota');
        insert into db_syscampo values (1011395, 's_vl_desc_incondicionado', 'float8', 's_vl_desc_incondicionado', '0', 's_vl_desc_incondicionado', 11, 'f', 'f', 'f', 1, 'text', 's_vl_desc_incondicionado');
        insert into db_syscampo values (1011451, 'tributacao_nao_incide', 'bool', 'tributacao_nao_incide', '0', 'tributacao_nao_incide', 11, 'f', 'f', 'f', 1, 'text', 'tributacao_nao_incide');
        insert into db_syscampo values (1011595, 'competencia_final', 'varchar', 'competencia_final', '0', 'competencia_final', 11, 'f', 'f', 'f', 1, 'text', 'competencia_final');
        insert into db_syscampo values (1011426, 't_cep', 'varchar', 't_cep', '0', 't_cep', 11, 'f', 'f', 'f', 1, 'text', 't_cep');
        insert into db_syscampo values (1011402, 'p_razao_social', 'varchar', 'p_razao_social', '0', 'p_razao_social', 11, 'f', 'f', 'f', 1, 'text', 'p_razao_social');
        insert into db_syscampo values (1011571, 'valor_corrigido', 'float8', 'valor_corrigido', '0', 'valor_corrigido', 11, 'f', 'f', 'f', 1, 'text', 'valor_corrigido');
        insert into db_syscampo values (1011357, 'hr_nota', 'date', 'hr_nota', 'null', 'hr_nota', 10, 'f', 'f', 'f', 3, 'text', 'hr_nota');
        insert into db_syscampo values (1011407, 'p_bairro', 'varchar', 'p_bairro', '0', 'p_bairro', 11, 'f', 'f', 'f', 1, 'text', 'p_bairro');
        insert into db_syscampo values (1011588, 'tipo_emissao', 'int8', 'tipo_emissao', '0', 'tipo_emissao', 11, 'f', 'f', 'f', 1, 'text', 'tipo_emissao');
        insert into db_syscampo values (1011453, 'aliquota_ibpt', 'varchar', 'aliquota_ibpt', '0', 'aliquota_ibpt', 11, 'f', 'f', 'f', 1, 'text', 'aliquota_ibpt');
        insert into db_syscampo values (1011491, 's_dados_item_lista_servico', 'varchar', 's_dados_item_lista_servico', '0', 's_dados_item_lista_servico', 11, 'f', 'f', 'f', 1, 'text', 's_dados_item_lista_servico');
        insert into db_syscampo values (1011399, 'p_cnpjcpf', 'varchar', 'p_cnpjcpf', '0', 'p_cnpjcpf', 11, 'f', 'f', 'f', 1, 'text', 'p_cnpjcpf');
        insert into db_syscampo values (1011584, 'cgm', 'int8', 'cgm', '0', 'cgm', 11, 'f', 'f', 'f', 1, 'text', 'cgm');
        insert into db_syscampo values (1011438, 's_codigo_obra', 'varchar', 's_codigo_obra', '0', 's_codigo_obra', 11, 'f', 'f', 'f', 1, 'text', 's_codigo_obra');
        insert into db_syscampo values (1011446, 'vl_desc_condicional', 'float8', 'vl_desc_condicional', '0', 'vl_desc_condicional', 11, 'f', 'f', 'f', 1, 'text', 'vl_desc_condicional');
        insert into db_syscampo values (1011360, 'cod_verificacao', 'varchar(10)', 'cod_verificacao', '', 'cod_verificacao', 10, 'f', 't', 'f', 0, 'text', 'cod_verificacao');
        insert into db_syscampo values (1011387, 's_dados_cod_municipio', 'float8', 's_dados_cod_municipio', '0', 's_dados_cod_municipio', 11, 'f', 'f', 'f', 1, 'text', 's_dados_cod_municipio');
        insert into db_syscampo values (1011411, 'p_cep', 'varchar', 'p_cep', '0', 'p_cep', 11, 'f', 'f', 'f', 1, 'text', 'p_cep');
        insert into db_syscampo values (1011419, 't_endereco', 'varchar', 't_endereco', '0', 't_endereco', 11, 'f', 'f', 'f', 1, 'text', 't_endereco');
        insert into db_syscampo values (1011498, 's_vl_bc', 'float', 's_vl_bc', '0', 's_vl_bc', 11, 'f', 'f', 'f', 1, 'text', 's_vl_bc');
        insert into db_syscampo values (1011353, 'id_contribuinte', 'int8', 'id_contribuinte', '0', 'id_contribuinte', 10, 'f', 'f', 'f', 1, 'text', 'id_contribuinte');
        insert into db_syscampo values (1011596, 'versao', 'varchar', 'versao', '0', 'versao', 11, 'f', 'f', 'f', 1, 'text', 'versao');
        insert into db_syscampo values (1011553, 'codigo_planilha', 'int8', 'codigo_planilha', '0', 'codigo_planilha', 11, 'f', 'f', 'f', 1, 'text', 'codigo_planilha');
        insert into db_syscampo values (1011578, 'data_fechamento', 'date', 'data_fechamento', '0', 'data_fechamento', 11, 'f', 'f', 'f', 1, 'text', 'data_fechamento');
        insert into db_syscampo values (1011412, 'p_telefone', 'varchar', 'p_telefone', '0', 'p_telefone', 11, 'f', 'f', 'f', 1, 'text', 'p_telefone');
        insert into db_syscampo values (1011603, 'id_importacao', 'int8', 'id_importacao', '0', 'id_importacao', 11, 'f', 'f', 'f', 1, 'text', 'id_importacao');
        insert into db_syscampo values (1011597, 'data_importacao', 'date', 'data_importacao', '0', 'data_importacao', 11, 'f', 'f', 'f', 1, 'text', 'data_importacao');
        insert into db_syscampo values (1011479, 'mes_comp', 'date', 'mes_comp', '0', 'mes_comp', 11, 'f', 'f', 'f', 1, 'text', 'mes_comp');
        insert into db_syscampo values (1011416, 't_im', 'varchar', 't_im', '0', 't_im', 11, 'f', 'f', 'f', 1, 'text', 't_im');
        insert into db_syscampo values (1011422, 't_bairro', 'varchar', 't_bairro', '0', 't_bairro', 11, 'f', 'f', 'f', 1, 'text', 't_bairro');
        insert into db_syscampo values (1011406, 'p_endereco_comp', 'varchar', 'p_endereco_comp', '0', 'p_endereco_comp', 11, 'f', 'f', 'f', 1, 'text', 'p_endereco_comp');
        insert into db_syscampo values (1011389, 's_dados_exigibilidadeiss', 'float8', 's_dados_exigibilidadeiss', '0', 's_dados_exigibilidadeiss', 11, 'f', 'f', 'f', 1, 'text', 's_dados_exigibilidadeiss');
        insert into db_syscampo values (1011528, 'tipo_documento_origem', 'int4', 'tipo documento de origem', '0', 'tipo de documento de origem', 10, 't', 'f', 'f', 1, 'text', 'tipo documento de origem');
        insert into db_syscampo values (1011397, 's_retido', 'int8', 's_retido', '0', 's_retido', 11, 'f', 'f', 'f', 1, 'text', 's_retido');
        insert into db_syscampo values (1011432, 'grupo_documento', 'int8', 'grupo_documento', '0', 'grupo_documento', 11, 'f', 'f', 'f', 1, 'text', 'grupo_documento');
        insert into db_syscampo values (1011499, 's_vl_iss', 'float', 's_vl_iss', '0', 's_vl_iss', 11, 'f', 'f', 'f', 1, 'text', 's_vl_iss');
        insert into db_syscampo values (1011418, 't_razao_social', 'varchar', 't_razao_social', '0', 't_razao_social', 11, 'f', 'f', 'f', 1, 'text', 't_razao_social');
        insert into db_syscampo values (1011500, 's_vl_pis', 'float', 's_vl_pis', '0', 's_vl_pis', 11, 'f', 'f', 'f', 1, 'text', 's_vl_pis');
        insert into db_syscampo values (1011398, 's_vl_base_calculo', 'float8', 's_vl_base_calculo', '0', 's_vl_base_calculo', 11, 'f', 'f', 'f', 1, 'text', 's_vl_base_calculo');
        insert into db_syscampo values (1011396, 's_vl_condicionado', 'float8', 's_vl_condicionado', '0', 's_vl_condicionado', 11, 'f', 'f', 'f', 1, 'text', 's_vl_condicionado');
        insert into db_syscampo values (1011495, 's_dec_incentivo_fiscal', 'float', 's_dec_incentivo_fiscal', '0', 's_dec_incentivo_fiscal', 11, 'f', 'f', 'f', 1, 'text', 's_dec_incentivo_fiscal');
        insert into db_syscampo values (1011400, 'p_im', 'varchar', 'p_im', '0', 'p_im', 11, 'f', 'f', 'f', 1, 'text', 'p_im');
        insert into db_syscampo values (1011575, 'linha_digitavel', 'varchar', 'linha_digitavel', '0', 'linha_digitavel', 11, 'f', 'f', 'f', 1, 'text', 'linha_digitavel');
        insert into db_syscampo values (1011435, 'situacao_documento', 'varchar', 'situacao_documento', '0', 'situacao_documento', 11, 'f', 'f', 'f', 1, 'text', 'situacao_documento');
        insert into db_syscampo values (1011574, 'codigo_barras', 'varchar', 'codigo_barras', '0', 'codigo_barras', 11, 'f', 'f', 'f', 1, 'text', 'codigo_barras');
        insert into db_syscampo values (1011433, 'tipo_documento', 'int8', 'tipo_documento', '0', 'tipo_documento', 11, 'f', 'f', 'f', 1, 'text', 'tipo_documento');
        insert into db_syscampo values (1011516, 'codigo_guia', 'int8', 'codigo da guia', '0', 'codigo da guia', 10, 't', 'f', 'f', 1, 'text', 'codigo da guia');
        insert into db_syscampo values (1011405, 'p_endereco_numero', 'varchar', 'p_endereco_numero', '0', 'p_endereco_numero', 11, 'f', 'f', 'f', 1, 'text', 'p_endereco_numero');
        insert into db_syscampo values (1011554, 'data_operacao', 'date', 'data_operacao', '0', 'data_operacao', 11, 'f', 'f', 'f', 1, 'text', 'data_operacao');
        insert into db_syscampo values (1011483, 'cancelada', 'bool', 'cancelada', '0', 'cancelada', 11, 'f', 'f', 'f', 1, 'text', 'cancelada');

        ------ Vincula os campos nas tabelas ------
        insert into db_sysarqcamp values (1010588, 568, 1, 0);
        insert into db_sysarqcamp values (1010588, 1011580, 1, 0);
        insert into db_sysarqcamp values (1010588, 1011582, 1, 0);
        insert into db_sysarqcamp values (1010588, 1011584, 1, 0);
        insert into db_sysarqcamp values (1010588, 1011586, 1, 0);
        insert into db_sysarqcamp values (1010588, 1011588, 1, 0);
        insert into db_sysarqcamp values (1010588, 1011590, 1, 0);
        insert into db_sysarqcamp values (1010588, 1011345, 1, 1000928);
        insert into db_sysarqcamp values (1010587, 1011603, 1, 0);
        insert into db_sysarqcamp values (1010587, 1011353, 1, 0);
        insert into db_sysarqcamp values (1010587, 1011431, 1, 0);
        insert into db_sysarqcamp values (1010587, 1011605, 1, 0);
        insert into db_sysarqcamp values (1010587, 1011481, 1, 0);
        insert into db_sysarqcamp values (1010587, 1011607, 1, 0);
        insert into db_sysarqcamp values (1010587, 1011609, 1, 0);
        insert into db_sysarqcamp values (1010587, 16016, 1, 0);
        insert into db_sysarqcamp values (1010587, 16012, 1, 0);
        insert into db_sysarqcamp values (1010587, 1011611, 1, 0);
        insert into db_sysarqcamp values (1010587, 1011345, 1, 1000943);
        insert into db_sysarqcamp values (1010589, 1011587, 1, 0);
        insert into db_sysarqcamp values (1010589, 750, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011442, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011443, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011444, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011445, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011446, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011447, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011448, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011449, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011450, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011451, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011452, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011453, 1, 0);
        insert into db_sysarqcamp values (1010589, 1011345, 1, 1000929);
        insert into db_sysarqcamp values (1010583, 1011382, 1, 0);
        insert into db_sysarqcamp values (1010583, 1011591, 1, 1000930);
        insert into db_sysarqcamp values (1010584, 1011441, 1, 0);
        insert into db_sysarqcamp values (1010584, 1011591, 1, 1000932);
        insert into db_sysarqcamp values (1010582, 1011345, 1, 0);
        insert into db_sysarqcamp values (1010582, 1011591, 1, 0);
        insert into db_sysarqcamp values (1010582, 1062, 1, 0);
        insert into db_sysarqcamp values (1010585, 1011353, 1, 0);
        insert into db_sysarqcamp values (1010585, 1011594, 1, 0);
        insert into db_sysarqcamp values (1010585, 1011595, 1, 0);
        insert into db_sysarqcamp values (1010585, 1011596, 1, 0);
        insert into db_sysarqcamp values (1010585, 1011597, 1, 0);
        insert into db_sysarqcamp values (1010585, 16024, 1, 0);
        insert into db_sysarqcamp values (1010585, 1011345, 1, 1000936);
        insert into db_sysarqcamp values (1010586, 568, 1, 0);
        insert into db_sysarqcamp values (1010586, 1011554, 1, 0);
        insert into db_sysarqcamp values (1010586, 16016, 1, 0);
        insert into db_sysarqcamp values (1010586, 16012, 1, 0);
        insert into db_sysarqcamp values (1010586, 16024, 1, 0);
        insert into db_sysarqcamp values (1010586, 1011598, 1, 0);
        insert into db_sysarqcamp values (1010586, 1011599, 1, 0);
        insert into db_sysarqcamp values (1010586, 1011600, 1, 0);
        insert into db_sysarqcamp values (1010586, 1011345, 1, 1000937);
        insert into db_sysarqcamp values (1010579, 1011353, 1, 0);
        insert into db_sysarqcamp values (1010579, 1073, 1, 0);
        insert into db_sysarqcamp values (1010579, 2311, 1, 0);
        insert into db_sysarqcamp values (1010579, 1011577, 1, 0);
        insert into db_sysarqcamp values (1010579, 1011578, 1, 0);
        insert into db_sysarqcamp values (1010579, 1011345, 1, 1000933);
        insert into db_sysarqcamp values (1010575, 1011345, 1, 1000931);
        insert into db_sysarqcamp values (1010575, 1011353, 2, 0);
        insert into db_sysarqcamp values (1010575, 568, 3, 0);
        insert into db_sysarqcamp values (1010575, 1011355, 4, 0);
        insert into db_sysarqcamp values (1010575, 1011356, 5, 0);
        insert into db_sysarqcamp values (1010575, 1011357, 6, 0);
        insert into db_sysarqcamp values (1010575, 1011358, 7, 0);
        insert into db_sysarqcamp values (1010575, 1011359, 8, 0);
        insert into db_sysarqcamp values (1010575, 1011360, 9, 0);
        insert into db_sysarqcamp values (1010575, 1011479, 10, 0);
        insert into db_sysarqcamp values (1010575, 1011480, 11, 0);
        insert into db_sysarqcamp values (1010575, 1011481, 12, 0);
        insert into db_sysarqcamp values (1010575, 1011482, 13, 0);
        insert into db_sysarqcamp values (1010575, 1011437, 14, 0);
        insert into db_sysarqcamp values (1010575, 1011436, 15, 0);
        insert into db_sysarqcamp values (1010575, 1011483, 16, 0);
        insert into db_sysarqcamp values (1010575, 1011484, 17, 0);
        insert into db_sysarqcamp values (1010575, 1011485, 18, 0);
        insert into db_sysarqcamp values (1010575, 1011486, 19, 0);
        insert into db_sysarqcamp values (1010575, 1011487, 20, 0);
        insert into db_sysarqcamp values (1010575, 1011488, 21, 0);
        insert into db_sysarqcamp values (1010575, 1011489, 22, 0);
        insert into db_sysarqcamp values (1010575, 1011438, 23, 0);
        insert into db_sysarqcamp values (1010575, 1011439, 24, 0);
        insert into db_sysarqcamp values (1010575, 1011440, 25, 0);
        insert into db_sysarqcamp values (1010575, 1011383, 26, 0);
        insert into db_sysarqcamp values (1010575, 1011394, 27, 0);
        insert into db_sysarqcamp values (1010575, 1011395, 28, 0);
        insert into db_sysarqcamp values (1010575, 1011396, 29, 0);
        insert into db_sysarqcamp values (1010575, 1011386, 30, 0);
        insert into db_sysarqcamp values (1010575, 1011490, 31, 0);
        insert into db_sysarqcamp values (1010575, 1011491, 32, 0);
        insert into db_sysarqcamp values (1010575, 1011387, 33, 0);
        insert into db_sysarqcamp values (1010575, 1011388, 34, 0);
        insert into db_sysarqcamp values (1010575, 1011389, 35, 0);
        insert into db_sysarqcamp values (1010575, 1011390, 36, 0);
        insert into db_sysarqcamp values (1010575, 1011492, 37, 0);
        insert into db_sysarqcamp values (1010575, 1011493, 38, 0);
        insert into db_sysarqcamp values (1010575, 1011494, 39, 0);
        insert into db_sysarqcamp values (1010575, 1011495, 40, 0);
        insert into db_sysarqcamp values (1010575, 1011496, 41, 0);
        insert into db_sysarqcamp values (1010575, 1011384, 42, 0);
        insert into db_sysarqcamp values (1010575, 1011385, 43, 0);
        insert into db_sysarqcamp values (1010575, 1011391, 44, 0);
        insert into db_sysarqcamp values (1010575, 1011497, 45, 0);
        insert into db_sysarqcamp values (1010575, 1011498, 46, 0);
        insert into db_sysarqcamp values (1010575, 1011392, 47, 0);
        insert into db_sysarqcamp values (1010575, 1011499, 48, 0);
        insert into db_sysarqcamp values (1010575, 1011500, 49, 0);
        insert into db_sysarqcamp values (1010575, 1011501, 50, 0);
        insert into db_sysarqcamp values (1010575, 1011393, 51, 0);
        insert into db_sysarqcamp values (1010575, 1011502, 52, 0);
        insert into db_sysarqcamp values (1010575, 1011503, 53, 0);
        insert into db_sysarqcamp values (1010575, 1011399, 54, 0);
        insert into db_sysarqcamp values (1010575, 1011400, 55, 0);
        insert into db_sysarqcamp values (1010575, 1011401, 56, 0);
        insert into db_sysarqcamp values (1010575, 1011402, 57, 0);
        insert into db_sysarqcamp values (1010575, 1011403, 58, 0);
        insert into db_sysarqcamp values (1010575, 1011404, 59, 0);
        insert into db_sysarqcamp values (1010575, 1011405, 60, 0);
        insert into db_sysarqcamp values (1010575, 1011406, 61, 0);
        insert into db_sysarqcamp values (1010575, 1011407, 62, 0);
        insert into db_sysarqcamp values (1010575, 1011408, 63, 0);
        insert into db_sysarqcamp values (1010575, 1011409, 64, 0);
        insert into db_sysarqcamp values (1010575, 1011410, 65, 0);
        insert into db_sysarqcamp values (1010575, 1011411, 66, 0);
        insert into db_sysarqcamp values (1010575, 1011412, 67, 0);
        insert into db_sysarqcamp values (1010575, 1011413, 68, 0);
        insert into db_sysarqcamp values (1010575, 1011414, 69, 0);
        insert into db_sysarqcamp values (1010575, 1011416, 70, 0);
        insert into db_sysarqcamp values (1010575, 1011417, 71, 0);
        insert into db_sysarqcamp values (1010575, 1011418, 72, 0);
        insert into db_sysarqcamp values (1010575, 1011415, 73, 0);
        insert into db_sysarqcamp values (1010575, 1011419, 74, 0);
        insert into db_sysarqcamp values (1010575, 1011420, 75, 0);
        insert into db_sysarqcamp values (1010575, 1011421, 76, 0);
        insert into db_sysarqcamp values (1010575, 1011422, 77, 0);
        insert into db_sysarqcamp values (1010575, 1011423, 78, 0);
        insert into db_sysarqcamp values (1010575, 1011424, 79, 0);
        insert into db_sysarqcamp values (1010575, 1011425, 80, 0);
        insert into db_sysarqcamp values (1010575, 1011426, 81, 0);
        insert into db_sysarqcamp values (1010575, 1011427, 82, 0);
        insert into db_sysarqcamp values (1010575, 1011428, 83, 0);
        insert into db_sysarqcamp values (1010575, 1011579, 84, 0);
        insert into db_sysarqcamp values (1010576, 1011345, 1, 1000923);
        insert into db_sysarqcamp values (1010576, 1011441, 2, 0);
        insert into db_sysarqcamp values (1010576, 750, 3, 0);
        insert into db_sysarqcamp values (1010576, 1011442, 4, 0);
        insert into db_sysarqcamp values (1010576, 1011443, 5, 0);
        insert into db_sysarqcamp values (1010576, 1011444, 6, 0);
        insert into db_sysarqcamp values (1010576, 1011445, 7, 0);
        insert into db_sysarqcamp values (1010576, 1011446, 8, 0);
        insert into db_sysarqcamp values (1010576, 1011447, 9, 0);
        insert into db_sysarqcamp values (1010576, 1011448, 10, 0);
        insert into db_sysarqcamp values (1010576, 1011449, 11, 0);
        insert into db_sysarqcamp values (1010576, 1011450, 12, 0);
        insert into db_sysarqcamp values (1010576, 1011451, 13, 0);
        insert into db_sysarqcamp values (1010576, 1011452, 14, 0);
        insert into db_sysarqcamp values (1010576, 1011453, 15, 0);
        insert into db_sysarqcamp values (1010580, 1011345, 1, 1000924);
        insert into db_sysarqcamp values (1010577, 1011345, 1, 1000925);
        insert into db_sysarqcamp values (1010577, 1011553, 2, 0);
        insert into db_sysarqcamp values (1010577, 1011353, 3, 0);
        insert into db_sysarqcamp values (1010577, 568, 4, 0);
        insert into db_sysarqcamp values (1010577, 1011554, 5, 0);
        insert into db_sysarqcamp values (1010577, 1011480, 6, 0);
        insert into db_sysarqcamp values (1010577, 1011479, 7, 0);
        insert into db_sysarqcamp values (1010577, 7856, 8, 0);
        insert into db_sysarqcamp values (1010577, 19101, 9, 0);
        insert into db_sysarqcamp values (1010577, 1011488, 10, 0);
        insert into db_sysarqcamp values (1010580, 1011353, 2, 0);
        insert into db_sysarqcamp values (1010580, 1011516, 3, 0);
        insert into db_sysarqcamp values (1010580, 1062, 4, 0);
        insert into db_sysarqcamp values (1010580, 1011528, 5, 0);
        insert into db_sysarqcamp values (1010580, 1011479, 6, 0);
        insert into db_sysarqcamp values (1010580, 1011480, 7, 0);
        insert into db_sysarqcamp values (1010580, 1011571, 8, 0);
        insert into db_sysarqcamp values (1010580, 1011572, 9, 0);
        insert into db_sysarqcamp values (1010580, 1011573, 10, 0);
        insert into db_sysarqcamp values (1010580, 1011574, 11, 0);
        insert into db_sysarqcamp values (1010580, 1011575, 12, 0);
        insert into db_sysarqcamp values (1010580, 1011576, 13, 0);
        insert into db_sysarqcamp values (1010580, 1073, 14, 0);
        insert into db_sysarqcamp values (1010580, 1011577, 15, 0);
        insert into db_sysarqcamp values (1010580, 1011578, 16, 0);
        insert into db_sysarqcamp values (1010580, 1211, 17, 0);
        insert into db_sysarqcamp values (1010580, 1011488, 18, 0);
        insert into db_sysarqcamp values (1010578, 1011382, 1, 0);
        insert into db_sysarqcamp values (1010578, 568, 2, 0);
        insert into db_sysarqcamp values (1010578, 1011353, 3, 0);
        insert into db_sysarqcamp values (1010578, 1011355, 4, 0);
        insert into db_sysarqcamp values (1010578, 15990, 5, 0);
        insert into db_sysarqcamp values (1010578, 1011356, 6, 0);
        insert into db_sysarqcamp values (1010578, 1011357, 7, 0);
        insert into db_sysarqcamp values (1010578, 1098, 8, 0);
        insert into db_sysarqcamp values (1010578, 1011383, 9, 0);
        insert into db_sysarqcamp values (1010578, 1011384, 10, 0);
        insert into db_sysarqcamp values (1010578, 1011385, 11, 0);
        insert into db_sysarqcamp values (1010578, 1011386, 12, 0);
        insert into db_sysarqcamp values (1010578, 1011387, 13, 0);
        insert into db_sysarqcamp values (1010578, 1011388, 14, 0);
        insert into db_sysarqcamp values (1010578, 1011389, 15, 0);
        insert into db_sysarqcamp values (1010578, 1011390, 16, 0);
        insert into db_sysarqcamp values (1010578, 1011391, 17, 0);
        insert into db_sysarqcamp values (1010578, 1011392, 18, 0);
        insert into db_sysarqcamp values (1010578, 1011393, 19, 0);
        insert into db_sysarqcamp values (1010578, 1011394, 20, 0);
        insert into db_sysarqcamp values (1010578, 1011395, 21, 0);
        insert into db_sysarqcamp values (1010578, 1011396, 22, 0);
        insert into db_sysarqcamp values (1010578, 1011397, 23, 0);
        insert into db_sysarqcamp values (1010578, 1011398, 24, 0);
        insert into db_sysarqcamp values (1010578, 1011399, 25, 0);
        insert into db_sysarqcamp values (1010578, 1011400, 26, 0);
        insert into db_sysarqcamp values (1010578, 1011401, 27, 0);
        insert into db_sysarqcamp values (1010578, 1011402, 28, 0);
        insert into db_sysarqcamp values (1010578, 1011403, 29, 0);
        insert into db_sysarqcamp values (1010578, 1011404, 30, 0);
        insert into db_sysarqcamp values (1010578, 1011405, 31, 0);
        insert into db_sysarqcamp values (1010578, 1011406, 32, 0);
        insert into db_sysarqcamp values (1010578, 1011407, 33, 0);
        insert into db_sysarqcamp values (1010578, 1011408, 34, 0);
        insert into db_sysarqcamp values (1010578, 1011409, 35, 0);
        insert into db_sysarqcamp values (1010578, 1011410, 36, 0);
        insert into db_sysarqcamp values (1010578, 1011411, 37, 0);
        insert into db_sysarqcamp values (1010578, 1011412, 38, 0);
        insert into db_sysarqcamp values (1010578, 1011413, 39, 0);
        insert into db_sysarqcamp values (1010578, 1011414, 40, 0);
        insert into db_sysarqcamp values (1010578, 1011415, 41, 0);
        insert into db_sysarqcamp values (1010578, 1011416, 42, 0);
        insert into db_sysarqcamp values (1010578, 1011417, 43, 0);
        insert into db_sysarqcamp values (1010578, 1011418, 44, 0);
        insert into db_sysarqcamp values (1010578, 1011419, 45, 0);
        insert into db_sysarqcamp values (1010578, 1011420, 46, 0);
        insert into db_sysarqcamp values (1010578, 1011421, 47, 0);
        insert into db_sysarqcamp values (1010578, 1011422, 48, 0);
        insert into db_sysarqcamp values (1010578, 1011423, 49, 0);
        insert into db_sysarqcamp values (1010578, 1011424, 50, 0);
        insert into db_sysarqcamp values (1010578, 1011425, 51, 0);
        insert into db_sysarqcamp values (1010578, 1011426, 52, 0);
        insert into db_sysarqcamp values (1010578, 1011427, 53, 0);
        insert into db_sysarqcamp values (1010578, 1011428, 54, 0);
        insert into db_sysarqcamp values (1010578, 1011429, 55, 0);
        insert into db_sysarqcamp values (1010578, 1011430, 56, 0);
        insert into db_sysarqcamp values (1010578, 1062, 57, 0);
        insert into db_sysarqcamp values (1010578, 1011431, 58, 0);
        insert into db_sysarqcamp values (1010578, 7856, 59, 0);
        insert into db_sysarqcamp values (1010578, 1011432, 60, 0);
        insert into db_sysarqcamp values (1010578, 1011433, 61, 0);
        insert into db_sysarqcamp values (1010578, 1011434, 62, 0);
        insert into db_sysarqcamp values (1010578, 1011435, 63, 0);
        insert into db_sysarqcamp values (1010578, 1011436, 64, 0);
        insert into db_sysarqcamp values (1010578, 1011437, 65, 0);
        insert into db_sysarqcamp values (1010578, 1011438, 66, 0);
        insert into db_sysarqcamp values (1010578, 1011439, 67, 0);
        insert into db_sysarqcamp values (1010578, 1011440, 68, 0);
        insert into db_sysarqcamp values (1010578, 1011345, 69, 0);

        ------ Define PK e Cria Sequences ------
        /*notas*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010575,1011345,1,1011345);
        insert into db_syssequencia values(1000931, 'notas_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000931 where codarq = 1010575 and codcam = 1011345;

        /*notas_servicos*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010576,1011345,1,1011345);
        insert into db_syssequencia values(1000923, 'notas_servicos_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000923 where codarq = 1010576 and codcam = 1011345;

        /*dms*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010577,1011345,1,1011345);
        insert into db_syssequencia values(1000925, 'dms_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000925 where codarq = 1010577 and codcam = 1011345;

        /*dms_nota*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010578,1011345,1,1011345);
        insert into db_syssequencia values(1000946, 'dms_nota_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000946 where codarq = 1010578 and codcam = 1011345;

        /*dms_nota_servicos*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010589,1011345,1,1011345);
        insert into db_syssequencia values(1000929, 'dms_nota_servicos_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000929 where codarq = 1010589 and codcam = 1011345;

        /*competencias*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010579,1011345,1,1011345);
        insert into db_syssequencia values(1000933, 'competencias_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000933 where codarq = 1010579 and codcam = 1011345;

        /*guias*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010580,1011345,1,1011345);
        insert into db_syssequencia values(1000924, 'guias_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000924 where codarq = 1010580 and codcam = 1011345;

        /*guias_numpre*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010582,1011345,1,1011345);
        insert into db_syssequencia values(1000948, 'guias_numpre_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000948 where codarq = 1010582 and codcam = 1011345;

        /*guias_dms*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010583,1011591,1,1011591);
        insert into db_syssequencia values(1000930, 'guias_dms_id_guia_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000930 where codarq = 1010583 and codcam = 1011345;

        /*importacao_desif*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010585,1011345,1,1011345);
        insert into db_syssequencia values(1000936, 'importacao_desif_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000936 where codarq = 1010585 and codcam = 1011345;

        /*importacao_dms*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010586,1011345,1,1011345);
        insert into db_syssequencia values(1000937, 'importacao_dms_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000937 where codarq = 1010586 and codcam = 1011345;

        /*importacao_dms_nota*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010587,1011345,1,1011345);
        insert into db_syssequencia values(1000943, 'importacao_dms_nota_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000943 where codarq = 1010587 and codcam = 1011345;

        /*usuarios_contribuintes*/
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010588,1011345,1,1011345);
        insert into db_syssequencia values(1000928, 'usuarios_contribuintes_id_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000928 where codarq = 1010588 and codcam = 1011345;
SQL
        );
    }

    public function upNotasServicos()
    {
        $this->execute(
        <<<SQL
        CREATE TABLE nfse.notas_servicos (
            id                    serial NOT NULL,
            id_nota               bigint not null,
            descricao             character varying not null,
            vl_servico            numeric(15,2) not null,
            vl_aliquota           numeric(15,2) not null,
            vl_deducao            numeric(15,2) not null,
            vl_desc_incondicional numeric(15,2) not null,
            vl_desc_condicional   numeric(15,2) not null,
            qnt_servico           integer not null,
            estrutural_cnae       character varying,
            codigo_tributacao     integer not null,
            item_servico          character varying not null,
            tributacao_nao_incide boolean not null,
            tributacao_municipio  integer,
            aliquota_ibpt         character varying
          );

        ALTER TABLE ONLY nfse.notas_servicos
        ADD CONSTRAINT notas_servicos_pkey PRIMARY KEY (id),
        ADD CONSTRAINT notas_servicos_id_notas_fkey FOREIGN KEY (id_nota) REFERENCES nfse.notas(id);
SQL
        );
    }

    public function upDms()
    {
        $this->execute(
            <<<SQL
        CREATE SEQUENCE nfse.dms_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

        CREATE TABLE nfse.dms (
            id              bigint NOT NULL,
            codigo_planilha bigint NOT NULL,
            id_contribuinte bigint NOT NULL,
            id_usuario      bigint NOT NULL,
            data_operacao   date,
            ano_comp        integer,
            mes_comp        integer,
            status          varchar(10) default 'aberto',
            operacao        varchar(1)  default 's',
            importada       boolean DEFAULT false NOT NULL
        );

        COMMENT ON COLUMN nfse.dms.operacao IS '[e=entrada][s=saida]';
        ALTER TABLE ONLY nfse.dms ADD CONSTRAINT dms_id_pkey PRIMARY KEY (id);
        ALTER TABLE ONLY nfse.dms ADD CONSTRAINT dms_id_contribuinte_fk FOREIGN KEY (id_contribuinte) REFERENCES nfse.usuarios_contribuintes;

        ALTER TABLE ONLY nfse.dms ALTER COLUMN id SET DEFAULT nextval('nfse.dms_id_seq'::regclass);
SQL
        );
    }

    public function upDmsNota()
    {
        $this->execute(
        <<<SQL
        CREATE SEQUENCE nfse.dms_nota_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

        CREATE TABLE nfse.dms_nota (
            id bigint NOT NULL,
            id_dms                       bigint NOT NULL,
            id_usuario                   bigint NOT NULL,
            id_contribuinte              bigint NOT NULL,
            nota                         bigint NOT NULL,
            serie                        character varying(5),
            dt_nota                      date,
            hr_nota                      time without time zone,
            s_data                       date,
            s_dados_discriminacao        text,
            s_dados_cod_tributacao       integer,
            s_dados_cod_cnae             character varying(10),
            s_dados_iss_retido           boolean DEFAULT false,
            s_dados_cod_municipio        numeric(7,0),
            s_dados_cod_pais             character varying(5),
            s_dados_exigibilidadeiss     numeric(2,0) DEFAULT 1,
            s_dados_municipio_incidencia numeric(7,0),
            s_vl_servicos                numeric(15,2),
            s_vl_aliquota                numeric(4,2),
            s_vl_inss                    numeric(15,2) DEFAULT 0,
            s_vl_outras_retencoes        numeric(15,2) DEFAULT 0,
            s_vl_desc_incondicionado     numeric(15,2) DEFAULT 0,
            s_vl_condicionado            numeric(15,2) DEFAULT 0,
            s_retido                     integer DEFAULT 0,
            s_vl_base_calculo            numeric(15,2),
            p_cnpjcpf                    character varying(14),
            p_im                         character varying(15),
            p_ie                         character varying(20),
            p_razao_social               character varying(150),
            p_nome_fantasia              character varying(60),
            p_endereco                   character varying(125),
            p_endereco_numero            character varying(10),
            p_endereco_comp              character varying(60),
            p_bairro                     character varying(60),
            p_cod_municipio              numeric(7,0),
            p_uf                         character(2),
            p_cod_pais                   character(5),
            p_cep                        character(8),
            p_telefone                   character varying(20),
            p_email                      character varying(80),
            t_cnpjcpf                    character varying(14),
            t_nome_fantasia              character varying(60),
            t_im                         character varying(15),
            t_ie                         character varying(20),
            t_razao_social               character varying(150),
            t_endereco                   character varying(125),
            t_endereco_numero            character varying(10),
            t_endereco_comp              character varying(60),
            t_bairro                     character varying(60),
            t_cod_municipio              numeric(7,0),
            t_uf                         character(2),
            t_cod_pais                   character(5),
            t_cep                        character(8),
            t_telefone                   character varying(20),
            t_email                      character varying(80),
            vl_liquido_nfse              numeric(15,2) DEFAULT 0,
            s_codigo_servico             integer,
            numpre                       integer,
            codigo_nota_planilha         integer,
            status                       integer,
            grupo_documento              integer,
            tipo_documento               integer,
            tipo_documento_descricao     character varying,
            situacao_documento           character varying(5) DEFAULT NULL::character varying,
            emite_guia                   boolean DEFAULT false NOT NULL,
            natureza_operacao            integer,
            s_codigo_obra                character varying,
            s_art                        character varying,
            s_informacoes_complementares text
        );

        ALTER TABLE ONLY nfse.dms_nota
                ADD CONSTRAINT dms_nota_pkey PRIMARY KEY (id),
                ADD CONSTRAINT dms_nota_id_contribuinte_fk FOREIGN KEY (id_contribuinte) REFERENCES nfse.usuarios_contribuintes(id),
                ADD CONSTRAINT dms_nota_id_dms_fkey FOREIGN KEY (id_dms) REFERENCES nfse.dms(id);

        ALTER TABLE ONLY nfse.dms_nota ALTER COLUMN id SET DEFAULT nextval('nfse.dms_nota_id_seq'::regclass);
        COMMENT ON COLUMN nfse.dms_nota.grupo_documento IS 'Grupo de Documento (e-Cidade)';
        COMMENT ON COLUMN nfse.dms_nota.tipo_documento IS 'Tipo de Documento (e-Cidade)';
        COMMENT ON COLUMN nfse.dms_nota.tipo_documento_descricao IS 'Descrição do Tipo de Documento (Eventual)';
        COMMENT ON COLUMN nfse.dms_nota.natureza_operacao IS 'Dentro / Fora Prefeitura';
        COMMENT ON COLUMN nfse.dms_nota.s_art IS 'Anotação de Responsabilidade Técnica';
SQL
        );
    }

    public function upCompetencias()
    {
        $this->execute(
        <<<SQL
        CREATE SEQUENCE nfse.competencias_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

        CREATE TABLE nfse.competencias (
            id INT4 NOT NULL DEFAULT nextval('nfse.competencias_id_seq'::regclass),
            id_contribuinte INT4 NOT NULL,
            tipo INT4 NOT NULL,
            mes INT4 NOT NULL,
            ano INT4 NOT NULL,
            data_fechamento date NOT NULL,
            situacao INT4 NOT NULL,
            CONSTRAINT competencias_id_pk PRIMARY KEY (id)
        );

        ALTER SEQUENCE nfse.competencias_id_seq OWNED BY nfse.competencias.id;

        -- Adiciona competencia na tabela guias
        ALTER TABLE nfse.guias ADD COLUMN id_competencia INT4;
        ALTER TABLE nfse.guias ADD CONSTRAINT guias_id_competencias_fk FOREIGN KEY (id_competencia) REFERENCES nfse.competencias(id);
SQL
        );
    }

    public function upGuias()
    {
        $this->execute(
        <<<SQL
        CREATE TABLE nfse.guias (
            id                    integer NOT NULL,
            id_contribuinte       bigint  NOT NULL,
            codigo_guia           bigint,
            numpre                bigint,
            tipo_documento_origem integer,
            mes_comp              integer NOT NULL,
            ano_comp              integer NOT NULL,
            valor_corrigido       numeric,
            valor_historico       numeric,
            juros_multa           numeric,
            codigo_barras         character varying,
            linha_digitavel       character varying,
            arquivo_guia          character varying,
            tipo                  character(1),
            situacao              character(1),
            data_fechamento       date,
            vencimento            date,
            importada boolean DEFAULT false NOT NULL
        );

        CREATE SEQUENCE nfse.guias_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
        ALTER TABLE ONLY nfse.guias ALTER COLUMN id SET DEFAULT nextval('nfse.guias_id_seq'::regclass);

        ALTER TABLE ONLY nfse.guias
                ADD CONSTRAINT guias_id_pk PRIMARY KEY (id),
                ADD CONSTRAINT guias_id_contribuinte_fk FOREIGN KEY (id_contribuinte) REFERENCES nfse.usuarios_contribuintes(id);
SQL
        );
    }

    private function upDDLDmsNotaServicos()
    {
        $this->execute(<<<SQL_UP
CREATE TABLE nfse.dms_nota_servicos (
    id                    serial NOT NULL,
    id_dms_nota           bigint not null,
    descricao             character varying not null,
    vl_servico            numeric(15,2) not null,
    vl_aliquota           numeric(15,2) not null,
    vl_deducao            numeric(15,2) not null,
    vl_desc_incondicional numeric(15,2) not null,
    vl_desc_condicional   numeric(15,2) not null,
    qnt_servico           numeric not null,
    estrutural_cnae       character varying,
    codigo_tributacao     integer not null,
    item_servico          character varying not null,
    tributacao_nao_incide boolean not null,
    tributacao_municipio  integer,
    aliquota_ibpt         character varying
);

ALTER TABLE ONLY nfse.dms_nota_servicos
        ADD CONSTRAINT dms_nota_servicos_pkey PRIMARY KEY (id),
        ADD CONSTRAINT dms_nota_servicos_id_notas_fkey FOREIGN KEY (id_dms_nota) REFERENCES nfse.dms_nota(id);
SQL_UP
        );
    }

    private function upDDLGuiasDms()
    {
        $this->execute(<<<SQL_UP
CREATE TABLE nfse.guias_dms (
    id_guia bigint NOT NULL,
    id_dms  bigint NOT NULL
);

ALTER TABLE ONLY nfse.guias_dms ADD CONSTRAINT guias_dms_pk PRIMARY KEY (id_guia, id_dms);
ALTER TABLE ONLY nfse.guias_dms ADD CONSTRAINT guias_dms_id_guia_fk  FOREIGN KEY (id_guia) REFERENCES nfse.guias(id);
ALTER TABLE ONLY nfse.guias_dms ADD CONSTRAINT guias_dms_id_dms_fk   FOREIGN KEY (id_dms)  REFERENCES nfse.dms(id);
SQL_UP
        );
    }

    private function upDDLGuiasNota()
    {
        $this->execute(<<<SQL_UP
CREATE TABLE nfse.guias_notas (
    id_guia bigint NOT NULL,
    id_nota bigint NOT NULL
);

ALTER TABLE ONLY nfse.guias_notas ADD constraint guias_notas_pk primary key (id_guia, id_nota);
ALTER TABLE ONLY nfse.guias_notas ADD CONSTRAINT guias_notas_id_guia_fk FOREIGN KEY (id_guia) REFERENCES nfse.guias(id);
SQL_UP
        );
    }

    private function upDDLGuiasNumpre()
    {
        $this->execute(<<<SQL_UP
CREATE SEQUENCE nfse.guias_numpre_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

CREATE TABLE nfse.guias_numpre (
    id integer NOT NULL,
    id_guia bigint NOT NULL,
    numpre  bigint NOT NULL
);

ALTER TABLE ONLY nfse.guias_numpre ADD CONSTRAINT guias_numpre_id_pk PRIMARY KEY (id);
ALTER TABLE ONLY nfse.guias_numpre ADD CONSTRAINT id_guia_and_numpre_uk UNIQUE (id_guia, numpre);
ALTER TABLE ONLY nfse.guias_numpre ADD CONSTRAINT guias_numpre_id_guia_fk FOREIGN KEY (id_guia) REFERENCES nfse.guias(id);
SQL_UP
        );
    }

    private function upDDLImportacaoDesif()
    {
        $this->execute(<<<SQL_UP
CREATE SEQUENCE nfse.importacao_desif_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

CREATE TABLE nfse.importacao_desif (
    id                  INTEGER     NOT NULL DEFAULT nextval('nfse.importacao_desif_id_seq'::regclass),
    id_contribuinte     INTEGER     NOT NULL,
    competencia_inicial VARCHAR(6)  NOT NULL,
    competencia_final   VARCHAR(6)  NOT NULL,
    versao              VARCHAR(10) NOT NULL,
    data_importacao     TIMESTAMP   NOT NULL,
    nome_arquivo        VARCHAR(200)
);

ALTER SEQUENCE nfse.importacao_desif_id_seq OWNED BY nfse.importacao_desif.id;

ALTER TABLE ONLY nfse.importacao_desif ADD CONSTRAINT importacao_desif_id_pk PRIMARY KEY (id),
                                       ADD CONSTRAINT importacao_desif_comp_inicial_comp_final_comp_uk UNIQUE (id_contribuinte, competencia_inicial, competencia_final),
                                       ADD CONSTRAINT importacao_desif_id_contribuinte_fk FOREIGN KEY (id_contribuinte) REFERENCES nfse.usuarios_contribuintes(id);
SQL_UP
        );
    }

    private function upDDLImportacaoDms()
    {
        $this->execute(<<<SQL_UP
CREATE SEQUENCE nfse.importacao_dms_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

CREATE TABLE nfse.importacao_dms (
    id                bigint    NOT NULL DEFAULT nextval('nfse.importacao_dms_id_seq'::regclass),
    id_usuario        bigint    NOT NULL,
    data_operacao     date,
    valor_total       double precision,
    valor_imposto     double precision,
    nome_arquivo      character varying(100),
    quantidade_notas  integer,
    codigo_escritorio integer,
    codigo_dms        integer
);

ALTER TABLE ONLY nfse.importacao_dms ADD CONSTRAINT importacao_dms_pkey PRIMARY KEY (id);
SQL_UP
        );
    }

    private function upDDLImportacaoDmsNota()
    {
        $this->execute(<<<SQL_UP
CREATE SEQUENCE nfse.importacao_dms_nota_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

CREATE TABLE nfse.importacao_dms_nota (
    id                   bigint        NOT NULL DEFAULT nextval('nfse.importacao_dms_nota_id_seq'::regclass),
    id_importacao        bigint        NOT NULL,
    id_contribuinte      bigint        NOT NULL,
    codigo_nota_planilha integer,
    numero_nota          integer,
    tipo_nota            integer,
    operacao_nota        varchar(2),
    data_emissao_nota    date,
    valor_total          numeric(15,2) DEFAULT 0,
    valor_imposto        numeric(15,2) DEFAULT 0,
    competencia          varchar(10)
);

ALTER TABLE ONLY nfse.importacao_dms_nota ADD CONSTRAINT importacao_dms_nota_pkey PRIMARY KEY(id);
ALTER TABLE ONLY nfse.importacao_dms_nota ADD CONSTRAINT importacao_dms_nota_id_dms_fkey FOREIGN KEY(id_importacao) REFERENCES nfse.importacao_dms(id);
ALTER TABLE ONLY nfse.importacao_dms_nota ADD CONSTRAINT importacao_dms_nota_id_contribuinte_fk FOREIGN KEY (id_contribuinte) REFERENCES nfse.usuarios_contribuintes(id);
SQL_UP
        );
    }

    private function upDDLUsuariosContribuintes()
    {
        $this->execute(<<<SQL_UP
CREATE SEQUENCE nfse.usuarios_contribuintes_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

CREATE TABLE nfse.usuarios_contribuintes (
    id integer NOT NULL DEFAULT nextval('nfse.usuarios_contribuintes_id_seq'::regclass),
    id_usuario integer,
    im integer,
    habilitado boolean,
    cgm integer,
    tipo_contribuinte integer,
    tipo_emissao integer,
    cnpj_cpf character varying NOT NULL
);

ALTER TABLE ONLY nfse.usuarios_contribuintes ADD CONSTRAINT usuarios_contribuintes_pkey PRIMARY KEY(id);
ALTER SEQUENCE nfse.usuarios_contribuintes_id_seq OWNED BY nfse.usuarios_contribuintes.id;
SQL_UP
        );
    }

  //////////////////////////
 ////////// DOWN //////////
//////////////////////////
    public function downDicionario()
    {
        $this->execute(
        <<<SQL
        delete from db_sysarqcamp where codarq in (
            1010588,
            1010587,
            1010589,
            1010583,
            1010583,
            1010584,
            1010582,
            1010585,
            1010586,
            1010579,
            1010575,
            1010576,
            1010580,
            1010577,
            1010578
        );

        delete from db_syscampo where codcam in (
            1011501,
            1011503,
            1011599,
            1011409,
            1011392,
            1011436,
            1011414,
            1011486,
            1011489,
            1011383,
            1011385,
            1011594,
            1011345,
            1011591,
            1011441,
            1011440,
            1011431,
            1011413,
            1011488,
            1011580,
            1011481,
            1011444,
            1011577,
            1011358,
            1011493,
            1011382,
            1011487,
            1011450,
            1011502,
            1011576,
            1011386,
            1011417,
            1011579,
            1011355,
            1011410,
            1011415,
            1011423,
            1011401,
            1011424,
            1011420,
            1011394,
            1011393,
            1011391,
            1011447,
            1011445,
            1011607,
            1011600,
            1011480,
            1011485,
            1011384,
            1011497,
            1011359,
            1011490,
            1011428,
            1011586,
            1011482,
            1011430,
            1011449,
            1011573,
            1011572,
            1011434,
            1011494,
            1011421,
            1011427,
            1011609,
            1011611,
            1011425,
            1011582,
            1011442,
            1011492,
            1011388,
            1011448,
            1011590,
            1011429,
            1011484,
            1011403,
            1011356,
            1011443,
            1011439,
            1011496,
            1011587,
            1011598,
            1011404,
            1011437,
            1011390,
            1011408,
            1011452,
            1011605,
            1011395,
            1011451,
            1011595,
            1011426,
            1011402,
            1011571,
            1011357,
            1011407,
            1011588,
            1011453,
            1011491,
            1011399,
            1011584,
            1011438,
            1011446,
            1011360,
            1011387,
            1011411,
            1011419,
            1011498,
            1011353,
            1011596,
            1011553,
            1011578,
            1011412,
            1011603,
            1011597,
            1011479,
            1011416,
            1011422,
            1011406,
            1011389,
            1011528,
            1011397,
            1011432,
            1011499,
            1011418,
            1011500,
            1011398,
            1011396,
            1011495,
            1011400,
            1011575,
            1011435,
            1011574,
            1011433,
            1011516,
            1011405,
            1011554,
            1011483
        );

        delete from db_sysarqmod where codmod = 83;

        delete from db_sysmodulo where codmod = 83;

        delete from db_sysarquivo where codarq in (
            1010588,
            1010587,
            1010589,
            1010583,
            1010583,
            1010584,
            1010582,
            1010585,
            1010586,
            1010579,
            1010575,
            1010576,
            1010580,
            1010577,
            1010578
        );


        delete from db_sysprikey where codarq in (
            1010588,
            1010587,
            1010589,
            1010583,
            1010584,
            1010582,
            1010585,
            1010586,
            1010579,
            1010575,
            1010576,
            1010580,
            1010577,
            1010578
        );

        delete from db_syssequencia where codsequencia in (
            1000931,
            1000923,
            1000925,
            1000929,
            1000933,
            1000924,
            1000948,
            1000930,
            1000932,
            1000936,
            1000937,
            1000943,
            1000928,
            1000946
        );
SQL
        );
    }

    public function downNotas()
    {
        $this->execute(
        <<<SQL
        DROP SEQUENCE IF EXISTS nfse.notas_id_seq CASCADE;
        DROP TABLE IF EXISTS nfse.notas;
SQL
        );
    }

    public function downNotasServicos()
    {
        $this->execute(
        <<<SQL
        DROP TABLE IF EXISTS nfse.notas_servicos;
SQL
        );
    }

    public function downDms()
    {
        $this->execute(
        <<<SQL
        DROP SEQUENCE IF EXISTS nfse.dms_id_seq CASCADE;
        DROP TABLE IF EXISTS nfse.dms;
SQL
        );
    }

    public function downDmsNota()
    {
        $this->execute(
        <<<SQL
        DROP SEQUENCE IF EXISTS nfse.dms_nota_id_seq CASCADE;
        DROP TABLE IF EXISTS nfse.dms_nota;
SQL
        );
    }

    public function downCompetencias()
    {
        $this->execute(
        <<<SQL
        ALTER TABLE nfse.guias DROP CONSTRAINT guias_id_competencias_fk;
        DROP TABLE IF EXISTS nfse.competencias;
        DROP SEQUENCE IF EXISTS nfse.competencias_id_seq CASCADE;
SQL
        );
    }

    public function downGuias()
    {
        $this->execute(
        <<<SQL
        DROP SEQUENCE IF EXISTS nfse.guias_id_seq CASCADE;
        DROP TABLE IF EXISTS nfse.guias;
SQL
        );
    }

    public function downSchema()
    {
        $this->execute(
        <<<SQL
        DROP SCHEMA nfse;
SQL
        );
    }

    private function downDDLDmsNotaServicos()
    {
        $this->execute(<<<SQL_DOWN
ALTER TABLE ONLY nfse.dms_nota_servicos DROP CONSTRAINT dms_nota_servicos_id_notas_fkey;
ALTER TABLE ONLY nfse.dms_nota_servicos DROP CONSTRAINT dms_nota_servicos_pkey;
DROP  TABLE nfse.dms_nota_servicos;
SQL_DOWN
        );
    }

    private function downDDLGuiasDms()
    {
        $this->execute(<<<SQL_DOWN
ALTER TABLE ONLY nfse.guias_dms DROP CONSTRAINT guias_dms_id_guia_fk;
ALTER TABLE ONLY nfse.guias_dms DROP CONSTRAINT guias_dms_id_dms_fk;
ALTER TABLE ONLY nfse.guias_dms DROP CONSTRAINT guias_dms_pk;
DROP  TABLE nfse.guias_dms;
SQL_DOWN
        );
    }

    private function downDDLGuiasNota()
    {
        $this->execute(<<<SQL_DOWN
ALTER TABLE ONLY nfse.guias_notas DROP CONSTRAINT guias_notas_id_guia_fk;
ALTER TABLE ONLY nfse.guias_notas DROP CONSTRAINT guias_notas_pk;
DROP TABLE nfse.guias_notas;
SQL_DOWN
        );
    }

    private function downDDLGuiasNumpre()
    {
        $this->execute(<<<SQL_DOWN
ALTER TABLE ONLY nfse.guias_numpre DROP CONSTRAINT guias_numpre_id_pk;
ALTER TABLE ONLY nfse.guias_numpre DROP CONSTRAINT guias_numpre_id_guia_fk;
DROP TABLE nfse.guias_numpre;
DROP SEQUENCE IF EXISTS nfse.guias_numpre_id_seq;
SQL_DOWN
        );
    }

    private function downDDLImportacaoDesif()
    {
        $this->execute(<<<SQL_DOWN
ALTER TABLE ONLY nfse.importacao_desif DROP CONSTRAINT importacao_desif_id_contribuinte_fk;
ALTER TABLE ONLY nfse.importacao_desif DROP CONSTRAINT importacao_desif_comp_inicial_comp_final_comp_uk;
ALTER TABLE ONLY nfse.importacao_desif DROP CONSTRAINT importacao_desif_id_pk;
DROP TABLE IF EXISTS nfse.importacao_desif;
DROP SEQUENCE IF EXISTS nfse.importacao_desif_id_seq;
SQL_DOWN
        );
    }

    private function downDDLImportacaoDms()
    {
        $this->execute(<<<SQL_DOWN
DROP TABLE IF EXISTS nfse.importacao_dms;
DROP SEQUENCE IF EXISTS nfse.importacao_dms_id_seq;
SQL_DOWN
        );
    }

    private function downDDLImportacaoDmsNota()
    {
        $this->execute(<<<SQL_DOWN
ALTER TABLE ONLY nfse.importacao_dms_nota DROP CONSTRAINT importacao_dms_nota_id_dms_fkey;
ALTER TABLE ONLY nfse.importacao_dms_nota DROP CONSTRAINT importacao_dms_nota_pkey;
DROP TABLE IF EXISTS nfse.importacao_dms_nota;
DROP SEQUENCE IF EXISTS nfse.importacao_dms_nota_id_seq;
SQL_DOWN
        );
    }

    private function downDDLUsuariosContribuintes()
    {
        $this->execute(<<<SQL_DOWN
DROP TABLE IF EXISTS nfse.usuarios_contribuintes;
DROP SEQUENCE IF EXISTS nfse.usuarios_contribuintes_id_seq;
SQL_DOWN
        );
    }

}
