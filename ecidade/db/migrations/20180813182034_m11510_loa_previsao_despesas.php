<?php

use Classes\PostgresMigration;

class M11510LoaPrevisaoDespesas extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<EOL
          insert into db_syscampo values(1009886,'c41_valorlinha','float8','Valor da linha de impacto','0', 'Valor',11,'f','f','f',4,'text','Valor');
          insert into db_sysarqcamp values(1010302,1009886,5,0);
          
          insert into db_syscampo values(1009887,'c55_codigo','int4','Código de ordenação da previsão da despesa.','0', 'Código',10,'f','f','f',1,'text','Código');
          insert into db_sysarqcamp values(1010303,1009887,5,0);

          alter table previsaodespesalinhaspacto
          add column c41_valorlinha float8 default 0 not null;

          alter table previsaodespesaplano
          add column c55_codigo int4 default 0 not null;
EOL;
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = <<<EOL
            delete from db_sysarqcamp where codarq = 1010302 and codcam = 1009886;
            delete from db_syscampo where codcam = 1009886;

            delete from db_sysarqcamp where codarq = 1010303 and codcam = 1009887;
            delete from db_syscampo where codcam = 1009887;

            alter table previsaodespesalinhaspacto
            drop column c41_valorlinha;

            alter table previsaodespesaplano
            drop column c55_codigo;
EOL;
        $this->execute($sSql);
    }
}
