<?php

use Classes\PostgresMigration;

class M19052ValidaCadastroPacienteMicroArea extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            ALTER TABLE sau_config
                ADD COLUMN s103_validamicroarea BOOLEAN default false;
            insert into db_syscampo values(1013448,'s103_validamicroarea','bool','Informa se deve validar o cadastro do paciente na micro area.','f', 'Valida Cadastro Paciente Micro Area',1,'f','f','f',5,'text','Valida Cadastro Paciente Micro Area');
            insert into db_syscampodef values(1013448,'False','');
            insert into db_sysarqcamp values(2354,1013448,30,0);
SQL
        );
    }
    
    public function down()
    {
        $this->execute(<<<SQL
            ALTER TABLE sau_config
                DROP COLUMN s103_validamicroarea;
            delete from db_sysarqcamp where codarq = 2354 and codcam = 1013448;
            delete from db_syscampodef where codcam = 1013448;
            delete from db_syscampo where codcam = 1013448;
SQL
        );
    }
}
