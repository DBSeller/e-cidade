<?php

use Classes\PostgresMigration;

class M13694AlteraOpcoesVinculoTrabalho extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            update
                avaliacaoperguntaopcao
            set
                db104_descricao = 'Sim (Cadastramento Inicial)'
            where db104_identificador = 'sim59f219977c4dc';

            update
                avaliacaoperguntaopcao
            set
                db104_descricao = 'Não (Admissão)'
            where db104_identificador = 'nao59f219977e5c0';
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            update
                avaliacaoperguntaopcao
            set
                db104_descricao = 'Sim'
            where db104_identificador = 'sim59f219977c4dc';

            update
                avaliacaoperguntaopcao
            set
                db104_descricao = 'Não'
            where db104_identificador = 'nao59f219977e5c0';
SQL
        );
    }
}
