<?php

use Classes\PostgresMigration;

class M19440AjustaTabelaIssarquivoretencaoregistroCampoNumeronotafiscal extends PostgresMigration
{
  public function up()
  {

        $this->execute("update db_syscampo set conteudo = 'int8' where codcam = 21096;");
        $this->execute('alter table issarquivoretencaoregistro alter column q91_numeronotafiscal type bigint;');
  }

  public function down()
  {
        return true;
  }
}
