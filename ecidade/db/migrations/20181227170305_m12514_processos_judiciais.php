<?php

use Classes\PostgresMigration;

class M12514ProcessosJudiciais extends PostgresMigration
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
        $sql = <<<SQL
            insert into db_sysarquivo values (1010399, 'rhpessoalprocessosjudiciais', 'Processos judiciais do servidor.', 'rh226', '2018-12-27', 'Processos Judiciais', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010399);
            insert into db_syscampo values(1010258,'rh226_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1010259,'rh226_ano','int4','Ano da competência.','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into db_syscampo values(1010260,'rh226_mes','int4','Mês da competência.','0', 'Mês',10,'f','f','f',1,'text','Mês');
            insert into db_syscampo values(1010261,'rh226_matricula','int8','Matrícula do servidor.','0', 'Matrícula',20,'f','f','f',1,'text','Matrícula');
            insert into db_syscampo values(1010262,'rh226_instituicao','int4','Código da Instituição.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_syscampo values(1010263,'rh226_tipoprocesso','int4','Tipo de processo judicial','0', 'Tipo de processo judicial',10,'f','f','f',1,'text','Tipo de processo judicial');
            insert into db_syscampo values(1010264,'rh226_numero','int4','Número do processo','0', 'Número do processo',10,'f','f','f',1,'text','Número do processo');
            insert into db_syscampo values(1010265,'rh226_indicativosuspensao','int4','Código do Indicativo da Suspensão','0', 'Código do Indicativo da Suspensão',10,'t','f','f',1,'text','Código do Indicativo da Suspensão');
            insert into db_sysarqcamp values(1010399,1010258,1,0);
            insert into db_sysarqcamp values(1010399,1010259,2,0);
            insert into db_sysarqcamp values(1010399,1010260,3,0);
            insert into db_sysarqcamp values(1010399,1010261,4,0);
            insert into db_sysarqcamp values(1010399,1010262,5,0);
            insert into db_sysarqcamp values(1010399,1010263,6,0);
            insert into db_sysarqcamp values(1010399,1010264,7,0);
            insert into db_sysarqcamp values(1010399,1010265,8,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010399,1010258,1,1010258);
            insert into db_sysforkey values(1010399,1010262,1,83,0);
            insert into db_sysforkey values(1010399,1010261,1,1153,0);
            insert into db_sysindices values(1008416,'rhpessoalprocessosjudiciais_rh226_matricula_in',1010399,'0');
            insert into db_syscadind values(1008416,1010261,1);
            insert into db_sysindices values(1008417,'rhpessoalprocessosjudiciais_rh226_instituicao_in',1010399,'0');
            insert into db_syscadind values(1008417,1010262,1);
            insert into db_syssequencia values(1000808, 'rhpessoalprocessosjudiciais_rh226_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000808 where codarq = 1010399 and codcam = 1010258;
            update db_syscampo set nomecam = 'rh226_numero', conteudo = 'varchar(20)', descricao = 'Número do processo', valorinicial = '0', rotulo = 'Número do processo', nulo = 'f', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Número do processo' where codcam = 1010264;
            update db_syscampo set nomecam = 'rh226_indicativosuspensao', conteudo = 'varchar(14)', descricao = 'Código do Indicativo da Suspensão', valorinicial = '0', rotulo = 'Código do Indicativo da Suspensão', nulo = 't', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Código do Indicativo da Suspensão' where codcam = 1010265;
            update db_itensmenu set id_item = 228075 , descricao = 'Remuneração' , help = 'Remuneração' , itemativo = '1' , manutencao = '1' , desctec = 'Menu referente aos formulários de remuneração.' , libcliente = 'false' where id_item = 228075;
SQL;
        $this->execute($sql);
    }

    private function upTabela() {
        $sql = <<<SQL

        CREATE SEQUENCE pessoal.rhpessoalprocessosjudiciais_rh226_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        CREATE TABLE pessoal.rhpessoalprocessosjudiciais(
            rh226_sequencial int primary key,
            rh226_ano int not null,
            rh226_mes int not null,
            rh226_matricula int not null,
            rh226_instituicao int not null,
            rh226_tipoprocesso int not null,
            rh226_numero VARCHAR(20) not null,
            rh226_indicativosuspensao VARCHAR(14)
        );

        ALTER TABLE pessoal.rhpessoalprocessosjudiciais
            ADD CONSTRAINT rhpessoalprocessosjudiciais_instituicao_fk FOREIGN KEY (rh226_instituicao)
            REFERENCES configuracoes.db_config;

        ALTER TABLE pessoal.rhpessoalprocessosjudiciais
            ADD CONSTRAINT rhpessoalprocessosjudiciais_matricula_fk FOREIGN KEY (rh226_matricula)
            REFERENCES pessoal.rhpessoal;

        CREATE INDEX rhpessoalprocessosjudiciais_rh226_instituicao_in ON pessoal.rhpessoalprocessosjudiciais(rh226_instituicao);
        CREATE INDEX rhpessoalprocessosjudiciais_rh226_matricula_in ON pessoal.rhpessoalprocessosjudiciais(rh226_matricula);


SQL;
        $this->execute($sql);
    }

    private function upMenu() {
        $sql = <<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228089 ,'Processos Judiciais da Folha' ,'Processos judiciais do servidor' ,'pes1_rhpessoalprocessosjudiciaisfolha001.php' ,'1' ,'1' ,'Processos judiciais do servidor' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4354 ,228089 ,8 ,952 );
SQL;
        $this->execute($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
        DELETE FROM db_syssequencia WHERE codsequencia = 1000808;
        DELETE FROM db_syscadind WHERE codind IN (1008416, 1008417);
        DELETE FROM db_sysindices WHERE codind IN (1008416, 1008417);
        DELETE FROM db_sysprikey WHERE codarq = 1010399;
        DELETE FROM db_sysforkey WHERE codarq = 1010399;
        DELETE FROM db_sysarqcamp WHERE codarq = 1010399;
        DELETE FROM db_syscampo WHERE codcam IN (1010258, 1010259, 1010260, 1010261, 1010262, 1010263, 1010264, 1010265);
        DELETE FROM db_sysarqmod WHERE codarq = 1010399;
        DELETE FROM db_sysarquivo WHERE codarq = 1010399;
SQL;
        $this->execute($sql);
    }

    private function downTabela() {
        $sql = <<<SQL
        DROP SEQUENCE rhpessoalprocessosjudiciais_rh226_sequencial_seq;
        DROP TABLE rhpessoalprocessosjudiciais;
SQL;
        $this->execute($sql);
    }

    private function downMenu() {
        $sql = <<<SQL
        delete from db_menu where id_item_filho = 228089 AND modulo = 952;
        delete from db_itensmenu where id_item = 228089;
SQL;
        $this->execute($sql);
    }

}
