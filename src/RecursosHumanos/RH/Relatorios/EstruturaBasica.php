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

namespace ECidade\RecursosHumanos\RH\Relatorios;

/**
 * Class EstruturaBasica
 * @package ECidade\RecursosHumanos\RH\Relatorios
 */
class EstruturaBasica
{
    /**
     * @var \PDFDocument
     */
    protected $pdf;

    /**
     * @var string
     */
    protected $titulo1Relatorio = 'APURAÇÃO COLABORADOR';

    /**
     * @var string
     */
    protected $titulo2Relatorio = '';

    /**
     * @var \Servidor[]
     */
    protected $servidores = array();

    /**
     * @var integer
     */
    protected $localTrabalho;

    /**
     * @var int
     */
    protected $alturaLinha = 4;

    /**
     * @var \DBDate
     */
    protected $dataInicial;

    /**
     * @var \DBDate
     */
    protected $dataFinal;

    /**
     * @var \DBDate[]
     */
    protected $datasIntervaloInicioFim;

    protected $filtro = '';

    /**
     * EstruturaBasica constructor.
     */
    public function __construct()
    {
        $this->pdf = new \PDFDocument();
    }

    public function setDataInicial(\DBDate $dataInicial)
    {
        $this->dataInicial = $dataInicial;
    }

    public function setDataFinal(\DBDate $dataFinal)
    {
        $this->dataFinal = $dataFinal;
    }

    public function setMatriculas(array $matriculas)
    {
        if (empty($matriculas)) {
            throw new \ParameterException("Matrículas não informadas.");
        }

        foreach ($matriculas as $matricula) {
            $this->servidores[] = \ServidorRepository::getInstanciaByCodigo($matricula, null, null, 18);
        }
    }

    public function setSelecao($selecao)
    {
        if (empty($selecao)) {
            throw new \ParameterException('Nenhuma seleção informada.');
        }

        $this->servidores = \ServidorRepository::getServidoresBySelecao(
          \DBPessoal::getAnoFolha(),
          \DBPessoal::getMesFolha(),
          $selecao
        );
    }

    public function setLocalTrabalho($localTrabalho)
    {
        if (empty($localTrabalho)) {
            throw new \ParameterException('Nenhum local de trabalho informado.');
        }

        $this->localTrabalho = $localTrabalho;
        $this->servidores = \ServidorRepository::getServidoresByLocalTrabalho(
          \DBPessoal::getAnoFolha(),
          \DBPessoal::getMesFolha(),
          $localTrabalho
        );
    }

    public function imprimir()
    {
        $this->datasIntervaloInicioFim = \DBDate::getDatasNoIntervalo($this->dataInicial, $this->dataFinal);

        $this->cabecalhoPadrao();
        $this->pdf->Open();
        $this->pdf->setFontSize(8);
        $this->montarConteudo();
        $this->imprimirDados();
        $this->pdf->Output();
    }

    public function impressaoParcial()
    {
        $this->datasIntervaloInicioFim = \DBDate::getDatasNoIntervalo($this->dataInicial, $this->dataFinal);
        $this->cabecalhoParcial();
        $this->pdf->setFontSize(8);
        $this->montarConteudo();
        $this->imprimirDados();
    }

    public function cabecalhoPadrao()
    {
        if (empty($this->dataInicial) || empty($this->dataFinal)) {
            throw new \ParameterException('Período de datas não informado.');
        }

        $periodo = "Período: {$this->dataInicial->getDate(\DBDate::DATA_PTBR)}";
        $periodo .= " à {$this->dataFinal->getDate(\DBDate::DATA_PTBR)}";

        $this->pdf->clearHeaderDescription();
        $this->pdf->addHeaderDescription($this->titulo1Relatorio);
        $this->pdf->addHeaderDescription($this->titulo2Relatorio);
        $this->pdf->addHeaderDescription($periodo);

        if (!empty($this->localTrabalho)) {
            $this->filtro .= "({$this->localTrabalho})";
        }

        $this->pdf->addHeaderDescription("Filtro: {$this->filtro}");
    }

    public function cabecalhoParcial()
    {
        if (empty($this->dataInicial) || empty($this->dataFinal)) {
            throw new \ParameterException('Período de datas não informado.');
        }

        $periodo = "Período: {$this->dataInicial->getDate(\DBDate::DATA_PTBR)}";
        $periodo .= " à {$this->dataFinal->getDate(\DBDate::DATA_PTBR)}";

        $this->pdf->clearHeaderDescription();
        $this->pdf->addHeaderDescription("APURAÇÃO COLABORADOR");
        $this->pdf->addHeaderDescription($periodo);

        if (!empty($this->localTrabalho)) {
            $this->filtro .= "({$this->localTrabalho})";
        }

        $this->pdf->addHeaderDescription("Filtro: {$this->filtro}");
    }

    public function montarConteudo()
    {
    }

    public function imprimirDados()
    {
    }

    protected function getPdf()
    {
        return $this->pdf;
    }

    protected function setPdf($pdf)
    {
        $this->pdf = $pdf;
    }

    protected function getAlturaLinha()
    {
        return $this->alturaLinha;
    }

    protected function getDatasIntervalo()
    {
        return $this->datasIntervaloInicioFim;
    }
}
