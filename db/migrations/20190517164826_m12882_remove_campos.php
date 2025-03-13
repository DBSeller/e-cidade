<?php

use Classes\PostgresMigration;

class M12882RemoveCampos extends PostgresMigration
{
    public function up()
    {
        $this->deletaGrupo('3000199');
        $this->deletaGrupo('3000201');
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

    public function down()
    {
        $this->execute(<<<SQL
            insert into avaliacaogrupopergunta (db102_sequencial, db102_avaliacao, db102_descricao, db102_identificador, db102_identificadorcampo, db102_ordem) values
                (3000201, 3000015, 'Informações relativas ao ente federativo estadual, distrital ou municipal', 'informacoes-relativas-ao-ente-federat5a2ac5a43fd23', 'infoEnte', 1),
                (3000199, 3000015, 'Informações relativas a Órgãos Públicos', 'informacoes-relativas-a-orgaos-public5a2ac5a43a913', 'infoOP', 1);

            insert into avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_obrigatoria, db103_ativo, db103_ordem, db103_identificador, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo) values
                (3000907, 2, 3000201, 'Preencher com o valor do subteto do Ente Federativo.', 'f', 't', 6, 'preencher_com_o_valor_do_subteto_do_e5a2ac5a448c18', 8, '', 0, 'f', '', 'vrSubteto'),
                (3000905, 1, 3000201, 'Informar se o ente público possui Regime Próprio de Previdência Social - RPPS.', 'f', 't', 4, 'informar_se_o_ente_publico_possui_reg5a2ac5a4443d6', 1, '', 0, 'f', '', 'indRPPS'),
                (3000902, 2, 3000201, 'Nome do Ente Federativo ao qual o órgão está vinculado', 'f', 't', 1, 'nome_do_ente_federativo_ao_qual_o_org5a2ac5a4404a0', 1, '', 0, 'f', '', 'nmEnte'),
                (3000904, 2, 3000201, 'Preencher com o código do município, conforme tabela do IBGE.', 'f', 't', 3, 'preencher_com_o_codigo_do_municipio_5a2ac5a442ecd', 1, '', 0, 'f', '', 'codMunic'),
                (3000903, 2, 3000201, 'Preencher com a sigla da Unidade da Federação.', 'f', 't', 2, 'preencher_com_a_sigla_da_unidade_da_f5a2ac5a441a47', 1, '', 0, 'f', '', 'uf'),
                (3000906, 1, 3000201, 'Preencher com o poder a que se refere o subteto:', 'f', 't', 5, 'preencher_com_o_poder_a_que_se_refere5a2ac5a445ffc', 1, '', 0, 'f', '', 'subteto'),
                (3000899, 2, 3000199, 'Preencher com o número SIAFI - Sistema Integrado de Administração Financeira, caso seja órgão público usuário do sistema.', 'f', 't', 1, 'preencher_com_o_numero_siafi_sistem5a2ac5a43b007', 1, '', 0, 'f', '', 'nrSiafi');

            insert into avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_aceitatexto, db104_identificador, db104_peso, db104_valorresposta, db104_identificadorcampo) values
                (3003710, 3000907, '', 't', '5a2ac5a449956', 0, '', 'vrSubteto'),
                (3003704, 3000905, 'Sim', 'f', 'sim5a2ac5a445096', 0, 'S', 'indRPPS_S'),
                (3003705, 3000905, 'Não', 'f', 'nao5a2ac5a44584e', 0, 'N', 'indRPPS_N'),
                (3003701, 3000902, '', 't', '5a2ac5a441256', 0, '', 'nmEnte'),
                (3003703, 3000904, '', 't', '5a2ac5a443aaa', 0, '', 'codMunic'),
                (3003702, 3000903, '', 't', '5a2ac5a4426c0', 0, '', 'uf'),
                (3003706, 3000906, 'Executivo', 'f', 'executivo5a2ac5a446c05', 0, 1, 'subteto_1'),
                (3003707, 3000906, 'Judiciário', 'f', 'judiciario5a2ac5a447465', 0, 2, 'subteto_2'),
                (3003708, 3000906, 'Legislativo', 'f', 'legislativo5a2ac5a447c4b', 0, 3, 'subteto_3'),
                (3003709, 3000906, 'Todos os poderes', 'f', 'todos-os-poderes5a2ac5a4483f4', 0, 9, 'subteto_9'),
                (3003697, 3000899, '', 't', '5a2ac5a43be61', 0, '', 'nrSiafi');
SQL
        );
    }
}
