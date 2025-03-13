<?php

use Classes\PostgresMigration;

class M16146TriggerProcandam extends PostgresMigration
{

    public function down()
    {
        $this->execute("DROP TRIGGER IF EXISTS tg_atualizacamposandpadraoresposta ON procandam;");
    }

    public function up()
    {
        $triggerProcandam = <<<SQL
            DROP TRIGGER IF EXISTS tg_atualizacamposandpadraoresposta ON procandam;

            CREATE OR REPLACE FUNCTION public.fc_atualizacamposandpadraoresposta()
            RETURNS TRIGGER AS
            $$
            DECLARE

              v_record_camposdinamicos  record;

            BEGIN

                for v_record_camposdinamicos IN SELECT cp.p110_sequencial
                                                       ,codandam_anterior.p61_codandam
                                                  FROM andpadrao AS a
                                            INNER JOIN camposandpadrao AS cp ON cp.p110_andpadrao_codigo = a.p53_codigo
                                                   AND p110_andpadrao_ordem = a.p53_ordem
                                            INNER JOIN (
                                                          SELECT DISTINCT
                                                             pa.p61_codproc
                                                            ,pa.p61_coddepto
                                                            ,tp.p51_codigo
                                                            ,pa.p61_codandam
                                                          FROM
                                                            procandam as pa
                                                          INNER JOIN procandam as pa_old
                                                                  ON pa_old.p61_codproc  =  pa.p61_codproc
                                                                 AND pa_old.p61_coddepto =  pa.p61_coddepto
                                                                 AND pa_old.p61_codandam <> pa.p61_codandam
                                                          INNER JOIN protprocesso as p
                                                                  ON p.p58_codproc = pa.p61_codproc
                                                          INNER JOIN tipoproc as tp 
                                                                  ON tp.p51_codigo = p.p58_codigo
                                                          INNER JOIN camposandpadraoresposta as cr
                                                                  ON cr.p111_codandam = pa.p61_codandam
                                                          WHERE pa.p61_codproc  = NEW.                                                                                                                                                                                                                                                                                                p61_codproc
                                                            AND pa.p61_coddepto = NEW.                                                                                                                                                                                                                                                                                                p61_coddepto
                                                       ORDER BY pa.p61_codandam DESC
                                                          LIMIT 1
                                                        ) as codandam_anterior
                                                    ON a.p53_codigo   = codandam_anterior.p51_codigo
                                                   AND a.p53_coddepto = codandam_anterior.p61_coddepto
                loop

                    INSERT INTO camposandpadraoresposta (p111_camposandpadrao, p111_codandam, p111_codcam, p111_resposta) 
                         SELECT p111_camposandpadrao,
                                NEW.p61_codandam,
                                p111_codcam,
                                p111_resposta
                           FROM camposandpadraoresposta
                          WHERE p111_camposandpadrao = v_record_camposdinamicos.p110_sequencial
                            AND p111_codandam = v_record_camposdinamicos.p61_codandam
                       ORDER BY p111_sequencial DESC
                          LIMIT 1;

                end loop;

                RETURN NEW;
            END;
            $$
            LANGUAGE 'plpgsql';

            CREATE TRIGGER tg_atualizacamposandpadraoresposta AFTER INSERT OR UPDATE
            ON procandam FOR EACH ROW 
            EXECUTE PROCEDURE fc_atualizacamposandpadraoresposta();
SQL;

        $this->execute($triggerProcandam);
    }
}
