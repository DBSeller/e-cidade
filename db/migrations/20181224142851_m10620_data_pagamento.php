<?php

use Classes\PostgresMigration;

class M10620DataPagamento extends PostgresMigration
{
    public function up() {
        $this->upDicionario();
        $this->upTabela();
    }

    public function down() {
        $this->downDicionario();
        $this->downTabela();  
    }

    private function upDicionario() {
        $sql = <<<SQL
        INSERT INTO db_syscampo VALUES(1010252,'rh05_datapagamento','date','Data na qual será realizado o pagamento da rescisão.','null', 'Data de Pagamento',10,'f','f','f',1,'text','Data de Pagamento');
        UPDATE db_syscampo SET nomecam = 'rh05_datapagamento', conteudo = 'date', descricao = 'Data na qual será realizado o pagamento da rescisão.', valorinicial = 'null', rotulo = 'Data de Pagamento', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data de Pagamento' WHERE codcam = 1010252;
        INSERT INTO db_sysarqcamp VALUES(1161,1010252,16,0);

        INSERT INTO db_sysarquivo VALUES (1010398, 'rhdatapagamentofolha', 'Guarda em qual data foi realizado o pagamento da folha para o eSocial.', 'rh225', '2018-12-26', 'Data de Pagamento da Folha', 0, 'f', 'f', 'f', 'f' );
        INSERT INTO db_sysarqmod VALUES (28,1010398);
        INSERT INTO db_syscampo VALUES(1010253,'rh225_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código');
        INSERT INTO db_syscampo VALUES(1010254,'rh225_instituicao','int4','Código da instituição.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
        INSERT INTO db_syscampo VALUES(1010255,'rh225_ano','int4','Ano da competência na qual se refere a data de pagamento.','0', 'Ano',10,'f','f','f',1,'text','Ano');
        INSERT INTO db_syscampo VALUES(1010256,'rh225_mes','int4','Mês da competência na qual se refere a data de pagamento.','0', 'Mês',10,'f','f','f',1,'text','Mês');
        INSERT INTO db_syscampo VALUES(1010257,'rh225_datapagamento','date','Data de pagamento da folha.','null', 'Data de Pagamento',10,'f','f','f',1,'text','Data de Pagamento');
        INSERT INTO db_sysarqcamp VALUES(1010398,1010253,1,0);
        INSERT INTO db_sysarqcamp VALUES(1010398,1010254,2,0);
        INSERT INTO db_sysarqcamp VALUES(1010398,1010255,3,0);
        INSERT INTO db_sysarqcamp VALUES(1010398,1010256,4,0);
        INSERT INTO db_sysarqcamp VALUES(1010398,1010257,5,0);
        INSERT INTO db_sysforkey VALUES(1010398,1010254,1,83,0);
        INSERT INTO db_sysindices VALUES(1008415,'rhdatapagamentofolha_rh225_instituicao_in',1010398,'0');
        INSERT INTO db_syscadind VALUES(1008415,1010254,1);
        INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010398,1010253,1,1010253);
        INSERT INTO db_syssequencia VALUES(1000807, 'rhdatapagamentofolha_rh225_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        UPDATE db_sysarqcamp SET codsequencia = 1000807 WHERE codarq = 1010398 AND codcam = 1010253;   

SQL;
        $this->execute($sql);
    }

    private function upTabela() {
        $sql = <<<SQL
        ALTER TABLE pessoal.rhpesrescisao ADD COLUMN rh05_datapagamento date;

        CREATE SEQUENCE pessoal.rhdatapagamentofolha_rh225_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        CREATE TABLE pessoal.rhdatapagamentofolha(
            rh225_sequencial int primary key,
            rh225_instituicao int not null,
            rh225_ano int not null,
            rh225_mes int not null,
            rh225_datapagamento date not null
        );

        ALTER TABLE pessoal.rhdatapagamentofolha
            ADD CONSTRAINT rhdatapagamentofolha_instituicao_fk FOREIGN KEY (rh225_instituicao)
            REFERENCES configuracoes.db_config;

        CREATE INDEX rhdatapagamentofolha_rh225_instituicao_in ON pessoal.rhdatapagamentofolha(rh225_instituicao);
SQL;
        $this->execute($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
        DELETE FROM db_sysarqcamp WHERE codcam = 1010252;
        DELETE FROM db_syscampo WHERE codcam = 1010252;

        DELETE FROM db_syssequencia WHERE codsequencia = 1000807;
        DELETE FROM db_syscadind WHERE codind = 1008415;
        DELETE FROM db_sysindices WHERE codind = 1008415;
        DELETE FROM db_sysprikey WHERE codarq = 1010398;
        DELETE FROM db_sysforkey WHERE codarq = 1010398;
        DELETE FROM db_sysarqcamp WHERE codarq = 1010398;
        DELETE FROM db_syscampo WHERE codcam in (1010253, 1010254, 1010255, 1010256, 1010257);
        DELETE FROM db_sysarqmod WHERE codarq = 1010398;
        DELETE FROM db_sysarquivo WHERE codarq = 1010398;
SQL;
        $this->execute($sql);
    }

    private function downTabela() {
        $sql = <<<SQL
        ALTER TABLE pessoal.rhpesrescisao drop column rh05_datapagamento;

        DROP SEQUENCE rhdatapagamentofolha_rh225_sequencial_seq;
        DROP TABLE rhdatapagamentofolha;
SQL;
        $this->execute($sql);
    }


}
