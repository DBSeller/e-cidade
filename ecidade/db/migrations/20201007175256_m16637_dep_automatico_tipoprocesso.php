<?php

use Classes\PostgresMigration;

class M16637DepAutomaticoTipoprocesso extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1011849,'p90_depandamentopadrao','int4','Departamento para inclusão automática do andamento padrão. Ao incluir um tipo de processo, será gerado um andamento padrão automaticamente, baseado no departamento informado nos parâmetros institucionais. Este, será vinculado ao tipo de processo.','0', 'Dep. para inclusão automática do andamento padrão',11,'t','f','f',1,'text','Dep. para inclusão automática andameto');
            insert into db_sysarqcamp values(1210,1011849,17,0);

            ALTER TABLE protparam ADD COLUMN p90_depandamentopadrao INT4 DEFAULT NULL;
            ALTER TABLE protparam DROP CONSTRAINT IF EXISTS protparam_documentotemplate_fk;
            ALTER TABLE protparam ALTER COLUMN p90_db_documentotemplate SET DEFAULT 0;
SQL;
        
        $this->execute($sql);

    }

    public function down()
    {
        $sql = <<<SQL
            DELETE FROM db_sysarqcamp WHERE codarq = 1210 AND codcam = 1011849;
            DELETE FROM db_syscampo WHERE codcam = 1011849;
            ALTER TABLE protparam DROP COLUMN p90_depandamentopadrao;
SQL;
                    
        $this->execute($sql);
    }
}
