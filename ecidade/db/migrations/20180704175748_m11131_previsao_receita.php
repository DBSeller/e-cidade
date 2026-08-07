<?php

use Classes\PostgresMigration;

class M11131PrevisaoReceita extends PostgresMigration
{
    public function up()
    {
        $this->cadastrarMenu();
        $this->cadastrarTipoFormulario();
        $this->cadastrarFormulario();
        $this->cadastrarPrevisaoReceita();
    }

    private function cadastrarMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) 
            VALUES (10541, 'Previsão de Receita', 'Previsão de Receita', 'con1_previsao_receita.php', '1', '1', 'Previsão de Receita', 'false');
            
            DELETE FROM db_menu
            WHERE id_item_filho = 10541 AND modulo = 209;
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo) 
            VALUES (29, 10541, 280, 209);
        ";

        $this->execute($sql);
    }

    private function cadastrarTipoFormulario()
    {
        $sql = "
            SELECT setval('avaliacaotipo_db100_sequencial_seq', (SELECT max(db100_sequencial) FROM avaliacaotipo));
            INSERT INTO avaliacaotipo VALUES (nextval('avaliacaotipo_db100_sequencial_seq'), 'Previsão de Receita');
        ";

        $this->execute($sql);
    }

    private function cadastrarFormulario()
    {
        $sql = "
            INSERT INTO avaliacao (db101_sequencial, db101_avaliacaotipo, db101_descricao, db101_identificador, db101_obs, db101_ativo, db101_cargadados, db101_permiteedicao)
            VALUES (3000024, 7, 'Previsão de Receita', 'previsao-de-receita5b3e07432a75d', 'Formulário', 'true', '', 'true');
            
            INSERT INTO avaliacaogrupopergunta (db102_sequencial, db102_avaliacao, db102_descricao, db102_identificador, db102_identificadorcampo, db102_ordem)
            VALUES (3000259, 3000024, 'Previsão', 'previsao5b3e074334812', 'previsao', 1);
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001095, 1, 3000259, 'Esfera Orçamentária', 'esfera-orcamentaria5b3e07433603f', 'true', 'true', 1, 1, '', 0, 'false', '', 'esferaOrcamentaria');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004115, 3001095, '10 - F - Orçamento Fiscal', '10-f-orcamento-fiscal5b3e074340658', 'false', 0, '10', 'opcaoEsfera10');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004116, 3001095, '20 - S - Orçamento da Seguridade Social', '20-s-orcamento-da-seguridade-soci5b3e074344005', 'false', 0, '20', 'opcaoEsfera20');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004117, 3001095, '30 - I - Orçamento de Investimento', '30-i-orcamento-de-investimento5b3e0743461c1', 'false', 0, '30', 'opcaoEsfera30');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001096, 2, 3000259, 'Unidade Orçamentária', 'unidade-orcamentaria5b3e07434780a', 'true', 'true', 2, 1, '', 0, 'false', '', 'unidadeOrcamentaria');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004118, 3001096, '', '5b3e074349155', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001097, 2, 3000259, 'Unidade Gestora', 'unidade-gestora5b3e07434a0d9', 'true', 'true', 3, 1, '', 0, 'false', '', 'unidadeGestora');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004119, 3001097, '', '5b3e07434b1d4', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001098, 1, 3000259, 'Indicador Resultado Primário', 'indicador-resultado-primario5b3e07434be5e', 'true', 'true', 4, 1, '', 0, 'false', '', 'indicadorResultadoPrimario');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004120, 3001098, 'Financeira', 'financeira5b3e07434d6ae', 'false', 0, '1', '');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004121, 3001098, 'Primária', 'primaria5b3e07434e0e6', 'false', 0, '2', '');
            
            INSERT INTO avaliacaogrupopergunta (db102_sequencial, db102_avaliacao, db102_descricao, db102_identificador, db102_identificadorcampo, db102_ordem)
            VALUES (3000260, 3000024, 'Fonte de Recurso', 'fonte-de-recurso5b3e07434eb7e', 'fonteRecurso', 1);
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001099, 2, 3000260, 'Número do Identificador de Uso', 'numero-do-identificador-de-uso5b3e07434f429', 'true', 'true', 1, 6, '', 0, 'false', '', 'numeroIdentificadorUso');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004122, 3001099, '', '5b3e0743501b0', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001100, 2, 3000260, 'Descrição do Identificador de Uso', 'descricao-do-identificador-de-uso5b3e074350d84', 'true', 'true', 2, 1, '', 0, 'false', '', 'descricaoIdentificadorUso');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004123, 3001100, '', '5b3e074351cb6', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001102, 2, 3000260, 'Especificação', 'especificacao5b3e074353ebc', 'true', 'true', 4, 1, '', 0, 'false', '', 'especificacao');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004125, 3001102, '', '5b3e074354e89', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001103, 2, 3000260, 'Número do Tipo de Detalhamento', 'numero-do-tipo-de-detalhamento5b3e0743557ed', 'true', 'true', 5, 1, '', 0, 'false', '', 'numeroTipoDetalhamento');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004126, 3001103, '', '5b3e074358653', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001104, 2, 3000260, 'Descrição do Tipo de Detalhamento', 'descricao-do-tipo-de-detalhamento5b3e07435915b', 'true', 'true', 6, 1, '', 0, 'false', '', 'descricaoTipoDetalhamento');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004127, 3001104, '', '5b3e07435b452', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001105, 2, 3000260, 'Detalhamento do Fonte', 'detalhamento-do-fonte5b3e07435bcfe', 'true', 'true', 7, 1, '', 0, 'false', '', 'detalhamentoFonte');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004128, 3001105, '', '5b3e07435cb06', 'true', 0, '', '');
        ";

        $this->execute($sql);
    }

    private function excluirFormulario()
    {
        $sql = "
            DELETE FROM avaliacaoperguntaopcao
            WHERE db104_avaliacaopergunta IN (
              SELECT db103_sequencial
              FROM avaliacaopergunta
              WHERE db103_avaliacaogrupopergunta IN (
                SELECT db102_sequencial
                FROM avaliacaogrupopergunta
                WHERE db102_avaliacao = 3000024
              )
            );
            
            DELETE FROM avaliacaopergunta
            WHERE db103_avaliacaogrupopergunta IN (
              SELECT db102_sequencial
              FROM avaliacaogrupopergunta
              WHERE db102_avaliacao = 3000024
            );
            
            DELETE FROM avaliacaogrupopergunta
            WHERE db102_avaliacao = 3000024;
            
            DELETE FROM avaliacao
            WHERE db101_sequencial = 3000024;
        ";

        $this->execute($sql);
    }

    private function cadastrarPrevisaoReceita()
    {
        $sql = "
            INSERT INTO db_sysarquivo 
            VALUES (1010294, 'avaliacaogruporespostaconta', 'Tabela de vínculo da avaliação com a conta', 'c06', '2018-07-05', 'Avaliação Grupo Resposta Conta', 0, 'f', 'f', 'f', 'f');
            
            INSERT INTO db_sysarqmod 
            VALUES (32, 1010294);
            
            INSERT INTO db_syscampo 
            VALUES (1009812, 'c06_sequencial', 'int4', 'Sequencial da Tabela', '0', 'Sequencial', 11, 'f', 'f', 'f', 1, 'text', 'Sequencial');
            
            INSERT INTO db_syscampo 
            VALUES (1009813, 'c06_avaliacaogruporesposta', 'int4', 'Código da Avaliação Grupo Resposta', '0', 'Avaliação Grupo Resposta', 11, 'f', 'f', 'f', 1, 'text', 'Avaliação Grupo Resposta');
            
            INSERT INTO db_syscampo
            VALUES (1009814, 'c06_conta', 'int4', 'Código da Conta', '0', 'Conta', 11, 'f', 'f', 'f', 1, 'text', 'Conta');
            
            INSERT INTO db_syscampo
            VALUES (1009815, 'c06_ano', 'int4', 'Ano da Conta', '0', 'Ano', 11, 'f', 'f', 'f', 1, 'text', 'Ano');
            
            INSERT INTO db_sysarqcamp VALUES (1010294, 1009812, 1, 0);
            INSERT INTO db_sysarqcamp VALUES (1010294, 1009813, 3, 0);
            INSERT INTO db_sysarqcamp VALUES (1010294, 1009814, 2, 0);
            INSERT INTO db_sysarqcamp VALUES (1010294, 1009815, 4, 0);
            
            INSERT INTO db_sysforkey VALUES(1010294,1009813,1,2987,0);
            INSERT INTO db_sysforkey VALUES(1010294,1009814,1,774,0);
            INSERT INTO db_sysforkey VALUES(1010294,1009815,1,774,0);
            
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010294,1009812,1,1009812);
            
            insert into db_syssequencia values(1000744, 'avaliacaogruporespostaconta_c06_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000744 where codarq = 1010294 and codcam = 1009812;
            insert into db_sysindices values(1008298,'avaliacaogruporespostaconta_conta_ano_in',1010294,'0');
            insert into db_syscadind values(1008298,1009814,1);
            insert into db_syscadind values(1008298,1009815,2);
            insert into db_sysindices values(1008299,'avaliacaogruporespostaconta_avaliacaogruporesposta_in',1010294,'0');
            insert into db_syscadind values(1008299,1009813,1);
        ";

        $this->execute($sql);

        $table = $this->table('avaliacaogruporespostaconta', array(
            'id' => 'c06_sequencial',
            'schema' => 'contabilidade'
        ));

        $table
            ->addColumn('c06_avaliacaogruporesposta', 'integer', array('null' => false))
            ->addColumn('c06_conta', 'integer', array('null' => false))
            ->addColumn('c06_ano', 'integer', array('null' => false))
            ->save();

        $sql = "
            ALTER TABLE avaliacaogruporespostaconta
            ADD CONSTRAINT avaliacaogruporespostaconta_avaliacaogruporesposta_fk FOREIGN KEY (c06_avaliacaogruporesposta)
            REFERENCES avaliacaogruporesposta;
            
            ALTER TABLE avaliacaogruporespostaconta
            ADD CONSTRAINT avaliacaogruporespostaconta_conta_fk FOREIGN KEY (c06_conta, c06_ano)
            REFERENCES conplano;
            
            CREATE INDEX avaliacaogruporespostaconta_conta_ano_in ON avaliacaogruporespostaconta(c06_conta,c06_ano);
            
            CREATE INDEX avaliacaogruporespostaconta_avaliacaogruporesposta_in ON avaliacaogruporespostaconta(c06_avaliacaogruporesposta);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $this->excluirMenu();
        $this->excluirFormulario();
        $this->excluirTipoFormulario();
        $this->excluirPrevisaoReceita();
    }

    private function excluirMenu()
    {
        $sql = "
            DELETE FROM db_menu WHERE id_item_filho = 10541 AND modulo = 209;
            DELETE FROM db_itensmenu WHERE id_item = 10541;
        ";

        $this->execute($sql);
    }

    private function excluirTipoFormulario()
    {
        $sql = "
            DELETE FROM avaliacaotipo WHERE db100_sequencial = 7;
            SELECT setval('avaliacaotipo_db100_sequencial_seq', (SELECT max(db100_sequencial) FROM avaliacaotipo));
        ";

        $this->execute($sql);
    }

    private function excluirPrevisaoReceita()
    {
        $this->table('avaliacaogruporespostaconta', array('schema' => 'contabilidade'))
            ->drop();

        $sql = "
            DELETE FROM db_syscadind WHERE codind IN (1008298, 1008299);
            DELETE FROM db_sysindices WHERE codind IN (1008298, 1008299);
            DELETE FROM db_syssequencia WHERE codsequencia = 1000744;
            DELETE FROM db_sysprikey WHERE codarq = 1010294;
            DELETE FROM db_sysforkey WHERE codarq = 1010294;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010294;
            DELETE FROM db_syscampo WHERE codcam IN (1009812, 1009813, 1009814, 1009815);
            DELETE FROM db_sysarqmod WHERE codarq = 1010294;
            DELETE FROM db_sysarquivo WHERE codarq = 1010294;
        ";

        $this->execute($sql);
    }
}
