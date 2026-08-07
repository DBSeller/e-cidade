<?php

use Classes\PostgresMigration;

class M14833UploadDeImagens extends PostgresMigration
{
    public function up()
    {
        $sql = "
            insert into db_syscampo values(1011189,'t52_foto','oid','Coluna para armazenar foto do bem.','', 'Foto do Bem',1,'t','f','f',1,'text','Foto do Bem');
            delete from db_sysarqcamp where codarq = 914;
            insert into db_sysarqcamp values(914,5764,1,155);
            insert into db_sysarqcamp values(914,5766,2,0);
            insert into db_sysarqcamp values(914,5769,3,0);
            insert into db_sysarqcamp values(914,5770,4,0);
            insert into db_sysarqcamp values(914,5771,5,0);
            insert into db_sysarqcamp values(914,5772,6,0);
            insert into db_sysarqcamp values(914,5773,7,0);
            insert into db_sysarqcamp values(914,5774,8,0);
            insert into db_sysarqcamp values(914,5775,9,0);
            insert into db_sysarqcamp values(914,9811,10,0);
            insert into db_sysarqcamp values(914,13864,11,0);
            insert into db_sysarqcamp values(914,13863,12,0);
            insert into db_sysarqcamp values(914,13862,13,0);
            insert into db_sysarqcamp values(914,1011189,14,0);
            alter table bens add column t52_foto oid default null;
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = '
            delete from db_sysarqcamp where codcam = 1011189;
            delete from db_syscampo where codcam = 1011189;
            alter table bens drop column t52_foto;
        ';

        $this->execute($sql);
    }
}
