<?php

use Classes\PostgresMigration;

class AjusteDsrConsultaJornada extends PostgresMigration
{
    function up()
    {
        $this->execute("DROP FUNCTION IF EXISTS fc_getjornadaservidornadata(date, integer);
                        DROP TYPE IF EXISTS tp_jornadaservidornadata;
                        CREATE TYPE tp_jornadaservidornadata AS (
                            codigo_escala        integer,
                            ordem_jornada        integer,
                            codigo_jornada       integer,
                            descricao_jornada    varchar,
                            tipo                 char
                        );

                        CREATE OR REPLACE FUNCTION fc_getjornadaservidornadata(date, integer) RETURNS tp_jornadaservidornadata AS $$
                        DECLARE
                          escala_servidor             integer := 0;
                          data_jornada                alias for $1;
                          matricula                   alias for $2;
                          rtp_jornadaservidornadata   tp_jornadaservidornadata%ROWTYPE;
                        BEGIN

                            SELECT rh192_gradeshorarios INTO escala_servidor
                              FROM escalaservidor
                             WHERE rh192_regist = matricula
                               AND rh192_dataescala <= data_jornada
                          ORDER BY rh192_dataescala DESC 
                             LIMIT 1;

                            SELECT 
                                rh191_gradehorarios,
                                rh191_ordemhorario,
                                (SELECT rh188_sequencial FROM jornada WHERE rh188_sequencial = dados.sequencial_jornada),
                                (SELECT rh188_descricao FROM jornada WHERE rh188_sequencial = dados.sequencial_jornada),
                                (SELECT rh188_tipo FROM jornada WHERE rh188_sequencial = dados.sequencial_jornada)
                              INTO 
                                rtp_jornadaservidornadata.codigo_escala,
                                rtp_jornadaservidornadata.ordem_jornada,
                                rtp_jornadaservidornadata.codigo_jornada,
                                rtp_jornadaservidornadata.descricao_jornada,
                                rtp_jornadaservidornadata.tipo
                              FROM (SELECT 
                                        rh191_gradehorarios,
                                        rh191_ordemhorario,
                                        (CASE WHEN exists(SELECT rh212_jornada 
                                                            FROM jornadaservidor 
                                                           WHERE rh212_data = data_jornada
                                                             AND rh212_matricula = matricula) 
                                              then (SELECT rh212_jornada 
                                                            FROM jornadaservidor 
                                                           WHERE rh212_data = data_jornada
                                                             AND rh212_matricula = matricula)
                                              else rh188_sequencial
                                          END) as sequencial_jornada
                                      FROM escalaservidor
                                     INNER JOIN gradeshorarios ON rh192_gradeshorarios = rh190_sequencial
                                     INNER JOIN gradeshorariosjornada ON rh191_gradehorarios = rh190_sequencial
                                     INNER JOIN jornada ON rh188_sequencial = rh191_jornada
                                     WHERE rh191_gradehorarios = escala_servidor
                                       AND rh191_ordemhorario IN (SELECT (((data_jornada - ( SELECT rh190_database
                                                                                               FROM gradeshorarios
                                                                                              WHERE rh190_sequencial = escala_servidor)
                                                                           )) % ( SELECT max(rh191_ordemhorario)
                                                                                    FROM gradeshorariosjornada
                                                                                   WHERE rh191_gradehorarios = escala_servidor ) + 1
                                                                                 ) AS ordem
                                                                    FROM ( SELECT ( SELECT rh192_sequencial
                                                                                      FROM escalaservidor
                                                                                     WHERE rh192_regist = matricula
                                                                                       AND rh192_dataescala <= data_jornada
                                                                                     ORDER BY rh192_dataescala DESC 
                                                                                     LIMIT 1 
                                                                                  ) AS codigo_escala,
                                                                                  data_jornada AS DATA 
                                                                         ) AS escalasperiodo 
                                                                  )
                                     GROUP BY
                                        rh191_gradehorarios,
                                        rh191_ordemhorario,
                                        rh188_sequencial
                                    ) as dados
                                ;
                            
                            RETURN rtp_jornadaservidornadata;
                        END;
                        $$ LANGUAGE plpgsql
        ");
    }

    function down()
    {
        $this->execute("DROP FUNCTION IF EXISTS fc_getjornadaservidornadata(date, integer);
                        DROP TYPE IF EXISTS tp_jornadaservidornadata;
        ");
    }
}
