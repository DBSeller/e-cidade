<?php

use Classes\PostgresMigration;

class M10321NomeSocial extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
          alter table ambulatorial.cgs_und add column z01_v_nome_social varchar(255);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
          alter table ambulatorial.cgs_und drop column z01_v_nome_social;
SQL
        );
    }
}
   