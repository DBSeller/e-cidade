update db_syscampo set nomecam = 'ed18_i_cnpj', conteudo = 'varchar(14)', descricao = 'CNPJ', valorinicial = 'null', rotulo = 'CNPJ', nulo = 't', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CNPJ' where codcam = 12619;
delete from db_syscampodep where codcam = 12619;
delete from db_syscampodef where codcam = 12619;
update db_syscampo set nomecam = 'ed18_i_cnpjmantprivada', conteudo = 'varchar(14)', descricao = 'CNPJ Mantenedora Privada', valorinicial = 'null', rotulo = 'CNPJ Mantenedora Privada', nulo = 't', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CNPJ Mantenedora Privada' where codcam = 17985;
delete from db_syscampodep where codcam = 17985;
delete from db_syscampodef where codcam = 17985;
update db_syscampo set nomecam = 'ed18_i_cnpjprivada', conteudo = 'varchar(14)', descricao = 'CNPJ da Escola Privada', valorinicial = 'null', rotulo = 'CNPJ da Escola Privada', nulo = 't', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CNPJ da Escola Privada' where codcam = 13455;
delete from db_syscampodep where codcam = 13455;
delete from db_syscampodef where codcam = 13455;

alter table escola alter ed18_i_cnpj            type varchar(14);
alter table escola alter ed18_i_cnpj            set default 'null';
alter table escola alter ed18_i_cnpjprivada     type varchar(14);
alter table escola alter ed18_i_cnpjprivada     set default 'null';
alter table escola alter ed18_i_cnpjmantprivada type varchar(14);
alter table escola alter ed18_i_cnpjmantprivada set default 'null';
