<?php

use Classes\PostgresMigration;

class M10174S2298Reintegracao extends PostgresMigration
{
    public function up()
    {
        $this->inserirFormulario();
        $this->inserirMenu();
    }

    private function inserirFormulario()
    {
        $sql = "
            INSERT INTO avaliacao (db101_sequencial, db101_avaliacaotipo, db101_descricao, db101_identificador, db101_obs, db101_ativo, db101_cargadados, db101_permiteedicao)
            VALUES (3000031, 5, 'S-2298 - Reintegração', 's2298-reintegracao', 'S-2298 - Reintegração', 'true', '', 'true');
            
            INSERT INTO avaliacaogrupopergunta (db102_sequencial, db102_avaliacao, db102_descricao, db102_identificador, db102_identificadorcampo, db102_ordem)
            VALUES (3000477, 3000031, 'Informações de Identificação do Trabalhador e do Vínculo', 'informacoes-de-identificacao-do-traba5b9fafa529609', 'ideVinculo', 1),
                   (3000478, 3000031, 'Reintegração', 'reintegracao', 'infoReintegr', 2);
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3002143, 2, 3000477, 'Preencher com o número do CPF do trabalhador', 'preencher-com-o-numero-do-cpf-do-trab5b9fafa539afd', 'true', 'true', 1, 4, '', 0, 'false', '', 'cpfTrab'),
                   (3002144, 2, 3000477, 'Preencher com o Número de Identificação Social - NIS, o qual pode ser o PIS, PASEP ou NIT', 'preencher-com-o-numero-de-identificac5b9fafa55dd04', 'true', 'true', 2, 1, '', 0, 'false', '', 'nisTrab'),
                   (3002145, 2, 3000477, 'Matrícula atribuída ao trabalhador', 'matricula-atribuida-ao-trabalhador5b9fafa560594', 'true', 'true', 3, 6, '', 0, 'false', '', 'matricula'),
                   (3002146, 1, 3000478, 'Tipo de Reintegração', 'tipo-de-reintegracao', 'true', 'true', 1, 1, '', 0, 'false', '', 'tpReint'),
                   (3002147, 2, 3000478, 'Em caso de reintegração por determinação judicial, preencher com o número do processo', 'em-caso-de-reintegracao-por-determinacao-judicial-', 'false', 'true', 2, 1, '', 0, 'false', '', 'nrProcJud'),
                   (3002148, 2, 3000478, 'Informar a Lei de Anistia, descrevendo seu número e ano de publicação', 'informar-a-lei-de-anistia-descrevendo-seu-numero-e', 'false', 'true', 3, 1, '', 0, 'false', '', 'nrLeiAnistia'),
                   (3002149, 2, 3000478, 'Informar a data do efetivo retorno ao trabalho', 'informar-a-data-do-efetivo-retorno-ao-trabalho', 'true', 'true', 4, 5, '', 0, 'false', '', 'dtEfetRetorno'),
                   (3002150, 2, 3000478, 'Data de início dos efeitos financeiros da reintegração', 'data-de-inicio-dos-efeitos-financeiros-da-reintegr', 'true', 'true', 5, 5, '', 0, 'false', '', 'dtEfeito'),
                   (3002151, 1, 3000478, 'Indicar se as remunerações e correspondentes contribuições do período compreendido entre o desligamento e a reintegração foram pagas em juízo', 'indicar-se-as-remuneracoes-e-correspondentes-contr', 'true', 'true', 6, 1, '', 0, 'false', '', 'indPagtoJuizo');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (4000437, 3002143, '', '5b9fafa554ed0', 'false', 0, '', 'cpfTrab'),
                   (4000438, 3002144, '', '5b9fafa55f5de', 'false', 0, '', 'nisTrab'),
                   (4000439, 3002145, '', '5b9fafa56f744', 'false', 0, '', 'matricula'),
                   (4000440, 3002146, '1 - Reintegração por Decisão Judicial', '1-reintegracao-por-decisao-judicial', 'false', 0, '1', 'tpReint1'),
                   (4000441, 3002146, '2 - Reintegração por Anistia Legal', '2-reintegracao-por-anistia-legal', 'false', 0, '2', 'tpReint2'),
                   (4000442, 3002146, '3 - Reversão de Servidor Público', '3-reversao-de-servidor-publico', 'false', 0, '3', 'tpReint3'),
                   (4000443, 3002146, '4 - Recondução de Servidor Público', '4-reconducao-de-servidor-publico', 'false', 0, '4', 'tpReint4'),
                   (4000444, 3002146, '5 - Reinclusão de Militar', '5-reinclusao-de-militar', 'false', 0, '5', 'tpReint5'),
                   (4000445, 3002146, '9 - Outros', '9-outros', 'false', 0, '9', 'tpReint9'),
                   (4000446, 3002147, '', '5b9fafa58ed1f', 'false', 0, '', 'nrProcJud'),
                   (4000447, 3002148, '', '5b9fafa596485', 'false', 0, '', 'nrLeiAnistia'),
                   (4000448, 3002149, '', '5b9fafa59dbd9', 'false', 0, '', 'dtEfetRetorno'),
                   (4000449, 3002150, '', '5b9fafa5a564b', 'false', 0, '', 'dtEfeito'),
                   (4000450, 3002151, 'S - Sim', 's-sim', 'false', 0, 'S', 'indPagtoJuizoS'),
                   (4000451, 3002151, 'N - Não', 'n-nao', 'false', 0, 'N', 'indPagtoJuizoN');
                   
            INSERT INTO recursoshumanos.esocialformulariotipo (rh209_sequencial, rh209_descricao)
            VALUES (19, 'Reintegração');
            
            SELECT setval('esocialversaoformulario_rh211_sequencial_seq', (SELECT max(rh211_sequencial) FROM esocialversaoformulario));
            
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo)
            VALUES (nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000031, 19);
                   
            CREATE TABLE esocial.avaliacaogruporespostareintegracao
            (
              eso21_sequencial             serial,
              eso21_avaliacaogruporesposta int NOT NULL,
              eso21_cgm                    int NOT NULL,
              eso21_matricula              int NOT NULL,
              CONSTRAINT avaliacaogruporespostareintegracao_avaliacaogruporesposta_db107_sequencial_fk FOREIGN KEY (eso21_avaliacaogruporesposta) REFERENCES habitacao.avaliacaogruporesposta (db107_sequencial),
              CONSTRAINT avaliacaogruporespostareintegracao_cgm_z01_numcgm_fk FOREIGN KEY (eso21_cgm) REFERENCES protocolo.cgm (z01_numcgm),
              CONSTRAINT avaliacaogruporespostareintegracao_rhpessoal_rh01_regist_fk FOREIGN KEY (eso21_matricula) REFERENCES pessoal.rhpessoal (rh01_regist)
            );
              
            INSERT INTO db_sysarquivo
            VALUES (1010316, 'avaliacaogruporespostareintegracao', 'Preenchimento Reintegração', 'eso21', current_date, 'Preenchimento Reintegração', 0, FALSE, FALSE, FALSE, FALSE);
        
            INSERT INTO db_sysarqmod 
            VALUES (81, 1010316);
            
            INSERT INTO db_syscampo
            VALUES (1009945, 'eso21_sequencial', 'int4', 'Sequencial', '0', 'Sequencial', 11, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
                   (1009946, 'eso21_avaliacaogruporesposta', 'int4', 'Preenchimento', '0', 'Preenchimento', 11, 'f', 'f', 'f', 1, 'text', 'Preenchimento'),
                   (1009947, 'eso21_matricula', 'int4', 'Matrícula', '0', 'Matrícula', 11, 'f', 'f', 'f', 1, 'text', 'Matrícula'),
                   (1009948, 'eso21_cgm', 'int4', 'Empregador', '0', 'Empregador', 10, 'f', 'f', 'f', 1, 'text', 'Empregador');
                   
            INSERT INTO db_syssequencia
            VALUES (1000765, 'avaliacaogruporespostareintegracao_eso21_sequencial_seq', 1, 1, 9000000000000000000, 1, 1);
            
            INSERT INTO db_sysarqcamp 
            VALUES (1010316, 1009945, 1, 1000765),
                   (1010316, 1009946, 2, 0),
                   (1010316, 1009947, 3, 0),
                   (1010316, 1009948, 4, 0);
                   
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden) 
            VALUES (1010316, 1009945, 1, 1009945);
            
            INSERT INTO db_sysforkey 
            VALUES (1010316, 1009946, 1, 2987, 0),
                   (1010316, 1009947, 1, 1153, 0),
                   (1010316, 1009948, 1, 42, 0);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            
            DELETE 
            FROM db_sysforkey 
            WHERE codarq = 1010316;
            
            DELETE 
            FROM db_sysprikey 
            WHERE codarq = 1010316;
            
            DELETE 
            FROM db_sysprikey 
            WHERE codarq = 1010316;
            
            DELETE 
            FROM db_sysarqcamp 
            WHERE codarq = 1010316;
            
            DELETE 
            FROM db_syssequencia 
            WHERE codsequencia = 1000765;
            
            DELETE 
            FROM db_syscampo
            WHERE codcam IN (1009945, 1009946, 1009947, 1009948);
            
            DELETE
            FROM db_sysarqmod
            WHERE codarq = 1010316;
            
            DELETE
            FROM db_sysarquivo
            WHERE codarq = 1010316;
            
            DROP TABLE esocial.avaliacaogruporespostareintegracao;
            
            DELETE
            FROM esocialversaoformulario
            WHERE rh211_avaliacao = 3000031;
            
            DELETE
            FROM esocialformulariotipo
            WHERE rh209_sequencial = 19;
            
            DELETE
            FROM avaliacaogrupoperguntaresposta
            WHERE db108_avaliacaoresposta IN (SELECT db106_sequencial
                                              FROM avaliacaoresposta
                                              WHERE db106_avaliacaoperguntaopcao IN
                                                    (4000437, 4000438, 4000439, 4000440, 4000441, 4000442, 4000443, 4000444, 4000445, 4000446, 4000447, 4000448, 4000449, 4000450, 4000451));
            
            DELETE
            FROM avaliacaoresposta
            WHERE db106_avaliacaoperguntaopcao IN
                  (4000437, 4000438, 4000439, 4000440, 4000441, 4000442, 4000443, 4000444, 4000445, 4000446, 4000447, 4000448, 4000449, 4000450, 4000451);
            
            DELETE
            FROM avaliacaoperguntaopcao
            WHERE db104_sequencial IN
                  (4000437, 4000438, 4000439, 4000440, 4000441, 4000442, 4000443, 4000444, 4000445, 4000446, 4000447, 4000448, 4000449, 4000450, 4000451);
            
            DELETE
            FROM avaliacaopergunta
            WHERE db103_avaliacaogrupopergunta IN (3000477, 3000478);
            
            DELETE
            FROM avaliacaogrupopergunta
            WHERE db102_sequencial IN (3000477, 3000478);
            
            DELETE
            FROM avaliacao
            WHERE db101_sequencial = 3000031;
        ";

        $this->execute($sql);

        $this->removerMenu();
    }

    private function inserirMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            VALUES (10577, 'Reintegração', 'Reintegração', 'eso01_preenchimentoreintegracao.php', '1', '1', 'Reintegração', 'true');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
            VALUES (10220, 10577, 14, 10216);
        ";

        $this->execute($sql);
    }

    private function removerMenu()
    {
        $sql = "
            DELETE
            FROM db_menu 
            WHERE id_item_filho = 10577;
            
            DELETE
            FROM db_itensmenu
            WHERE id_item = 10577;
        ";

        $this->execute($sql);
    }
}
