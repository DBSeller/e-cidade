<?php
return array(
    'trabalhador' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab',
            'nmTrab',
            'sexo',
            'racaCor' => array(
                'type' => 'int'
            ),
            'estCiv' => array(
                'type' => 'int'
            ),
            'grauInstr',
            'indPriEmpr',
            'nmSoc',
        ),
        'groups' => array(
            'nascimento' => array(
                'properties' => array(
                    'dtNascto',
                    'codMunic',
                    'uf',
                    'paisNascto',
                    'paisNac',
                    'nmMae',
                    'nmPai'
                )
            ),

            'documentos' => array(
                'groups' => array(
                    'CTPS' => array(
                        'properties' => array(
                            'nrCtps',
                            'serieCtps',
                            'ufCtps'
                        )
                    ),
                    'RIC' => array(
                        'properties' => array(
                            'nrRic',
                            'orgaoEmissor',
                            'dtExped'
                        )
                    ),
                    'RG' => array(
                        'properties' => array(
                            'nrRg',
                            'orgaoEmissor',
                            'dtExped'
                        )
                    ),
                    'RNE' => array(
                        'properties' => array(
                            'nrRne',
                            'orgaoEmissor',
                            'dtExped',
                        )
                    ),
                    'OC' => array(
                        'properties' => array(
                            'nrOc',
                            'orgaoEmissor',
                            'dtExped',
                            'dtValid',
                        )
                    ),
                    'CNH' => array(
                        'properties' => array(
                            'nrRegCnh',
                            'dtExped',
                            'ufCnh',
                            'dtValid',
                            'dtPriHab',
                            'categoriaCnh',
                        )
                    ),
                ),
            ),

            'endereco' => array(
                'label' => 'Endereços',
                'groups' => array(
                    'brasil' => array(
                        'properties' => array(
                            'tpLograd',
                            'dscLograd',
                            'nrLograd',
                            'complemento',
                            'bairro',
                            'cep',
                            'codMunic' => array(
                                'type' => 'int'
                            ),
                            'uf'
                        )
                    ),
                    'exterior' => array(
                        'properties' => array(
                            'paisResid',
                            'dscLograd',
                            'nrLograd',
                            'complemento',
                            'bairro',
                            'nmCid',
                            'codPostal'
                        )
                    ),
                ),
            ),


            'trabEstrangeiro' => array(
                'properties' => array(
                    'dtChegada',
                    'classTrabEstrang' => array(
                        'type' => 'int'
                    ),
                    'casadoBr',
                    'filhosBr',
                )
            ),
            'infoDeficiencia' => array(
                'properties' => array(
                    'defFisica',
                    'defVisual',
                    'defAuditiva',
                    'defMental',
                    'defIntelectual',
                    'reabReadap',
                    'infoCota',
                    'observacao'
                )
            ),
            'aposentadoria' => array(
                'properties' => array(
                    'trabAposent'
                )
            ),
            'contato' => array(
                'properties' => array(
                    'fonePrinc',
                    'foneAlternat',
                    'emailPrinc',
                    'emailAlternat',
                )
            ),
            'dependente_1' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'label' => 'Dependentes',
                'items' => array(
                    'properties' => array(
                        'tpDep_1' => 'tpDep',
                        'nmDep_1' => 'nmDep',
                        'dtNascto_1' => 'dtNascto',
                        'cpfDep_1' => 'cpfDep',
                        'depIRRF_1' => 'depIRRF',
                        'depSF_1' => 'depSF',
                        'incTrab_1' => 'incTrab',
                        'depFinsPrev_1' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_2' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_2' => 'tpDep',
                        'nmDep_2' => 'nmDep',
                        'dtNascto_2' => 'dtNascto',
                        'cpfDep_2' => 'cpfDep',
                        'depIRRF_2' => 'depIRRF',
                        'depSF_2' => 'depSF',
                        'incTrab_2' => 'incTrab',
                        'depFinsPrev_2' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_3' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_3' => 'tpDep',
                        'nmDep_3' => 'nmDep',
                        'dtNascto_3' => 'dtNascto',
                        'cpfDep_3' => 'cpfDep',
                        'depIRRF_3' => 'depIRRF',
                        'depSF_3' => 'depSF',
                        'incTrab_3' => 'incTrab',
                        'depFinsPrev_3' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_4' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_4' => 'tpDep',
                        'nmDep_4' => 'nmDep',
                        'dtNascto_4' => 'dtNascto',
                        'cpfDep_4' => 'cpfDep',
                        'depIRRF_4' => 'depIRRF',
                        'depSF_4' => 'depSF',
                        'incTrab_4' => 'incTrab',
                        'depFinsPrev_4' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_5' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_5' => 'tpDep',
                        'nmDep_5' => 'nmDep',
                        'dtNascto_5' => 'dtNascto',
                        'cpfDep_5' => 'cpfDep',
                        'depIRRF_5' => 'depIRRF',
                        'depSF_5' => 'depSF',
                        'incTrab_5' => 'incTrab',
                        'depFinsPrev_5' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_6' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_6' => 'tpDep',
                        'nmDep_6' => 'nmDep',
                        'dtNascto_6' => 'dtNascto',
                        'cpfDep_6' => 'cpfDep',
                        'depIRRF_6' => 'depIRRF',
                        'depSF_6' => 'depSF',
                        'incTrab_6' => 'incTrab',
                        'depFinsPrev_6' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_7' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_7' => 'tpDep',
                        'nmDep_7' => 'nmDep',
                        'dtNascto_7' => 'dtNascto',
                        'cpfDep_7' => 'cpfDep',
                        'depIRRF_7' => 'depIRRF',
                        'depSF_7' => 'depSF',
                        'incTrab_7' => 'incTrab',
                        'depFinsPrev_7' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_8' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_8' => 'tpDep',
                        'nmDep_8' => 'nmDep',
                        'dtNascto_8' => 'dtNascto',
                        'cpfDep_8' => 'cpfDep',
                        'depIRRF_8' => 'depIRRF',
                        'depSF_8' => 'depSF',
                        'incTrab_8' => 'incTrab',
                        'depFinsPrev_8' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_9' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_9' => 'tpDep',
                        'nmDep_9' => 'nmDep',
                        'dtNascto_9' => 'dtNascto',
                        'cpfDep_9' => 'cpfDep',
                        'depIRRF_9' => 'depIRRF',
                        'depSF_9' => 'depSF',
                        'incTrab_9' => 'incTrab',
                        'depFinsPrev_9' => 'depFinsPrev'
                    )
                )
            ),
            'dependente_10' => array(
                'type' => 'array',
                'nome_api' => 'dependente',
                'items' => array(
                    'properties' => array(
                        'tpDep_10' => 'tpDep',
                        'nmDep_10' => 'nmDep',
                        'dtNascto_10' => 'dtNascto',
                        'cpfDep_10' => 'cpfDep',
                        'depIRRF_10' => 'depIRRF',
                        'depSF_10' => 'depSF',
                        'incTrab_10' => 'incTrab',
                        'depFinsPrev_10' => 'depFinsPrev'
                    )
                )
            )
        )
    ),
    'vinculo' => array(
        'properties' => array(
            'matricula',
            'tpRegTrab' => array(
                'type' => 'int'
            ),
            'tpRegPrev' => array(
                'type' => 'int'
            ),
            'nrRecInfPrelim',
            'cadIni',
        ),

        'groups' => array(

            'infoRegimeTrab' => array(

                'groups' => array(
                    'infoCeletista' => array(
                        'properties' => array(
                            "dtAdm",
                            "tpAdmissao" => array(
                                "type" => "int"
                            ),
                            "indAdmissao" => array(
                                "type" => "int"
                            ),
                            "tpRegJor" => array(
                                "type" => "int"
                            ),
                            "natAtividade" => array(
                                "type" => "int"
                            ),
                            "dtBase" => array(
                                'type' => 'int'
                            ),
                            "cnpjSindCategProf"
                        ),
                        "groups" => array(
                            "FGTS" => array(
                                "properties" => array(
                                    "opcFGTS" => array(
                                        'type' => 'int'
                                    ),
                                    "dtOpcFGTS",
                                )
                            ),
                            "trabTemporario" => array(
                                "properties" => array(
                                    "hipLeg" => array(
                                        "type" => "int"
                                    ),
                                    "justContr",
                                    "tpInclContr" => array(
                                        "type" => "int"
                                    )
                                ),
                                "groups" => array(
                                    "ideTomadorServ" => array(
                                        "properties" => array(
                                            "tpInsc" => array(
                                                "type" => "int"
                                            ),
                                            "nrInsc"
                                        ),
                                        "groups" => array(
                                            "ideEstabVinc" => array(
                                                "properties" => array(
                                                    "tpInsc" => array(
                                                        "type" => "int"
                                                    ),
                                                    "nrInsc"
                                                )
                                            )
                                        )
                                    ),
                                    "ideTrabSubstituido" => array(
                                        'type' => 'array',
                                        'items' => array(
                                            'properties' => array(
                                                'cpfTrabSubst'
                                            )
                                        )
                                    )
                                )
                            ),
                            "aprend" => array(
                                'properties' => array(
                                    "tpInsc" => array(
                                        "type" => "int"
                                    ),
                                    "nrInsc"
                                )
                            )
                        )
                    ),
                    'infoEstatutario' => array(
                        'properties' => array(
                            'indProvim' => array(
                                'type' => 'int'
                            ),
                            'tpProv' => array(
                                'type' => 'int'
                            ),
                            'dtNomeacao',
                            'dtPosse',
                            'dtExercicio',
                            'tpPlanRP' => array(
                                'type' => 'int'
                            ),
                        ),
                        'groups' => array(
                            'infoDecJud' => array(
                                'properties' => array(
                                    'nrProcJud'
                                )
                            )
                        )
                    ),
                ),

            ),

            "infoContrato" => array(
                "properties" => array(
                    "codCargo",
                    "codFuncao",
                    "codCateg" => array(
                        "type" => "int"
                    ),
                    "codCarreira",
                    "dtIngrCarr"
                ),
                "groups" => array(
                    "remuneracao" => array(
                        "properties" => array(
                            "vrSalFx" => array(
                                "type" => "float"
                            ),
                            "undSalFixo" => array(
                                "type" => "int"
                            ),
                            "dscSalVar"
                        )
                    ),
                    "duracao" => array(
                        "properties" => array(
                            "tpContr" => array(
                                "type" => "int"
                            ),
                            "dtTerm",
                            "clauAssec",
                            "objDet"
                        )
                    ),

                    'localTrabalho' => array(
                        'groups' => array(
                            "localTrabGeral" => array(
                                "properties" => array(
                                    "tpInsc" => array(
                                        "type" => "int"
                                    ),
                                    "nrInsc",
                                    "descComp"
                                )
                            ),
                            "localTrabDom" => array(
                                "properties" => array(
                                    "tpLograd",
                                    "dscLograd",
                                    "nrLograd",
                                    "complemento",
                                    "bairro",
                                    "cep",
                                    "codMunic" => array(
                                        "type" => "int"
                                    ),
                                    "uf",
                                )
                            ),
                        ),
                    ),

                    "horContratual" => array(
                        "properties" => array(
                            "qtdHrsSem" => array(
                                "type" => "int"
                            ),
                            "tpJornada" => array(
                                "type" => "int"
                            ),
                            "dscTpJorn",
                            "tmpParc" => array(
                                "type" => "int"
                            ),
                        ),
                        'groups' => array(
                            'horario_codHorContrat_1' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => "Segunda-Feira",
                                    'properties' => array(
                                        'horario_codHorContrat_1' => 'codHorContrat',
                                        'dia_1' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_2' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => "Terça-Feira",
                                    'properties' => array(
                                        'horario_codHorContrat_2' => 'codHorContrat',
                                        'dia_2' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_3' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Quarta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_3' => 'codHorContrat',
                                        'dia_3' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_4' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Quinta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_4' => 'codHorContrat',
                                        'dia_4' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_5' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Sexta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_5' => 'codHorContrat',
                                        'dia_5' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_6' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Sábado',
                                    'properties' => array(
                                        'horario_codHorContrat_6' => 'codHorContrat',
                                        'dia_6' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_7' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Domingo',
                                    'properties' => array(
                                        'horario_codHorContrat_7' => 'codHorContrat',
                                        'dia_7' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_8' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Dia Variável',
                                    'properties' => array(
                                        'horario_codHorContrat_8' => 'codHorContrat',
                                        'dia_8' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_9' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => "Segunda-Feira",
                                    'properties' => array(
                                        'horario_codHorContrat_9' => 'codHorContrat',
                                        'dia_9' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_10' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => "Terça-Feira",
                                    'properties' => array(
                                        'horario_codHorContrat_10' => 'codHorContrat',
                                        'dia_10' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_11' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Quarta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_11' => 'codHorContrat',
                                        'dia_11' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_12' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Quinta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_12' => 'codHorContrat',
                                        'dia_12' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_13' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Sexta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_13' => 'codHorContrat',
                                        'dia_13' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_14' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Sábado',
                                    'properties' => array(
                                        'horario_codHorContrat_14' => 'codHorContrat',
                                        'dia_14' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_15' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Domingo',
                                    'properties' => array(
                                        'horario_codHorContrat_15' => 'codHorContrat',
                                        'dia_15' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_16' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Dia Variável',
                                    'properties' => array(
                                        'horario_codHorContrat_16' => 'codHorContrat',
                                        'dia_16' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_17' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => "Segunda-Feira",
                                    'properties' => array(
                                        'horario_codHorContrat_17' => 'codHorContrat',
                                        'dia_17' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_18' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => "Terça-Feira",
                                    'properties' => array(
                                        'horario_codHorContrat_18' => 'codHorContrat',
                                        'dia_18' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_19' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Quarta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_19' => 'codHorContrat',
                                        'dia_19' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_20' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Quinta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_20' => 'codHorContrat',
                                        'dia_20' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_21' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Sexta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_21' => 'codHorContrat',
                                        'dia_21' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_22' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Sábado',
                                    'properties' => array(
                                        'horario_codHorContrat_22' => 'codHorContrat',
                                        'dia_22' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_23' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Domingo',
                                    'properties' => array(
                                        'horario_codHorContrat_23' => 'codHorContrat',
                                        'dia_23' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_24' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Dia Variável',
                                    'properties' => array(
                                        'horario_codHorContrat_24' => 'codHorContrat',
                                        'dia_24' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_25' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => "Segunda-Feira",
                                    'properties' => array(
                                        'horario_codHorContrat_25' => 'codHorContrat',
                                        'dia_25' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_26' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => "Terça-Feira",
                                    'properties' => array(
                                        'horario_codHorContrat_26' => 'codHorContrat',
                                        'dia_26' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_27' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Quarta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_27' => 'codHorContrat',
                                        'dia_27' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_28' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Quinta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_28' => 'codHorContrat',
                                        'dia_28' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_29' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Sexta-Feira',
                                    'properties' => array(
                                        'horario_codHorContrat_29' => 'codHorContrat',
                                        'dia_29' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_30' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Sábado',
                                    'properties' => array(
                                        'horario_codHorContrat_30' => 'codHorContrat',
                                        'dia_30' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_31' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Domingo',
                                    'properties' => array(
                                        'horario_codHorContrat_31' => 'codHorContrat',
                                        'dia_31' => 'dia'
                                    )
                                )
                            ),
                            'horario_codHorContrat_32' => array(
                                'type' => 'array',
                                'nome_api' => 'horario',
                                'label' => 'Horário',
                                'items' => array(
                                    'label' => 'Dia Variável',
                                    'properties' => array(
                                        'horario_codHorContrat_32' => 'codHorContrat',
                                        'dia_32' => 'dia'
                                    )
                                )
                            ),
                            'filiacaoSindical' => array(
                                'type' => 'array',
                                'items' => array(
                                    'properties' => array(
                                        'cnpjSindTrab'
                                    )

                                )
                            ),
                            "alvaraJudicial" => array(
                                "properties" => array(
                                    "nrProcJud"
                                )
                            ),
                            'observacoes' => array(
                                'type' => 'array',
                                'items' => array(
                                    'properties' => array(
                                        'observacao'
                                    )
                                )
                            )
                        )
                    )
                )
                    ),
                    'sucessaoVinc' => array(
                        'properties' => array(
                            'tpInscAnt',
                            'cnpjEmpregAnt',
                            'matricAnt',
                            'sucessaoVinc_dtTransf' => 'dtTransf',
                            'sucessaoVinc_observacao' => 'observacao'
                        )
                    ),
                    'transfDom' => array(
                        'properties' => array(
                            'cpfSubstituido',
                            'transfDom_matricAnt' => 'matricAnt',
                            'dtTransf'
                        )
                    ),
                    'mudancaCPF' => array(
                        'properties' => array(
                            'cpfAnt',
                            'matricAnt',
                            'dtAltCPF',
                            'mudancaCPF_observacao' => 'observacao',
                        )
                    ),
                    'afastamento' => array(
                        'properties' => array(
                            'dtIniAfast',
                            'codMotAfast'
                        )
                    ),
                    'desligamento' => array(
                        'properties' => array(
                            'dtDeslig'
                    )
            )
        )
    )
);
