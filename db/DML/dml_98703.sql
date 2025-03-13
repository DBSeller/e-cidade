insert into db_syscampo values (21580, 'k157_observacao', 'text', 'Observação', 'Observação', '', 1, 'true', 'true', 'false', 0, 'text', 'Observação' );
delete from db_syscampodef where codcam = 21580;
insert into db_sysarqcamp values ( 3484 ,21580 ,8 ,0 );


select fc_executa_ddl('
alter table abatimentoutilizacao add k157_observacao text;
');
