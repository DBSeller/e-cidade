<?php

use Classes\PostgresMigration;

class M15710AcertoCenso extends PostgresMigration
{
    public function change()
    {
        $this->execute("update aluno set ed47_c_atenddifer = '3'  where ed47_c_atenddifer = 'N'");
    }
}
