<?php

use Classes\PostgresMigration;

class M10893 extends PostgresMigration
{

    public function up()
    {
        $this->execute("
            
        insert into db_syscampo values(1009740,'w13_recaptcha_privatekey','varchar(200)','Captcha Chave Privada','', 'Captcha Chave Privada',200,'f','t','f',0,'text','');
        insert into db_syscampo values(1009742,'w13_recaptcha_sitekey','varchar(200)','Captcha Chave Pública','', 'Captcha Chave Pública',200,'f','t','f',0,'text','');
        insert into db_sysarqcamp values(1383,1009740,28,0);
        insert into db_sysarqcamp values(1383,1009742,27,0);

        ALTER TABLE configdbpref ADD COLUMN  w13_recaptcha_privatekey varchar (200); 
        ALTER TABLE configdbpref ADD COLUMN  w13_recaptcha_sitekey varchar (200);
          
        ");
    }


    public function down()
    {


        $this->execute("
              DELETE FROM  db_sysarqcamp WHERE  codcam IN (1009740, 1009742);
              DELETE FROM  db_syscampo   WHERE  codcam IN (1009740, 1009742);
             
                   
              ALTER TABLE configdbpref DROP COLUMN w13_recaptcha_privatekey;
              ALTER TABLE configdbpref DROP COLUMN w13_recaptcha_sitekey;
        ");

    }
}
