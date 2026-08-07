
select fc_executa_ddl($$
  alter sequence if exists cronogramaperspectivaacompanhamento_o151_sequencial_seq
      set schema orcamento;
$$);

select fc_executa_ddl($$
  alter table if exists cronogramaperspectivaacompanhamento
      set schema orcamento;
$$);

select fc_executa_ddl($$
  alter sequence if exists limbo.cronogramaperspectivaacompanhamento_o151_sequencial_seq
      set schema orcamento;
$$);

select fc_executa_ddl($$
  alter table if exists limbo.cronogramaperspectivaacompanhamento
      set schema orcamento;
$$);

insert into db_sysarqmod
  select 35,3814
    from db_sysarqmod
   where not exists(select 1 from db_sysarqmod where codarq = 3814) limit 1;