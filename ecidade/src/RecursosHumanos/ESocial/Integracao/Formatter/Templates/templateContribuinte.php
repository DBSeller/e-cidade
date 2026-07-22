<?php
return array(
    'idePeriodo' => array(
        'properties' => array(
            'iniValid',
            'fimValid' => array(
                'nome_api' => 'fimvalid'
            )
        )
    ),
    'infoCadastro' => array(
        'nome_api' => 'infocadastro',
        'properties' => array(
            'classTrib' => array(
                'nome_api' => 'classtrib'
            ),
            'indEscrituracao' => array(
                'nome_api' => 'indescrituracao',
                'type' => 'int'
            ),
            'indDesoneracao' => array(
                'nome_api' => 'inddesoneracao',
                'type' => 'int'
            ),
            'indAcordoIsenMulta' => array(
                'nome_api' => 'indacordoisenmulta',
                'type' => 'int'
            ),
            'indSitPJ' => array(
                'nome_api' => 'indsitpj',
                'type' => 'int'
            )
        ),
        'groups' => array(
            'contato' => array(
                'properties' => array(
                    'nmCtt' => array(
                        'nome_api' => 'nmctt'
                    ),
                    'cpfCtt' => array(
                        'nome_api' => 'cpfctt'
                    ),
                    'foneFixo' => array(
                        'nome_api' => 'fonefixo'
                    ),
                    'foneCel' => array(
                        'nome_api' => 'fonecel'
                    ),
                    'email' => array(
                        'nome_api' => 'email'
                    )
                )
            )
        )
    ),
    'grupo-novaValidade' => array(
        'nome_api' => 'novavalidade',
        'properties' => array(
            'novainiValid' => array(
                'nome_api' => 'inivalid'
            ),
            'novafimValid' => array(
                'nome_api' => 'fimvalid'
            )
        )
    )
);
