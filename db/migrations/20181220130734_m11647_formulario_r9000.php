<?php

use Classes\PostgresMigration;

class M11647FormularioR9000 extends PostgresMigration
{
    public function up()
    {
        $this->criaFormulario();
        $this->criaDicionarioDados();
        $this->criaTabela();
        $this->criaEstruturaSped();
        $this->criaMenu();
    }

    public function down()
    {
        $this->deletaEstruturaSped();
        $this->deletaTabela();
        $this->deletaDicionarioDados();
        $this->deletaFormulario();
        $this->deletaMenu();
    }

    private function criaFormulario()
    {
        $sql = "
            INSERT INTO avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) VALUES ( 3000038 ,8 ,'R-9000 - Exclusão de Eventos' ,'r9000-exclusao-de-eventos' ,'Registros do evento R-9000 - Exclusão de Eventos' ,'true' ,'' ,'true' );
            INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) VALUES ( 3000539 ,3000038 ,'Registro que identifica o evento objeto da exclusão' ,'registro-que-identifica-o-evento-objeto-da-exclusa' ,'infoExclusao' ,1 );
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) VALUES ( 3002426 ,2 ,3000539 ,'Preencher com o tipo de evento, conforme tabela 10' ,'preencher-com-o-tipo-de-evento-conforme-tabela-10' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpEvento' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 4001051 ,3002426 ,'' ,'5c1b93d8e95f1' ,'true' ,0 ,'' ,'tpEvento' );
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) VALUES ( 3002427 ,2 ,3000539 ,'Preencher com o número do recibo do evento que será excluído' ,'preencher-com-o-numero-do-recibo-do-evento-que-ser' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nrRecEvt' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 4001052 ,3002427 ,'' ,'5c1b93d90b7a5' ,'true' ,0 ,'' ,'nrRecEvt' );
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) VALUES ( 3002428 ,2 ,3000539 ,'Informar o período de referência das informações no formato AAAA-MM' ,'nformar-o-periodo-de-referencia-das-informacoes-no' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'perApur' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 4001053 ,3002428 ,'' ,'5c1b93d911d22' ,'true' ,0 ,'' ,'perApur' );
        ";
        $this->execute($sql);
    }

    private function deletaFormulario()
    {
        $sql = "
            create temp table x_avaliacaopergunta as
             select db103_sequencial
               from avaliacaopergunta
              where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000038);
            
            create temp table x_avaliacaoperguntaopcao as
             select db104_sequencial
               from avaliacaoperguntaopcao
              where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
              
            DELETE
            FROM avaliacaogrupoperguntaresposta
            WHERE db108_avaliacaoresposta IN (SELECT db106_sequencial 
                                              FROM avaliacaoresposta  
                                              WHERE db106_avaliacaoperguntaopcao IN (SELECT db104_sequencial 
                                                                               FROM avaliacaoperguntaopcao
                                                                               WHERE db104_avaliacaopergunta IN (SELECT db103_sequencial 
                                                                                                               FROM x_avaliacaopergunta)));        
            
            DELETE 
            FROM avaliacaoresposta  
            WHERE db106_avaliacaoperguntaopcao IN (SELECT db104_sequencial 
                                             FROM avaliacaoperguntaopcao
                                             WHERE db104_avaliacaopergunta IN (SELECT db103_sequencial 
                                                                               FROM x_avaliacaopergunta));
                                                                               
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaopergunta where db103_sequencial in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaogrupopergunta where db102_avaliacao = 3000038;
            delete from avaliacao where db101_sequencial = 3000038;
            
            drop table x_avaliacaopergunta;
            drop table x_avaliacaoperguntaopcao;
        ";
        $this->execute($sql);
    }

    private function criaDicionarioDados()
    {
        $sql = "
            INSERT INTO db_sysarquivo VALUES (1010360, 'avaliacaogruporespostaexclusaoeventosefd', 'Salva os dados de exclusão de eventos do EFD', 'eso29', '2018-12-20', 'avaliacaogruporespostaexclusaoeventosefd', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (81,1010360);

            INSERT INTO db_syscampo VALUES(1010211,'eso29_sequencial','int4','Sequencial','0', 'Sequencial',15,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1010212,'eso29_avaliacaogruporesposta','int4','Sequencial (chave única) da tabela que guarda o grupo de respostas do formulário.','0', 'Código do Grupo de Resposta',15,'f','f','f',1,'text','Código do Grupo de Resposta');
            INSERT INTO db_syscampo VALUES(1010213,'eso29_cgm','int4','Sequencial (chave única) do cgm do contribuinte do EFD.','0', 'Cgm do Contribuinte',15,'f','f','f',1,'text','Cgm do Contribuinte');
            INSERT INTO db_syscampo VALUES(1010214,'eso29_protocolo','varchar(255)','Protocolo','', 'Protocolo',255,'f','f','f',0,'text','Protocolo');
            INSERT INTO db_syscampo VALUES(1010215,'eso29_periodo','varchar(10)','Período','', 'Período',10,'f','f','f',0,'text','Período');

            INSERT INTO db_sysarqcamp VALUES(1010360,1010211,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010360,1010212,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010360,1010213,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010360,1010214,4,0);
            INSERT INTO db_sysarqcamp VALUES(1010360,1010215,5,0);
            
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010360,1010211,1,1010211);
            
            INSERT INTO db_sysforkey VALUES(1010360,1010212,1,2987,0);
            INSERT INTO db_sysindices VALUES(1008405,'avaliacaogruporespostaexclusaoeventosefd_eso29_sequencial_in',1010360,'0');
            INSERT INTO db_syscadind VALUES(1008405,1010211,1);
            INSERT INTO db_syssequencia VALUES(1000802, 'avaliacaogruporespostaexclusaoeventosefd_eso29_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000802 WHERE codarq = 1010360 AND codcam = 1010211;
        ";
        $this->execute($sql);
    }

    private function deletaDicionarioDados()
    {
        $sql = "
            DELETE FROM db_syssequencia WHERE codsequencia = 1000802;
            DELETE FROM db_sysforkey WHERE codarq = 1010360;
            DELETE FROM db_sysprikey WHERE codarq = 1010360;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010360;
            DELETE FROM db_syscampo WHERE codcam IN (1010211, 1010212, 1010213, 1010214, 1010215);
            DELETE FROM db_sysarqmod WHERE codarq = 1010360;
            DELETE FROM db_sysarquivo WHERE codarq = 1010360;
            DELETE FROM db_syscadind WHERE codind = 1008405;
            DELETE FROM db_sysindices WHERE codind = 1008405;
        ";
        $this->execute($sql);
    }

    private function criaTabela()
    {
        $sql = "
            CREATE SEQUENCE avaliacaogruporespostaexclusaoeventosefd_eso29_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE avaliacaogruporespostaexclusaoeventosefd(
            eso29_sequencial		int4 NOT NULL default 0,
            eso29_avaliacaogruporesposta		int4 NOT NULL default 0,
            eso29_cgm		int4 NOT NULL default 0,
            eso29_protocolo		varchar(255) NOT NULL ,
            eso29_periodo		varchar(10) ,
            CONSTRAINT avaliacaogruporespostaexclusaoeventosefd_sequ_pk PRIMARY KEY (eso29_sequencial));
            
            ALTER TABLE avaliacaogruporespostaexclusaoeventosefd
            ADD CONSTRAINT avaliacaogruporespostaexclusaoeventosefd_avaliacaogruporesposta_fk FOREIGN KEY (eso29_avaliacaogruporesposta)
            REFERENCES avaliacaogruporesposta;
            
            CREATE  INDEX avaliacaogruporespostaexclusaoeventosefd_eso29_sequencial_in ON avaliacaogruporespostaexclusaoeventosefd(eso29_sequencial);
        ";
        $this->execute($sql);
    }

    private function deletaTabela()
    {
        $sql = "
            DROP TABLE IF EXISTS avaliacaogruporespostaexclusaoeventosefd CASCADE;
            DROP SEQUENCE IF EXISTS avaliacaogruporespostaexclusaoeventosefd_eso29_sequencial_seq;
        ";
        $this->execute($sql);
    }

    private function criaEstruturaSped()
    {
        $sql = "
          INSERT INTO esocialformulariotipo VALUES (25, 'R-9000 - Exclusão de Eventos');
          SELECT setval('esocialversaoformulario_rh211_sequencial_seq', (SELECT max(rh211_sequencial) FROM esocialversaoformulario));
          INSERT INTO efdreinfversaoformulario (efd03_versao, efd03_avaliacao, efd03_esocialformulariotipo) VALUES ('1.4', 3000038, 25);
        ";
        $this->execute($sql);
    }

    private function deletaEstruturaSped()
    {
        $sql = "
          DELETE FROM efdreinfversaoformulario WHERE efd03_avaliacao = 3000038;
          DELETE FROM esocialformulariotipo WHERE rh209_sequencial= 25;
        ";
        $this->execute($sql);
    }

    private function criaMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228086 ,'Exclusão de Eventos' ,'Exclusão de Eventos' ,'efd01_preenchimentoexclusaoeventos.php' ,'1' ,'1' ,'Exclusão de Eventos' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228079 ,228086 ,3 ,228077 );
        ";
        $this->execute($sql);
    }

    private function deletaMenu()
    {
        $sql = "
            DELETE FROM db_menu WHERE id_item_filho = 228086 AND modulo = 228077;
            DELETE FROM db_itensmenu WHERE id_item = 228086;
        ";
        $this->execute($sql);
    }
}
