<?php

use Classes\PostgresMigration;

class M13299AlteraEscolaDiretor extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1010459,'ed254_criterioacessofuncao','int4','Critério de acesso a função','0', 'Critério de acesso a função',10,'t','f','f',1,'text','Critério de acesso a função');
            insert into db_syscampodef values(1010459,'null','');
            insert into db_syscampo values(1010460,'ed254_especificacaocriteriooutros','varchar(100)','Especificação do critério de acesso','', 'Especificação do critério de acesso',100,'t','f','f',0,'text','Especificação do critério de acesso');
            insert into db_syscampodef values(1010460,'null','');

            insert into db_sysarqcamp values(2183,1010460,12,0);
            insert into db_sysarqcamp values(2183,1010459,13,0);


            alter table escoladiretor add ed254_criterioacessofuncao int4 default null;
            alter table escoladiretor add ed254_especificacaocriteriooutros varchar(100) default null;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            alter table escoladiretor drop column ed254_criterioacessofuncao;
            alter table escoladiretor drop column ed254_especificacaocriteriooutros;


            delete from db_sysarqcamp where codarq = 2183 AND codcam = 1010459 AND seqarq = 13;
            delete from db_sysarqcamp where codarq = 2183 AND codcam = 1010460 AND seqarq = 12;

            delete from db_syscampodef where codcam in (1010460, 1010459);
            delete from db_syscampo where codcam in (1010460, 1010459);
SQL
        );
    }
}
