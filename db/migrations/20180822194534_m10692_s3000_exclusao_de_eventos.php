<?php

use Classes\PostgresMigration;

class M10692S3000ExclusaoDeEventos extends PostgresMigration
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
            VALUES (3000025, 5, 'S3000 - Exclusão de Eventos', 's3000-exclusao-de-eventos5b7dc393a9964', 'S3000 - Exclusão de Eventos', 'true', '', 'true');
            
            INSERT INTO avaliacaogrupopergunta (db102_sequencial, db102_avaliacao, db102_descricao, db102_identificador, db102_identificadorcampo, db102_ordem)
            VALUES (3000262, 3000025, 'Registro que identifica o evento objeto da exclusão.', 'registro-que-identifica-o-evento-obje5b7dc393b1651', 'infoExclusao', 1),
                   (3000263, 3000025, 'Registro que identifica a qual trabalhador refere-se o evento a ser excluído.', 'registro-que-identifica-a-qual-trabal5b7dc393c1d6c', 'ideTrabalhador', 1),
                   (3000264, 3000025, 'Registro que identifica a qual folha de pagamento pertence o evento que será excluído', 'registro-que-identifica-a-qual-folha-5b7dc393cc044', 'ideFolhaPagto', 1);
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001114, 2, 3000262, 'Tipo de evento, conforme tabela 9', 'tipo-de-evento-conforme-tabela-95b7dc393b4f04', 'true', 'true', 1, 1, '', 0, 'false', '', 'tpEvento'),
                   (3001115, 2, 3000262, 'Número do recibo do evento', 'numero-do-recibo-do-evento5b7dc393bdf49', 'true', 'true', 2, 1, '', 0, 'false', '', 'nrRecEvt'),
                   (3001116, 2, 3000263, 'Número do CPF do trabalhador', 'numero-do-cpf-do-trabalhador5b7dc393c40b3', 'false', 'true', 1, 4, '', 0, 'false', '', 'cpfTrab'),
                   (3001117, 2, 3000263, 'Número de Identificação Social', 'numero-de-identificacao-social5b7dc393c7ecc', 'false', 'true', 2, 6, '', 0, 'false', '', 'nisTrab'),
                   (3001119, 2, 3000264, 'Mês e ano de referência das informações, no formato AAAA-MM', 'mes-e-ano-de-referencia-das-informaco5b7dc393ddafe', 'false', 'true', 2, 1, '', 0, 'false', '', 'perApur'),
                   (3001118, 1, 3000264, 'Indicativo de período de apuração', 'indicativo-de-periodo-de-apuracao5b7dc393cd6fc', 'false', 'true', 1, 1, '', 0, 'false', '', 'indApuracao');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004172, 3001114, '', '5b7dc393bb538', 'false', 0, '', 'tpEvento'),
                   (3004173, 3001115, '', '5b7dc393c05ca', 'false', 0, '', 'nrRecEvt'),
                   (3004174, 3001116, '', '5b7dc393c64cd', 'false', 0, '', 'cpfTrab'),
                   (3004175, 3001117, '', '5b7dc393ca5f7', 'false', 0, '', 'nisTrab'),
                   (3004176, 3001118, '1 - Mensal', '1-mensal5b7dc393d7e61', 'false', 0, '1', 'indApuracaoMensal'),
                   (3004177, 3001118, '2 - Anual (13° salário)', '2-anual-13-salario5b7dc393dafc3', 'false', 0, '2', 'indApuracaoAnual'),
                   (3004178, 3001119, '', '5b7dc393e1e8a', 'false', 0, '', 'perApur');
                   
            INSERT INTO esocialformulariotipo
            VALUES (13, 'Exclusão de Eventos');      
                   
            INSERT INTO esocialversaoformulario
            VALUES (20, '2.4', 3000025, 13);
            
            CREATE TABLE esocial.avaliacaogruporespostaexclusaoeventos
            (
              eso14_sequencial             serial,
              eso14_avaliacaogruporesposta int NOT NULL,
              eso14_protocolo              varchar NOT NULL,
              eso14_cgm                    int NOT NULL,
              CONSTRAINT avaliacaogruporespostaexclusaoeventos_avaliacaogruporesposta_db107_sequencial_fk FOREIGN KEY (eso14_avaliacaogruporesposta) REFERENCES habitacao.avaliacaogruporesposta (db107_sequencial),
              CONSTRAINT avaliacaogruporespostaexclusaoeventos_cgm_eso14_cgm_fk FOREIGN KEY (eso14_cgm) REFERENCES protocolo.cgm (z01_numcgm)
            );

            CREATE INDEX avaliacaogruporespostaexclusaoeventos_cgm_in ON esocial.avaliacaogruporespostaexclusaoeventos(eso14_cgm);
            CREATE INDEX avaliacaogruporespostaexclusaoeventos_avaliacaogruporesposta_in ON esocial.avaliacaogruporespostaexclusaoeventos(eso14_avaliacaogruporesposta);
   
            INSERT INTO db_sysarquivo
            VALUES (1010305, 'avaliacaogruporespostaexclusaoeventos', 'Vínculo da Exclusão de Eventos com o Grupo e CGM.', 'eso14', current_date, 'Vínculo da Exclusão de Eventos com o Grupo e CGM.', 0, FALSE, FALSE, FALSE, FALSE);
        
            INSERT INTO db_sysarqmod 
            VALUES (81, 1010305);
            
            INSERT INTO db_syscampo 
            VALUES (1009895, 'eso14_sequencial', 'int4', 'Sequencial da Tabela', '0', 'Sequencial', 11, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
                   (1009896, 'eso14_avaliacaogruporesposta', 'int4', 'Código da Avaliação Grupo Resposta', '0', 'Avaliação Grupo Resposta', 11, 'f', 'f', 'f', 1, 'text', 'Avaliação Grupo Resposta'),
                   (1009897, 'eso14_protocolo', 'varchar', 'Protocolo', '0', 'Protocolo', 11, 'f', 'f', 'f', 1, 'text', 'Protocolo'),
                   (1009898, 'eso14_cgm', 'int4', 'Vínculo com o empregador.', '0', 'Empregador', 10, 'f', 'f', 'f', 1, 'text', 'Empregador');
          
            INSERT INTO db_syssequencia
            VALUES (1000755, 'avaliacaogruporespostaexclusaoeventos_eso14_sequencial_seq', 1, 1, 9000000000000000000, 1, 1);
            
            INSERT INTO db_sysarqcamp 
            VALUES (1010305, 1009895, 1, 1000755),
                   (1010305, 1009896, 2, 0),
                   (1010305, 1009897, 3, 0),
                   (1010305, 1009898, 4, 0);
                   
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden) 
            VALUES (1010305, 1009895, 1, 1009895);
                   
            INSERT INTO db_sysforkey 
            VALUES (1010305, 1009896, 1, 2987, 0),
                   (1010305, 1009898, 1, 42, 0);

            INSERT INTO db_sysindices 
            VALUES (1008312, 'avaliacaogruporespostaexclusaoeventos_cgm_in', 1010305, '0'),
                   (1008313, 'avaliacaogruporespostaexclusaoeventos_avaliacaogruporesposta_in', 1010305, '0');
            
            INSERT INTO db_syscadind 
            VALUES (1008312, 1009898, 1),
                   (1008313, 1009896, 1);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $this->deletarFormulario();
        $this->deletarMenu();
    }

    private function deletarFormulario()
    {
        $sql = "
            DELETE
            FROM db_sysindices
            WHERE codarq = 1010305;

            DELETE
            FROM db_syscadind
            WHERE codcam IN (1009898, 1009896);

            DELETE
            FROM db_sysforkey
            WHERE codarq = 1010305;
            
            DELETE
            FROM db_sysprikey
            WHERE codarq = 1010305;
            
            DELETE
            FROM db_sysarqcamp
            WHERE codarq = 1010305;
            
            DELETE
            FROM db_syssequencia
            WHERE codsequencia = 1000755;
            
            DELETE
            FROM db_syscampo
            WHERE codcam IN (1009895, 1009896, 1009897, 1009898);
            
            DELETE
            FROM db_sysarqmod
            WHERE codarq = 1010305;
            
            DELETE
            FROM db_sysarquivo
            WHERE codarq = 1010305;
            
            DROP TABLE esocial.avaliacaogruporespostaexclusaoeventos;

            DELETE
            FROM esocialversaoformulario
            WHERE rh211_sequencial = 20;
            
            DELETE
            FROM esocialformulariotipo
            WHERE rh209_sequencial = 13;

            DELETE
            FROM avaliacaogrupoperguntaresposta
            WHERE db108_avaliacaoresposta IN (SELECT db106_sequencial
                                              FROM avaliacaoresposta
                                              WHERE db106_avaliacaoperguntaopcao IN
                                                    (3004172, 3004173, 3004174, 3004175, 3004176, 3004177, 3004178));
            
            DELETE
            FROM avaliacaoresposta
            WHERE db106_avaliacaoperguntaopcao IN (3004172, 3004173, 3004174, 3004175, 3004176, 3004177, 3004178);
            
            DELETE
            FROM avaliacaoperguntaopcao
            WHERE db104_sequencial IN (3004172, 3004173, 3004174, 3004175, 3004176, 3004177, 3004178);
            
            DELETE
            FROM avaliacaopergunta
            WHERE db103_sequencial IN (3001114, 3001115, 3001116, 3001117, 3001119, 3001118);
            
            DELETE
            FROM avaliacaogrupopergunta
            WHERE db102_sequencial IN (3000262, 3000263, 3000264);
            
            DELETE
            FROM avaliacao
            WHERE db101_sequencial = 3000025;
        ";

        $this->execute($sql);
    }

    private function inserirMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            VALUES (10567, 'Exclusão de Eventos', 'Exclusão de Eventos', 'eso01_preenchimentoexclusaoeventos.php', '1', '1', 'Exclusão de Eventos', 'true');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
            VALUES (10466, 10567, 12, 10216);
        ";

        $this->execute($sql);
    }

    private function deletarMenu()
    {
        $sql = "
            DELETE
            FROM db_menu 
            WHERE id_item_filho = 10567;
            
            DELETE
            FROM db_itensmenu
            WHERE id_item = 10567;
        ";

        $this->execute($sql);
    }
}
