<?php

use Classes\PostgresMigration;

class M11020CensoOutrosCursos extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in (3000083, 3000085));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao  in (3000083, 3000085);
            delete from avaliacaoperguntaopcao where db104_sequencial in (3000083, 3000085); 
SQL
        );

    }

    public function down()
    {
        $this->execute(<<<SQL
            insert into avaliacaoperguntaopcao values (3000083, 3000013, 'Específico para Educação - Modalidade substitutiva', false);
            insert into avaliacaoperguntaopcao values (3000085, 3000013, 'Intercultural / Diversidade', false);
SQL
        );
    }
}
