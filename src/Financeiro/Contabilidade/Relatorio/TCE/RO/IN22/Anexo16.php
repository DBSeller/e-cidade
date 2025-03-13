<?php
/**
 * Created by PhpStorm.
 * User: robson
 * Date: 2020-02-05
 * Time: 16:36
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

use ECidade\Library\SpreadSheet\Template\Parser;

/**
 * Class Anexo2
 * @package ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22
 */
class Anexo16 extends Base implements In22
{
    const CODIGO_RELATORIO = 231;
    const TEMPLATE = 'config/templates/IN22/anexo16.xlsx';

    /**
     * @return mixed|void
     * @throws \BusinessException
     * @throws \ParameterException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function processar()
    {
        $periodo = new \Periodo($this->codigoPeriodo);
        $parser = new Parser();
        $parser->loadXLS(self::TEMPLATE);
        $parser->addVariable('data_emissao', $this->getDataEmissao()->getDate(\DBDate::DATA_PTBR));
        $parser->addVariable('ano', ' ' . $this->ano . ' ');
        $dados = $this->processarDados();
        $parser->addCollection('dados', $dados);
        $totalValorInscrito = 0;
        foreach ($dados as $dado) {
            $totalValorInscrito += $dado->valortotal;
            $dado->valorinscrito = $this->formataValor($dado->valortotal);
        }
        $totalValorInscrito = $this->formataValor($totalValorInscrito);

        $parser->addVariable('total', $totalValorInscrito);
        $parser->addCollection('dados', $dados);
        $parser->parse();
        $path = 'tmp/anexo16.xlsx';
        $parser->save($path);
        return $path;
    }

    /**
     * Processa os dados do
     * @return \stdClass[]
     * @throws \BusinessException
     */
    private function processarDados()
    {
        $periodo = new \Periodo($this->codigoPeriodo);
        $dataInicial = $periodo->getDataInicial($this->ano);
        $dataInicial2 = "{$this->ano}-01-01";
        $dataFinal = $periodo->getDataFinal($this->ano);
        $whereFiltro = $this->getFiltrosConfigurados();
        $where = [
            "c70_data between '{$dataInicial2}' and '{$dataFinal->getDate()}'",
            "c70_anousu = {$this->ano}",
            "e60_instit in (" . implode(",", $this->instituicoes) . ")",
        ];
        $whereRestos = $where;
        $whereEmpenhos = $where;
        $whereRestos [] = "e91_anousu = {$this->ano}";
        $whereRestos [] = "(c53_tipo in(30, 31) or c53_coddoc in (31, 32))";
        $whereEmpenhos[] = "e60_anousu = {$this->ano}";
        $whereEmpenhos[] = "c53_tipo in(30, 31, 10, 11)";

        $whereRestos = implode(" and ", $whereRestos) . $whereFiltro;
        $whereEmpenhos = implode(" and ", $whereEmpenhos) . $whereFiltro;

        $sqlEmpenho = "select * from (
            select
                processo,
                fornecedor,
                empenho,
                funcionalprogramatica,
                contacorrente,
                sum(x.valor_empenhado - x.valor_pago) as valortotal,
                e60_codemp,
                e60_anousu
            from (
                SELECT
                    e150_numeroprocesso AS processo,
                    z01_nome AS fornecedor,
                    e60_codemp || '/' || e60_anousu::text AS empenho,
                    lpad(o58_orgao::varchar, 2 , '0') || '.' || lpad(o58_unidade::varchar, 2 , '0') || '.'
                        || lpad(o58_funcao::varchar, 3, '0') || '.' || lpad(o58_subfuncao::varchar, 3, '0') || '.'
                        || lpad(o58_programa, 4, '0') || '.' || lpad(o58_projativ::varchar, 4, '0') || '.'
                        || substr(o56_elemento, 2, 12) || '.' || o15_codigo AS funcionalprogramatica,
                    array_to_string(array_accum(distinct db89_db_bancos || ' ' || db89_codagencia || '-'
                    || db89_digito || ' ' || db83_conta || '-' || db83_dvconta), ', ') AS contacorrente,
                    round(coalesce(sum(case when c53_tipo = 11 then c70_valor * -1 else (case when
                        c53_tipo = 10 then c70_valor else 0 end) end), 0), 2) as valor_empenhado,
                    round(coalesce(sum(case when c53_tipo = 31 then c70_valor * -1 else (case when
                        c53_tipo = 30 then c70_valor else 0 end) end), 0), 2) as valor_pago,
                    e60_codemp,
                    e60_anousu
                FROM
                    contabilidade.conlancam
                    INNER JOIN contabilidade.conlancamemp ON conlancam.c70_codlan = conlancamemp.c75_codlan
                    INNER JOIN contabilidade.conlancamdoc ON conlancam.c70_codlan = conlancamdoc.c71_codlan
                    INNER JOIN contabilidade.conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc
                    INNER JOIN empenho.empempenho ON c75_numemp = e60_numemp
                    INNER JOIN protocolo.cgm ON z01_numcgm = e60_numcgm
                    INNER JOIN empenho.empempaut ON empempenho.e60_numemp = empempaut.e61_numemp
                    INNER JOIN empenho.empautoriza ON empempaut.e61_autori = empautoriza.e54_autori
                    LEFT JOIN empenho.empautorizaprocesso ON
                        empautoriza.e54_autori = empautorizaprocesso.e150_empautoriza
                    INNER JOIN orcamento.orcdotacao ON empempenho.e60_anousu = orcdotacao.o58_anousu
                        AND empempenho.e60_coddot = orcdotacao.o58_coddot
                    INNER JOIN orcamento.orcorgao ON orcdotacao.o58_anousu = orcorgao.o40_anousu
                        AND orcdotacao.o58_orgao = orcorgao.o40_orgao
                    INNER JOIN orcamento.orcunidade ON orcdotacao.o58_anousu = orcunidade.o41_anousu
                        AND orcdotacao.o58_orgao = orcunidade.o41_orgao
                        AND orcdotacao.o58_unidade = orcunidade.o41_unidade
                    INNER JOIN orcamento.orcprograma ON orcdotacao.o58_anousu = orcprograma.o54_anousu
                        AND orcdotacao.o58_programa = orcprograma.o54_programa
                    INNER JOIN orcamento.orcprojativ ON orcdotacao.o58_anousu = orcprojativ.o55_anousu
                        AND orcdotacao.o58_projativ = orcprojativ.o55_projativ
                    INNER JOIN orcamento.orcfuncao ON orcdotacao.o58_funcao = orcfuncao.o52_funcao
                    INNER JOIN orcamento.orcsubfuncao ON orcdotacao.o58_subfuncao = orcsubfuncao.o53_subfuncao
                    INNER JOIN orcamento.orcelemento ON orcdotacao.o58_codele = orcelemento.o56_codele
                        AND orcdotacao.o58_anousu = orcelemento.o56_anousu
                    INNER JOIN orcamento.orctiporec ON orcdotacao.o58_codigo = orctiporec.o15_codigo
                    LEFT JOIN contabilidade.conlancampag ON conlancam.c70_codlan = conlancampag.c82_codlan
                    LEFT JOIN contabilidade.conplanoreduz ON conlancampag.c82_reduz = conplanoreduz.c61_reduz
                        AND conlancampag.c82_anousu = conplanoreduz.c61_anousu
                    LEFT JOIN contabilidade.conplanocontabancaria ON
                        conplanoreduz.c61_reduz = conplanocontabancaria.c56_reduz
                        AND conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu
                    LEFT JOIN configuracoes.contabancaria ON
                        conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial
                    LEFT JOIN configuracoes.bancoagencia ON contabancaria.db83_bancoagencia = db89_sequencial
                WHERE
                    {$whereEmpenhos}
                GROUP BY
                    e150_numeroprocesso,
                    o58_orgao,
                    o58_unidade,
                    o58_funcao,
                    o58_subfuncao,
                    o58_programa,
                    o58_projativ,
                    o56_elemento,
                    o15_codigo,
                    e60_codemp,
                    e60_anousu,
                    z01_nome,
                    e60_codemp,
                    e60_anousu
                ORDER BY
                    processo
                ) as x
            group by
                processo,
                fornecedor,
                empenho,
                funcionalprogramatica,
                contacorrente,
                e60_codemp,
                e60_anousu";

        $sqlResto = " select
                processo,
                fornecedor,
                empenho,
                funcionalprogramatica,
                contacorrente,
                sum(x.valorinscrito - x.valor_anulado - x.valor_pago) as valortotal,
                e60_codemp,
                e60_anousu
            from (
                SELECT
                    e150_numeroprocesso AS processo,
                    z01_nome AS fornecedor,
                    e60_codemp || '/' || e60_anousu::text AS empenho,
                    lpad(o58_orgao::varchar, 2 , '0') || '.' || lpad(o58_unidade::varchar, 2 , '0') || '.'
                        || lpad(o58_funcao::varchar, 3, '0') || '.' || lpad(o58_subfuncao::varchar, 3, '0') || '.'
                        || lpad(o58_programa, 4, '0') || '.' || lpad(o58_projativ::varchar, 4, '0') || '.'
                        || substr(o56_elemento, 2, 12) || '.' || o15_codigo AS funcionalprogramatica,
                    array_to_string(array_accum(distinct db89_db_bancos || ' ' || db89_codagencia || '-'
                        || db89_digito || ' ' || db83_conta || '-' || db83_dvconta), ', ') AS contacorrente,
                    round(e91_vlremp-e91_vlranu-e91_vlrpag, 2) AS valorinscrito,
                    round(coalesce(sum(case when c53_tipo = 31 then c70_valor * -1 else (case when
                        c53_tipo = 30 then c70_valor else 0 end) end), 0), 2) as valor_pago,
                    round(coalesce(sum(case when c53_coddoc = 31 then c70_valor else (case when
                        c53_coddoc = 32 then c70_valor else 0 end) end), 0), 2) as valor_anulado,
                    e60_codemp,
                    e60_anousu
                FROM
                    contabilidade.conlancam
                    INNER JOIN contabilidade.conlancamemp ON conlancam.c70_codlan = conlancamemp.c75_codlan
                    INNER JOIN contabilidade.conlancamdoc ON conlancam.c70_codlan = conlancamdoc.c71_codlan
                    INNER JOIN contabilidade.conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc
                    INNER JOIN empenho.empempenho ON c75_numemp = e60_numemp
                    INNER JOIN protocolo.cgm ON z01_numcgm = e60_numcgm
                    INNER JOIN empenho.empempaut ON empempenho.e60_numemp = empempaut.e61_numemp
                    INNER JOIN empenho.empautoriza ON empempaut.e61_autori = empautoriza.e54_autori
                    LEFT JOIN empenho.empautorizaprocesso ON
                        empautoriza.e54_autori = empautorizaprocesso.e150_empautoriza
                    INNER JOIN orcamento.orcdotacao ON empempenho.e60_anousu = orcdotacao.o58_anousu
                        AND empempenho.e60_coddot = orcdotacao.o58_coddot
                    INNER JOIN orcamento.orcorgao ON orcdotacao.o58_anousu = orcorgao.o40_anousu
                        AND orcdotacao.o58_orgao = orcorgao.o40_orgao
                    INNER JOIN orcamento.orcunidade ON orcdotacao.o58_anousu = orcunidade.o41_anousu
                        AND orcdotacao.o58_orgao = orcunidade.o41_orgao
                        AND orcdotacao.o58_unidade = orcunidade.o41_unidade
                    INNER JOIN orcamento.orcprograma ON orcdotacao.o58_anousu = orcprograma.o54_anousu
                        AND orcdotacao.o58_programa = orcprograma.o54_programa
                    INNER JOIN orcamento.orcprojativ ON orcdotacao.o58_anousu = orcprojativ.o55_anousu
                        AND orcdotacao.o58_projativ = orcprojativ.o55_projativ
                    INNER JOIN orcamento.orcfuncao ON orcdotacao.o58_funcao = orcfuncao.o52_funcao
                    INNER JOIN orcamento.orcsubfuncao ON orcdotacao.o58_subfuncao = orcsubfuncao.o53_subfuncao
                    INNER JOIN orcamento.orcelemento ON orcdotacao.o58_codele = orcelemento.o56_codele
                        AND orcdotacao.o58_anousu = orcelemento.o56_anousu
                    INNER JOIN orcamento.orctiporec ON orcdotacao.o58_codigo = orctiporec.o15_codigo
                    LEFT JOIN contabilidade.conlancampag ON conlancam.c70_codlan = conlancampag.c82_codlan
                    LEFT JOIN contabilidade.conplanoreduz ON conlancampag.c82_reduz = conplanoreduz.c61_reduz
                        AND conlancampag.c82_anousu = conplanoreduz.c61_anousu
                    LEFT JOIN contabilidade.conplanocontabancaria ON
                        conplanoreduz.c61_reduz = conplanocontabancaria.c56_reduz
                        AND conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu
                    LEFT JOIN configuracoes.contabancaria ON
                        conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial
                    LEFT JOIN configuracoes.bancoagencia ON contabancaria.db83_bancoagencia = db89_sequencial
                    INNER JOIN empenho.empresto ON empresto.e91_numemp = empempenho.e60_numemp
                        AND empresto.e91_anousu = {$this->ano}
                WHERE
                     {$whereRestos}
                GROUP BY
                    e150_numeroprocesso,
                    o58_orgao,
                    o58_unidade,
                    o58_funcao,
                    o58_subfuncao,
                    o58_programa,
                    o58_projativ,
                    o56_elemento,
                    o15_codigo,
                    e60_codemp,
                    e60_anousu,
                    z01_nome,
                    e60_codemp,
                    e60_anousu,
                    e91_vlremp,
                    e91_vlranu,
                    e91_vlrpag
                ORDER BY
                    processo
            ) as x
            group by
                processo,
                fornecedor,
                empenho,
                funcionalprogramatica,
                contacorrente,
                e60_codemp,
                e60_anousu) as y
            where valortotal > 0
            order by
                e60_anousu asc,
                e60_codemp asc";
        $sql = "{$sqlEmpenho} union all {$sqlResto}";

        $rsDados = db_query($sql);
        $dados = \db_utils::getCollectionByRecord($rsDados);
        return $dados;
    }
}
