<?php

use Classes\PostgresMigration;

class M16508AlteracoesColunaTempoFuncionamento extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDDL();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL

            delete from db_sysarqcamp where codcam in (1011826);
            delete from db_syscampodef where codcam = 1011826;
            delete from db_syscampodep where codcam = 1011826;
            DELETE FROM db_syscampo where codcam in (1011826);
            insert into db_syscampo values(1011839,'q30_tempofuncionamento','float8','Tempo de Funcionamento em horas','0', 'Tempo Funcionamento',10,'t','f','f',4,'text','Tempo de Funcionamento (Horas)');
            delete from db_sysarqcamp where codarq = 47;
            insert into db_sysarqcamp values(47,285,1,0);
            insert into db_sysarqcamp values(47,286,2,0);
            insert into db_sysarqcamp values(47,287,3,0);
            insert into db_sysarqcamp values(47,288,4,0);
            insert into db_sysarqcamp values(47,7428,5,0);
            insert into db_sysarqcamp values(47,1011839,6,0);

SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL

            delete from db_sysarqcamp where codcam in (1011839);
            delete from db_syscampodef where codcam = 1011839;
            delete from db_syscampodep where codcam = 1011839;
            DELETE FROM db_syscampo where codcam in (1011839);

SQL
        );
    }

    private function upDDL()
    {
        $this->execute(<<<SQL

            ALTER TABLE issqn.issbase
             DROP COLUMN q02_tempofuncionamento;

            ALTER TABLE issqn.issquant
              ADD COLUMN q30_tempofuncionamento DECIMAL(4, 2);

SQL
        );
    }

    private function downDDL()
    {
        $this->execute(<<<SQL

            ALTER TABLE issqn.issquant
             DROP COLUMN q30_tempofuncionamento;

            ALTER TABLE issqn.issbase
              ADD COLUMN q02_tempofuncionamento DECIMAL(4, 2);

SQL
        );
    }
}
