
-- ## Início DDL
alter table acordocomissaomembro add ac07_datainicio date;
alter table acordocomissaomembro add ac07_datatermino date;
alter table acordoposicao add ac26_tipooperacao integer;

-- Controle de contratos encerrados
create sequence acordoencerramentolicitacon_ac58_sequencial_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
create table acordoencerramentolicitacon(
ac58_sequencial   int4 not null default nextval('acordoencerramentolicitacon_ac58_sequencial_seq'),
ac58_acordo       int4 not null,
ac58_data         date,
constraint acordoencerramentolicitacon_sequ_pk primary key (ac58_sequencial));
alter table acordoencerramentolicitacon
add constraint acordoencerramentolicitacon_acordo_fk foreign key (ac58_acordo)
references acordo;

-- Campo Ano do Exercício na Tabela acordoempempenho
alter table acordoempempenho add column ac54_ano int4 default null;

-- ## Início DML
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21841 ,'ac07_datainicio' ,'date' ,'Data de Início' ,'' ,'Data de Início' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Data de Início');
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 2831 ,21841 ,5 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21842 ,'ac07_datatermino' ,'date' ,'Data de Término' ,'' ,'Data de Término' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Data de Término');
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 2831 ,21842 ,6 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21843 ,'ac26_tipooperacao' ,'int4' ,'Tipo de Operação' ,'' ,'Tipo de Operação' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Tipo de Operação' );
insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 21843 , '1' ,'Acréscimo de Valor por Aumento de Quantitativo' );
insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 21843 , '2' ,'Acréscimo de valor por inclusão de Itens novos' );
insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 21843 , '3' ,'Reajustamento de Preços' );
insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 21843 , '4' ,'Redução de Valor por Supressão de Itens' );
insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 21843 , '5' ,'Redução de Valor por Supressão de Quantitativo' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 2930 ,21843 ,10 ,0 );

insert into acordoposicaotipo values (7, 'Alteração de Dotação');
insert into acordoposicaotipo values (8, 'Supressão de Quantidade/Valor');

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10227 ,'Supressão de Quantidade/Valor' ,'Supressão de Quantidade/Valor' ,'ac04_aditamentosupressaovalor001.php' ,'1' ,'1' ,'Supressão de Quantidade/Valor' ,'true' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8568 ,10227 ,6 ,8251 );

update acordocomissaotipomembro set ac42_descricao = 'Gestor' where ac42_sequencial = 1;

-- Controle de contratos encerrados
insert into db_sysarquivo values (3933, 'acordoencerramentolicitacon', 'Controle do encerramento de contratos para o LicitaCon.', 'ac58', '2016-04-15', 'Encerramento de Contratos', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (69,3933);
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21845 ,'ac58_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3933 ,21845 ,1 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21846 ,'ac58_acordo' ,'int4' ,'Acordo' ,'' ,'Acordo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Acordo' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3933 ,21846 ,2 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21847 ,'ac58_data' ,'date' ,'Data' ,'' ,'Data' ,10 ,'false' ,'false' ,'false' ,0 ,'text' ,'Data' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3933 ,21847 ,3 ,0 );
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3933,21845,1,21845);
insert into db_sysforkey values(3933,21846,1,2828,0);
insert into db_syssequencia values(1000565, 'acordoencerramentolicitacon_ac58_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000565 where codarq = 3933 and codcam = 21845;
update db_layoutcampos set db52_layoutformat = 13 where db52_codigo = 13148;

-- Campo Ano do Exercício na Tabela acordoempempenho
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21848 ,'ac54_ano' ,'int4' ,'Exercício' ,'' ,'Exercício' ,4 ,'true' ,'false' ,'false' ,1 ,'text' ,'Exercício' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3926 ,21848 ,5 ,0);

-- ## Acertos

-- Ajusta data de encerramento das licitações
update liclicitaevento
   set l46_dataevento = '2016-05-01'
 where l46_liclicitatipoevento = 7;

-- Ativação dos itens de menu das novas rotinas desenvolvidas para o LicitaCon
update db_itensmenu set libcliente = true where id_item in (10221, 10225);

-- Encerramento de todos os contratos
insert into acordoevento
     select nextval('acordoevento_ac55_sequencial_seq'),
            4,
            ac16_sequencial,
            '2016-05-01',
            null,
            null,
            null,
            null
       from acordo
      where not exists(select * from acordoevento where ac55_acordo = ac16_sequencial and ac55_tipoevento = 4);