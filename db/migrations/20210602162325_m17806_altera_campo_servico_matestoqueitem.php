<?php

use Classes\PostgresMigration;

class M17806AlteraCampoServicoMatestoqueitem extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
    update matestoqueitem set m71_servico = false where m71_servico is null;
    alter table matestoqueitem alter column m71_servico set default false;
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
    alter table matestoqueitem alter column m71_servico set default null;
sql
        );
    }
}
