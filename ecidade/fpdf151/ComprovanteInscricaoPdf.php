<?php

use ECidade\Educacao\MatriculaOnline\Repository\ConfiguracaoRepository;
use ECidade\Educacao\MatriculaOnline\Service\ConfiguracaoComprovanteInscricaoService;

class ComprovanteInscricaoPdf extends FpdfMultiCellBorder
{
    /**
     * @var ConfiguracaoComprovanteInscricaoService
     */
    protected $serviceConfiguracao;

    /**
     * ComprovanteInscricaoPdf constructor.
     * @param string $orientation
     * @param string $unit
     * @param string $format
     */
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
        $this->exibeHeader(true);
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->Open();
        $this->SetAutoPageBreak(true, 15);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->SetFont('Arial', '', 8);
        $this->AddPage();
        $this->serviceConfiguracao = new ConfiguracaoComprovanteInscricaoService();
    }

    function Header()
    {
        global $conn;
        global $result;
        global $url;

        $rs = db_query(
            "select nomeinst, munic, ed260_c_nome, logo
               from db_config
               join censouf on censouf.ed260_c_sigla = uf
              where codigo = ".db_getsession("DB_instit")
        );
        $dados = pg_fetch_array($rs);

        $this->SetFont('Arial', 'B', 8);
        $logo = 'imagens/files/' . $dados['logo'];
        if (empty($logo)) {
            $logo = 'imagens/logo_matricula_online.jpg';
        }

        $this->Image($logo, 7, 4, 20);
        $this->SetXY(30, 10);
        $this->Cell(190, 4, $dados['ed260_c_nome'], 0, 1);
        $this->SetXY(30, 14);
        $this->Cell(190, 4, $dados['nomeinst'] . " - Matrícula Online", 0, 1);
        $this->SetXY(10, 20);

        $configuracaoRepository = new ConfiguracaoRepository();
        $configuracaoModel = $configuracaoRepository->get();
        $tituloComprovanteInscricao = $configuracaoModel->getTituloComprovante();

        $this->Cell(190, 4, $tituloComprovanteInscricao, 0, 1, 'C');

        $this->SetXY(10, 35);
        $this->SetFont('Arial', '', 8);
    }
}
