<?php
return array(
    'ideVinculo' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab',
            'matricula'
        )
    ),
    'altContratual' => array(
        'properties' => array(
            'dtAlteracao',
            'dtEf',
            'dscAlt'
        ),
        'groups' => array(
            'vinculo' => array(
                'properties' => array(
                    'tpRegPrev' => array(
                        'type' => 'integer',
                    )
                )
            ),
            'infoRegimeTrab' => array(
                'groups' => array(
                    'infoCeletista' => array(
                        'properties' => array(
                            'tpRegJor' => array(
                                'type' => 'integer',
                            ),
                            'natAtividade' => array(
                                'type' => 'integer',
                            ),
                            'dtBase' => array(
                                'type' => 'integer',
                            ),
                            'cnpjSindCategProf'
                        ),
                        'groups' => array(
                            'trabTemp' => array(

                                'properties' => array(
                                    'justProrr'
                                )
                            ),
                            'aprend' => array(
                                'properties' => array(
                                    'aprend_tpInsc' => array(
                                        'nome_api' => 'tpInsc',
                                        'type' => 'integer',
                                    ),
                                    'aprend_nrInsc' => 'nrInsc'
                                )
                            )
                        )
                    ),
                    'infoEstatutario' => array(
                        'properties' => array(
                            'tpPlanRP' => array(
                                'type' => 'integer',
                            )
                        )
                    )
                )
            ),
            'infoContrato' => array(
                'properties' => array(
                    'codCargo',
                    'codFuncao',
                    'codCateg' => array(
                        'type' => 'integer',
                    ),
                    'codCarreira',
                    'dtIngrCarr'
                ),
                'groups' => array(
                    'remuneracao' => array(
                        'properties' => array(
                            'vrSalFx' => array(
                                'type' => 'float'
                            ),
                            'undSalFixo' => array(
                                'type' => 'integer',
                            ),
                            'dscSalVar'
                        )
                    ),
                    'duracao' => array(
                        'properties' => array(
                            'tpContr' => array(
                                'type' => 'integer',
                            ),
                            'dtTerm',
                            'objDet'
                        )
                    ),
                    'localTrabalho' => array(
                        'groups' => array(
                            'localTrabGeral' => array(
                                'properties' => array(
                                    'localTrabGeral_tpInsc' => array(
                                        'nome_api' => 'tpInsc',
                                        'type' => 'integer',
                                    ),
                                    'localTrabGeral_nrInsc' => 'nrInsc',
                                    'localTrabGeral_descComp' => 'descComp'
                                )
                            ),
                            'localTrabDom' => array(
                                'properties' => array(
                                    'localTrabDom_tpLograd' => 'tpLograd',
                                    'localTrabDom_dscLograd' => 'dscLograd',
                                    'localTrabDom_nrLograd' => 'nrLograd',
                                    'localTrabDom_complemento' => 'complemento',
                                    'localTrabDom_bairro' => 'bairro',
                                    'localTrabDom_cep' => 'cep',
                                    'localTrabDom_codMunic' => array(
                                        'nome_api' => 'codMunic',
                                        'type' => 'integer',
                                    ),
                                    'localTrabDom_uf' => 'uf'
                                )
                            )
                        )
                    ),
                    'horContratual' => array(
                        'properties' => array(
                            'qtdHrsSem' => array(
                                'type' => 'float'
                            ),
                            'tpJornada' => array(
                                'type' => 'integer',
                            ),
                            'dscTpJorn',
                            'tmpParc' => array(
                                'type' => 'integer',
                            )
                        ),
                        'groups' => array(
                            'horario_semana_1' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'items' => array(
                                    'properties' => array(
                                        'horario_semana_1_codHorContrat_8' => 'codHorContrat_8',
                                        'horario_semana_1_codHorContrat_7' => 'codHorContrat_7',
                                        'horario_semana_1_codHorContrat_6' => 'codHorContrat_6',
                                        'horario_semana_1_codHorContrat_5' => 'codHorContrat_5',
                                        'horario_semana_1_codHorContrat_4' => 'codHorContrat_4',
                                        'horario_semana_1_codHorContrat_3' => 'codHorContrat_3',
                                        'horario_semana_1_codHorContrat_2' => 'codHorContrat_2',
                                        'horario_semana_1_codHorContrat_1' => 'codHorContrat_1'
                                    )
                                )
                            ),
                            'horario_semana_2' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'items' => array(
                                    'properties' => array(
                                        'horario_semana_2_codHorContrat_8' => 'codHorContrat_8',
                                        'horario_semana_2_codHorContrat_7' => 'codHorContrat_7',
                                        'horario_semana_2_codHorContrat_6' => 'codHorContrat_6',
                                        'horario_semana_2_codHorContrat_5' => 'codHorContrat_5',
                                        'horario_semana_2_codHorContrat_4' => 'codHorContrat_4',
                                        'horario_semana_2_codHorContrat_3' => 'codHorContrat_3',
                                        'horario_semana_2_codHorContrat_2' => 'codHorContrat_2',
                                        'horario_semana_2_codHorContrat_1' => 'codHorContrat_1'
                                    )
                                )
                            ),
                            'horario_semana_3' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'items' => array(
                                    'properties' => array(
                                        'horario_semana_3_codHorContrat_8' => 'codHorContrat_8',
                                        'horario_semana_3_codHorContrat_7' => 'codHorContrat_7',
                                        'horario_semana_3_codHorContrat_6' => 'codHorContrat_6',
                                        'horario_semana_3_codHorContrat_5' => 'codHorContrat_5',
                                        'horario_semana_3_codHorContrat_4' => 'codHorContrat_4',
                                        'horario_semana_3_codHorContrat_3' => 'codHorContrat_3',
                                        'horario_semana_3_codHorContrat_2' => 'codHorContrat_2',
                                        'horario_semana_3_codHorContrat_1' => 'codHorContrat_1'
                                    )
                                )
                            ),
                            'horario_semana_4' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'items' => array(
                                    'properties' => array(
                                        'horario_semana_4_codHorContrat_8' => 'codHorContrat_8',
                                        'horario_semana_4_codHorContrat_7' => 'codHorContrat_7',
                                        'horario_semana_4_codHorContrat_6' => 'codHorContrat_6',
                                        'horario_semana_4_codHorContrat_5' => 'codHorContrat_5',
                                        'horario_semana_4_codHorContrat_4' => 'codHorContrat_4',
                                        'horario_semana_4_codHorContrat_3' => 'codHorContrat_3',
                                        'horario_semana_4_codHorContrat_2' => 'codHorContrat_2',
                                        'horario_semana_4_codHorContrat_1' => 'codHorContrat_1'
                                    )
                                )
                            )
                        )
                    ),
                    'filiacaoSindical_1' => array(
                        'type' => 'array',
                        'nome_api' => 'filiacaoSindical',
                        'items' => array(
                            'properties' => array(
                                'filiacaoSindical_1_cnpjSindTrab' => 'cnpjSindTrab'
                            )
                        )
                    ),
                    'filiacaoSindical_2' => array(
                        'type' => 'array',
                        'nome_api' => 'filiacaoSindical',
                        'items' => array(
                            'properties' => array(
                                'filiacaoSindical_2_cnpjSindTrab' => 'cnpjSindTrab'
                            )
                        )
                    ),
                    'alvaraJudicial' => array(
                        'properties' => array(
                            'nrProcJud'
                        )
                    ),
                    'observacoes' => array(
                        'type' => 'array',
                        'nome_api' => 'observacoes',
                        'items' => array(
                            'properties' => array(
                                'observacao'
                            )
                        )
                    ),
                    'servPubl' => array(
                        'properties' => array(
                            'mtvAlter' => array(
                                'type' => 'integer',
                            )
                        )
                    )
                )
            )
        )
    )
);
