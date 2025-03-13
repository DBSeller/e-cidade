<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

return array(
    'ideEstabPrest' => array(
        'properties' => array(
            'tpInscEstabPrest'=> array(
                'type' => 'int'
            ),
            'nrInscEstabPrest',
            'perApur',
        ),
        'groups' => array(
            'ideTomador' => array(
                'properties' => array(
                    'tpInscTomador' => array(
                        'type' => 'int'
                    ),
                    'nrInscTomador',
                    'indObra',
                    'vlrTotalBruto' => array(
                        'type' => 'float'
                    ),
                    'vlrTotalBaseRet' => array(
                        'type' => 'float'
                    ),
                    'vlrTotalRetPrinc' => array(
                        'type' => 'float'
                    ),
                    'vlrTotalRetAdic' => array(
                        'type' => 'float'
                    ),
                    'vlrTotalNRetPrinc' => array(
                        'type' => 'float'
                    ),
                    'vlrTotalNRetAdic' => array(
                        'type' => 'float'
                    ),
                ),
                'groups' => array(
                    'nfs' => array(
                        'properties' => array(
                            'serie',
                            'numDocto',
                            'dtEmissaoNF',
                            'vlrBruto' => array(
                                'type' => 'float'
                            ),
                            'obs',
                        ),
                        'groups' => array(
                            'infoTpServ' => array(
                                'type' => 'array',
                                'nome_api' => 'infoTpServ',
                                'items' => array(
                                    'properties' => array(
                                        'tpServico' => array(
                                            'type' => 'int'
                                        ),
                                        'vlrBaseRet' => array(
                                            'type' => 'float'
                                        ),
                                        'vlrRetencao' => array(
                                            'type' => 'float'
                                        ),
                                        'vlrRetSub' => array(
                                            'type' => 'float'
                                        ),
                                        'vlrNRetPrinc' => array(
                                            'type' => 'float'
                                        ),
                                        'vlrServicos15' => array(
                                            'type' => 'float'
                                        ),
                                        'vlrServicos20' => array(
                                            'type' => 'float'
                                        ),
                                        'vlrServicos25' => array(
                                            'type' => 'float'
                                        ),
                                        'vlrAdicional' => array(
                                            'type' => 'float'
                                        ),
                                        'vlrNRetAdic' => array(
                                            'type' => 'float'
                                        ),
                                    )
                                )
                            )
                        )
                    ),
                    'infoProcRetPr' => array(
                        'type' => 'array',
                        'nome_api' => 'infoProcRetPr',
                        'items' => array(
                            'properties' => array(
                                'tpProcRetPrinc' => array(
                                    'type' => 'int'
                                ),
                                'nrProcRetPrinc',
                                'codSuspPrinc' => array(
                                    'type' => 'int'
                                ),
                                'valorPrinc' => array(
                                    'type' => 'float'
                                )
                            )
                        )
                    ),
                    'infoProcRetAd' => array(
                        'type' => 'array',
                        'nome_api' => 'infoProcRetAd',
                        'items' => array(
                            'properties' => array(
                                'tpProcRetAdic' => array(
                                    'type' => 'int'
                                ),
                                'nrProcRetAdic',
                                'codSuspAdic' => array(
                                    'type' => 'int'
                                ),
                                'valorAdic' => array(
                                    'type' => 'float'
                                )
                            )
                        )
                    ),
                )
            )
        )
    )
);