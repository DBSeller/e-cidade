<?php
return array(
    'ideProcesso' => array(
        'properties' => array(
            'tpProc'=> array(
                'type' => 'int'
            ),
            'nrProc',
            'iniValid',
            'fimValid'
        )
    ),
    'dadosProc' => array(
        'properties' => array(
            'indAutoria'=> array(
                'type' => 'int'
            ),
            'indMatProc'=> array(
                'type' => 'int'
            ),
            'observacao'
        ),
        'groups' => array(
            'dadosProcJud' => array(
                'properties' => array(
                    'ufVara',
                    'codMunic',
                    'idVara'
                )
            ),
            'infoSusp_1' => array(
                'nome_api' => 'infoSusp',
                'type' => 'array',
                'items' => array(
                    'properties' => array(
                        'codSusp_1'=> 'codSusp',
                        'indSusp_1' => 'indSusp',
                        'dtDecisao_1' => 'dtDecisao',
                        'indDeposito_1' =>'indDeposito'
                    )
                )
            ),
            'infoSusp_2' => array(
                'nome_api' => 'infoSusp',
                'type' => 'array',
                'items' => array(
                    'properties' => array(
                        'codSusp_2'=> 'codSusp',
                        'indSusp_2' => 'indSusp',
                        'dtDecisao_2' => 'dtDecisao',
                        'indDeposito_2' =>'indDeposito'
                    )
                )
            ),
            'infoSusp_3' => array(
                'nome_api' => 'infoSusp',
                'type' => 'array',
                'items' => array(
                    'properties' => array(
                        'codSusp_3'=> 'codSusp',
                        'indSusp_3' => 'indSusp',
                        'dtDecisao_3' => 'dtDecisao',
                        'indDeposito_3' =>'indDeposito'
                    )
                )
            ),
            'infoSusp_4' => array(
                'nome_api' => 'infoSusp',
                'type' => 'array',
                'items' => array(
                    'properties' => array(
                        'codSusp_4'=> 'codSusp',
                        'indSusp_4' => 'indSusp',
                        'dtDecisao_4' => 'dtDecisao',
                        'indDeposito_4' =>'indDeposito'
                    )
                )
            ),
            'infoSusp_5' => array(
                'nome_api' => 'infoSusp',
                'type' => 'array',
                'items' => array(
                    'properties' => array(
                        'codSusp_5'=> 'codSusp',
                        'indSusp_5' => 'indSusp',
                        'dtDecisao_5' => 'dtDecisao',
                        'indDeposito_5' =>'indDeposito'
                    )
                )
            )
        )
    )
);
