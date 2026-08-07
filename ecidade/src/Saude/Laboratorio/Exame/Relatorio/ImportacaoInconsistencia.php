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

namespace ECidade\Saude\Laboratorio\Exame\Relatorio;

use JSON;
use PDFDocument;

/**
 * Class ImportacaoInconsistencia
 * @package ECidade\Saude\Laboratorio\Exame\Relatorio
 */
class ImportacaoInconsistencia
{
    /**
     * @var PDFDocument
     */
    private $pdf;

    /**
     * @var null|string
     */
    private $caminhoArquivo;

    /**
     * @var bool
     */
    private $buscarRegistros;

    /**
     * @var array
     */
    private $dadosJson;

    /**
     * @var array
     */
    private $dadosImpressao = array();

    /**
     * @var int
     */
    private $tamanhoLinha = 192;

    /**
     * @var int
     */
    private $alturaLinha = 4;

    private $laboratorioAtual;

    private $setorAtual;

    private $requisicaoAtual;

    private $exameAtual;

    /**
     * ImportacaoInconsistencia constructor.
     * @param PDFDocument $pdf
     * @param null $caminhoArquivo
     * @param bool $buscarRegistros
     */
    public function __construct(PDFDocument $pdf, $caminhoArquivo = null, $buscarRegistros = true)
    {
        $this->pdf = $pdf;
        $this->caminhoArquivo = $caminhoArquivo;
        $this->buscarRegistros = $buscarRegistros;
    }

    public function imprimir()
    {
        $this->addHeaders();
        $this->pdf->Open();

        $this->addPage();
        $this->setFont();

        $this->estruturarDados();
        $this->imprimirDados();

        $this->pdf->showPDF();
    }

    private function addPage()
    {
        $this->pdf->AddPage();
    }

    /**
     * @param $dados array
     */
    public function setDadosRelatorio($dados)
    {
         $this->dadosJson = $dados;
    }

    /**
     * @param string $fonte
     * @param string $estilo
     * @param int $tamanho
     */
    private function setFont($fonte = 'arial', $estilo = '', $tamanho = 8)
    {
        $this->pdf->setFontFamily($fonte);
        $this->pdf->setBold(!empty($estilo));
        $this->pdf->SetFontSize($tamanho);
    }

    private function converterJsonParaObjeto()
    {
        $conteudoArquivo = file_get_contents($this->caminhoArquivo);
        $this->dadosJson = JSON::create()->parse($conteudoArquivo, JSON::UTF8_DECODE, true, true);
    }

    private function estruturarDados()
    {
        if (!empty($this->caminhoArquivo)) {
            $this->converterJsonParaObjeto();
        }

        foreach ($this->dadosJson as $requisicao) {
            foreach ($requisicao as $codigoRequisicao => $dadosRequisicao) {
                foreach ($dadosRequisicao['exames'] as $dadosExame) {
                    $this->adicionarLaboratorio($dadosExame);
                    $this->adicionarSetor($dadosExame);
                    $this->adicionarRequisicao($dadosExame, $codigoRequisicao);
                    $this->adicionarExame($dadosExame, $codigoRequisicao);
                    $this->adicionarAtributos($dadosExame, $codigoRequisicao);
                }
            }
        }
    }

    private function adicionarLaboratorio($dadosExame)
    {
        $laboratorio = $dadosExame['codigoLaboratorio'];

        if (!array_key_exists($laboratorio, $this->dadosImpressao)) {
            $dadosLaboratorio = array();
            $dadosLaboratorio['codigoLaboratorio'] = $laboratorio;
            $dadosLaboratorio['nomeLaboratorio'] = $dadosExame['nomeLaboratorio'];
            $dadosLaboratorio['setores'] = array();

            $this->dadosImpressao[$laboratorio] = $dadosLaboratorio;
        }
    }

    private function adicionarSetor($dadosExame)
    {
        $laboratorio = $dadosExame['codigoLaboratorio'];
        $setor = $dadosExame['codigoSetor'];

        $setores = $this->dadosImpressao[$laboratorio]['setores'];

        if (!array_key_exists($setor, $setores)) {
            $dadosSetor = array();
            $dadosSetor['codigoSetor'] = $setor;
            $dadosSetor['nomeSetor'] = $dadosExame['nomeSetor'];
            $dadosSetor['requisicoes'] = array();

            $this->dadosImpressao[$laboratorio]['setores'][$setor] = $dadosSetor;
        }
    }

    private function adicionarRequisicao($dadosExame, $requisicao)
    {
        $laboratorio = $dadosExame['codigoLaboratorio'];
        $setor = $dadosExame['codigoSetor'];

        $laboratorioSetor = $this->dadosImpressao[$laboratorio]['setores'][$setor];

        if (!array_key_exists($requisicao, $laboratorioSetor['requisicoes'])) {
            $dadosRequisicao = array();
            $dadosRequisicao['exames'] = array();

            $laboratorioSetor['requisicoes'][$requisicao] = $dadosRequisicao;

            $this->dadosImpressao[$laboratorio]['setores'][$setor] = $laboratorioSetor;
        }
    }

    private function adicionarExame($dadosExame, $requisicao)
    {
        $laboratorio = $dadosExame['codigoLaboratorio'];
        $setor = $dadosExame['codigoSetor'];
        $exame = $dadosExame['codigoExame'];

        $dadosRequisicao = $this->dadosImpressao[$laboratorio]['setores'][$setor]['requisicoes'][$requisicao];

        if (!array_key_exists($exame, $dadosRequisicao['exames'])) {
            $novosDadosExame = array();
            $novosDadosExame['codigoExame'] = $exame;
            $novosDadosExame['nomeExame'] = $dadosExame['nomeExame'];
            $novosDadosExame['atributos'] = array();

            $dadosRequisicao['exames'][$exame] = $novosDadosExame;

            $this->dadosImpressao[$laboratorio]['setores'][$setor]['requisicoes'][$requisicao] = $dadosRequisicao;
        }
    }

    private function adicionarAtributos($dadosExame, $requisicao)
    {
        $laboratorio = $dadosExame['codigoLaboratorio'];
        $setor = $dadosExame['codigoSetor'];
        $exame = $dadosExame['codigoExame'];

        $dadosRequisicao = $this->dadosImpressao[$laboratorio]['setores'][$setor]['requisicoes'][$requisicao];
        $dadosRequisicao['exames'][$exame]['atributos'] = $dadosExame['atributos'];

        $this->dadosImpressao[$laboratorio]['setores'][$setor]['requisicoes'][$requisicao] = $dadosRequisicao;
    }

    private function setFillColor($rgb)
    {
        $this->pdf->SetFillColor($rgb);
    }

    private function addHeaders()
    {
        $this->pdf->addHeaderDescription('Importação de Resultados - Inconsistências');
        $this->pdf->addHeaderDescription('');
        $this->pdf->addHeaderDescription('Atributos obrigatórios não preenchidos');
    }

    private function imprimirDados()
    {
        foreach ($this->dadosImpressao as $laboratorio) {
            $this->laboratorioAtual = $laboratorio;
            $this->imprimirLaboratorio($laboratorio);

            foreach ($laboratorio['setores'] as $setor) {
                $this->setorAtual = $setor;
                $this->imprimirSetor($setor);

                foreach ($setor['requisicoes'] as $requisicao => $requisicaoExames) {
                    $this->requisicaoAtual = $requisicao;
                    $this->imprimirRequisicao($requisicao);

                    foreach ($requisicaoExames['exames'] as $exame) {
                        $this->exameAtual = $exame;
                        $this->imprimirExame($exame);

                        $arrayAtributos = array();

                        foreach ($exame['atributos'] as $atributo) {
                            $arrayAtributos[] = $atributo['nome'];
                        }

                        $this->imprimirAtributos($arrayAtributos);
                        $this->pdf->Ln(4);
                    }

                    $this->pdf->Ln(4);
                }
            }
        }
    }

    private function imprimirLaboratorio($laboratorio)
    {
        $linha = "{$laboratorio['codigoLaboratorio']} - {$laboratorio['nomeLaboratorio']}";

        $this->setFillColor(215);
        $this->pdf->setBold(true);
        $this->pdf->Cell($this->tamanhoLinha, $this->alturaLinha, $linha, 1, 1, PDFDocument::ALIGN_LEFT, 1);
    }

    private function imprimirSetor($setor)
    {
        $linha = " Setor: {$setor['nomeSetor']}";
        $linha = str_repeat(' ', 4) . $linha;

        $this->setFillColor(235);
        $this->pdf->setBold(true);
        $this->pdf->Cell($this->tamanhoLinha, $this->alturaLinha, $linha, 'TB', 1, PDFDocument::ALIGN_LEFT, 1);
    }

    private function imprimirRequisicao($requisicao)
    {
        $linha = " Requisição: {$requisicao}";
        $linha = str_repeat(' ', 8) . $linha;

        $this->pdf->setBold(true);
        $this->pdf->Cell($this->tamanhoLinha, $this->alturaLinha, $linha, 'T', 1, PDFDocument::ALIGN_LEFT);
    }

    private function imprimirExame($exame)
    {
        $linha = " - {$exame['nomeExame']}";
        $linha = str_repeat(' ', 15) . $linha;

        $this->pdf->SetFontSize(7);
        $this->pdf->setBold(false);
        $this->pdf->Cell($this->tamanhoLinha, $this->alturaLinha, $linha, '', 1, PDFDocument::ALIGN_LEFT);
    }

    private function imprimirAtributos($arrayAtributos)
    {
        $linha = implode(' | ', $arrayAtributos);

        $this->pdf->SetX(22);
        $this->pdf->SetFontSize(7);
        $this->pdf->setBold(false);
        $this->pdf->setItalic(true);
        $this->pdf->MultiCell($this->tamanhoLinha - 10, $this->alturaLinha, $linha, '', PDFDocument::ALIGN_LEFT);
        $this->pdf->setItalic(false);
    }
}
