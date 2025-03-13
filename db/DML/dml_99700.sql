drop table if exists bkp_contacorrenteduplicados;
drop table if exists bkp_de_para_contacorrente;

update contacorrentedetalhe
   set c19_contacorrente = 1
  from conplanoreduz, conplano
 where c61_codcon = c60_codcon
   and c61_anousu = c60_anousu
   and c61_reduz  = c19_reduz
   and c19_contacorrente = 103
   and c60_anousu = 2016
   and c60_estrut ilike '82111%'
   and exists (select 1 from db_config where upper(uf) = 'RS');

update contacorrentedetalhe
   set c19_concarpeculiar = '000'
 where c19_contacorrente = 1
   and c19_concarpeculiar is null
   and exists (select 1 from db_config where upper(uf) = 'RS');

create table bkp_contacorrenteduplicados
    as select min(c19_sequencial) as primeiro_sequencial,
       c19_orctiporec,
       c19_concarpeculiar,
       c19_reduz,
       count(*)
  from contacorrentedetalhe
       inner join conplanoreduz on conplanoreduz.c61_reduz = contacorrentedetalhe.c19_reduz
                               and conplanoreduz.c61_anousu = contacorrentedetalhe.c19_conplanoreduzanousu
       inner join conplano  on conplano.c60_codcon = conplanoreduz.c61_codcon
                           and conplano.c60_anousu = conplanoreduz.c61_anousu
 where c19_contacorrente = 1
   and c19_conplanoreduzanousu = 2016
   and c60_estrut ilike '82111%'
 group by c19_orctiporec,c19_concarpeculiar, c19_reduz, c19_instit having count(*) > 1;

create table bkp_de_para_contacorrente
    as select c.c19_sequencial as sequencial_errado,
              bkp.primeiro_sequencial
         from contacorrentedetalhe c
              inner join bkp_contacorrenteduplicados bkp  on bkp.c19_orctiporec = c.c19_orctiporec
                                                         and bkp.c19_concarpeculiar::varchar = c.c19_concarpeculiar::varchar
                                                         and bkp.c19_reduz = c.c19_reduz
                                                         and c.c19_sequencial <> bkp.primeiro_sequencial
                                                         and c.c19_conplanoreduzanousu = 2016 ;

create table bkp_pad_contacorrentedetalheconlancamval
    as select * from contacorrentedetalheconlancamval;

update contacorrentedetalheconlancamval
   set c28_contacorrentedetalhe = bkp.primeiro_sequencial
  from bkp_de_para_contacorrente as bkp
 where bkp.sequencial_errado = contacorrentedetalheconlancamval.c28_contacorrentedetalhe
  and exists (select 1 from db_config where upper(uf) = 'RS');

create table bkp_pad_contacorrentesaldo
    as select * from contacorrentesaldo;

delete from contacorrentesaldo
 where c29_contacorrentedetalhe in (select sequencial_errado from bkp_de_para_contacorrente)
   and exists (select 1 from db_config where upper(uf) = 'RS');


create table bkp_pad_contacorrentedetalhe
    as select * from contacorrentedetalhe;

delete from contacorrentedetalhe
 where c19_sequencial in (select sequencial_errado from bkp_de_para_contacorrente)
   and exists (select 1 from db_config where upper(uf) = 'RS');

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10236 ,'Disponibilidade Financeira' ,'Relatório de Disponibilidade Financeira' ,'con2_disponibilidadefinanceira001.php' ,'1' ,'1' ,'Disponibilidade Financeira' ,'true' );
delete from db_menu where id_item_filho = 10236 AND modulo = 209;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9581 ,10236 ,3 ,209 );