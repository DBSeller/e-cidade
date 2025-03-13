<?php

use Classes\PostgresMigration;

class M17614BloqueiaMenus extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
update configuracoes.db_itensmenu set libcliente = 'f' where id_item in (228361, 228362, 228367, 228371);
SQL
        );
    }
}
