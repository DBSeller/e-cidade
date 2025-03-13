---------------------------------------------------------------------------------------------
-------------------------------- INCIO FINANCEIRO -------------------------------------------
---------------------------------------------------------------------------------------------

delete from db_sysarqcamp where codarq = 1603 and codcam = 21351;
delete from db_syscampo where nomecam = 've62_situacao';

delete from db_sysarqcamp where codarq = 1603 and codcam = 21352;
delete from db_syscampo where nomecam = 've62_numero';

delete from db_sysarqcamp where codarq = 1603 and codcam = 21353;
delete from db_syscampo where nomecam = 've62_anousu';

delete from db_sysarqcamp where codarq = 1604 and codcam = 21354;
delete from db_syscampo where nomecam = 've63_valortotalcomdesconto';

delete from db_sysarqcamp where codarq = 1604 and codcam = 21355;
delete from db_sysforkey where codarq = 1604 and codcam = 21355;
delete from db_syscampo where nomecam = 've63_unidade';

delete from db_sysarqcamp where codarq = 1604 and codcam = 21356;
delete from db_syscampo where nomecam = 've63_tipoitem';

delete from db_sysarqcamp where codarq = 1604 and codcam = 21495;
delete from db_syscampo where nomecam = 've63_proximatroca';

delete from db_sysarqcamp where codarq = 1604 and codcam = 21496;
delete from db_syscampo where nomecam = 've63_datanota';

delete from db_sysarqcamp where codarq = 1604 and codcam = 21497;
delete from db_syscampo where nomecam = 've63_numeronota';
update db_syscampo set nulo = 'false' where nomecam = 've62_notafisc';

delete from db_sysarqcamp where codarq = 1603 and codcam = 21376;
delete from db_sysforkey where codarq = 1603 and codcam = 21376;
delete from db_syscampo where codcam = 21376;

delete from db_syssequencia where nomesequencia = 'autorizacaocirculacaoveiculo_ve13_sequencial_seq';

delete from db_syscadind where codind = 4248 and codcam = 21377;
delete from db_sysindices where nomeind = 'autorizacaocirculacaoveiculo_departamento_in';
delete from db_sysforkey where codarq = 3845 and codcam = 21377 and referen = 154;
delete from db_sysarqcamp where codarq = 3845 and codcam = 21377;
delete from db_syscampo where nomecam = 've13_departamento';

delete from db_syscadind where codind = 4244 and codcam = 21366;
delete from db_syscadind where codind = 4246 and codcam = 21367;
delete from db_syscadind where codind = 4245 and codcam = 21368;
delete from db_sysindices where nomeind = 'autorizacaocirculacaoveiculo_veiculo_in';
delete from db_sysindices where nomeind = 'autorizacaocirculacaoveiculo_motorista_in';
delete from db_sysindices where nomeind = 'autorizacaocirculacaoveiculo_instituicao_in';

delete from db_sysforkey where codarq = 3845 and codcam = 21366 and referen = 83;
delete from db_sysforkey where codarq = 3845 and codcam = 21367 and referen = 1590;
delete from db_sysforkey where codarq = 3845 and codcam = 21368 and referen = 1593;

delete from db_sysprikey where codarq = 3845 and codcam = 21365 and camiden = 21365;

delete from db_sysarqcamp where codarq = 3845 and codcam = 21372;
delete from db_sysarqcamp where codarq = 3845 and codcam = 21371;
delete from db_sysarqcamp where codarq = 3845 and codcam = 21370;
delete from db_sysarqcamp where codarq = 3845 and codcam = 21369;
delete from db_sysarqcamp where codarq = 3845 and codcam = 21368;
delete from db_sysarqcamp where codarq = 3845 and codcam = 21367;
delete from db_sysarqcamp where codarq = 3845 and codcam = 21366;
delete from db_sysarqcamp where codarq = 3845 and codcam = 21365;

delete from db_syscampo where nomecam = 've13_observacao';
delete from db_syscampo where nomecam = 've13_dataemissao';
delete from db_syscampo where nomecam = 've13_datafinal';
delete from db_syscampo where nomecam = 've13_datainicial';
delete from db_syscampo where nomecam = 've13_motorista';
delete from db_syscampo where nomecam = 've13_veiculo';
delete from db_syscampo where nomecam = 've13_instituicao';
delete from db_syscampo where nomecam = 've13_sequencial';

delete from db_sysarqmod where codmod = 45 and codarq = 3845;
delete from db_sysarquivo where nomearq = 'autorizacaocirculacaoveiculo';

delete from db_docparagpadrao where db62_coddoc in (select db60_coddoc from db_documentopadrao where db60_tipodoc  = 5020);
delete from db_documentopadrao where db60_tipodoc = 5020;
delete from db_tipodoc where db08_codigo = 5020;

delete from db_docparagpadrao where db62_coddoc in (select db60_coddoc from db_documentopadrao where db60_tipodoc  = 5021);
delete from db_documentopadrao where db60_tipodoc = 5021;
delete from db_tipodoc where db08_codigo = 5021;

delete from db_menu where id_item_filho = 10135 AND modulo = 633;
delete from db_itensmenu where id_item = 10135;
delete from db_menu where id_item_filho = 10134 AND modulo = 633;
delete from db_itensmenu where id_item = 10134;
delete from db_menu where id_item_filho = 10133 AND modulo = 633;
delete from db_itensmenu where id_item = 10133;
delete from db_menu where id_item_filho = 10132 AND modulo = 633;
delete from db_itensmenu where id_item = 10132;

delete from db_menu where id_item_filho = 608583 AND modulo = 633;
delete from db_menu where id_item_filho = 5437 AND modulo = 633;
delete from db_menu where id_item_filho = 5438 AND modulo = 633;
delete from db_menu where id_item_filho = 5445 AND modulo = 633;
delete from db_menu where id_item_filho = 5444 AND modulo = 633;
delete from db_menu where id_item_filho = 5447 AND modulo = 633;
delete from db_menu where id_item_filho = 5448 AND modulo = 633;
delete from db_menu where id_item_filho = 5452 AND modulo = 633;
delete from db_menu where id_item_filho = 5451 AND modulo = 633;
delete from db_menu where id_item_filho = 5450 AND modulo = 633;
delete from db_menu where id_item_filho = 5440 AND modulo = 633;
delete from db_menu where id_item_filho = 5439 AND modulo = 633;
delete from db_menu where id_item_filho = 5441 AND modulo = 633;
delete from db_menu where id_item_filho = 5442 AND modulo = 633;
delete from db_menu where id_item_filho = 5449 AND modulo = 633;
delete from db_menu where id_item_filho = 5443 AND modulo = 633;
delete from db_menu where id_item_filho = 5446 AND modulo = 633;
delete from db_menu where id_item_filho = 6977 AND modulo = 633;

insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,608583 ,1 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5437 ,1 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5438 ,2 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5445 ,3 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5444 ,4 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5447 ,5 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5448 ,6 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5452 ,7 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5451 ,8 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5450 ,9 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5440 ,10 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5439 ,11 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5441 ,12 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5442 ,13 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5449 ,14 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5443 ,15 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,5446 ,16 ,633 );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,6977 ,17 ,633 );

delete from db_menu where id_item_filho = 10137 AND modulo = 633;
delete from db_menu where id_item_filho = 10138 AND modulo = 633;
delete from db_itensmenu where id_item = 10137;
delete from db_itensmenu where id_item = 10138;
delete from db_menu where id_item_filho = 10136 AND modulo = 633;
delete from db_itensmenu where id_item = 10136;
delete from db_menu where id_item_filho = 10145 AND modulo = 10094;
delete from db_itensmenu where id_item = 10145;

delete from db_docparagpadrao where db62_coddoc in (select db60_coddoc from db_documentopadrao where db60_tipodoc  = 5022);
delete from db_documentopadrao where db60_tipodoc = 5022;
delete from db_tipodoc where db08_codigo = 5022;

delete from db_docparagpadrao where db62_coddoc in (select db60_coddoc from db_documentopadrao where db60_tipodoc  = 5023);
delete from db_documentopadrao where db60_tipodoc = 5023;
delete from db_tipodoc where db08_codigo = 5023;

delete from db_menu where id_item = 10132 and id_item_filho = 10144 and modulo = 633;
delete from db_itensmenu where id_item = 10144 and funcao = 'vei2_manutencaoveiculos001.php';

delete from db_menu where id_item = 10132 and id_item_filho = 10149 AND modulo = 633;
delete from db_itensmenu where id_item = 10149 and funcao = 'vei2_fichacontrolemanutencao001.php';

delete from db_menu where id_item = 10132 and id_item_filho = 10152  and modulo = 633;
delete from db_itensmenu where id_item = 10152  and funcao = 'vei2_controlehodometro001.php';

-- Levantamento Patrimonial

---- tabela levantamentopatrimonial
delete from db_syssequencia where nomesequencia = 'levantamentopatrimonial_p13_sequencial_seq';
delete from db_syscadind    where codind = 4270 and codcam  = 21509;
delete from db_sysindices   where codind = 4270 and nomeind = 'levantamentopatrimonial_departamento_in';
delete from db_sysforkey    where codarq = 3862 and codcam  = 21509 and referen = 154;
delete from db_sysprikey    where codarq = 3862 and codcam  = 21508 and camiden = 21508;

delete from db_sysarqcamp where codarq = 3862  and codcam  = 21510;
delete from db_syscampo   where codcam = 21510 and nomecam = 'p13_data';

delete from db_sysarqcamp where codarq = 3862  and codcam  = 21509;
delete from db_syscampo   where codcam = 21509 and nomecam = 'p13_departamento';

delete from db_sysarqcamp where codarq = 3862  and codcam  = 21508;
delete from db_syscampo   where codcam = 21508 and nomecam = 'p13_sequencial';

delete from db_sysarqmod  where codmod = 18   and codarq  = 3862;
delete from db_sysarquivo where codarq = 3862 and nomearq = 'levantamentopatrimonial';

---- tabela levantamentopatrimonialbens
delete from db_syssequencia where nomesequencia = 'levantamentopatrimonialbens_p14_sequencial_seq';
delete from db_syscadind    where codind = 4271 and codcam  = 21512;
delete from db_sysindices   where codind = 4271 and nomeind = 'levantamentopatrimonialbens_levantamentopatrimonial_in';
delete from db_sysforkey    where codarq = 3864 and codcam  = 21512 and referen = 3862;
delete from db_sysprikey    where codarq = 3864 and codcam  = 21511 and camiden = 21511;

delete from db_sysarqcamp where codarq = 3864  and codcam  = 21513;
delete from db_syscampo   where codcam = 21513 and nomecam = 'p14_placa';

delete from db_sysarqcamp where codarq = 3864  and codcam  = 21512;
delete from db_syscampo   where codcam = 21512 and nomecam = 'p14_levantamentopatrimonial';

delete from db_sysarqcamp where codarq = 3864  and codcam  = 21511;
delete from db_syscampo   where codcam = 21511 and nomecam = 'p14_sequencial';

delete from db_sysarqarq  where codarqpai = 3862 and codarq  = 3864;
delete from db_sysarqmod  where codmod    = 18   and codarq  = 3864;
delete from db_sysarquivo where codarq    = 3864 and nomearq = 'levantamentopatrimonialbens';


delete from db_menu where id_item = 32 and id_item_filho = 10153 and menusequencia = 460 and modulo = 439;
delete from db_itensmenu where id_item = 10153;

delete from db_menu where id_item = 9150 and id_item_filho = 10154 and menusequencia = 6 and modulo = 439;
delete from db_itensmenu where id_item = 10154;


delete from db_syscadind where codind = 4272 and codcam = 21514;
delete from db_syscadind where codind = 4273 and codcam = 21516;
delete from db_syscadind where codind = 4274 and codcam = 21515;

delete from db_sysindices where codind = 4272 and nomeind = 'pagordemdescontoempanulado_sequencial_in';
delete from db_sysindices where codind = 4273 and nomeind = 'pagordemdescontoempanulado_empanulado_in';
delete from db_sysindices where codind = 4274 and nomeind = 'pagordemdescontoempanulado_pagordemdesconto_in';

delete from db_sysprikey where codarq = 3865 and codcam = 21516 and camiden = 21514;
delete from db_sysforkey where codarq = 3865 and codcam = 21516 and referen = 1030;
delete from db_sysforkey where codarq = 3865 and codcam = 21515 and referen = 2026;

delete from db_syssequencia where nomesequencia = 'pagordemdescontoempanulado_e06_sequencial_seq';

delete from db_sysarqcamp where codarq = 3865 and codcam = 21514;
delete from db_sysarqcamp where codarq = 3865 and codcam = 21516;
delete from db_sysarqcamp where codarq = 3865 and codcam = 21515;

delete from db_syscampo where codcam = 21514 and nomecam = 'e06_sequencial';
delete from db_syscampo where codcam = 21515 and nomecam = 'e06_pagordemdesconto';
delete from db_syscampo where codcam = 21516 and nomecam = 'e06_empanulado';

delete from db_sysarqmod where codmod = 38 and codarq = 3865;
delete from db_sysarquivo where codarq = 3865 and nomearq = 'pagordemdescontoempanulado';
---------------------------------------------------------------------------------------------
-------------------------------- FIM FINANCEIRO---------------------------------------------
---------------------------------------------------------------------------------------------

---------------------------------------------------------------------------------------------
------------------------------ INÍCIO EDUCAÇÃO/SAÚDE ----------------------------------------
---------------------------------------------------------------------------------------------
delete from db_syscampodef where codcam = 20089 and defcampo = '3';
delete from db_sysarqcamp where codarq = 3846;
delete from db_sysprikey where codarq = 3846;
delete from db_sysforkey where codarq = 3846;
delete from db_syscampo where codcam in(21373, 21374, 21375);
delete from db_sysarqmod where codmod = 60 and codarq = 3846;
delete from db_sysarquivo where codarq = 3846;
delete from db_syscadind where codind = 4247;
delete from db_sysindices where codind = 4247;
delete from db_syssequencia where codsequencia = 1000497;

delete from db_sysforkey where codarq in (3866, 3867, 3868, 3869, 3870, 3871);
delete from db_sysforkey where codarq = 3837 and codcam in (21519, 21579);
delete from db_sysprikey where codarq in (3866, 3867, 3868, 3869, 3870, 3871);
delete from db_sysarqcamp where codarq in (3866, 3867, 3868, 3869, 3870, 3871);
delete from db_sysarqcamp where codcam in  (21519, 21579) and codarq = 3837;
delete from db_sysarqmod  where codarq in (3866, 3867, 3868, 3869, 3870, 3871);
delete from db_sysarquivo where codarq in (3866, 3867, 3868, 3869, 3870, 3871);
delete from db_syscadind  where codind in (4275, 4276, 4277, 4278, 4279, 4280, 4281, 4282, 4283, 4284, 4285, 4286);
delete from db_sysindices where codind in (4275, 4276, 4277, 4278, 4279, 4280, 4281, 4282, 4283, 4284, 4285, 4286);
delete from db_syscampo where codcam in (21517, 21518, 21519, 21520, 21521, 21522, 21523, 21524, 21525, 21526, 21527, 21528, 21529, 21530, 21531, 21532, 21533, 21534, 21535, 21536, 21537, 21538, 21539, 21540, 21541, 21542, 21543, 21544, 21545, 21546, 21547, 21548, 21549, 21550, 21551, 21552, 21553, 21554, 21555, 21556, 21557, 21558, 21559, 21560, 21561, 21562, 21563, 21564, 21565, 21566, 21567, 21568, 21569, 21570, 21571, 21572, 21579);
delete from db_syssequencia where codsequencia in (1000515,1000514,1000516,1000517,1000518,1000519);


insert into db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
     values (21295, 'fa59_data', 'date', 'Data da integração', null, 'Data', 10, 'f', 'f', 'f', 1, 'text', 'Data' ),
            (21298, 'fa59_protocolo', 'text', 'Protocolo de retorno do Horus', null, 'Protocolo', 1, 'f', 't', 'f', 0, 'text', 'Protocolo');

update db_itensmenu set libcliente = 'false' where id_item = 10115;

---------------------------------------------------------------------------------------------
-------------------------------- FIM EDUCAÇÃO/SAÚDE -----------------------------------------
---------------------------------------------------------------------------------------------

--------------------------------------------------------------------------------------------
-------------------------------------- INICIO PESSOAL --------------------------------------
--------------------------------------------------------------------------------------------
--Exclui menu de consulta ao cadastro do servidor do módulo RH
delete from db_menu where id_item_filho = 10155 AND modulo = 2323;
delete from db_itensmenu where id_item = 10155;

--Recria vínculo do menu de consulta ao cadastro do servidor do módulo pessoal no módulo RH
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,2464 ,1 ,2323 );');

delete from db_layoutcampos where db52_layoutlinha in (select db51_codigo from db_layoutlinha where db51_layouttxt = 229);
delete from db_layoutlinha where db51_layouttxt = 229;
delete from db_layouttxt where db50_codigo = 229;

---------------------------------------------------------------------------------------------
-------------------------------------- FIM PESSOAL ------------------------------------------
---------------------------------------------------------------------------------------------
