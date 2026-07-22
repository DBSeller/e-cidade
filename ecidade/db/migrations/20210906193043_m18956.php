<?php

use Classes\PostgresMigration;

class M18956 extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
        insert into db_syscampo values(
                                       1013429
                                       ,'ov33_client_atendimento_id'
                                       ,'int4'
                                       ,'Identificação da aplicação de atendimento referente ao EAUTH'
                                       ,'0'
                                       , 'ov33_client_atendimento_id'
                                       ,8
                                       ,'t'
                                       ,'f'
                                       ,'f'
                                       ,1
                                       ,'text'
                                       ,'ov33_client_atendimento_id'
                                       );
        insert into db_sysarqcamp values(1010472,1013429,5,0);
        ALTER TABLE ouvidoria.ouvidoriaatendimentoprocessoeletronico ADD COLUMN ov33_client_atendimento_id integer;

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
       DELETE FROM db_sysarqcamp WHERE codarq =  1010472 AND codcam = 1013429;
       DELETE FROM db_syscampo  WHERE codcam = 1013429;
    ALTER TABLE ouvidoria.ouvidoriaatendimentoprocessoeletronico DROP COLUMN ov33_client_atendimento_id;

SQL
        );
    }
}
