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

namespace ECidade\RecursosHumanos\ESocial\Model\Arquivo\QualificacaoCadastral;
use ECidade\RecursosHumanos\ESocial\Repository\QualificacaoCadastral as QualificacaoCadastralRepository;
use ECidade\RecursosHumanos\ESocial\Model\QualificacaoCadastral as QualificacaoCadastralModel;
use \PDF;

/**
 * Gera o arquivo de qualificações cadastrais
 */
class Geracao
{   
    private $qualificacoesCadastrais = array();
    private $arquivos = array();
    private $arquivo;
    private $inconsistencias = array();

    public function __construct($selecao = null, $matriculas = array())
    {   
        $qualificacaoCadastralRepository = new QualificacaoCadastralRepository();

        if (!empty($selecao)) {
            $this->qualificacoesCadastrais = $qualificacaoCadastralRepository->buscarServidoresPorSelecao($selecao);
        } else {
            $this->qualificacoesCadastrais = $qualificacaoCadastralRepository->buscarServidoresPorMatriculas($matriculas);
        }     
    }

    /**
     * Gera o arquivo com os dados da qualificação cadastral e os adiciona no tmp, retornando
     * o nome do arquivo
     * @return array
     */
    public function gerarArquivo()
    {   
        if (empty($this->qualificacoesCadastrais)) {
            return $this->arquivos;
        }

        $this->escreverArquivo();
        $this->escreverArquivoInconsistencias();
        return $this->arquivos;
    }

    /**
     * Escreve o PDF contendo as inconsistências dos dados da qualificação cadastral
     */
    private function escreverArquivoInconsistencias() 
    {
        if (empty($this->inconsistencias)) {
            return;
        }

        $caminhoLog = "tmp/inconsistencias_qualificacao_cadastral_".time().".pdf";
        
        global $head1, $head3;
        $head1 = "RELATÓRIO DE INCONSISTÊNCIAS - QUALIFICAÇÃO CADASTRAL";
        $head3 = "Data de emissão: " . date('d/m/Y');
        
        $pdf = new \pdf();
        $pdf->Open();
        $pdf->AliasNbPages();
        $pdf->Setfillcolor(235);
        $pdf->SetAutoPageBreak(false);
        $preencher = true;
        $this->escreverCabecalhoArquivoInconsistencias($pdf);

        foreach ($this->inconsistencias as $inconsistencia) {
            
            if ( $pdf->GetY() > $pdf->h - 25) {
                $this->escreverCabecalhoArquivoInconsistencias($pdf);
            }
            $preencher = !$preencher;
            $pdf->Cell(20, 4, $inconsistencia->matricula, 0, 0, 'R', $preencher);
            $pdf->Cell(60, 4, $inconsistencia->nome, 0, 0, 'L', $preencher);
            $pdf->Cell(0,  4, $inconsistencia->mensagem, 0, 1, 'L', $preencher);
        }        
        $pdf->Cell(0, 4, "", "T", 0, 0);
        $pdf->Output($caminhoLog, false, true);
        $this->adicionarArquivo($caminhoLog, "Arquivo de inconsistências");
    }

    /**
     * Escreve o cabecalho do PDF de inconsistências dos dados da qualificação cadastral
     * @param \pdf $pdf 
     */
    private function escreverCabecalhoArquivoInconsistencias($pdf)
    {
        $pdf->AddPage();
        $pdf->SetFont('arial', 'b', 6);
        $pdf->Cell(20, 4, "Matrícula", "TRB", 0, 'C');
        $pdf->Cell(60, 4, "Nome", "TRB", 0, 'C');
        $pdf->Cell(0, 4, "Mensagem", "TLB", 1, 'C');
        $pdf->SetFont('arial', '', 6);
    }

    /**
     * Escreve o arquivo TXT contendo os dados da qualificação cadastral
     */
    private function escreverArquivo()
    {
        $caminhoArquivo = "tmp/qualificacao_cadastral_".time().".txt";
        $this->arquivo = fopen($caminhoArquivo,"w");
        $this->processar();
        fclose($this->arquivo);
        $this->adicionarArquivo($caminhoArquivo, "Qualificação cadastral");
    }

    /**
     * Adiciona arquivos gerados
     * @param string $caminhoArquivo 
     * @param string $nomeArquivo 
     */
    private function adicionarArquivo($caminhoArquivo, $nomeArquivo)
    {
        $stdArquivo = new \stdClass();
        $stdArquivo->caminho = $caminhoArquivo;
        $stdArquivo->nome = $nomeArquivo;
        $this->arquivos[] = $stdArquivo;
    }

    /**
     * Processa os dados da qualificação cadastral, validando sua consistência e os adicionando
     * em um arquivo
     */
    private function processar() 
    {
        foreach ($this->qualificacoesCadastrais as $qualificacaoCadastral) {
            if (!$this->validar($qualificacaoCadastral)) {
                continue;
            }
            $this->escreverLinha($qualificacaoCadastral);
        }
    }

    /**
     * Escreve uma linha no arquivo
     * @param QualificacaoCadastralModel $qualificacaoCadastral 
     */
    private function escreverLinha(QualificacaoCadastralModel $qualificacaoCadastral) 
    {   
        $nome = \DBString::removerAcentuacao($qualificacaoCadastral->getNome());
        $dataNascimento = str_replace( "/", "", $qualificacaoCadastral->getDataNascimento());

        $dados = array(
            $qualificacaoCadastral->getCPF(),
            $qualificacaoCadastral->getNIS(),
            $nome,
            $dataNascimento
        );
        fwrite($this->arquivo, implode(";", $dados));
        fwrite($this->arquivo, "\n");
    }

    /**
     * Valida os dados para montar a linha do arquivo
     * @param QualificacaoCadastralModel $qualificacaoCadastral 
     * @return boolean
     */
    private function validar(QualificacaoCadastralModel $qualificacaoCadastral)
    {       
        $stdInconsistencia = new \stdClass();
        $stdInconsistencia->matricula = $qualificacaoCadastral->getMatricula();
        $stdInconsistencia->nome = $qualificacaoCadastral->getNome();
        $qualificacaoValida = true;

        if ($qualificacaoCadastral->getCPF() == null) {
            $this->adicionarInconsistencia($qualificacaoCadastral, "CPF não preenchido.");
            $qualificacaoValida = false;
        }

        if (!\DBString::isCPF($qualificacaoCadastral->getCPF())) {
            $this->adicionarInconsistencia($qualificacaoCadastral, "CPF inválido.");
            $qualificacaoValida = false;
        }

        if ($qualificacaoCadastral->getNIS() == null) {
            $this->adicionarInconsistencia($qualificacaoCadastral, "NIS não preenchido.");
            $qualificacaoValida = false;
        }

        if (!\DBString::isPIS($qualificacaoCadastral->getNIS())) {
            $this->adicionarInconsistencia($qualificacaoCadastral, "NIS inválido.");
            $qualificacaoValida = false;
        }

        if (strlen($qualificacaoCadastral->getNome()) > 60) {
            $this->adicionarInconsistencia($qualificacaoCadastral, "Nome com mais de 60 caracteres.");
            $qualificacaoValida = false;
        }
        return $qualificacaoValida;
    }

    /**
     * Adiciona uma inconsistência nos dados da qualificação cadastral
     * @param QualificacaoCadastralModel $qualificacaoCadastral 
     * @param string $mensagem
     */
    private function adicionarInconsistencia($qualificacaoCadastral, $mensagem)
    {
        $stdInconsistencia = new \stdClass();
        $stdInconsistencia->matricula = $qualificacaoCadastral->getMatricula();
        $stdInconsistencia->nome = $qualificacaoCadastral->getNome();
        $stdInconsistencia->mensagem = $mensagem;
        $this->inconsistencias[] = $stdInconsistencia;
    }
}
