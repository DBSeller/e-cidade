<?php

use Classes\PostgresMigration;

class M15290 extends PostgresMigration
{
    public function change()
    {
        $this->execute("
        update censoetapa set ed266_c_descr ='Educação Infantil e Ensino Fundamental de 9 anos - Multietapa'
        where ed266_i_codigo = 56 and ed266_ano in (2019, 2020);
        ");
    }
}
