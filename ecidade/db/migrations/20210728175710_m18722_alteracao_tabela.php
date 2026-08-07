<?php

use Classes\PostgresMigration;

class M18722AlteracaoTabela extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_syscampo values(1013369,'o201_orctiporec','int4','Id do Recurso','0', 'Recurso',10,'f','f','f',1,'text','Recurso');
insert into db_sysarqcamp values(1010564,1013369,4,0);
SQL
        );
        $this->alteraEstrutura();
    }

    public function down()
    {
        $this->execute("alter table contabilidade.conlancamcomplementorecurso drop column o201_orctiporec;");

        $this->execute(<<<SQL
delete from db_sysarqcamp where codarq = 1010564 and codcam = 1013369;
delete from db_syscampo where codcam = 1013369;
SQL
        );
    }

    private function alteraEstrutura()
    {
        $this->execute(<<<SQL
alter table contabilidade.conlancamcomplementorecurso
add column o201_orctiporec int default 0;
SQL
        );
        /**
         * Ajusta apenas os registros de 2021
         */
        $this->ajustaTabelaConlancamrecurso();

        $this->execute(<<<SQL
create temporary table w_conlancamcomplementorecurso as
with recurso_lancamento as (
  select o201_sequencial,
         c70_codlan,
         c70_anousu,
         (select c130_orctiporec
           from conlancamrecurso
          where conlancamrecurso.c130_conlancam = conlancam.c70_codlan limit 1
         ) as recurso,
         o201_complemento
    from conlancam
    join conlancamcomplementorecurso on o201_codlan = c70_codlan
), complemento_recurso_lancamento as (
  select recurso_lancamento.*,
         case
            when c70_anousu < 2021
            then o201_complemento
            else o15_complemento
          end as complemento
    from recurso_lancamento
    join orctiporec on o15_codigo = recurso
) select * from complemento_recurso_lancamento;

delete from conlancamcomplementorecurso
 using w_conlancamcomplementorecurso
 where conlancamcomplementorecurso.o201_sequencial = w_conlancamcomplementorecurso.o201_sequencial;

insert into conlancamcomplementorecurso
select o201_sequencial,
       c70_codlan,
       complemento,
       recurso
 from w_conlancamcomplementorecurso;
SQL
        );

        $this->execute(<<<SQL
with autalizar_recursos as (
select distinct c130_conlancam, o15_complemento, c130_orctiporec
  from conlancamrec
  join conlancamdoc on conlancamdoc.c71_codlan = conlancamrec.c74_codlan
  join conlancamrecurso on conlancamrecurso.c130_conlancam =  conlancamrec.c74_codlan
  join orctiporec on orctiporec.o15_codigo = conlancamrecurso.c130_orctiporec
  left join conlancamcomplementorecurso on o201_codlan = conlancamrec.c74_codlan
 where o201_codlan is null
   and c71_coddoc = 100
   and c74_data > '2021-01-01'
) insert into conlancamcomplementorecurso
select nextval('conlancamcomplementorecurso_o201_sequencial_seq'), c130_conlancam, o15_complemento, c130_orctiporec
  from autalizar_recursos;
SQL
        );

        $this->execute(<<<SQL
alter table contabilidade.conlancamcomplementorecurso
  add constraint conlancamcomplementorecurso_recurso_fk foreign key (o201_orctiporec) references orcamento.orctiporec;

alter table contabilidade.conlancamcomplementorecurso alter column o201_orctiporec set not null;
SQL
        );
    }

    /**
     * Só atualiza os lançamentos de 2021
     */
    private function ajustaTabelaConlancamrecurso()
    {
        /**
         *
         * - Na primeira query, busca os lancamentos com desdobramento na receita
         *
         * - Na segunda query, busca os lançamentos de receitas sem desdobramento onde a fonte de recurso da receita
         * é diferente da fonte de recurso do lançamento
         */
        $this->execute(<<<SQL
create temporary table w_ajustar_lancamentos as
select c70_codlan, c74_codrec, o70_codfon, o70_anousu, o70_codigo as recurso_certo
  from conlancam
  join conlancamcomplementorecurso on o201_codlan = c70_codlan
  join conlancamrec on conlancamrec.c74_codlan = conlancam.c70_codlan
  join orcreceita on (o70_anousu, o70_codrec) = (c74_anousu, c74_codrec)
 where c70_anousu >= 2021
   and exists (
    select 1
      from orcfontesdes
     where o60_codfon = o70_codfon
       and o60_anousu = o70_anousu
  )
union all
select c70_codlan, c74_codrec, o70_codfon, o70_anousu, o70_codigo
  from conlancam
  join conlancamcomplementorecurso on o201_codlan = c70_codlan
  join conlancamrec on conlancamrec.c74_codlan = conlancam.c70_codlan
  join orcreceita on (o70_anousu, o70_codrec) = (c74_anousu, c74_codrec)
  join orctiporec on orctiporec.o15_codigo = orcreceita.o70_codigo
 where c70_anousu >= 2021
   and not exists (
    select 1
      from orcfontesdes
     where o60_codfon = o70_codfon
       and o60_anousu = o70_anousu
  )
   and exists (
    select *
      from conlancamrecurso
      join orctiporec as xrec on xrec.o15_codigo = conlancamrecurso.c130_orctiporec
     where conlancamrecurso.c130_conlancam = c70_codlan
       and xrec.o15_recurso != orctiporec.o15_recurso
);

create temporary table w_ajustar_conlancamrecurso as
select conlancamrecurso.*, recurso_certo
  from conlancamrecurso
  join w_ajustar_lancamentos on c130_conlancam = c70_codlan;

delete from conlancamrecurso
  using w_ajustar_conlancamrecurso
  where conlancamrecurso.c130_sequencial = w_ajustar_conlancamrecurso.c130_sequencial;

insert into conlancamrecurso
  select c130_sequencial,
         c130_conlancam,
         recurso_certo,
         c130_conta,
         c130_anousu,
         c130_natureza
    from w_ajustar_conlancamrecurso;
SQL
        );
    }
}
