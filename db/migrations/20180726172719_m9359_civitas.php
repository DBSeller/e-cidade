<?php

use Classes\PostgresMigration;

class M9359Civitas extends PostgresMigration
{
    public function up()
    {
        $this->upMenu();
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downMenu();
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function upMenu()
    {
        $sql = <<<SQL
          insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10555 ,'Notificação' ,'Notificação de recadastramento de iptu' ,'' ,'1' ,'1' ,'Notificação de recadastramento de iptu' ,'true' );
          insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10437 ,10555 ,3 ,578 );

          insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10556 ,'Lançamento' ,'Lançamento de Notificações de recadastramento de iptu' ,'cad4_iptunotificacaolancamento.php' ,'1' ,'1' ,'Lançamento de Notificações de recadastramento de iptu' ,'true' );
          insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10555 ,10556 ,1 ,578 );

          insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10557 ,'Manutenção de Notificações' ,'Manutenção de Notificações de recadastramento de iptu' ,'cad4_manutencaonotificacaolancamento.php' ,'1' ,'1' ,'Manutenção de Notificações de recadastramento de iptu' ,'true' );
          insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10555 ,10557 ,2 ,578 );
SQL;

        $this->execute($sql);
    }

    private function downMenu()
    {
        $sql = <<<SQL
          delete from db_menu where id_item_filho in (10556 ,10555 ,10557 ) AND modulo = 578;
          delete from db_itensmenu where id_item in (10556 ,10555 ,10557 );
SQL;

        $this->execute($sql);
    }


    private function upDicionario()
    {
        $sql = <<<SQL
          -- Tabela iptunotificacao
          insert into db_sysarquivo values (1010296, 'iptunotificacao', 'Notificação de Recadastramento de Iptu', 'j147', '2018-07-26', 'Notificação de Recadastramento de Iptu', 0, 'f', 'f', 'f', 'f' );
          insert into db_sysarqmod values (2,1010296);
          
          -- Campos da tabela iptunotificao
          insert into db_syscampo values(1009838,'j147_sequencial','int4','Sequencial do campo','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
          insert into db_syscampo values(1009839,'j147_matricula','int4','Matricula a ser notificada','0', 'Matricula',10,'f','f','f',1,'text','Matricula');
          insert into db_syscampo values(1009840,'j147_processo','int4','Código do Processo','0', 'Processo',10,'f','f','f',1,'text','Processo');
          insert into db_syscampo values(1009841,'j147_multa','int4','Taxa da multa','0', 'Taxa da Multa',10,'f','f','f',1,'text','Taxa da Multa');
          insert into db_syscampo values(1009842,'j147_nome','varchar(40)','Nome do Destinatário da notificação','', 'Nome do Destinatário',40,'f','t','f',0,'text','Nome do Destinatário');
          insert into db_syscampo values(1009843,'j147_endereco','varchar(255)','Endereço do destinatário da notificação de recadastramento de iptu','', 'Endereço do destinatário',255,'f','t','f',0,'text','Endereço do destinatário');
          insert into db_syscampo values(1009844,'j147_numero','int4','Numero do logradouro','0', 'Numero do logradouro',10,'f','f','f',1,'text','Numero do logradouro');
          insert into db_syscampo values(1009845,'j147_complemento','varchar(40)','Complemento do endereço do destinatário ','', 'Complemento',40,'f','t','f',0,'text','Complemento');
          insert into db_syscampo values(1009846,'j147_cep','varchar(10)','Cep do destinatário da notificação','', 'Cep do destinatário',10,'f','t','f',0,'text','Cep do destinatário');
          insert into db_syscampo values(1009847,'j147_bairro','varchar(100)','Bairro do destinatário da notificação','', 'Bairro',100,'f','t','f',0,'text','Bairro');
          insert into db_syscampo values(1009848,'j147_municipio','varchar(255)','Município do destinatário da notificação.','', 'Municipio',255,'f','t','f',0,'text','Municipio');
          
          -- Sequencia iptunotificacao
          insert into db_syssequencia values(1000746, 'iptunotificacao_j147_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
          
          insert into db_sysarqcamp values(1010296,1009838,1,1000746);
          insert into db_sysarqcamp values(1010296,1009839,2,0);
          insert into db_sysarqcamp values(1010296,1009840,3,0);
          insert into db_sysarqcamp values(1010296,1009841,4,0);
          insert into db_sysarqcamp values(1010296,1009842,5,0);
          insert into db_sysarqcamp values(1010296,1009843,6,0);
          insert into db_sysarqcamp values(1010296,1009844,7,0);
          insert into db_sysarqcamp values(1010296,1009845,8,0);
          insert into db_sysarqcamp values(1010296,1009846,9,0);
          insert into db_sysarqcamp values(1010296,1009847,10,0);
          insert into db_sysarqcamp values(1010296,1009848,11,0);
          
          -- Chaves estrangeiras iptunotificao
          insert into db_sysforkey values(1010296,1009839,1,27,0);
          insert into db_sysforkey values(1010296,1009840,1,403,0);
          insert into db_sysforkey values(1010296,1009841,1,79,0);
          
          -- Chave primaria iptunotificao
          insert into db_sysprikey values (1010296, 1009838, 1, 0, 1009838);
          
          -- Indices iptunotificao
          insert into db_sysindices values(1008300,'iptunotificacao_j147_sequencial_in',1010296,'1');
          insert into db_syscadind values(1008300,1009838,1);
          
          insert into db_sysindices values(1008301,'iptunotificacao_j147_matricula_in',1010296,'0');
          insert into db_syscadind values(1008301,1009839,1);
          
          insert into db_sysindices values(1008302,'iptunotificacao_j147_processo_in',1010296,'0');
          insert into db_syscadind values(1008302,1009840,1);
          
          insert into db_sysindices values(1008303,'iptunotificacao_j147_matricula_processo_in',1010296,'0');
          insert into db_syscadind values(1008303,1009839,1);
          insert into db_syscadind values(1008303,1009840,2);
          
          -- Tabela iptunotificaostatus
          insert into db_sysarquivo values (1010297, 'iptunotificacaostatus', 'Status da Notificação de Recadastramento', 'j148', '2018-07-26', 'Status da Notificação', 0, 'f', 'f', 'f', 'f' );
          insert into db_sysarqmod values (2,1010297);
          
          -- Campos da tabela iptunotificaostatus
          insert into db_syscampo values(1009849,'j148_sequencial','int4','Sequencial','0', 'Seuquencial',10,'f','f','f',1,'text','Seuquencial');
          insert into db_syscampo values(1009850,'j148_descricao','varchar(45)','Descrição da notificação de recadastramento','', 'Descrição',45,'f','t','f',0,'text','Descrição');
          
          -- Sequencia iptunotificaostatus
          insert into db_syssequencia values(1000747, 'iptunotificacaostatus_j148_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
          
          insert into db_sysarqcamp values(1010297,1009849,1,1000747);
          insert into db_sysarqcamp values(1010297,1009850,2,0);
          
          -- Chave Primaria iptunotificaostatus
          insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010297,1009849,1,1009849);
          
          -- Indices iptunotificaostatus
          insert into db_sysindices values(1008304,'iptunotificacaostatus_j148_sequencial_in',1010297,'1');
          insert into db_syscadind values(1008304,1009849,1);
          
          -- Tabela iptunotificacaomov
          insert into db_sysarquivo values (1010298, 'iptunotificacaomov', 'Movimentações das Notificações de Recadastramento de Iptu', 'j149', '2018-07-26', 'Movimentação da Notificação', 0, 'f', 'f', 'f', 'f' );
          insert into db_sysarqmod values (2,1010298);

          -- Campos da tabela iptunotificacaomov
          insert into db_syscampo values(1009851,'j149_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
          insert into db_syscampo values(1009852,'j149_iptunotificacao','int4','Notificação da movimentação','0', 'Notificação',10,'f','f','f',1,'text','Notificação');
          insert into db_syscampo values(1009853,'j149_iptunotificacaostatus','int4','Status da movimentação','0', 'Status',10,'f','f','f',1,'text','Status');
          insert into db_syscampo values(1009854,'j149_data','date','Data da movimentação','null', 'Data da movimentação',10,'f','f','f',1,'text','Data da movimentação');
          insert into db_syscampo values(1009855,'j149_observacao','varchar(255)','Observação da movimentação','', 'Observação',255,'t','t','f',0,'text','Observação');
          
          -- Sequencia  iptunotificacaomov         
          insert into db_syssequencia values(1000748, 'iptunotificacaomov_j149_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
          insert into db_sysarqcamp values(1010298,1009851,1,1000748);
          insert into db_sysarqcamp values(1010298,1009852,2,0);
          insert into db_sysarqcamp values(1010298,1009853,3,0);
          insert into db_sysarqcamp values(1010298,1009854,4,0);
          insert into db_sysarqcamp values(1010298,1009855,5,0);
          
          -- Chave primaria
          insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010298,1009851,1,1009851);
          
          -- Chave estrangeira iptunotificacaomov
          insert into db_sysforkey values(1010298,1009852,1,1010296,0);
          insert into db_sysforkey values(1010298,1009853,1,1010297,0);
          
          -- Indices iptunotificacaomov
          insert into db_sysindices values(1008306,'iptunotificacaomov_j149_iptunotificacao_in',1010298,'0');
          insert into db_syscadind values(1008306,1009852,1);
          
          insert into db_sysindices values(1008307,'iptunotificacaomov_j149_iptunotificacao_iptunotificacaostatus_in',1010298,'1');
          insert into db_syscadind values(1008307,1009852,1);
          insert into db_syscadind values(1008307,1009853,2);
          
          insert into db_sysindices values(1008305,'iptunotificacaomov_j149_sequencial_in',1010298,'1');
          insert into db_syscadind values(1008305,1009851,1);
SQL;
        $this->execute($sql);

    }

    
    private function downDicionario()
    {
         $sql = <<<SQL
           delete from db_syscadind where codcam in (1009838, 1009839, 1009840,1009849,1009852,1009853,1009851);
           
           delete from db_sysindices where codarq in (1010296, 1010297, 1010298);
           
           delete from db_sysprikey where codarq  in (1010296, 1010297, 1010298);
           delete from db_sysforkey where codarq  in (1010296, 1010297, 1010298);
           delete from db_sysarqcamp where codarq in (1010296, 1010297, 1010298);
           
           delete from db_syscampo where codcam in (1009838,1009839,1009840,1009841,1009842,1009843,1009844,1009845,1009846,1009847,1009848,1009851,1009852,1009853,1009854,1009855,1009849,1009850);
           
           delete from db_syssequencia where codsequencia in (1000746,1000747,1000748);
           
           delete from db_sysarqmod where codmod = 2 and codarq in (1010296, 1010297, 1010298);
           delete from db_sysarquivo where codarq in (1010296, 1010297, 1010298);
SQL;
         $this->execute($sql);

    }

    private function upEstrutura()
    {
        $sql = <<<SQL
          -- Criando  sequences
          create sequence if not exists cadastro.iptunotificacao_j147_sequencial_seq
          INCREMENT 1
          MINVALUE 1
          MAXVALUE 9223372036854775807
          START 1
          CACHE 1;
          
          
          create sequence if not exists cadastro.iptunotificacaomov_j149_sequencial_seq
          INCREMENT 1
          MINVALUE 1
          MAXVALUE 9223372036854775807
          START 1
          CACHE 1;
          
          
          create sequence if not exists cadastro.iptunotificacaostatus_j148_sequencial_seq
          INCREMENT 1
          MINVALUE 1
          MAXVALUE 9223372036854775807
          START 1
          CACHE 1;

          -- Criando tabelas
          create table if not exists cadastro.iptunotificacao(
              j147_sequencial	int4 not null,
              j147_matricula    int4 not null,
              j147_processo		int4 not null,
              j147_multa	    int4 default null,
              j147_nome		    varchar(40) not null ,
              j147_endereco		varchar(255) not null ,
              j147_numero		int4 not null,
              j147_complemento	varchar(40) default null,
              j147_cep		    varchar(10)  not null ,
              j147_bairro		varchar(100) not null ,
              j147_municipio	varchar(255) not null,
              constraint iptunotificacao_sequ_pk PRIMARY KEY (j147_sequencial)
          );
          
          create table if not exists cadastro.iptunotificacaomov(
              j149_sequencial		     int4 not null,
              j149_iptunotificacao	     int4 not null,
              j149_iptunotificacaostatus int4 not null,
              j149_data		             date not null,
              j149_observacao		     varchar(255) default null,
              constraint iptunotificacaomov_sequ_pk primary key (j149_sequencial)
          );
          
          
          create table if not exists cadastro.iptunotificacaostatus(
              j148_sequencial		int4 not null,
              j148_descricao		varchar(45) ,
              constraint iptunotificacaostatus_sequ_pk primary key (j148_sequencial)
          );
          
          -- CHAVE ESTRANGEIRA
          alter table cadastro.iptunotificacao
          add constraint iptunotificacao_processo_fk foreign key (j147_processo)
          references protprocesso;
          
          alter table cadastro.iptunotificacao
          add constraint iptunotificacao_matricula_fk foreign key (j147_matricula)
          references iptubase;
          
          alter table cadastro.iptunotificacao
          add constraint iptunotificacao_multa_fk foreign key (j147_multa)
          references tabdesc;
          
          alter table cadastro.iptunotificacaomov
          add constraint iptunotificacaomov_iptunotificacaostatus_fk foreign key (j149_iptunotificacaostatus)
          references iptunotificacaostatus;
          
          alter table cadastro.iptunotificacaomov
          add constraint iptunotificacaomov_iptunotificacao_fk foreign key (j149_iptunotificacao)
          references iptunotificacao;
          
          
          -- Indices
          create unique index iptunotificacao_j147_sequencial_in on iptunotificacao(j147_sequencial);

          create index iptunotificacao_j147_matricula_in on iptunotificacao(j147_matricula);
          
          create index iptunotificacao_j147_processo_in on iptunotificacao(j147_processo);
          
          create index iptunotificacao_j147_matricula_processo_in on iptunotificacao(j147_matricula,j147_processo);
          
          create index iptunotificacaomov_j149_iptunotificacao_in on iptunotificacaomov(j149_iptunotificacao);
          
          create unique index iptunotificacaomov_j149_iptunotificacao_iptunotificacaostatus_in on iptunotificacaomov(j149_iptunotificacao,j149_iptunotificacaostatus);
          
          create unique index iptunotificacaomov_j149_sequencial_in on iptunotificacaomov(j149_sequencial);
          
          create unique index iptunotificacaostatus_j148_sequencial_in on iptunotificacaostatus(j148_sequencial);
SQL;

        $this->execute($sql);
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
          --DROP TABLE:
          DROP TABLE IF EXISTS cadastro.iptunotificacaomov;
          DROP TABLE IF EXISTS cadastro.iptunotificacao;
          DROP TABLE IF EXISTS cadastro.iptunotificacaostatus;
          
          --Criando drop sequences
          DROP SEQUENCE IF EXISTS cadastro.iptunotificacao_j147_sequencial_seq;
          DROP SEQUENCE IF EXISTS cadastro.iptunotificacaomov_j149_sequencial_seq;
          DROP SEQUENCE IF EXISTS cadastro.iptunotificacaostatus_j148_sequencial_seq;
SQL;
        $this->execute($sql);

    }
}
