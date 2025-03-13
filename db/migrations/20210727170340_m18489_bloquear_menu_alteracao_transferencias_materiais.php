<?php

use Classes\PostgresMigration;

class M18489BloquearMenuAlteracaoTransferenciasMateriais extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            update db_itensmenu set id_item = 4335,
                                    descricao = 'Alteração',
                                    help = 'Alteração',
                                    funcao = 'mat1_mattransfdepto002.php',
                                    itemativo = '1',
                                    manutencao = '1',
                                    desctec = 'Alteração',
                                    libcliente = 'false'
                where id_item = 4335;

            update db_itensmenu set id_item = 5724,
                                    descricao = 'Estoque por Item',
                                    help = 'Relatório de Estoque por Item',
                                    funcao = 'mat2_relestoque001.php',
                                    itemativo = '1',
                                    manutencao = '1',
                                    desctec = 'Relatório de Estoque por Item.',
                                    libcliente = 'false'
                where id_item = 5724;

            update db_itensmenu set id_item = 4805,
                                    descricao = 'Controle de Estoque',
                                    help = 'Controle de Estoque',
                                    funcao = 'mat2_controlestaba001.php',
                                    itemativo = '1',
                                    manutencao = '1',
                                    desctec = 'Controle de Estoque',
                                    libcliente = 'false'
                where id_item = 4805;
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            update db_itensmenu set id_item = 4335,
                                    descricao = 'Alteração',
                                    help = 'Alteração',
                                    funcao = 'mat1_mattransfdepto002.php',
                                    itemativo = '1',
                                    manutencao = '1',
                                    desctec = 'Alteração',
                                    libcliente = 'true'
                where id_item = 4335;

            update db_itensmenu set id_item = 5724,
                                    descricao = 'Estoque por Item',
                                    help = 'Relatório de Estoque por Item',
                                    funcao = 'mat2_relestoque001.php',
                                    itemativo = '1',
                                    manutencao = '1',
                                    desctec = 'Relatório de Estoque por Item.',
                                    libcliente = 'true'
                where id_item = 5724;

            update db_itensmenu set id_item = 4805,
                                    descricao = 'Controle de Estoque',
                                    help = 'Controle de Estoque',
                                    funcao = 'mat2_controlestaba001.php',
                                    itemativo = '1',
                                    manutencao = '1',
                                    desctec = 'Controle de Estoque',
                                    libcliente = 'true'
                where id_item = 4805;
sql
        );
    }
}
