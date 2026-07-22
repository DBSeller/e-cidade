<?php
return array(
    'infoExclusao' => array(
        'properties' => array(
            'tpEvento' => array(
                'type' => 'string',
                'maxLength' => 6,
                'minLength' => 5
            ),
            'nrRecEvt' =>array(
                'type' => 'string',
                'maxLength' => 40,
                'minLength' => 1
            )
        ),
        'groups' => array(
            'ideTrabalhador' => array(
                'properties' => array(
                    'cpfTrab' => array(
                        'type' => 'string',
                        'maxLength' => 11,
                        'minLength' => 11
                    )
                )
            ),
            'ideFolhaPagto' => array(
                'properties' => array(
                    'indApuracao' => array(
                        'type' => 'int'
                    ),
                    'perApur' => array(
                        'type' => 'string'
                    )
                )
            )
        )
    ),
);
