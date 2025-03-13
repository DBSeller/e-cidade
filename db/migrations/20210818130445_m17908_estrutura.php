<?php

use Classes\PostgresMigration;

class M17908Estrutura extends PostgresMigration
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
        $this->execute(<<<SQL
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values (228556, 'Arquivo Tef', 'Arquivo Tef', 'Arquivo Tef', '1', '1', 'Arquivo Tef', 'true'),
       (228557, 'Importação', 'Importação', 'cai4_importar_tef.php', '1', '1', 'Importa o arquivo do tef', 'true'),
       (228558, 'Processar', 'Processar', 'cai4_processar_tef.php', '1', '1', 'Processa o arquivo do tef', 'true');

insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
values (32, 228556, 542, 39),
       (228556, 228557, 1, 39),
       (228556, 228558, 2, 39);
SQL
        );
    }

    private function downMenu()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho in (228556, 228557, 228558);
delete from db_itensmenu where id_item in (228556, 228557, 228558);
SQL
        );
    }

    private function upEstrutura()
    {
        $this->execute(<<<SQL
create table caixa.linha_tef (
    id serial primary key,
    numero_autorizacao text,
    numero_cv int8,
    cartao varchar(30),
    data_venda date,
    data_vencimento date,
    parcela int,
    total_parcelas int,
    valor_original numeric(15,2),
    valor_bruto numeric(15,2),
    valor_descontos numeric(15,2),
    valor_liquido numeric(15,2),
    consistente boolean default true
);

select configuracoes.fc_auditoria_cria_funcao('caixa.linha_tef');

CREATE UNIQUE INDEX in_linha_unica
ON caixa.linha_tef (
    numero_autorizacao,
    numero_cv,
    cartao,
    data_venda,
    data_vencimento,
    parcela,
    total_parcelas,
    valor_original,
    valor_bruto,
    valor_descontos,
    valor_liquido
);
SQL
        );

        $this->execute(<<<SQL
create table caixa.linha_tef_processado (
  id serial primary key,
  linha_tef_id integer not null,
  conlancam_id integer not null,
  foreign key (linha_tef_id) references caixa.linha_tef on delete cascade,
  foreign key (conlancam_id) references contabilidade.conlancam on delete cascade
);

SQL
        );
    }

    private function downEstrutura()
    {
        $this->execute(<<<SQL
drop table caixa.linha_tef_processado;
drop table caixa.linha_tef;
SQL
        );
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
insert into db_sysarquivo
values (1010821, 'linha_tef', 'Guarda os arquivos importados do tef', 't69', '2021-08-18', 'Arquivo do tef', 0, 'f', 'f', 'f', 'f' ),
       (1010822, 'linha_tef_processado', 'Armazena uma linha do arquivo tef que já teve os lançamentos efetuadps', 't70', '2021-08-19', 'Linha Processada TEF', 0, 'f', 'f', 'f', 'f' );

insert into db_sysarqmod
values (5, 1010821),
       (5,1010822);

insert into db_syscampo
values (1013387,'numero_autorizacao','text','Número da Autorização','0', 'Número da Autorização',10,'f','f','f',1,'text','Número da Autorização'),
       (1013388,'numero_cv','int8','Número da CV','0', 'Número da CV',10,'f','f','f',3,'text','Número da CV'),
       (1013389,'cartao','varchar(30)','Nº do Cartão de Crédito','', 'Nº do Cartão de Crédito',30,'f','f','f',0,'text','Nº do Cartão de Crédito'),
       (1013390,'data_venda','date','Data da venda','null', 'Data Venda',10,'f','f','f',1,'text','Data Venda'),
       (1013392,'total_parcelas','int4','Total de Parcelas','0', 'Total de Parcelas',10,'f','f','f',1,'text','Total de Parcelas'),
       (1013393,'valor_original','float8','Valor Original','0', 'Valor Original',15,'f','f','f',4,'text','Valor Original'),
       (1013394,'valor_bruto','float8','Valor Bruto','0', 'Valor Bruto',10,'f','f','f',4,'text','Valor Bruto'),
       (1013395,'valor_descontos','float8','Descontos','0', 'Descontos',15,'f','f','f',4,'text','Descontos'),
       (1013396,'valor_liquido','float8','Valor Líquido','0', 'Valor Líquido',18,'f','f','f',4,'text','Valor Líquido'),
       (1013399,'linha_tef_id','int4','Representa a linha do arquivo TEF','0', 'Linha TEF',10,'f','f','f',1,'text','Linha TEF'),
       (1013400,'conlancam_id','int4','Vínculo com o lançamento contábil','0', 'Lançamento',10,'f','f','f',1,'text','Lançamento'),
       (1013401,'consistente','bool','Registro consiste com dado do sistema','f', 'Consistente',1,'f','f','f',5,'text','Consistente');

insert into db_sysarqcamp
values (1010821, 1011345, 1, 0),
       (1010821, 1013387, 2, 0),
       (1010821, 1013388, 3, 0),
       (1010821, 1013389, 4, 0),
       (1010821, 1013390, 5, 0),
       (1010821, 16006, 6, 0),
       (1010821, 16005, 7, 0),
       (1010821, 1013392, 8, 0),
       (1010821, 1013393, 9, 0),
       (1010821, 1013394, 10 ,0),
       (1010821, 1013395, 11 ,0),
       (1010821, 1013396, 12 ,0),
       (1010821, 1013401, 13, 0),
       (1010822, 1011345, 1, 0),
       (1010822, 1013399, 2, 0),
       (1010822, 1013400, 3, 0);

insert into db_sysprikey (codarq,codcam,sequen,camiden)
values (1010821,1011345,1,1011345),
       (1010822,1011345,1,1011345);

insert into db_sysforkey
values (1010822,1013399,1,1010821,0),
       (1010822,1013400,1,760,0);
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
delete from db_sysforkey where codarq in (1010821, 1010822);
delete from db_sysprikey where codarq in (1010821, 1010822);
delete from db_sysarqcamp where codarq in (1010821, 1010822);
delete from db_syscampo where codcam in (1013387, 1013388, 1013389, 1013390, 1013392, 1013393, 1013394, 1013395, 1013396, 1013399, 1013400, 1013401);
delete from db_sysarqmod where codarq in (1010821, 1010822);
delete from db_sysarquivo where codarq in (1010821, 1010822);
SQL
        );
    }
}
