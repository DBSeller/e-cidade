insert into db_syscampo select 22161,'rh85_liquido ','float8','Valor liquido do comprovante de pagamento','0', 'Valor Líquido',20,'t','f','f',4,'text','Valor Líquido'   where 22161 not in (select codcam from db_syscampo);
insert into db_syscampo select 22162,'rh85_desconto','float8','Valor total de desconto para o comprovante de pagamento','0', 'Valor Desconto',20,'t','f','f',4,'text','Valor Desconto' where 22162 not in (select codcam from db_syscampo);
insert into db_syscampo select 22163,'rh85_provento','float8','Valor total de proventos no comprovante de pagamento.','0', 'Total Provento',20,'t','f','f',4,'text','Total Provento'   where 22163 not in (select codcam from db_syscampo);

insert into db_sysarqcamp select 2563,22161,11,0 where 22161 not in (select codcam from db_sysarqcamp);
insert into db_sysarqcamp select 2563,22162,12,0 where 22162 not in (select codcam from db_sysarqcamp);
insert into db_sysarqcamp select 2563,22163,13,0 where 22163 not in (select codcam from db_sysarqcamp);

select fc_executa_ddl('alter table pessoal.rhemitecontracheque add column rh85_liquido  float8 default 0');
select fc_executa_ddl('alter table pessoal.rhemitecontracheque add column rh85_desconto float8 default 0');
select fc_executa_ddl('alter table pessoal.rhemitecontracheque add column rh85_provento float8 default 0');