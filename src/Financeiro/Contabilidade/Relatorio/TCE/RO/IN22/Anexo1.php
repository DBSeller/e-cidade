<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

use ECidade\Library\SpreadSheet\Template\Parser;
use Exception;
use Periodo;
use RelatoriosLegaisBase;
use stdClass;

/**
 * Class Anexo1
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22
 */
class Anexo1 extends Base implements In22
{

    /**
     * Código do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 206;

    /**
     * @var string
     */
    const TEMPLATE = 'config/templates/IN22/Anexo1.xlsx';

    /**
     * @var \PDFDocument
     */
    protected $pdf;

    /**
     * @var RelatoriosLegaisBase
     */
    protected $relatorioLegal;

    /**
     * @var stdClass[]
     */
    protected $linhas;


    /**
     * Anexo1 constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    protected function processarRelatorioLegal()
    {
        $periodo = new Periodo($this->codigoPeriodo);
        $this->relatorioLegal = new RelatoriosLegaisBase($this->ano, self::CODIGO_RELATORIO, $this->codigoPeriodo);
        $this->relatorioLegal->setInstituicoes(implode(',', $this->instituicoes));
        $this->relatorioLegal->setDataInicialPeriodo($periodo->getDataInicial($this->ano));
        $this->relatorioLegal->setDataInicial($this->relatorioLegal->getDataInicialPeriodo());

        $this->linhas = $this->relatorioLegal->getDados();

        foreach ($this->linhas as $ordem => $stdLinha) {
            $stdLinha->valor = 0;
            if (!empty($stdLinha->ano)) {
                $valor = ($stdLinha->mes / $stdLinha->ano) * 100;
                $stdLinha->valor = round($valor, 2);
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
        $parser->addVariable('mes_emissao', $mesExtenso);
        $parser->addVariable('ano_emissao', $this->ano);
        $parser->addCollection('iterar_dados', $this->processarDados());

        $total = $this->processarTotalizador();
        $parser->addVariable('descricao_total', $total->descricao_total);
        $parser->addVariable('mes_total', $total->mes_total);
        $parser->addVariable('ano_total', $total->ano_total);
        $parser->addVariable('percentual_total', $total->percentual_total);

        $parser->parse();
        $path = 'tmp/Anexo1_'.date('d-m-Y', db_getsession('DB_datausu')).'.xlsx';
        $parser->save($path);
        return $path;
    }

    /**
     * Processa os dados do relatório.
     * @return stdClass[]
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
        return (object)array(
            'descricao_total' => $this->linhas[14]->descricao,
            'mes_total' => $this->formataValor($this->linhas[14]->mes),
            'ano_total' => $this->formataValor($this->linhas[14]->ano),
            'percentual_total' => $this->linhas[14]->valor,
        );
    }
}
