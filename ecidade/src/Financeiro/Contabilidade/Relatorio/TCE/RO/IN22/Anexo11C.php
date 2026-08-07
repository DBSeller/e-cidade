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
use Exception;
use Periodo;
use RelatoriosLegaisBase;

/**
 * Class Anexo10
 * @package ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22
 */
class Anexo11C extends Base implements In22
{
    const CODIGO_RELATORIO = 240;
    const TEMPLATE = 'config/templates/IN22/anexo11_C.xlsx';

    //linhas totalizadoras
    const LINHA_4 = 2;
    const LINHA_5 = 6;
    const LINHA_6 = 7;
    const LINHA_6_3 = 10;
    const LINHA_7 = 18;
    const LINHA_9 = 20;

    const LINHA_3 = 1; // 3.SALDO DO EXERCÍCIO ANTERIOR
    const LINHA_8 = 19; // 8. SALDO FINANCEIRO EXISTENTE NAS CONTAS DO FUNDEB

    /**
     * Diminuir o valor das linha : LINHA_4_2 e LINHA_4_3
     */
    const LINHA_4_1 = 3;

    // linhas de receita
    const LINHA_4_2 = 4;
    const LINHA_4_3 = 5;

    // linhas de empenho
    const LINHA_6_1 = 8;
    const LINHA_6_2 = 9;
    const LINHA_6_3_1 = 11;
    const LINHA_6_3_2 = 12;
    const LINHA_6_4 = 13;
    const LINHA_6_5 = 14;
    const LINHA_6_6 = 15;
    const LINHA_6_7 = 16;
    const LINHA_6_8 = 17;

    private $linhasRecalcular = [
        self::LINHA_4_2,
        self::LINHA_4_3,
        self::LINHA_6_1,
        self::LINHA_6_2,
        self::LINHA_6_3_1,
        self::LINHA_6_3_2,
        self::LINHA_6_4,
        self::LINHA_6_5,
        self::LINHA_6_6,
        self::LINHA_6_7,
    ];

    private $linhasTotalizadorasSoma = [
        self::LINHA_4 => [
            self::LINHA_4_1,
            self::LINHA_4_2,
            self::LINHA_4_3,
        ],
        self::LINHA_5 => [self::LINHA_3, self::LINHA_4],
        self::LINHA_6_3 => [self::LINHA_6_3_1, self::LINHA_6_3_2],
        self::LINHA_6 => [
            self::LINHA_6_1,
            self::LINHA_6_2,
            self::LINHA_6_4,
            self::LINHA_6_5,
            self::LINHA_6_6,
            self::LINHA_6_7,
            self::LINHA_6_8,
        ],
    ];

    /**
     * Linhas do relatorioLegal
     * @var array
     */
    private $linhas;


    /**
     * Contem as linhas já formatadas para parse no template de ordem 1 a 20
     * @var array
     */
    private $linhasConta = [];
    /**
     * Contem as linhas já formatadas para parse no template de ordem 21 a 22
     * @var array
     */
    private $linhasFundeb = [];

    /**
     * Totalizador da linha: 12. TOTAL (10 + 11 )
     */
    private $totalFundeb = 0;

    /**
     * Valor da linha:
     * 13. Despesas com Educação de Jovens e Adultos (Máximo de 10% dos recursos do Fundeb, conforme definição em Lei
     * @var float
     */
    private $valorLinha13 = 0;

    /**
     * Valor da linha
     * 10. Remuneração dos Profissionais do Magistério-(Mínimo de 60% do item 4)
     * @var float
     */
    private $valorLinha10 = 0;
    /**
     * Valor da linha
     * 11. Despesas diversas com recursos do Fundeb -(Máxima de 40% do item 4)
     * @var float
     */
    private $valorLinha11 = 0;
    /**
     * @var Periodo
     */
    private $periodo;


    /**
     * @return mixed|void
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function processar()
    {
        $this->periodo = new Periodo($this->codigoPeriodo);

        $this->processarDados();
        $this->processarLinhas();

        $parser = new Parser();
        $parser->loadXLS(self::TEMPLATE);
        $parser->addVariable('ano', $this->ano);

        $parser->addCollection('iterar_dadosconta', $this->linhasConta);

        $parser->addVariable('valorlinha10', $this->formataValor($this->valorLinha10));
        $parser->addVariable('valorlinha11', $this->formataValor($this->valorLinha11));
        $parser->addVariable('totalfundeb', $this->formataValor($this->totalFundeb));

        $parser->addVariable('valorlinha13', $this->formataValor($this->valorLinha13));

        $parser->addVariable('data_emissao', $this->getDataEmissao()->getDate(DBDate::DATA_PTBR));

        $parser->parse();
        $path = 'tmp/anexo11_C.xlsx';
        $parser->save($path);
        return $path;
    }

    /**
     * @throws Exception
     */
    private function processarDados()
    {
        $dataFinal = Periodo::dataFinalPeriodo($this->codigoPeriodo, $this->ano);

        $this->relatorioLegal = new RelatoriosLegaisBase($this->ano, self::CODIGO_RELATORIO, $this->codigoPeriodo);
        $this->relatorioLegal->setInstituicoes(implode(',', $this->instituicoes));

        $linhas = $this->relatorioLegal->getDados();
        $dataInicio = "{$this->ano}-01-01";

        $filtroPadrao = [
            "c69_data between '{$dataInicio}' and  '{$dataFinal->getDate()}'",
            "c61_anousu = {$this->ano}"
        ];

        foreach ($linhas as $linha) {
            if (!in_array($linha->ordem, $this->linhasRecalcular)) {
                continue;
            }

            $tipo = 2; // 1 - buscar valores da orcrecita | 2 - empempenho
            $rp = false;
            switch ((int)$linha->ordem) {
                case self::LINHA_4_2:
                    $tipo = 1;
                    $filtros = ["substr(o57_fonte,1,8) = '41321001'", "c53_tipo = 100", "c69_debito = c61_reduz"];
                    break;
                case self::LINHA_4_3:
                    $tipo = 1;
                    $filtros = ["substr(o57_fonte,1,8) = '41718091'", "c53_tipo = 100", "c69_debito = c61_reduz"];
                    break;
                case self::LINHA_6_1:
                    $rp = true;
                    $filtros = [
                        "e91_anousu = {$this->ano}",
                        "c53_tipo = 30",
                        "c69_credito = c61_reduz",
                        "o58_codigo = 31"
                    ];
                    break;
                case self::LINHA_6_2:
                    $rp = true;
                    $filtros = [
                        "e91_anousu = {$this->ano}",
                        "c53_tipo = 30",
                        "c69_credito = c61_reduz",
                        "o58_codigo != 31",
                    ];
                    break;
                case self::LINHA_6_3_1:
                    $filtros = [
                        "c53_tipo = 30",
                        "c69_credito = c61_reduz",
                        "e60_anousu = {$this->ano}",
                        "o58_subfuncao = 365",
                        "o58_codigo = 31",
                        "o58_coddot in (594)"
                    ];
                    break;
                case self::LINHA_6_3_2:
                    $filtros = [
                        "c53_tipo = 30",
                        "c69_credito = c61_reduz",
                        "e60_anousu = {$this->ano}",
                        "o58_subfuncao = 365",
                        "o58_codigo = 31",
                        "o58_coddot in (584,582,586)"
                    ];
                    break;
                case self::LINHA_6_4:
                    $filtros = [
                        "c53_tipo = 30",
                        "c69_credito = c61_reduz",
                        "e60_anousu = {$this->ano}",
                        "o58_subfuncao = 361",
                        "o58_codigo = 31"
                    ];
                    break;
                case self::LINHA_6_5:
                    $filtros = [
                        "c53_tipo = 30",
                        "c69_credito = c61_reduz",
                        "e60_anousu = {$this->ano}",
                        "o58_subfuncao = 362",
                        "o58_codigo = 31"
                    ];
                    break;
                case self::LINHA_6_6:
                    $filtros = [
                        "c53_tipo = 30",
                        "c69_credito = c61_reduz",
                        "e60_anousu = {$this->ano}",
                        "o58_subfuncao = 367",
                        "o58_codigo = 31"
                    ];
                    break;
                case self::LINHA_6_7:
                    $filtros = [
                        "c53_tipo = 30",
                        "c69_credito = c61_reduz",
                        "e60_anousu = {$this->ano}",
                        "o58_subfuncao = 366",
                        "o58_codigo = 31"
                    ];
                    break;
            }

            $filtros = array_merge($filtros, $filtroPadrao);
            $linha->valor = $this->recalculaLinha($tipo, $linha, $filtros, $rp);
        }

        $linhas[self::LINHA_6_8]->valor = $this->calculaLinhaOutros($linhas[self::LINHA_6_8], $filtroPadrao);

        // processa totalizadores
        $linhas[self::LINHA_4_1]->valor -= $linhas[self::LINHA_4_2]->valor;
        $linhas[self::LINHA_4_1]->valor -= $linhas[self::LINHA_4_3]->valor;

        foreach ($this->linhasTotalizadorasSoma as $ordem => $linhasSomar) {
            $linhas[$ordem]->valor = 0;

            foreach ($linhasSomar as $linhaSomar) {
                $linhas[$ordem]->valor += $linhas[$linhaSomar]->valor;
            }
        }

        $linhas[self::LINHA_7]->valor = $linhas[self::LINHA_5]->valor - $linhas[self::LINHA_6]->valor;
        $linhas[self::LINHA_9]->valor = $linhas[self::LINHA_7]->valor - $linhas[self::LINHA_8]->valor;

        $this->linhas = $linhas;
    }

    /**
     * @param integer $tipo
     * @param $linha
     * @param array $filtros
     * @param bool $rp
     * @return int
     */
    private function recalculaLinha($tipo, $linha, array $filtros, $rp = false)
    {
        $where = $filtros;
        $recurso = $linha->parametros->orcamento->recurso;

        if (!empty($recurso->valor)) {
            $recursos = implode(', ', $recurso->valor);
            $where[] = "c61_codigo {$recurso->operador} ($recursos)";
        }

        $valor = 0;
        foreach ($linha->parametros->contas as $conta) {
            $where['estrutural'] = "c60_estrut like substr('{$conta->estrutural}', 1, {$conta->nivel}) || '%'";

            if ($tipo == 1) {
                $sql = $this->queryCorrente();
            } else {
                $sql = $this->queryEmpenho($rp);
            }

            $sql .= "where " . implode(" and ", $where);
            $rs = db_query($sql);
            $valor += \db_utils::fieldsMemory($rs, 0)->valor;
        }

        return $valor;
    }

    /**
     * @param $linha
     * @param array $filtros
     * @return float
     */
    private function calculaLinhaOutros($linha, array $filtros)
    {
        $filtros[] = "c69_credito = c61_reduz";
        $where1 = $filtros;
        $where2 = $filtros;
        $recurso = $linha->parametros->orcamento->recurso;

        if (!empty($recurso->valor)) {
            $recursos = implode(', ', $recurso->valor);
            $where[] = "c61_codigo {$recurso->operador} ($recursos)";
        }

        $valor = 0;
        foreach ($linha->parametros->contas as $conta) {
            $estrutural = "c60_estrut like substr('{$conta->estrutural}', 1, {$conta->nivel}) || '%'";
            $where1[] = $estrutural;
            $where1[] = "e60_anousu = {$this->ano}";
            $where1[] = "c53_tipo = 30";
            $where1[] = "o58_subfuncao not in (365,361,362,367,366)";
            $where1[] = "o58_codigo = 31";

            $where2[] = $estrutural;
            $where2[] = "c71_coddoc in (120, 140, 151, 161)";
            $sql1 = "
                select coalesce(sum(round(c69_valor,2)), 0) as valor
                  from conlancamval
                       join conlancamemp on c69_codlan = c75_codlan
                       join conlancamdoc on c69_codlan = c71_codlan
                       join conplanoreduz on c61_anousu = c69_anousu
                            and c61_reduz = c69_credito
                       join conplano on c61_anousu = c60_anousu
                            and c60_codcon = c61_codcon
                       join conhistdoc on c71_coddoc = c53_coddoc
                       join empempenho on c75_numemp = e60_numemp
                       join orcdotacao on e60_anousu = o58_anousu and e60_coddot = o58_coddot
            ";
            $sql1 .= " where " . implode(' and ', $where1);

            $sql2 = "
            select coalesce(sum(round(c69_valor,2)), 0) as valor
              from conlancamval
                   join conlancamdoc on c69_codlan = c71_codlan
                   join conhistdoc on c71_coddoc = c53_coddoc
                   join conplanoreduz on c61_anousu = c69_anousu
                            and c61_reduz = c69_credito
                   join conplano on c61_anousu = c60_anousu
                            and c60_codcon = c61_codcon
            ";
            $sql2 .= " where " . implode(" and ", $where2);

            $sql = "
            select sum(valor) as valor
              from (
                {$sql1}
                union all
                {$sql2}
              ) as x
            ";

            $rs = db_query($sql);
            $valor += \db_utils::fieldsMemory($rs, 0)->valor;
        }
        return $valor;
    }

    /**
     * @param bool $rp
     * @return string
     */
    private function queryEmpenho($rp = false)
    {
        $sql = "
            select sum(round(c69_valor,2)) as valor
             from conlancamval
             join conlancamemp on c69_codlan = c75_codlan
             join conlancamdoc on c69_codlan = c71_codlan
             join conplanoreduz on c61_anousu = c69_anousu
                               and ((c61_reduz = c69_credito) or (c61_reduz = c69_debito))
             join conplano on c61_anousu = c60_anousu
                          and c60_codcon = c61_codcon
             join conhistdoc on c71_coddoc = c53_coddoc
             join empempenho on c75_numemp = e60_numemp
             join orcdotacao on e60_anousu = o58_anousu and e60_coddot = o58_coddot
        ";
        if ($rp) {
            $sql .= " join empresto on e60_numemp = e91_numemp ";
        }

        return $sql;
    }

    private function processarLinhas()
    {
        $idLinhasConta = range(1, 20);
        $idLinhasFundeb = range(21, 22);

        foreach ($this->linhas as $linha) {
            if (in_array($linha->ordem, $idLinhasConta)) {
                $this->processarLinhasConta($linha);
            } elseif (in_array($linha->ordem, $idLinhasFundeb)) {
                $this->processarLinhasFundeb($linha);
            } else {
                $this->valorLinha13 = $linha->valor;
            }
        }
    }

    private function processarLinhasConta($linha)
    {
        $this->linhasConta[] = (object)[
            'descricao' => $linha->descricao,
            'valor' => $this->formataValor($linha->valor),
        ];
    }

    private function processarLinhasFundeb($linha)
    {
        $this->totalFundeb += $linha->valor;

        if ($linha->ordem == 21) {
            $this->valorLinha10 = $linha->valor;
        } else {
            $this->valorLinha11 = $linha->valor;
        }
    }

    /**
     * @return string
     */
    private function queryCorrente()
    {
        return "
            select
                   coalesce(sum(round(c69_valor,2)), 0) as valor
              from conlancamval
              join conlancamrec on c69_codlan = c74_codlan
              join conlancamdoc on c69_codlan = c71_codlan
              join conplanoreduz on c61_anousu = c69_anousu
                            and (c61_reduz = c69_debito)
              join conplano on c61_anousu = c60_anousu
                            and c60_codcon = c61_codcon
              join conhistdoc on c71_coddoc = c53_coddoc
              join orcreceita on c74_anousu = o70_anousu and c74_codrec = o70_codrec
              join orcfontes on o70_codfon = o57_codfon and o70_anousu = o57_anousu
        ";
    }
}
