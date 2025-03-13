<?php
return array(
    'ideTrabalhador' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab'
        ),
        'groups' => array(
            'infoMV' => array(
                'properties' => array(
                    'indMV' => array(
                        'type' => 'int'
                    )
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
            ),
            'infoComplem' => array(
                'properties' => array(
                    'nmTrab',
                    'dtNascto'
                ),
                'groups' => array(
                    'sucessaoVinc' => array(
                        'properties' => array(
                            'nrInsc',
                            'matricAnt',
                            'dtAdm',
                            'observacao'
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
                        'codSusp'
                    )
                )
            ),
            'infoInterm' => array(
                'properties' => array(
                    'dia' => array(
                        'type' => 'int'
                    )
                )
            )
        )
    ),
    'dmDev' => array(
        'properties' => array(
            'ideDmDev',
            'codCateg' => array(
                'type' => 'int'
            )
        ),
        'groups' => array(
            'infoPerApur' => array(
                'groups'=> array(
                    'ideEstabLot' => array(
                        'type' => 'array',
                        'items' => array(
                            'properties' => array(
                                'tpInsc' => array(
                                    'type' => 'int'
                                ),
                                'nrInsc',
                                'codLotacao',
                                'qtdDiasAv' => array(
                                    'type' => 'int'
                                )
                            ),
                            'groups' => array(
                                'remunPerApur' => array(
                                    'type' => 'array',
                                    'items' => array(
                                        'properties' => array(
                                            'matricula',
                                            'indSimples' => array(
                                                'type' => 'int'
                                            )
                                        ),
                                        'groups' => array(
                                            'itensRemun' => array(
                                                'type' => 'array',
                                                'items' => array(
                                                    'properties' => array(
                                                        'codRubr',
                                                        'ideTabRubr',
                                                        'qtdRubr' => array(
                                                            'type' => 'float'
                                                        ),
                                                        'fatorRubr' => array(
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
                                            'infoTrabInterm' => array(
                                                'type' => 'array',
                                                'items' => array(
                                                    'properties' => array(
                                                        'codConv'
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'infoPerAnt' => array(
                'groups' => array(
                    'ideADC' => array(
                        'type' => 'array',
                        'items' => array(
                            'properties' => array(
                                'dtAcConv',
                                'tpAcConv',
                                'compAcConv',
                                'dtEfAcConv',
                                'dsc',
                                'remunSuc'
                            ),
                            'groups' => array(
                                'idePeriodo' => array(
                                    'type' => 'array',
                                    'items' => array(
                                        'properties' => array(
                                            'perRef',
                                        ),
                                        'groups' => array(
                                            'ideEstabLot' => array(
                                                'type' => 'array',
                                                'items' => array(
                                                    'properties' => array(
                                                        'tpInsc' => array(
                                                            'type' => 'int'
                                                        ),
                                                        'nrInsc',
                                                        'codLotacao'
                                                    ),
                                                    'groups' => array(
                                                        'remunPerAnt' => array(
                                                            'type' => 'array',
                                                            'items' => array(
                                                                'properties' => array(
                                                                    'matricula',
                                                                    'indSimples' => array(
                                                                        'type' => 'int'
                                                                    ),
                                                                ),
                                                                'groups' => array(
                                                                    'itensRemun' => array(
                                                                        'type' => 'array',
                                                                        'items' => array(
                                                                            'properties' => array(
                                                                                'codRubr',
                                                                                'ideTabRubr',
                                                                                'qtdRubr' => array(
                                                                                    'type' => 'float'
                                                                                ),
                                                                                'fatorRubr' => array(
                                                                                    'type' => 'float'
                                                                                ),
                                                                                'vrRubr' => array(
                                                                                    'type' => 'float'
                                                                                ),
                                                                                'indApurIR' => array(
                                                                                    'type' => 'int'
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
                                                                    'infoTrabInterm'  => array(
                                                                        'type' => 'array',
                                                                        'items' => array(
                                                                            'properties' => array(
                                                                                'codConv'
                                                                            )
                                                                        )
                                                                    )
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'infoComplCont' => array(
                'properties' => array(
                    'codCBO',
                    'natAtividade' => array(
                        'type' => 'int'
                    ),
                    'qtdDiasTrab' => array(
                        'type' => 'int'
                    )
                )
            )
        )
    )
);
