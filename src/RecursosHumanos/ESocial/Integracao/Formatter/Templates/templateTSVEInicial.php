<?php
/* S-2300 - Trabalhador Sem Vínculo de Emprego/Estatutário - Início */
return array(
    'trabalhador' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab',
            'nmTrab',
            'sexo',
            'racaCor' => array(
              'type' => 'int'
            ),
            'estCiv' => array(
              'type' => 'int'
            ),
            'grauInstr'
        ),
        'groups' => array(
            'nascimento' => array(
                'properties' => array(
                    'dtNascto',
                    'codMunic',
                    'uf',
                    'paisNascto',
                    'paisNac',
                    'nmMae',
                    'nmPai'
                )
            ),
            'documentos' => array(
                'groups' => array(
                    'CTPS' => array(
                        'properties' => array(
                            'nrCtps',
                            'serieCtps',
                            'ufCtps'
                        )
                    ),
                    'RIC' => array(
                        'properties' => array(
                            'nrRic',
                            'orgaoEmissor_Ric' =>'orgaoEmissor',
                            'dtExped_Ric' => 'dtExped'
                        )
                    ),
                    'RG' => array(
                        'properties' => array(
                            'nrRg',
                            'orgaoEmissor_RG' => 'orgaoEmissor',
                            'dtExped_RG' => 'dtExped'
                        )
                    ),
                    'RNE' => array(
                        'properties' => array(
                            'nrRne',
                            'orgaoEmissor_RNE' => 'orgaoEmissor',
                            'dtExped_RNE' => 'dtExped',
                        )
                    ),
                    'OC' => array(
                        'properties' => array(
                            'nrOc',
                            'orgaoEmissor_OC' => 'orgaoEmissor',
                            'dtExped_OC' => 'dtExped',
                            'dtValid_OC' => 'dtValid',
                        )
                    ),
                    'CNH' => array(
                        'properties' => array(
                            'nrRegCnh',
                            'dtExped_CNH' =>'dtExped',
                            'ufCnh',
                            'dtValid_CNH' =>'dtValid',
                            'dtPriHab',
                            'categoriaCnh',
                        )
                    ),
                ),
            ),
            'endereco' => array(
                'groups' => array(
                    'brasil' => array(
                        'properties' => array(
                            'tpLograd_brasil' => 'tpLograd',
                            'dscLograd_brasil' => 'dscLograd',
                            'nrLograd_brasil' => 'nrLograd',
                            'complemento_brasil' => 'complemento',
                            'bairro_brasil' => 'bairro',
                            'cep_brasil' => 'cep',
                            'codMunic_brasil' => array(
                                'nome_api' => 'codMunic'
                            ),
                            'uf_brasil' => 'uf'
                        )
                    ),
                    'exterior' => array(
                        'properties' => array(
                            'paisResid_exterior' => 'paisResid',
                            'dscLograd_exterior' => 'dscLograd',
                            'nrLograd_exterior' => 'nrLograd',
                            'complemento_exterior' => 'complemento',
                            'bairro_exterior' => 'bairro',
                            'nmCid_exterior' => 'nmCid',
                            'codPostal_exterior' => 'codPostal'
                        )
                    ),
                ),
            ),
            'trabEstrangeiro' => array(
                'properties' => array(
                    'dtChegada',
                    'classTrabEstrang',
                    'casadoBr',
                    'filhosBr',
                )
            ),
            'infoDeficiencia' => array(
                'properties' => array(
                    'defFisica',
                    'defVisual',
                    'defAuditiva',
                    'defMental',
                    'defIntelectual',
                    'reabReadap',
                    'observacao'
                )
            ),
            'dependente_1' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_1_tpDep' => 'tpDep',
                        'dependente_1_nmDep' => 'nmDep',
                        'dependente_1_dtNascto' => 'dtNascto',
                        'dependente_1_cpfDep' => 'cpfDep',
                        'dependente_1_depIRRF' => 'depIRRF',
                        'dependente_1_depSF' => 'depSF',
                        'dependente_1_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_2' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_2_tpDep' => 'tpDep',
                        'dependente_2_nmDep' => 'nmDep',
                        'dependente_2_dtNascto' => 'dtNascto',
                        'dependente_2_cpfDep' => 'cpfDep',
                        'dependente_2_depIRRF' => 'depIRRF',
                        'dependente_2_depSF' => 'depSF',
                        'dependente_2_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_3' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_3_tpDep' => 'tpDep',
                        'dependente_3_nmDep' => 'nmDep',
                        'dependente_3_dtNascto' => 'dtNascto',
                        'dependente_3_cpfDep' => 'cpfDep',
                        'dependente_3_depIRRF' => 'depIRRF',
                        'dependente_3_depSF' => 'depSF',
                        'dependente_3_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_4' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_4_tpDep' => 'tpDep',
                        'dependente_4_nmDep' => 'nmDep',
                        'dependente_4_dtNascto' => 'dtNascto',
                        'dependente_4_cpfDep' => 'cpfDep',
                        'dependente_4_depIRRF' => 'depIRRF',
                        'dependente_4_depSF' => 'depSF',
                        'dependente_4_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_5' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_5_tpDep' => 'tpDep',
                        'dependente_5_nmDep' => 'nmDep',
                        'dependente_5_dtNascto' => 'dtNascto',
                        'dependente_5_cpfDep' => 'cpfDep',
                        'dependente_5_depIRRF' => 'depIRRF',
                        'dependente_5_depSF' => 'depSF',
                        'dependente_5_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_6' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_6_tpDep' => 'tpDep',
                        'dependente_6_nmDep' => 'nmDep',
                        'dependente_6_dtNascto' => 'dtNascto',
                        'dependente_6_cpfDep' => 'cpfDep',
                        'dependente_6_depIRRF' => 'depIRRF',
                        'dependente_6_depSF' => 'depSF',
                        'dependente_6_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_7' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_7_tpDep' => 'tpDep',
                        'dependente_7_nmDep' => 'nmDep',
                        'dependente_7_dtNascto' => 'dtNascto',
                        'dependente_7_cpfDep' => 'cpfDep',
                        'dependente_7_depIRRF' => 'depIRRF',
                        'dependente_7_depSF' => 'depSF',
                        'dependente_7_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_8' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_8_tpDep' => 'tpDep',
                        'dependente_8_nmDep' => 'nmDep',
                        'dependente_8_dtNascto' => 'dtNascto',
                        'dependente_8_cpfDep' => 'cpfDep',
                        'dependente_8_depIRRF' => 'depIRRF',
                        'dependente_8_depSF' => 'depSF',
                        'dependente_8_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_9' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_9_tpDep' => 'tpDep',
                        'dependente_9_nmDep' => 'nmDep',
                        'dependente_9_dtNascto' => 'dtNascto',
                        'dependente_9_cpfDep' => 'cpfDep',
                        'dependente_9_depIRRF' => 'depIRRF',
                        'dependente_9_depSF' => 'depSF',
                        'dependente_9_incTrab' => 'incTrab'
                    )
                )
            ),
            'dependente_10' => array (
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'dependente_10_tpDep' => 'tpDep',
                        'dependente_10_nmDep' => 'nmDep',
                        'dependente_10_dtNascto' => 'dtNascto',
                        'dependente_10_cpfDep' => 'cpfDep',
                        'dependente_10_depIRRF' => 'depIRRF',
                        'dependente_10_depSF' => 'depSF',
                        'dependente_10_incTrab' => 'incTrab'
                    )
                )
            ),
            'contato' => array(
                'properties' => array(
                    'fonePrinc',
                    'foneAlternat',
                    'emailPrinc',
                    'emailAlternat'
                )
            )
        )
    ),
    'infoTSVInicio' => array(
        'properties' => array(
            'cadIni',
            'codCateg',
            'dtInicio',
            'natAtividade' => array(
                'type' => 'int'
            )
        ),
        'groups' => array(
            'infoComplementares' => array(
                'groups' => array(
                    'cargoFuncao' => array(
                        'properties' => array(
                            'codCargo',
                            'codFuncao'
                        )
                    ),
                    'remuneracao' => array(
                        'properties' => array(
                            'vrSalFx' => array(
                                'type' => 'float'
                            ),
                            'undSalFixo' => array(
                              'type' => 'int'
                            ),
                            'dscSalVar'
                        )
                    ),
                    'fgts' => array(
                        'properties' => array(
                            'opcFGTS' => array(
                                'type' => 'int'
                            ),
                            'dtOpcFGTS',
                        )
                    ),
                    'infoDirigenteSindical' => array(
                        'properties' => array(
                            'categOrig',
                            'cnpjOrigem',
                            'dtAdmOrig',
                            'matricOrig'
                        )
                    ),
                    'infoTrabCedido' => array(
                        'properties' => array(
                            'categOrig',
                            'cnpjCednt',
                            'matricCed',
                            'dtAdmCed',
                            'tpRegTrab' => array(
                                'type' => 'int'
                            ),
                            'tpRegPrev' => array(
                                'type' => 'int'
                            ),
                            'infOnus' => array(
                                'type' => 'int'
                            )
                        )
                    ),
                    'infoEstagiario' => array(
                        'properties' => array(
                            'natEstagio',
                            'nivEstagio' => array(
                                'type' => 'int',
                            ),
                            'areaAtuacao',
                            'nrApol',
                            'vlrBolsa' => array(
                                'type' => 'float',
                            ),
                            'dtPrevTerm'
                        ),
                        'groups' => array(
                            'instEnsino' => array(
                                'properties' => array(
                                    'instEnsino_cnpjInstEnsino' => 'cnpjInstEnsino',
                                    'instEnsino_nmRazao' => 'nmRazao',
                                    'instEnsino_dscLograd' => 'dscLograd',
                                    'instEnsino_nrLograd' => 'nrLograd',
                                    'instEnsino_bairro' => 'bairro',
                                    'instEnsino_cep' => 'cep',
                                    'instEnsino_codMunic' => array(
                                        'nome_api' => 'codMunic'
                                    ),
                                    'instEnsino_uf' => 'uf',
                                )
                            ),
                            'ageIntegracao' => array(
                                'properties' => array(
                                    'ageIntegracao_cnpjAgntInteg' => 'cnpjAgntInteg',
                                    'ageIntegracao_nmRazao' => 'nmRazao',
                                    'ageIntegracao_dscLograd' => 'dscLograd',
                                    'ageIntegracao_nrLograd' => 'nrLograd',
                                    'ageIntegracao_bairro' => 'bairro',
                                    'ageIntegracao_cep' => 'cep',
                                    'ageIntegracao_codMunic' => array(
                                        'nome_api' => 'codMunic'
                                    ),
                                    'ageIntegracao_uf' => 'uf',
                                )
                            ),
                            'supervisorEstagio' => array(
                                'properties' => array(
                                    'cpfSupervisor',
                                    'nmSuperv'
                                )
                            )
                        )
                    )
                )
            ),
            'afastamento' => array(
                'properties' => array(
                    'dtIniAfast',
                    'codMotAfast'
                )
            ),
            'termino' => array(
                'properties' => array(
                    'dtTerm'
                )
            ),
            'mudancaCPF' => array(
                'properties' => array(
                    'cpfAnt',
                    'dtAltCPF',
                    'mudancaCPF_observacao' => 'observacao'
                )
            )
        )
    )
);
