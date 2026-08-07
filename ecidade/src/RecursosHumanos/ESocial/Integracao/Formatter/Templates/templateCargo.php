<?php
return array(
    'ideCargo' => array(
        'properties' => array(
            'codCargo',
            'iniValid',
            'fimValid'
        )
    ),
    'dadosCargo' => array(
        'properties' => array(
            'nmCargo' => array(
                'type' => 'string',
                'minLength' => 8,
                'maxLength' => 100,
            ),
            'codCBO' => array(
                'type' => 'string',
                'minLength' => 6,
                'maxLength' => 6,
                'pattern' => '^[0-9]',
            ),
        ),
        'groups' => array(
            'cargoPublico' => array(
                'properties' => array(
                    'acumCargo' => array(
                        'required' => true,
                        'type' => 'integer',
                        'minimum' => 1,
                        'maximum' => 4,
                    ),
                    'contagemEsp' => array(
                        'required' => true,
                        'type' => 'integer',
                        'minimum' => 1,
                        'maximum' => 4,
                    ),
                    'dedicExcl' => array(
                        'required' => true,
                        'type' => 'string',
                        'pattern' => 'S|N',
                    ),

                ),
                'groups' => array(
                    'leiCargo' => array(
                        'required' => true,
                        'properties' => array(
                            'nrLei' => array(
                                'required' => true,
                                'type' => 'string',
                                'minLength' => 3,
                                'maxLength' => 12,
                            ),
                            'dtLei' => array(
                                'required' => true,
                                'type' => 'string',
                                'pattern' => '^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$',
                            ),
                            'sitCargo' => array(
                                'required' => true,
                                'type' => 'integer',
                                'minimum' => 1,
                                'maximum' => 3,
                            )
                        )
                    )
                )
            )
        )
    )
);

