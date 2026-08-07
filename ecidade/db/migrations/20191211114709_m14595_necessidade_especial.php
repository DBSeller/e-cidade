<?php

use Classes\PostgresMigration;

class M14595NecessidadeEspecial extends PostgresMigration
{
    public function change()
    {
        $this->execute("alter table escola.necessidade alter column ed48_c_descr TYPE varchar(100)");
        $this->execute("update escola.necessidade set ed48_c_descr = trim(ed48_c_descr)");
    }
}
