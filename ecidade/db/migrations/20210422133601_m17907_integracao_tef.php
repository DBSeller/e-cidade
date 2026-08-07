<?php

use Classes\PostgresMigration;

class M17907IntegracaoTef extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            INSERT INTO caixa.cadtipomod VALUES (31, 'RECIBO TEF');

            create table caixa.operacoesrealizadastef
            (
                k198_sequencial serial not null,
                k198_numnov integer not null,
                k198_nsu integer,
                k198_valor numeric not null,
                k198_operacaotef integer not null,
                k198_bandeira text,
                k198_parcela integer,
                k198_dataoperacao timestamp not null,
                k198_confirmado boolean default 'f',
                k198_mensagemretorno text,
                k198_desfeito boolean default 'f',
                k198_codigoaprovacao integer,
                k198_nsuautorizadora integer,
                k198_concluidobaixabanco boolean default 'f',
                k198_cartao text,
                k198_retorno text not null,
                k198_grupo integer not null,
                CONSTRAINT operacoesrealizadastef_pk PRIMARY KEY(k198_sequencial)
            );

            insert into cadmodcarne values (
                107,
                'GUIA TEF',
                '',
                0,
                0,
                '',
                2
            );
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            DELETE FROM caixa.cadtipomod WHERE k46_sequencial = 31;
            drop table operacoesrealizadastef;
            delete from cadmodcarne where k47_sequencial = 107;
SQL
        );
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010796, 'operacoesrealizadastef', 'Guarda todas as operações realizadas por TEF.', 'k198', '2021-05-03', 'Operações Realizadas TEF', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (5,1010796);

            insert into db_syscampo values(1013220,'k198_sequencial','int8','Sequencial da tabela operacoesrealizadastef.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1013221,'k198_numnov','int8','Número do recibo','0', 'Recibo',11,'f','f','f',1,'text','Recibo');
            insert into db_syscampo values(1013222,'k198_nsu','int8','Número do NSU retornado da Getnet','0', 'NSU',11,'t','f','f',1,'text','NSU');
            insert into db_syscampo values(1013228,'k198_valor','float8','Valor da operação','0', 'Valor',11,'f','f','f',4,'text','Valor');
            insert into db_syscampo values(1013223,'k198_operacaotef','int8','Operação que foi executada por TEF','0', 'Operação',11,'f','f','f',1,'text','Operação');
            insert into db_syscampo values(1013224,'k198_bandeira','text','Bandeira retornada pelo CTFClient','', 'Bandeira',255,'t','f','f',0,'text','Bandeira');
            insert into db_syscampo values(1013225,'k198_parcela','int8','Número da parcela caso a operação por TEF tenha sido de parcelamento.','0', 'Parcela',11,'t','f','f',1,'text','Parcela');
            insert into db_syscampo values(1013226,'k198_dataoperacao','date','Data da operação','null', 'Data Operação',10,'f','f','f',1,'text','Data Operação');
            insert into db_syscampo values(1013227,'k198_confirmado','text','Salva se a operação foi confirmada ou não','', 'Confirmado',1,'t','f','f',0,'text','Confirmado');
            insert into db_syscampo values(1013229,'k198_mensagemretorno','text','Guarda a mensagem retornada pelo CTFClient.','', 'Mensagem Retorno',255,'t','f','f',0,'text','Mensagem Retorno');
            insert into db_syscampo values(1013230,'k198_desfeito','text','Salva se a operação foi desfeita ou não.','', 'Desfeito',1,'t','f','f',0,'text','Desfeito');
            insert into db_syscampo values(1013231,'k198_codigoaprovacao','int8','Código da aprovação retornado pelo CTFClient','0', 'Código Aprovação',11,'t','f','f',1,'text','Código Aprovação');
            insert into db_syscampo values(1013232,'k198_nsuautorizadora','int8','NSU da Autorizadora retornado pelo CTFClient','0', 'NSU Autorizadora',11,'t','f','f',1,'text','NSU Autorizadora');
            insert into db_syscampo values(1013233,'k198_concluidobaixabanco','text','Campo que guarda se foi concluído a baixa de banco dos débitos do recibo. ','', 'Concluido Baixa de Banco',1,'t','f','f',0,'text','Concluido Baixa de Banco');
            insert into db_syscampo values(1013234,'k198_retorno','text','Campo que guarda todas as informações retornadas pelo CTFClient','', 'Retorno',1000,'f','f','f',0,'text','Retorno');
            insert into db_syscampo values(1013236,'k198_cartao','text','Campo que salva o número parcial do cartão utilizado na transação.','', 'Cartão',255,'t','f','f',0,'text','Cartão');
            insert into db_syscampo values(1013289,'k198_grupo','int4','Informa o grupo de transações que aquela transação pertence.','0', 'Grupo',11,'f','f','f',1,'text','Grupo');

            insert into db_syssequencia values(1001002, 'operacoesrealizadastef_k198_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysarqcamp values(1010796,1013220,1,1001002);
            insert into db_sysarqcamp values(1010796,1013221,2,0);
            insert into db_sysarqcamp values(1010796,1013222,3,0);
            insert into db_sysarqcamp values(1010796,1013228,4,0);
            insert into db_sysarqcamp values(1010796,1013223,5,0);
            insert into db_sysarqcamp values(1010796,1013224,6,0);
            insert into db_sysarqcamp values(1010796,1013225,7,0);
            insert into db_sysarqcamp values(1010796,1013226,8,0);
            insert into db_sysarqcamp values(1010796,1013227,9,0);
            insert into db_sysarqcamp values(1010796,1013229,10,0);
            insert into db_sysarqcamp values(1010796,1013230,11,0);
            insert into db_sysarqcamp values(1010796,1013231,12,0);
            insert into db_sysarqcamp values(1010796,1013232,13,0);
            insert into db_sysarqcamp values(1010796,1013233,14,0);
            insert into db_sysarqcamp values(1010796,1013234,15,0);
            insert into db_sysarqcamp values(1010796,1013236,16,0);
            insert into db_sysarqcamp values(1010796,1013289,17,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010796,1013220,1,1013220);
            insert into db_sysforkey values(1010796,1013223,1,1010786,0);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysforkey where codarq = 1010796;
            delete from db_sysprikey where codarq = 1010796;
            delete from db_sysarqcamp where codarq = 1010796;
            delete from db_syssequencia where codsequencia = 1001002;

            delete from db_syscampo where codcam in (
                1013220,
                1013221,
                1013222,
                1013228,
                1013223,
                1013224,
                1013225,
                1013226,
                1013227,
                1013229,
                1013230,
                1013231,
                1013232,
                1013233,
                1013234,
                1013289
            );

            delete from db_sysarqmod where codarq = 1010796;
            delete from db_sysarquivo where codarq = 1010796;
SQL
        );
    }
}
