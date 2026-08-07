<?php

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Factories\DecretoFactory;

class decreto
{
    protected $arq = null;

    function __construct($header)
    {
        $this->header = $header;
    }

    function processa($instit = 1, $data_ini = "", $data_fim = "", $tribinst = null, $subelemento = "")
    {
        $anousu = db_getsession("DB_anousu");
        $instituicoes = InstituicaoRepository::getInstituicaoConsolida(db_getsession('DB_instit'));

        $service = DecretoFactory::getService($anousu, $instituicoes, $data_ini, $data_fim);
        $service->setHeader($this->header);
        $service->processa();

        return true;
    }
}

