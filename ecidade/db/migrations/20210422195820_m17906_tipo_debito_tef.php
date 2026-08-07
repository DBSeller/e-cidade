<?php

use Classes\PostgresMigration;

class M17906TipoDebitoTef extends PostgresMigration
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
            create table caixa.operacoestef
            (
                k195_sequencial serial not null,
                k195_descricao text not null,
                k195_codigoperacao integer not null,
                CONSTRAINT operacoestef_pk PRIMARY KEY(k195_sequencial)
            );

            create table caixa.configuracoesteftipodebito
            (
                k196_sequencial serial not null,
                k196_tipo integer not null,
                k196_aceitatef boolean not null default false,
                k196_maximoparcelas integer,
                k196_valorminimoparcela numeric,
                CONSTRAINT configuracoesteftipodebito_pk PRIMARY KEY(k196_sequencial),
                CONSTRAINT arretipo_fk FOREIGN KEY(k196_tipo) REFERENCES arretipo(k00_tipo)
            );

            create table caixa.operacoesteftipodebito
            (
                k197_sequencial serial not null,
                k197_configuracoesteftipodebito integer not null,
                k197_operacoestef integer not null,
                CONSTRAINT operacoesteftipodebito_pk PRIMARY KEY(k197_sequencial),
                CONSTRAINT operacoestef_fk FOREIGN KEY(k197_operacoestef) REFERENCES operacoestef(k195_sequencial),
                CONSTRAINT configuracoesteftipodebito_fk FOREIGN KEY(k197_configuracoesteftipodebito) REFERENCES configuracoesteftipodebito(k196_sequencial)
            );
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            drop table caixa.operacoesteftipodebito;
            drop table caixa.configuracoesteftipodebito;
            drop table caixa.operacoestef cascade;
SQL
        );
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010786, 'operacoestef', 'Guarda as operações que estão disponíveis para ser utilizada por pagamento TEF', 'k195', '2021-04-22', 'Operações TEF', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (5,1010786);
            insert into db_syscampo values(1013170,'k195_sequencial','int8','Sequencial da tabela operacoestef','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1013171,'k195_descricao','text','Descrição da operação TEF','', 'Descrição',255,'f','t','f',0,'text','Descrição');
            insert into db_syscampo values(1013172,'k195_codigoperacao','int8','Código da operação que deve ser enviado na integração','0', 'Código da Operação',11,'f','f','f',1,'text','Código da Operação');
            insert into db_syssequencia values(1000996, 'operacoestef_k195_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_sysarqcamp values(1010786,1013170,1,1000996);
            insert into db_sysarqcamp values(1010786,1013171,2,0);
            insert into db_sysarqcamp values(1010786,1013172,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010786,1013170,1,1013170);

            insert into db_sysarquivo values (1010787, 'configuracoesteftipodebito', 'Salvar as configurações para TEF pot tipo de débito', 'k196', '2021-04-22', 'Configurações TEF Tipo Débito', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (5,1010787);
            insert into db_syscampo values(1013173,'k196_sequencial','int8','Sequencial da tabela configuracoesteftipodebito.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1013174,'k196_tipo','int8','Guarda o tipo do débito','0', 'Tipo Débito',11,'f','f','f',1,'text','Tipo Débito');
            insert into db_syscampo values(1013175,'k196_aceitatef','text','Informa se o tipo de débito aceita TEF ou não','', 'Aceita TEF',1,'t','f','f',0,'text','Aceita TEF');
            insert into db_syscampo values(1013176,'k196_maximoparcelas','int8','Campo com o número máximo de parcelas o débito pode ser parcelado.','0', 'Máximo de Parcelas',11,'f','f','f',1,'text','Máximo de Parcelas');
            insert into db_syscampo values(1013177,'k196_valorminimoparcela','float8','Campo com o valor mínimo que cada parcela pode ter.','0', 'Valor Mínimo da Parcela',11,'t','f','f',4,'text','Valor Mínimo da Parcela');
            insert into db_syssequencia values(1000997, 'configuracoesteftipodebito_k196_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_sysarqcamp values(1010787,1013173,1,1000997);
            insert into db_sysarqcamp values(1010787,1013174,2,0);
            insert into db_sysarqcamp values(1010787,1013175,3,0);
            insert into db_sysarqcamp values(1010787,1013176,4,0);
            insert into db_sysarqcamp values(1010787,1013177,5,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010787,1013173,1,1013173);
            insert into db_sysforkey values(1010787,1013174,1,82,0);

            insert into db_sysarquivo values (1010788, 'operacoesteftipodebito', 'Salva quais operações que o débito pode ser pago por TEF.', 'k197', '2021-04-22', 'Operações TEF por Tipo de Débito', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (5,1010788);
            insert into db_syscampo values(1013178,'k197_sequencial','int8','Sequencial da tabela operacoesteftipodebito.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1013179,'k197_configuracoesteftipodebito','int8','Vinculo com a tabela configuracoesteftipodebito','0', 'Configurações TEF por Tipo de Débito',11,'f','f','f',1,'text','Configurações TEF por Tipo de Débito');
            insert into db_syscampo values(1013180,'k197_operacoestef','int8','Vinculo com a tabela operacoestef','0', 'Operações TEF',11,'f','f','f',1,'text','Operações TEF');
            insert into db_syssequencia values(1000998, 'operacoesteftipodebito_k197_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into db_sysarqcamp values(1010788,1013178,1,1000998);
            insert into db_sysarqcamp values(1010788,1013179,2,0);
            insert into db_sysarqcamp values(1010788,1013180,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010788,1013178,1,1013178);
            insert into db_sysforkey values(1010788,1013180,1,1010786,0);
            insert into db_sysforkey values(1010788,1013179,1,1010787,0);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysprikey where codarq in (
                1010787,
                1010788,
                1010786
            );

            delete from db_sysforkey where codarq in (
                1010787,
                1010788,
                1010786
            );

            delete from db_sysarqcamp where codarq in (
                1010787,
                1010788,
                1010786
            );

            delete from db_syssequencia where codsequencia in (
                1000997,
                1000996,
                1000998
            );

            delete from db_syscampo where codcam in (
                1013173,
                1013174,
                1013175,
                1013176,
                1013177,

                1013178,
                1013179,
                1013180,

                1013170,
                1013171,
                1013172
            );

            delete from db_sysarqmod where codarq in (
                1010787,
                1010788,
                1010786
            );

            delete from db_sysarquivo where codarq in (
                1010787,
                1010788,
                1010786
            );
SQL
        );
    }
}
