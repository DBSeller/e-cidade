<?php

namespace ECidade\Financeiro\Contabilidade\LancamentoContabil\Documento;

/**
 * Class NotaDeLancamento
 *
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil\Documento
 */
class NotaDeLancamento
{

    /**
     * @var \PDFDocument
     */
    protected $pdf;

    /**
     * @var integer[]
     */
    protected $codigosLancamentos = array();

    /**
     * @var \stdClass[]
     */
    protected $lancamentos = array();

    /**
     * NotaDeLancamento constructor.
     *
     * @param array $codigosLancamentos
     */
    public function __construct(array $codigosLancamentos)
    {
        $this->codigosLancamentos = $codigosLancamentos;
    }

    /**
     * @throws \DBException
     * @throws \Exception
     * @throws \ParameterException
     */
    public function emitir()
    {
        $this->prepararInformacoes();

        $this->pdf = new \PDFDocument(\PDFDocument::PRINT_PORTRAIT);
        $this->pdf->setFontSize(8);
        $this->pdf->setFillColor(250);
        $this->pdf->addHeaderDescription("Nota de Lançamento Contábil");
        $this->pdf->open();
        $this->pdf->addPage();

        foreach ($this->lancamentos as $stdLancamento) {
            $this->pdf->ln(10);

            $dataLancamento = new \DBDate($stdLancamento->data_lancamento);
            $this->pdf->setBold(true);
            $this->pdf->cell($this->pdf->getAvailWidth(), 4, 'Informações do Lançamento', 'B', 1, 'C');

            $this->pdf->cell(25, 4, 'Lançamento:', 0, 0, 'L');
            $this->pdf->setBold(false);
            $this->pdf->cell(25, 4, $stdLancamento->codigo_lancamento, 0, 0, 'L');

            $this->pdf->setBold(true);
            $this->pdf->cell(10, 4, 'Valor:', 0, 0, 'L');
            $this->pdf->setBold(false);
            $this->pdf->cell(35, 4, trim(db_formatar($stdLancamento->valor_lancamento, 'f')), 0, 0, 'L');

            $this->pdf->setBold(true);
            $this->pdf->cell(20, 4, 'Data:', 0, 0, 'L');
            $this->pdf->setBold(false);
            $this->pdf->cell(25, 4, $dataLancamento->getDate(\DBDate::DATA_PTBR), 0, 1, 'L');

            $this->pdf->setBold(true);
            $this->pdf->cell(25, 4, 'Documento:', 0, 0, 'L');
            $this->pdf->setBold(false);
            $this->pdf->cell(
                70,
                4,
                "{$stdLancamento->codigo_documento} - {$stdLancamento->descricao_documento}",
                0,
                0,
                'L'
            );
            $this->pdf->setBold(true);
            $this->pdf->cell(20, 4, 'Histórico:', 0, 0, 'L');
            $this->pdf->setBold(false);
            $this->pdf->cell(
                50,
                4,
                "{$stdLancamento->codigo_historico} - {$stdLancamento->descricao_historico}",
                0,
                1,
                'L'
            );

            $this->pdf->ln(4);

            $totalContasDebito = count($stdLancamento->contas_debito);
            $this->pdf->setBold(true);
            $this->pdf->cell(90, 4, 'Conta', 0, 0, 'C', 1);
            $this->pdf->cell(15, 4, 'Natureza', 0, 0, 'C', 1);
            $this->pdf->cell(50, 4, 'Tipo de Atributo', 0, 0, 'C', 1);
            $this->pdf->cell(35, 4, 'Atributo', 0, 1, 'C', 1);
            $this->pdf->setBold(false);
            for ($rowContas = 0; $rowContas < $totalContasDebito; $rowContas++) {
                $stdContaDebito = $stdLancamento->contas_debito[$rowContas];
                $stdContaCredito = $stdLancamento->contas_credito[$rowContas];
                $tamanhoHeight = 4;
                $this->pdf->cell(
                    90,
                    $tamanhoHeight,
                    substr("{$stdContaDebito->estrutural} - {$stdContaDebito->descricao}", 0, 55),
                    0,
                    0,
                    'L'
                );
                $this->pdf->cell(15, $tamanhoHeight, "D", 0, 0, 'C');

                $volta = 0;
                foreach ($stdContaDebito->sistemas as $codigo => $stdAtribtos) {
                    if ($volta > 0) {
                        $this->pdf->cell(105, 4, '', '0', 0);
                    }
                    $this->pdf->cell(50, 4, $stdAtribtos->descricao_sistema, 0, 0, 'C');
                    $this->pdf->MultiCell(35, 4, $stdAtribtos->atributos, 0, 'C');
                    $volta++;
                }

                if (empty($stdContaDebito->conta_corrente)) {
                    $this->pdf->ln();
                }

                $this->pdf->cell(
                    90,
                    $tamanhoHeight,
                    substr("{$stdContaCredito->estrutural} - {$stdContaCredito->descricao}", 0, 55),
                    0,
                    0,
                    'L'
                );
                $this->pdf->cell(15, $tamanhoHeight, "C", 0, 0, 'C');
                $volta = 0;
                foreach ($stdContaCredito->sistemas as $codigo => $stdAtribtos) {
                    if ($volta > 0) {
                        $this->pdf->cell(105, 4, '', '0', 0);
                    }
                    $this->pdf->cell(50, 4, $stdAtribtos->descricao_sistema, 0, 0, 'C');
                    $this->pdf->MultiCell(35, 4, $stdAtribtos->atributos, 0, 'C');
                    $volta++;
                }

                if (empty($stdContaCredito->conta_corrente)) {
                    $this->pdf->ln();
                }
            }


            $where = implode(' or ', array(
                "c135_codlaninclusao = {$stdLancamento->codigo_lancamento}",
                "c135_codlanestorno = {$stdLancamento->codigo_lancamento}",
                "c135_codlannovo = {$stdLancamento->codigo_lancamento}"
            ));
            $buscaRetificacao = db_query("select * from conlancamretificacao where ({$where})");
            $mensagemAdicional = null;
            if (pg_num_rows($buscaRetificacao) > 0) {
                $stdRetificacao = \db_utils::fieldsMemory($buscaRetificacao, 0);

                if ($stdRetificacao->c135_codlaninclusao == $stdLancamento->codigo_lancamento) {
                    $configuracaoComplemento = explode('#', $stdLancamento->complemento);
                    $complementoAdicional = str_replace('NOTA DO SISTEMA: ', '', $configuracaoComplemento[1]);
                    $mensagemAdicional = "Nota: {$complementoAdicional}";
                }

                if ($stdRetificacao->c135_codlanestorno == $stdLancamento->codigo_lancamento) {
                    $mensagemAdicional  = "Nota: este lançamento corresponde ao estorno gerado ";
                    $mensagemAdicional .= "pela retificação do lançamento {$stdRetificacao->c135_codlaninclusao}.";
                }

                if ($stdRetificacao->c135_codlannovo == $stdLancamento->codigo_lancamento) {
                    $mensagemAdicional  = "Nota: este lançamento corresponde à retificação do ";
                    $mensagemAdicional .= "lançamento {$stdRetificacao->c135_codlaninclusao}.";
                }
            }

            $this->pdf->ln(4);

            $this->pdf->setBold(true);
            $this->pdf->cell(25, 4, 'Complemento:', 0, 1, 'L');
            $this->pdf->setBold(false);
            if (!empty($mensagemAdicional)) {
                $this->pdf->cell($this->pdf->getAvailWidth(), 4, $mensagemAdicional, 0, 1);
                $configuracaoComplemento = explode('#', $stdLancamento->complemento);
                $stdLancamento->complemento = $configuracaoComplemento[0];
            }

            $this->pdf->multicell($this->pdf->getAvailWidth(), 4, $stdLancamento->complemento, 'B');


            $emissor = \lancamentoContabil::getLog($stdLancamento->codigo_lancamento);
            $nomeEmissor = $emissor[0]->nome;
            $this->pdf->ln(4);
            $this->pdf->cell($this->pdf->getAvailWidth(), 4, "Emissor: $nomeEmissor", 0, 1, 'R');
        }

        $this->pdf->showPDF('NotaDeLancamento_' . date('Ymdhis'));
    }


    /**
     * Método que prepara as informações para serem impressas
     *
     * @return array|\stdClass[]
     * @throws \Exception
     */
    private function prepararInformacoes()
    {

        $buscaLancamentos = $this->consultarLancamentos();
        $totalRegistros = pg_num_rows($buscaLancamentos);

        $this->lancamentos = array();
        for ($rowLancamento = 0; $rowLancamento < $totalRegistros; $rowLancamento++) {
            $stdLancamento = \db_utils::fieldsMemory($buscaLancamentos, $rowLancamento);

            if (empty($this->lancamentos[$stdLancamento->codigo_lancamento])) {
                $stdDadosLancamentos = new \stdClass();
                $stdDadosLancamentos->codigo_lancamento = $stdLancamento->codigo_lancamento;
                $stdDadosLancamentos->data_lancamento = $stdLancamento->data_lancamento;
                $stdDadosLancamentos->valor_lancamento = $stdLancamento->valor_lancamento;
                $stdDadosLancamentos->codigo_historico = $stdLancamento->codigo_historico;
                $stdDadosLancamentos->descricao_historico = $stdLancamento->descricao_historico;
                $stdDadosLancamentos->codigo_documento = $stdLancamento->codigo_documento;
                $stdDadosLancamentos->descricao_documento = $stdLancamento->descricao_documento;
                $stdDadosLancamentos->complemento = $stdLancamento->complemento;
                $stdDadosLancamentos->contas_debito = array();
                $stdDadosLancamentos->contas_credito = array();
                $this->lancamentos[$stdLancamento->codigo_lancamento] = $stdDadosLancamentos;
            }

            $dadosContaDebito = (object)array(
                'reduzido'       => $stdLancamento->reduzido_debito,
                'estrutural'     => $stdLancamento->estrutural_debito,
                'descricao'      => $stdLancamento->descricao_debito,
                'atributos'      => array(),
                'conta_corrente' => array(),
                'sistemas'       => array(),
            );

            $buscaAtributosDebito = db_query("
                select c122_sequencial,
                       c122_descricao,
                       array_to_string(array_accum(c121_sigla||':'||c123_valor), ' | ') as atributos
                  from infocomplementarvalor
                       join conplanoatributolancamentos on c123_conplanoatributolancamentos = c124_sequencial
                       join conplanoinfocomplementar on c121_sequencial = c123_infocomplementar
                       join conplanosistema on c123_conplanosistema = c122_sequencial
                 where c124_lancamento = {$stdLancamento->codigo_lancamento}
                   and c124_natureza = 'D'
                 group by 1;");
            if (pg_num_rows($buscaAtributosDebito) > 0) {
                for ($row = 0; $row < pg_num_rows($buscaAtributosDebito); $row++) {
                    $stdAtributos = \db_utils::fieldsMemory($buscaAtributosDebito, $row);
                    $descricao = $stdAtributos->c122_sequencial == 1 ? 'MSC' : $stdAtributos->c122_descricao;
                    $dadosContaDebito->sistemas[] = (object)array(
                        'codigo_sistema' => $stdAtributos->c122_sequencial,
                        'descricao_sistema' => $descricao,
                        'atributos' => $stdAtributos->atributos,
                    );
                }
            }


            $this->lancamentos[$stdLancamento->codigo_lancamento]->contas_debito[] = $dadosContaDebito;

            $dadosContaCredito = (object)array(
                'reduzido'       => $stdLancamento->reduzido_credito,
                'estrutural'     => $stdLancamento->estrutural_credito,
                'descricao'      => $stdLancamento->descricao_credito,
                'atributos'      => array(),
                'conta_corrente' => array(),
                'sistemas'       => array()
            );
            $buscaAtributosCredito = db_query("
                select c122_sequencial,
                       c122_descricao,
                       array_to_string(array_accum(c121_sigla||':'||c123_valor), ' | ') as atributos
                  from infocomplementarvalor
                       join conplanoatributolancamentos on c123_conplanoatributolancamentos = c124_sequencial
                       join conplanoinfocomplementar on c121_sequencial = c123_infocomplementar
                       join conplanosistema on c123_conplanosistema = c122_sequencial
                 where c124_lancamento = {$stdLancamento->codigo_lancamento}
                   and c124_natureza = 'C'
                 group by 1;");
            if (pg_num_rows($buscaAtributosCredito) > 0) {
                for ($row = 0; $row < pg_num_rows($buscaAtributosCredito); $row++) {
                    $stdAtributos = \db_utils::fieldsMemory($buscaAtributosCredito, $row);
                    $descricao = $stdAtributos->c122_sequencial == 1 ? 'MSC' : $stdAtributos->c122_descricao;
                    $dadosContaCredito->sistemas[] = (object)array(
                        'codigo_sistema' => $stdAtributos->c122_sequencial,
                        'descricao_sistema' => $descricao,
                        'atributos' => $stdAtributos->atributos,
                    );
                }
            }
            $this->lancamentos[$stdLancamento->codigo_lancamento]->contas_credito[] = $dadosContaCredito;
        }
        return $this->lancamentos;
    }


    /**
     * Executa a consulta padrão para apresentação dos dados.
     *
     * @return bool|resource|string
     * @throws \DBException
     */
    protected function consultarLancamentos()
    {
        $lancamentos = implode(',', $this->codigosLancamentos);

        $campos = implode(',', array(
            'conlancam.c70_codlan as codigo_lancamento',
            'conlancam.c70_data as data_lancamento',
            'conlancam.c70_valor as valor_lancamento',
            'conhist.c50_codhist as codigo_historico',
            'conhist.c50_descr as descricao_historico',
            'conhistdoc.c53_coddoc as codigo_documento',
            'conhistdoc.c53_descr as descricao_documento',
            'conlancamcompl.c72_complem as complemento',

            'reduzdebito.c61_reduz as reduzido_debito',
            'planodebito.c60_codcon as conta_debito',
            'planodebito.c60_estrut as estrutural_debito',
            'planodebito.c60_descr as descricao_debito',
            'reduzcredito.c61_reduz as reduzido_credito',
            'planocredito.c60_codcon as conta_credito',
            'planocredito.c60_estrut as estrutural_credito',
            'planocredito.c60_descr as descricao_credito',
        ));
        $where = implode(' and ', array(
            "conlancam.c70_codlan in ({$lancamentos})"
        ));
        $daoLancamento = new \cl_conlancam();
        $buscaLancamentos = $daoLancamento->sql_query_nota_lancamento($campos, $where, "order by conlancam.c70_codlan");
        $buscaLancamentos = db_query($buscaLancamentos);
        if (!$buscaLancamentos) {
            throw new \DBException("Ocorreu um erro ao consultar os dados dos lançamentos selecionados.");
        }
        return $buscaLancamentos;
    }


    /**
     * @param $codigoConta
     *
     * @return bool|resource
     * @throws \Exception
     */
    private function getContaCorrente($codigoConta)
    {

        $sqlBuscaConta = "
        select conplanosistema.c122_descricao as descricao,
               array_to_string(array_accum(distinct conplanoinfocomplementar.c121_sigla), ', ') as siglas
          from conplanosistema
               join conplanoatributos on conplanoatributos.c120_conplanosistema = conplanosistema.c122_sequencial
               join conplanoinfocomplementar
                             on conplanoinfocomplementar.c121_sequencial = conplanoatributos.c120_infocomplementar
               join conplano on conplano.c60_codcon = conplanoatributos.c120_conplano
                            and conplano.c60_anousu = conplanoatributos.c120_anousu
         where conplanosistema.c122_tipo = 2
           and c60_codcon = {$codigoConta}
         group by conplanosistema.c122_descricao";
        $resultBusca = db_query($sqlBuscaConta);
        if (!$resultBusca) {
            throw new \Exception("Não foi possível consultar os contas correntes da conta.");
        }
        return $resultBusca;
    }
}
