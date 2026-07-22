<?php

use Classes\PostgresMigration;

class M10180S2260ConvocacaoParaTrabalhoIntermitente extends PostgresMigration
{
    public function up()
    {
        $this->ajustarAvaliacaoGrupoResposta();
        $this->inserirFormulario();
        $this->inserirMenu();
    }

    private function ajustarAvaliacaoGrupoResposta()
    {
        $sql = "
            ALTER TABLE habitacao.avaliacaogrupopergunta
              ALTER COLUMN db102_descricao TYPE varchar(150) USING db102_descricao :: varchar(150)
        ";

        $this->execute($sql);
    }

    private function inserirFormulario()
    {
        $sql = "
            INSERT INTO avaliacao (db101_sequencial, db101_avaliacaotipo, db101_descricao, db101_identificador, db101_obs, db101_ativo, db101_cargadados, db101_permiteedicao)
            VALUES (3000029, 5, 'S2260 - Convocação para Trabalho Intermitente', 's2260-convocacao-para-trabalho-intermitente', 'S2260 - Convocação para Trabalho Intermitente', 'true', '', 'true');
            
            INSERT INTO avaliacaogrupopergunta (db102_sequencial, db102_avaliacao, db102_descricao, db102_identificador, db102_identificadorcampo, db102_ordem)
            VALUES (3000365, 3000029, 'Informações de Identificação do Trabalhador e do Vínculo', 'informacoes-de-identificacao-do-traba5b8d258886f60', 'ideVinculo', 1),
                   (3000366, 3000029, 'Informações da convocação para trabalho intermitente', 'informacoes-da-convocacao-para-trabalho-intermiten', 'infoConvInterm', 2),
                   (3000367, 3000029, 'Informações da(s) jornada(s) diária(s) da prestação de trabalho intermitente', 'informacoes-das-jornadas-diarias-da-prestacao-de-t', 'jornada', 3),
                   (3000368, 3000029, 'Informações do local da prestação de trabalho intermitente', 'informacoes-do-local-da-prestacao-de-trabalho-inte', 'localTrab', 4),
                   (3000369, 3000029, 'Informações do local de trabalho intermitente, quando prestado em apenas um local e fora do estabelecimento do empregador cadastrado no S-2200/S-2206', 'informacoes-do-local-de-trabalho-intermitente-quan', 'localTrabInterm', 5);
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001610, 2, 3000365, 'Preencher com o número do CPF do trabalhador', 'preencher-com-o-numero-do-cpf-do-trabalhador', 'true', 'true', 1, 4, '', 0, 'false', '', 'cpfTrab'),
                   (3001611, 2, 3000365, 'Preencher com o Número de Identificação Social - NIS, o qual pode ser o PIS, PASEP ou NIT', 'preencher-com-o-numero-de-identificacao-social-nis', 'true', 'true', 2, 1, '', 0, 'false', '', 'nisTrab'),
                   (3001612, 2, 3000365, 'Matrícula atribuída ao trabalhador', 'matricula-atribuida-ao-trabalhador', 'true', 'true', 3, 6, '', 0, 'false', '', 'matricula'),
                   (3001613, 2, 3000366, 'Preencher com o código atribuído pela empresa que identifica a convocação para trabalho intermitente', 'preencher-com-o-codigo-atribuido-pela-empresa-que-', 'true', 'true', 1, 1, '', 0, 'false', '', 'codConv'),
                   (3001614, 2, 3000366, 'Preencher com a data de início da prestação de trabalho intermitente', 'preencher-com-a-data-de-inicio-da-prestacao-de-tra', 'true', 'true', 2, 5, '', 0, 'false', '', 'dtInicio'),
                   (3001615, 2, 3000366, 'Preencher com a data de término da prestação de trabalho intermitente', 'preencher-com-a-data-de-termino-da-prestacao-de-tr', 'true', 'true', 3, 5, '', 0, 'false', '', 'dtFim'),
                   (3001616, 2, 3000366, 'Preencher com a data prevista para o pagamento da remuneração. Caso a convocação compreenda mais de um período de apuração, preencher com a data prevista para o pagamento da remuneração do último mês', 'preencher-com-a-data-prevista-para-o-pagamento-da-', 'true', 'true', 4, 5, '', 0, 'false', '', 'dtPrevPgto'),
                   (3001617, 2, 3000367, 'Preencher com o código atribuído pela empresa para o horário da prestação de trabalho intermitente, caso a jornada diária seja fixa durante o período da convocação e não haja prestação de serviços em dias alternados', 'preencher-com-o-codigo-atribuido-pela-empresa-para', 'false', 'true', 1, 1, '', 0, 'false', '', 'codHorContrat'),
                   (3001618, 2, 3000367, 'Descrição das jornadas e dos dias da prestação de trabalho intermitente, caso a jornada diária seja variável durante o período da convocação ou haja prestação de serviços em dias alternados', 'descricao-das-jornadas-e-dos-dias-da-prestacao-de-', 'false', 'true', 2, 1, '', 0, 'false', '', 'dscJornada'),
                   (3001619, 1, 3000368, 'Indicativo do local da prestação de trabalho intermitente', 'indicativo-do-local-da-prestacao-de-trabalho-inter', 'true', 'true', 1, 1, '', 0, 'false', '', 'indLocal'),
                   (3001620, 2, 3000369, 'Tipo de Logradouro, conforme tabela 20', 'tipo-de-logradouro-conforme-tabela-20', 'false', 'true', 1, 1, '', 0, 'false', '', 'tpLograd'),
                   (3001621, 2, 3000369, 'Descrição do logradouro', 'descricao-do-logradouro5b8d258905aad', 'false', 'true', 2, 1, '', 0, 'false', '', 'dscLograd'),
                   (3001622, 2, 3000369, 'Número do logradouro', 'numero-do-logradouro5b8d25890eeb8', 'false', 'true', 3, 6, '', 0, 'false', '', 'nrLograd'),
                   (3001623, 2, 3000369, 'Complemento do logradouro', 'complemento-do-logradouro5b8d25891816d', 'false', 'true', 4, 1, '', 0, 'false', '', 'complem'),
                   (3001625, 2, 3000369, 'Nome do bairro/distrito', 'nome-do-bairrodistrito5b8d258931888', 'false', 'true', 6, 1, '', 0, 'false', '', 'bairro'),
                   (3001626, 2, 3000369, 'Código de Endereçamento Postal - CEP', 'codigo-de-enderecamento-postal-cep5b8d25893ac71', 'false', 'true', 7, 2, '', 0, 'false', '', 'cep'),
                   (3001627, 2, 3000369, 'Preencher com o código do município, conforme tabela do IBGE', 'preencher-com-o-codigo-do-municipio-conforme-tabel', 'false', 'true', 8, 1, '', 0, 'false', '', 'codMunic'),
                   (3001628, 2, 3000369, 'Preencher com a sigla da Unidade da Federação', 'preencher-com-a-sigla-da-unidade-da-federacao', 'false', 'true', 9, 1, '', 0, 'false', '', 'uf');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3005244, 3001610, '', '5b8d25888f192', 'false', 0, '', 'cpfTrab'),
                   (3005245, 3001611, '', '5b8d25889156b', 'false', 0, '', 'nisTrab'),
                   (3005246, 3001612, '', '5b8d258893ba5', 'false', 0, '', 'matricula'),
                   (3005247, 3001613, '', '5b8d25889df5e', 'false', 0, '', 'codConv'),
                   (3005248, 3001614, '', '5b8d2588a0716', 'false', 0, '', 'dtInicio'),
                   (3005249, 3001615, '', '5b8d2588aa9fb', 'false', 0, '', 'dtFim'),
                   (3005250, 3001616, '', '5b8d2588b3a8c', 'false', 0, '', 'dtPrevPgto'),
                   (3005251, 3001617, '', '5b8d2588bfe6f', 'false', 0, '', 'codHorContrat'),
                   (3005252, 3001618, '', '5b8d2588c8f82', 'false', 0, '', 'dscJornada'),
                   (3005253, 3001619, '0 - Prestação de serviços no estabelecimento informado no grupo \"Estabelecimento (CNPJ, CNO, CAEPF)\" do S-2200 ou S-2206, quando for o caso', '0-prestacao-de-servicos-no-estabelecimento-informa', 'false', 0, '0', 'indLocalZero'),
                   (3005254, 3001619, '1 - Prestação de serviços em apenas um local e fora do estabelecimento informado no grupo \"Estabelecimento (CNPJ, CNO, CAEPF)\" do S-2200 ou S-2206, quando for o caso', '1-prestacao-de-servicos-em-apenas-um-local-e-fora-', 'false', 0, '1', 'indLocalUm'),
                   (3005255, 3001619, '2 - Prestação de serviços de natureza externa ou em mais de um local', '2-prestacao-de-servicos-de-natureza-externa-ou-em-', 'false', 0, '2', 'indLocalDois'),
                   (3005256, 3001620, '', '5b8d2588f0732', 'false', 0, '', 'tpLograd'),
                   (3005257, 3001621, '', '5b8d25890b23f', 'false', 0, '', 'dscLograd'),
                   (3005258, 3001622, '', '5b8d2589148ad', 'false', 0, '', 'nrLograd'),
                   (3005259, 3001623, '', '5b8d25891d8ed', 'false', 0, '', 'complem'),
                   (3005261, 3001625, '', '5b8d2589370f7', 'false', 0, '', 'bairro'),
                   (3005262, 3001626, '', '5b8d2589405a2', 'false', 0, '', 'cep'),
                   (3005263, 3001627, '', '5b8d2589493fc', 'false', 0, '', 'codMunic'),
                   (3005264, 3001628, '', '5b8d258951f59', 'false', 0, '', 'uf');
                   
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000308 ,3000827 ,'Empregado - contrato de trabalho intermitente' ,'codCateConvInterm' ,'f' ,0 ,'111' ,'' );
                   
            INSERT INTO recursoshumanos.esocialformulariotipo (rh209_sequencial, rh209_descricao)
            VALUES (16, 'Convocação para Trabalho Intermitente');
            
            SELECT setval('esocialversaoformulario_rh211_sequencial_seq', (SELECT max(rh211_sequencial) FROM esocialversaoformulario));
            
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo)
            VALUES (nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000029, 16);
            
            CREATE TABLE esocial.avaliacaogruporespostatrabintermitente
            (
              eso19_sequencial             serial,
              eso19_avaliacaogruporesposta int         NOT NULL,
              eso19_matricula              int         NOT NULL,
              eso19_cgm                    int         NOT NULL,
              eso19_codigoconvocacao       varchar(30) NOT NULL,
              CONSTRAINT avaliacaogruporespostatrabintermitente_fk FOREIGN KEY (eso19_avaliacaogruporesposta) REFERENCES habitacao.avaliacaogruporesposta (db107_sequencial),
              CONSTRAINT avaliacaogruporespostatrabintermitente_rhpessoal_fk FOREIGN KEY (eso19_matricula) REFERENCES pessoal.rhpessoal (rh01_regist),
              CONSTRAINT avaliacaogruporespostatrabintermitente_cgm_fk FOREIGN KEY (eso19_cgm) REFERENCES protocolo.cgm (z01_numcgm)
            );
              
            CREATE UNIQUE INDEX avaliacaogruporespostatrabintermitente_eso19_codigoconvocacao_uindex
              ON esocial.avaliacaogruporespostatrabintermitente (eso19_codigoconvocacao);
              
            INSERT INTO db_sysarquivo
            VALUES (1010313, 'avaliacaogruporespostatrabintermitente', 'Preenchimento Con. Trabalho Intermitente', 'eso19', current_date, 'Preenchimento Con. Trabalho Intermitente', 0, FALSE, FALSE, FALSE, FALSE);
        
            INSERT INTO db_sysarqmod 
            VALUES (81, 1010313);
            
            INSERT INTO db_syscampo 
            VALUES (1009931, 'eso19_sequencial', 'int4', 'Sequencial', '0', 'Sequencial', 11, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
                   (1009932, 'eso19_avaliacaogruporesposta', 'int4', 'Preenchimento', '0', 'Preenchimento', 11, 'f', 'f', 'f', 1, 'text', 'Preenchimento'),
                   (1009933, 'eso19_matricula', 'int4', 'Matrícula', '0', 'Matrícula', 11, 'f', 'f', 'f', 1, 'text', 'Matrícula'),
                   (1009934, 'eso19_cgm', 'int4', 'Empregador', '0', 'Empregador', 10, 'f', 'f', 'f', 1, 'text', 'Empregador'),
                   (1009940, 'eso19_codigoconvocacao', 'varchar(30)', 'Código Convocação', '0', 'Código Convocação', 30, 'f', 'f', 'f', 1, 'text', 'Código Convocação');
                   
            INSERT INTO db_syssequencia
            VALUES (1000762, 'avaliacaogruporespostatrabintermitente_eso19_sequencial_seq', 1, 1, 9000000000000000000, 1, 1);
            
            INSERT INTO db_sysarqcamp 
            VALUES (1010313, 1009931, 1, 1000762),
                   (1010313, 1009932, 2, 0),
                   (1010313, 1009933, 3, 0),
                   (1010313, 1009934, 4, 0),
                   (1010313, 1009940, 5, 0);
                   
            INSERT INTO db_sysprikey (codarq, codcam, sequen, camiden) 
            VALUES (1010313, 1009931, 1, 1009931);
            
            INSERT INTO db_sysforkey 
            VALUES (1010313, 1009932, 1, 2987, 0),
                   (1010313, 1009933, 1, 1153, 0),
                   (1010313, 1009934, 1, 42, 0);
                   
            INSERT INTO db_sysindices 
            VALUES (1008322, 'avaliacaogruporespostatrabintermitente_eso19_codigoconvocacao_uindex', 1010313, '0');
            
            INSERT INTO db_syscadind 
            VALUES (1008320, 1009933, 1),
                   (1008322, 1009940, 1);
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $this->removerMenu();
        $this->removerFormulario();
        $this->desajustarAvaliacaoGrupoResposta();
    }

    private function removerFormulario()
    {
        $sql = "
            DELETE
            FROM db_syscadind
            WHERE codind IN (1008320, 1008322);
            
            DELETE
            FROM db_sysindices
            WHERE codind IN (1008322);
            
            DELETE
            FROM db_sysforkey
            WHERE codarq = 1010313;
            
            DELETE
            FROM db_sysprikey
            WHERE codarq = 1010313;
            
            DELETE
            FROM db_sysarqcamp
            WHERE codarq = 1010313;
            
            DELETE
            FROM db_syssequencia
            WHERE codsequencia = 1000762;
            
            DELETE
            FROM db_syscampo
            WHERE codcam IN (1009931, 1009932, 1009933, 1009934, 1009940);
            
            DELETE
            FROM db_sysarqmod
            WHERE codarq = 1010313;
            
            DELETE
            FROM db_sysarquivo
            WHERE codarq = 1010313;
            
            DROP INDEX avaliacaogruporespostatrabintermitente_eso19_codigoconvocacao_uindex;
            
            DROP TABLE esocial.avaliacaogruporespostatrabintermitente;
            
            DELETE
            FROM esocialversaoformulario
            WHERE rh211_avaliacao = 3000029;
            
            DELETE
            FROM esocialformulariotipo
            WHERE rh209_sequencial = 16;
            
            DELETE
            FROM avaliacaogrupoperguntaresposta
            WHERE db108_avaliacaoresposta IN (SELECT db106_sequencial
                                              FROM avaliacaoresposta
                                              WHERE db106_avaliacaoperguntaopcao IN
                                                    (3005244, 3005245, 3005246, 3005247, 3005248, 3005249, 3005250, 3005251, 3005252, 3005253, 3005254, 3005255, 3005256, 3005257, 3005258, 3005259, 3005260, 3005261, 3005262, 3005263, 3005264));
            
            DELETE
            FROM avaliacaoresposta
            WHERE db106_avaliacaoperguntaopcao IN
                  (3005244, 3005245, 3005246, 3005247, 3005248, 3005249, 3005250, 3005251, 3005252, 3005253, 3005254, 3005255, 3005256, 3005257, 3005258, 3005259, 3005260, 3005261, 3005262, 3005263, 3005264);
            
            DELETE
            FROM avaliacaoperguntaopcao
            WHERE db104_sequencial IN
                  (3005244, 3005245, 3005246, 3005247, 3005248, 3005249, 3005250, 3005251, 3005252, 3005253, 3005254, 3005255, 3005256, 3005257, 3005258, 3005259, 3005260, 3005261, 3005262, 3005263, 3005264, 4000308);
            
            DELETE
            FROM avaliacaopergunta
            WHERE db103_sequencial IN
                  (3001610, 3001611, 3001612, 3001613, 3001614, 3001615, 3001616, 3001617, 3001618, 3001619, 3001620, 3001621, 3001622, 3001623, 3001624, 3001625, 3001626, 3001627, 3001628);
            
            DELETE
            FROM avaliacaogrupopergunta
            WHERE db102_sequencial IN (3000365, 3000366, 3000367, 3000368, 3000369);
            
            DELETE
            FROM avaliacao
            WHERE db101_sequencial = 3000029;
        ";

        $this->execute($sql);
    }

    private function desajustarAvaliacaoGrupoResposta()
    {
        $sql = "
            ALTER TABLE habitacao.avaliacaogrupopergunta
              ALTER COLUMN db102_descricao TYPE varchar(100) USING db102_descricao :: varchar(100)
        ";

        $this->execute($sql);
    }

    private function inserirMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            VALUES (10573, 'Convocação para Trabalho Intermitente', 'Convocação para Trabalho Intermitente', 'eso01_preenchimentotrabalhointermitente.php', '1', '1', 'Convocação para Trabalho Intermitente', 'true');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
            VALUES (10220, 10573, 13, 10216);
        ";

        $this->execute($sql);
    }

    private function removerMenu()
    {
        $sql = "
            DELETE
            FROM db_menu 
            WHERE id_item_filho = 10573;
            
            DELETE
            FROM db_itensmenu
            WHERE id_item = 10573;
        ";

        $this->execute($sql);
    }
}
