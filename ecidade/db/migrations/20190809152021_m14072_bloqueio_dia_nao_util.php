<?php

use Classes\PostgresMigration;

class M14072BloqueioDiaNaoUtil extends PostgresMigration
{
  public function up()
  {
      $sql = "
        ALTER TABLE caixa.arretipo ADD COLUMN k00_bloqnutil boolean DEFAULT false;
        INSERT INTO db_syscampo VALUES (1010680, 'k00_bloqnutil', 'boolean', 'Define se bloqueia emissão no dia não util','f','Bloqueia dia não útil', 1,'f','f','f',null,null,'Bloqueia dia não útil');
        INSERT INTO configuracoes.db_sysarqcamp VALUES (82,1010680,43,0);
      ";
      $this->execute($sql);
  }


  public function down()
  {
      $sql = "
        ALTER TABLE caixa.arretipo DROP COLUMN k00_bloqnutil;
        DELETE FROM configuracoes.db_sysarqcamp WHERE codcam IN (1010680);
        DELETE FROM configuracoes.db_syscampo WHERE codcam IN (1010680);
      ";
      $this->execute($sql);
  }
}
