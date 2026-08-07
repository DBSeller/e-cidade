update db_itensmenu set funcao = regexp_replace(funcao::varchar, '(.*\.php).*', '\\1')||'?menuDepreciado=true' where id_item IN (437509);

delete from db_menu where id_item_filho = 437509;
insert into db_menu values (30, 437509, 6, 2323);	

insert into db_itensmenu select 10302, 'Extrato à Previdência', 'Extrato à Previdência', 'pes2_relextratorpps001.php', '1', '1', 'Extrato do RPPS', 'true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10302) limit 1;
delete from db_menu where id_item_filho = 10302 AND modulo = 952;
insert into db_menu values (2458, 10302, 30, 952);