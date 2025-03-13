<?php

use Classes\PostgresMigration;

class M11471FeedbackLoa extends PostgresMigration
{
    public function up()
    {
        $this->adicionarNullableColunas();
    }

    public function down()
    {
        $this->removerNullableColunas();
    }

    private function adicionarNullableColunas()
    {
        $this->table('teto_orcamentario', array('schema' => 'contabilidade'))
            ->removeColumn('c40_funcao')
            ->removeColumn('c40_subfuncao')
            ->removeColumn('c40_programa')
            ->save();

        $sql = "
            DELETE FROM db_sysforkey WHERE codcam IN (1009868, 1009869, 1009870);
            DELETE FROM db_sysarqcamp WHERE codcam IN (1009868, 1009869, 1009870);
            DELETE FROM db_syscampo WHERE codcam IN (1009868, 1009869, 1009870);
        ";
        $this->execute($sql);
    }

    private function removerNullableColunas()
    {
        $this->table('teto_orcamentario', array('schema' => 'contabilidade'))
            ->addColumn('c40_funcao', "integer", array("null" => true))
            ->addColumn('c40_subfuncao', "integer", array('null' => true))
            ->addColumn('c40_programa', "integer", array('null' => true))
            ->save();

        $sql = "
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial,rotulo, tamanho, nulo, aceitatipo, tipoobj, rotulorel)
            VALUES (1009868, 'c40_funcao', 'int4', 'Função', NULL, 'Função', 10, FALSE, 1, 'text', 'Função'),
                   (1009869, 'c40_subfuncao', 'int4', 'Subfunção', NULL, 'Subfunção', 10, FALSE, 1, 'text', 'Subfunção'),
                   (1009870, 'c40_programa', 'int4', 'Programa', NULL, 'Programa', 10, FALSE, 1, 'text', 'Programa');
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq)
            VALUES (1010300, 1009868, 5),
                   (1010300, 1009869, 6),
                   (1010300, 1009870, 7);

            INSERT INTO db_sysforkey
            VALUES (1010300, 1009868, 1, 750),
                   (1010300, 1009869, 1, 751),
                   (1010300, 1009870, 1, 752);

        ";
        $this->execute($sql);
    }
}
