<?php

use Classes\PostgresMigration;

class M13299AtualizaEscolaridade extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            -- Magisterio
            update rechumano set ed20_tipoensinomedio = 2 where ed20_i_escolaridade = 3;
            -- Magisterio Indigena
            update rechumano set ed20_tipoensinomedio = 4 where ed20_i_escolaridade = 4;

            update rechumano set ed20_i_escolaridade = 5 where ed20_i_escolaridade in (3, 4);
SQL
        );
    }
}
