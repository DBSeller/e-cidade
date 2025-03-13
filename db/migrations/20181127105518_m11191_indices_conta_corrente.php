<?php

use Classes\PostgresMigration;

class M11191IndicesContaCorrente extends PostgresMigration
{

    public function up()
    {

        $this->execute("create index conplanoatributolancamentos_mes_in on conplanoatributolancamentos(extract(month from c124_data));");
        $this->execute("create index conplanoatributolancamentos_ano_in on conplanoatributolancamentos(extract(year from c124_data));");
    }

    public function down()
    {
        $this->execute("drop index conplanoatributolancamentos_mes_in ");
        $this->execute("drop index conplanoatributolancamentos_ano_in ");
    }
}
