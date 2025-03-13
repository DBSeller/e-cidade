<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios;

class CotasDespesaPdf extends Pdf
{
    protected $wLinha = 279;

    protected $wValor = 23;

    protected $fonte = 7;

    protected $alturaLinha = 5;

    protected $yCapa = 105;
    protected $wCapa = 270;

    protected $porBimestre = true;
    /**
     * @var string
     */
    protected $agrupadorSelecionado = '';

    protected $agrupadores = [
        'orgao' => 'Orgão',
        'unidade' => 'Unidade',
        'funcao' => 'Função',
        'subfuncao' => 'Subfunção',
        'programa' => 'Programa',
        'iniciativa' => 'Projeto/Atividade',
        'elemento' => 'Elemento',
        'recurso' => 'Recurso',
    ];
    /**
     * tamanho reservado para o código do agrupador
     * @var int
     */
    protected $wCodigo;
    /**
     * tamanho reservado para descrição do agrupador
     * @var float|int
     */
    protected $wDescricao;


    public function __construct($orientation = 'L', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
    }

    public function setDados(array $dados)
    {
        parent::setDados($dados);

        $this->porBimestre = $this->dados['filtros']['periodicidade'] === 'bimestral';
        $this->agrupadorSelecionado = $this->dados['filtros']['agruparPor'];
    }

    public function emitir()
    {
        $this->headers('COTAS MENSAIS DA DESPESA');
        $this->capa('COTAS MENSAIS DA DESPESA');

        $this->imprimeCotas();

        $this->imprimeFonteNotas();

        $filename = sprintf('tmp/cotas-despesa-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    public function headers($titulo)
    {
        parent::headers($titulo);

        $this->addTitulo('Art. 8º, da Lei Complementar 101/2000');
        $this->addTitulo(sprintf("Agrupado por: %s", $this->agrupadores[$this->agrupadorSelecionado]));

        $periocidade = "Periodicidade: Mensal";
        if ($this->porBimestre) {
            $periocidade = "Periodicidade: Bimestral";
        }
        $this->addTitulo($periocidade);

        $this->wValores = $this->wValor * 7;
        $this->wTitulo = $this->wLinha - $this->wValores;
        $this->wCodigo = $this->agrupadorSelecionado === 'elemento' ? 20 : 9;
        $this->wDescricao = $this->wTitulo - $this->wCodigo;
    }

    protected function imprimeFonteNotas()
    {
        if (!empty($this->dados['fonte'])) {
            $this->imprimeTexto($this->dados['fonte'], $this->fonte);
        }

        if (!empty($this->dados['notaExplicativa'])) {
            $this->imprimeTexto($this->dados['notaExplicativa'], $this->fonte);
        }
    }

    protected function imprimeTexto($texto, $fonteSize)
    {
        $this->SetFont('Arial', '', $fonteSize);
        $linhas = $this->NbLines($this->wLinha, $texto);
        if ($this->getAvailHeight() < ($this->alturaLinha * $linhas)) {
            $this->AddPage();
        }
        $this->MultiCell($this->wLinha, $this->alturaLinha, $texto);
    }

    protected function imprimeCotas()
    {
        if ($this->porBimestre) {
            $this->imprimeModeloBimestral();
        } else {
            $this->imprimeModeloMensal();
        }

        $this->imprimeTotalizador();
    }

    protected function imprimeModeloBimestral()
    {
        $this->cabecalhoBimestral();
        foreach ($this->dados['dados'] as $dado) {
            if ($this->getAvailHeight() < $this->alturaLinha) {
                $this->cabecalhoBimestral();
            }

            $this->Cell($this->wCodigo, $this->alturaLinha, $dado->codigo, 'TBR', 0, 'C');
            $this->cellAdapt($this->fonte, $this->wDescricao, $this->alturaLinha, $dado->descricao, 1, 0, 'L');
            $this->imprimeLinhaValor($dado->bimestre_1);
            $this->imprimeLinhaValor($dado->bimestre_2);
            $this->imprimeLinhaValor($dado->bimestre_3);
            $this->imprimeLinhaValor($dado->bimestre_4);
            $this->imprimeLinhaValor($dado->bimestre_5);
            $this->imprimeLinhaValor($dado->bimestre_6);
            $this->imprimeLinhaValor($dado->valor, 'BTL', $this->alturaLinha, 1);
        }
    }

    protected function imprimeModeloMensal()
    {
        $this->cabecalhoMensal();
        $h = $this->alturaLinha * 2;
        foreach ($this->dados['dados'] as $dado) {
            if ($this->getAvailHeight() < $this->alturaLinha) {
                $this->cabecalhoMensal();
            }
            $yInicial = $this->GetY();
            $this->Cell($this->wCodigo, $h, $dado->codigo, 'TBR', 0, 'C');
            $this->cellAdapt($this->fonte, $this->wDescricao, $h, $dado->descricao, 1, 0, 'L');

            $this->imprimeLinhaValor($dado->janeiro);
            $this->imprimeLinhaValor($dado->fevereiro);
            $this->imprimeLinhaValor($dado->marco);
            $this->imprimeLinhaValor($dado->abril);
            $this->imprimeLinhaValor($dado->maio);
            $this->imprimeLinhaValor($dado->junho, 1, $this->alturaLinha, 1);
            $this->SetX(10 + $this->wTitulo);
            $this->imprimeLinhaValor($dado->julho);
            $this->imprimeLinhaValor($dado->agosto);
            $this->imprimeLinhaValor($dado->setembro);
            $this->imprimeLinhaValor($dado->outubro);
            $this->imprimeLinhaValor($dado->novembro);
            $this->imprimeLinhaValor($dado->dezembro);

            $this->SetXY(($this->wLinha - $this->wValor) + 10, $yInicial);
            $this->imprimeLinhaValor($dado->valor, 'TBL', $h, 1);
        }
    }

    protected function imprimeLinhaValor($valor, $b = 1, $hLinha = 5, $ln = 0)
    {
        $this->cellAdapt($this->fonte, $this->wValor, $hLinha, formataValorMonetario($valor), $b, $ln, 'R');
    }


    protected function imprimeBimestres()
    {
        $this->Cell($this->wValor, $this->alturaLinha, '1º Bimestre', 1, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, '2º Bimestre', 1, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, '3º Bimestre', 1, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, '4º Bimestre', 1, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, '5º Bimestre', 1, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, '6º Bimestre', 1, 'C');
    }

    protected function imprimeMeses()
    {
        $wReceita = $this->wLinha - $this->wValores;
        $this->Cell($this->wValor, $this->alturaLinha, 'Janeiro', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Fevereiro', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Março', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Abril', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Maio', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Junho', 1, 1, 'C');

        $this->SetX(10 + $wReceita);

        $this->Cell($this->wValor, $this->alturaLinha, 'Julho', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Agosto', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Setembro', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Outubro', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Novembro', 1, 0, 'C');
        $this->Cell($this->wValor, $this->alturaLinha, 'Dezembro', 1, 1, 'C');
    }

    protected function cabecalhoBimestral()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', $this->fonte);

        $agrupado = $this->agrupadores[$this->agrupadorSelecionado];
        $this->Cell($this->wCodigo, $this->alturaLinha, 'Cód.', 'TBR', 0, 'C');
        $this->Cell($this->wDescricao, $this->alturaLinha, $agrupado, 1, 0, 'C');
        $this->imprimeBimestres();
        $this->Cell($this->wValor, $this->alturaLinha, 'Total', 'TBL', 1, 'C');
        $this->SetFont('Arial', '', $this->fonte);
    }

    protected function cabecalhoMensal()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', $this->fonte);

        $h = $this->alturaLinha * 2;

        $agrupado = $this->agrupadores[$this->agrupadorSelecionado];
        $this->Cell($this->wCodigo, $h, 'Cód.', 'TBR', 0, 'C');
        $this->Cell($this->wDescricao, $h, $agrupado, 1, 0, 'C');
        $yInicial = $this->GetY();

        $this->imprimeMeses();

        $this->SetXY(($this->wLinha - $this->wValor) + 10, $yInicial);
        $this->Cell($this->wValor, $h, 'Total', 'TBL', 1, 'C');

        $this->SetFont('Arial', '', $this->fonte);
    }

    protected function imprimeTotalizador()
    {
        if ($this->getAvailHeight() < $this->alturaLinha) {
            $this->AddPage();
        }

        $this->SetFont('Arial', 'B', $this->fonte);

        if ($this->porBimestre) {
            $this->imprimeTotalizadorBimestral();
        } else {
            $this->imprimeTotalizadorMensal();
        }
        $this->SetFont('Arial', '', $this->fonte);
    }

    protected function imprimeTotalizadorBimestral()
    {
        $this->Cell($this->wTitulo, $this->alturaLinha, 'Total Geral', 'TBR', 0, 'L');
        $this->imprimeLinhaValor($this->dados['totalizador']->bimestre_1);
        $this->imprimeLinhaValor($this->dados['totalizador']->bimestre_2);
        $this->imprimeLinhaValor($this->dados['totalizador']->bimestre_3);
        $this->imprimeLinhaValor($this->dados['totalizador']->bimestre_4);
        $this->imprimeLinhaValor($this->dados['totalizador']->bimestre_5);
        $this->imprimeLinhaValor($this->dados['totalizador']->bimestre_6);
        $this->imprimeLinhaValor($this->dados['totalizador']->valor, 'BTL', $this->alturaLinha, 1);
    }

    protected function imprimeTotalizadorMensal()
    {
        $h = $this->alturaLinha * 2;
        $yInicial = $this->GetY();
        $this->Cell($this->wTitulo, $h, 'Total Geral', 'TBR', 0, 'L');
        $this->imprimeLinhaValor($this->dados['totalizador']->janeiro);
        $this->imprimeLinhaValor($this->dados['totalizador']->fevereiro);
        $this->imprimeLinhaValor($this->dados['totalizador']->marco);
        $this->imprimeLinhaValor($this->dados['totalizador']->abril);
        $this->imprimeLinhaValor($this->dados['totalizador']->maio);
        $this->imprimeLinhaValor($this->dados['totalizador']->junho, 1, $this->alturaLinha, 1);
        $this->SetX(10 + $this->wTitulo);
        $this->imprimeLinhaValor($this->dados['totalizador']->julho);
        $this->imprimeLinhaValor($this->dados['totalizador']->agosto);
        $this->imprimeLinhaValor($this->dados['totalizador']->setembro);
        $this->imprimeLinhaValor($this->dados['totalizador']->outubro);
        $this->imprimeLinhaValor($this->dados['totalizador']->novembro);
        $this->imprimeLinhaValor($this->dados['totalizador']->dezembro);

        $this->SetXY(($this->wLinha - $this->wValor) + 10, $yInicial);
        $this->imprimeLinhaValor($this->dados['totalizador']->valor, 'TBL', $h, 1);
    }
}
