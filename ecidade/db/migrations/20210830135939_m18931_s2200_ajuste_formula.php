<?php

use Classes\PostgresMigration;

class M18931S2200AjusteFormula extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            update configuracoes.db_formulas set db148_formula = 'SELECT case when rh02_regimejornadatrabalho = 1 then 3003392 when rh02_regimejornadatrabalho = 2 then 3003393 when rh02_regimejornadatrabalho = 3 then 3003394 when rh02_regimejornadatrabalho = 4 then 3004026 end FROM rhpessoalmov  INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg  INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime  WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR] AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO]) AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) AND rhcadregime.rh52_regime = 2;' where db148_nome = 'ESOCIAL_CLT_REGIME_JORNADA';
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            update configuracoes.db_formulas set db148_formula = 'SELECT 3003392 FROM rhpessoalmov INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg  INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime  WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR] AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO]) AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) AND rhcadregime.rh52_regime = 2;' where db148_nome = 'ESOCIAL_CLT_REGIME_JORNADA';
SQL;
        $this->execute($sql);
    }
}
