--Rotina do controle de direitos
update db_itensmenu set descricao = 'Agenda de Assentamentos' , help = 'Agenda de Assentamentos', funcao = 'rec1_agendaassentamento001.php' where id_item = 10113;
update db_itensmenu set descricao = 'Autorização de Assentamentos' , help = 'Autorização de Assentamentos', funcao = 'rec4_processamentoagendaassentamento001.php' where id_item = 10114;

update db_itensmenu set funcao = regexp_replace(funcao::varchar, '(.*\.php).*', '\\1')||'?menuDepreciado=true' where id_item IN (10113, 10114);

delete from db_menu where id_item_filho IN (10113, 10114);
insert into db_menu values (29, 10113, 263, 2323);
insert into db_menu values (32, 10114, 457, 2323);

insert into db_itensmenu select 10291, 'Parâmetros', 'Parâmetros', 'rec1_agendaassentamento001.php', '1', '1', 'Menu para configuração da agenda de assentamentos.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10291) limit 1;
delete from db_menu where id_item_filho = 10291 AND modulo = 2323;
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (10250, 10291, 3, 2323);
insert into db_itensmenu select 10292, 'Processamento', 'Processamento', 'rec4_processamentoagendaassentamento001.php', '1', '1', 'Autorização de assentamentos, este é o gatilho que cria ou não os assentamentos.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10292) limit 1;
delete from db_menu where id_item_filho = 10292 AND modulo = 2323;
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (10250, 10292, 4, 2323);


--Rotina do Consignet
insert into db_itensmenu select 10294, 'Gerar Arquivo de Margem', 'Gera o arquivo com as margens dos servidores', 'pes4_consignetmargem001.php', '1', '1', 'Rotina para geração de arquivo com as margens consignáveis dos servidores para o consignet da empresa DB1.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10294) limit 1;
delete from db_menu where id_item_filho = 10294 AND modulo = 952;
insert into db_menu values (10271, 10294, 1, 952);
insert into db_itensmenu select 10295, 'Importar Arquivo de Movimento', 'Importa arquivo com descontos', 'pes4_importacaoarquivoconsignet001.php', '1', '1', 'Rotina para importar arquivo com os descontos a serem lançados no ponto de salário do servidor.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10295) limit 1;
delete from db_menu where id_item_filho = 10295 AND modulo = 952;
insert into db_menu values (10271, 10295, 2, 952);
insert into db_itensmenu select 10296, 'Gerar Arquivo de Retorno', 'Gera arquivo para envio com os descontos efetuados', 'pes4_geracaoarquivoretornoconsignet001.php', '1', '1', 'Rotina para gerar arquivo de retorno com a informação do valor debitado dos servidores.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10296) limit 1;
delete from db_menu where id_item_filho = 10296 AND modulo = 952;
insert into db_menu values (10271, 10296, 3, 952);
insert into db_itensmenu select 10297, 'Reemitir Relatório de Importação', 'Reemissão do relatório da importação', 'pes4_consignetrelatorioimportacao001.php', '1', '1', 'Rotina para reemissão de relatório com possíveis inconstistências da importação do arquivo com os descontos a serem efetuados.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10297) limit 1;
delete from db_menu where id_item_filho = 10297 AND modulo = 952;
insert into db_menu values (10271, 10297, 4, 952);

update db_itensmenu set funcao = regexp_replace(funcao::varchar, '(.*\.php).*', '\\1')||'?menuDepreciado=true' where id_item IN (10050, 10051, 10052, 10053);

--Rotina do E-consig
insert into db_itensmenu select 10298, 'Gerar Arquivo de Margem', 'Gerar Arquivo de Margem', 'pes4_econsigmargem001.php', '1', '1', 'Gera o Arquivo de margem para ser enviado ao e-consig.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10298) limit 1;
delete from db_menu where id_item_filho = 10298 AND modulo = 952;
insert into db_menu values (10272, 10298, 1, 952);
insert into db_itensmenu select 10301, 'Importar Arquivo de Movimento', 'Importar Arquivo de Movimento', 'pes4_importacaoarquivoeconsig001.php', '1', '1', 'Rotina responsável pela importação do arquivo enviado pela Zetra referente ao e-consig, que são os eventos financeiros que devem ser lançados no ponto do servidor.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10301) limit 1;
delete from db_menu where id_item_filho = 10301 AND modulo = 952;
insert into db_menu values (10272, 10301, 2, 952);
insert into db_itensmenu select 10299, 'Gerar Arquivo de Retorno', 'Gerar Arquivo de Retorno', 'pes4_geracaoarquivoretornoeconsig001.php', '1', '1', 'Rotina de geração do arquivo de retorno econsig.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10299) limit 1;
delete from db_menu where id_item_filho = 10299 AND modulo = 952;
insert into db_menu values (10272, 10299, 3, 952);
insert into db_itensmenu select 10300, 'Reemitir Relatório de Importação', 'Reemitir Relatório de Importação', 'pes4_econsigrelatorioimportacao001.php', '1', '1', 'Item de menu responsável por imprimir o relatório de importção do e-consig.', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10300) limit 1;
delete from db_menu where id_item_filho = 10300 AND modulo = 952;
insert into db_menu values (10272, 10300, 4, 952);

update db_itensmenu set funcao = regexp_replace(funcao::varchar, '(.*\.php).*', '\\1')||'?menuDepreciado=true' where id_item IN (10019,10017, 9867, 9898);