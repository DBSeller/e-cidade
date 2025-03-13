<?php

use Classes\PostgresMigration;

class M16123IssVeiculos extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upTabela();
        $this->upInsertGrupoAlvara();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downTabela();
        $this->downInsertGrupoAlvara();
    }

    public function upDicionario()
    {
        $sql = <<<SQL
        -- ISSVEICULO
        insert into db_sysarquivo values (1010602, 'issveiculo', 'Guarda os dados da inscrição de um veículo', 'q172', '2020-07-09', 'ISS Veículo', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (3,1010602);
        insert into db_syscampo values(1011688,'q172_sequencial','int8','Código sequencial da tabela.','0', 'Código',20,'f','f','f',1,'text','Código');
        insert into db_syscampo values(1011689,'q172_datacadastro','date','Data em que o registro foi incluído.','null', 'Data de cadastro',10,'f','f','f',1,'text','Data de cadastro');
        insert into db_syscampo values(1011691,'q172_issbase','int8','Vínculo dos dados do veículo com a sua inscrição.','0', 'Inscrição',10,'f','f','f',1,'text','Inscrição');
        insert into db_syscampo values(1011692,'q172_tipo','int8','Vinculo com o típo de veículo (veiccadtipo).','0', 'Tipo de veículo',20,'t','f','f',1,'text','Tipo de veículo');
        insert into db_syscampo values(1011693,'q172_marca','int8','Vínculo com a marca do veícullo (veiccadmarca).','0', 'Marca',20,'t','f','f',1,'text','Marca');
        insert into db_syscampo values(1011694,'q172_modelo','int8','Vínculo com o modelo do veículo (veiccadmodelo).','0', 'Modelo',20,'t','f','f',1,'text','Modelo');
        insert into db_syscampo values(1011695,'q172_cor','int8','Vínculo com a cor do veículo (veiccadcor).','0', 'Cor',10,'t','f','f',1,'text','Cor');
        insert into db_syscampo values(1011696,'q172_procedencia','int8','Vinculo com a procedência do veículo (veiccadproced).','0', 'Procedência',20,'t','f','f',1,'text','Procedência');
        insert into db_syscampo values(1011697,'q172_categoria','int8','Vínculo com a categoria do veículo (veiccadcateg).','0', 'Categoria',20,'t','f','f',1,'text','Categoria');
        insert into db_syscampo values(1011698,'q172_chassi','varchar(20)','Número do chassi do veículo.','', 'Número do Chassi',20,'t','t','f',0,'text','Número do Chassi');
        insert into db_syscampo values(1011699,'q172_renavam','varchar(20)','Renavam do veículo.','', 'Renavam',20,'t','t','f',0,'text','Renavam');
        insert into db_syscampo values(1011700,'q172_placa','varchar(20)','Placa do veículo.','', 'Placa',20,'t','t','f',0,'text','Placa');
        insert into db_syscampo values(1011701,'q172_potencia','varchar(20)','Potência do veículo.','', 'Potência',20,'t','t','f',0,'text','Potência');
        insert into db_syscampo values(1011702,'q172_capacidade','int4','Capacidade do veículo.','0', 'Capacidade',10,'t','f','f',1,'text','Capacidade');
        insert into db_syscampo values(1011703,'q172_anofabricacao','int4','Ano de fabricação do veículo.','0', 'Ano de fabricação',10,'t','f','f',1,'text','Ano de fabricação');
        insert into db_syscampo values(1011704,'q172_anomodelo','int4','Ano do modelo do veículo.','0', 'Ano do modelo',10,'t','f','f',1,'text','Ano do modelo');
        insert into db_sysarqcamp values(1010602,1011688,1,0);
        insert into db_sysarqcamp values(1010602,1011689,2,0);
        insert into db_sysarqcamp values(1010602,1011691,4,0);
        insert into db_sysarqcamp values(1010602,1011692,5,0);
        insert into db_sysarqcamp values(1010602,1011693,6,0);
        insert into db_sysarqcamp values(1010602,1011694,7,0);
        insert into db_sysarqcamp values(1010602,1011695,8,0);
        insert into db_sysarqcamp values(1010602,1011696,9,0);
        insert into db_sysarqcamp values(1010602,1011697,10,0);
        insert into db_sysarqcamp values(1010602,1011698,11,0);
        insert into db_sysarqcamp values(1010602,1011699,12,0);
        insert into db_sysarqcamp values(1010602,1011700,13,0);
        insert into db_sysarqcamp values(1010602,1011701,14,0);
        insert into db_sysarqcamp values(1010602,1011702,15,0);
        insert into db_sysarqcamp values(1010602,1011703,16,0);
        insert into db_sysarqcamp values(1010602,1011704,17,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010602,1011688,1,1011688);
        insert into db_sysforkey values(1010602,1011691,1,41,0);
        insert into db_sysforkey values(1010602,1011692,1,1576,0);
        insert into db_sysforkey values(1010602,1011693,1,1577,0);
        insert into db_sysforkey values(1010602,1011694,1,1578,0);
        insert into db_sysforkey values(1010602,1011695,1,1579,0);
        insert into db_sysforkey values(1010602,1011696,1,1597,0);
        insert into db_sysforkey values(1010602,1011697,1,1599,0);
        insert into db_syssequencia values(1000956, 'issveiculo_q172_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000956 where codarq = 1010602 and codcam = 1011688;

        -- ISSVEICULOCONDUTORAUXILIAR
        insert into db_sysarquivo values (1010603, 'issveiculocondutorauxiliar', 'Guarda os condutores auxiliares de uma inscrição de veículo.', 'q173', '2020-07-09', 'Condutor Auxiliar', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (3,1010603);
        insert into db_syscampo values(1011705,'q173_cgm','int8','CGM do condutor auxiliar.','0', 'Condutor Auxiliar',20,'f','f','f',1,'text','Condutor Auxiliar');
        insert into db_syscampo values(1011706,'q173_datainicio','date','Data de início.','null', 'Data de início',10,'f','f','f',1,'text','Data de início');
        insert into db_syscampo values(1011707,'q173_datafim','date','Data final.','null', 'Data de fim',10,'t','f','f',1,'text','Data de fim');
        insert into db_syscampo values(1011708,'q173_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código');
        insert into db_syscampo values(1011709,'q173_issveiculo','int8','Guarda o vínculo cm a inscrição do veículo (issveiculo).','0', 'Inscrição do veículo',20,'f','f','f',1,'text','Inscrição do veículo');
        insert into db_sysarqcamp values(1010603,1011708,1,0);
        insert into db_sysarqcamp values(1010603,1011709,2,0);
        insert into db_sysarqcamp values(1010603,1011705,3,0);
        insert into db_sysarqcamp values(1010603,1011706,4,0);
        insert into db_sysarqcamp values(1010603,1011707,5,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010603,1011708,1,1011708);
        insert into db_sysforkey values(1010603,1011709,1,1010602,0);
        insert into db_sysforkey values(1010603,1011705,1,42,0);
        insert into db_syssequencia values(1000957, 'issveiculocondutorauxiliar_q173_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000957 where codarq = 1010603 and codcam = 1011708;

SQL;
        $this->execute($sql);
    }

    public function downDicionario()
    {
        $sql = <<<SQL
        delete from db_syssequencia where codsequencia in (1000956, 1000957);
        delete from db_sysprikey where codarq in (1010602, 1010603);
        delete from db_sysforkey where codarq in(1010602, 1010603);
        delete from db_sysarqcamp where codarq in (1010602, 1010603);
        delete from db_syscampo where codcam in (1011688, 1011689, 1011691, 1011692, 1011693, 1011694, 1011695, 1011696, 1011697, 1011698, 1011699, 1011700, 1011701, 1011702, 1011703, 1011704, 1011705, 1011706, 1011707, 1011708, 1011709);
        delete from db_sysarqmod where codarq in (1010602, 1010603);
        delete from db_sysarquivo where codarq in (1010602, 1010603);
SQL;
        $this->execute($sql);
    }

    public function upTabela()
    {
        $sql = <<<SQL
            CREATE SEQUENCE issqn.issveiculo_q172_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE issqn.issveiculo(
                q172_sequencial int8 NOT NULL DEFAULT nextval('issqn.issveiculo_q172_sequencial_seq'),
                q172_datacadastro date NOT NULL DEFAULT NOW(),
                q172_issbase int8 NOT NULL,
                q172_tipo int8,
                q172_marca int8,
                q172_modelo int8,
                q172_cor int8,
                q172_procedencia int8,
                q172_categoria int8,
                q172_chassi varchar(20),
                q172_renavam varchar(20),
                q172_placa varchar(20),
                q172_potencia varchar(20),
                q172_capacidade int4,
                q172_anofabricacao int4,
                q172_anomodelo int4,
                CONSTRAINT issveiculo_sequ_pk PRIMARY KEY (q172_sequencial));

            ALTER TABLE issqn.issveiculo
            ADD CONSTRAINT issveiculo_issbase_fk FOREIGN KEY (q172_issbase)
            REFERENCES issqn.issbase;

            ALTER TABLE issqn.issveiculo
            ADD CONSTRAINT issveiculo_tipo_fk FOREIGN KEY (q172_tipo)
            REFERENCES veiculos.veiccadtipo;

            ALTER TABLE issqn.issveiculo
            ADD CONSTRAINT issveiculo_marca_fk FOREIGN KEY (q172_marca)
            REFERENCES veiculos.veiccadmarca;

            ALTER TABLE issqn.issveiculo
            ADD CONSTRAINT issveiculo_modelo_fk FOREIGN KEY (q172_modelo)
            REFERENCES veiculos.veiccadmodelo;

            ALTER TABLE issqn.issveiculo
            ADD CONSTRAINT issveiculo_cor_fk FOREIGN KEY (q172_cor)
            REFERENCES veiculos.veiccadcor;

            ALTER TABLE issqn.issveiculo
            ADD CONSTRAINT issveiculo_procedencia_fk FOREIGN KEY (q172_procedencia)
            REFERENCES veiculos.veiccadproced;

            ALTER TABLE issqn.issveiculo
            ADD CONSTRAINT issveiculo_categoria_fk FOREIGN KEY (q172_categoria)
            REFERENCES veiculos.veiccadcateg;

            CREATE SEQUENCE issqn.issveiculocondutorauxiliar_q173_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE issqn.issveiculocondutorauxiliar(
                q173_sequencial int8 NOT NULL DEFAULT nextval('issqn.issveiculocondutorauxiliar_q173_sequencial_seq'),
                q173_issveiculo int8 NOT NULL,
                q173_cgm int8 NOT NULL,
                q173_datainicio date NOT NULL,
                q173_datafim date,
                CONSTRAINT issveiculocondutorauxiliar_sequ_pk PRIMARY KEY (q173_sequencial));

            ALTER TABLE issqn.issveiculocondutorauxiliar
                ADD CONSTRAINT issveiculocondutorauxiliar_issveiculo_fk FOREIGN KEY (q173_issveiculo)
                REFERENCES issqn.issveiculo;

            ALTER TABLE issqn.issveiculocondutorauxiliar
                ADD CONSTRAINT issveiculocondutorauxiliar_cgm_fk FOREIGN KEY (q173_cgm)
                REFERENCES protocolo.cgm;

            SELECT configuracoes.fc_auditoria_cria_funcao('issqn.issveiculo');
            SELECT configuracoes.fc_auditoria_cria_funcao('issqn.issveiculocondutorauxiliar');
SQL;
        $this->execute($sql);
    }

    public function downTabela()
    {
        $sql = <<<SQL
            SELECT configuracoes.fc_auditoria_remove_funcao('issqn.issveiculo');
            SELECT configuracoes.fc_auditoria_remove_funcao('issqn.issveiculocondutorauxiliar');

            DROP TABLE issqn.issveiculocondutorauxiliar;
            DROP SEQUENCE issqn.issveiculocondutorauxiliar_q173_sequencial_seq;
            DROP TABLE issqn.issveiculo;
            DROP SEQUENCE issqn.issveiculo_q172_sequencial_seq;
SQL;
        $this->execute($sql);
    }

    public function upInsertGrupoAlvara()
    {
        $this->execute("INSERT INTO issgrupotipoalvara(q97_sequencial, q97_descricao, q97_isstipogrupoalvara) VALUES (8, 'VEÍCULOS', 1);");
    }

    public function downInsertGrupoAlvara()
    {
        $this->execute("DELETE FROM issgrupotipoalvara WHERE q97_sequencial = 8;");
    }
}
