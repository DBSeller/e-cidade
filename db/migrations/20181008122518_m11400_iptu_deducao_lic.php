<?php

use Classes\PostgresMigration;

class M11400IptuDeducaoLic extends PostgresMigration
{
    public function up()
    {
        $sql = "
            CREATE OR REPLACE FUNCTION fc_iptu_deducao_lic(imatricula INTEGER, ianousu INTEGER,
                                                           ireceita   INTEGER DEFAULT NULL :: INTEGER)
              RETURNS NUMERIC
            LANGUAGE plpgsql
            AS $$
            DECLARE
              nValorDeducao NUMERIC(15, 2) DEFAULT 0;
              iReceitaIPTU  INTEGER;
            BEGIN
            
              -- Caso haja receita informada, mesma seja diferente do que está configurada para o IPTU, retornamos 0
              IF iReceita IS NOT NULL
              THEN
                SELECT CASE
                         WHEN EXISTS(SELECT TRUE FROM cadastro.iptuconstr WHERE j39_matric = iMatricula)
                                 THEN j18_rpredi
                         ELSE j18_rterri
                           END AS receita
                    INTO iReceitaIPTU
                FROM cadastro.cfiptu
                WHERE j18_anousu = iAnousu;
            
                IF iReceitaIPTU <> iReceita
                THEN
                  RETURN 0;
                END IF;
              END IF;
            
              -- Caso haja dedução no cálculo anterior, retornamos o valor
              SELECT valor INTO nValorDeducao
              FROM tmpipturecalculo
              WHERE matricula = iMatricula
                AND anousu = iAnousu
                AND historico = 937;
            
              IF nValorDeducao IS NULL
              THEN
                RETURN 0;
              END IF;
            
              RETURN nValorDeducao;
            END;
            $$;
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $this->execute("DROP FUNCTION fc_iptu_deducao_lic(INTEGER, INTEGER, INTEGER)");
    }
}
