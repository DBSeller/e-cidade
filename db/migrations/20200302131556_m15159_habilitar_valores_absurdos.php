<?php

use Classes\PostgresMigration;

class M15159HabilitarValoresAbsurdos extends PostgresMigration
{
    public function up()
    {
        $this->execute('
            insert into db_syscampo values(1011076,\'la49_habilitarabsurdo\',\'char(1)\',\'Configuração para habilitar ou desabilitar os campos Absurdo Mínimo e Absurdo Máximo.\',\'1\', \'Habilitar Absurdo Mín. e Máx.\',1,\'f\',\'f\',\'f\',0,\'text\',\'Habilitar Absurdo Mín. e Máx.\');
            delete from db_sysarqcamp where codarq = 2909;
            insert into db_sysarqcamp values(2909,16575,1,1818);
            insert into db_sysarqcamp values(2909,16576,2,0);
            insert into db_sysarqcamp values(2909,17925,3,0);
            insert into db_sysarqcamp values(2909,1010672,4,0);
            insert into db_sysarqcamp values(2909,1010694,5,0);
            insert into db_sysarqcamp values(2909,1011076,6,0);
            alter table lab_parametros add column la49_habilitarabsurdo boolean default true;
            update db_syscampo set nomecam = \'la49_habilitarabsurdo\', conteudo = \'bool\', descricao = \'Configuração para habilitar ou desabilitar os campos Absurdo Mínimo e Absurdo Máximo.\', valorinicial = \'t\', rotulo = \'Habilitar Absurdo Mín. e Máx.\', nulo = \'f\', tamanho = 1, maiusculo = \'f\', autocompl = \'f\', aceitatipo = 5, tipoobj = \'text\', rotulorel = \'Habilitar Absurdo Mín. e Máx.\' where codcam = 1011076;
        ');
    }

    public function down()
    {
        $this->execute('
            delete from db_sysarqcamp where codarq = 2909;
            delete from db_syscampo where codcam = 1011076;
            alter table lab_parametros drop column la49_habilitarabsurdo;
        ');
    }
}
