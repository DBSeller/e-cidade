<?php

use Classes\PostgresMigration;

class M16590AlteraExibicaoValorPagar extends PostgresMigration
{
   public function up()
   {
    $this->execute("insert into itbitipoformapag values (6,'ISENTO', TRUE);");
   }

   public function down()
   {
    $this->execute("delete from  itbitipoformapag where it28_sequencial = 6 ");
   }
}
