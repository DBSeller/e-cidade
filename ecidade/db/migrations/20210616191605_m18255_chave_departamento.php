<?php

use Classes\PostgresMigration;

class M18255ChaveDepartamento extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL

        alter table proctransfer 
          add constraint proctransfer_coddeptorec_fk FOREIGN KEY(p62_coddeptorec) references db_depart(coddepto) MATCH FULL DEFERRABLE;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        alter table proctransfer 
         drop constraint proctransfer_coddeptorec_fk ;
SQL
        );
    }
}