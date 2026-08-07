<?php

use Classes\PostgresMigration;

class M18420RenomeadoMenus extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
update db_itensmenu set descricao = 'Programas Estratégicos - Temáticos' , help = 'Programas Estratégicos - Temáticos' where id_item = 228498;
update db_itensmenu set descricao = 'Programas Estratégicos - Gestão' , help = 'Programas Estratégicos - Gestão' where id_item = 228499;
update db_itensmenu set descricao = 'Projeções da Receita' , help = 'Projeções da Receita' where id_item = 228504;
update db_itensmenu set descricao = 'Projeções da Despesa' , help = 'Projeções da Despesa' where id_item = 228522;
SQL
        );
    }
}
