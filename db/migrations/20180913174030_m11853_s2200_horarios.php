<?php

use Classes\PostgresMigration;

class M11853S2200Horarios extends PostgresMigration
{
    public function up()
    {
        $sql = "
            UPDATE avaliacaogrupopergunta
            SET db102_ordem = db102_ordem + 4
            WHERE db102_avaliacao = 3000013
              AND db102_ordem >= 41;

            UPDATE avaliacaogrupopergunta
            SET db102_descricao     = 'Horários da primeira semana',
                db102_identificador = 'horarios_1',
                db102_ordem         = 41
            WHERE db102_sequencial = 3000247;
            
            INSERT INTO avaliacaogrupopergunta (db102_sequencial,
                                                db102_avaliacao,
                                                db102_descricao,
                                                db102_identificador,
                                                db102_identificadorcampo,
                                                db102_ordem)
            VALUES (3000474, 3000013, 'Horários da segunda semana', 'horarios_2', 'horario', 42),
                   (3000475, 3000013, 'Horários da terceira semana', 'horarios_3', 'horario', 43),
                   (3000476, 3000013, 'Horários da quarta semana', 'horarios_4', 'horario', 44);
            
            INSERT INTO habitacao.avaliacaopergunta (db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_obrigatoria, db103_ativo, db103_ordem, db103_identificador, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            VALUES (3002126, 2, 3000474, 'Código da Jornada na Segunda-Feira', false, true, 1, 'codigo_da_jornada_na_segundafeira_2', 1, '', 0, false, '', 'horario_codHorContrat_9'),
                   (3002124, 2, 3000474, 'Código da Jornada na Terça-Feira', false, true, 2, 'codigo_da_jornada_na_tercafeira_2', 1, '', 0, false, '', 'horario_codHorContrat_10'),
                   (3002121, 2, 3000474, 'Código da Jornada na Quarta-Feira', false, true, 3, 'codigo_da_jornada_na_quartafeira_2', 1, '', 0, false, '', 'horario_codHorContrat_11'),
                   (3002119, 2, 3000474, 'Código da Jornada na Quinta-Feira', false, true, 4, 'codigo_da_jornada_na_quintafeira_2', 1, '', 0, false, '', 'horario_codHorContrat_12'),
                   (3002123, 2, 3000474, 'Código da Jornada na Sexta-Feira', false, true, 5, 'codigo_da_jornada_na_sextafeira_2', 1, '', 0, false, '', 'horario_codHorContrat_13'),
                   (3002120, 2, 3000474, 'Código da Jornada no Sábado', false, true, 6, 'codigo_da_jornada_no_sabado_2', 1, '', 0, false, '', 'horario_codHorContrat_14'),
                   (3002122, 2, 3000474, 'Código da Jornada no Domingo', false, true, 7, 'codigo_da_jornada_no_domingo_2', 1, '', 0, false, '', 'horario_codHorContrat_15'),
                   (3002125, 2, 3000474, 'Código da Jornada em Dia Variável', false, true, 8, 'codigo_da_jornada_em_dia_variavel_2', 1, '', 0, false, '', 'horario_codHorContrat_16'),
            
                   (3002134, 2, 3000475, 'Código da Jornada na Segunda-Feira', false, true, 1, 'codigo_da_jornada_na_segundafeira_3', 1, '', 0, false, '', 'horario_codHorContrat_17'),
                   (3002132, 2, 3000475, 'Código da Jornada na Terça-Feira', false, true, 2, 'codigo_da_jornada_na_tercafeira_3', 1, '', 0, false, '', 'horario_codHorContrat_18'),
                   (3002129, 2, 3000475, 'Código da Jornada na Quarta-Feira', false, true, 3, 'codigo_da_jornada_na_quartafeira_3', 1, '', 0, false, '', 'horario_codHorContrat_19'),
                   (3002127, 2, 3000475, 'Código da Jornada na Quinta-Feira', false, true, 4, 'codigo_da_jornada_na_quintafeira_3', 1, '', 0, false, '', 'horario_codHorContrat_20'),
                   (3002131, 2, 3000475, 'Código da Jornada na Sexta-Feira', false, true, 5, 'codigo_da_jornada_na_sextafeira_3', 1, '', 0, false, '', 'horario_codHorContrat_21'),
                   (3002128, 2, 3000475, 'Código da Jornada no Sábado', false, true, 6, 'codigo_da_jornada_no_sabado_3', 1, '', 0, false, '', 'horario_codHorContrat_22'),
                   (3002130, 2, 3000475, 'Código da Jornada no Domingo', false, true, 7, 'codigo_da_jornada_no_domingo_3', 1, '', 0, false, '', 'horario_codHorContrat_23'),
                   (3002133, 2, 3000475, 'Código da Jornada em Dia Variável', false, true, 8, 'codigo_da_jornada_em_dia_variavel_3', 1, '', 0, false, '', 'horario_codHorContrat_24'),
            
                   (3002142, 2, 3000476, 'Código da Jornada na Segunda-Feira', false, true, 1, 'codigo_da_jornada_na_segundafeira_4', 1, '', 0, false, '', 'horario_codHorContrat_25'),
                   (3002140, 2, 3000476, 'Código da Jornada na Terça-Feira', false, true, 2, 'codigo_da_jornada_na_tercafeira_4', 1, '', 0, false, '', 'horario_codHorContrat_26'),
                   (3002137, 2, 3000476, 'Código da Jornada na Quarta-Feira', false, true, 3, 'codigo_da_jornada_na_quartafeira_4', 1, '', 0, false, '', 'horario_codHorContrat_27'),
                   (3002135, 2, 3000476, 'Código da Jornada na Quinta-Feira', false, true, 4, 'codigo_da_jornada_na_quintafeira_4', 1, '', 0, false, '', 'horario_codHorContrat_28'),
                   (3002139, 2, 3000476, 'Código da Jornada na Sexta-Feira', false, true, 5, 'codigo_da_jornada_na_sextafeira_4', 1, '', 0, false, '', 'horario_codHorContrat_29'),
                   (3002136, 2, 3000476, 'Código da Jornada no Sábado', false, true, 6, 'codigo_da_jornada_no_sabado_4', 1, '', 0, false, '', 'horario_codHorContrat_30'),
                   (3002138, 2, 3000476, 'Código da Jornada no Domingo', false, true, 7, 'codigo_da_jornada_no_domingo_4', 1, '', 0, false, '', 'horario_codHorContrat_31'),
                   (3002141, 2, 3000476, 'Código da Jornada em Dia Variável', false, true, 8, 'codigo_da_jornada_em_dia_variavel_4', 1, '', 0, false, '', 'horario_codHorContrat_32');
            
            
            INSERT INTO habitacao.avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_aceitatexto, db104_identificador, db104_peso, db104_valorresposta, db104_identificadorcampo)
            VALUES (4000413, 3002126, '', true, '34b406cadbb32', 0, '', 'horario_codHorContrat_9'),
                   (4000414, 3002124, '', true, '4f157c9eca60a', 0, '', 'horario_codHorContrat_10'),
                   (4000415, 3002121, '', true, '57cba0664a6f8', 0, '', 'horario_codHorContrat_11'),
                   (4000416, 3002119, '', true, 'e935931ef0100', 0, '', 'horario_codHorContrat_12'),
                   (4000417, 3002123, '', true, '0169ed26ee3db', 0, '', 'horario_codHorContrat_13'),
                   (4000418, 3002120, '', true, '99d6ebe654ee9', 0, '', 'horario_codHorContrat_14'),
                   (4000419, 3002122, '', true, 'b1e1d0e72e55f', 0, '', 'horario_codHorContrat_15'),
                   (4000420, 3002125, '', true, '0cf82c319f43e', 0, '', 'horario_codHorContrat_16'),
            
                   (4000421, 3002134, '', true, '98f642c0c18c0', 0, '', 'horario_codHorContrat_17'),
                   (4000422, 3002132, '', true, '0fc3aabf40ac6', 0, '', 'horario_codHorContrat_18'),
                   (4000423, 3002129, '', true, '92d9bda94e6cd', 0, '', 'horario_codHorContrat_19'),
                   (4000424, 3002127, '', true, '40bd6d2da7e16', 0, '', 'horario_codHorContrat_20'),
                   (4000425, 3002131, '', true, 'c53e987dedd10', 0, '', 'horario_codHorContrat_21'),
                   (4000426, 3002128, '', true, 'f41915983e447', 0, '', 'horario_codHorContrat_22'),
                   (4000427, 3002130, '', true, 'b337ac38e24ac', 0, '', 'horario_codHorContrat_23'),
                   (4000428, 3002133, '', true, '98786a3c140ba', 0, '', 'horario_codHorContrat_24'),
            
                   (4000429, 3002142, '', true, 'baae358d84955', 0, '', 'horario_codHorContrat_25'),
                   (4000430, 3002140, '', true, 'd456523a40970', 0, '', 'horario_codHorContrat_26'),
                   (4000431, 3002137, '', true, 'b38dac6e8efbf', 0, '', 'horario_codHorContrat_27'),
                   (4000432, 3002135, '', true, 'd9d87bd7d6698', 0, '', 'horario_codHorContrat_28'),
                   (4000433, 3002139, '', true, 'e747e9a12b927', 0, '', 'horario_codHorContrat_29'),
                   (4000434, 3002136, '', true, 'c3947a7ab9bd6', 0, '', 'horario_codHorContrat_30'),
                   (4000435, 3002138, '', true, 'c5a0ead7adcf0', 0, '', 'horario_codHorContrat_31'),
                   (4000436, 3002141, '', true, '155b12a36d350', 0, '', 'horario_codHorContrat_32');
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE 
            FROM habitacao.avaliacaoperguntaopcao
            WHERE db104_sequencial IN (
                4000413,
                4000414,
                4000415,
                4000416,
                4000417,
                4000418,
                4000419,
                4000420,
                4000421,
                4000422,
                4000423,
                4000424,
                4000425,
                4000426,
                4000427,
                4000428,
                4000429,
                4000430,
                4000431,
                4000432,
                4000433,
                4000434,
                4000435,
                4000436
            );
            
            DELETE 
            FROM habitacao.avaliacaopergunta
            WHERE db103_avaliacaogrupopergunta IN (3000474, 3000475, 3000476);
            
            DELETE 
            FROM habitacao.avaliacaogrupopergunta
            WHERE db102_sequencial IN (3000474, 3000475, 3000476);
            
            UPDATE avaliacaogrupopergunta
            SET db102_descricao     = 'Horários',
                db102_identificador = 'horarios',
                db102_ordem         = 40
            WHERE db102_sequencial = 3000247;
            
            UPDATE avaliacaogrupopergunta
            SET db102_ordem = db102_ordem - 4
            WHERE db102_avaliacao = 3000013
              AND db102_ordem >= 41;     
        ";

        $this->execute($sql);
    }
}
