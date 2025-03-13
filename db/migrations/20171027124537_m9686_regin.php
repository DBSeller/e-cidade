<?php

use Classes\PostgresMigration;

class M9686Regin extends PostgresMigration
{
    public function up()
    {
        $sql  = "insert into db_syscampo values(1009490,'q147_retorno','int4','Código do Retorno do REGIN','0', 'Código do Retorno',10,'t','f','f',1,'text','Código do Retorno');";
        $sql .= "insert into db_sysarqcamp values(1010222,1009490,10,0);";
        $sql .= "alter table juntacomercialprotocolo add COLUMN q147_retorno integer;";

        $sql .= "insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10467 ,'Configuração webservice de retorno - REGIN' ,'Configuração webservice de retorno - REGIN' ,'con4_manutencaoiniRegin001.php' ,'1' ,'1' ,'Configuração webservice de retorno - REGIN' ,'true' );";
        $sql .= "insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10467 ,493 ,1 );";

        $this->execute($sql);
    }

    public function down()
    {
        $sql  = "delete from db_sysarqcamp where codcam = 1009490;";
        $sql .= "delete from db_syscampo where codcam = 1009490;";
        $sql .= "alter table juntacomercialprotocolo DROP COLUMN q147_retorno;";

        $sql .= "delete from db_menu where id_item_filho = 10467 AND modulo = 1;";
        $sql .= "delete from db_itensmenu where id_item = 10467;";

        $this->execute($sql);
    }
}
