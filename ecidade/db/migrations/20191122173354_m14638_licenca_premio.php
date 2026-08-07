<?php

use Classes\PostgresMigration;

class M14638LicencaPremio extends PostgresMigration
{
    public function up() {
        $sql = <<<SQL
            insert into db_syscampo values(1010799,'r33_rublicencapremio','varchar(4)','Configuração da Rubrica de Licença Prêmio.','', 'Rubrica Licença Prêmio',4,'t','f','f',1,'text','Rubrica Licença Prêmio');
            insert into db_sysarqcamp values(561,1010799,23,0);
            
            alter table pessoal.inssirf add column r33_rublicencapremio varchar(4) default null;
            INSERT INTO situacaoafastamento VALUES(11, 'Licença Prêmio');
SQL;
        $this->execute($sql);
    }

    public function down() {
        $sql = <<<SQL
            delete from db_sysarqcamp where codcam = 1010799;
            delete from db_syscampo where codcam = 1010799;

            alter table pessoal.inssirf drop column r33_rublicencapremio;
            delete from situacaoafastamento where rh166_sequencial = 11;
SQL;
        $this->execute($sql);
    }
}
