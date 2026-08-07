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

namespace ECidade\Saude\Laboratorio\Exame\ColetaAmostra\Relatorio;

use DateTime;
use RequisicaoExame;
use RequisicaoLaboratorial;
use scpdf;
use stdClass;

/**
 * Class Modelo3
 * @package ECidade\Saude\Laboratorio\Exame\ColetaAmostra\Relatorio
 */
class Modelo3
{
    /**
     * @var RequisicaoLaboratorial
     */
    private $requisicao;

    /**
     * @var array
     */
    private $codigosExames = array();

    /**
     * @var scpdf
     */
    private $pdf;

    /**
     * @var int
     */
    private $tamanhoFonte = 8;

    /**
     * @var int
     */
    private $tamanhoFonteNomeNumero = 10;

    /**
     * @var int
     */
    private $alturaLinha = 3.5;

    /**
     * @var int
     */
    private $tamanhoLinha = 78;

    /**
     * @var int
     */
    private $posicaoCodigoBarras = 22;

    /**
     * @var int
     */
    private $margemXInicial = 3.5;

    /**
     * @var bool
     */
    private $borda = false;

    /**
     * @var RequisicaoExame
     */
    private $requisicaoExame;

    /**
     * Modelo3 constructor.
     * @param RequisicaoLaboratorial $requisicaoLaboratorial
     * @param scpdf $scpdf
     */
    public function __construct(RequisicaoLaboratorial $requisicaoLaboratorial, scpdf $scpdf)
    {
        $this->requisicao = $requisicaoLaboratorial;
        $this->pdf = $scpdf;
        $this->pdf->Open();
        $this->pdf->AliasNbPages();
        $this->pdf->setfillcolor(243);
        $this->pdf->SetAutoPageBreak(false);
    }

    /**
     * @param $codigoExame
     */
    public function adicionarExame($codigoExame, $dataColetaExame)
    {
        if (!in_array($codigoExame, $this->codigosExames)) {
            $this->codigosExames[$codigoExame] = array(
                "dataColetaExame" => $dataColetaExame
            );
        }
    }

    /**
     * @throws \Exception
     */
    public function imprimir()
    {
        $this->pdf->addpage('P');
        $this->setFonte();

        $this->imprimirEtiquetaPaciente();
        $this->imprimirExames();

        $this->pdf->Output();
    }

    private function setFonte($fonteNomeNumero = false)
    {
        $this->pdf->setfont('arial', 'B', !$fonteNomeNumero ? $this->tamanhoFonte : $this->tamanhoFonteNomeNumero);
    }

    /**
     * @throws \Exception
     */
    private function imprimirEtiquetaPaciente()
    {
        $this->gerarCodigoBarras(true);
        $this->imprimirDadosPaciente(true);
    }

    /**
     * @param bool $primeiraEtiqueta
     * @param null $codigoMaterialColeta
     * @throws \Exception
     */
    private function gerarCodigoBarras($primeiraEtiqueta = false, $codigoMaterialColeta = null, $setor = null)
    {
        $numeroCodigoBarras = str_pad($this->requisicao->getCodigo(), 12, '0', STR_PAD_LEFT);

        if (!$primeiraEtiqueta) {
            $requisicaoExame = $this->requisicaoExame;
            $numeroCodigoBarras = $requisicaoExame->getNumeroCodigoBarras($codigoMaterialColeta, $setor);
        }

        $this->setFonte();
        $this->pdf->SetFillColor(000);
        $this->pdf->int25($this->tamanhoLinha/3.7, 2, $numeroCodigoBarras, 13, 0.41);

        $this->pdf->SetXY($this->margemXInicial, 16);
        $this->setFonte(true);
        $this->pdf->Cell($this->tamanhoLinha, $this->alturaLinha, $numeroCodigoBarras, $this->borda, 1, 'C');

        $this->setFonte();
    }

    private function imprimirDadosPaciente($etiquetaPaciente = false)
    {
        $dadosPaciente = $this->dadosPaciente();

        $this->pdf->SetX($this->margemXInicial);
        $this->setFonte(true);
        $this->pdf->Cell($this->tamanhoLinha, $this->alturaLinha, $dadosPaciente->nome, $this->borda, 1, 'C');
        $this->setFonte();
        
        if ($etiquetaPaciente) {
            $linhaIdade = $this->tamanhoLinha;
            $this->pdf->SetX($this->margemXInicial);
            $this->pdf->Cell($linhaIdade, $this->alturaLinha, "Idade: " . $dadosPaciente->idade, $this->borda, 0, 'C');
        } else {
            $linhaIdade = $this->tamanhoLinha/2;
            $this->pdf->SetX($this->margemXInicial);
            $this->pdf->Cell($linhaIdade, $this->alturaLinha, "Idade: " . $dadosPaciente->idade, $this->borda, 0, 'R');
        }
    }

    private function dadosPaciente()
    {
        $cgs = $this->requisicao->getCgs();

        $dataAtual = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $dataNascimento = DateTime::createFromFormat('Y-m-d', $cgs->getDataNascimento()->getDate());

        $dadosPaciente = new stdClass();
        $dadosPaciente->nome = $cgs->getNome();
        $dadosPaciente->idade = str_pad($dataAtual->diff($dataNascimento)->y, 3, '0', STR_PAD_LEFT) . 'A';
        $dadosPaciente->id = str_pad($cgs->getIdentidade(), 11, '0', STR_PAD_LEFT);
        return $dadosPaciente;
    }

    /**
     * @throws \Exception
     */
    private function imprimirExames()
    {
        $examesMaterial = $this->organizarExamesMaterial();

        foreach ($examesMaterial as $codigoMaterial => $exameMaterial) {
            $this->pdf->addpage('P');

            $setores = explode('#', $codigoMaterial);
            $this->gerarCodigoBarras(false, $setores[0], $setores[1]);

            $dataColetaExame = $exameMaterial->dataColetaExame;
            $this->imprimirDadosPaciente(false);
            if ($dataColetaExame) {
                $this->pdf->Cell(
                    $this->tamanhoLinha/2,
                    $this->alturaLinha,
                    "Data Coleta: " . date("d/m/y", strtotime($dataColetaExame)),
                    $this->borda,
                    1,
                    'L'
                );
            } else {
                $this->pdf->Cell($this->tamanhoLinha/2, $this->alturaLinha, "Não Coletado", $this->borda, 1, 'L');
            }
            

            $this->setFonte();
            $this->pdf->SetX($this->margemXInicial);

            $siglas = implode(' ', $exameMaterial->siglasExames);
            $this->pdf->Cell($this->tamanhoLinha, $this->alturaLinha, $siglas, $this->borda, 1, 'C');

            $this->pdf->SetX($this->margemXInicial);
            $this->pdf->Cell(
                $this->tamanhoLinha,
                $this->alturaLinha,
                $exameMaterial->descricaoMaterial,
                $this->borda,
                1,
                'C'
            );
        }
    }

    /**
     * @return array
     * @throws \DBException
     * @throws \Exception
     */
    private function organizarExamesMaterial()
    {
        $examesMaterial = array();
        
        $requisicoesExame = $this->requisicao->getRequisicoesDeExames();

        foreach ($requisicoesExame as $requisicaoExame) {
            if (!array_key_exists($requisicaoExame->getCodigo(), $this->codigosExames)) {
                continue;
            }
            
            $dataColetaExame = $this->codigosExames[$requisicaoExame->getCodigo()]['dataColetaExame'];
            $setor = $requisicaoExame->getLaboratorioSetor()->getCodigo();
            $materiasColeta = $requisicaoExame->getExame()->getMaterialColeta();

            if (count($materiasColeta) === 0) {
                $mensagem  = " Impressão de etiqueta não permitida. Exame sem material de coleta cadastrado";
                $mensagem .= " ({$requisicaoExame->getExame()->getNome()}). Acesse:";
                $mensagem .= " SAÚDE > Laboratório > Cadastros > Exame > Alteração > aba Material de coleta";

                throw new \Exception($mensagem);
            }

            $this->requisicaoExame = $requisicaoExame;

            foreach ($materiasColeta as $material) {
                $chavePorData = $dataColetaExame ? "#" . $dataColetaExame : "";
                $chaveCodigoColetaSetor = $material->codigo_material_coleta . "#" . $setor . $chavePorData;
                if (!isset($examesMaterial[$chaveCodigoColetaSetor]) ||
                ($examesMaterial[$chaveCodigoColetaSetor]->dataColetaExame != $dataColetaExame &&
                $examesMaterial[$chaveCodigoColetaSetor]->dataColetaExame != null)) {
                    $examesMaterial[$chaveCodigoColetaSetor] = new stdClass();
                    $examesMaterial[$chaveCodigoColetaSetor]->siglasExames = array();
                }
                
                $exameMaterial = $examesMaterial[$chaveCodigoColetaSetor];
                $exame = $requisicaoExame->getExame();
                
                $exameMaterial->descricaoMaterial = $material->material_coleta;
                $exameMaterial->siglasExames[$exame->getCodigo()] = $exame->getSigla();
                $exameMaterial->dataColetaExame = $dataColetaExame;
                
                $examesMaterial[$chaveCodigoColetaSetor] = $exameMaterial;
            }
        }
        return $examesMaterial;
    }
}
