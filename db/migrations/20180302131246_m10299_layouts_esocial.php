<?php

use Classes\PostgresMigration;

class M10299LayoutsEsocial extends PostgresMigration
{
    function up()
    {
        $this->upS1005();
        $this->upS1070();
        $this->upS2200();
        $this->upS1050();
    }

    function down()
    {
        $this->downS1005();
        $this->downS1070();
        $this->downS2200();
        $this->downS1050();
    }

    private function upS1005()
    {
        $sql = "
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo) VALUES
                (3004072, 3000924, 'Processo FAP', 'processo-fap-5a9d7375723d0', FALSE, 0, '4', 'tpProc'),
                (3004074, 3000927, 'Processo FAP', 'processo-fap-5a9d737e94ddb', FALSE, 0, '4', 'tpProc');
        ";

        $this->execute($sql);
    }

    private function downS1005()
    {
        $sql = "
            DELETE FROM avaliacaogrupoperguntaresposta
            WHERE db108_avaliacaoresposta IN (SELECT db106_sequencial
                                              FROM avaliacaoresposta
                                              WHERE db106_avaliacaoperguntaopcao IN (3004072, 3004074));
            
            DELETE FROM avaliacaoresposta
            WHERE db106_avaliacaoperguntaopcao IN (3004072, 3004074);
            
            DELETE FROM avaliacaoperguntaopcao
            WHERE db104_sequencial IN (3004072, 3004074);
        ";

        $this->execute($sql);
    }

    private function upS1070()
    {
        $this->execute(<<<SQL
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_aceitatexto, db104_identificador, db104_peso, db104_valorresposta, db104_identificadorcampo) VALUES
                (3004070, 3000991, 'Número de Benefício (NB) do INSS', 'f', 'numero-de-beneficio-inss5a997a2c9f73b', 0, 3, 'tpProc'),
                (3004071, 3000991, 'Processo FAP', 'f', 'processo-fap5a997a2c9f73b', 0, 4, 'tpProc');
            
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = 'FGTS ou Contribuição Social Rescisória (Lei Complementar 110/2001)'
            WHERE db104_sequencial = 3003922;
            
            INSERT INTO avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3001061, 2, 3000233, 'Observação', 'observacao_1070', FALSE, TRUE, 3, 1, '', 0, FALSE, '', 'observacao');
            
            INSERT INTO avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (3004073, 3001061, '', 'observacao_1070_2', TRUE, 0, '', 'observacao');
SQL
        );
    }

    private function downS1070()
    {
        $this->execute(<<<SQL
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = 'FGTS'
            WHERE db104_sequencial = 3003922;
            
            DELETE FROM avaliacaogrupoperguntaresposta
            WHERE db108_avaliacaoresposta IN (SELECT db106_sequencial
                                              FROM avaliacaoresposta
                                              WHERE db106_avaliacaoperguntaopcao IN (3004070, 3004071, 3004073));
            
            DELETE FROM avaliacaoresposta
            WHERE db106_avaliacaoperguntaopcao IN (3004070, 3004071, 3004073);
            
            DELETE FROM avaliacaoperguntaopcao
            WHERE db104_sequencial IN (3004070, 3004071, 3004073);
            
            DELETE FROM avaliacaopergunta
            WHERE db103_sequencial = 3001061;
SQL
        );
    }

    private function upS2200()
    {
        $sql = "
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = 'Preta'
            WHERE db104_sequencial = 3003078;
            
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = 'Amarela'
            WHERE db104_sequencial = 3003080;
        ";

        $this->execute($sql);
    }

    private function downS2200()
    {
        $sql = "
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = 'Negra'
            WHERE db104_sequencial = 3003078;
            
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = 'Amarela (de origem japonesa, chinesa, coreana etc)'
            WHERE db104_sequencial = 3003080;
        ";

        $this->execute($sql);
    }

    private function upS1050()
    {
        $sql = <<<UPDATE
          update avaliacaopergunta set db103_tipo = 6 where db103_sequencial = 3000983;
          update avaliacaopergunta set db103_tipo = 6 where db103_sequencial = 3000984;
          update avaliacaopergunta set db103_tipo = 6 where db103_sequencial = 3000989;
          update avaliacaopergunta set db103_tipo = 6 where db103_sequencial = 3000990;
UPDATE;

        $this->execute($sql);
    }

    private function downS1050()
    {
        $sql = <<<UPDATE
          update avaliacaopergunta set db103_tipo = 9 where db103_sequencial = 3000983;
          update avaliacaopergunta set db103_tipo = 9 where db103_sequencial = 3000984;
          update avaliacaopergunta set db103_tipo = 9 where db103_sequencial = 3000989;
          update avaliacaopergunta set db103_tipo = 9 where db103_sequencial = 3000990;
UPDATE;

        $this->execute($sql);
    }


}
