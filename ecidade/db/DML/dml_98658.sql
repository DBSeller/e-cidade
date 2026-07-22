
/**
 * Criando layout para o cnab240 para o banrisul
 */
insert into db_layouttxt values (229, 'CNAB240 BANCO DO ESTADO DO RS', 0, 'Arquivo bancário cnab 240', 5);

/**
 * Criando as linhas para o arquivo
 */
insert into db_layoutlinha values (751, 229, 'REGISTRO HEADER ARQUIVO', 1, 240, 0, 0, NULL, NULL, false);
insert into db_layoutlinha values (752, 229, 'REGISTRO HEADER LOTE', 2, 240, 0, 0, NULL, NULL, false);
insert into db_layoutlinha values (753, 229, 'REGISTRO DETALHE  TIPO 3- SEGMENTO A', 3, 240, 0, 0, NULL, NULL, false);
insert into db_layoutlinha values (754, 229, 'REGISTRO TRAILER LOTE/ REG 5', 4, 240, 0, 0, '', '', false);
insert into db_layoutlinha values (755, 229, 'REGISTRO TRAILER DE ARQUIVO / REG 5', 5, 240, 0, 0, NULL, NULL, false);

/**
 * Inserindo os campos para cada linha.
 */
insert into db_layoutcampos values (12543, 753, 'cod_compensacao', 'CÓDIGO DA CÂMARA COMPENSAÇÃO', 1, 18, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12544, 753, 'codigo_banco_fav', 'CÓDIGO DO BANCO DO FAVORECIDO', 1, 21, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12545, 753, 'agenc_conta', 'AGÊNCIA MANTENEDORA DA CONTA FAV', 1, 24, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12546, 753, 'digito_verificador_conta', 'DÍGITO VERIFICADOR DA CONTA', 1, 29, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12547, 753, 'num_conta_corrente', 'NÚMERO DA CONTA CORRENTE', 1, 30, '', 13, false, true, 'e', '', 0);
insert into db_layoutcampos values (12548, 753, 'digito_conta', 'ZERO OU BRANCOS', 1, 43, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12549, 753, 'vlr_real_efetivado', 'VALOR DO CRÉDITO EFETUADO', 1, 163, '', 15, false, true, 'e', '', 0);
insert into db_layoutcampos values (12550, 753, 'brancos_2', 'BRANCOS', 1, 178, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12551, 753, 'nome_favorecido', 'NOME DO FAVORECIDO', 1, 44, '', 30, false, true, 'e', '', 0);
insert into db_layoutcampos values (12552, 753, 'num_documento', 'NÚMERO DO DOCUMENTO DE COBRANÇA', 1, 74, '', 15, false, true, 'e', '', 0);
insert into db_layoutcampos values (12553, 753, 'finalidade_cliente', 'FINALIDADE DO CLIENTE TED OU DOC', 1, 89, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12554, 753, 'data_credito', 'DATA DO CREDITO', 1, 94, '', 8, false, true, 'e', '', 0);
insert into db_layoutcampos values (12555, 753, 'tipo_moeda', 'TIPO DE MOEDA', 1, 102, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12556, 753, 'zeros_1', 'ZEROS', 1, 105, '', 15, false, true, 'e', '', 0);
insert into db_layoutcampos values (12557, 753, 'valor_creditado', 'VALOR A SER CREDITADO', 1, 120, '', 15, false, true, 'e', '', 0);
insert into db_layoutcampos values (12558, 753, 'num_documento_atribuido', 'NÚMERO DO DOCUMENTO ATRIBUIDO PELO BANCO', 1, 135, '', 20, false, true, 'd', '', 0);
insert into db_layoutcampos values (12559, 753, 'data_efetiva_cred', 'DATA DA EFETIVAÇÃO DO CRÉDITO', 1, 155, '', 8, false, true, 'e', '', 0);
insert into db_layoutcampos values (12560, 753, 'exclusivo_febraban', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 218, '', 12, false, true, 'd', '', 0);
insert into db_layoutcampos values (12561, 753, 'brancos_3', '0 NÃO EMITE AVISO AO FAVORECIDO', 1, 230, '', 1, false, true, 'd', '', 0);
insert into db_layoutcampos values (12562, 753, 'cod_identifcador_transf', 'CÓDIGO IDENTIFICARDOR DE TRANSFERENCIA', 1, 183, '', 20, false, true, 'd', '', 0);
insert into db_layoutcampos values (12563, 753, 'tipo_inscricao', 'TIPO DE INSCRIÇÃO', 1, 203, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12564, 753, 'num_inscricao', 'NÚMERO DA INSCRIÇÃO', 1, 204, '', 14, false, true, 'e', '', 0);
insert into db_layoutcampos values (12565, 753, 'ocorrencias', 'CÓDIGOS DAS OCORRÊNCIAS DE RETONO', 1, 231, '', 10, false, true, 'e', '', 0);
insert into db_layoutcampos values (12566, 753, 'codigo_banco', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 1, 1, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12567, 753, 'codigo_registro', 'REGISTRO DETALHE', 1, 8, '3', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12568, 753, 'num_registro_lote', 'Nº SEQÜENCIAL DO REGISTRO NO LOTE', 1, 9, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12569, 753, 'lote_servico', 'LOTE DE SERVIÇO', 1, 4, '', 4, false, true, 'e', '', 0);
insert into db_layoutcampos values (12570, 753, 'codigo_segmento', 'CÓD. SEGMENTO DO REGISTRO DETALHE', 1, 14, 'A', 1, true, true, 'e', '', 0);
insert into db_layoutcampos values (12571, 753, 'tipo_movimento', 'TIPO DE MOVIMENTO', 1, 15, '', 1, false, true, 'd', '', 0);
insert into db_layoutcampos values (12572, 753, 'codigo_mov', 'CÓDIGO DE MOVIMENTO', 1, 16, '', 2, false, true, 'e', '', 0);

insert into db_layoutcampos values (12573, 754, 'cnab_2', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 60, '', 171, false, true, 'e', '', 0);
insert into db_layoutcampos values (12574, 754, 'cod_ocorrencias_retorno', 'CÓDIGOS DE OCORRÊNCIA PARA RETOENO', 1, 231, '', 10, false, true, 'e', '', 0);
insert into db_layoutcampos values (12575, 754, 'soma_valores', 'SOMATÓRIO DOS VALORES', 1, 24, '', 18, false, true, 'e', '', 0);
insert into db_layoutcampos values (12576, 754, 'somatorio_quant_moedas', 'SOMATÓRIO DE QUANTIDADES DE MOEDAS', 1, 42, '', 18, false, true, 'e', '', 0);
insert into db_layoutcampos values (12577, 754, 'codigo_registro', 'REGISTRO TRAILER DO LOTE', 1, 8, '5', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12578, 754, 'cnab_1', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 9, '', 9, false, true, 'e', '', 0);
insert into db_layoutcampos values (12579, 754, 'quantid_registros_lote', 'QUANTIDADE DE REGISTROS DO LOTE', 1, 18, '', 6, false, true, 'e', '', 0);
insert into db_layoutcampos values (12580, 754, 'codigo_compens', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 1, 1, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12581, 754, 'lote_servico', 'LOTE DE SERVIÇO', 1, 4, '', 4, false, true, 'e', '', 0);
insert into db_layoutcampos values (12582, 755, 'cnab_2', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 36, '', 205, false, true, 'd', '', 0);
insert into db_layoutcampos values (12583, 755, 'codigo_registro', 'REGISTRO TRAILER DE ARQUIVO', 1, 8, '9', 1, true, true, 'd', '', 0);
insert into db_layoutcampos values (12584, 755, 'codigo_compens', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 1, 1, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12585, 755, 'lote_servico', 'LOTE DE SERVIÇO', 1, 4, '', 4, false, true, 'e', '', 0);
insert into db_layoutcampos values (12586, 755, 'cnab_1', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 9, '', 9, false, true, 'e', '', 0);
insert into db_layoutcampos values (12587, 755, 'quantid_lotes', 'QUANTID. DE LOTES DO ARQUIVO', 1, 18, '', 6, false, true, 'e', '', 0);
insert into db_layoutcampos values (12588, 755, 'quantid_registros', 'QUANTID. DE REGISTROS DO ARQUIVO', 1, 24, '', 6, false, true, 'e', '', 0);
insert into db_layoutcampos values (12589, 755, 'zeros_1', 'ZEROS', 1, 30, '', 6, false, true, 'e', '', 0);

insert into db_layoutcampos values (12590, 752, 'endereco', 'ENDEREÇO', 1, 143, '', 30, false, true, 'd', '', 0);
insert into db_layoutcampos values (12591, 752, 'brancos_2', 'BRANCO', 1, 103, '', 40, false, true, 'd', '', 0);
insert into db_layoutcampos values (12592, 752, 'nome_cidade', 'NOME DA CIDADE', 1, 193, '', 20, false, true, 'e', '', 0);
insert into db_layoutcampos values (12593, 752, 'cep', 'CEP', 1, 213, '', 8, false, true, 'e', '', 0);
insert into db_layoutcampos values (12594, 752, 'sigla_estado', 'SIGLA DO ESTADO', 1, 221, '', 2, false, true, 'e', '', 0);
insert into db_layoutcampos values (12595, 752, 'classif_ordem', 'CLASSIFICAÇÃO DA ORDEM DOS REG NO LOTE', 1, 223, '', 2, false, true, 'd', '', 0);
insert into db_layoutcampos values (12596, 752, 'cnab_2', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 225, '', 6, false, true, 'e', '', 0);
insert into db_layoutcampos values (12597, 752, 'ocorrencias', 'CÓDIGO DE OCORENCIAS P/RETORNO', 1, 231, '', 10, false, true, 'd', '', 0);
insert into db_layoutcampos values (12598, 752, 'codigo_compens', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 1, 1, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12599, 752, 'lote_serv', 'LOTE DE SERVIÇO', 1, 4, '', 4, false, true, 'e', '', 0);
insert into db_layoutcampos values (12600, 752, 'codigo_registro', 'REGISTRO HEADER DO LOTE', 1, 8, '1', 1, true, true, 'e', '', 0);
insert into db_layoutcampos values (12601, 752, 'num_insc_emp', 'Nº DE INSCRIÇÃO DA EMPRESA', 1, 19, '', 14, false, true, 'e', '', 0);
insert into db_layoutcampos values (12602, 752, 'codigo_conv_banco', 'CÓDIGO DO CONVÊNIO NO BANCO', 1, 33, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12603, 752, 'tipo_operac', 'TIPO DE OPERAÇÃO', 1, 9, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12604, 752, 'tipo_serv', 'TIPO DE SERVIÇO', 1, 10, '', 2, false, true, 'e', '', 0);
insert into db_layoutcampos values (12605, 752, 'num_layout_lote', 'Nº DA VERSÃO DO LAYOUT DO LOTE', 1, 14, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12606, 752, 'cnab_1', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 17, '', 1, false, true, 'd', '', 0);
insert into db_layoutcampos values (12607, 752, 'forma_lanca', 'FORMA DE LANÇAMENTO', 1, 12, '', 2, false, true, 'e', '', 0);
insert into db_layoutcampos values (12608, 752, 'tipo_insc_empresa', 'TIPO DE INSCRIÇÃO DA EMPRESA', 1, 18, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12609, 752, 'branco_1', 'BRANCOS', 1, 38, '', 15, false, true, 'e', '', 0);
insert into db_layoutcampos values (12610, 752, 'agenc_mantenad_conta', 'Agência Mantenedora da Conta no Banrisul', 1, 53, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12611, 752, 'zeros_1', 'ZEROS CONSTANTES', 1, 58, '', 4, false, true, 'e', '', 0);
insert into db_layoutcampos values (12612, 752, 'num_conta_corrente', 'NÚMERO DA CONTA CORRENTE', 1, 62, '', 10, false, true, 'e', '', 0);
insert into db_layoutcampos values (12613, 752, 'digito_verific_agenc', 'DÍGITO VERIFICADOR DA AG/CONTA', 1, 72, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12614, 752, 'nome_empresa', 'NOME DA EMPRESA', 1, 73, '', 30, false, true, 'd', '', 0);
insert into db_layoutcampos values (12615, 752, 'numero_local', 'NÚMERO LOCAL', 1, 173, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12616, 752, 'complemento_1', 'COMPLEMENTO, CASA, APTO', 1, 178, '', 15, false, true, 'e', '', 0);

insert into db_layoutcampos values (12490, 751, 'codigo_banco', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 1, 1, '041', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12491, 751, 'lote_servico', 'LOTE DE SERVIÇO', 1, 4, '', 4, false, true, 'e', '', 0);
insert into db_layoutcampos values (12492, 751, 'codigo_registro', 'REGISTRO HEADER DE ARQUIVO', 1, 8, '0', 1, true, true, 'd', '', 0);
insert into db_layoutcampos values (12493, 751, 'cnab', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 9, '', 9, false, true, 'd', '', 0);
insert into db_layoutcampos values (12494, 751, 'cod_conv_banco', 'CÓDIGO DO CONVÊNIO NO BANCO', 1, 33, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12495, 751, 'agenc_conta', 'AGÊNCIA MANTENEDORA DA CONTA', 1, 53, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12496, 751, 'tipo_insc_emp', 'TIPO DE INSCRIÇÃO DA EMPRESA', 1, 18, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12497, 751, 'brancos_1', 'BRANCOS', 1, 38, '', 15, false, true, 'e', '', 0);
insert into db_layoutcampos values (12498, 751, 'num_insc_emp', 'Nº DE INSCRIÇÃO DA EMPRESA', 1, 19, '', 14, false, true, 'e', '', 0);
insert into db_layoutcampos values (12499, 751, 'dig_verific_agenc', 'DÍGITO VERIFICADOR DA AGÊNCIA', 1, 58, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12500, 751, 'zeros_constantes', 'NIMERICO OBRIGATORIO', 1, 59, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12501, 751, 'num_conta_corrente', 'NÚMERO DA CONTA CORRENTE.', 1, 62, '', 10, false, true, 'e', '', 0);
insert into db_layoutcampos values (12502, 751, 'dig_verificador_ag', 'DÍGITO VERIFICADOR DA AG/CONTA', 1, 72, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12503, 751, 'nome_empresa', 'NOME DA EMPRESA', 1, 73, '', 30, false, true, 'd', '', 0);
insert into db_layoutcampos values (12504, 751, 'nome_banco', 'NOME DO BANCO', 1, 103, '', 30, false, true, 'd', '', 0);
insert into db_layoutcampos values (12505, 751, 'cnab_2', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 133, '', 10, false, true, 'd', '', 0);
insert into db_layoutcampos values (12506, 751, 'exclusivo_febraban', 'USO EXCLUSIVO FEBRABAN/CNAB', 1, 212, '', 29, false, true, 'd', '', 0);
insert into db_layoutcampos values (12507, 751, 'cod_remessa', 'CÓDIGO REMESSA / RETORNO', 1, 143, '', 1, false, true, 'e', '', 0);
insert into db_layoutcampos values (12508, 751, 'data_gera_arq', 'DATA DE GERAÇÃO DO ARQUIVO', 1, 144, '', 8, false, true, 'e', '', 0);
insert into db_layoutcampos values (12509, 751, 'hora_gera_arq', 'HORA DE GERAÇÃO DO ARQUIVO', 1, 152, '', 6, false, true, 'e', '', 0);
insert into db_layoutcampos values (12510, 751, 'seq_arquivo', 'Nº SEQÜENCIAL DO ARQUIVO', 1, 158, '', 6, false, true, 'e', '', 0);
insert into db_layoutcampos values (12511, 751, 'num_layout_arquivo', 'Nº DA VERSÃO DO LAYOUT DO ARQUIVO', 1, 164, '', 3, false, true, 'e', '', 0);
insert into db_layoutcampos values (12512, 751, 'densidade_gravac', 'DENSIDADE DE GRAVAÇÃO DO ARQUIVO', 1, 167, '', 5, false, true, 'e', '', 0);
insert into db_layoutcampos values (12513, 751, 'uso_banco', 'USO RESERVADO DO BANCO', 1, 172, '', 20, false, true, 'd', '', 0);
insert into db_layoutcampos values (12514, 751, 'reserv_banc_remessa', 'USO RESERVADO DO BANCO - REMESSA', 1, 192, '', 20, false, true, 'd', '', 0);