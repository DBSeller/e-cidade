<?php
/**
 * Created by PhpStorm.
 * User: robson
 * Date: 2020-02-05
 * Time: 16:36
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

use db_utils;
use DBDate;
use ECidade\Library\SpreadSheet\Template\Parser;
use Exception;
use Periodo;

/**
 * Class Anexo10
 * @package ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22
 */
class Anexo11 extends Base implements In22
{
    const CODIGO_RELATORIO = 238;
    const TEMPLATE = 'config/templates/IN22/anexo11.xlsx';

    /**
     * @return mixed|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws Exception
     */
    public function processar()
    {
        $parser = new Parser();
        $parser->loadXLS(self::TEMPLATE);
        $parser->addVariable('ano', $this->ano);

        $dados = $this->processarDados();

        $totalInscito1 = 0;
        $totalInscito2 = 0;
        foreach ($dados as $dado) {
            if ($dado->tipo == 1) {
                $totalInscito1 += $dado->valorinscrito;
            } else {
                $totalInscito2 += $dado->valorinscrito;
            }
            $dado->valorinscrito = $this->formataValor($dado->valorinscrito);
        }

        $total = $totalInscito1 + $totalInscito2;

        $totalInscito1 = $this->formataValor($totalInscito1);
        $totalInscito2 = $this->formataValor($totalInscito2);
        $total = $this->formataValor($total);

        $parser->addCollection('dados', $dados);

        $parser->addVariable('totalvalorinscritotipo1', $totalInscito1);
        $parser->addVariable('totalvalorinscritotipo2', $totalInscito2);
        $parser->addVariable('totalvalorinscrito', $total);

        $parser->addVariable('data_emissao', $this->getDataEmissao()->getDate(DBDate::DATA_PTBR));

        $parser->parse();
        $path = 'tmp/anexo11.xlsx';
        $parser->save($path);
        return $path;
    }

    /**
     * @return array
     * @throws \BusinessException
     */
    private function processarDados()
    {
        $periodo = new \Periodo($this->codigoPeriodo);

        $dataInicial = "{$this->ano}-01-01";
        $dataFinal = $periodo->getDataFinal($this->ano);
        $whereFiltro = $this->getFiltrosConfigurados();

        $campoProjeto = "lpad(o58_orgao::varchar, 2 , '0') || '.'||lpad(o58_unidade::varchar, 2 , '0') || '.' ||";
        $campoProjeto .= "lpad(o58_funcao::varchar, 3, '0') || '.'|| ";
        $campoProjeto .= "lpad(o58_subfuncao::varchar, 3, '0') || '.' || lpad(o58_programa, 4,  '0') || '.' ||";
        $campoProjeto .= "lpad(o58_projativ::varchar, 4, '0')||'.'||o56_elemento||'.'||o15_codigo ";
        $campoProjeto .= "as funcionalprogramatica";

        $campoConta = "db89_db_bancos||' '||db89_codagencia||'-'||db89_digito||' '||db83_conta||'-'||db83_dvconta";
        $contaCorrente = "array_to_string(array_accum(DISTINCT {$campoConta}), ', ') AS contacorrente";

        $campos = [
            "e150_numeroprocesso as processo",
            "z01_nome as fornecedor",
            "e60_codemp||'/'||e60_anousu::text as empenho",
            $campoProjeto,
            "to_char(max(c70_data), 'DD/MM/YYYY') as datapagamento",
            $contaCorrente,
            "(case when substr(o56_elemento, 1, 3)  = '331' then 1 else 2 end) as tipo",
            "e60_numemp",
        ];
        $valoresInscritoResto = [
            "round(e91_vlremp-e91_vlranu-e91_vlrpag, 2) AS valorinscrito",
            "round(coalesce(sum(CASE
                WHEN c53_tipo = 31 THEN c70_valor * -1
                WHEN c53_tipo = 30 THEN c70_valor ELSE 0 END), 0), 2) AS valor_pago",
            "round(coalesce(sum(CASE
                WHEN c53_coddoc = 31 THEN c70_valor
                WHEN c53_coddoc = 32 THEN c70_valor ELSE 0 END), 0), 2) AS valor_anulado",
        ];

        $valoresEmpenhoAno = [
            "round(coalesce(sum(
              case when c53_tipo = 11 then c70_valor * -1
                   when c53_tipo = 10 then c70_valor else 0 end), 0), 2) as valor_empenhado",
            "round(coalesce(sum(
              case when c53_tipo = 31 then c70_valor * -1
                   when c53_tipo = 30 then c70_valor else 0 end), 0), 2) as valor_pago",
        ];

        $camposResto = array_merge($campos, $valoresInscritoResto);
        $camposAno = array_merge($campos, $valoresEmpenhoAno);

        $where = [
            "c70_data between '{$dataInicial}' and '{$dataFinal->getDate()}'",
            "c70_anousu = {$this->ano}",
            "e60_instit in (" . implode(",", $this->instituicoes) . ")",
        ];

        $whereResto = $where;
        $whereResto[] = "e91_anousu = {$this->ano}";
        $whereResto[] = "(c53_tipo in(30, 31) or c53_coddoc in (31, 32))";

        $whereAno = $where;
        $whereAno[] = "e60_anousu = {$this->ano}";
        $whereAno[] = "c53_tipo in (10, 11, 30, 31)";

        $groupBy = [
            "e150_numeroprocesso",
            "o58_orgao",
            "o58_unidade",
            "o58_funcao",
            "o58_subfuncao",
            "o58_programa",
            "o58_projativ",
            "o56_elemento",
            "o15_codigo",
            "e60_codemp",
            "e60_anousu",
            "z01_nome",
            "e60_codemp",
            "e60_anousu",
            "e60_numemp",
        ];

        $groupByRestos = array_merge($groupBy, ["e91_vlremp", "e91_vlranu", "e91_vlrpag"]);

        $daoConlancamemp = new \cl_conlancamemp();
        $sqlRestos = $daoConlancamemp->sql_query_dados_empenho(
            implode(", ", $camposResto),
            implode(" and ", $whereResto) . $whereFiltro,
            "",
            implode(', ', $groupByRestos),
            true,
            $this->ano
        );

        $sqlRP = "
        SELECT processo,
               fornecedor,
               empenho,
               funcionalprogramatica,
               datapagamento,
               contacorrente,
               sum(x.valorinscrito - x.valor_anulado - x.valor_pago) AS valorinscrito,
               tipo,
               e60_numemp
          FROM ({$sqlRestos})  AS x
         GROUP BY processo, fornecedor, empenho, funcionalprogramatica, datapagamento, contacorrente, tipo, e60_numemp
        ";

        $sqlEmpenhosAno = $daoConlancamemp->sql_query_dados_empenho(
            implode(", ", $camposAno),
            implode(" and ", $whereAno) . $whereFiltro,
            "",
            implode(', ', $groupBy)
        );

        $sqlEmpenhos = "
        select processo,
               fornecedor,
               empenho,
               funcionalprogramatica,
               contacorrente,
               datapagamento,
               sum(x.valor_empenhado - x.valor_pago) as valorinscrito,
               tipo,
               e60_numemp
          from ($sqlEmpenhosAno) as x
         GROUP BY processo, fornecedor, empenho, funcionalprogramatica, datapagamento, contacorrente, tipo, e60_numemp
        ";

        $sql = "select * from ({$sqlRP} union all {$sqlEmpenhos}) as y where valorinscrito > 0 order by e60_numemp";

        $rsDados = db_query($sql);
        $dados = db_utils::getCollectionByRecord($rsDados);
        return $dados;
    }
}
