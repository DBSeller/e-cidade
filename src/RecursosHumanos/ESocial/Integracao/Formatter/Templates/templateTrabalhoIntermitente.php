<?php
return array(
    'ideVinculo' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab',
            'matricula'
        )
    ),
    'infoConvInterm' => array(
        'properties' => array(
            'codConv',
            'dtInicio',
            'dtFim',
            'dtPrevPgto'
        ),
        'groups' => array(
            'jornada' => array(
                'properties' => array(
                    'codHorContrat',
                    'dscJornada'
                )
            ),
            'localTrab' => array(
                'properties' => array(
                    'indLocal' => array(
                        'type' => 'int'
                    )
                ),
                'groups' => array(
                    'localTrabInterm' => array(
                        'properties' => array(
                            'tpLograd',
                            'dscLograd',
                            'nrLograd',
                            'complem',
                            'bairro',
                            'cep',
                            'codMunic' => array(
                                'type' => 'int'
                            ),
                            'uf'
                        )
                    )
                )
            )
        )
    )
);
