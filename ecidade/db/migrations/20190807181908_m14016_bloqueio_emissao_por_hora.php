<?php

use Classes\PostgresMigration;

class M14016BloqueioEmissaoPorHora extends PostgresMigration
{
  public function up()
  {
      $sql = "
        ALTER TABLE caixa.arretipo ADD COLUMN k00_horainicial char(5);
        ALTER TABLE caixa.arretipo ADD COLUMN k00_horafinal char(5);

        INSERT INTO configuracoes.db_syscampo
        VALUES(1010650,
               'k00_horainicial',
               'char(5)',
               'Hora Inicial do Bloqueio de Emissão no Ecidade Online',
               '',
               'Hora Inicial',
               5,
               'f',
               'f',
               'f',
               0,
               'text',
               'Hora Inicial');


        INSERT INTO configuracoes.db_syscampo
        VALUES(1010651,
               'k00_horafinal',
               'char(5)',
               'Hora Final do Bloqueio de Emissão no Ecidade Online',
               '',
               'Hora Final',
               5,
               'f',
               'f',
               'f',
               0,
               'text',
               'Hora Final');

        INSERT INTO configuracoes.db_sysarqcamp VALUES (82,1010650,41,0);
        INSERT INTO configuracoes.db_sysarqcamp VALUES (82,1010651,42,0);
      ";
      $this->execute($sql);
  }


  public function down()
  {
      $sql = "
        ALTER TABLE caixa.arretipo DROP COLUMN k00_horainicial;
        ALTER TABLE caixa.arretipo DROP COLUMN k00_horafinal;
        DELETE FROM configuracoes.db_sysarqcamp WHERE codcam IN (1010650,1010651);
        DELETE FROM configuracoes.db_syscampo WHERE codcam IN (1010650,1010651);
      ";
      $this->execute($sql);
  }
}
