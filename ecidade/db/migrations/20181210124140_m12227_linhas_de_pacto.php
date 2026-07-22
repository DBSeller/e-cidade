<?php

use Classes\PostgresMigration;

class M12227LinhasDePacto extends PostgresMigration
{

    public function up ()
    {
        $row = $this->fetchRow("select * from linhaspacto where c07_sequencial = 0");
        if (empty($row)) {
            $this->execute("insert into linhaspacto values (0, 'N/A', 0)");
        }

    }

    public function down ()
    {
        $this->execute('delete from linhaspacto where c07_sequencial = 0');
    }
}
