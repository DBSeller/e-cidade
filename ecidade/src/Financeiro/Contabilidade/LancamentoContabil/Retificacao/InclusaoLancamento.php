<?php

namespace ECidade\Financeiro\Contabilidade\LancamentoContabil\Retificacao;

/**
 * Class InclusaoLancamento
 *
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil\Retificacao
 */
class InclusaoLancamento
{

    /**
     * @param $parametros
     *
     * @return \stdClass
     * @throws \DBException
     * @throws \ParameterException
     * @throws \Exception
     */
    public function getLancamentos($parametros)
    {

        if (empty($parametros->documento)) {
            throw new \Exception("Código do Documento é de preenchimento obrigatório.");
        }

        $anoSessao = db_getsession('DB_anousu');
        $instituicaoSessao = db_getsession('DB_instit');

        $where = array("c71_coddoc = {$parametros->documento}");
        $where[] = "c02_instit = {$instituicaoSessao}";
        $where[] = "c70_anousu = {$anoSessao}";

        if (!empty($parametros->codigo_lancamento)) {
            $where[] = "c70_codlan = {$parametros->codigo_lancamento}";
        }

        if (!empty($parametros->data_inicial)) {
            $dataInicial = new \DBDate($parametros->data_inicial);
            $where[] = "c70_data >= '{$dataInicial->getDate()}'";
        }

        if (!empty($parametros->data_final)) {
            $dataFinal = new \DBDate($parametros->data_final);
            $where[] = "c70_data <= '{$dataFinal->getDate()}'";
        }

        $campos = array(
            'c70_codlan as codigo_lancamento',
            'c70_data as data',
            'c70_valor as valor',
            '(select count(c69_sequen) from conlancamval clv where clv.c69_codlan = c70_codlan) as total_executado',
            'array_to_string(
               array_accum(c69_ordem||\'#\'||c69_sequen order by c69_ordem, c69_sequen) , \',\') as executados'
        );

        $daoConlancamval = new \cl_conlancamval();
        $consultaRegistros = $daoConlancamval->sql_query_lancamentos(
            implode(',', $campos),
            implode(' and ', $where) . ' group by 1, 2, 3',
            'c70_codlan'
        );

        $resultConsulta = db_query($consultaRegistros);
        if (!$resultConsulta) {
            throw new \DBException("Ocorreu um erro ao consultar os lançamentos.");
        }
        $totalRegistros = pg_num_rows($resultConsulta);

        $eventoContabil = new \EventoContabil($parametros->documento, $anoSessao, $instituicaoSessao);
        $lancamentos = $eventoContabil->getEventoContabilLancamento();
        $totalLancamentos = count($lancamentos);
        $retorno = new \stdClass();
        $retorno->lancamentos = array();
        $retorno->lancamentosEvento = array();
        $retorno->totalLancamentosEvento = $totalLancamentos;
        foreach ($lancamentos as $lancamento) {
            if ((int)$lancamento->getOrdem() === 1) {
                continue;
            }
            $retorno->lancamentosEvento[] = (object)array(
                'codigo' => $lancamento->getSequencialLancamento(),
                'ordem' => $lancamento->getOrdem(),
                'descricao' => $lancamento->getDescricao(),
            );
        }

        for ($row = 0; $row < $totalRegistros; $row++) {
            $stdLancamento = \db_utils::fieldsMemory($resultConsulta, $row);
            $ordenarExecutados = explode(',', $stdLancamento->executados);
            sort($ordenarExecutados);
            $stdLancamento->executados = implode('|', $ordenarExecutados);
            $retorno->lancamentos[] = $stdLancamento;
        }

        return $retorno;
    }

    /**
     * Verifica se o evento possui do lancamento na ordem .
     * @param $lancamento
     * @param $ordem
     * @return bool
     * @throws \Exception
     */
    public function eventoTemLancamentoExecutado($lancamento, $ordem)
    {

        $daoConLancamVal = new \cl_conlancamval();
        $buscaDados = $daoConLancamVal->sql_query_file(
            null,
            "*",
            '1 limit 1',
            "c69_codlan = {$lancamento} and c69_ordem = {$ordem}"
        );
        $resultBusca = db_query($buscaDados);
        if (!$resultBusca) {
            throw new \Exception("Lançamento {$lancamento} não encontrado.");
        }
        return pg_num_rows($resultBusca) > 0;
    }

    /**
     * @param $oParametros
     * @param $fileLog
     *
     * @throws \Exception
     * @return integer
     */
    public function incluirLancamento($parametros, $fileLog)
    {

        $lancamentoEvento = new \EventoContabilLancamento($parametros->lancamento);
        $regrasDoLancamento = $lancamentoEvento->getRegrasLancamento();
        if (count($regrasDoLancamento) > 1) {
            $mensagem  = "O lançamento selecionado possui mais de uma regra cadastrada. ";
            $mensagem .= "Não é possível processar a inclusão do lançamento.";
            throw new \Exception($mensagem);
        }


        $regra = $regrasDoLancamento[0];
        $totalRegistrosProcessados = 0;
        $logRegistros = array();
        try {
            db_inicio_transacao();

            foreach ($parametros->registros as $codigoLancamento) {
                $conlancamval = $this->getDadosLancamento($codigoLancamento);
                if ($this->eventoTemLancamentoExecutado($codigoLancamento, $lancamentoEvento->getOrdem())) {
                    $textoLog = "[INFO] Lançamento {$codigoLancamento} já possui lançamento de ordem ";
                    $textoLog .= "{$lancamentoEvento->getOrdem()} processado.";
                    $logRegistros[] = $textoLog;
                    continue;
                }
                $contaCredito = getContaReduzidaNaInstituicao($regra->getContaCredito(), $conlancamval->c69_anousu);
                $contaDebito  = getContaReduzidaNaInstituicao($regra->getContaDebito(), $conlancamval->c69_anousu);

                $dao = new \cl_conlancamval();
                $dao->c69_sequen  = null;
                $dao->c69_anousu  = $conlancamval->c69_anousu;
                $dao->c69_codlan  = $conlancamval->c69_codlan;
                $dao->c69_codhist = $lancamentoEvento->getHistorico();
                $dao->c69_credito = $contaCredito;
                $dao->c69_debito  = $contaDebito;
                $dao->c69_valor   = $conlancamval->c69_valor;
                $dao->c69_data    = $conlancamval->c69_data;
                $dao->c69_ordem   = $lancamentoEvento->getOrdem();
                $dao->incluir(null);
                if ($dao->erro_status === '0') {
                    throw new \Exception("[ERRO] Lançamento {$codigoLancamento} não processado. - " . $dao->erro_msg);
                }

                AlteracaoLancamento::removerLancamentoContaCorrente($conlancamval);
                AlteracaoLancamento::processarContaCorrente($conlancamval);
                AlteracaoLancamento::ajustaRecursoLancamento($conlancamval);
                \EventoContabil::salvarLancamento($conlancamval->c69_codlan);

                $logRegistros[] = "[INFO] Lançamento {$codigoLancamento} processado.";
                $totalRegistrosProcessados++;
            }

            db_fim_transacao(false);
        } catch (\Exception $e) {
            $logRegistros[] = $e->getMessage();
            db_fim_transacao(true);
        }

        file_put_contents($fileLog, implode("\n", $logRegistros));
        return $totalRegistrosProcessados;
    }

    /**
     * @param $codigoLancamento
     *
     * @return \stdClass
     * @throws \Exception
     */
    private function getDadosLancamento($codigoLancamento)
    {
        $daoConLancamVal = new \cl_conlancamval();
        $buscaDados = $daoConLancamVal->sql_query_file(
            null,
            "*",
            '1 limit 1',
            "c69_codlan = {$codigoLancamento}"
        );
        $resultBusca = db_query($buscaDados);
        if (!$resultBusca) {
            throw new \Exception("Lançamento {$codigoLancamento} não encontrado.");
        }
        return \db_utils::fieldsMemory($resultBusca, 0);
    }

    /**
     * @param $codigo
     *
     * @throws \DBException
     * @throws \Exception
     * @throws \ParameterException
     * @throws \ReflectionException
     * @return boolean
     */
    public function excluir($codigo)
    {

        $daoConlancamval = new \cl_conlancamval();
        $buscaLancamento = $daoConlancamval->sql_query_file($codigo);
        $conlancamval = \db_utils::fieldsMemory(db_query($buscaLancamento), 0);
        $daoConlancamval->excluir($codigo);
        if ($daoConlancamval->erro_status === '0') {
            $mensagem = str_replace('\\n', "\n", $daoConlancamval->erro_msg);
            throw new \Exception("Não foi possível excluir o registro desejado.\n\n{$mensagem}");
        }

        AlteracaoLancamento::removerLancamentoContaCorrente($conlancamval);
        AlteracaoLancamento::processarContaCorrente($conlancamval);
        AlteracaoLancamento::ajustaRecursoLancamento($conlancamval);
        \EventoContabil::salvarLancamento($conlancamval->c69_codlan);
        return true;
    }
}
