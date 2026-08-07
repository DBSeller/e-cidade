<?php

use Classes\PostgresMigration;

class M17675AdicionaRegiaoTabelaAluno extends PostgresMigration
{

    /* Cria campos de regiões admnistrativas e regiao naturalidade*/

    public function up()
    {
        $this->execute("ALTER TABLE escola.aluno ADD COLUMN ed47_censoregiao int4");
        $this->execute("ALTER TABLE escola.aluno ADD CONSTRAINT aluno_censoregiao_fk
                            FOREIGN KEY (ed47_censoregiao) REFERENCES censoregiao(ed174_codigo)");
        $this->execute("ALTER TABLE escola.aluno ADD COLUMN ed47_censoregiaonat int4");
        $this->execute("ALTER TABLE escola.aluno ADD CONSTRAINT aluno_censoregiaonat_fk
                            FOREIGN KEY (ed47_censoregiaonat) REFERENCES censoregiao(ed174_codigo)");
        $this->dicionarioUp();
    }

    public function down()
    {
        $this->execute("ALTER TABLE escola.aluno DROP COLUMN ed47_censoregiao");
        $this->execute("ALTER TABLE escola.aluno DROP COLUMN ed47_censoregiaonat");
        $this->dicionarioDown();
    }

    private function dicionarioUp()
    {
        $sql = <<<SQL
        insert into db_syscampo values(1013162,'ed47_censoregiao','int4','Código região do aluno. Referencia o código da censoregiao.','0', 'Região Aluno',10,'t','f','f',1,'text','Região Aluno');
        insert into db_sysarqcamp values(1010051,1013162,79,0);
        insert into db_sysforkey values(1010051,1013162,1,1010784,0);

        insert into db_syscampo values(1013163,'ed47_censoregiaonat','int4','Região de naturalidade do aluno.','0', 'Região Naturalidade',10,'t','f','f',1,'text','Região Naturalidade');
        insert into db_sysarqcamp values(1010051,1013163,80,0);
        insert into db_sysforkey values(1010051,1013163,1,1010784,0);
SQL;
        $this->execute($sql);
    }

    private function dicionarioDown()
    {
        $sql = <<<SQL
        delete from db_sysforkey where codcam = 1013162;
        delete from db_sysarqcamp where codcam = 1013162;
        delete from db_syscampo where codcam = 1013162;

        delete from db_sysforkey where codcam = 1013163;
        delete from db_sysarqcamp where codcam = 1013163;
        delete from db_syscampo where codcam = 1013163;
SQL;
        $this->execute($sql);
    }
}
