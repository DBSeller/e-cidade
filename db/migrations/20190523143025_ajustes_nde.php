<?php

use Classes\PostgresMigration;

class AjustesNde extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            delete from db_syscampodef where codcam = 17759;

            update db_syscampo set valorinicial = '' where codcam = 1010479;

            update avaliacaopergunta set db103_obrigatoria = 't' where db103_sequencial in (3000878,3000879);
            update avaliacaopergunta set db103_identificadorcampo = 'iniValid1005', db103_descricao = 'Preencher com o mês e ano de início da validade das informações prestadas no evento, no formato AAAA-MM - Estabelecimentos, Obras ou Unidades de Órgãos Públicos' where db103_sequencial = 3000918;
            update avaliacaopergunta set db103_identificadorcampo = 'fimValid1005', db103_descricao = 'Preencher com o mês e ano de término da validade das informações, se houver - Estabelecimentos, Obras ou Unidades de Órgãos Públicos' where db103_sequencial = 3000919;
            update avaliacaopergunta set db103_obrigatoria = 'f' where db103_sequencial in (3000930, 3000936);
SQL
        );
    }

    public function down()
    {
        $this->execute(
            <<<SQL
            insert into db_syscampodef (codcam, defcampo, defdescr) values
                (17759, 1, 'Executivo'),
                (17759, 2, 'Legislativo'),
                (17759, 3, 'Judiciário'),
                (17759, 4, 'Ministério Público'),
                (17759, 5, 'Tribunal de Contas'),
                (17759, 6, 'Outros');

                update avaliacaopergunta set db103_obrigatoria = 't' where db103_sequencial in (3000930, 3000936);
                update avaliacaopergunta set db103_identificadorcampo = 'iniValid', db103_descricao = 'Preencher com o mês e ano de início da validade das informações prestadas no evento, no formato AAAA-MM' where db103_sequencial = 3000918;
                update avaliacaopergunta set db103_identificadorcampo = 'fimValid', db103_descricao = 'Preencher com o mês e ano de término da validade das informações, se houver' where db103_sequencial = 3000919;
                update avaliacaopergunta set db103_obrigatoria = 'f' where db103_sequencial in (3000878,3000879);
SQL
        );
    }
}
