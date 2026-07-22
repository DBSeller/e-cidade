create temp table w_1000013 as
  select
    distinct  c69_codlan, c61_instit
  from conlancam
    left join conlancaminstit on c02_codlan = c70_codlan
    inner join conlancamval   on c69_codlan = c70_codlan
    inner join conplanoreduz  on (c69_anousu, c69_credito) = (c61_anousu, c61_reduz)
  where c02_codlan is null;

insert into conlancaminstit
  select nextval('conlancaminstit_c02_sequencial_seq'), c69_codlan, c61_instit
  from w_1000013;