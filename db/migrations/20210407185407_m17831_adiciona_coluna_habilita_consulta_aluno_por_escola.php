<?php

use Classes\PostgresMigration;

class M17831AdicionaColunaHabilitaConsultaAlunoPorEscola extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            INSERT INTO db_syscampo
            VALUES (1013144,
                'ed290_habilitaconsultaalunoporescola',
                'bool',
                'Faz com que o menu de opções Escola na rotina Escola > Consultas > Alunos traga apenas a escola do departamento selecionado ao invés de todas as escolas. Ainda será possível ver todas as escolas se a mesma rotina for acessada pelo menu Secretaria.',
                'f',
                'Habilita Consulta Aluno Por Escola',
                1,
                'f',
                'f',
                'f',
                0,
                'text',
                'Habilita Consulta Aluno Por Escola'
            );

            INSERT INTO db_sysarqcamp
            VALUES (3180, 1013144, 6, 0);

            ALTER TABLE sec_parametros
            ADD COLUMN ed290_habilitaconsultaalunoporescola BOOLEAN DEFAULT FALSE;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            DELETE FROM db_sysarqcamp WHERE codcam = 1013144;

            DELETE FROM db_syscampo WHERE codcam = 1013144;

            ALTER TABLE sec_parametros DROP COLUMN ed290_habilitaconsultaalunoporescola;
SQL
        );
    }
}
