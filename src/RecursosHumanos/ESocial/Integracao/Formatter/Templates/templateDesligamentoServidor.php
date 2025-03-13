<?php
/**
 * S-2299
 * Desligamento do Servidor
 */
return array(
    'ideVinculo' => array(
        'properties' => array(
            'cpfTrab',
            'nisTrab',
            'matricula'
        )
    ),
    'infoDeslig' => array(
        'properties' => array(
            'mtvDeslig',
            'dtDeslig',
            'indPagtoAPI',
            'dtProjFimAPI',
            'pensAlim',
            'percAliment',
            'vrAlim',
            'nrCertObito',
            'nrProcTrab',
            'indCumprParc',
            'qtdDiasInterm'
        ),
        'groups' => array(
            'observacoes' => array(
                'type' => 'array',
                'items' => array(
                    'properties' => array(
                        'observacao'
                    )
                )
            ),
            'sucessaoVinc' => array(
                'properties' => array(
                    'tpInscSuc',
                    'cnpjSucessora'
                )
            ),
            'transfTit' => array(
                'properties' => array(
                    'cpfSubstituto',
                    'dtNascto_transfTit' => 'dtNascto'
                )
            ),
            'mudancaCPF' => array(
                'properties' => array(
                    'novoCPF'
                )
            ),
            'verbasResc' => array(
                'groups' => array(
                    'dmDev' => array(
                        'groups' => array(
                            'infoPerApur' => array(
                                'groups' => array(
                                    'ideEstabLot' => array(
                                        'properties' => array(
                                            'infoPerApur_tpInsc' => 'tpInsc',
                                            'infoPerApur_nrInsc' => 'nrInsc',
                                            'infoPerApur_codLotacao' => 'codLotacao',
                                        ),
                                        'groups' => array(
                                            'detVerbas' => array(
                                                'properties' => array(
                                                    'detVerbas_codRubr'    => 'codRubr',
                                                    'detVerbas_ideTabRubr' => 'ideTabRubr',
                                                    'detVerbas_qtdRubr'    => 'qtdRubr',
                                                    'detVerbas_fatorRubr'  => 'fatorRubr',
                                                    'detVerbas_vrUnit'     => 'vrUnit',
                                                    'detVerbas_vrRubr'     => 'vrRubr',
                                                )
                                            ),
                                            'infoSaudeColet' => array(
                                                'groups' => array(
                                                    'detOper' => array(
                                                        'properties' => array(
                                                            'detOper_1_cnpjOper',
                                                            'detOper_1_regANS'  ,
                                                            'detOper_1_vrPgTit' ,
                                                            'detOper_2_cnpjOper',
                                                            'detOper_2_regANS'  ,
                                                            'detOper_2_vrPgTit' ,
                                                        ),
                                                        'groups' => array(
                                                            'dependente_1' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano1_tpDep'    => 'tpDep',
                                                                        'detPlano1_cpfDep'   => 'cpfDep',
                                                                        'detPlano1_nmDep'    => 'nmDep',
                                                                        'detPlano1_dtNascto' => 'dtNascto',
                                                                        'detPlano1_vlrPgDep_operadora1',
                                                                        'detPlano1_vlrPgDep_operadora2',
                                                                        'detPlano1_cnpj_operadora1',
                                                                        'detPlano1_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_2' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano2_tpDep'    => 'tpDep',
                                                                        'detPlano2_cpfDep'   => 'cpfDep',
                                                                        'detPlano2_nmDep'    => 'nmDep',
                                                                        'detPlano2_dtNascto' => 'dtNascto',
                                                                        'detPlano2_vlrPgDep_operadora1',
                                                                        'detPlano2_vlrPgDep_operadora2',
                                                                        'detPlano2_cnpj_operadora1',
                                                                        'detPlano2_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_3' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano3_tpDep'    => 'tpDep',
                                                                        'detPlano3_cpfDep'   => 'cpfDep',
                                                                        'detPlano3_nmDep'    => 'nmDep',
                                                                        'detPlano3_dtNascto' => 'dtNascto',
                                                                        'detPlano3_vlrPgDep_operadora1',
                                                                        'detPlano3_vlrPgDep_operadora2',
                                                                        'detPlano3_cnpj_operadora1',
                                                                        'detPlano3_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_4' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano4_tpDep'    => 'tpDep',
                                                                        'detPlano4_cpfDep'   => 'cpfDep',
                                                                        'detPlano4_nmDep'    => 'nmDep',
                                                                        'detPlano4_dtNascto' => 'dtNascto',
                                                                        'detPlano4_vlrPgDep_operadora1',
                                                                        'detPlano4_vlrPgDep_operadora2',
                                                                        'detPlano4_cnpj_operadora1',
                                                                        'detPlano4_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_5' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano5_tpDep'    => 'tpDep',
                                                                        'detPlano5_cpfDep'   => 'cpfDep',
                                                                        'detPlano5_nmDep'    => 'nmDep',
                                                                        'detPlano5_dtNascto' => 'dtNascto',
                                                                        'detPlano5_vlrPgDep_operadora1',
                                                                        'detPlano5_vlrPgDep_operadora2',
                                                                        'detPlano5_cnpj_operadora1',
                                                                        'detPlano5_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_6' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano6_tpDep'    => 'tpDep',
                                                                        'detPlano6_cpfDep'   => 'cpfDep',
                                                                        'detPlano6_nmDep'    => 'nmDep',
                                                                        'detPlano6_dtNascto' => 'dtNascto',
                                                                        'detPlano6_vlrPgDep_operadora1',
                                                                        'detPlano6_vlrPgDep_operadora2',
                                                                        'detPlano6_cnpj_operadora1',
                                                                        'detPlano6_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_7' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano7_tpDep'    => 'tpDep',
                                                                        'detPlano7_cpfDep'   => 'cpfDep',
                                                                        'detPlano7_nmDep'    => 'nmDep',
                                                                        'detPlano7_dtNascto' => 'dtNascto',
                                                                        'detPlano7_vlrPgDep_operadora1',
                                                                        'detPlano7_vlrPgDep_operadora2',
                                                                        'detPlano7_cnpj_operadora1',
                                                                        'detPlano7_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_8' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano8_tpDep'    => 'tpDep',
                                                                        'detPlano8_cpfDep'   => 'cpfDep',
                                                                        'detPlano8_nmDep'    => 'nmDep',
                                                                        'detPlano8_dtNascto' => 'dtNascto',
                                                                        'detPlano8_vlrPgDep_operadora1',
                                                                        'detPlano8_vlrPgDep_operadora2',
                                                                        'detPlano8_cnpj_operadora1',
                                                                        'detPlano8_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_9' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano9_tpDep'    => 'tpDep',
                                                                        'detPlano9_cpfDep'   => 'cpfDep',
                                                                        'detPlano9_nmDep'    => 'nmDep',
                                                                        'detPlano9_dtNascto' => 'dtNascto',
                                                                        'detPlano9_vlrPgDep_operadora1',
                                                                        'detPlano9_vlrPgDep_operadora2',
                                                                        'detPlano9_cnpj_operadora1',
                                                                        'detPlano9_cnpj_operadora2',
                                                                    )
                                                                )
                                                            ),
                                                            'dependente_10' => array(
                                                                'type' => 'array',
                                                                'nome_api' => 'detPlano',
                                                                'items' => array(
                                                                    'properties' => array(
                                                                        'detPlano10_tpDep'    => 'tpDep',
                                                                        'detPlano10_cpfDep'   => 'cpfDep',
                                                                        'detPlano10_nmDep'    => 'nmDep',
                                                                        'detPlano10_dtNascto' => 'dtNascto',
                                                                        'detPlano10_vlrPgDep_operadora1',
                                                                        'detPlano10_vlrPgDep_operadora2',
                                                                        'detPlano10_cnpj_operadora1',
                                                                        'detPlano10_cnpj_operadora2',
                                                                    )
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            ),
                                            'infoPerApur_infoAgNocivo' => array(
                                                'nome_api' => 'infoAgNocivo',
                                                'properties' => array(
                                                    'infoPerApur_grauExp' => 'grauExp',
                                                )
                                            )
                                        )
                                    )
                                )
                            ),
                            'infoPerAnt' => array(
                                'groups' => array(
                                    'ideADC' => array(
                                        'properties' => array(
                                            'dtAcConv',
                                            'tpAcConv',
                                            'compAcConv',
                                            'dtEfAcConv',
                                            'dsc',
                                        ),
                                        'groups' => array(
                                            'idePeriodo' => array(
                                                'properties' => array(
                                                    'perRef'
                                                ),
                                                'groups' => array(
                                                    'ideEstabLot' => array(
                                                        'properties' => array(
                                                            'infoPerAnt_tpInsc' => 'tpInsc',
                                                            'infoPerAnt_nrInsc' => 'nrInsc',
                                                            'infoPerAnt_codLotacao' => 'codLotacao',
                                                        ),
                                                        'groups' => array(
                                                            'detVerbas' => array(
                                                                'properties' => array(
                                                                    'detVerbas_codRubr'    => 'codRubr',
                                                                    'detVerbas_ideTabRubr' => 'ideTabRubr',
                                                                    'detVerbas_qtdRubr'    => 'qtdRubr',
                                                                    'detVerbas_fatorRubr'  => 'fatorRubr',
                                                                    'detVerbas_vrUnit'     => 'vrUnit',
                                                                    'detVerbas_vrRubr'     => 'vrRubr',
                                                                )
                                                            ),
                                                            'infoPerAnt_infoAgNocivo' => array(
                                                                'nome_api' => 'infoAgNocivo',
                                                                'properties' => array(
                                                                    'infoPerAnt_grauExp' => 'grauExp'
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            ),
                            'infoTrabInterm' => array(
                                'properties' => array(
                                    'codConv'
                                )
                            ),
                        )
                    ),
                    'procJudTrab' => array(
                        'properties' => array(
                            'tpTrib',
                            'procJudTrab_nrProcJud' => 'nrProcJud',
                            'codSusp',
                        ),
                    ),
                    'infoMV' => array(
                        'properties' => array(
                            'indMV',
                        ),
                        'groups' => array(
                            'remunOutrEmpr' => array(
                                'properties' => array(
                                    'remunOutrEmpr_tpInsc' => 'tpInsc',
                                    'remunOutrEmpr_nrInsc' => 'nrInsc',
                                    'codCateg',
                                    'vlrRemunOE',
                                )
                            )
                        )
                    ),
                    'procCS' => array(
                        'properties' => array(
                           'procCS_nrProcJud' => 'nrProcJud'
                        )
                    )
                )
            ),
            'quarentena' => array(
                'properties' => array(
                    'dtFimQuar'
                )
            ),
            'consigFGTS' => array(
                'properties' => array(
                    'insConsig',
                    'nrContr'
                )
            )
        )
    ),
    'desligamento_rubricas' => array(
        'properties' => array(
            'desligamento_rubricas_json'
        )
    )
);
