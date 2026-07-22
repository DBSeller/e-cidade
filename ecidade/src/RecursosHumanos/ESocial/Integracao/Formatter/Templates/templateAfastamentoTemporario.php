<?php
return array(
    'ideVinculo' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab' => array(
                'required' => false,
            ),
            'matricula' => array(
                'required' => false,
            ),
            'codCateg' => array(
                'required' => false,
            )
        )
    ),
    'infoAfastamento' => array(
        'groups' => array(
            'iniAfastamento' => array(
                'properties' => array(
                    'dtIniAfast',
                    'codMotAfast',
                    'infoMesmoMtv' => array(
                        'required' => false,
                    ),
                    'tpAcidTransito' => array(
                        'required' => false,
                    ),
                    'observacao' => array(
                        'required' => false,
                    ),
                ),
                'groups' => array(
                    'perAquis' => array(
                        'required' => false,
                        'properties' => array(
                            'dtInicio' => array(
                                'required' => true,
                            ),
                            'dtFim' => array(
                                'required' => false
                            )
                        )
                    ),
                    'infoCessao' => array(
                        'required' => false,
                        'properties' => array(
                            'cnpjCess',
                            'infOnus' => array(
                                'type' => 'int'
                            )
                        )
                    ),
                    'infoMandSind' => array(
                        'required' => false,
                        'properties' => array(
                            'cnpjSind',
                            'infOnusRemun' => array(
                                'type' => 'int'
                            )
                        )
                    )
                )
            ),
            'infoRetif' => array(
                'required' => false,
                'properties' => array(
                    'origRetif' => array(
                        'type' => 'int'
                    ),
                    'tpProc' => array(
                        'required' => false,
                        'type' => 'int'
                    ),
                    'nrProc' => array(
                        'required' => false,
                    )
                )
            ),
            'fimAfastamento' => array(
                'required' => false,
                'properties' => array(
                    'dtTermAfast'
                )
            )
        )
    )
);
