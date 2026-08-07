drop table if exists w_acordodocumento_6086;
drop table if exists w_liclicitaeventodocumento_6086;
drop table if exists w_liccomissao_6086;

create table w_acordodocumento_6086 as select ac40_sequencial, ac40_nomearquivo from acordodocumento;
update acordodocumento set ac40_nomearquivo = translate(ac40_nomearquivo, 'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑıİ', 'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY') where  exists (select 1 from db_config where upper(uf) = 'RS');;

create table w_liclicitaeventodocumento_6086 as select l47_sequencial, l47_nomearquivo from liclicitaeventodocumento;
update liclicitaeventodocumento set l47_nomearquivo = translate(l47_nomearquivo, 'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑıİ', 'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY') where  exists (select 1 from db_config where upper(uf) = 'RS');;

create table w_liccomissao_6086 as select l30_codigo, l30_nomearquivo from liccomissao;
update liccomissao set l30_nomearquivo = translate(l30_nomearquivo, 'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑıİ', 'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY') where  exists (select 1 from db_config where upper(uf) = 'RS');;