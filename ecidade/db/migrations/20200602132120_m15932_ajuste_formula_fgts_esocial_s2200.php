<?php

use Classes\PostgresMigration;

class M15932AjusteFormulaFgtsEsocialS2200 extends PostgresMigration
{
    public function up()
    {
        $sql = "update db_formulas set db148_formula='select coalesce((SELECT 3003399  FROM rhpesfgts WHERE rh15_regist = [ESOCIAL_MATRICULA_SERVIDOR] AND rh15_data is not null), 3003400);' where db148_nome = 'ESOCIAL_FGTS_OPTANTE'";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "update db_formulas set db148_formula='SELECT 3003399 FROM rhpesfgts WHERE rh15_regist = [ESOCIAL_MATRICULA_SERVIDOR] AND rh15_data is not null;' where db148_nome = 'ESOCIAL_FGTS_OPTANTE'";
        $this->execute($sql);
    }
}
