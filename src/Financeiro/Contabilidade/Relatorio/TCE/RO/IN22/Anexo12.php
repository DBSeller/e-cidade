<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

use ECidade\Library\SpreadSheet\Template\Parser;
use DBDate;
use Periodo;

/**
 * Class Anexo1
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22
 */
class Anexo12 extends Base implements In22
{

    /**
     * Código do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 227;

    /**
     * @var string
     */
    const TEMPLATE = 'config/templates/IN22/anexo12.xlsx';

    /**
     * @var \PDFDocument
     */
    protected $pdf;

    /**
     * @var \RelatoriosLegaisBase
     */
    protected $relatorioLegal;

    /**
     * @var \stdClass[]
     */
    protected $linhas;


    /**
     * Anexo1 constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return \stdClass[]
     */
    protected function processarRelatorioLegal()
    {
        $periodo = new Periodo($this->codigoPeriodo);
        $this->relatorioLegal = new \RelatoriosLegaisBase($this->ano, self::CODIGO_RELATORIO, $this->codigoPeriodo);
        $this->relatorioLegal->setInstituicoes(implode(',', $this->instituicoes));
        $this->relatorioLegal->setDataInicialPeriodo($periodo->getDataInicial($this->ano));
        $this->relatorioLegal->setDataInicial($this->relatorioLegal->getDataInicialPeriodo());

        $this->linhas = $this->relatorioLegal->getDados();
        foreach ($this->linhas as $ordem => $stdLinha) {
            $stdLinha->valor = 0;
            if (!empty($stdLinha->ano)) {
                $stdLinha->valor = round(($stdLinha->mes/$stdLinha->ano) * 100, 2);
            }
        }
        return $this->linhas;
    }


    /**
     * @return mixed|string
     * @throws \ParameterException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function processar()
    {
        $this->processarRelatorioLegal();

        $periodo = $this->relatorioLegal->getPeriodo();
        $mesExtenso = \DBDate::getMesExtenso($periodo->getMesInicial());

        $parser = new Parser();
        $parser->loadXLS(self::TEMPLATE);
        $parser->addVariable('data_emissao', $this->getDataEmissao()->getDate(DBDate::DATA_PTBR));
        $parser->addVariable('mes_emissao', $mesExtenso);
        $parser->addVariable('ano_emissao', $this->ano);
        $parser->addCollection('iterar_dados', $this->processarDados());
        $parser->addCollection('valor_total', $this->processarTotalizador());
        $parser->parse();
        $path = 'tmp/Anexo12_'.date('d-m-Y', db_getsession('DB_datausu')).'.xlsx';
        $parser->save($path);
        return $path;
    }

    /**
     * Processa os dados do relatório.
     * @return \stdClass[]
     */
    private function processarDados()
    {
        $dadosImpressao = array();
        foreach ($this->linhas as $linha) {
            if ($linha->ordem == 14) {
                continue;
            }
            $dadosImpressao[] = (object)array(
                'descricao' => $linha->descricao,
                'mes' => $this->formataValor($linha->mes),
                'ano' => $this->formataValor($linha->ano),
                'percentual' => $linha->valor,
            );
        }
        return $dadosImpressao;
    }

    /**
     * Processa a linha totalizadora
     * @return object
     */
    private function processarTotalizador()
    {
        return array((object)array(
            'descricao' => $this->linhas[14]->descricao,
            'mes' => $this->formataValor($this->linhas[14]->mes),
            'ano' => $this->formataValor($this->linhas[14]->ano),
            'percentual' => $this->linhas[14]->valor,
        ));
    }
}
