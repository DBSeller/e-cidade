<?php

namespace Tests\Unit\Patrimonial\Licitacao\LicitaCon;

use ECidade\Patrimonial\Licitacao\Licitacon\Julgamento;
use licitacao;
use Tests\TestCase;

/**
 * Class JulgamentoTest
 * @package Tests\Unit\Patrimonial\Licitacao\LicitaCon
 */
class JulgamentoTest extends TestCase
{
    /**
     *
     */
    public function testItem()
    {
        $iTipoJulgamento = licitacao::TIPO_JULGAMENTO_POR_ITEM;
        $sSiglaJulgamento = Julgamento::TIPO_JULGAMENTO_SIGLA_POR_ITEM;

        $oJulgamento = new Julgamento($iTipoJulgamento);
        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Julgamento', $oJulgamento);
        $this->assertEquals($sSiglaJulgamento, $oJulgamento->getSigla());
        $this->assertTrue($oJulgamento->isItem());
        $this->assertFalse($oJulgamento->isGlobal());
        $this->assertFalse($oJulgamento->isLote());
    }

    /**
     *
     */
    public function testGlobal()
    {
        $iTipoJulgamento = licitacao::TIPO_JULGAMENTO_GLOBAL;
        $sSiglaJulgamento = Julgamento::TIPO_JULGAMENTO_SIGLA_GLOBAL;

        $oJulgamento = new Julgamento($iTipoJulgamento);
        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Julgamento', $oJulgamento);
        $this->assertEquals($sSiglaJulgamento, $oJulgamento->getSigla());
        $this->assertTrue($oJulgamento->isGlobal());
        $this->assertFalse($oJulgamento->isItem());
        $this->assertFalse($oJulgamento->isLote());
    }

    /**
     *
     */
    public function testLote()
    {
        $iTipoJulgamento = licitacao::TIPO_JULGAMENTO_POR_LOTE;
        $sSiglaJulgamento = Julgamento::TIPO_JULGAMENTO_SIGLA_POR_LOTE;

        $oJulgamento = new Julgamento($iTipoJulgamento);
        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Julgamento', $oJulgamento);
        $this->assertEquals($sSiglaJulgamento, $oJulgamento->getSigla());
        $this->assertTrue($oJulgamento->isLote());
        $this->assertFalse($oJulgamento->isGlobal());
        $this->assertFalse($oJulgamento->isItem());
    }
}
