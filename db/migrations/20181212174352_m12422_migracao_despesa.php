<?php

use Classes\PostgresMigration;

class M12422MigracaoDespesa extends PostgresMigration
{

    public function up(){


        $this->execute("drop index if exists orcdotacao_oufspae_in");

        $ppaVersao = 17;
        $ano       = 2019;

        $rsInstituicao = $this->query("select db21_codcli from configuracoes.db_config where db21_codcli = 7107");
        if ($rsInstituicao->rowCount() == 0) {;
            return;
        }

        $sSql = <<<SQL
drop sequence if exists orcdotacao_seq; 
delete
  from ppaintegracaodespesa
 where o121_ppaintegracao in ( select o123_sequencial
                                 from ppaintegracao
                                where o123_ppaversao = {$ppaVersao} );
delete
  from ppadotacaoorcdotacao
 where o19_ppadotacao in (select o08_sequencial 
                            from ppadotacao 
                           where o08_ppaversao = {$ppaVersao});

delete
  from ppadotacao
 where o08_ppaversao = {$ppaVersao};

delete 
  from ppaestimativadespesa 
 where o07_anousu = {$ano};

delete
  from ppaestimativa
 where o05_ppaversao = {$ppaVersao}
   and not exists (select 1 
                      from ppaestimativareceita 
                     where o06_ppaestimativa = o05_sequencial )
   and not exists (select 1 
                      from ppaestimativadespesa 
                     where o07_ppaestimativa = o05_sequencial ) ; 
delete
  from planoorcamentariolinhapacto;

delete
  from orcdotacaoplanoorcamentario;

delete 
  from orcreserva 
 where o80_anousu = {$ano} 
   and o80_coddot in (select o58_coddot 
                        from orcdotacao 
                       where o58_anousu = {$ano});                                                                                                                    
delete
  from orcdotacao
 where o58_anousu = {$ano};

create table w_dotacao_nova as
select array_accum(c333_sequencial) as c333_sequencial,
       0::integer as o58_coddot,
       c333_orcorgao,
       c333_orcunidade,
       c333_orcfuncao,
       c333_orcsubfuncao,
       c333_orcprograma,
       c333_orcprojativ,
       c333_ppasubtitulolocalizadorgasto,
       c333_conplanoorcamento,
       c333_esferaorcamentaria,
       c333_planoorcamentario,
       o40_instit,
       c333_ano,
       orctiporec.o15_codigo,
       count(*),
       sum(c333_previsao) as previsao
  from previsaodespesa
       inner join orcorgao o on o.o40_anousu = c333_ano
                            and o.o40_orgao  = c333_orcorgao
       inner join orctiporec on o15_loaidentificadoruso = c333_identificadoruso
                            and o15_loatipo = c333_tipodetalhamento::integer
                            and o15_loagrupo = c333_grupofonterecursos::integer
                            and o15_loaespecificacao = c333_especificacaofonte
 group by c333_orcorgao,
           c333_orcunidade,
           o58_coddot,
           c333_orcfuncao,
           c333_orcsubfuncao,
           c333_orcprograma,
           c333_orcprojativ,
           c333_ppasubtitulolocalizadorgasto,
           c333_conplanoorcamento,
           c333_esferaorcamentaria,
           c333_planoorcamentario,
           o40_instit,
           c333_ano,
           orctiporec.o15_codigo
 order by count desc;
create sequence orcdotacao_seq;
select setval('orcdotacao_seq', (select max(o58_coddot) from orcdotacao));

insert into orcdotacao ( o58_anousu,
                         o58_coddot,
                         o58_orgao,
                         o58_unidade,
                         o58_subfuncao,
                         o58_projativ,
                         o58_codigo,
                         o58_funcao,
                         o58_programa,
                         o58_codele,
                         o58_valor,
                         o58_instit,
                         o58_localizadorgastos,
                         o58_datacriacao,
                         o58_concarpeculiar,
                         o58_esferaorcamentaria )

select c333_ano,
       nextval('orcdotacao_seq') as coddot,
       c333_orcorgao,
       c333_orcunidade,
       c333_orcsubfuncao,
       c333_orcprojativ,
       o15_codigo,
       c333_orcfuncao,
       c333_orcprograma,
       c333_conplanoorcamento,
       previsao,
       o40_instit,
       c333_ppasubtitulolocalizadorgasto,
       current_date as datacricao,
       '000' as cp,
       c333_esferaorcamentaria
  from w_dotacao_nova;
   update w_dotacao_nova
      set o58_coddot = od.o58_coddot
     from orcdotacao od
     where od.o58_anousu     = c333_ano
       and od.o58_orgao      = c333_orcorgao
       and od.o58_unidade    = c333_orcunidade
       and od.o58_funcao     = c333_orcfuncao
       and od.o58_subfuncao  = c333_orcsubfuncao
       and od.o58_projativ   = c333_orcprojativ
       and od.o58_programa   = c333_orcprograma
       and od.o58_codele     = c333_conplanoorcamento
       and od.o58_codigo     = o15_codigo
       and od.o58_localizadorgastos = c333_ppasubtitulolocalizadorgasto
       and od.o58_esferaorcamentaria = c333_esferaorcamentaria
       and od.o58_concarpeculiar     = '000';
update orcparametro
  set o50_coddot = ( select currval('orcdotacao_seq') )
where o50_anousu = {$ano};

create table w_orcdotacaoplanoorcamentario as
select 0::integer as sequencial,
       o58_coddot,
       {$ano} as anousu,
       c55_titulo,
       array_accum(c55_sequencial) as previsaodespesaplano,
       sum(c55_valor) as c55_valor
  from w_dotacao_nova
       inner join previsaodespesaplano on c55_previsaodespesa = any(c333_sequencial)
 group by 1,2,3,4;
update w_orcdotacaoplanoorcamentario set sequencial = nextval('previsaodespesaplano_c55_sequencial_seq');
insert into orcdotacaoplanoorcamentario ( o155_sequencial,
                                          o155_coddot,
                                          o155_anousu,
                                          o155_titulo,
                                          o155_valor )
                                   select sequencial,
                                          o58_coddot,
                                          anousu,
                                          c55_titulo,
                                          c55_valor
                                     from w_orcdotacaoplanoorcamentario;
insert into planoorcamentariolinhapacto ( o156_sequencial,
                                          o156_linhaspacto,
                                          o156_orcdotacaoplanoorcamentario,
                                          o156_valor )
select nextval('planoorcamentariolinhapacto_o156_sequencial_seq') as o156_sequencial,
       c41_linhaspacto as o156_linhaspacto,
       sequencial as o156_orcdotacaoplanoorcamentario,
       c41_valorlinha  as o156_valor
  from previsaodespesalinhaspacto
       inner join w_orcdotacaoplanoorcamentario on c41_previsaodespesaplano = any(previsaodespesaplano) ;
drop sequence orcdotacao_seq;
SQL;
        $this->execute( $sSql );
    }

    public function down()
    {


    }


}
