<?php
return array(
    'infoRegPrelim' => array(
        'nome_api' => 'infoRegPrelim',
        'properties' => array(
            'cpfTrab',
            'dtNascto',
            'dtAdm',
            'matricula',
            'codCateg' => array(
                'type' => 'int'
            ),
            'natAtividade' => array(
                'type' => 'int'
            )
        ),
        'groups' => array(
            'infoRegCTPS' => array(
                'type' => 'array',
                'items' => array(
                    'properties' => array(
                        'CBOCargo',
                        'vrSalFx' => array(
                            'type' => 'float'
                        ),
                        'undSalFixo' => array(
                            'type' => 'int'
                        ),
                        'tpContr' => array(
                            'type' => 'int'
                        ),
                        'dtTerm'
                    )
                )
            )
        )
    )
);
