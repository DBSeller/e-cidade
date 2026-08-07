<?php

use Classes\PostgresMigration;

class M14073ParametrosIssqnProcessoEletronico extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDdl();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDdl();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228165 ,'Processo Eletrônico' ,'Cadastro de Parâmetros do processo eletrônico' ,'iss1_parissqnprocessoeletronico.php' ,'1' ,'1' ,'Menu para cadastro de parametros do processo eletronico dentro do modulo issqn' ,'true' );
            delete from db_menu where id_item_filho = 228165 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 608574 ,228165 ,4 ,40 );

            insert into db_sysarquivo values (1010473, 'parametroprocessoeletronico', 'Tabela que guarda os parametros do processo eletronico', 'q150', '2019-10-09', 'Parametros do processo eletronico', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010473);
            insert into db_syscampo values(1010758,'q150_tipoalvaraprovisorio','int8','Código do tipo de alvará provisório','', 'Código Alvará Provisório',1,'f','t','f',0,'text','Código Alvará Provisório');
            insert into db_syscampo values(1010759,'q150_alvaraautonomo','int8','Código do processo de alvará autonomo','', 'Código Processo Alvará Autonomo',1,'f','t','f',0,'text','Código Processo Alvará Autonomo');
            insert into db_syscampo values(1010760,'q150_alvaraempresa','int8','Código do processo de alvara empresa','', 'Código Processo Alvará Empresa',1,'f','t','f',0,'text','Código Processo Alvará Empresa');
            insert into db_syscampo values(1010761,'q150_alvaramei','int8','Código do processo de alvara mei','', 'Código Processo Alvará Mei',1,'f','t','f',0,'text','Código Processo Alvará Mei');
            insert into db_syscampo values(1010762,'q150_sequencial','int4','Sequencial da tabela','0', 'Sequencial da tabela',1,'t','f','t',1,'text','Sequencial da tabela');
            delete from db_sysarqcamp where codarq = 1010473;
            insert into db_sysarqcamp values(1010473,1010762,1,0);
            insert into db_sysarqcamp values(1010473,1010758,2,0);
            insert into db_sysarqcamp values(1010473,1010759,3,0);
            insert into db_sysarqcamp values(1010473,1010760,4,0);
            insert into db_sysarqcamp values(1010473,1010761,5,0);

SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228165 AND modulo = 40;
            delete from db_itensmenu where id_item = 228165;

            delete from db_sysarqcamp where codarq = 1010473;
            delete from db_syscampo where codcam in(1010758, 1010759, 1010760, 1010761, 1010762);
            delete from db_sysarqmod where codarq = 1010473;
            delete from db_sysarquivo where codarq = 1010473;
SQL
        );
    }

    public function upDdl()
    {
        $this->execute(<<<SQL
            CREATE SEQUENCE issqn.parametroprocessoeletronico_q150_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE issqn.parametroprocessoeletronico (
                q150_sequencial integer not null,
                q150_tipoalvaraprovisorio integer not null,
                q150_alvaraautonomo integer not null,
                q150_alvaraempresa integer not null,
                q150_alvaramei integer not null,
                CONSTRAINT parametroprocessoeletronico_sequ_pk PRIMARY KEY (q150_sequencial)
            );
SQL
        );
    }

    public function downDdl()
    {
        $this->execute(<<<SQL
            drop table issqn.parametroprocessoeletronico;
            drop sequence issqn.parametroprocessoeletronico_q150_sequencial_seq;
SQL
        );
    }

}
