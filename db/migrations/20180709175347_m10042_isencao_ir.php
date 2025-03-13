<?php

use Classes\PostgresMigration;

class M10042IsencaoIr extends PostgresMigration
{
    public function up()
    {
        $sql = "
          INSERT INTO rhrubricas (rh27_rubric ,rh27_descr ,rh27_pd ,rh27_quant ,rh27_cond2 ,rh27_cond3 ,rh27_form ,rh27_form2 ,rh27_form3 ,rh27_formq ,rh27_calc1 ,rh27_calc2 ,rh27_calc3 ,rh27_tipo ,rh27_limdat ,rh27_presta ,rh27_calcp ,rh27_propq ,rh27_propi ,rh27_obs ,rh27_instit ,rh27_ativo ,rh27_complementarautomatica ,rh27_valorpadrao ,rh27_quantidadepadrao ,rh27_rhfundamentacaolegal ,rh27_valorlimite ,rh27_quantidadelimite ,rh27_tipobloqueio ,rh27_periodolancamento ,rh27_previdenciacomplementar)
            SELECT 'R960', upper('Isenção IR Aux. Doença e Ac. Trabalho'), 3, 0, '', '', '', '', '', '', 0, 0, 'false', 2, 'f', 'f', 'f', 'f', 'f', '', codigo, 't',
              'f', 0, 0, NULL, 0, 0, 'N', 'f', 0 FROM db_config;
       ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
          DELETE FROM rhrubricas WHERE rh27_rubric = 'R960';
       ";

        $this->execute($sql);
    }
}
