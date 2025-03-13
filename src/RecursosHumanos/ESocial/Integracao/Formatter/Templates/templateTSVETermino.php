<?php
return array(
    'ideTrabSemVinculo' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab',
            'termino_ideTrabSemVinculo_codCateg' =>'codCateg'
        )
    ),
    'infoTSVTermino' => array(
        'properties' => array(
            'dtTerm',
            'mtvDesligTSV',
            'pensAlim',
            'percAliment',
            'vrAlim',
        ),
        'groups' => array(
            'verbasResc' => array(
                'groups'=> array(
                    'dmDev' => array(
                        'groups' => array(
                            'ideEstabLot' => array(
                                'properties' => array(
                                    'termino_lotacao_tpInsc' => 'tpInsc',
                                    'nrInsc',
                                    'codLotacao'
                                ),
                                'groups' => array(
                                    'detVerbas' => array(
                                        'type' => 'array',
                                        'items' => array(
                                            'properties' => array(
                                                'codRubr',
                                                'ideTabRubr',
                                                'qtdRubr' => array(
                                                    'type' => 'float'
                                                ),
                                                'fatorRubr',
                                                'vrUnit' => array(
                                                    'type' => 'float'
                                                ),
                                                'vrRubr' => array(
                                                    'type' => 'float'
                                                )
                                            )
                                        )
                                    ),
                                    'infoSaudeColet' => array(
                                        'groups' => array(
                                            'detOper' => array(
                                                'type' => 'array',
                                                'items' => array(
                                                    'properties' => array(
                                                        'cnpjOper',
                                                        'regANS',
                                                        'vrPgTit' => array(
                                                            'type' => 'float'
                                                        )
                                                    ),
                                                    'groups' => array(
                                                        'detPlano' => array(
                                                            'type' => 'array',
                                                            'items' => array(
                                                                'properties' => array(
                                                                    'tpDep',
                                                                    'cpfDep',
                                                                    'nmDep',
                                                                    'dtNascto',
                                                                    'vlrPgDep' => array(
                                                                        'type' => 'float'
                                                                    )
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    ),
                                    'infoAgNocivo' => array(
                                        'properties' => array(
                                            'grauExp' => array(
                                                'type' => 'int'
                                            )
                                        )
                                    ),
                                    'infoSimples' => array(
                                        'properties' => array(
                                            'termino_indSimples' => 'indSimples'
                                        )
                                    )
                                )
                            )
                        )
                    ),
                    'procJudTrab' => array(
                        'type' => 'array',
                        'items' => array(
                            'properties' => array(
                                'tpTrib' => array(
                                    'type' => 'int'
                                ),
                                'nrProcJud',
                                'codSusp' => array(
                                    'type' => 'int'
                                )
                            )
                        )
                    ),
                    'infoMV' => array(
                        'properties' => array(
                            'indMV'
                        ),
                        'groups' => array(
                            'remunOutrEmpr' => array (
                                'type' => 'array',
                                'items' => array(
                                    'properties' => array(
                                        'tpInsc' => array(
                                            'type' => 'int'
                                        ),
                                        'nrInsc',
                                        'codCateg' => array(
                                            'type' => 'int'
                                        ),
                                        'vlrRemunOE' => array(
                                            'type' => 'float'
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'quarentena' => array(
                'properties' => array(
                    'dtFimQuar'
                )
            ),
            'mudancaCPF' => array(
                'properties' => array(
                    'novoCPF'
                )
            )
        )
    ),
    'termino_rubricas' => array(
        'properties' => array(
            'desligamento_rubricas_json'
        )
    )
);
