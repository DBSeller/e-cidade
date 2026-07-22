<?php

use Classes\PostgresMigration;

class M13822CampoConplanoSaldoContinuo extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL_UP

insert into db_syscampo values(1010784,'c60_saldocontinuo','bool','Transfere Saldo','false', 'Transfere Saldo entre exercícios',1,'f','f','f',5,'text','Transfere Saldo');
insert into db_sysarqcamp values(3268,1010784,13,0);
alter table conplano add column c60_saldocontinuo boolean default false;

SQL_UP
);
    }


    public function down()
    {

        $this->execute(<<<SQL_DOWN

alter table conplano drop column c60_saldocontinuo;
delete from db_sysarqcamp where codarq = 3268 and codcam = 1010784;

SQL_DOWN
);

    }
}
