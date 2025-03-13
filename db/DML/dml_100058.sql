insert into db_itensfilho  select * from w_limpezamenus_db_itensfilho w where w.id_item not in (select dd.id_item from db_itensfilho dd );

drop table if exists w_limpezamenus_db_itensmenu;
drop table if exists w_limpezamenus_db_itensfilho;

create table w_limpezamenus_db_itensmenu  as select * from db_itensmenu d where libcliente is false and trim(funcao) <> '' and (select count(*) from db_itensmenu dd where d.funcao = dd.funcao) > 1 order by funcao;
create table w_limpezamenus_db_itensfilho as select * from db_itensfilho d where id_item in (select id_item from w_limpezamenus_db_itensmenu);

delete from db_itensfilho where id_item in (select id_item from w_limpezamenus_db_itensmenu);
delete from db_itensmenu where id_item in (select id_item from w_limpezamenus_db_itensmenu);