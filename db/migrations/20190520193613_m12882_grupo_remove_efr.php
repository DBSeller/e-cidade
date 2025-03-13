<?php

use Classes\PostgresMigration;

class M12882GrupoRemoveEfr extends PostgresMigration
{
    public function up()
    {
        $this->deletaGrupo('3000200');
    }

    public function down()
    {
        $this->execute(<<<SQL
            insert into avaliacaogrupopergunta (db102_sequencial, db102_avaliacao, db102_descricao, db102_identificador, db102_identificadorcampo, db102_ordem) values
                (3000200, 3000015, 'Informações relativas a Ente Federativo Responsável - EFR', 'informacoes-relativas-a-ente-federati5a2ac5a43c672', 'infoEFR', 1);

            insert into avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_obrigatoria, db103_ativo, db103_ordem, db103_identificador, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo) values
                (3000901, 2, 3000200, 'CNPJ do Ente Federativo Responsável - EFR.', 'f', 't', 2, 'cnpj_do_ente_federativo_responsavel_5a2ac5a43e921', 3, '', 0, 'f', '', 'cnpjEFR'),
                (3000900, 1, 3000200, 'Informar se o Órgão Público é o Ente Federativo Responsável - EFR ou se é uma unidade administrativa autônoma vinculada a um EFR.', 'f', 't', 1, 'informar_se_o_orgao_publico_e_o_ente_5a2ac5a43cdbd', 1, '', 0, 'f', '', 'ideEFR');

            insert into avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_aceitatexto, db104_identificador, db104_peso, db104_valorresposta, db104_identificadorcampo) values
                (3003700, 3000901, '', 't', '5a2ac5a43f4ea', 0, '', 'cnpjEFR'),
                (3003698, 3000900, 'É EFR', 'f', 'e-efr5a2ac5a43d98e', 0, 'S', 'ideEFR_S'),
                (3003699, 3000900, 'Não é EFR', 'f', 'nao-e-efr5a2ac5a43e15f', 0, 'N', 'ideEFR_N');
SQL
        );
    }

    private function deletaGrupo($db102_sequencial) {
        $this->execute(<<<SQL
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial})));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial}));
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial});
            delete from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial};
            delete from avaliacaogrupopergunta where db102_sequencial = ${db102_sequencial};
SQL
        );
    }
}
