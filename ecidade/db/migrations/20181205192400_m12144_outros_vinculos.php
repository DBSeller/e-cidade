<?php

use Classes\PostgresMigration;

class M12144OutrosVinculos extends PostgresMigration
{

    public function up() {

        $this->upDicionario();
        $this->upTabela();
        $this->upMenu();
    }

    public function down() {
        $this->downDicionario();
        $this->downTabela();
        $this->downMenu();
    }

    private function upDicionario() {
            $sql = "
            insert into db_sysarquivo values (1010347, 'rhpessoaloutrosvinculos', 'Outros vínculos do Servidor.', 'rh224', '2018-12-05', 'Outros vínculos', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010347);
            insert into db_syscampo values(1010137,'rh224_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1010139,'rh224_tipocontribuicao','int4','Indicativo do tipo de contribuição.','0', 'Indicativo do tipo de contribuição',10,'f','f','f',1,'text','Indicativo do tipo de contribuição');
            insert into db_syscampo values(1010140,'rh224_tipoinscricao','int4','Tipo de inscrição (1- CNPJ/ 2- CPF)','0', 'Tipo de inscrição',10,'f','f','f',1,'text','Tipo de inscrição');
            insert into db_syscampo values(1010141,'rh224_numeroinscricao','int4','Número de inscrição.','0', 'Número de inscrição',10,'f','f','f',1,'text','Número de inscrição');
            insert into db_syscampo values(1010142,'rh224_codigocategoria','int4','Código da categoria referente a tabela 1 do eSocial.','0', 'Código da categoria',10,'f','f','f',1,'text','Código da categoria');
            insert into db_syscampo values(1010143,'rh224_valorremuneracao','float4','Valor de remuneração.','0', 'Valor de remuneração',10,'f','f','f',4,'text','Valor de remuneração');
            insert into db_syscampo values(1010144,'rh224_instituicao','int4','Código da Instituição.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_syscampo values(1010176,'rh224_ano','int4','Ano','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into db_syscampo values(1010177,'rh224_mes','int4','Mês','0', 'Mês',10,'f','f','f',1,'text','Mês');
            insert into db_syscampo values(1010178,'rh224_matricula','int8','Matrícula','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');

            insert into db_sysarqcamp values(1010347,1010137,1,0);
            insert into db_sysarqcamp values(1010347,1010139,2,0);
            insert into db_sysarqcamp values(1010347,1010140,3,0);
            insert into db_sysarqcamp values(1010347,1010141,4,0);
            insert into db_sysarqcamp values(1010347,1010142,5,0);
            insert into db_sysarqcamp values(1010347,1010143,6,0);
            insert into db_sysarqcamp values(1010347,1010144,7,0);
            insert into db_sysarqcamp values(1010347,1010176,8,0);
            insert into db_sysarqcamp values(1010347,1010177,9,0);
            insert into db_sysarqcamp values(1010347,1010178,10,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010347,1010137,1,1010137);

            insert into db_sysforkey values(1010347,1010178,1,1153,0);
            insert into db_sysindices values(1008387,'rhpessoaloutrosvinculos_rh224_matricula_in',1010347,'0');
            insert into db_syscadind values(1008387,1010178,1);

            insert into db_syssequencia values(1000794, 'rhpessoaloutrosvinculos_rh224_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000794 where codarq = 1010347 and codcam = 1010137;
            update db_syscampo set nomecam = 'rh224_numeroinscricao', conteudo = 'varchar(14)', descricao = 'Número de inscrição.', valorinicial = '0', rotulo = 'Número de inscrição', nulo = 'f', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Número de inscrição' where codcam = 1010141;
        ";
        $this->execute($sql);
    }


    private function upTabela() {
        $sql = "
            CREATE SEQUENCE pessoal.rhpessoaloutrosvinculos_rh224_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;

            CREATE TABLE pessoal.rhpessoaloutrosvinculos(
                rh224_sequencial INTEGER DEFAULT nextval('pessoal.rhpessoaloutrosvinculos_rh224_sequencial_seq') NOT NULL PRIMARY KEY,
                rh224_ano NUMERIC(4) NOT NULL,
                rh224_mes NUMERIC(2) NOT NULL,
                rh224_matricula INTEGER NOT NULL,
                rh224_tipocontribuicao INTEGER NOT NULL, 
                rh224_tipoinscricao INTEGER NOT NULL, 
                rh224_numeroinscricao VARCHAR(14) NOT NULL, 
                rh224_codigocategoria INTEGER NOT NULL, 
                rh224_valorremuneracao NUMERIC(15, 2) NOT NULL,
                rh224_instituicao INTEGER NOT NULL
            );

            ALTER TABLE pessoal.rhpessoaloutrosvinculos
            ADD CONSTRAINT rhpessoaloutrosvinculos_matricula_fk FOREIGN KEY (rh224_matricula)
            REFERENCES pessoal.rhpessoal;

            CREATE INDEX rhpessoaloutrosvinculos_rh224_matricula_in ON pessoal.rhpessoaloutrosvinculos(rh224_matricula);
        ";
        $this->execute($sql);
    }

    private function upMenu() {
        $sql = "
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228072 ,'Outros Vínculos' ,'Outros vínculos do servidor' ,'pes1_rhpessoaloutrosvinculos001.php' ,'1' ,'1' ,'Outros vínculos do servidor.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4354 ,228072 ,7 ,952 );
        ";
        $this->execute($sql);
    }

    private function downDicionario() {
        $sql = "
            delete from db_syssequencia where codsequencia = 1000794;
            delete from db_syscadind where codind = 1008387;
            delete from db_sysindices where codind = 1008387;
            delete from db_sysforkey where codarq = 1010347;
            delete from db_sysprikey where codarq = 1010347;
            delete from db_sysarqcamp where codarq = 1010347;
            delete from db_syscampo where codcam in (1010137,1010139,1010140,1010141,1010142,1010143,1010144,1010176, 1010177, 1010178);
            delete from db_sysarqmod where codarq = 1010347;
            delete from db_sysarquivo where codarq = 1010347;
        ";
        $this->execute($sql);
    }

    private function downTabela() {
        $sql = "
            DROP TABLE pessoal.rhpessoaloutrosvinculos;
            DROP SEQUENCE pessoal.rhpessoaloutrosvinculos_rh224_sequencial_seq;
        ";
        $this->execute($sql);
    }

    private function downMenu() {
        $sql = "
            delete from db_menu where id_item_filho = 228072 AND modulo = 952;
            delete from db_itensmenu where id_item = 228072;
        ";
        $this->execute($sql);
    }
}
