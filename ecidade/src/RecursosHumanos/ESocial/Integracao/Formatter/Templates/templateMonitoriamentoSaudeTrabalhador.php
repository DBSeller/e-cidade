<?php
return array(
    'ideVinculo' => array(
        'required' => true,
        'properties' => array(
            'cpfTrab' => array(
                'required' => true,
                'label' => 'Código pessoa física do trabalhador',
                'type' => 'string',
            ),
            'matricula' => array(
                'required' => false,
                'label' => 'Matrícula atribuída ao trabalhador',
                'type' => 'string',
            ),
            'codCateg' => array(
                'required' => false,
                'label' => 'Categoria do trabalhador',
                'type' => 'int'
            ),
        )
    ),
    'exMedOcup' => array(
        'required' => true,
        'label' => 'Informações do exame médico ocupacional.',
        'properties' => array(
            'tpExameOcup' => array(
                'required' => true,
                'label' => 'Tipo do exame médico ocupacional.',
                'type' => 'int',
            )
        ),
        'groups' => array(
            'aso' => array(
                'required' => true,
                'label' => 'Detalhamento das informações do Atestado de Saúde Ocupacional - ASO.',
                'properties' => array(
                    'dtAso' => array(
                        'required' => true,
                        'label' => 'Data de emissão do ASO.',
                        'type' => 'string'
                    ),
                    'resAso' => array(
                        'required' => false,
                        'label' => 'Resultado do ASO.',
                        'type' => 'int'
                    )
                ),
                'groups' => array(
                    'exame' => array(
                        'required' => true,
                        'label' => 'Grupo que detalha as avaliações clínicas e os exames complementares porventura 
                            realizados pelo trabalhador. ',
                        'properties' => array(
                            'dtExm' => array(
                                'required' => true,
                                'label' => 'Data do exame realizado.',
                                'type' => 'string'
                            ),
                            'procRealizado' => array(
                                'required' => true,
                                'label' => 'Código do procedimento diagnóstico.',
                                'type' => 'int'
                            ),
                            'obsProc' => array(
                                'required' => false,
                                'label' => 'Observação sobre o procedimento diagnóstico realizado.',
                                'type' => 'string'
                            ),
                            'ordExame' => array(
                                'required' => false,
                                'label' => 'Ordem do exame.',
                                'type' => 'int'
                            ),
                            'indResult' => array(
                                'required' => false,
                                'label' => 'Indicação dos resultados.',
                                'type' => 'int'
                            )
                        )
                    ),
                    'medico' => array(
                        'required' => true,
                        'label' => 'Informações sobre o médico emitente do ASO.',
                        'properties' => array(
                            'nmMed' => array(
                                'required' => true,
                                'label' => 'Preencher com o nome do médico emitente do ASO.',
                                'type' => 'string'
                            ),
                            'nrCRM' => array(
                                'required' => true,
                                'label' => 'Número de inscrição do médico emitente do ASO no Conselho Regional de 
                                    Medicina - CRM.',
                                'type' => 'string'
                            ),
                            'ufCRM' => array(
                                'required' => true,
                                'label' => 'Preencher com a sigla da Unidade da Federação - UF de expedição do CRM.',
                                'type' => 'string'
                            )
                        )
                    )
                )
            ),
            'respMonit' => array(
                'required' => false,
                'label' => 'Informações sobre o médico responsável/coordenador do PCMSO.',
                'properties' => array(
                    'cpfResp' => array(
                        'required' => false,
                        'label' => 'Preencher com o CPF do médico responsável/coordenador do PCMSO.',
                        'type' => 'string'
                    ),
                    'nmResp' => array(
                        'required' => true,
                        'label' => 'Preencher com o nome do médico responsável/coordenador do PCMSO.',
                        'type' => 'string'
                    ),
                    'nrCRM' => array(
                        'required' => true,
                        'label' => 'Número de inscrição do médico responsável/coordenador do PCMSO no CRM.',
                        'type' => 'string'
                    ),
                    'ufCRM' => array(
                        'required' => true,
                        'label' => 'Preencher com a sigla da UF de expedição do CRM..',
                        'type' => 'string'
                    )

                )
            )
        )
    )
);
