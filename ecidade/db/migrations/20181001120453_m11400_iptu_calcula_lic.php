<?php

use Classes\PostgresMigration;

class M11400IptuCalculaLic extends PostgresMigration
{
    public function up()
    {
        $sql =
            <<<SQL
CREATE OR REPLACE FUNCTION fc_iptu_calcula_lic(INTEGER, INTEGER)
  RETURNS BOOLEAN
LANGUAGE plpgsql
AS $$
DECLARE
  iMatricula ALIAS FOR $1;
  iAnousu ALIAS FOR $2;

  fDeducao         FLOAT DEFAULT 0;
  arrecadacao      RECORD;
  nParcelasPagas   INT;
  nReceitaIPTU     INT;
  nCodigoHistorico INT DEFAULT 937;
  bRaise           BOOLEAN DEFAULT TRUE;
  nValorDesconto   NUMERIC(15, 2) DEFAULT 0;
  nValorParcela    NUMERIC(15, 2) DEFAULT 0;
  nValorCorrecaoDesconto NUMERIC(15, 2) DEFAULT 0;
  nTotalIptu             NUMERIC(15, 2) DEFAULT 0;
  totalArrecad           NUMERIC(15, 2) DEFAULT 0;
  totalArrepaga          NUMERIC(15, 2) DEFAULT 0;
BEGIN
  bRaise := (CASE WHEN fc_getsession('DB_debugon') IS NULL
    THEN FALSE
             ELSE TRUE END);
  PERFORM fc_debug(' <fc_iptu_calcula_lic> INICIO LIC', bRaise);

  IF EXISTS(
      SELECT TRUE FROM information_schema.tables WHERE table_schema = 'plugins'
                                                   AND table_name = 'iptulic'
  )
  THEN

    PERFORM fc_debug(' <fc_iptu_calcula_lic> PLUGIN ESTA INSTALADO', bRaise);
    SELECT deducao INTO fDeducao FROM plugins.iptulic WHERE matricula = iMatricula
                                                        AND exercicio = iAnousu;

    IF fDeducao > 0
    THEN
      PERFORM fc_debug(' <fc_iptu_calcula_lic> MATRICULA POSSUI DESCONTO', bRaise);
      SELECT CASE
               WHEN EXISTS(SELECT TRUE FROM cadastro.iptuconstr WHERE j39_matric = iMatricula)
                       THEN j18_rpredi
               ELSE j18_rterri
                 END AS receita
          INTO nReceitaIPTU
      FROM cadastro.cfiptu
      WHERE j18_anousu = iAnousu;

      PERFORM fc_debug(' <fc_iptu_calcula_lic> INCLUINDO VALOR NA IPTUCALV', bRaise);
      INSERT INTO cadastro.iptucalv (j21_anousu, j21_matric, j21_codhis, j21_receit, j21_valor, j21_quant)
      VALUES (iAnousu, iMatricula, nCodigoHistorico, nReceitaIPTU, -fDeducao, 0);

      PERFORM fc_debug(' <fc_iptu_calcula_lic> AJUSTANDO VALORES DA ARRECAD', bRaise);

      SELECT count(*) INTO nParcelasPagas
      FROM caixa.arrepaga
             JOIN cadastro.iptunump ON j20_numpre = k00_numpre
      WHERE j20_anousu = iAnousu
        AND j20_matric = iMatricula
        AND k00_receit = nReceitaIPTU;

      FOR arrecadacao IN SELECT *
                         FROM caixa.arrecad
                                JOIN cadastro.iptunump ON j20_numpre = k00_numpre
                         WHERE j20_anousu = iAnousu
                           AND j20_matric = iMatricula
                           AND k00_receit = nReceitaIPTU
      LOOP

        nValorDesconto := fdeducao / (arrecadacao.k00_numtot - nParcelasPagas);
        nValorParcela := round((arrecadacao.k00_valor - nValorDesconto) :: NUMERIC, 2);

        UPDATE caixa.arrecad
        SET k00_valor = nValorParcela
        WHERE k00_numpre = arrecadacao.k00_numpre
          AND k00_numpar = arrecadacao.k00_numpar
          AND k00_receit = nReceitaIPTU;

      END LOOP;

      -- Verificamos se após aplicar os descontos nas parcelas, há alguma diferença de arredondamento.
      -- Caso haja, aplicamos a diferença na última parcela
      SELECT coalesce(sum(j21_valor), 0) INTO nTotalIptu
      FROM cadastro.iptucalv
      WHERE j21_matric = iMatricula
        AND j21_anousu = iAnousu;

      SELECT coalesce(sum(k00_valor), 0) INTO totalArrecad
      FROM caixa.arrecad
             JOIN cadastro.iptunump ON j20_numpre = k00_numpre
      WHERE j20_anousu = iAnousu
        AND j20_matric = iMatricula;

      SELECT coalesce(sum(k00_valor), 0) INTO totalArrepaga
      FROM arrepaga
             JOIN cadastro.iptunump ON j20_numpre = k00_numpre
      WHERE j20_anousu = iAnousu
        AND j20_matric = iMatricula;

      nValorCorrecaoDesconto := nTotalIptu - (totalArrecad + totalArrepaga);

      UPDATE caixa.arrecad
      SET k00_valor = k00_valor + nValorCorrecaoDesconto
      FROM cadastro.iptunump
      WHERE j20_numpre = k00_numpre
        AND j20_anousu = iAnousu
        AND j20_matric = iMatricula
        AND k00_receit = nReceitaIPTU
        AND k00_numpar = k00_numtot;

    END IF;
  END IF;

  PERFORM fc_debug(' <fc_iptu_calcula_lic> FIM DO CALCULO DA LIC', bRaise);
  RETURN TRUE;
END;
$$;
    
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $this->execute("drop function if exists fc_iptu_calcula_lic(integer,integer);");
    }
}
