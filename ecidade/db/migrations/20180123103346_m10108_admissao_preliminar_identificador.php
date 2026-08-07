<?php

use Classes\PostgresMigration;

class M10108AdmissaoPreliminarIdentificador extends PostgresMigration
{
    public function up()
    {
      $sSql = "update avaliacaogrupopergunta set db102_identificadorcampo = 'infoRegPrelim' where db102_avaliacao = '3000021'";
      $this->execute($sSql);
    }

    public function down()
    {
      $sSql = "update avaliacaogrupopergunta set db102_identificadorcampo = '' where db102_avaliacao = '3000021'";
      $this->execute($sSql);
    }
}
