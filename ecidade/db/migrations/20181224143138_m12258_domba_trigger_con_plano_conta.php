<?php

use Classes\PostgresMigration;

class M12258DombaTriggerConPlanoConta extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

    
    
    -- não permite exclusão do conplano contas que estejam na terouraria
	-- conplanoconta(c63_codcon,c63_anousu)--> conplanoreduz(c61_codcon,c60_anousu)-->saltes(k13_reduz)    
	
CREATE OR REPLACE FUNCTION fc_conplanoconta_del()
RETURNS TRIGGER
AS $$
DECLARE 

  CODCON INTEGER;
  dtSessao date;
BEGIN

      
    dtSessao = cast(fc_getsession('DB_datausu') as date);
    if (dtSessao is null) then 
      raise exception 'Data da sessão não informada';
    end if;
    SELECT C61_CODCON
    INTO CODCON
    FROM CONPLANOREDUZ
         INNER JOIN SALTES ON K13_REDUZ= C61_REDUZ 
	                      and ((k13_limite > dtSessao and extract(year from k13_limite) = C61_ANOUSU ) 
                                                        or k13_limite is null)
         
    WHERE C61_CODCON  = OLD.C63_CODCON
      AND C61_reduz  = OLD.C63_reduz
      and C61_ANOUSU  = OLD.C63_ANOUSU;


    -- raise notice '' valores olds  %,% '',OLD.C63_CODCON,OLD.C63_ANOUSU;
   
    IF CODCON IS NOT NULL THEN

       RAISE EXCEPTION 'Exclusão não permitida, conta existente na tesouraria !%  % % ', codcon, dtsessao, OLD.C63_ANOUSU;  			   
    
    END IF;


    RETURN OLD;
       
END;
$$
LANGUAGE 'plpgsql';


DROP TRIGGER   "tg_conplanoconta_del" on conplanoconta;
CREATE TRIGGER "tg_conplanoconta_del" before delete
ON "conplanoconta" FOR EACH ROW EXECUTE PROCEDURE "fc_conplanoconta_del" () ;



SQL_UP
);
    }

    public function down()
    {
    }
}
