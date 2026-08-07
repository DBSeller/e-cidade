<?php

namespace App\Domain\Financeiro\Orcamento\Relatorios;

use App\Domain\Financeiro\Planejamento\Relatorios\CotasDespesaPdf;

class AcompanhamentoCotasDespesaPdf extends CotasDespesaPdf
{
    /**
     * @var mixed
     */
    protected $exercicio;

    public function setDados(array $dados)
    {
        $this->dados = $dados;

        $this->exercicio = $this->dados['filtros']['exercicio'];
        $this->porBimestre = $this->dados['filtros']['periodicidade'] === 'bimestral';
        $this->agrupadorSelecionado = $this->dados['filtros']['agruparPor'];

        $this->wValores = $this->wValor * 7;
        $this->wTitulo = $this->wLinha - $this->wValores;
        $this->wCodigo = $this->agrupadorSelecionado === 'elemento' ? 20 : 9;
        $this->wDescricao = $this->wTitulo - $this->wCodigo;
        $this->yCapa = 90;
    }

    public function emitir()
    {
        $this->headers('ACOMPANHAMENTO DAS COTAS MENSAIS DA DESPESA');
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
        $this->addTitulo($titulo);

        $this->addTitulo('Art. 13, da Lei Complementar 101/2000');
        $this->addTitulo("Exercício: {$this->exercicio}");
        $this->addTitulo(sprintf("Agrupado por: %s", $this->agrupadores[$this->agrupadorSelecionado]));

        $periocidade = "Periodicidade: Mensal";
        if ($this->porBimestre) {
            $periocidade = "Periodicidade: Bimestral";
        }
        $this->addTitulo($periocidade);
    }

    protected function capa($titulo)
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 28);

        $this->SetY($this->yCapa);
        $wCapa = $this->wCapa-60;
        $this->SetX(60);
        $this->MultiCell($wCapa, 12, $titulo, 0, 'R');
        $this->ln(4);
        $this->SetFont('Arial', 'B', 21);
        $this->SetX(60);
        $this->MultiCell($wCapa, 12, "ACOMPANHAMENTO DAS COTAS MENSAIS DA DESPESA", 0, 'R');
        $this->SetX(60);
        $this->Cell($wCapa, 12, "EXERCÍCIO {$this->exercicio}", 0, 1);
    }
}
