<?php

use Classes\PostgresMigration;

class M15704AddPerguntasFormularioCenso extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update avaliacaoperguntaopcao set db104_descricao = 'Banheiro'
            where  db104_sequencial = 3000013;
        ");
    }

    public function down()
    {
        $this->execute("
            update avaliacaoperguntaopcao set db104_descricao = 'Banheiro dentro do prédio'
             where  db104_sequencial = 3000013;
        ");
    }
}
