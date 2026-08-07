<?php
/**
 * Created by PhpStorm.
 * User: robson
 * Date: 2020-02-05
 * Time: 16:36
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

use DBDate;
use ECidade\Library\SpreadSheet\Template\Parser;
use Periodo;
use RelatoriosLegaisBase;

/**
 * Class Anexo10
 * @package ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22
 */
class Anexo11A extends Base implements In22
{
    const CODIGO_RELATORIO = 237;
    const TEMPLATE = 'config/templates/IN22/anexo11_A.xlsx';
    /**
     * @var array
     */
    private $linhas;

    /**
     * @return mixed|void
     * @throws \BusinessException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function processar()
    {
        $this->processarRelatorioLegal();

        $dadosOrigem = $this->processarLinhasOrigem();
        $dadosFundeb = $this->processarLinhasFundeb();

        $parser = new Parser();
        $parser->loadXLS(self::TEMPLATE);
        $parser->addVariable('ano', $this->ano);

        // linhas da origem 1 a 9
        $parser->addCollection('dados', $dadosOrigem);

       // linhas 13 a 16
        $parser->addCollection('dados_fundeb', $dadosFundeb);

        $parser->addVariable('data_emissao', $this->getDataEmissao()->getDate(DBDate::DATA_PTBR));

        $parser->parse();
        $path = 'tmp/anexo11_A.xlsx';
        $parser->save($path);
        return $path;
    }



    /**
     * @return array
     * @throws \BusinessException
     */
    private function processarDados()
    {
    }

    private function processarRelatorioLegal()
    {
        $periodo = new Periodo($this->codigoPeriodo);
        $this->relatorioLegal = new RelatoriosLegaisBase($this->ano, self::CODIGO_RELATORIO, $this->codigoPeriodo);
        $this->relatorioLegal->setInstituicoes(implode(',', $this->instituicoes));
//        $this->relatorioLegal->setDataInicialPeriodo($periodo->getDataInicial($this->ano));
//        $this->relatorioLegal->setDataInicial($this->relatorioLegal->getDataInicialPeriodo());

        $this->linhas = $this->relatorioLegal->getDados();
    }

    /**
     * @return array
     */
    private function processarLinhasOrigem()
    {
        $dados = [
            (object)[
                "descricao" => '',
                "valor" => ''
            ]
        ];

        $ordemLinhas = range(1, 11);
        foreach ($this->linhas as $linha) {
            if (in_array($linha->ordem, $ordemLinhas)) {
                $dados[] = (object)[
                    "descricao" => $linha->descricao,
                    "valor" => $this->formataValor($linha->valor)
                ];
            }
        }
        return $dados;
    }

    /**
     * @return array
     */
    private function processarLinhasFundeb()
    {
        $dados = [];
        $ordemLinhas = range(13, 16);
        foreach ($this->linhas as $linha) {
            if (in_array($linha->ordem, $ordemLinhas)) {
                $percentual = 0;
                if ($linha->rarrec > 0) {
                    $percentual = ($linha->rarrec / $linha->previsao) * 100;
                }

                $dados[] = (object) [
                    "descricao" => $linha->descricao,
                    "previsao" => $this->formataValor($linha->previsao),
                    "arrecadacao" => $this->formataValor($linha->rarrec),
                    "percentual" => round($percentual, 2)
                ];
            }
        }

        return $dados;
    }
}
