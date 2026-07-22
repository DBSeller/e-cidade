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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoI as LayoutAnexoI2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\AnexoI as Relatorio;

class AnexoI extends LayoutAnexoI2018
{
    const LINHARECEITAFIM = 79;
    const LINHADESPESAINICIO = 80;
    const LINHADEPSESAFIM = 104;
    const LINHARECEITAINTRAINICIO = 105;
    const LINHARECEITAINTRAFIM = 168;
    const LINHADESPESAINTRAINICIO = 169;
    const LINHADESPESAINTRAFIM = 178;

    protected $sessao = null;
    /**
     * @var int
     */
    private $codigoRelatorio;

    public function __construct($iAno, \Periodo $oPeriodo, $sInstituicao, $sessao = [])
    {
        $this->sessao = $sessao;
        parent::__construct($iAno, $oPeriodo, $sInstituicao);
    }

    protected function processar()
    {
        $this->codigoRelatorio = Relatorio::CODIGO_RELATORIO;
        $this->relatorio = new Relatorio($this->iAno, Relatorio::CODIGO_RELATORIO, $this->oPeriodo->getCodigo());
        $this->relatorio->setDataInicial($this->relatorio->getDataInicialPeriodo());
        $this->relatorio->setInstituicoes($this->sInstituicao);
        $this->aLinhas = $this->relatorio->getLinhas();
        $this->oDataFinal = $this->relatorio->getDataFinal();
        $this->oDataInicial = $this->relatorio->getDataInicialPeriodo();
        $this->ajustaDespesas();
    }

    public function processarFiscal()
    {
        $this->processar();
    }

    /**
     * @return integer
     */
    public function getCodigoRelatorio()
    {
        return $this->codigoRelatorio;
    }

    protected function formataValor($linha)
    {
        $linhasCondicao = array(
            Relatorio::LINHA_DEFICIT_VI,
            Relatorio::LINHA_SUPERAVIT_FINANCEIRO_UTILIZADO_CREDITOS_ADICIONAIS,
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

        $legendaDeficit  = '1 - O déficit será apurado pela diferença entre a receita realizada e a despesa liquidada';
        $legendaDeficit .= 'nos cinco primeiros bimestres e a despesa empenhada no último bimestre.';
        $legendaDemonstrativo = '2 - Essa linha será apresentada somente no Demonstrativo aplicado aos Estados.';

        $this->oPdf->setfont('arial', '', 6);
        $this->oPdf->Cell(190, 3, $legendaDeficit, 0, 1);
        $this->oPdf->Cell(190, 3, $legendaDemonstrativo, 0, 1);
    }

    /**
     * Imprime as linhas da Receita
     * @param bool $lIntra
     */
    protected function imprimeReceitas($lIntra = false)
    {
        $iInicio = static::LINHARECEITAINICIO;
        $iFim = static::LINHARECEITAFIM;

        if ($lIntra) {
            $iInicio = static::LINHARECEITAINTRAINICIO;
            $iFim = static::LINHARECEITAINTRAFIM;
        }

        for ($i = $iInicio; $i <= $iFim; $i++) {
            if (($i - ($iInicio - 1)) % 35 == 0) {
                $this->imprimeCabecalhoReceita($lIntra);
            }

            $oLinha = $this->aLinhas[$i];
            if (!empty($this->sessao) && !empty($this->sessao['DB_DEBUG'])) {
                if (!empty($oLinha) && !empty($oLinha->descricao)) {
                    $oLinha->descricao = "{$i}) " . $oLinha->descricao;
                }
            }
            $this->imprimeValoresReceitas($oLinha);
        }
    }


    /**
     * Imprime as linhas da despesa
     * @param bool $lIntra
     */
    protected function imprimeDespesas($lIntra = false)
    {

        $iInicio = static::LINHADESPESAINICIO;
        $iFim = static::LINHADEPSESAFIM;

        if ($lIntra) {
            $iInicio = static::LINHADESPESAINTRAINICIO;
            $iFim = static::LINHADESPESAINTRAFIM;
        }

        for ($i = $iInicio; $i <= $iFim; $i++) {
            if (($i - ($iInicio - 1)) % 35 == 0) {
                $this->imprimeCabecalhoDespesa($lIntra);
            }

            $oLinha = $this->aLinhas[$i];
            if (!empty($this->sessao) && !empty($this->sessao['DB_DEBUG'])) {
                if (!empty($oLinha) && !empty($oLinha->descricao)) {
                    $oLinha->descricao = "{$i}) " . $oLinha->descricao;
                }
            }
            $this->imprimeValoresDespesas($oLinha);
        }
    }
}
