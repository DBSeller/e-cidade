<?php
/**
 * Arquivo S - 2205 - Alteração dos Dados do Servidor
 */
return array(
    'ideTrabalhador' => array(
        'properties' => array(
            'cpfTrab'
        )
    ),
    'alteracao' => array(
        'properties' => array(
            'dtAlteracao'
        ),
        'groups' => array(
            'dadosTrabalhador' => array(
                'properties' => array(
                    'nisTrab',
                    'nmTrab',
                    'sexo',
                    'racaCor',
                    'estCiv',
                    'grauInstr',
                    'nmSoc'
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
                                    'RIC_nrRic' => 'nrRic',
                                    'RIC_orgaoEmissor' => 'orgaoEmissor',
                                    'RIC_dtExped' => 'dtExped'
                                )
                            ),
                            'RG' => array(
                                'properties' => array(
                                    'RG_nrRg' => 'nrRg',
                                    'RG_orgaoEmissor' => 'orgaoEmissor',
                                    'RG_dtExped' => 'dtExped'
                                )
                            ),
                            'RNE' => array(
                                'properties' => array(
                                    'RNE_nrRne' => 'nrRne',
                                    'RNE_orgaoEmissor' => 'orgaoEmissor',
                                    'RNE_dtExped' => 'dtExped',
                                )
                            ),
                            'OC' => array(
                                'properties' => array(
                                    'OC_nrOc' => 'nrOc',
                                    'OC_orgaoEmissor' => 'orgaoEmissor',
                                    'OC_dtExped' => 'dtExped',
                                    'OC_dtValid' => 'dtValid',
                                )
                            ),
                            'CNH' => array(
                                'properties' => array(
                                    'CNH_nrRegCnh' => 'nrRegCnh',
                                    'CNH_dtExped' => 'dtExped',
                                    'CNH_ufCnh' => 'ufCnh',
                                    'CNH_dtValid' => 'dtValid',
                                    'CNH_dtPriHab' => 'dtPriHab',
                                    'CNH_categoriaCnh' => 'categoriaCnh',
                                )
                            ),
                        )
                    ),

                    'endereco' => array(
                        'label' => 'Endereços',
                        'groups' => array(
                            'brasil' => array(
                                'properties' => array(
                                    'tpLograd',
                                    'dscLograd',
                                    'nrLograd',
                                    'complemento',
                                    'bairro',
                                    'cep',
                                    'brasil_codMunic' => array(
                                        'type' => 'int',
                                        'nome_api' => 'codMunic'
                                    ),
                                    'brasil_uf' => 'uf'
                                )
                            ),
                            'exterior' => array(
                                'properties' => array(
                                    'paisResid',
                                    'exterior_dscLograd' => 'dscLograd',
                                    'exterior_nrLograd' => 'nrLograd',
                                    'exterior_complemento' => 'complemento',
                                    'exterior_bairro' => 'bairro',
                                    'nmCid',
                                    'codPostal'
                                )
                            ),
                        ),
                    ),

                    'trabEstrangeiro' => array(
                        'properties' => array(
                            'dtChegada',
                            'classTrabEstrang' => array(
                                'type' => 'int'
                            ),
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
                            'infoCota',
                            'observacao'
                        )
                    ),

                    'aposentadoria' => array(
                        'properties' => array(
                            'trabAposent'
                        )
                    ),
                    'contato' => array(
                        'properties' => array(
                            'fonePrinc',
                            'foneAlternat',
                            'emailPrinc',
                            'emailAlternat',
                        )
                    ),

                    'dependente_1' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'label' => 'Dependentes',
                        'items' => array(
                            'properties' => array(
                                'tpDep_1' => 'tpDep',
                                'nmDep_1' => 'nmDep',
                                'dtNascto_1' => 'dtNascto',
                                'cpfDep_1' => 'cpfDep',
                                'depIRRF_1' => 'depIRRF',
                                'depSF_1' => 'depSF',
                                'incTrab_1' => 'incTrab'
                            )
                        )
                    ),

                    'dependente_2' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_2' => 'tpDep',
                                'nmDep_2' => 'nmDep',
                                'dtNascto_2' => 'dtNascto',
                                'cpfDep_2' => 'cpfDep',
                                'depIRRF_2' => 'depIRRF',
                                'depSF_2' => 'depSF',
                                'incTrab_2' => 'incTrab'
                            )
                        )
                    ),
                    'dependente_3' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_3' => 'tpDep',
                                'nmDep_3' => 'nmDep',
                                'dtNascto_3' => 'dtNascto',
                                'cpfDep_3' => 'cpfDep',
                                'depIRRF_3' => 'depIRRF',
                                'depSF_3' => 'depSF',
                                'incTrab_3' => 'incTrab'
                            )
                        )
                    ),
                    'dependente_4' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_4' => 'tpDep',
                                'nmDep_4' => 'nmDep',
                                'dtNascto_4' => 'dtNascto',
                                'cpfDep_4' => 'cpfDep',
                                'depIRRF_4' => 'depIRRF',
                                'depSF_4' => 'depSF',
                                'incTrab_4' => 'incTrab'
                            )
                        )
                    ),
                    'dependente_5' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_5' => 'tpDep',
                                'nmDep_5' => 'nmDep',
                                'dtNascto_5' => 'dtNascto',
                                'cpfDep_5' => 'cpfDep',
                                'depIRRF_5' => 'depIRRF',
                                'depSF_5' => 'depSF',
                                'incTrab_5' => 'incTrab'
                            )
                        )
                    ),
                    'dependente_6' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_6' => 'tpDep',
                                'nmDep_6' => 'nmDep',
                                'dtNascto_6' => 'dtNascto',
                                'cpfDep_6' => 'cpfDep',
                                'depIRRF_6' => 'depIRRF',
                                'depSF_6' => 'depSF',
                                'incTrab_6' => 'incTrab'
                            )
                        )
                    ),
                    'dependente_7' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_7' => 'tpDep',
                                'nmDep_7' => 'nmDep',
                                'dtNascto_7' => 'dtNascto',
                                'cpfDep_7' => 'cpfDep',
                                'depIRRF_7' => 'depIRRF',
                                'depSF_7' => 'depSF',
                                'incTrab_7' => 'incTrab'
                            )
                        )
                    ),
                    'dependente_8' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_8' => 'tpDep',
                                'nmDep_8' => 'nmDep',
                                'dtNascto_8' => 'dtNascto',
                                'cpfDep_8' => 'cpfDep',
                                'depIRRF_8' => 'depIRRF',
                                'depSF_8' => 'depSF',
                                'incTrab_8' => 'incTrab'
                            )
                        )
                    ),
                    'dependente_9' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_9' => 'tpDep',
                                'nmDep_9' => 'nmDep',
                                'dtNascto_9' => 'dtNascto',
                                'cpfDep_9' => 'cpfDep',
                                'depIRRF_9' => 'depIRRF',
                                'depSF_9' => 'depSF',
                                'incTrab_9' => 'incTrab'
                            )
                        )
                    ),
                    'dependente_10' => array (
                        'type' => 'array',
                        'nome_api' => 'dependente',
                        'items' => array(
                            'properties' => array(
                                'tpDep_10' => 'tpDep',
                                'nmDep_10' => 'nmDep',
                                'dtNascto_10' => 'dtNascto',
                                'cpfDep_10' => 'cpfDep',
                                'depIRRF_10' => 'depIRRF',
                                'depSF_10' => 'depSF',
                                'incTrab_10' => 'incTrab'
                            )
                        )
                    )
                )
            )
        )
    )
);

