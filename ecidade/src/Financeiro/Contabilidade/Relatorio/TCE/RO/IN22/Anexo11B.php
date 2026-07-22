<?php
/**
 * Created by PhpStorm.
 * User: robson
 * Date: 2020-02-05
 * Time: 16:36
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

use cl_conlancamemp;
use db_utils;
use DBDate;
use ECidade\Library\SpreadSheet\Template\Parser;
use Periodo;

/**
 * Class Anexo10
 * @package ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22
 */
class Anexo11B extends Base implements In22
{
    const CODIGO_RELATORIO = 239;
    const TEMPLATE = 'config/templates/IN22/anexo11_B.xlsx';

    const ENSINO_INFANTIL = 365;
    const ENSINO_FUNDAMENTAL = 361;
    const ENSINO_MEDIO = 362;
    const ENSINO_ESPECIAL = 367;
    const ENSINO_EJA = 366;


    /**
     * @return mixed|void
     * @throws \BusinessException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function processar()
    {
        $parser = new Parser();
        $parser->loadXLS(self::TEMPLATE);
        $parser->addVariable('ano', $this->ano);

        $dados = $this->processarDados();

        $totalEmpenhado = 0;
        $totalLiquidado = 0;
        $totalPago = 0;
        $dadosOrganizados = $this->organizaDadosPorSubFuncao($dados);

        foreach ($dadosOrganizados as $dadosOrganizado) {
            $totalEmpenhado += $dadosOrganizado->vlr_emp;
            $totalLiquidado += $dadosOrganizado->vlr_liq;
            $totalPago += $dadosOrganizado->vlr_pag;
        }

        $totalEmpenhado = $this->formataValor($totalEmpenhado);
        $totalLiquidado = $this->formataValor($totalLiquidado);
        $totalPago = $this->formataValor($totalPago);
        $dadosImpressao = $this->organizaDadosParaImpressao($dadosOrganizados);

        $parser->addCollection('iterar_dados', $dadosImpressao);

        $parser->addVariable('totalvalorempenhado', $totalEmpenhado);
        $parser->addVariable('totalvalorliquidado', $totalLiquidado);
        $parser->addVariable('totalvalorpago', $totalPago);

        $parser->addVariable('data_emissao', $this->getDataEmissao()->getDate(DBDate::DATA_PTBR));

        $parser->parse();
        $path = 'tmp/anexo11_B.xlsx';
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

        $campos = [
            "o58_projativ",
            "o55_descr::varchar as projetoatividade",
            "substr(o56_elemento,2,12) as elemento",
            "0 as valor_autorizado",
            "round(coalesce(sum(
              case when c53_tipo = 11 then c70_valor * -1
                   when c53_tipo = 10 then c70_valor else 0 end), 0), 2) as valor_empenhado",
            "round(coalesce(sum(
                case when c53_tipo = 21 then c70_valor * -1
                     when c53_tipo = 20 then c70_valor else 0 end), 0), 2) as valor_liquidado",
            "round(coalesce(sum(
              case when c53_tipo = 31 then c70_valor * -1
                   when c53_tipo = 30 then c70_valor else 0 end), 0), 2) as valor_pago",
            "o58_subfuncao"
        ];

        $where = [
            "c70_data between '{$dataInicial}' and '{$dataFinal->getDate()}'",
            "c70_anousu = {$this->ano}",
            "e60_instit in (" . implode(",", $this->instituicoes) . ")",
            "e60_anousu = {$this->ano}",
            "c53_tipo in (10, 11, 20, 21, 30, 31)",
        ];

        $groupBy = [
            "o58_projativ",
            "o56_elemento",
            "o58_orgao",
            "o58_unidade",
            "o58_funcao",
            "o58_subfuncao",
            "o58_programa",
            "o58_projativ",
            "o55_descr",
        ];

        $daoConlancamemp = new cl_conlancamemp();
        $sqlProjeto = $daoConlancamemp->sql_query_dados_empenho(
            implode(", ", $campos),
            implode(" and ", $where) . $whereFiltro,
            "",
            implode(', ', $groupBy)
        );

        $sqlAutorizado = "select o58_projativ,
               o55_descr::varchar                    as projetoatividade,
               substr(o56_elemento, 2, 12)           as elemento,
               round(coalesce(sum(o58_valor), 0), 2) as valor_autorizado,
               0 as valor_empenhado,
               0 as valor_liquidado,
               0 as valor_pago,
               o58_subfuncao
        from orcdotacao
                join orcsubfuncao on o58_subfuncao = o53_subfuncao
                join orcprojativ on o58_projativ = o55_projativ and o58_anousu = o55_anousu
                join orcelemento on o58_anousu = o56_anousu and o58_codele = o56_codele
        where o58_anousu = {$this->ano} {$whereFiltro}
        group by o58_projativ, o55_descr, o56_elemento, o58_subfuncao";

        $sql = "select o58_projativ
             , projetoatividade
             , elemento
             , sum(valor_autorizado) as valor_autorizado
             , sum(valor_empenhado) as valor_empenhado
             , sum(valor_liquidado) as valor_liquidado
             , sum(valor_pago) as valor_pago
             , o58_subfuncao
         from ({$sqlProjeto} union all {$sqlAutorizado}) as x
        group by o58_projativ, projetoatividade, elemento, o58_subfuncao";

        $rsDados = db_query($sql);
        return db_utils::getCollectionByRecord($rsDados);
    }

    private function organizaDadosPorSubFuncao(array $dados)
    {
        $subFuncoes = [
            self::ENSINO_INFANTIL,
            self::ENSINO_FUNDAMENTAL,
            self::ENSINO_MEDIO,
            self::ENSINO_ESPECIAL,
            self::ENSINO_EJA
        ];

        $dadosImpressao = [
            self::ENSINO_INFANTIL => $this->getStdClassSubFuncao(self::ENSINO_INFANTIL),
            self::ENSINO_FUNDAMENTAL => $this->getStdClassSubFuncao(self::ENSINO_FUNDAMENTAL),
            self::ENSINO_MEDIO => $this->getStdClassSubFuncao(self::ENSINO_MEDIO),
            self::ENSINO_ESPECIAL => $this->getStdClassSubFuncao(self::ENSINO_ESPECIAL),
            self::ENSINO_EJA => $this->getStdClassSubFuncao(self::ENSINO_EJA),
            999 => $this->getStdClassSubFuncao(),
        ];

        foreach ($dados as $dado) {
            $stdClassProjeto = $this->getStdClassProjeto($dado);
            $id = 999;
            if (in_array($dado->o58_subfuncao, $subFuncoes)) {
                $id = $dado->o58_subfuncao;
            }

            $dadosImpressao[$id]->linhas[] = $stdClassProjeto;
            $dadosImpressao[$id]->vlr_autoriza += $stdClassProjeto->vlr_autoriza;
            $dadosImpressao[$id]->vlr_emp += $stdClassProjeto->vlr_emp;
            $dadosImpressao[$id]->vlr_liq += $stdClassProjeto->vlr_liq;
            $dadosImpressao[$id]->vlr_pag += $stdClassProjeto->vlr_pag;
        }

        return $dadosImpressao;
    }

    /**
     * @param int $tipo
     * @return object
     */
    private function getStdClassSubFuncao($tipo = 999)
    {
        $tipos = [
            self::ENSINO_INFANTIL => "ENSINO INFANTIL
Creche
Pré-Escola",
            self::ENSINO_FUNDAMENTAL => "ENSINO FUNDAMENTAL",
            self::ENSINO_MEDIO => "ENSINO MÉDIO",
            self::ENSINO_ESPECIAL => "EDUCAÇÃO ESPECIAL",
            self::ENSINO_EJA => "EDUCAÇÃO DE JOVENS E ADULTOS",
            999 => "Outros",
        ];

        return (object)[
            "descricao" => $tipos[$tipo],
            "elemento" => '',
            "vlr_autoriza" => 0,
            "vlr_emp" => 0,
            "vlr_liq" => 0,
            "vlr_pag" => 0,
            "linhas" => []
        ];
    }

    /**
     * @param $dado
     * @return object
     */
    public function getStdClassProjeto($dado)
    {
        return (object)[
            "descricao" => "{$dado->o58_projativ} - {$dado->projetoatividade}",
            "elemento" => $dado->elemento,
            "vlr_autoriza" => $dado->valor_autorizado,
            "vlr_emp" => $dado->valor_empenhado,
            "vlr_liq" => $dado->valor_liquidado,
            "vlr_pag" => $dado->valor_pago,
        ];
    }

    private function organizaDadosParaImpressao($dadosOrganizados)
    {
        $dadosImpressao = [];
        foreach ($dadosOrganizados as $subFuncao) {
            $dadosImpressao[] = (object)[
                'descricao' => $subFuncao->descricao,
                'elemento' => $subFuncao->elemento,
                'vlr_autoriza' => $this->formataValor($subFuncao->vlr_autoriza),
                'vlr_emp' => $this->formataValor($subFuncao->vlr_emp),
                'vlr_liq' => $this->formataValor($subFuncao->vlr_liq),
                'vlr_pag' => $this->formataValor($subFuncao->vlr_pag),
            ];
            foreach ($subFuncao->linhas as $projetos) {
                $dadosImpressao[] = (object)[
                    'descricao' => $projetos->descricao,
                    'elemento' => $projetos->elemento,
                    'vlr_autoriza' => $this->formataValor($projetos->vlr_autoriza),
                    'vlr_emp' => $this->formataValor($projetos->vlr_emp),
                    'vlr_liq' => $this->formataValor($projetos->vlr_liq),
                    'vlr_pag' => $this->formataValor($projetos->vlr_pag),
                ];
            }
        }

        return $dadosImpressao;
    }
}
