<?php

use Classes\PostgresMigration;

class M10657S1299Estrutura extends PostgresMigration
{

    public function up()
    {
        $this->dicionario();
        $this->esocial();
        $this->estrutura();
        $this->menu();
    }

    public function dicionario()
    {
        $this->execute("
            insert into db_sysarquivo values (1010407, 'avaliacaogruporespostaesocials1299', 'respostas do fechamento do esocial s-1299 ', 'eso33', '2019-01-11', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (81,1010407);
            insert into db_syscampo 
            values (1010295,'eso33_sequencial','int4','PK','0', 'Código',10,'f','f','f',1,'text','Código'),
                   (1010296,'eso33_empregador','int4','cgm do empregador','0', 'Empregador',10,'f','f','f',1,'text','Empregador'),
                   (1010297,'eso33_indicativoapuracao','int4','Inficativo de Apuração: 1 - Mensal; 2 - Anual (13° salário).','0', 'Inficativo de Apuração',10,'f','f','f',1,'text','Inficativo de Apuração'),
                   (1010299,'eso33_avaliacaogruporesposta','int4','id do Preenchimento','0', 'Preenchimento',10,'f','f','f',1,'text','Preenchimento'),
                   (1010300,'eso33_periodo','varchar(7)','Período do fechamento: (formato AAAA-MM) se Inficativo de Apuração = 1; ou apenas o ano (formato AAAA), se Inficativo de Apuração = 2; ','', 'Período',7,'f','t','f',0,'text','Período'),
                   (1010301,'eso33_avaliacao','int4','Avalição','0', 'Avaliação',10,'f','f','f',1,'text','Avaliação');
            
            insert into db_sysarqcamp 
            values (1010407,1010295,1,0),
                   (1010407,1010296,2,0),
                   (1010407,1010299,3,0),
                   (1010407,1010297,4,0),
                   (1010407,1010301,6,0);
                   
            insert into db_sysarqcamp values(1010407,1010300,5,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010407,1010295,1,1010295);
            
            insert into db_sysforkey 
            values (1010407,1010296,1,42,0),    
                   (1010407,1010299,1,2987,0),
                   (1010407,1010301,1,2980,0);  
            
            insert into db_sysindices 
            values (1008424,'avaliacaogruporespostaesocials1299_unico_in',1010407,'1'),
                   (1008425,'avaliacaogruporespostaesocials1299_avaliacaogruporesposta_in',1010407,'0');
            
            insert into db_syscadind 
            values (1008424,1010296,1),
                   (1008424,1010297,2),
                   (1008424,1010300,3),
                   (1008425,1010299,1);
            
            insert into db_syssequencia values(1000814, 'avaliacaogruporespostaesocials1299_eso33_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000814 where codarq = 1010407 and codcam = 1010295;    
        ");
    }

    public function esocial()
    {
        $this->execute("insert into  recursoshumanos.esocialformulariotipo values (31, 'S-1299 Fechamento dos Eventos Periódicos')");
        $this->execute("insert into  recursoshumanos.esocialversaoformulario values ( nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'::regclass), '2.4', 3000043, 31)");
    }

    private function estrutura()
    {
        $this->execute("
            CREATE SEQUENCE avaliacaogruporespostaesocials1299_eso33_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
    
            CREATE TABLE avaliacaogruporespostaesocials1299(
            eso33_sequencial int4 default nextval('avaliacaogruporespostaesocials1299_eso33_sequencial_seq'),
            eso33_empregador int4 not null,
            eso33_indicativoapuracao int4 not null,
            eso33_avaliacaogruporesposta int4 not null,
            eso33_periodo varchar(7) not null,
            eso33_avaliacao int4 not null,
            CONSTRAINT avaliacaogruporespostaesocials1299_sequ_pk PRIMARY KEY (eso33_sequencial));
            
            ALTER TABLE avaliacaogruporespostaesocials1299 ADD CONSTRAINT avaliacaogruporespostaesocials1299_empregador_fk FOREIGN KEY (eso33_empregador) REFERENCES cgm;
            ALTER TABLE avaliacaogruporespostaesocials1299 ADD CONSTRAINT avaliacaogruporespostaesocials1299_avaliacaogruporesposta_fk FOREIGN KEY (eso33_avaliacaogruporesposta) REFERENCES avaliacaogruporesposta;
            ALTER TABLE avaliacaogruporespostaesocials1299 ADD CONSTRAINT avaliacaogruporespostaesocials1299_avaliacao_fk FOREIGN KEY (eso33_avaliacao) REFERENCES avaliacao;
            
            CREATE UNIQUE INDEX avaliacaogruporespostaesocials1299_unico_in ON avaliacaogruporespostaesocials1299(eso33_empregador,eso33_indicativoapuracao,eso33_periodo);
            CREATE  INDEX avaliacaogruporespostaesocials1299_avaliacaogruporesposta_in ON avaliacaogruporespostaesocials1299(eso33_avaliacaogruporesposta);
        ");
    }

    public function menu()
    {
        $this->execute("
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228093 ,'Fechamento dos Eventos Periódicos' ,'Fechamento dos Eventos Periódicos' ,'es04_s1299fechamentoeventos001.php' ,'1' ,'1' ,'Fechamento dos Eventos Periódicos' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,228093 ,17 ,10216 );
        ");
    }

    public function down()
    {
        $this->execute("
        delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 31;
        delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 31;
        ");

        $this->execute("
            delete from db_syscampodep where codcam = 1010300;
            delete from db_syscampodef where codcam = 1010300;
            delete from db_sysarqcamp where codarq = 1010407;
            delete from db_sysprikey where codarq = 1010407;
            delete from db_sysforkey where codarq = 1010407;
            delete from db_syssequencia where codsequencia = 1000814;
            delete from db_syscadind where codind in (1008424, 1008425);
            delete from db_sysindices where codind in (1008424, 1008425);
            delete from db_sysarqcamp where codcam in (1010295, 1010296, 1010297, 1010299, 1010300, 1010301);
            delete from db_syscampo where codcam in (1010295, 1010296, 1010297, 1010299, 1010300, 1010301);
            delete from db_sysarqmod where codarq =1010407;
            delete from db_sysarquivo where codarq =1010407;
        ");

        $this->execute("
            DROP TABLE IF EXISTS avaliacaogruporespostaesocials1299 CASCADE;
            DROP SEQUENCE IF EXISTS avaliacaogruporespostaesocials1299_eso33_sequencial_seq;
        ");

        $this->execute("
            delete from db_menu where id_item_filho = 228093 AND modulo = 10216;
            delete from db_itensmenu where id_item = 228093;
        ");
    }
}
