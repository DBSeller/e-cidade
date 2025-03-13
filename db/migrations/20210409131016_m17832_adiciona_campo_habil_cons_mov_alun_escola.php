<?php

use Classes\PostgresMigration;

class M17832AdicionaCampoHabilConsMovAlunEscola extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            INSERT INTO db_syscampo
            VALUES(
                   1013156,
                   'ed290_habilconsmovalunescola',
                   'bool',
                   'Faz com que a aba Movimentação Escolar na rotina Escola > Consultas > Alunos seja acessível apenas pelo módulo Secretaria.','f', 'Habil. Cons. Mov. Aluno Por Escola',1,'f','f','f',5,'text','Habil. Cons. Mov. Aluno Por Escola');

            INSERT INTO db_sysarqcamp
            VALUES(3180,1013156,7,0);

            ALTER TABLE sec_parametros
            ADD COLUMN ed290_habilconsmovalunescola BOOLEAN NOT NULL DEFAULT FALSE;

            -- Adiciona NOT NULL corrigindo a tarefa M17831
            ALTER TABLE sec_parametros ALTER COLUMN ed290_habilitaconsultaalunoporescola SET NOT NULL
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            DELETE FROM db_sysarqcamp WHERE codcam = 1013156;

            DELETE FROM db_syscampo WHERE codcam = 1013156;

            ALTER TABLE sec_parametros DROP COLUMN ed290_habilconsmovalunescola;

            ALTER TABLE sec_parametros ALTER COLUMN ed290_habilitaconsultaalunoporescola DROP NOT NULL
SQL
        );
    }
}
