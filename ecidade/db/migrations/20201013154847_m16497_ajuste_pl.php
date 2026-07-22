<?php

use Classes\PostgresMigration;

class M16497AjustePl extends PostgresMigration
{

    public function up()
    {
        $this->upEstruturalDotacao();
        $this->upEstruturalReceita();
        $this->upEstruturalReceitaPPA();
        $this->upEstruturalDotacaoPPA();
        $this->upViewSuplementacaoReceita();
        $this->upViewSuplementacaoDespesa();
    }

    public function down()
    {
        $this->downEstruturalDotacao();
        $this->downEstruturalReceita();
        $this->downEstruturalReceitaPPA();
        $this->downEstruturalDotacaoPPA();
        $this->downViewSuplementacaoReceita();
        $this->downViewSuplementacaoDespesa();
    }

    private function upEstruturalDotacao()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturaldotacao(integer,integer)
        returns varchar
        as '
        DECLARE

          ANOUSU ALIAS FOR $1;
          CODDOT ALIAS FOR $2;
          ESTRUTURAL VARCHAR(200);

        BEGIN

          SELECT LPAD(O58_ORGAO,2,0)||''.''||
                 LPAD(O58_UNIDADE,2,0)||''.''||
                 LPAD(O58_FUNCAO,2,0)||''.''||
                 LPAD(O58_SUBFUNCAO,3,0)||''.''||
                 LPAD(O58_PROGRAMA,4,0)||''.''||
                 LPAD(O58_PROJATIV,4,0)||''.''||
                 LPAD(O56_ELEMENTO,13,0)||''.''||
                 o15_recurso
          INTO ESTRUTURAL
          FROM ORCDOTACAO D
               JOIN ORCELEMENTO O ON O.O56_CODELE = D.O58_CODELE
                                       AND O.O56_ANOUSU = D.O58_ANOUSU
               JOIN orctiporec on orctiporec.o15_codigo = D.O58_CODIGO
           WHERE D.O58_ANOUSU = ANOUSU AND D.O58_CODDOT = CODDOT;

          RETURN ESTRUTURAL;

        END;
        ' language 'plpgsql';
SQL
);
    }

    private function downEstruturalDotacao()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturaldotacao(integer,integer)
        returns varchar
        as '
        DECLARE

          ANOUSU ALIAS FOR $1;
          CODDOT ALIAS FOR $2;
          ESTRUTURAL VARCHAR(200);

        BEGIN

        SELECT LPAD(O58_ORGAO,2,0)||''.''||
                 LPAD(O58_UNIDADE,2,0)||''.''||
                 LPAD(O58_FUNCAO,2,0)||''.''||
                 LPAD(O58_SUBFUNCAO,3,0)||''.''||
                 LPAD(O58_PROGRAMA,4,0)||''.''||
             LPAD(O58_PROJATIV,4,0)||''.''||
                 LPAD(O56_ELEMENTO,13,0)||''.''||
                 LPAD(O58_CODIGO,4,0)
          INTO ESTRUTURAL
          FROM ORCDOTACAO D
               INNER JOIN ORCELEMENTO O ON O.O56_CODELE = D.O58_CODELE
                                       AND O.O56_ANOUSU = D.O58_ANOUSU
           WHERE D.O58_ANOUSU = ANOUSU AND D.O58_CODDOT = CODDOT;
        END;
        ' language 'plpgsql';
SQL
        );
    }

    private function upEstruturalReceita()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturalreceita(integer,integer)
        returns varchar
        as '
        DECLARE

          ANOUSU ALIAS FOR $1;
          CODREC ALIAS FOR $2;
          ESTRUTURAL VARCHAR(200);

        BEGIN

          SELECT SUBSTR(O57_FONTE,1,1)||''.''||
                 SUBSTR(O57_FONTE,2,1)||''.''||
                 SUBSTR(O57_FONTE,3,1)||''.''||
                 SUBSTR(O57_FONTE,4,1)||''.''||
                 SUBSTR(O57_FONTE,5,1)||''.''||
                 SUBSTR(O57_FONTE,6,2)||''.''||
                 SUBSTR(O57_FONTE,8,2)||''.''||
                 SUBSTR(O57_FONTE,10,2)||''.''||
                 SUBSTR(O57_FONTE,12,2)||''.''||
                 SUBSTR(O57_FONTE,14,2)||''.''||
                 o15_recurso
          INTO ESTRUTURAL
          FROM ORCRECEITA R
               INNER JOIN ORCFONTES O ON O.O57_CODFON = R.O70_CODFON AND O.O57_ANOUSU = R.O70_ANOUSU
               INNER JOIN orctiporec TR ON TR.o15_codigo = R.o70_codigo
          WHERE R.O70_ANOUSU = ANOUSU AND R.O70_CODREC = CODREC;

        RETURN ESTRUTURAL;

        END;
        ' language 'plpgsql';
SQL
);
    }

    private function downEstruturalReceita()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturalreceita(integer,integer)
        returns varchar
        as '
        DECLARE

          ANOUSU ALIAS FOR $1;
          CODREC ALIAS FOR $2;
          ESTRUTURAL VARCHAR(200);

        BEGIN

          SELECT SUBSTR(O57_FONTE,1,1)||''.''||
                 SUBSTR(O57_FONTE,2,1)||''.''||
                 SUBSTR(O57_FONTE,3,1)||''.''||
                 SUBSTR(O57_FONTE,4,1)||''.''||
                 SUBSTR(O57_FONTE,5,1)||''.''||
                 SUBSTR(O57_FONTE,6,2)||''.''||
                 SUBSTR(O57_FONTE,8,2)||''.''||
                 SUBSTR(O57_FONTE,10,2)||''.''||
                 SUBSTR(O57_FONTE,12,2)||''.''||
                 SUBSTR(O57_FONTE,14,2)||''.''||
                 LPAD(o15_loaespecificacao::int,4,0)
          INTO ESTRUTURAL
          FROM ORCRECEITA R
               INNER JOIN ORCFONTES O ON O.O57_CODFON = R.O70_CODFON AND O.O57_ANOUSU = R.O70_ANOUSU
               INNER JOIN orctiporec TR ON TR.o15_codigo = R.o70_codigo
          WHERE R.O70_ANOUSU = ANOUSU AND R.O70_CODREC = CODREC;

        RETURN ESTRUTURAL;

        END;
        ' language 'plpgsql';
SQL
        );
    }

    private function upEstruturalReceitaPPA()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturalreceitappa(integer,integer,integer)
        returns varchar
        as $$
        DECLARE

          ANOUSU      ALIAS FOR $1;
          CODREC      ALIAS FOR $2;
          instituicao ALIAS FOR $3;
          ESTRUTURAL VARCHAR(200);

        BEGIN

          SELECT SUBSTR(O57_FONTE,1,1)||'.'||
                 SUBSTR(O57_FONTE,2,1)||'.'||
                 SUBSTR(O57_FONTE,3,1)||'.'||
                 SUBSTR(O57_FONTE,4,1)||'.'||
                 SUBSTR(O57_FONTE,5,1)||'.'||
                 SUBSTR(O57_FONTE,6,2)||'.'||
                 SUBSTR(O57_FONTE,8,2)||'.'||
                 SUBSTR(O57_FONTE,10,2)||'.'||
                 SUBSTR(O57_FONTE,12,2)||'.'||
                 SUBSTR(O57_FONTE,14,2)||'.'||
                 o15_recurso
          INTO ESTRUTURAL
          FROM ppaestimativareceita R
               INNER JOIN ORCFONTES O  ON O.O57_CODFON = R.o06_codrec AND O.O57_ANOUSU = R.o06_anousu
               INNER JOIN conplanoreduz  ON R.o06_codrec = c61_codcon AND c61_anousu     = R.o06_anousu
               JOIN orctiporec on orctiporec.o15_codigo = c61_CODIGO
          WHERE R.o06_anousu = ANOUSU
            AND R.o06_sequencial = CODREC
            and c61_instit   = instituicao;

          RETURN ESTRUTURAL;

        END;
        $$ language 'plpgsql';
SQL
);
    }

    private function downEstruturalReceitaPPA()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturalreceitappa(integer,integer,integer)
        returns varchar
        as $$
        DECLARE

          ANOUSU      ALIAS FOR $1;
          CODREC      ALIAS FOR $2;
          instituicao ALIAS FOR $3;
          ESTRUTURAL VARCHAR(200);

        BEGIN

          SELECT SUBSTR(O57_FONTE,1,1)||'.'||
                 SUBSTR(O57_FONTE,2,1)||'.'||
                 SUBSTR(O57_FONTE,3,1)||'.'||
                 SUBSTR(O57_FONTE,4,1)||'.'||
                 SUBSTR(O57_FONTE,5,1)||'.'||
                 SUBSTR(O57_FONTE,6,2)||'.'||
                 SUBSTR(O57_FONTE,8,2)||'.'||
                 SUBSTR(O57_FONTE,10,2)||'.'||
                 SUBSTR(O57_FONTE,12,2)||'.'||
                 SUBSTR(O57_FONTE,14,2)||'.'||
                 LPAD(c61_CODIGO,4,0)
          INTO ESTRUTURAL
          FROM ppaestimativareceita R
               INNER JOIN ORCFONTES O  ON O.O57_CODFON = R.o06_codrec AND O.O57_ANOUSU = R.o06_anousu
               INNER JOIN conplanoreduz  ON R.o06_codrec = c61_codcon AND c61_anousu     = R.o06_anousu
          WHERE R.o06_anousu = ANOUSU
            AND R.o06_sequencial = CODREC
            and c61_instit   = instituicao;

          RETURN ESTRUTURAL;

        END;
        $$ language 'plpgsql';
SQL
        );
    }

    private function upEstruturalDotacaoPPA()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturaldotacaoppa(integer,integer)
        returns varchar
        as
        $$
        DECLARE

          ANOUSU ALIAS FOR $1;
          CODDOT ALIAS FOR $2;
          ESTRUTURAL VARCHAR(200);

        BEGIN

          SELECT LPAD(o08_ORGAO,2,0)||'.'||
                 LPAD(o08_UNIDADE,2,0)||'.'||
                 LPAD(o08_FUNCAO,2,0)||'.'||
                 LPAD(o08_SUBFUNCAO,3,0)||'.'||
                 LPAD(o08_PROGRAMA,4,0)||'.'||
                 LPAD(o08_PROJATIV,4,0)||'.'||
                 LPAD(O56_ELEMENTO,13,0)||'.'||
                 o15_recurso ||'.'||
                 LPAD(o08_localizadorgastos,4,0)||'.'||
                 REPLACE(o08_concarpeculiar,'.','')
          INTO ESTRUTURAL
          FROM ppaDOTACAO D
               JOIN ORCELEMENTO O ON O.O56_CODELE = D.o08_elemento
               JOIN orctiporec on orctiporec.o15_codigo = D.o08_recurso
          WHERE D.o08_ANO = ANOUSU AND D.o08_sequencial = CODDOT;

          RETURN ESTRUTURAL;

        END;
        $$
        language 'plpgsql';
SQL
        );
    }

    private function downEstruturalDotacaoPPA()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturaldotacaoppa(integer,integer)
        returns varchar
        as
        $$
        DECLARE

          ANOUSU ALIAS FOR $1;
          CODDOT ALIAS FOR $2;
          ESTRUTURAL VARCHAR(200);

        BEGIN

          SELECT LPAD(o08_ORGAO,2,0)||'.'||
                 LPAD(o08_UNIDADE,2,0)||'.'||
                 LPAD(o08_FUNCAO,2,0)||'.'||
                 LPAD(o08_SUBFUNCAO,3,0)||'.'||
                 LPAD(o08_PROGRAMA,4,0)||'.'||
                 LPAD(o08_PROJATIV,4,0)||'.'||
                 LPAD(O56_ELEMENTO,13,0)||'.'||
                 LPAD(o08_recurso,4,0)||'.'||
                 LPAD(o08_localizadorgastos,4,0)||'.'||
                 REPLACE(o08_concarpeculiar,'.','')
          INTO ESTRUTURAL
          FROM ppaDOTACAO D
               INNER JOIN ORCELEMENTO O ON O.O56_CODELE = D.o08_elemento
          WHERE D.o08_ANO = ANOUSU AND D.o08_sequencial = CODDOT;

          RETURN ESTRUTURAL;

        END;
        $$
        language 'plpgsql';
SQL
        );
    }

    private function upViewSuplementacaoReceita()
    {
        $this->execute(<<<SQL
        drop view v_suplementacao_receita;
        create or replace view v_suplementacao_receita as
        select orcsuplem.o46_codlei  as sequencial_projeto,
               orcreceita.o70_codrec as receita,
               orcsuplemrec.o85_valor  as valor,
               orcreceita.o70_valor  as valor_orcado_receita,
               orcfontes.o57_descr   as descricao,
               orcfontes.o57_fonte   as estrutural,
               orcreceita.o70_anousu as ano,
               orctiporec.o15_recurso as recurso,
               orctiporec.o15_descr  as descricao_recurso
          from orcsuplemrec
               inner join orcsuplem  on orcsuplem.o46_codsup   = orcsuplemrec.o85_codsup
               inner join orcprojeto on orcprojeto.o39_codproj = orcsuplem.o46_codlei
               inner join orcreceita on orcreceita.o70_anousu  = orcsuplemrec.o85_anousu
                                    and orcreceita.o70_codrec  = orcsuplemrec.o85_codrec
               inner join orcfontes  on orcfontes.o57_codfon   = orcreceita.o70_codfon
                                    and orcfontes.o57_anousu   = orcreceita.o70_anousu
               inner join orctiporec on orctiporec.o15_codigo  = orcreceita.o70_codigo;
SQL
);
    }

    private function downViewSuplementacaoReceita()
    {
        $this->execute(<<<SQL
        drop view v_suplementacao_receita;
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
SQL
);
    }

    private function upViewSuplementacaoDespesa()
    {
        $this->execute(<<< SQL
        drop view v_suplementacao_despesa;
        create or replace view v_suplementacao_despesa as
         select orcsuplem.o46_codlei       as sequencial_projeto,
                orcorgao.o40_orgao         as orgao,
                orcorgao.o40_descr         as descricao_orgao,
                orcunidade.o41_unidade     as unidade,
                orcunidade.o41_descr       as descricao_unidade,
                orcfuncao.o52_funcao       as funcao,
                orcfuncao.o52_descr        as descricao_funcao,
                orcsubfuncao.o53_subfuncao as subfuncao,
                orcsubfuncao.o53_descr     as descricao_subfuncao,
                orcprograma.o54_programa   as programa,
                orcprograma.o54_descr      as descricao_programa,
                orcprojativ.o55_projativ   as projativ,
                orcprojativ.o55_descr      as descricao_projativ,
                substr(fc_estruturaldespesa(orcelemento.o56_elemento),3,10) as elemento_despesa,
                orcelemento.o56_elemento   as elemento,
                orcelemento.o56_descr      as descricao_elemento,
                orctiporec.o15_recurso     as recurso,
                orcdotacao.o58_coddot      as dotacao,
                orcsuplemval.o47_valor     as valor_suplementacao
           from orcsuplemval
                inner join orcdotacao   on orcdotacao.o58_anousu      = orcsuplemval.o47_anousu
                                       and orcdotacao.o58_coddot      = orcsuplemval.o47_coddot
                inner join orcorgao     on orcorgao.o40_anousu        = orcdotacao.o58_anousu
                                       and orcorgao.o40_orgao         = orcdotacao.o58_orgao
                inner join orcunidade   on orcunidade.o41_anousu      = orcdotacao.o58_anousu
                                       and orcunidade.o41_orgao       = orcdotacao.o58_orgao
                                       and orcunidade.o41_unidade     = orcdotacao.o58_unidade
                inner join orcprograma  on orcprograma.o54_anousu     = orcdotacao.o58_anousu
                                       and orcprograma.o54_programa   = orcdotacao.o58_programa
                inner join orcprojativ  on orcprojativ.o55_anousu     = orcdotacao.o58_anousu
                                       and orcprojativ.o55_projativ   = orcdotacao.o58_projativ
                inner join orcelemento  on orcelemento.o56_codele     = orcdotacao.o58_codele
                                       and orcelemento.o56_anousu     = orcdotacao.o58_anousu
                inner join orctiporec   on orctiporec.o15_codigo      = orcdotacao.o58_codigo
                inner join orcfuncao    on orcfuncao.o52_funcao       = orcdotacao.o58_funcao
                inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
                inner join orcsuplem    on orcsuplem.o46_codsup       = orcsuplemval.o47_codsup
                inner join orcprojeto   on orcprojeto.o39_codproj     = orcsuplem.o46_codlei;
SQL
        );
    }

    private function downViewSuplementacaoDespesa()
    {
        $this->execute(<<< SQL
        drop view v_suplementacao_despesa;
        create or replace view v_suplementacao_despesa as
         select orcsuplem.o46_codlei       as sequencial_projeto,
                orcorgao.o40_orgao         as orgao,
                orcorgao.o40_descr         as descricao_orgao,
                orcunidade.o41_unidade     as unidade,
                orcunidade.o41_descr       as descricao_unidade,
                orcfuncao.o52_funcao       as funcao,
                orcfuncao.o52_descr        as descricao_funcao,
                orcsubfuncao.o53_subfuncao as subfuncao,
                orcsubfuncao.o53_descr     as descricao_subfuncao,
                orcprograma.o54_programa   as programa,
                orcprograma.o54_descr      as descricao_programa,
                orcprojativ.o55_projativ   as projativ,
                orcprojativ.o55_descr      as descricao_projativ,
                substr(fc_estruturaldespesa(orcelemento.o56_elemento),3,10) as elemento_despesa,
                orcelemento.o56_elemento   as elemento,
                orcelemento.o56_descr      as descricao_elemento,
                orctiporec.o15_codigo     as recurso,
                orcdotacao.o58_coddot      as dotacao,
                orcsuplemval.o47_valor     as valor_suplementacao
           from orcsuplemval
                inner join orcdotacao   on orcdotacao.o58_anousu      = orcsuplemval.o47_anousu
                                       and orcdotacao.o58_coddot      = orcsuplemval.o47_coddot
                inner join orcorgao     on orcorgao.o40_anousu        = orcdotacao.o58_anousu
                                       and orcorgao.o40_orgao         = orcdotacao.o58_orgao
                inner join orcunidade   on orcunidade.o41_anousu      = orcdotacao.o58_anousu
                                       and orcunidade.o41_orgao       = orcdotacao.o58_orgao
                                       and orcunidade.o41_unidade     = orcdotacao.o58_unidade
                inner join orcprograma  on orcprograma.o54_anousu     = orcdotacao.o58_anousu
                                       and orcprograma.o54_programa   = orcdotacao.o58_programa
                inner join orcprojativ  on orcprojativ.o55_anousu     = orcdotacao.o58_anousu
                                       and orcprojativ.o55_projativ   = orcdotacao.o58_projativ
                inner join orcelemento  on orcelemento.o56_codele     = orcdotacao.o58_codele
                                       and orcelemento.o56_anousu     = orcdotacao.o58_anousu
                inner join orctiporec   on orctiporec.o15_codigo      = orcdotacao.o58_codigo
                inner join orcfuncao    on orcfuncao.o52_funcao       = orcdotacao.o58_funcao
                inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
                inner join orcsuplem    on orcsuplem.o46_codsup       = orcsuplemval.o47_codsup
                inner join orcprojeto   on orcprojeto.o39_codproj     = orcsuplem.o46_codlei;
SQL
        );
    }
}
