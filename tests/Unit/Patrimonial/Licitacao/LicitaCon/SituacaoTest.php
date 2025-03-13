<?php

namespace Tests\Unit\Patrimonial\Licitacao\LicitaCon;

use ECidade\Patrimonial\Licitacao\Licitacon\Situacao;
use SituacaoLicitacao;
use Tests\TestCase;

class SituacaoTest extends TestCase
{
    public function testAnulada()
    {
        $iCodigo = SituacaoLicitacao::SITUACAO_ANULADA;
        $sSigla = Situacao::SIGLA_SITUACAO_ANULADA;

        $oSituacao = new Situacao($iCodigo);

        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Situacao', $oSituacao);
        $this->assertEquals($iCodigo, $oSituacao->getCodigo());
        $this->assertEquals($sSigla, $oSituacao->getSigla());
        $this->assertTrue($oSituacao->isAnulada());
        $this->assertFalse($oSituacao->isDeserta());
        $this->assertFalse($oSituacao->isEmAndamento());
        $this->assertFalse($oSituacao->isFracassada());
        $this->assertFalse($oSituacao->isAdjudicada());
        $this->assertFalse($oSituacao->isJulgada());
        $this->assertFalse($oSituacao->isRevogada());
        $this->assertFalse($oSituacao->isHomologada());
    }

    public function testDeserta()
    {
        $iCodigo = SituacaoLicitacao::SITUACAO_DESERTA;
        $sSigla = Situacao::SIGLA_SITUACAO_DESERTA;

        $oSituacao = new Situacao($iCodigo);

        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Situacao', $oSituacao);
        $this->assertEquals($iCodigo, $oSituacao->getCodigo());
        $this->assertEquals($sSigla, $oSituacao->getSigla());
        $this->assertTrue($oSituacao->isDeserta());
        $this->assertFalse($oSituacao->isAnulada());
        $this->assertFalse($oSituacao->isEmAndamento());
        $this->assertFalse($oSituacao->isFracassada());
        $this->assertFalse($oSituacao->isAdjudicada());
        $this->assertFalse($oSituacao->isJulgada());
        $this->assertFalse($oSituacao->isRevogada());
        $this->assertFalse($oSituacao->isHomologada());
    }

    public function testEmAndamento()
    {
        $iCodigo = SituacaoLicitacao::SITUACAO_EM_ANDAMENTO;
        $sSigla = Situacao::SIGLA_SITUACAO_EM_ANDAMENTO;

        $oSituacao = new Situacao($iCodigo);

        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Situacao', $oSituacao);
        $this->assertEquals($iCodigo, $oSituacao->getCodigo());
        $this->assertEquals($sSigla, $oSituacao->getSigla());
        $this->assertTrue($oSituacao->isEmAndamento());
        $this->assertFalse($oSituacao->isDeserta());
        $this->assertFalse($oSituacao->isAnulada());
        $this->assertFalse($oSituacao->isFracassada());
        $this->assertFalse($oSituacao->isAdjudicada());
        $this->assertFalse($oSituacao->isJulgada());
        $this->assertFalse($oSituacao->isRevogada());
        $this->assertFalse($oSituacao->isHomologada());
    }

    public function testFracassada()
    {
        $iCodigo = SituacaoLicitacao::SITUACAO_FRACASSADA;
        $sSigla = Situacao::SIGLA_SITUACAO_FRACASSADA;

        $oSituacao = new Situacao($iCodigo);

        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Situacao', $oSituacao);
        $this->assertEquals($iCodigo, $oSituacao->getCodigo());
        $this->assertEquals($sSigla, $oSituacao->getSigla());
        $this->assertTrue($oSituacao->isFracassada());
        $this->assertFalse($oSituacao->isDeserta());
        $this->assertFalse($oSituacao->isEmAndamento());
        $this->assertFalse($oSituacao->isAnulada());
        $this->assertFalse($oSituacao->isAdjudicada());
        $this->assertFalse($oSituacao->isJulgada());
        $this->assertFalse($oSituacao->isRevogada());
        $this->assertFalse($oSituacao->isHomologada());
    }

    public function testAdjudicada()
    {
        $iCodigo = SituacaoLicitacao::SITUACAO_ADJUDICADA;
        $sSigla = Situacao::SIGLA_SITUACAO_ADJUDICADO;

        $oSituacao = new Situacao($iCodigo);

        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Situacao', $oSituacao);
        $this->assertEquals($iCodigo, $oSituacao->getCodigo());
        $this->assertEquals($sSigla, $oSituacao->getSigla());
        $this->assertTrue($oSituacao->isAdjudicada());
        $this->assertFalse($oSituacao->isDeserta());
        $this->assertFalse($oSituacao->isEmAndamento());
        $this->assertFalse($oSituacao->isFracassada());
        $this->assertFalse($oSituacao->isAnulada());
        $this->assertFalse($oSituacao->isJulgada());
        $this->assertFalse($oSituacao->isRevogada());
        $this->assertFalse($oSituacao->isHomologada());
    }

    public function testHomologada()
    {
        $iCodigo = SituacaoLicitacao::SITUACAO_HOMOLOGADA;
        $sSigla = Situacao::SIGLA_SITUACAO_HOMOLOGADA;

        $oSituacao = new Situacao($iCodigo);

        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Situacao', $oSituacao);
        $this->assertEquals($iCodigo, $oSituacao->getCodigo());
        $this->assertEquals($sSigla, $oSituacao->getSigla());
        $this->assertTrue($oSituacao->isHomologada());
        $this->assertFalse($oSituacao->isDeserta());
        $this->assertFalse($oSituacao->isEmAndamento());
        $this->assertFalse($oSituacao->isFracassada());
        $this->assertFalse($oSituacao->isAdjudicada());
        $this->assertFalse($oSituacao->isJulgada());
        $this->assertFalse($oSituacao->isRevogada());
        $this->assertFalse($oSituacao->isAnulada());
    }

    public function testJulgada()
    {
        $iCodigo = SituacaoLicitacao::SITUACAO_JULGADA;
        $sSigla = Situacao::SIGLA_SITUACAO_JULGADA;

        $oSituacao = new Situacao($iCodigo);

        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Situacao', $oSituacao);
        $this->assertEquals($iCodigo, $oSituacao->getCodigo());
        $this->assertEquals($sSigla, $oSituacao->getSigla());
        $this->assertTrue($oSituacao->isJulgada());
        $this->assertFalse($oSituacao->isDeserta());
        $this->assertFalse($oSituacao->isEmAndamento());
        $this->assertFalse($oSituacao->isFracassada());
        $this->assertFalse($oSituacao->isAdjudicada());
        $this->assertFalse($oSituacao->isAnulada());
        $this->assertFalse($oSituacao->isRevogada());
        $this->assertFalse($oSituacao->isHomologada());
    }

    public function testRevogada()
    {
        $iCodigo = SituacaoLicitacao::SITUACAO_REVOGADA;
        $sSigla = Situacao::SIGLA_SITUACAO_REVOGADA;

        $oSituacao = new Situacao($iCodigo);

        $this->assertInstanceOf('ECidade\Patrimonial\Licitacao\Licitacon\Situacao', $oSituacao);
        $this->assertEquals($iCodigo, $oSituacao->getCodigo());
        $this->assertEquals($sSigla, $oSituacao->getSigla());
        $this->assertTrue($oSituacao->isRevogada());
        $this->assertTrue($oSituacao->isRevogada());
        $this->assertFalse($oSituacao->isDeserta());
        $this->assertFalse($oSituacao->isEmAndamento());
        $this->assertFalse($oSituacao->isFracassada());
        $this->assertFalse($oSituacao->isAdjudicada());
        $this->assertFalse($oSituacao->isJulgada());
        $this->assertFalse($oSituacao->isAnulada());
        $this->assertFalse($oSituacao->isHomologada());
    }
}
