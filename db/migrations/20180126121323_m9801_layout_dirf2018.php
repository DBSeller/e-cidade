<?php

use Classes\PostgresMigration;

class M9801LayoutDirf2018 extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO db_layouttxt (db50_codigo, db50_layouttxtgrupo, db50_descr, db50_quantlinhas, db50_obs)
            VALUES (297, 1, 'LAYOUT DIRF 2018', 0, '');
            
            INSERT INTO db_layoutlinha VALUES
              (976, 297, 'RRA', 3, 192, 0, 0, 'Registros de rendimentos recebidos acumuladamente.', '|', TRUE),
              (977, 297, 'BPFRRA', 3, 135, 0, 0, 'Regras de validação do registro:
                         - Deve estar classificado em ordem crescente por:
                            - CPF;
                            - Natureza do RRA;
                         - Deve estar associado ao registro do tipo IDREC.', '|', TRUE),
              (978, 297, 'QTMESES', 3, 55, 0, 0, 'Regras de validação do registro:
                        - Deve ocorrer apenas um registro de cada identificador para o mesmo beneficiário;
                        - Deve estar associado ao registro do tipo BPFRRA.', '|', TRUE),
              (979, 297, 'VALORES MENSAIS - RTRT', 3, 200, 0, 0, '', '|', TRUE),
              (980, 297, 'RESPO', 3, 86, 0, 0, '', '|', TRUE),
              (981, 297, 'HEADER', 1, 32, 0, 0, '', '|', TRUE),
              (982, 297, 'DECPJ', 3, 195, 0, 0, '', '|', TRUE),
              (983, 297, 'IDREC', 3, 9, 0, 0, '', '|', TRUE),
              (984, 297, 'BPFDEC', 3, 85, 0, 0, '', '|', TRUE),
              (985, 297, 'BPJDEC', 3, 170, 0, 0, '', '|', TRUE),
              (986, 297, 'IDENTIFICADOR_PSE', 3, 3, 0, 0, '', '|', TRUE),
              (987, 297, 'INF', 3, 214, 0, 0, '', '|', TRUE),
              (988, 297, 'FIMDIRF', 4, 7, 0, 0, '', '|', TRUE),
              (989, 297, 'RIO', 3, 78, 0, 0, '', '|', TRUE),
              (990, 297, 'PLANO_SAUDE', 3, 91, 0, 0, '', '|', TRUE),
              (991, 297, 'operadora_PSE', 3, 176, 0, 0, 'OPERADOR DO PLANO DE SAUDE', '|', TRUE),
              (992, 297, 'INFPC', 3, 200, 0, 0, '', '|', TRUE),
              (993, 297, 'INFPA', 3, 86, 0, 0, '', '|', TRUE);
            
            INSERT INTO db_layoutcampos VALUES
              (16543, 976, 'identificador', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'RRA', 3, TRUE, TRUE, 'd', '', 0),
              (16544, 976, 'identificador_rendimento_recebido', 'IDENTIFICADOR DE RENDIMENTO RECEBIDO', 1, 3, '', 1, FALSE, TRUE, 'd', '', 0),
              (16545, 976, 'numero_processo', 'NÚMERO DO PROCESSO/REQUERIMENTO', 1, 4, '', 20, FALSE, TRUE, 'd', '', 0),
              (16546, 976, 'nome_advogado', 'NOME DO ADVOGADO', 1, 39, '', 150, FALSE, TRUE, 'd', 'Nome do advogado/Nome empresarial do escritório de advocacia', 0),
              (16547, 976, 'tipo_advogado', 'INDICADOR DE TIPO DE ADVOGADO/ESCRITÓRI', 1, 24, '', 1, FALSE, TRUE, 'e', 'Indicador de tipo de advogado/escritório de advocacia', 0),
              (16548, 976, 'documento_advogado', 'CPF DO ADVOGADO/CNPJ DO ESCRITÓRIO DE A', 1, 25, '', 14, FALSE, TRUE, 'e', 'CPF do advogado/CNPJ do escritório de advocacia', 0),
              (16549, 976, 'pipe', 'PIPE', 1, 189, '', 0, FALSE, TRUE, 'd', 'PIPE DE ENCERRAMENTO DE LINHA', 0),
              (16550, 977, 'identificador', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'BPFRRA', 6, TRUE, TRUE, 'd', 'Identificador de registro', 0),
              (16551, 977, 'cpf', 'CPF', 1, 7, '', 11, FALSE, TRUE, 'd', '', 0),
              (16552, 977, 'nome', 'NOME', 1, 18, '', 60, FALSE, TRUE, 'd', '', 0),
              (16553, 977, 'natureza', 'NATUREZA DO RRA', 1, 78, '', 50, FALSE, TRUE, 'd', '', 0),
              (16554, 977, 'data_molestia', 'DATA MOLESTIA GRAVE', 4, 128, '', 8, FALSE, TRUE, 'e', '', 0),
              (16555, 977, 'pipe', 'PIPE', 1, 136, '', 0, FALSE, TRUE, 'd', '', 0),
              (16556, 978, 'janeiro', 'JANEIRO', 3, 8, '', 4, FALSE, TRUE, 'e', 'Janeiro', 0),
              (16557, 978, 'fevereiro', 'FEVEREIRO', 3, 12, '', 4, FALSE, TRUE, 'e', 'Fevereiro', 0),
              (16558, 978, 'identificador', 'IDENTIFICADOR', 1, 1, 'QTMESES', 7, TRUE, TRUE, 'd', 'QTMESES', 0),
              (16559, 978, 'março', 'MARÇO', 3, 16, '', 4, FALSE, TRUE, 'e', 'Março', 0),
              (16560, 978, 'abril', 'ABRIL', 3, 20, '', 4, FALSE, TRUE, 'e', 'Abril', 0),
              (16561, 978, 'maio', 'MAIO', 3, 24, '', 4, FALSE, TRUE, 'e', 'Maio', 0),
              (16562, 978, 'junho', 'JUNHO', 3, 28, '', 4, FALSE, TRUE, 'e', 'Junho', 0),
              (16563, 978, 'agosto', 'AGOSTO', 3, 36, '', 4, FALSE, TRUE, 'e', 'Agosto', 0),
              (16564, 978, 'outubro', 'OUTUBRO', 3, 44, '', 4, FALSE, TRUE, 'e', 'Outubro', 0),
              (16565, 978, 'dezembro', 'DEZEMBRO', 3, 52, '', 4, FALSE, TRUE, 'e', 'Dezembro', 0),
              (16566, 978, 'julho', 'JULHO', 3, 32, '', 4, FALSE, TRUE, 'e', 'Julho', 0),
              (16567, 978, 'setembro', 'SETEMBRO', 3, 40, '', 4, FALSE, TRUE, 'e', 'Setembro', 0),
              (16568, 978, 'novembro', 'NOVEMBRO', 3, 48, '', 4, FALSE, TRUE, 'e', 'Novembro', 0),
              (16569, 978, 'pipe', 'PIPE', 1, 56, '', 0, FALSE, TRUE, 'd', '', 0),
              (16470, 979, 'julho', 'JULHO', 1, 96, '', 15, FALSE, TRUE, 'e', '', 0),
              (16471, 979, 'junho', 'JUNHO', 1, 81, '', 15, FALSE, TRUE, 'e', '', 0),
              (16482, 979, 'idetificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'RTRT', 5, TRUE, TRUE, 'd', '', 0),
              (16485, 979, 'janeiro', 'JANEIRO', 1, 6, '', 15, FALSE, TRUE, 'e', '', 0),
              (16504, 979, 'fevereiro', 'FEVEREIRO', 1, 21, '', 15, FALSE, TRUE, 'e', '', 0),
              (16505, 979, 'marco', 'MARÇO', 1, 36, '', 15, FALSE, TRUE, 'e', '', 0),
              (16506, 979, 'abril', 'ABRIL', 1, 51, '', 15, FALSE, TRUE, 'e', '', 0),
              (16507, 979, 'maio', 'MAIO', 1, 66, '', 15, FALSE, TRUE, 'e', '', 0),
              (16527, 979, 'Pipe', 'PIPE', 13, 201, '', 0, FALSE, TRUE, 'd', '', 0),
              (16528, 979, 'agosto', 'AGOSTO', 1, 111, '', 15, FALSE, TRUE, 'e', '', 0),
              (16529, 979, 'setembro', 'SETEMBRO', 1, 126, '', 15, FALSE, TRUE, 'e', '', 0),
              (16530, 979, 'outubro', 'OUTUBRO', 1, 141, '', 15, FALSE, TRUE, 'd', '', 0),
              (16531, 979, 'novembro', 'NOVEMBRO', 1, 156, '', 15, FALSE, TRUE, 'd', '', 0),
              (16532, 979, 'dezembro', 'DEZEMBRO', 1, 171, '', 15, FALSE, TRUE, 'd', '', 0),
              (16533, 979, 'decimo_terceiro', 'DÉCIMO TERCEIRO', 1, 186, '', 15, FALSE, TRUE, 'e', '', 0),
              (16469, 980, 'telefone', 'TELEFONE', 1, 79, '', 9, FALSE, TRUE, 'd', '', 0),
              (16517, 980, 'cpf', 'CPF', 1, 6, '', 11, FALSE, TRUE, 'd', '', 0),
              (16518, 980, 'nome', 'NOME', 1, 17, '', 60, FALSE, TRUE, 'd', '', 0),
              (16519, 980, 'ddd', 'DDD', 1, 77, '', 2, FALSE, TRUE, 'd', '', 0),
              (16538, 980, 'ramal', 'RAMAL', 1, 88, '', 6, FALSE, TRUE, 'd', '', 0),
              (16539, 980, 'fax', 'FAX', 1, 94, '', 9, FALSE, TRUE, 'd', '', 0),
              (16540, 980, 'correio_eletronico', 'CORREIO ELETRÔNICO', 1, 103, '', 50, FALSE, TRUE, 'd', '', 0),
              (16541, 980, 'Pipe', 'PIPE', 13, 153, '', 0, FALSE, TRUE, 'd', '', 0),
              (16570, 980, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'RESPO', 5, TRUE, TRUE, 'd', '', 0),
              (16481, 981, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'Dirf', 4, TRUE, TRUE, 'd', '', 0),
              (16512, 981, 'ano_referencia', 'ANO REFERÊNCIA', 1, 5, '', 4, FALSE, TRUE, 'd', '', 0),
              (16513, 981, 'ano_calendario', 'ANO CALENDÁRIO', 1, 9, '', 4, FALSE, TRUE, 'e', '', 0),
              (16514, 981, 'idetificador_retificadora', 'IDENTIFICADOR DE RETIFICADORA', 1, 13, '', 1, FALSE, TRUE, 'd', '', 0),
              (16515, 981, 'numero_recibo', 'NÚMERO DO RECIBO', 1, 14, '', 12, FALSE, TRUE, 'd', '', 0),
              (16535, 981, 'identificador_estrutura_layout', 'IDENTIFICADOR DE ESTRUTURA DE LAYOUT', 1, 26, '7C2DE7J', 7, FALSE, TRUE, 'd', '', 0),
              (16536, 981, 'Pipe', 'PIPE', 13, 33, '', 0, FALSE, TRUE, 'd', '', 0),
              (16472, 982, 'cnpj', 'CNPJ', 1, 6, '', 14, FALSE, TRUE, 'd', '', 0),
              (16473, 982, 'nome_empresarial', 'NOME EMPRESARIAL', 1, 20, '', 150, FALSE, TRUE, 'd', '', 0),
              (16474, 982, 'natureza_declarante', 'NATUREZA DO DECLARANTE', 2, 170, '2', 1, FALSE, TRUE, 'e', '', 0),
              (16475, 982, 'responsavel_perante_cnpj', 'CPF RESPONSÁVEL PERANTE O CNPJ', 2, 171, '', 11, FALSE, TRUE, 'e', '', 0),
              (16490, 982, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'DECPJ', 5, TRUE, TRUE, 'd', '', 0),
              (16500, 982, 'administradora_fund_invest', 'O DECLARANTE É INSTITUIÇÃO ADMINISTRADOR', 1, 184, 'N', 1, FALSE, TRUE, 'd', '', 0),
              (16508, 982, 'socio_ostensivo', 'O DECLARANTE É SÓCIO OSTENSIVO RESPONSÁV', 1, 182, 'N', 1, FALSE, TRUE, 'd', '', 0),
              (16509, 982, 'depositario_credito_dec_judicial', 'O DECLARANTE É DEPOSITÁRIO DE CRÉDITO', 1, 183, 'N', 1, FALSE, TRUE, 'd', '', 0),
              (16534, 982, 'rendimentos_residentes_exterior', 'O DECLARANTE PAGOU RENDIMENTOS A RESIDEN', 1, 185, 'N', 1, FALSE, TRUE, 'd', '', 0),
              (16537, 982, 'plano_privado_assistencia', 'INDICADOR DE PLANO PRIVADO DE ASSISTÊNCI', 1, 186, 'N', 1, FALSE, TRUE, 'd', '', 0),
              (16542, 982, 'indicador_pagto_copa', 'INDICADOR DE PAGAMENTO PARA A COPA', 1, 187, 'N', 1, FALSE, TRUE, 'd', '', 0),
              (16572, 982, 'situacao_especial', 'A DECLARAÇÃO É SITUAÇÃO ESPECIAL', 1, 189, 'N', 1, FALSE, TRUE, 'd', '', 0),
              (16573, 982, 'data_evento', 'DATA DE EVENTO', 1, 190, '', 8, FALSE, TRUE, 'd', '', 0),
              (16574, 982, 'Pipe', 'PIPE', 13, 198, '', 0, FALSE, TRUE, 'd', '', 0),
              (16575, 982, 'indicador_pagto_olimpiada', 'INDICADOR DE PAGAMENTO PARA A OLIMPÍADA', 1, 188, 'N', 1, FALSE, TRUE, 'd', 'Indicador de pagamentos aos jogos olímpicos e paraolímpicos de 2016. S para existência e N para não existência.', 0),
              (16491, 983, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'IDREC', 5, TRUE, TRUE, 'd', '', 0),
              (16503, 983, 'codigo_receita', 'CÓDIGO DE RECEITA', 2, 6, '', 4, FALSE, TRUE, 'e', '', 0),
              (16522, 983, 'Pipe', 'PIPE', 13, 10, '', 0, FALSE, TRUE, 'd', '', 0),
              (16492, 984, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'BPFDEC', 6, TRUE, TRUE, 'd', '', 0),
              (16499, 984, 'data_laudo', 'DATA ATRIBUÍDA PELO LAUDO DA MOLÉSTIA GR', 1, 78, '', 0, FALSE, TRUE, 'd', '', 0),
              (16501, 984, 'cpf', 'CPF', 1, 7, '', 11, FALSE, TRUE, 'd', '', 0),
              (16502, 984, 'nome', 'NOME', 1, 18, '', 60, FALSE, TRUE, 'd', '', 0),
              (16521, 984, 'Pipe', 'PIPE', 13, 78, '', 0, FALSE, TRUE, 'd', '', 0),
              (16496, 985, 'cnpj', 'CNPJ', 1, 7, '', 14, FALSE, TRUE, 'd', '', 0),
              (16497, 985, 'nome', 'NOME', 1, 21, '', 60, FALSE, TRUE, 'd', '', 0),
              (16498, 985, 'identificador_registro', 'IDETIFICADOR DE REGISTRO', 1, 1, 'BPJDEC', 6, TRUE, TRUE, 'd', '', 0),
              (16525, 985, 'Pipe', 'PIPE', 13, 81, '', 0, FALSE, TRUE, 'd', '', 0),
              (16479, 986, 'identificador_registro', 'identificador_registro', 1, 1, 'PSE', 3, TRUE, TRUE, 'd', '', 0),
              (16480, 986, 'pipe', 'PIPE', 1, 4, '', 0, FALSE, TRUE, 'd', '', 0),
              (16510, 987, 'cpf', 'CPF', 1, 4, '', 11, FALSE, TRUE, 'd', '', 0),
              (16520, 987, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'INF', 3, TRUE, TRUE, 'd', '', 0),
              (16526, 987, 'Pipe', 'PIPE', 13, 215, '', 0, FALSE, TRUE, 'd', '', 0),
              (16571, 987, 'informacao_complementar', 'INFORMAÇÃO COMLEMENTAR', 1, 15, '', 500, FALSE, TRUE, 'd', '', 0),
              (16523, 988, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'FIMDirf', 7, TRUE, TRUE, 'd', '', 0),
              (16524, 988, 'Pipe', 'PIPE', 13, 8, '', 0, FALSE, TRUE, 'd', '', 0),
              (16476, 989, 'valor_anual', 'VALOR ANUAL', 1, 4, '', 13, FALSE, TRUE, 'd', '', 0),
              (16477, 989, 'descricao_rend_isentos', 'DESCRIÇÃO DOS RENDIMENTOS ISENTOS - OUTR', 1, 17, 'OUTROS', 60, FALSE, TRUE, 'd', '', 0),
              (16478, 989, 'Pipe', 'PIPE', 13, 77, '', 0, FALSE, TRUE, 'd', '', 0),
              (16493, 989, 'identificador_registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'RIO', 3, TRUE, TRUE, 'd', '', 0),
              (16486, 990, 'identificador_registro', 'identificador_registro', 1, 1, 'TPSE', 4, TRUE, TRUE, 'd', '', 0),
              (16487, 990, 'cpf', 'cpf', 1, 5, '', 11, FALSE, TRUE, 'd', '', 0),
              (16488, 990, 'nome', 'nome', 1, 16, '', 60, FALSE, TRUE, 'd', '', 0),
              (16511, 990, 'valor_ano', 'valor_ano', 1, 76, '', 13, FALSE, TRUE, 'e', '', 0),
              (16516, 990, 'pipe', 'PIPE', 13, 89, '', 0, FALSE, TRUE, 'd', '', 0),
              (16483, 991, 'identificador_registro', 'identificador_registro', 1, 1, 'OPSE', 4, TRUE, TRUE, 'd', '', 0),
              (16484, 991, 'cnpj', 'CNPJ OPERADOR', 1, 5, '', 14, FALSE, TRUE, 'd', '', 0),
              (16489, 991, 'pipe', 'PIPE', 13, 175, '', 0, FALSE, TRUE, 'd', '', 0),
              (16494, 991, 'nome', 'nome', 13, 19, '', 150, FALSE, TRUE, 'd', '', 0),
              (16495, 991, 'registro_ans', 'registro_ans', 1, 169, '', 6, FALSE, TRUE, 'd', '', 0),
              (16576, 992, 'Identificador de Registro', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'INFPC', 5, TRUE, TRUE, 'd', '', 0),
              (16577, 992, 'CNPJ', 'CNPJ', 1, 6, '', 14, FALSE, TRUE, 'd', '', 0),
              (16578, 992, 'Nome Empresarial', 'NOME EMPRESARIAL', 1, 20, '', 150, FALSE, TRUE, 'd', '', 0),
              (16580, 992, 'pipe', 'PIPE', 1, 170, '|', 0, FALSE, TRUE, 'd', '', 0),
              (16581, 993, 'identificador', 'IDENTIFICADOR DE REGISTRO', 1, 1, 'INFPA', 5, TRUE, TRUE, 'd', '', 0),
              (16582, 993, 'cpf_alimentando', 'CPF DO ALIMENTADO', 1, 6, '', 11, FALSE, TRUE, 'd', '', 0),
              (16583, 993, 'data_nascimento', 'DATA DE NASCIMENTO', 1, 17, '', 8, FALSE, TRUE, 'd', '', 0),
              (16584, 993, 'nome', 'NOME', 1, 25, '', 60, FALSE, TRUE, 'd', '', 0),
              (16585, 993, 'relacao_dependencia', 'RELAÇÃO DE DEPENDÊNCIA', 2, 85, '', 2, FALSE, TRUE, 'e', '', 0),
              (16579, 993, 'pipe', 'PIPE', 1, 87, '|', 0, FALSE, TRUE, 'd', '', 0);
            
            INSERT INTO db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
            VALUES
              (16587, 982, 'indicador_execucao_orcamentaria', 'INDICADOR EXECUÇÃO ORÇAMENTÁRIA', 1, 189, 'N', 1, 'f', 't', 'd', '',
               0);
            
            UPDATE db_layoutcampos
            SET db52_posicao = db52_posicao + 1
            WHERE db52_layoutlinha = 982 AND db52_posicao >= 189 AND db52_codigo <> 16587;
            
            UPDATE db_layoutcampos
            SET db52_posicao = 79, db52_tamanho = 8
            WHERE db52_nome = 'data_laudo' AND db52_layoutlinha = 984;
            
            INSERT INTO db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
            VALUES
              (16589, 984, 'indicador_identificacao_alimentando', 'INDICADOR DE ID. DO ALIMENTANDO', 1, 88, 'N', 1, 'f', 't', 'd',
               '', 0);
            
            INSERT INTO db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
            VALUES
              (16590, 984, 'indicador_identificacao_previdencia_complementar', 'INDICADOR DE ID. DA PREVIDÊNCIA COMPLE.', 1, 89,
                      'N', 1, 'f', 't', 'd', '', 0);
            
            UPDATE db_layoutcampos
            SET db52_posicao = 90, db52_tamanho = 1
            WHERE db52_nome = 'Pipe' AND db52_layoutlinha = 984;        
            
            UPDATE db_layoutcampos
            SET db52_posicao = db52_posicao - 1
            WHERE db52_layoutlinha = 982 AND db52_posicao >= 187 AND db52_codigo <> 16542;
            
            DELETE FROM db_layoutcampos
            WHERE db52_codigo = 16542;
            
            INSERT INTO db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
            VALUES
              (16594, 977, 'indicador_identificacao_alimentando_rra', 'INDICADOR IDENTIFICAÇÃO ALIMENTANDO RRA', 1, 136, 'N', 1,
                      'f', 't', 'd', '', 0);
            
            UPDATE db_layoutcampos
            SET db52_posicao = db52_posicao + 1
            WHERE db52_layoutlinha = 977 AND db52_posicao >= 136 AND db52_codigo <> 16594;              
            
            INSERT INTO db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
            VALUES (16595, 976, 'valor_pago_advogado', 'VALOR PAGO PARA O ADVOGADO', 2, 189, '', 13, 'f', 't', 'd', '', 0);
            
            UPDATE db_layoutcampos
            SET db52_posicao = db52_posicao + 13
            WHERE db52_layoutlinha = 976 AND db52_posicao >= 189 AND db52_codigo <> 16595;           
            
            SELECT setval('db_layouttxt_db50_codigo_seq', (SELECT max(db50_codigo)
                                               FROM db_layouttxt) + 1);

            SELECT setval('db_layoutlinha_db51_codigo_seq', (SELECT max(db51_codigo)
                                                             FROM db_layoutlinha) + 1);
            
            SELECT setval('db_layoutcampos_db52_codigo_seq', (SELECT max(db52_codigo)
                                                              FROM db_layoutcampos) + 1);                                                                                                     
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM db_layoutcampos WHERE db52_layoutlinha IN (SELECT db51_codigo FROM db_layoutlinha WHERE db51_layouttxt = 297);
            DELETE FROM db_layoutlinha WHERE db51_layouttxt = 297;
            DELETE FROM db_layouttxt WHERE db50_codigo = 297;
            
            SELECT setval('db_layouttxt_db50_codigo_seq', (SELECT max(db50_codigo)
                                               FROM db_layouttxt) + 1);

            SELECT setval('db_layoutlinha_db51_codigo_seq', (SELECT max(db51_codigo)
                                                             FROM db_layoutlinha) + 1);
            
            SELECT setval('db_layoutcampos_db52_codigo_seq', (SELECT max(db52_codigo)
                                                              FROM db_layoutcampos) + 1);              
        ");
    }
}
