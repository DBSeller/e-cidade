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

use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\InterfaceRelatorioLegal;
use Instituicao;
use InstituicaoRepository;
use PDFDocument;

class AnexoVI extends RelatorioLegal implements InterfaceRelatorioLegal
{
    public function __construct() {

        $this->oPdf = new PDFDocument("P");
        $this->oPdf->setFillColor(235);


    }

    private function header()
    {
        $this->oPdf->addHeaderDescription('');
        $instituicoes = $this->oAnexo->getInstituicoes();
        if (count($instituicoes) == 1) {
            $instituicao = array_shift($instituicoes);

            if ($instituicao->getTipo() != Instituicao::TIPO_PREFEITURA) {
                $enteFederacao = $instituicao->getDescricao();
            } else {
                $enteFederacao = DemonstrativoFiscal::getEnteFederativo($instituicao);
            }
        } else {
            $prefeitura = InstituicaoRepository::getInstituicaoPrefeitura();
            $enteFederacao = DemonstrativoFiscal::getEnteFederativo($prefeitura);
        }

        $mesInicio = mb_strtoupper(DBDate::getMesExtenso($this->oAnexo->getPeriodo()->getMesInicial()));
        $mesFinal = mb_strtoupper(DBDate::getMesExtenso($this->oAnexo->getPeriodo()->getMesFinal()));

        $this->oPdf->addHeaderDescription($enteFederacao);

        $this->oPdf->addHeaderDescription('RELATÓRIO DE GESTÃO FISCAL');
        $this->oPdf->addHeaderDescription('DEMONSTRATIVO SIMPLIFICADO DO RELATÓRIO DE GESTÃO FISCAL');
        $this->oPdf->addHeaderDescription('ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL');
        $this->oPdf->addHeaderDescription($mesInicio . ' A ' . $mesFinal . ' DE ' . $this->oAnexo->getAno());
        $this->oPdf->open();
        $this->oPdf->addPage();
        $this->oPdf->SetFont("Arial", "", 8);
    }

    public function emitir()
    {
        $this->header();
        $this->imprimirLinhas($this->oAnexo->getDadosProcessados());
        $this->oPdf->showPDF("ANEXO_VI_DEMONSTRATIVO_SIMPLIFICADO_DO_RELATORIO_DE_GESTAO_FISCAL");
    }

    public function emitirDadosSimplificado()
    {
        $this->emitir();
    }
}