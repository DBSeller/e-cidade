<?php

use Classes\PostgresMigration;

class M10181TrabalhadorSemVinculoInicio extends PostgresMigration
{
    public function up()
    {
        $sql = "
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10570 ,'Trabalhador sem vínculo' ,'Trabalhador sem vínculo empregatício com a instituição' ,'eso4_trabalhadorsemvinculo001.php' ,'1' ,'1' ,'Rotina de manutenção dos dados de trabalhadores sem vínculo empregatício com a instituição.' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,10570 ,13 ,10216 );
            
            INSERT INTO db_syscampo VALUES(1009920,'rh30_vinculoemprego','bool','Trabalhador com vínculo de emprego','true', 'Trabalhador com vínculo de emprego',1,'f','f','f',5,'text','Trabalhador com vínculo de emprego');
            INSERT INTO db_sysarqcamp VALUES(1183,1009920,11,0);
            
            ALTER TABLE rhregime ADD COLUMN rh30_vinculoemprego bool default 'true';
            
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_menu where id_item_filho = 10570 AND modulo = 10216;
            delete from db_itensmenu where id_item = 10570;

            DELETE FROM db_sysarqcamp WHERE codcam = 1009920;
            DELETE FROM db_syscampo WHERE codcam = 1009920;
            
            ALTER TABLE rhregime DROP COLUMN rh30_vinculoemprego;
        ";
        $this->execute($sql);
    }
}
