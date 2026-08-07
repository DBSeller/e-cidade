
set client_encoding = latin1;

insert into sau_tipocompatibilidade values (5,  'COMPATIBILIDADE OBRIGATÓRIA');
select setval('sau_tipocompatibilidade_sd68_i_codigo_seq', (select max(sd68_i_codigo) from sau_tipocompatibilidade));