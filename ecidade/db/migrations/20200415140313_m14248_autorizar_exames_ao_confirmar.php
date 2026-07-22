<?php

use Classes\PostgresMigration;

class M14248AutorizarExamesAoConfirmar extends PostgresMigration
{
    public function up()
    {
        $sql = "
            insert into db_syscampo values(1011188,'la49_autorizarexamesaoconfirmar','bool','Campo adicionado em DB:Saúde > Laboratório > Procedimentos > Parâmetros. Trata-se de uma flag que quando ativada permite a autorização automática ao realizar o lançamento de exames.','false', 'Autorizar Exames ao Confirmar',1,'t','f','f',5,'text','Autorizar Exames ao Confirmar');
            delete from db_sysarqcamp where codarq = 2909;
            insert into db_sysarqcamp values(2909,16575,1,1818);
            insert into db_sysarqcamp values(2909,16576,2,0);
            insert into db_sysarqcamp values(2909,17925,3,0);
            insert into db_sysarqcamp values(2909,1010672,4,0);
            insert into db_sysarqcamp values(2909,1010694,5,0);
            insert into db_sysarqcamp values(2909,1011076,6,0);
            insert into db_sysarqcamp values(2909,1011142,7,0);
            insert into db_sysarqcamp values(2909,1011188,8,0);
            alter table lab_parametros add column la49_autorizarexamesaoconfirmar boolean not null default false;
        ";
        
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_sysarqcamp where codcam = 1011188;
            delete from db_syscampo where codcam = 1011188;
            alter table lab_parametros drop column la49_autorizarexamesaoconfirmar;
        ";

        $this->execute($sql);
    }
}
