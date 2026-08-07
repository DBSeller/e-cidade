<?php


namespace ECidade\Financeiro\Contabilidade\Relatorio\Razao\PorConta;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel extends Relatorio
{

    private $excel;
    /**
     * @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     */
    private $sheet;
    private $row = 0;

    private function nextRow()
    {
        $this->row++;
    }

    private function getRow()
    {
        return $this->row;
    }

    public function __construct()
    {
        $this->excel = new Spreadsheet();
        $this->sheet = $this->excel->getActiveSheet();
    }

    public function writeHeader()
    {
        $this->nextRow();
        $this->sheet->setCellValue("A{$this->getRow()}", "LAN");
        $this->sheet->setCellValue("B{$this->getRow()}", "SEQ");
        $this->sheet->setCellValue("C{$this->getRow()}", "DATA");
        $this->sheet->setCellValue("D{$this->getRow()}", "RECEITA");
        $this->sheet->setCellValue("E{$this->getRow()}", utf8_encode("DOTAÇÃO"));
        $this->sheet->setCellValue("F{$this->getRow()}", "EMPENHO");
        $this->sheet->setCellValue("G{$this->getRow()}", utf8_encode("SUPLEMENTAÇÃO"));
        $this->sheet->setCellValue("H{$this->getRow()}", "DOCUMENTO");
        $this->sheet->setCellValue("I{$this->getRow()}", "SLIP");
        $this->sheet->setCellValue("J{$this->getRow()}", utf8_encode("OP"));
        $this->sheet->setCellValue("k{$this->getRow()}", "PLANILHA");
        $this->sheet->setCellValue("L{$this->getRow()}", "CONTA ORIGEM");
        $this->sheet->setCellValue("M{$this->getRow()}", "CONTRAPARTIDA");
        $this->sheet->setCellValue("N{$this->getRow()}", "VALOR");
        $this->sheet->setCellValue("O{$this->getRow()}", "VALOR TIPO");
        $this->sheet->setCellValue("P{$this->getRow()}", "HISTORICO");
        $this->sheet->setCellValue("Q{$this->getRow()}", "INSTITUICAO");
    }

    public function writeLine(ExcelLinha $linha)
    {
        $this->nextRow();
        $this->sheet->setCellValue("A{$this->getRow()}", utf8_encode($linha->getLancamento()));
        $this->sheet->setCellValue("B{$this->getRow()}", utf8_encode($linha->getSequencial()));
        $this->sheet->setCellValue("C{$this->getRow()}", utf8_encode($linha->getData()));
        $this->sheet->setCellValue("D{$this->getRow()}", utf8_encode($linha->getReceita()));
        $this->sheet->setCellValue("E{$this->getRow()}", utf8_encode($linha->getDotacao()));
        $this->sheet->setCellValue("F{$this->getRow()}", utf8_encode($linha->getEmpenho()));
        $this->sheet->setCellValue("G{$this->getRow()}", utf8_encode($linha->getSuplementacao()));
        $this->sheet->setCellValue("H{$this->getRow()}", utf8_encode($linha->getDocumento()));
        $this->sheet->setCellValue("I{$this->getRow()}", utf8_encode($linha->getSlip()));
        $this->sheet->setCellValue("J{$this->getRow()}", utf8_encode($linha->getOp()));
        $this->sheet->setCellValue("K{$this->getRow()}", utf8_encode($linha->getPlanilha()));
        $this->sheet->setCellValue("L{$this->getRow()}", utf8_encode($linha->getContaOrigem()));
        $this->sheet->setCellValue("M{$this->getRow()}", utf8_encode($linha->getContraPartida()));
        $this->sheet->setCellValue("N{$this->getRow()}", utf8_encode($linha->getValor()));
        $this->sheet->setCellValue("O{$this->getRow()}", utf8_encode($linha->getTipo()));
        $this->sheet->setCellValue("P{$this->getRow()}", utf8_encode($linha->getHistorico()));
        $this->sheet->setCellValue("Q{$this->getRow()}", utf8_encode($linha->getInstituicao()));
    }

    public function writeBody()
    {
        $dados = $this->getComplanoAnalitico();
        foreach ($dados as $dado) {
            $dado = (object)$dado;
            $contas = $this->getDadosGerais($dado->c61_reduz);
            if (!empty($contas)) {
                foreach ($contas as $conta) {
                    $conta = (object)$conta;
                    $excelLinha = new ExcelLinha();
                    $sNumeroEmpenho = "{$conta->e60_codemp}/{$conta->e60_anousu}";
                    if (empty($conta->e60_codemp)) {
                        $sNumeroEmpenho = "";
                    }
                    $excelLinha->setContaOrigem("{$conta->c60_estrut} -  $conta->conta_descr ({$conta->c61_reduz})");
                    $excelLinha->setLancamento($conta->c69_codlan);
                    $excelLinha->setSequencial($conta->c69_sequen);
                    $excelLinha->setData($conta->c69_data);
                    $excelLinha->setReceita($conta->c74_codrec);
                    $excelLinha->setDotacao($conta->c73_coddot);
                    $excelLinha->setEmpenho($sNumeroEmpenho);
                    $excelLinha->setSuplementacao($conta->c79_codsup);
                    $excelLinha->setDocumento(" {$conta->c53_coddoc} - {$conta->c53_descr}");
                    $excelLinha->setTipo($conta->tipo == "D" ? "DEBITO" : "CREDITO");
                    $excelLinha->setValor(db_formatar($conta->c69_valor, 'f'));
                    if ($conta->c61_reduz == $conta->c69_debito) {
                        $contrapartida = "{$conta->credito_estrut} -  {$conta->credito_descr}  ($conta->c69_credito)";
                    } else {
                        $contrapartida = "{$conta->debito_estrut} -  {$conta->debito_descr} ($conta->c69_debito)";
                    }
                    $excelLinha->setContraPartida($contrapartida);

                    $historico = "HISTORICO: {$conta->c50_descr} {$conta->c72_complem} ";

                    if (!empty($conta->z01_numcgm)) {
                        $historico .= " CGM: $conta->z01_numcgm : $conta->z01_nome, ";
                    }

                    if ($conta->c75_numemp != "") {
                        $historico .= $conta->e60_resumo;
                    }

                    $excelLinha->setHistorico($historico);

                    if (!empty($conta->planilha)) {
                        $excelLinha->setPlanilha($conta->planilha);
                    }

                    if (!empty($conta->slip)) {
                        $excelLinha->setSlip($conta->slip);
                    }

                    if (!empty($conta->codigo_movimento)) {
                        $excelLinha->setOp($conta->codigo_movimento);
                    }
                    $excelLinha->setInstituicao("{$dado->codigo} - {$dado->nomeinst}");

                    $this->writeLine($excelLinha);
                }
            }
        }
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function output($nameFile = "file")
    {
        header('Content-Type: application/vnd.ms-excel;charset=ISO-8859-1');
        header('Content-Disposition: attachment;filename="' . $nameFile . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($this->excel, 'Xlsx');
        $writer->save('php://output');
    }

    public function run()
    {
        $this->writeHeader();
        $this->writeBody();
        $this->output("razao_por_conta");
    }
}
