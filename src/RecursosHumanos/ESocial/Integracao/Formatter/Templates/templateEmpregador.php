<?php
return array(
    'ideEstab' => array(
        'nome_api' => 'ideEstab',
        'properties' => array(
            'iniValid' => 'iniValid',
            'fimValid' => 'fimValid'
        )
    ),
    'idePeriodo' => array(
        'label' => 'Identificação do periodo',
        'properties' => array(
            'iniValid' => array(
                'type' => 'string',
                'label' => 'Data inicial',
            ),
            'fimValid' => array(
                'type' => 'string',
                'label' => 'Data final',
            ),
        ),
    ),
    'infoCadastro' => array(
        'properties' => array(
            'classTrib',
            'indCoop' => array(
                'type' => 'int'
            ),
            'indConstr' => array(
                'type' => 'int'
            ),
            'indDesFolha' => array(
                'type' => 'int'
            ),
            'indOpcCP' => array(
                'type' => 'int'
            ),
            'indPorte',
            'indOptRegEletron' => array(
                'type' => 'int'
            ),
            'cnpjEFR' => 'cnpjEFR',
            'iniValid1000',
            'fimValid1000'
        )
    ),
    'dadosIsencao' => array(
        'properties' => array(
            'ideMinLei' => 'ideMinLei',
            'nrCertif' => 'nrCertif',
            'dtEmisCertif' => 'dtEmisCertif',
            'dtVencCertif' => 'dtVencCertif',
            'nrProtRenov' => 'nrProtRenov',
            'dtProtRenov' => 'dtProtRenov',
            'dtDou' => 'dtDou',
            'pagDou' => array(
                'nome_api' => 'pagDou',
                'type' => 'int'
            )
        )
    ),
    'infoOrgInternacional' => array(
        'properties' => array(
            'indAcordoIsenMulta' => array(
                'nome_api' => 'indAcordoIsenMulta',
                'type' => 'int'
            )
        )
    )
);
