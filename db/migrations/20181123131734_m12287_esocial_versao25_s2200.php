<?php

use Classes\PostgresMigration;

class M12287EsocialVersao25S2200 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO avaliacaogrupopergunta(db102_sequencial, db102_avaliacao, db102_descricao, db102_identificador, db102_identificadorcampo, db102_ordem)
            VALUES (3000521, 3000013, 'Informações de mudança do CPF do trabalhador', 'informacoes-de-mudanca-de-cpf-do-trabalhador', 'mudancaCPF', 52);
            
            INSERT INTO avaliacaopergunta(db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3002361, 2, 3000183, 'Indicação do objeto determinante da contratação por prazo determinado', 'indicacao-do-objeto-determinante-da-contratacao-po', 'false',  'true', 4, 1, '', 0, 'false', '', 'objDet'),
                   (3002362, 2, 3000188, 'Código correspondente ao tipo de inscrição (Conforme tabela 05)', 'preencher-com-o-codigo-correspondente-ao-tipo-de-i', 'true', 'true', 1, 1, '', 0, 'false', '', 'tpInscAnt'),
                   (3002363, 2, 3000521, 'Número do CPF antigo do trabalhador', 'preencher-com-o-numero-do-cpf-antigo-do-trabalhado', 'false', 'true', 1, 4, '', 0, 'false', '', 'cpfAnt'),
                   (3002364, 2, 3000521, 'Matrícula anterior do trabalhador', 'matricula-do-trabalhador-no-representante-anterior', 'false', 'true', 2, 1, '', 0, 'false', '', 'matricAnt'),
                   (3002365, 2, 3000521, 'Data de alteração do CPF', 'data-de-alteracao-do-cpf', 'false', 'true', 3, 5, '', 0, 'false', '', 'dtAltCPF'),
                   (3002366, 2, 3000521, 'Observação', 'observacao5bf7fb636fda7', 'false', 'true', 4, 1, '', 0, 'false', '', 'mudancaCPF_observacao');
            
            INSERT INTO avaliacaoperguntaopcao(db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (4000898, 3000800, 'Mudança de CPF', 'mudanca-de-cpf', 'false', 0, '6', 'tpAdmissao_6'),
                   (4000899, 3000833, 'Prazo determinado, vinculado à ocorrência de um fato.', 'prazo-determinado-vinculado-a-ocorrencia-de-um-fat', 'false', 0, '3', 'duracao_tpContr_3'),
                   (4000900, 3002361, '', '5bf7fb6170f65', 'true', 0, '', 'objDet'),
                   (4000901, 3002362, '', '5bf7fb631829f', 'true', 0, '', 'tpInscAnt'),
                   (4000902, 3002363, '', '5bf7fb635c1fa', 'true', 0, '', 'cpfAnt'),
                   (4000903, 3002364, '', '5bf7fb636480c', 'true', 0, '', 'matricAnt'),
                   (4000904, 3002365, '', '5bf7fb636c8da', 'true', 0, '', 'dtAltCPF'),
                   (4000905, 3002366, '', '5bf7fb6374d8e', 'true', 0, '', 'mudancaCPF_observacao');
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE
            FROM avaliacaoperguntaopcao
            WHERE db104_sequencial IN (4000898, 4000899, 4000900, 4000901, 4000902, 4000903, 4000904, 4000905);
            
            DELETE
            FROM avaliacaopergunta
            WHERE db103_sequencial IN (3002361, 3002362, 3002363, 3002364, 3002365, 3002366);
            
            DELETE
            FROM avaliacaogrupopergunta
            WHERE db102_sequencial = 3000521;
        ";

        $this->execute($sql);
    }
}
