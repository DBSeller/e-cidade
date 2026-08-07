<?php

namespace Tests\Unit\Patrimonial\Licitacao\LicitaCon;

use DBDate;
use ECidade\Patrimonial\Licitacao\Licitacon\Versao;
use Exception;
use Tests\TestCase;

/**
 * Class VersaoTest
 * @package Tests\Unit\Patrimonial\Licitacao\LicitaCon
 */
class VersaoTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testVersao12()
    {
        $sVersaoEsperada = "1.2";

        $sData = "30/11/2016";
        $oDataGeracao = new DBDate($sData);
        $oConfiguracaoLicitacon = new Versao($oDataGeracao);
        $this->assertEquals($sVersaoEsperada, $oConfiguracaoLicitacon->getVersao());

        $sData = "01/01/2016";
        $oDataGeracao = new DBDate($sData);
        $oConfiguracaoLicitacon = new Versao($oDataGeracao);
        $this->assertEquals($sVersaoEsperada, $oConfiguracaoLicitacon->getVersao());

        $sData = "01/12/2015";
        $oDataGeracao = new DBDate($sData);
        $oConfiguracaoLicitacon = new Versao($oDataGeracao);
        $this->assertEquals($sVersaoEsperada, $oConfiguracaoLicitacon->getVersao());
    }

    /**
     * @throws Exception
     */
    public function testVersao13()
    {
        $sVersaoEsperada = "1.3";

        $sData = "01/12/2016";
        $oDataGeracao = new DBDate($sData);
        $oConfiguracaoLicitacon = new Versao($oDataGeracao);
        $this->assertEquals($sVersaoEsperada, $oConfiguracaoLicitacon->getVersao());

        $sData = "31/12/2016";
        $oDataGeracao = new DBDate($sData);
        $oConfiguracaoLicitacon = new Versao($oDataGeracao);
        $this->assertEquals($sVersaoEsperada, $oConfiguracaoLicitacon->getVersao());

        $sData = "01/01/2017";
        $oDataGeracao = new DBDate($sData);
        $oConfiguracaoLicitacon = new Versao($oDataGeracao);
        $this->assertEquals($sVersaoEsperada, $oConfiguracaoLicitacon->getVersao());
    }
}
