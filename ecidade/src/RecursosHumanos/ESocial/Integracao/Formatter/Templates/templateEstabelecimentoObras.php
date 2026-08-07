<?php
return array(
    'ideEstab' => array(
        'nome_api' => 'ideEstab',
        'properties' => array(
            'tpInsc' => array(
                'nome_api'=> 'tpInsc',
                'type' => 'int'
            ),
            'nrInsc' => array(
                'nome_api'=> 'nrInsc',
                'type' => 'string'
            ),
            'iniValid1005' => 'iniValid',
            'fimValid1005' => 'fimValid'
        )
    ),
    'dadosEstab' => array(
        'nome_api' => 'dadosEstab',
        'properties' => array(
            'cnaePrep' => array(
              'type' => 'string'
            )
        ),
        'groups' => array (
            'aliqGilrat' => array(
                'nome_api' => 'aliqGilrat',
                'properties' => array(
                    'aliqRat' => array(
                        'nome_api'=>'aliqRat',
                        'type' => 'integer'
                    ),
                    'fap' => array(
                        'nome_api'=>'fap',
                        'type' => 'string'
                    )
                ),
                'groups' =>array (
                    'procAdmJudRat' => array (
                        'properties' => array(
                            'tpProc' => array(
                                'nome_api'=> 'tpProc',
                                'type' => 'int'
                            ),
                            'nrProc' => 'nrProc',
                            'codSusp'
                        )
                    ),
                    'procAdmJudFap' => array (
                        'properties' => array(
                            'tpProc' => array(
                                'nome_api'=> 'tpProc',
                                'type' => 'int'
                            ),
                            'nrProc' => 'nrProc',
                            'codSusp'
                        )
                    )
                )
            ),
            'infoCaepf' => array (
                'nome_api' => 'infoCaepf',
                'properties' => array(
                    'tpCaepf' => array(
                        'nome_api'=> 'tpCaepf',
                        'type' => 'int'
                    ),
                )
            ),
            'infoObra' => array (
                'nome_api' => 'infoObra',
                'properties' => array(
                    'indSubstPatrObra' => array(
                        'nome_api'=>  'indSubstPatrObra',
                        'type' => 'int'
                    )
                )
            ),
            'infoTrab' => array (
                'nome_api' => 'infoTrab',
                'groups' => array(
                    'infoApr' => array (
                        'nome_api' => 'infoApr',
                        'groups' => array(
                            'infoEntEduc' => array (
                                'type' => 'array',
                                'label' => 'Identificação da(s) entidade(s) educativa(s) ou de prática desportiva',
                                'nome_api' => 'infoEntEduc',
                                'items' => array(
                                    'properties' => array(
                                        'nrInsc'
                                    )
                                )
                            ),
                        )
                    ),
                    'infoPCD' => array (
                        'nome_api' => 'infoPCD',
                        'properties' => array(
                            'nrProcJud' => 'nrProcJud'
                        )
                    )
                )
            ),
        )
    ),
);
