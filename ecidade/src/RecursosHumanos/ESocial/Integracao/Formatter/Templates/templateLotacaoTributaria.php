<?php
return array(
    "ideLotacao" => array(
        'properties' => array(
            "codLotacao",
            "iniValid",
            "fimValid",
        )
    ),
    "dadosLotacao"=> array(
        'properties' => array(
            "tpLotacao",
            "tpInsc" => array(
                "type" => "int"
            ),
            "nrInsc",
        ),
        "groups" => array(
            "fpasLotacao" => array(
                "properties" => array(
                    "fpas" => array(
                        "type" => "int"
                    ),
                    "codTercs",
                    "codTercsSusp",
                ),
                "groups" => array (
                    "procJudTerceiro" => array(
                        "type" => "array",
                        "items" => array(
                            "properties" => array(
                                "codTerc",
                                "nrProcJud",
                                "codSusp",
                            )
                        )
                    )
                )
            ),
            "infoEmprParcial" => array (
                "properties" => array(

                    "tpInscContrat" => array(
                        "type" => "int"
                    ),
                    "nrInscContrat",
                    "tpInscProp" => array(
                        "type" => "int"
                    ),
                    "nrInscProp",
                )
            ),
            "dadosOpPort" => array(
                "properties" => array(
                    "aliqRat" => array(
                        "type" => "int"
                    ),
                    "fap" => array(
                        "type" => "float"
                    ),
                )
            )
        )
    )
);
