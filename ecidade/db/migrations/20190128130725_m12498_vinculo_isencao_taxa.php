<?php

use Classes\PostgresMigration;

class M12498VinculoIsencaoTaxa extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicicionario();
        $this->atualizaTabela();
    }

    public function down()
    {
        $this->removeDicionario();
        $this->retornaTabela();
    }

    private function adicionaDicicionario()
    {
        $sql  = <<<SQL
            insert into db_syscampo values(1010316,'j56_iptucadtaxaexe','int4','Código de vinculo com a tabela iptucadtaxaexe, onde descreve se isenção é separada do iptu utilizando a regra implementada em 2019.','0', 'Código da Taxa de IPTU no exercicio',10,'f','f','f',1,'text','Código da Taxa de IPTU no exercicio');
            delete from db_sysarqcamp where codarq = 113;
            insert into db_sysarqcamp values(113,577,1,0);
            insert into db_sysarqcamp values(113,578,2,0);
            insert into db_sysarqcamp values(113,579,3,0);
            insert into db_sysarqcamp values(113,1010316,4,0);
SQL;
        $this->execute($sql);
    }

    private function removeDicionario()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codarq = 113 and codcam = 1010316;
            delete from db_syscampo where codcam = 1010316;
SQL;
        $this->execute($sql);
    }

    private function atualizaTabela()
    {
        $sql = <<<SQL
ALTER TABLE isentaxa add column j56_iptucadtaxaexe int4 default null;

ALTER TABLE isentaxa ADD CONSTRAINT isentaxa_iptucadtaxaexe_fk FOREIGN KEY (j56_iptucadtaxaexe) REFERENCES iptucadtaxaexe;

alter table isentaxa alter column j56_iptucadtaxaexe drop not null;

alter table iptutaxanump alter column j151_numpre drop not null;

SQL;
        $this->execute($sql);

    }

    private function retornaTabela()
    {
        $sql = <<<SQL
        ALTER TABLE isentaxa drop column j56_iptucadtaxaexe;

SQL;
        $this->execute($sql);
    }
}
