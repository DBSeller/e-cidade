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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\InterfaceRelatorioLegal;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

class AnexoIII extends RelatorioLegal implements InterfaceRelatorioLegal
{
    /**
     * @var integer
     */
    private $ano;

    /**
     * @var \Periodo
     */
    private $periodo;

    /**
     * @var array
     */
    private $descricaoMes = array(4=>"ABRIL", 6 => "JUNHO", 8 => "AGOSTO", 12 => "DEZEMBRO");

    /**
     * AnexoIII constructor.
     * @param int $ano
     * @param \Periodo $periodo
     */
    public function __construct($ano, $periodo)
    {
        $this->oPdf = new \PDFDocument("P");
        $this->oPdf->setFillColor(235);

        $this->ano = $ano;
        $this->periodo = $periodo;
    }

    private function header()
    {
        $oInstituicao = \InstituicaoRepository::getInstituicaoSessao();

        $demonstrativoFiscal = DemonstrativoFiscal::getEnteFederativo($oInstituicao);

        if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
            $demonstrativoFiscal .= "\n" . $oInstituicao->getDescricao();
        }

        $this->oPdf->addHeaderDescription($demonstrativoFiscal);
        $this->oPdf->addHeaderDescription("RELATÓRIO DE GESTÃO FISCAL");
        $this->oPdf->addHeaderDescription("DEMONSTRATIVO DAS GARANTIAS E CONTRAGARANTIAS DE VALORES");
        $this->oPdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
        $mesFinal = $this->periodo->getMesFinal();
        $this->oPdf->addHeaderDescription("JANEIRO A ". $this->descricaoMes[$mesFinal] ." DE ". $this->ano);
        $this->oPdf->open();
        $this->oPdf->addPage();
    }

    /**
     * Emite o relatório em PDF.
     */
    public function emitir()
    {
        $this->header();
        $aLinhas = $this->oAnexo->getDadosProcessados();
        $this->imprimirLinhas($aLinhas);
        $this->oPdf->showPDF("AnexoIII");
    }

    /**
     * @return \stdClass
     */
    public function emitirDadosSimplificado()
    {
        return $this->oAnexo->getDadosSimplificado();
    }
}
