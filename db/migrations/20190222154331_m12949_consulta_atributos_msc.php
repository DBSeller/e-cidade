<?php

use Classes\PostgresMigration;

class M12949ConsultaAtributosMsc extends PostgresMigration
{

    public function up()
    {
        $this->execute("update conplanoinfocomplementar set c121_sql = replace(c121_sql, 'c60_naturezasaldo', 'c60_identificadorfinanceiro') where c121_sequencial = 2;");
    }

    public function down()
    {
        $this->execute("update conplanoinfocomplementar set c121_sql = replace(c121_sql, 'c60_identificadorfinanceiro', 'c60_naturezasaldo') where c121_sequencial = 2;");
    }
}
