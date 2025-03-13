<?php
return array(
    'ideHorContratual' => array(
        'properties' => array(
            'codHorContrat' => array(
                'required' => true,
                'type' => 'string',
                'maxLength' => 30,
                'pattern' => '^(?!eSocial)',
            ),
            'iniValid' => array(
                'required' => true,
                'type' => 'string',
                'pattern' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])$',
            ),
            'fimValid' => array(
                'required' => false,
                'type' => array(
                    0 => 'string',
                    1 => 'null',
                ),
                'pattern' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])$',
            ),

        )
    ),
    'dadosHorContratual' => array(
        'required' => true,
        'type' => 'object',
        'properties' => array(
            'hrEntr' =>
                array(
                    'required' => true,
                    'type' => 'string',
                    'pattern' => '^(?:2[0-3]|[0-1]?[0-9])[0-5]?[0-9]$',
                ),
            'hrSaida' =>
                array(
                    'required' => true,
                    'type' => 'string',
                    'pattern' => '^(?:2[0-3]|[0-1]?[0-9])[0-5]?[0-9]$',
                ),
            'durJornada' =>
                array(
                    'required' => true,
                    'type' => 'integer',
                    'minimum' => 1,
                    'maximum' => 9999,
                ),
            'perHorFlexivel' => array(
                'required' => true,
                'type' => 'string',
                'pattern' => 'S|N',
            )
        ),
        'groups' => array(
            'horarioIntervalo' => array(
                'required' => false,
                'type' => 'array',
                'minItems' => 0,
                'maxItems' => 99,
                'items' => array(
                    'type' => 'object',
                    'properties' => array(
                        'tpInterv' => array(
                            'required' => true,
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 2,
                        ),
                        'durInterv' => array(
                            'required' => true,
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 999,
                        ),
                        'iniInterv' => array(
                            'required' => false,
                            'type' => 'string',
                            'pattern' => '^[0-2][0-3][0-5][0-9]$',
                        ),
                        'termInterv' => array(
                            'required' => false,
                            'type' => 'string',
                            'pattern' => '^[0-2][0-3][0-5][0-9]$',
                        )
                    )
                )
            )
        )
    )
);
