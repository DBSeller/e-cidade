<?php

use Classes\PostgresMigration;

class M9344FieldsetErroOrtografico extends PostgresMigration
{
    public function up()
    {
      $this->execute("UPDATE db_syscampo SET rotulo = 'Código' WHERE nomecam = 'j92_sequencial'");
    }

    public function down()
    {
      $this->execute("UPDATE db_syscampo SET rotulo = 'Codigo' WHERE nomecam = 'j92_sequencial'");
    }
}
