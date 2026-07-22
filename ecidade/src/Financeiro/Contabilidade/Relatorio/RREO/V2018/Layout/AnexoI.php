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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoI as LayoutAnexoI2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoI as Relatorio;

class AnexoI extends LayoutAnexoI2017
{
    const LINHARECEITAFIM = 80;
    const LINHADESPESAINICIO = 81;
    const LINHADEPSESAFIM = 105;
    const LINHARECEITAINTRAINICIO = 106;
    const LINHARECEITAINTRAFIM = 169;
    const LINHADESPESAINTRAINICIO = 170;
    const LINHADESPESAINTRAFIM = 179;

    protected function processar()
    {
        $this->relatorio = new Relatorio($this->iAno, Relatorio::CODIGO_RELATORIO, $this->oPeriodo->getCodigo());
        $this->relatorio->setDataInicial($this->relatorio->getDataInicialPeriodo());
        $this->relatorio->setInstituicoes($this->sInstituicao);
        $this->aLinhas = $this->relatorio->getLinhas();
        $this->oDataFinal = $this->relatorio->getDataFinal();
        $this->oDataInicial = $this->relatorio->getDataInicialPeriodo();
        $this->ajustaDespesas();
    }

    protected function formataValor($linha)
    {
        $linhasCondicao = array(
            Relatorio::LINHA_DEFICIT_VI,
            Relatorio::LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS,
            Relatorio::LINHA_REABERTURA_CREDITOS_ADICIONAIS,
            Relatorio::LINHA_SUPERAVIT_XIII
        );

        if (in_array($linha->ordem, $linhasCondicao)) {
            array_map(function ($coluna) use ($linha) {
                $nomeColuna = $coluna->o115_nomecoluna;
                $valor = $linha->{$nomeColuna};
                if ($valor !== '-') {
                    $linha->{$nomeColuna} = db_formatar($valor, 'f');
                }
            }, $linha->colunas);
        } else {
            parent::formataValor($linha);
        }
    }

    protected function adicionarLegendas()
    {
        if ($this->oPdf->gety() > $this->oPdf->h - 35) {
            $this->oPdf->addpage();
        }

        $legendaDeficit = '1 - O déficit será apurado pela diferença entre a receita realizada e a despesa liquidada nos cinco primeiros bimestres e a despesa empenhada no último bimestre.';
        $legendaDemonstrativo = '2 - Essa linha será apresentada somente no Demonstrativo aplicado aos Estados.';

        $this->oPdf->setfont('arial', '', 6);
        $this->oPdf->Cell(190, 3, $legendaDeficit, 0, 1);
        $this->oPdf->Cell(190, 3, $legendaDemonstrativo, 0, 1);
    }
}
