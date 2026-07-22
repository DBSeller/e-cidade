<?php

use Classes\PostgresMigration;

class M16519ContribuicaoMelhoriasAjustePlFcFracao extends PostgresMigration
{
    public function up()
    {
    $sql_ajusta_funcao = <<<STRING_UP

-- PARAMETROS
-- 1 - ID DO LOTE
-- 2 - EXERCICIO DB_GETSESSION("DB_ANOUSU")
-- 3 - MATRICULA

-- RETORNO 
-- FRACAO

create or replace function fc_fracao(integer,integer,integer)
returns double precision 

AS $$

DECLARE
      bqlini        ALIAS FOR $1;
      anousu        ALIAS FOR $2;
      v_matric      ALIAS FOR $3;
      v_fracao 		FLOAT8;
      v_fracaoCAD	FLOAT8;
      V_matricula	integer;
      V_AREAL 		float8;
      V_AREACALC 	FLOAT8;
BEGIN

   SELECT CASE WHEN J34_AREAL = 0 THEN J34_AREA ELSE J34_AREAL END AS J34_AREAL, J34_TOTCON,J01_MATRIC
   INTO V_AREAL, V_FRACAO, V_MATRICULA
   FROM IPTUBASE 
        INNER JOIN LOTE ON J34_IDBQL = J01_IDBQL
   WHERE J01_IDBQL = BQLINI AND J01_BAIXA IS NULL AND J01_MATRIC = V_MATRIC ;
   IF V_MATRICULA IS NULL THEN
      RETURN '0';
   END IF;
   IF V_AREAL = 0 OR V_AREAL IS NULL THEN
      RETURN '1';
   END IF;
   IF V_FRACAO IS NULL OR V_FRACAO = 0 THEN
      V_FRACAO = 100::float8;
   ELSE
      -- CALCULA FRACAO DO LOTE
      IF V_FRACAO != 0 THEN
         SELECT SUM(J39_AREA)
         INTO V_AREACALC
         FROM IPTUCONSTR
         WHERE J39_MATRIC = V_MATRICULA  
           AND J39_DTDEMO IS NULL
         GROUP BY J39_MATRIC;
         IF V_AREACALC IS NULL OR V_AREACALC = 0 THEN
            V_FRACAO = 100;
         ELSE
            V_FRACAO = ((V_AREACALC/V_FRACAO)*100);
         END IF;
      END IF;
   END IF;
 
   RETURN V_FRACAO;
END;

$$ LANGUAGE 'plpgsql';

STRING_UP;
            $this->execute($sql_ajusta_funcao);

    }

    public function down()
    {

    $sql_ajusta_funcao_down = <<<STRING_DOWN

-- PARAMETROS
-- 1 - ID DO LOTE
-- 2 - EXERCICIO DB_GETSESSION("DB_ANOUSU")
-- 3 - MATRICULA

-- RETORNO 
-- FRACAO

create or replace function fc_fracao(integer,integer,integer)
returns double precision 

AS $$

DECLARE
      bqlini        ALIAS FOR $1;
      anousu        ALIAS FOR $2;
      v_matric      ALIAS FOR $3;
      v_fracao 		FLOAT8;
      v_fracaoCAD	FLOAT8;
      V_matricula	integer;
      V_AREAL 		float8;
      V_AREACALC 	FLOAT8;
BEGIN

   SELECT CASE WHEN J34_AREAL = 0 THEN J34_AREA ELSE J34_AREAL END AS J34_AREAL, J34_TOTCON,J01_MATRIC
   INTO V_AREAL, V_FRACAO, V_MATRICULA
   FROM IPTUBASE 
        INNER JOIN LOTE ON J34_IDBQL = J01_IDBQL
   WHERE J01_IDBQL = BQLINI AND J01_BAIXA IS NULL AND J01_MATRIC = V_MATRIC ;
   IF V_MATRICULA IS NULL THEN
      RETURN '0';
   END IF;
   IF V_AREAL = 0 OR V_AREAL IS NULL THEN
      RETURN '1';
   END IF;
   IF V_FRACAO IS NULL OR V_FRACAO = 0 THEN
      V_FRACAO = 100::float8;
   ELSE
      -- CALCULA FRACAO DO LOTE
      IF V_FRACAO != 0 THEN
         SELECT SUM(J39_AREA)
         INTO V_AREACALC
         FROM IPTUCONSTR
         WHERE J39_MATRIC = V_MATRICULA  
         GROUP BY J39_MATRIC;
         IF V_AREACALC IS NULL OR V_AREACALC = 0 THEN
            V_FRACAO = 100;
         ELSE
            V_FRACAO = ((V_AREACALC/V_FRACAO)*100);
         END IF;
      END IF;
   END IF;
 
   RETURN V_FRACAO;
END;

$$ LANGUAGE 'plpgsql';

STRING_DOWN;
            $this->execute($sql_ajusta_funcao_down);

    }
}
