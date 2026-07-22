<?php

use Classes\PostgresMigration;

class M12882AlteraInfosEmpregador extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            -- Torna respostas obrigatórias
            update avaliacaopergunta set db103_obrigatoria = 't' where db103_identificador in ('preencher-com-o-codigo-da-natureza-ju5a2ac5a3e8f65', 'indicativo_de_cooperativa5a2ac5a416ebf');

            -- Adicionar datainicio e datafim para o s1000
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values
                (4000250, 2, 3000196, 'Preencher com o mês e ano de início da validade das informações prestadas no evento, no formato AAAA-MM - Empregador', 'iniValid1000', 't', 't', 11, 1, '', 0, 'false', '', 'iniValid1000'),
                (4000251, 2, 3000196, 'Preencher com o mês e ano de término da validade das informações, se houver - Empregador', 'fimValid1000', 'f', 't', 12, 1, '', 0, 'false', '', 'fimValid1000');

            insert into avaliacaoperguntaopcao(db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_aceitatexto, db104_identificador, db104_peso, db104_valorresposta, db104_identificadorcampo) values
                (4001301, 4000250, '', 't', 'iniValid1000_2', 0, '', 'null'),
                (4001302, 4000251, '', 't', 'fimValid1000_2', 0, '' , 'null');

            -- Atualizar pergunta de datainicio e datafim para o s1005
            update avaliacaopergunta set db103_identificadorcampo = 'iniValid1005', db103_descricao = 'Preencher com o mês e ano de início da validade das informações prestadas no evento, no formato AAAA-MM - Estabelecimentos, Obras ou Unidades de Órgãos Públicos' where  db103_identificador = 'preencher_com_o_mes_e_ano_de_inicio_d5a2ac5a45db44';
            update avaliacaopergunta set db103_identificadorcampo = 'fimValid1005', db103_descricao = 'Preencher com o mês e ano de término da validade das informações, se houver - Estabelecimentos, Obras ou Unidades de Órgãos Públicos' where  db103_identificador = 'preencher_com_o_mes_e_ano_de_termino_5a2ac5a45ef7b';

            -- Desobrigar perguntas
            update avaliacaopergunta set db103_obrigatoria = 'f' where db103_identificador in ('tipo_de_caepf5a2ac5a4706f0', 'informar_o_numero_de_inscricao_da_ent5a2ac5a48000b');
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

            update avaliacaopergunta set db103_obrigatoria = 't' where db103_identificador in ('tipo_de_caepf5a2ac5a4706f0', 'informar_o_numero_de_inscricao_da_ent5a2ac5a48000b');

            update avaliacaopergunta set db103_identificadorcampo = 'iniValid', db103_descricao = 'Preencher com o mês e ano de início da validade das informações prestadas no evento, no formato AAAA-MM' where  db103_identificador = 'preencher_com_o_mes_e_ano_de_inicio_d5a2ac5a45db44';
            update avaliacaopergunta set db103_identificadorcampo = 'fimValid', db103_descricao = 'Preencher com o mês e ano de término da validade das informações, se houver' where db103_identificador = 'preencher_com_o_mes_e_ano_de_termino_5a2ac5a45ef7b';

            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in (select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (4000250, 4000251)));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in (select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (4000250, 4000251));
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (4000250, 4000251);
            delete from avaliacaopergunta where db103_sequencial in (4000250, 4000251);

            update avaliacaopergunta set db103_obrigatoria = 'f' where db103_identificador in ('preencher-com-o-codigo-da-natureza-ju5a2ac5a3e8f65', 'indicativo_de_cooperativa5a2ac5a416ebf');
SQL
        );
    }
}
