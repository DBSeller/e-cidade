<?php
/**
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

namespace ECidade\Tributario\Arrecadacao\Relatorio;

include_once(modification('libs/db_stdlib.php'));

use PDFDocument;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use InstituicaoRepository;

/**
 * Classe abstrata para relatorios do tributario.
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
abstract class Contract
{
    /** @var int */
    const ALTURA_LINHA = 4;

    /** @var int */
    const TAMANHO_FONTE = 6;

    /** @var PDFDocument */
    protected $pdf;

    /** @var integer */
    private $alturaLinha;

    public function __construct()
    {
        $pdf = new PDFDocument(PDFDocument::PRINT_PORTRAIT);
        $pdf->Open();
        $pdf->setAutoPageBreak(false);
        $pdf->AliasNbPages();
        $pdf->SetFillColor(235);

        $this->pdf = $pdf;
        $this->alturaLinha = static::ALTURA_LINHA;
    }

    public function imprimir($mostrar = true)
    {
        $this->montarEnteFederativo();

        $this->pdf->AddPage();
        $this->pdf->SetFont('arial', '', static::TAMANHO_FONTE);

        $this->montar();

        $this->pdf->Output('', false, !$mostrar);

        return $this->pdf->arquivo_retorno;
    }

    final protected function montarLinha(
        $largura,
        $valor = '',
        $mascaraMonetaria = false,
        $totalizador = false,
        $preenche = false,
        $quebra = false,
        $alinhamento = 'C'
    ) {
        $borda = 'LRB';

        if ($totalizador) {
            $this->pdf->setBold(1);
            $borda .= 'T';
        } else {
            $this->pdf->setBold(0);
        }

        if ($preenche) {
            $borda .= 'B';
        }

        if ($mascaraMonetaria) {
            $alinhamento = 'R';
            $valor = db_formatar($valor, 'f');
        }

        $linhasOcupadas = $this->pdf->NbLines($largura, $valor);

        if ($linhasOcupadas > 1) {
            $y = $this->pdf->GetY();
            $this->pdf->MultiCell($largura, static::ALTURA_LINHA, $valor, $borda, $alinhamento, $preenche);
            $x = 10 + $largura;
            $this->pdf->SetXY($x, $y);

            $this->alturaLinha = static::ALTURA_LINHA * $linhasOcupadas;
        } else {
            $this->pdf->Cell($largura, $this->alturaLinha, $valor, $borda, $quebra, $alinhamento, $preenche);
        }

        if ($quebra) {
            $this->alturaLinha = static::ALTURA_LINHA;
        }

        $this->pdf->setBold(0);

        return $this;
    }

    protected function montarCabecalhoColuna($largura, $altura, $valor, $x = 10)
    {
        $this->pdf->setBold(1);
        $y = $this->pdf->GetY();
        $this->pdf->MultiCell($largura, $altura, $valor, 1, 'C', 1);
        $x += $largura;
        $this->pdf->SetXY($x, $y);
        $this->pdf->setBold(0);

        return $x;
    }

    protected function montarEnteFederativo()
    {
        $prefeitura = InstituicaoRepository::getInstituicaoPrefeitura();
        $enteFederacao = DemonstrativoFiscal::getEnteFederativo($prefeitura);

        $this->pdf->addHeaderDescription($enteFederacao);

        return $this;
    }

    /**
     * @return mixed
     */
    abstract protected function montar();
}
