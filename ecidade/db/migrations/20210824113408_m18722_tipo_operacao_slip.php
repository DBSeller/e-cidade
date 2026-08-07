<?php

use Classes\PostgresMigration;

class M18722TipoOperacaoSlip extends PostgresMigration
{


    public function up()
    {

        $sql = <<<SQL

        insert into sliptipooperacao (k152_sequencial, k152_descricao) values
         (17, 'TRANSF BANCARIA PARA COBERTURA FINANCEIRA - Inclusão'),
         (18, 'TRANSF BANCARIA PARA COBERTURA FINANCEIRA - Estorno');


insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (142,'TRANSF BANCARIA PARA COBERTURA FINANCEIRA',140);
insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (143,'ESTORNO DE TRANSF BANCARIA PARA COBERTURA FINANCEIRA',141);
insert into vinculoeventoscontabeis (c115_sequencial,c115_conhistdocinclusao,c115_conhistdocestorno) values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'),142,143);


insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228561 ,'Transferência Bancária - Cobertura Financeira' ,'Transferência Bancária - Cobertura Financeira' ,'' ,'1' ,'1' ,'Transferência Bancária - Cobertura Financeira' ,'true' );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228562 ,'Inclusão' ,'Inclusão Cobertura Financeira' ,'cai4_transferenciabancariacoberturafinanceira001.php' ,'1' ,'1' ,'Inclusão Transferência Bancária - Cobertura Financeira' ,'true' );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228563 ,'Estorno' ,'Estorno Cobertura Financeira' ,'cai4_transferenciabancariacoberturafinanceira002.php' ,'1' ,'1' ,'Estorno Transferência Bancária - Cobertura Financeira' ,'true' );

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9372 ,228561 ,7 ,39 );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228561 ,228562 ,1 ,39 );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228561 ,228563 ,2 ,39 );

update db_itensmenu set descricao = 'Transferência Bancária - Decêndio' where id_item = 9382;


insert into conhistdocregra( c92_sequencial ,c92_conhistdoc ,c92_descricao ,c92_regra ,c92_anousu ) values ( nextval('conhistdocregra_c92_sequencial_seq') ,142 ,'TRANSFERENCIA BANCARIA' ,'select 1 from conhistdoc where c53_coddoc = 142' ,2021 );

insert into conhistdocregra( c92_sequencial ,c92_conhistdoc ,c92_descricao ,c92_regra ,c92_anousu ) values ( nextval('conhistdocregra_c92_sequencial_seq') ,143 ,'ESTPRNO DE TRANSFERENCIA BANCARIA' ,'select 1 from conhistdoc where c53_coddoc = 143' ,2021 );


SQL;
      $this->execute($sql);
    }



    public function down()
    {
        $sql = <<<SQL

delete from sliptipooperacao where k152_sequencial in (17, 18);
delete from vinculoeventoscontabeis where c115_conhistdocinclusao = 142 and c115_conhistdocestorno = 143;
delete from conhistdoc where c53_coddoc in (142, 143);


delete from db_menu where id_item_filho = 228562 AND modulo = 39;
delete from db_menu where id_item_filho = 228563 AND modulo = 39;
delete from db_menu where id_item_filho = 228561 AND modulo = 39;

delete from db_itensmenu where id_item in (228561, 228562, 228563);

update db_itensmenu set descricao = 'Transferência Bancária' where id_item = 9382;

SQL;
      $this->execute($sql);
    }




}
