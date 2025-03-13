<?php
/* S-2306 - Trabalhador Sem Vínculo de Emprego/Estatutário - Alteração */
return array(
    'ideTrabSemVinculo' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab',
            'codCateg' => array(
              'type' => 'int'
            )
        )
    ),
    'infoTSVAlteracao' => array(
        'properties' => array(
            'dtAlteracao',
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
            )
        )
    )
);
