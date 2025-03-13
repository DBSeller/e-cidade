<?php

use Classes\PostgresMigration;

class M14442FixPreenchimentoEsocialS2200 extends PostgresMigration
{
    public function up()
    {
        $this->atualizaGrauInstrucao();
    }

    public function down()
    {
        $this->reverteGrauInstrucao();
    }

    private function atualizaGrauInstrucao()
    {
        $sql = "UPDATE db_formulas 
                    SET db148_formula = 'SELECT CASE rh01_instru WHEN 1
                                                THEN 3003088
                                            WHEN 2
                                                THEN 3003089
                                            WHEN 3
                                                THEN 3003090
                                            WHEN 4
                                                THEN 3003091
                                            WHEN 5
                                                THEN 3003092
                                            WHEN 6
                                                THEN 3003093
                                            WHEN 7
                                                THEN 3003094
                                            WHEN 8
                                                THEN 3003095
                                            WHEN 9
                                                THEN 3003096
                                            WHEN 10
                                                THEN 3003098
                                            WHEN 11
                                                THEN 3003099
                                            WHEN 12
                                                THEN 3003097
                                            END 
                    FROM cgm
                             INNER JOIN rhpessoal ON rhpessoal.rh01_numcgm = cgm.z01_numcgm
                    WHERE rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]' WHERE db148_nome = 'ESOCIAL_GRAU_INSTRUCAO_SERVIDOR_V3';";
        $this->execute($sql);
    }

    private function reverteGrauInstrucao()
    {
        $sql = "UPDATE db_formulas 
                    SET db148_formula = 'SELECT CASE rh01_instru WHEN 1
                                                THEN 3003088
                                            WHEN 2
                                                THEN 3003089
                                            WHEN 3
                                                THEN 3003090
                                            WHEN 4
                                                THEN 3003091
                                            WHEN 5
                                                THEN 3003092
                                            WHEN 6
                                                THEN 3003093
                                            WHEN 7
                                                THEN 3003094
                                            WHEN 8
                                                THEN 3003095
                                            WHEN 9
                                                THEN 3003096
                                            WHEN 10
                                                THEN 3003098
                                            WHEN 11
                                                THEN 3003099
                                            END 
                    FROM cgm
                             INNER JOIN rhpessoal ON rhpessoal.rh01_numcgm = cgm.z01_numcgm
                    WHERE rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]' WHERE db148_nome = 'ESOCIAL_GRAU_INSTRUCAO_SERVIDOR_V3';";
        $this->execute($sql);
    }


}
