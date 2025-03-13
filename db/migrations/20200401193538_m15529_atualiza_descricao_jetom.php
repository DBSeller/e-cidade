<?php

use Classes\PostgresMigration;

class M15529AtualizaDescricaoJetom extends PostgresMigration
{

    public function up()
    {
        $this->adicionaDicionarioDados();
        $this->atualizaQuantidade();
    }

    public function down()
    {
        $this->removeDicionarioDados();
    }

    public function adicionaDicionarioDados()
    {
        $sql = <<<SQL
            insert into db_syscampo values(
                1010882,
                'rh246_quantidade',
                'int4',
                'quantidade de sessões da função na comissão.',
                '0',
                'quantidade',
                10,
                'f',
                'f',
                'f',
                1,
                'text',
                'quantidade'
            );
            insert into db_sysarqcamp values(1010496,1010882,4,0);
SQL;
        $this->execute($sql);
    }

    public function atualizaQuantidade()
    {
        $sql = <<<SQL
            update  pessoal.jetomcomissaofuncao set rh246_quantidade = 3 where rh246_quantidade < 3;
SQL;
        $this->execute($sql);
    }

    public function removeDicionarioDados()
    {
        $sql = <<<SQL
             delete from db_sysarqcamp where codarq = 1010496 and codcam = 1010882;
             delete from db_syscampo  where codcam = 1010882;
SQL;
        $this->execute($sql);
    }
}
