<?php

use Classes\PostgresMigration;

class M13822TriggersConplanoexesaldo extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
create or replace function fc_atualiza_saldo_continuo(integer, integer, integer)
returns boolean
as $$
declare 

   contaDebito  alias for $1;
   contaCredito alias for $2;
   anoUsu       alias for $3;

   saldofinal   numeric(15,2);

begin
    
    /**
      atualiza o saldo inicial do exercicio posterior da conta a credito
     */
    select round(sum(c68_debito) - sum(c68_credito), 2)
      into saldofinal
      from contabilidade.conplanoexesaldo
     where c68_reduz  = contaCredito
       and c68_anousu = anoUsu;

    update contabilidade.conplanoexe
       set c62_vlrcre = case when saldofinal < 0 then abs(saldofinal) else 0 end,
           c62_vlrdeb = case when saldofinal > 0 then abs(saldofinal) else 0 end
     where c62_reduz  = contaCredito
       and c62_anousu > anoUsu ;

    /**
      atualiza o saldo inicial do exercicio posterior da conta a debito
     */
    select round(sum(c68_debito) - sum(c68_credito), 2)
      into saldofinal
      from contabilidade.conplanoexesaldo
     where c68_reduz  = contaDebito
       and c68_anousu = anoUsu;

    update contabilidade.conplanoexe
       set c62_vlrcre =  case when saldofinal < 0 then abs(saldofinal) else 0 end,
           c62_vlrdeb =  case when saldofinal > 0 then abs(saldofinal) else 0 end
    where c62_reduz  = contaDebito
      and c62_anousu > anoUsu ;

    return true;
       
end;
$$ language 'plpgsql';
SQL;
        $this->execute($sql);

        $sql = <<<SQL

--drop function fc_conplanoexesaldo_inc();
CREATE OR REPLACE FUNCTION fc_conplanoexesaldo_inc()
RETURNS TRIGGER
AS $$
DECLARE 

  CREDITO 	FLOAT8;

  lAtualizouSaldo boolean;

BEGIN
    
    SELECT C68_DEBITO
      INTO CREDITO
      FROM CONPLANOEXESALDO
     WHERE C68_ANOUSU = NEW.C69_ANOUSU
       AND C68_REDUZ  = NEW.C69_DEBITO
       AND C68_MES    = TO_CHAR(NEW.C69_DATA,'MM')::INTEGER  ;
	  
    IF CREDITO IS NULL THEN
  
       INSERT INTO CONPLANOEXESALDO (C68_ANOUSU,C68_REDUZ,C68_MES,C68_DEBITO,C68_CREDITO)
                          VALUES    (NEW.C69_ANOUSU,NEW.C69_DEBITO,TO_CHAR(NEW.C69_DATA,'MM')::INTEGER,NEW.C69_VALOR,0::FLOAT8);
    ELSE

       UPDATE CONPLANOEXESALDO SET C68_DEBITO  = ROUND(C68_DEBITO + NEW.C69_VALOR,2)::FLOAT8
       WHERE C68_ANOUSU = NEW.C69_ANOUSU AND
             C68_REDUZ  = NEW.C69_DEBITO AND
             C68_MES    = TO_CHAR(NEW.C69_DATA,'MM')::INTEGER  ;

    END IF;

    SELECT C68_CREDITO
    INTO CREDITO
    FROM CONPLANOEXESALDO
    WHERE C68_ANOUSU = NEW.C69_ANOUSU AND
          C68_REDUZ  = NEW.C69_CREDITO AND
          C68_MES    = TO_CHAR(NEW.C69_DATA,'MM')::INTEGER  ;
	  
    IF CREDITO IS NULL THEN
  
       INSERT INTO CONPLANOEXESALDO (C68_ANOUSU,C68_REDUZ,C68_MES,C68_DEBITO,C68_CREDITO)
                          VALUES    (NEW.C69_ANOUSU,NEW.C69_CREDITO,TO_CHAR(NEW.C69_DATA,'MM')::INTEGER,0::FLOAT8,NEW.C69_VALOR);
    ELSE

       UPDATE CONPLANOEXESALDO SET C68_CREDITO = ROUND(C68_CREDITO + NEW.C69_VALOR,2)::FLOAT8
       WHERE C68_ANOUSU = NEW.C69_ANOUSU AND
             C68_REDUZ  = NEW.C69_CREDITO AND
             C68_MES    = TO_CHAR(NEW.C69_DATA,'MM')::INTEGER  ;

    END IF;

    /**
      atualiza o saldo inicial do exercicio posterior da conta a debito e credito
     */
    perform
       from conplano
            inner join conplanoreduz cr on cr.c61_codcon = conplano.c60_codcon
                                       and cr.c61_anousu = conplano.c60_anousu
      where c60_anousu = new.c69_anousu
        and c60_saldocontinuo is true
        and ( cr.c61_reduz = NEW.c69_debito or cr.c61_reduz = NEW.c69_credito );

    if found then
      lAtualizouSaldo := fc_atualiza_saldo_continuo (NEW.c69_debito, NEW.c69_credito, NEW.c69_anousu);
      if not lAtualizouSaldo then
         raise exception 'Ocorreu algo inexperado: Nao foi possivel atualizar o saldo das contas de saldo continuo.';
      end if;
    end if;

    RETURN NEW;

END;
$$ LANGUAGE 'plpgsql';

SQL;
        $this->execute($sql);

    }

    public function down()
    {

        $sql = <<<SQL
drop function fc_atualiza_saldo_continuo(integer, integer, integer);
SQL;
        $this->execute($sql);

        $sql = <<<SQL

--drop function fc_conplanoexesaldo_inc();
CREATE OR REPLACE FUNCTION fc_conplanoexesaldo_inc()
RETURNS TRIGGER
AS $$
DECLARE 

  CREDITO 	FLOAT8;

  lAtualizouSaldo boolean;

BEGIN
    
    SELECT C68_DEBITO
      INTO CREDITO
      FROM CONPLANOEXESALDO
     WHERE C68_ANOUSU = NEW.C69_ANOUSU
       AND C68_REDUZ  = NEW.C69_DEBITO
       AND C68_MES    = TO_CHAR(NEW.C69_DATA,'MM')::INTEGER  ;
	  
    IF CREDITO IS NULL THEN
  
       INSERT INTO CONPLANOEXESALDO (C68_ANOUSU,C68_REDUZ,C68_MES,C68_DEBITO,C68_CREDITO)
                          VALUES    (NEW.C69_ANOUSU,NEW.C69_DEBITO,TO_CHAR(NEW.C69_DATA,'MM')::INTEGER,NEW.C69_VALOR,0::FLOAT8);
    ELSE

       UPDATE CONPLANOEXESALDO SET C68_DEBITO  = ROUND(C68_DEBITO + NEW.C69_VALOR,2)::FLOAT8
       WHERE C68_ANOUSU = NEW.C69_ANOUSU AND
             C68_REDUZ  = NEW.C69_DEBITO AND
             C68_MES    = TO_CHAR(NEW.C69_DATA,'MM')::INTEGER  ;

    END IF;

    SELECT C68_CREDITO
    INTO CREDITO
    FROM CONPLANOEXESALDO
    WHERE C68_ANOUSU = NEW.C69_ANOUSU AND
          C68_REDUZ  = NEW.C69_CREDITO AND
          C68_MES    = TO_CHAR(NEW.C69_DATA,'MM')::INTEGER  ;
	  
    IF CREDITO IS NULL THEN
  
       INSERT INTO CONPLANOEXESALDO (C68_ANOUSU,C68_REDUZ,C68_MES,C68_DEBITO,C68_CREDITO)
                          VALUES    (NEW.C69_ANOUSU,NEW.C69_CREDITO,TO_CHAR(NEW.C69_DATA,'MM')::INTEGER,0::FLOAT8,NEW.C69_VALOR);
    ELSE

       UPDATE CONPLANOEXESALDO SET C68_CREDITO = ROUND(C68_CREDITO + NEW.C69_VALOR,2)::FLOAT8
       WHERE C68_ANOUSU = NEW.C69_ANOUSU AND
             C68_REDUZ  = NEW.C69_CREDITO AND
             C68_MES    = TO_CHAR(NEW.C69_DATA,'MM')::INTEGER  ;

    END IF;


    RETURN NEW;

END;
$$ LANGUAGE 'plpgsql';

SQL;
        $this->execute($sql);




    }



}
