<?php

use Classes\PostgresMigration;

class M12816AcertoAtributoFonteRecursoContaCorrente extends PostgresMigration
{

    public function up()
    {

        $this->execute("update conplanoinfocomplementar set c121_sql = c121_sql || ' limit 1 ' where c121_sequencial = 3;");
    }

    public function down()
    {

    }

}
