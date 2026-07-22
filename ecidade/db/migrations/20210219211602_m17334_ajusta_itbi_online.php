<?php

use Classes\PostgresMigration;

class M17334AjustaItbiOnline extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downEstrutura();
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            INSERT INTO formareclamacao
                (
                    p42_sequencial,
                    p42_descricao
                )
            VALUES
                (
                    7,
                    'ITBI'
                );

            insert into configuracoes.db_documentotemplatetipo values (63, 'Autenticação de ITBI');

             insert into db_documentotemplatepadrao
                (
                    db81_sequencial,
                    db81_templatetipo,
                    db81_nomearquivo,
                    db81_descricao
                )
             values
                (
                    nextval('db_documentotemplatepadrao_db81_sequencial_seq'),
                    63,
                    'documentos/templates/documentos/templates/modelo_variaveis_autenticacao_itbi.docx',
                    'AUTENTICAÇÃO DE ITBI'
                );

SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            DELETE FROM formareclamacao WHERE p42_sequencial = 7;
SQL
        );
    }
}
