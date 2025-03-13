<?php
/**
 * Template para o arquivo S-2306
 * Alteração de Contrato de Trabalhador Sem Vinculo Empregaticio
 */
return array(

    'ideTrabSemVinculo' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab',
            'codCateg'
        )
    ),
    'infoTSVAlteracao' => array(
        'properties' => array(
            'dtAlteracao',
            'natAtividade'
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
                            'vrSalFx',
                            'undSalFixo',
                            'dscSalVar'
                        )
                    ),
                    'infoEstagiario' => array(
                        'properties' => array(
                            'natEstagio',
                            'nivEstagio',
                            'areaAtuacao',
                            'nrApol',
                            'vlrBolsa',
                            'dtPrevTerm'
                        ),
                        'groups' => array(
                            'instEnsino' => array(
                                'instEnsino_cnpjInstEnsino' => 'cnpjInstEnsino',
                                'instEnsino_nmRazao' => 'nmRazao',
                                'instEnsino_dscLograd' => 'dscLograd',
                                'instEnsino_nrLograd' => 'nrLograd',
                                'instEnsino_bairro' => 'bairro',
                                'instEnsino_cep' => 'cep',
                                'instEnsino_codMunic' => 'codMunic',
                                'instEnsino_uf' => 'uf'
                            ),
                            'ageIntegracao' => array(
                                'ageIntegracao_cnpjAgntInteg' => 'cnpjAgntInteg',
                                'ageIntegracao_nmRazao' => 'nmRazao',
                                'ageIntegracao_dscLograd' => 'dscLograd',
                                'ageIntegracao_nrLograd' => 'nrLograd',
                                'ageIntegracao_bairro' => 'bairro',
                                'ageIntegracao_cep' => 'cep',
                                'ageIntegracao_codMunic' => 'codMunic',
                                'ageIntegracao_uf' => 'uf'
                            ),
                            'supervisorEstagio' => array(
                                'cpfSupervisor',
                                'nmSuperv'
                            )
                        )
                    )
                )
            )
        )
    )
);