<?php

use Classes\PostgresMigration;

class M14967AlterarItemDeMenu extends PostgresMigration
{    
    public function up()
    {
        $this->execute("update db_itensmenu set descricao = 'Atendimento' , help = 'Atendimento' where id_item = 6828;");
    }

    public function down()
    {
        $this->execute("update db_itensmenu set descricao = 'Consulta Médica' , help = 'Consulta Médica' where id_item = 6828;");
    }
}
