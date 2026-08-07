<?php

use Classes\PostgresMigration;

class M13345AjustaTabelaIssarquivoretencaoregistroCampoNumeronotafiscal extends PostgresMigration
{
  public function up()
  {

        $this->execute("update db_syscampo set conteudo = 'int8' where codcam = 21096;");
  }

  public function down()
  {
        return true;
  }
}
