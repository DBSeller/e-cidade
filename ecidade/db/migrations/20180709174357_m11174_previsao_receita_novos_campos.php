<?php

use Classes\PostgresMigration;

class M11174PrevisaoReceitaNovosCampos extends PostgresMigration
{
    public function removerCamposAntigos()
    {
        $sql = "
            DELETE
            FROM avaliacaoperguntaopcao
            WHERE db104_avaliacaopergunta IN (
              SELECT db103_sequencial
              FROM avaliacaopergunta
              WHERE db103_avaliacaogrupopergunta IN (
                SELECT db102_sequencial
                FROM avaliacaogrupopergunta
                WHERE db102_avaliacao = 3000024
              ) AND avaliacaopergunta.db103_identificadorcampo IN ('unidadeGestora', 'numeroTipoDetalhamento', 'descricaoTipoDetalhamento')
            );

            DELETE
            FROM avaliacaopergunta
            WHERE db103_avaliacaogrupopergunta IN (
              SELECT db102_sequencial
              FROM avaliacaogrupopergunta
              WHERE db102_avaliacao = 3000024
            ) AND avaliacaopergunta.db103_identificadorcampo IN ('unidadeGestora', 'numeroTipoDetalhamento', 'descricaoTipoDetalhamento');
        ";
        $this->execute($sql);
    }

    public function adicionarCamposAntigos()
    {
        $sql = "
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001097, 2, 3000259, 'Unidade Gestora', 'unidade-gestora5b3e07434a0d9', 'true', 'true', 3, 1, '', 0, 'false', '', 'unidadeGestora');
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004119, 3001097, '', '5b3e07434b1d4', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001103, 2, 3000260, 'Número do Tipo de Detalhamento', 'numero-do-tipo-de-detalhamento5b3e0743557ed', 'true', 'true', 5, 1, '', 0, 'false', '', 'numeroTipoDetalhamento');
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004126, 3001103, '', '5b3e074358653', 'true', 0, '', '');
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001104, 2, 3000260, 'Descrição do Tipo de Detalhamento', 'descricao-do-tipo-de-detalhamento5b3e07435915b', 'true', 'true', 6, 1, '', 0, 'false', '', 'descricaoTipoDetalhamento');
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004127, 3001104, '', '5b3e07435b452', 'true', 0, '', '');
        ";
        $this->execute($sql);
    }

    public function removerCamposNovos()
    {
        $sql = "
            DELETE
            FROM avaliacaoperguntaopcao
            WHERE db104_avaliacaopergunta IN (
              SELECT db103_sequencial
              FROM avaliacaopergunta
              WHERE db103_avaliacaogrupopergunta IN (
                SELECT db102_sequencial
                FROM avaliacaogrupopergunta
                WHERE db102_avaliacao = 3000024
              ) AND avaliacaopergunta.db103_identificadorcampo IN ('previsaoTipoDetalhamento', 'previsaoReal2017', 'previsaoProvavel2018', 'previsaoPrevisao2019')
            );

            DELETE
            FROM avaliacaopergunta
            WHERE db103_avaliacaogrupopergunta IN (
              SELECT db102_sequencial
              FROM avaliacaogrupopergunta
              WHERE db102_avaliacao = 3000024
            ) AND avaliacaopergunta.db103_identificadorcampo IN ('previsaoTipoDetalhamento', 'previsaoReal2017', 'previsaoProvavel2018', 'previsaoPrevisao2019');
        
            DELETE FROM avaliacaopergunta WHERE db103_avaliacaogrupopergunta = 3000261;
            DELETE FROM avaliacaogrupopergunta WHERE db102_sequencial = 3000261 ;
        ";
        $this->execute($sql);
    }

    public function adicionarCamposNovos()
    {
        $sql = "
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) VALUES ( 3001106 ,1 ,3000260 ,'Tipo de detalhamento' ,'previsaoTipoDetalhamento' ,'t' ,'t' ,1 ,1 ,'' ,0 ,'false' ,'' ,'previsaoTipoDetalhamento');
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 3004129 ,3001106 ,'0 - Sem Detalhamento' ,'opcaotipoDeDetalhamento0b3e074340658' ,'f' ,0 ,'0' ,'' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 3004130 ,3001106 ,'1 - Cadastro' ,'opcaotipoDeDetalhamento15b3e074344005' ,'f' ,0 ,'1' ,'' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 3004131 ,3001106 ,'2 - Operação de Crédito' ,'opcaotipoDeDetalhamento2b3e0743461c1' ,'f' ,0 ,'2' ,'' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 3004132 ,3001106 ,'3 - Convênio' ,'opcaotipoDeDetalhamento3b3e0743461c1' ,'f' ,0 ,'3' ,'' );

            INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) VALUES ( 3000261 ,3000024 ,'Valores' ,'valoresa984r3dfa984xablau' ,'previsaoValores' ,1 );
            
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) VALUES ( 3001108 ,2 ,3000261 ,'Real 2017' ,'previsaoReal2017' ,'f' ,'t' ,1 ,8 ,'' ,0 ,'false' ,'' ,'previsaoReal2017');
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 3004134 ,3001108 ,'' ,'previsaoReal2017_2' ,'true' ,0 ,'' ,'null' );

            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) VALUES ( 3001109 ,2 ,3000261 ,'Provável 2018' ,'previsaoProvavel2018' ,'f' ,'t' ,1 ,8 ,'' ,0 ,'false' ,'' ,'previsaoProvavel2018');
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 3004135 ,3001109 ,'' ,'previsaoProvavel2018_2' ,'true' ,0 ,'' ,'null' );

            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) VALUES ( 3001110 ,2 ,3000261 ,'Previsão 2019' ,'previsaoPrevisao2019' ,'f' ,'t' ,1 ,8 ,'' ,0 ,'false' ,'' ,'previsaoPrevisao2019');
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 3004136 ,3001110 ,'' ,'previsaoPrevisao2019_2' ,'true' ,0 ,'' ,'null' );
        ";
        $this->execute($sql);
    }

    public function up()
    {
        $this->removerCamposAntigos();
        $this->adicionarCamposNovos();
    }

    public function down()
    {
        $this->adicionarCamposAntigos();
        $this->removerCamposNovos();
    }
}
