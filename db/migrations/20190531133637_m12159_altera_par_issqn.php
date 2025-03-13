<?php

use Classes\PostgresMigration;

class M12159AlteraParIssqn extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            alter table parissqn add q60_parcelasissqn integer default null;

            insert into db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                             values (1010538, 'q60_parcelasissqn', 'int4', 'Informar o limite de parcelas do ISSQN fixo (deixe 0 para o comportamento padrão).', '0', 'Limite Parcelas ISSQN Fixo', 5, 't', 'f', 'f', 1, 'text', 'Limite Parcelas ISSQN Fixo');
            insert into db_sysarqcamp values(664,1010538,30,0);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            alter table parissqn drop column q60_parcelasissqn;

            delete from db_sysarqcamp where codcam = 1010538;
            delete from db_syscampo where codcam = 1010538;
SQL
        );
    }

}
