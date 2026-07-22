<?php

use Classes\PostgresMigration;

class M9626OrdenacaoFormulariosEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = "
            insert into db_syscampo values(1009583,'db102_ordem','int4','Ordem dos grupos nos formulários do E-Social','0', 'Ordem',4,'t','f','f',0,'text','Ordem');
            insert into db_sysarqcamp values(2981,1009583,6,0);
            alter table avaliacaogrupopergunta add column db102_ordem int default 1;
                        
            update avaliacaogrupopergunta set db102_ordem = 2  where db102_sequencial = 3000217;
            update avaliacaogrupopergunta set db102_ordem = 3  where db102_sequencial = 3000218;
            update avaliacaogrupopergunta set db102_ordem = 4  where db102_sequencial = 3000219;
            update avaliacaogrupopergunta set db102_ordem = 5  where db102_sequencial = 3000220;
            update avaliacaogrupopergunta set db102_ordem = 6  where db102_sequencial = 3000221;
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_sysarqcamp where codcam = 1009583;
            delete from db_syscampo where codcam = 1009583;
            alter table avaliacaogrupopergunta drop column db102_ordem;
        ";
        $this->execute($sql);
    }
}
