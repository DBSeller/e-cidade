<?php

use Classes\PostgresMigration;

class M12934AlteracaoEstruturaRecurso extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP


drop view if exists v_suplementacao_receita;
drop view if exists vs_planocontas;
drop view if exists vs_planosistema;

alter table orctiporec alter column o15_descr type varchar(100);

create or replace view v_suplementacao_receita as
select orcsuplem.o46_codlei  as sequencial_projeto,
       orcreceita.o70_codrec as receita,
       orcsuplemrec.o85_valor  as valor,
       orcreceita.o70_valor  as valor_orcado_receita,
       orcfontes.o57_descr   as descricao,
       orcfontes.o57_fonte   as estrutural,
       orcreceita.o70_anousu as ano,
       orctiporec.o15_codigo as recurso,
       orctiporec.o15_descr  as descricao_recurso
  from orcsuplemrec
       inner join orcsuplem  on orcsuplem.o46_codsup   = orcsuplemrec.o85_codsup
       inner join orcprojeto on orcprojeto.o39_codproj = orcsuplem.o46_codlei
       inner join orcreceita on orcreceita.o70_anousu  = orcsuplemrec.o85_anousu
                            and orcreceita.o70_codrec  = orcsuplemrec.o85_codrec
       inner join orcfontes  on orcfontes.o57_codfon   = orcreceita.o70_codfon
                            and orcfontes.o57_anousu   = orcreceita.o70_anousu
       inner join orctiporec on orctiporec.o15_codigo  = orcreceita.o70_codigo;
       

create view vs_planocontas as
        SELECT *
        FROM CONPLANO
     	 INNER JOIN CONSISTEMA             ON C60_CODSIS = C52_CODSIS
   	     INNER JOIN CONCLASS               ON C60_CODCLA = C51_CODCLA
			 LEFT JOIN CONPLANOREDUZ           ON C60_CODCON = C61_CODCON and C60_ANOUSU =C61_ANOUSU
			 LEFT  JOIN CONPLANOCONTA          ON c63_ANOUSU = C60_ANOUSU
																				and C61_REDUZ = C63_REDUZ
  	     LEFT JOIN CONPLANOEXE             ON C61_ANOUSU = C62_ANOUSU and C61_REDUZ  = C62_REDUZ
	     LEFT JOIN ORCTIPOREC              ON C61_CODIGO = O15_CODIGO
	     LEFT JOIN DB_CONFIG               ON CODIGO     = CONPLANOREDUZ.C61_INSTIT;       
       


create view vs_planosistema as
        SELECT *
        FROM CONPLANOSIS
	     INNER JOIN CONPLANOREF             ON C65_CODPLA = C64_CODPLA
	     INNER JOIN CONPLANO                ON C60_CODCON = C65_CODCON
	     INNER JOIN CONPLANOREDUZ           ON C61_CODCON = C60_CODCON
  	     INNER JOIN CONPLANOEXE        	ON C61_REDUZ  = C62_REDUZ
	     INNER JOIN ORCTIPOREC              ON C61_CODIGO = O15_CODIGO
	     LEFT OUTER JOIN CONPLANOCONTA      ON C60_CODCON = C63_CODCON;

SQL_UP
);
    }

    public function down()
    {

    }
}
