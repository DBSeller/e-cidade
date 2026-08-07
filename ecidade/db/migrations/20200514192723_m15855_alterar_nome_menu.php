<?php

use Classes\PostgresMigration;

class M15855AlterarNomeMenu extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_itensmenu set id_item = 9154,
                                descricao = 'Arquivo de Identificação',
                                help = 'censo escolar' , itemativo = '1',
                                manutencao = '1',
                                desctec = 'Solicitação de informaçoes de docentes e alunos que não tem o código inep.',
                                libcliente = 'true'
                            where id_item = 9154;");
    }

    public function down()
    {
        $this->execute("update db_itensmenu set id_item = 9154,
                                descricao = 'Consulta de alunos e docentes sem INEP',
                                help = 'censo escolar' , itemativo = '1',
                                manutencao = '1',
                                desctec = 'Solicitação de informaçoes de docentes e alunos que não tem o código inep.',
                                libcliente = 'true'
                            where id_item = 9154;");
    }
}
