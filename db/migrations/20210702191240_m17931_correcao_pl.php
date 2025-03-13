<?php

use Classes\PostgresMigration;

class M17931CorrecaoPl extends PostgresMigration
{
    public function change()
    {
        $this->saldoReceitaPorEstrutural();
        $this->saldoReceitaPorCodfon();
    }

    private function saldoReceitaPorEstrutural()
    {
        $this->execute(<<<SQL
create or replace function fc_receitasaldo_estrutural(integer, varchar, varchar, integer, date, date)
  returns numeric[]
language plpgsql
as $$
DECLARE

  ANOUSU     ALIAS FOR $1;
  ESTRUTURAL ALIAS FOR $2;
  CP         ALIAS FOR $3;
  TIPO       ALIAS FOR $4;
  -- 1 SALDO INICIAL DA RECEITA - ORCAMENTO
  -- 2 SALDO DA RECEITA  MENOS O ARRECADADO
  -- 3 SALDO DA RECEITA  PELA CONTABILIDADE ...
  -- 4 SALDO ACUMULADO POR MES
  DATAUSU	ALIAS FOR $5;
  DATAFIM	ALIAS FOR $6;

  CODREC int;

  retorno numeric[];

BEGIN

  SELECT O70_CODREC
    INTO CODREC
    FROM ORCFONTES
         INNER JOIN ORCRECEITA ON O57_CODFON = O70_CODFON
                             AND O57_ANOUSU = O70_ANOUSU
   WHERE o57_fonte = ESTRUTURAL
     and O57_ANOUSU = ANOUSU
     and o70_concarpeculiar = CP;


  select fc_receitasaldo_array(ANOUSU, CODREC, TIPO, DATAUSU, DATAFIM)
    into retorno;

  return retorno ;
END;
$$;

SQL
);

    }

    private function saldoReceitaPorCodfon()
    {
        $this->execute(<<<SQL
create or replace function fc_receitasaldo_codfon(integer, integer, varchar, integer, date, date)
  returns numeric[]
language plpgsql
as $$
DECLARE

  ANOUSU     ALIAS FOR $1;
  CODFON     ALIAS FOR $2;
  CP         ALIAS FOR $3;
  TIPO       ALIAS FOR $4;
  -- 1 SALDO INICIAL DA RECEITA - ORCAMENTO
  -- 2 SALDO DA RECEITA  MENOS O ARRECADADO
  -- 3 SALDO DA RECEITA  PELA CONTABILIDADE ...
  -- 4 SALDO ACUMULADO POR MES
  DATAUSU   ALIAS FOR $5;
  DATAFIM   ALIAS FOR $6;

  CODREC int;

  retorno numeric[];

BEGIN

  SELECT O70_CODREC
    INTO CODREC
    FROM ORCFONTES
         INNER JOIN ORCRECEITA ON O57_CODFON = O70_CODFON
                             AND O57_ANOUSU = O70_ANOUSU
   WHERE O57_CODFON = CODFON
     and O57_ANOUSU = ANOUSU
     and o70_concarpeculiar = CP;


  select fc_receitasaldo_array(ANOUSU, CODREC, TIPO, DATAUSU, DATAFIM)
    into retorno;

  return retorno ;

END;
$$;

SQL
        );
    }
}
