<?php


namespace ECidade\Financeiro\Contabilidade\ExercicioContabil;

use ECidade\Financeiro\Contabilidade\LancamentoContabil\Documento;
use EventoContabil;

/**
 * Class Abertura
 * @package ECidade\Financeiro\Contabilidade\ExercicioContabil
 */
class Abertura extends ExercicioContabil
{

    /**
     * @var \AberturaExercicioOrcamento
     */
    protected $abertura;

    /**
     *
     * Abertura constructor.
     * @param $ano
     * @param \DBDate $data
     * @param \Instituicao $instituicao
     */
    public function __construct($ano, \DBDate $data, \Instituicao $instituicao)
    {
        $dataArquivo = str_replace('/', '-', $data);
        $this->nomeLog = "abertura_{$dataArquivo}_{$instituicao->getCodigo()}.log";
        parent::__construct($ano, $data, $instituicao);
    }

    /**
     * @return array
     */
    public function getDocumentosParaProcessamento()
    {
        return array(
            Documento::ABERTURA_ORCAMENTO_RECEITA,
            Documento::ABERTURA_ORCAMENTO_DESPESA,
            //Documento::ABERTURA_TRANSFERENCIA_SALDOS_RPNP_EX_ANT, nao serao processados esse ano  (2020)
            //Documento::ABERTURA_TRANSFERENCIA_SALDOS_RPP_EX_ANT,nao serao processados esse ano  (2020)
            Documento::ABERTURA_TRANSFERENCIA_SALDOS_RPNP_INSCRITOS_EX_ANT,
            Documento::ABERTURA_TRANSFERENCIA_SALDOS_RPP_INSCRITOS_EX_ANT,

        );
    }

    protected function cancelarDocumento($codigoDocumento)
    {

        $where = "c71_coddoc = {$codigoDocumento} and c70_anousu = {$this->ano} ";
        $where .= " and c02_instit = {$this->instituicao->getCodigo()}";
        $daoAberturaExercicio = new \cl_conlancamaberturaexercicioorcamento();
        $consultaAbertura = $daoAberturaExercicio->sql_query_documento(
            null,
            '*',
            'c71_coddoc',
            $where
        );
        $rsAbertura = db_query($consultaAbertura);
        $totalRegistros = pg_num_rows($rsAbertura);
        for ($i = 0; $i < $totalRegistros; $i++) {
            $dados = \db_utils::fieldsMemory($rsAbertura, $i);
            $daoAberturaExercicio->excluir($dados->c105_aberturaexercicioorcamento);
            if ($daoAberturaExercicio->erro_status == 0) {
                $mensagem = 'Não foi possível cancelar a abertura do documento ' . $codigoDocumento;
                $mensagem .= " Erro técnico: {$daoAberturaExercicio->erro_msg}";
                throw new \Exception($mensagem);
            }
            \lancamentoContabil::excluirLancamento($dados->c105_codlan);
        }
        return true;
    }

    /**
     * Realiza o cancelamento do exercicio contabil.
     * consiste em excluir os dados de abertura
     * @return boolean true
     * @throws \Exception
     */
    public function cancelar()
    {
        foreach ($this->getDocumentosParaProcessamento() as $codigoDocumento) {
            $this->cancelarDocumento($codigoDocumento);
        }
        $oDaoAberturaExercicio = new \cl_aberturaexercicioorcamento();
        $where = "c104_ano = {$this->ano}  and c104_instit = {$this->instituicao->getCodigo()}";
        $oDaoAberturaExercicio->excluir(null, $where);
        if ($oDaoAberturaExercicio->erro_status == 0) {
            $msg = "Ocorreu algo inexperado ao excluir a abertura do ";
            $msg .= "exercício contabil. {$oDaoAberturaExercicio->erro_msg}";
            throw new \Exception($msg);
        }
        return true;
    }

    /**
     * Processa os
     * @throws \Exception
     */
    public function processar()
    {

        $this->abertura = \AberturaExercicioOrcamento::getInstanciaPorAnoInstituicao(
            $this->ano,
            $this->instituicao->getCodigo()
        );
        $this->abertura->setCodigoUsuario(db_getsession("DB_id_usuario"));
        $this->abertura->setCodigoInstituicao(db_getsession("DB_instit"));
        $this->abertura->setAno($this->ano);
        $this->abertura->setDataProcessamento(new \DBDate(date('Y-m-d', db_getsession("DB_datausu"))));
        $this->abertura->setProcessado(true);
        $this->abertura->salvar();
        foreach ($this->getDocumentosParaProcessamento() as $documento) {
            $this->processarDocumento($documento);
        }
    }

    /**
     * Processa os dados do lancamento Contábil
     * @param $documento
     * @throws \Exception
     */
    protected function processarDocumento($documento)
    {
        /**
         *  1 - inserir na tabela de encerramento. Ok
         *  2 - buscar a query com os dados do documnento para serem lancados - Ok
         *  3 - Realizar os lancamentos - ok
         */
        $sql = $this->getConsultaLancamentosDoDocumento($documento);
        $rsLancamentos = db_query($sql);
        if (!$rsLancamentos) {
            $mensagem = "Não foi possível executar regra para definição dos lançamentos do ";
            $mensagem .= "documento {$documento} " . pg_last_error();
            throw new \Exception($mensagem);
        }

        $instancia = $this;
        $eventoContabil = new EventoContabil($documento, $this->ano);
        \db_utils::makeCollectionFromRecord($rsLancamentos, function ($dados) use (
            $instancia,
            $documento,
            $eventoContabil
        ) {
            $instancia->mensagensLog = array();
            $lancamentoAuxiliar = $instancia->getLancamentoAuxiliar($documento, $dados);
            if (empty($lancamentoAuxiliar)) {
                throw new \Exception("Não foi possível executar identificar o o lancamento. ");
            }
            $codigoLancamento = $eventoContabil->executaLancamento($lancamentoAuxiliar, $instancia->data->getDate());
            if (!empty($instancia->mensagensLog)) {
                $instancia->logger->warning("Código do Lançamento: " . $codigoLancamento);
                $instancia->logger->write(implode("\n", $instancia->mensagensLog) . "\n");
            }
        });
    }

    /**
     * Retorna o lancamento auxiliar
     * @param $documento
     * @param $dados
     * @return \LancamentoAuxiliarAberturaExercicioOrcamento
     */
    protected function getLancamentoAuxiliar($documento, $dados)
    {
        switch ($documento) {
            case Documento::ABERTURA_ORCAMENTO_RECEITA:
                $observacao = 'Lançamento automático de abertura do exercício de ' . $this->ano;
                $receita = \ReceitaContabilRepository::getReceitaByCodigo($dados->receita, $this->ano);
                $lancamentoAuxiliarAberturaExercicio = new \LancamentoAuxiliarAberturaExercicioOrcamento();
                $lancamentoAuxiliarAberturaExercicio->setObservacaoHistorico($observacao);
                $lancamentoAuxiliarAberturaExercicio->setValorTotal(abs($dados->valor));
                $lancamentoAuxiliarAberturaExercicio->setHistorico(9600);
                $lancamentoAuxiliarAberturaExercicio->setReceita($receita);
                $lancamentoAuxiliarAberturaExercicio->setAberturaExercicioOrcamento($this->abertura->getCodigo());
                return $lancamentoAuxiliarAberturaExercicio;

            case Documento::ABERTURA_ORCAMENTO_DESPESA:
                $dotacao = new \Dotacao(null, null);
                $dotacao->setValor($dados->valor);
                $dotacao->setCodigo($dados->dotacao);
                $dotacao->setAno($dados->ano);
                $observacao = 'Lançamento automático de abertura da despesa do exercício de ' . $this->ano;
                $lancamentoAuxiliarAberturaExercicio = new \LancamentoAuxiliarAberturaExercicioOrcamento();
                $lancamentoAuxiliarAberturaExercicio->setObservacaoHistorico($observacao);
                $lancamentoAuxiliarAberturaExercicio->setValorTotal(abs($dados->valor));
                $lancamentoAuxiliarAberturaExercicio->setHistorico(9600);
                $lancamentoAuxiliarAberturaExercicio->setDotacao($dotacao);
                $lancamentoAuxiliarAberturaExercicio->setAberturaExercicioOrcamento($this->abertura->getCodigo());
                return $lancamentoAuxiliarAberturaExercicio;

            case Documento::ABERTURA_TRANSFERENCIA_SALDOS_RPNP_EX_ANT:
            case Documento::ABERTURA_TRANSFERENCIA_SALDOS_RPP_EX_ANT:
                $observacao = 'Lançamento automático de transferência de saldos de RPP do exercício de ' . $this->ano;
                $lancamentoAuxiliarAberturaExercicio = new \LancamentoAuxiliarAberturaExercicioOrcamento();
                $lancamentoAuxiliarAberturaExercicio->setCodigoElemento($dados->desdobramento);
                $lancamentoAuxiliarAberturaExercicio->setObservacaoHistorico($observacao);
                $lancamentoAuxiliarAberturaExercicio->setAberturaExercicioOrcamento($this->abertura->getCodigo());
                $lancamentoAuxiliarAberturaExercicio->setNumeroEmpenho($dados->empenho);
                $lancamentoAuxiliarAberturaExercicio->setFavorecido($dados->credor);
                $lancamentoAuxiliarAberturaExercicio->setValorTotal($dados->valor);
                return $lancamentoAuxiliarAberturaExercicio;
                break;

            case Documento::ABERTURA_TRANSFERENCIA_SALDOS_RPNP_INSCRITOS_EX_ANT:
            case Documento::ABERTURA_TRANSFERENCIA_SALDOS_RPP_INSCRITOS_EX_ANT:
                $observacao = 'Lançamento automático de transferência de saldos de RPNP do exercício de ' . $this->ano;
                $lancamentoAuxiliar = new \LancamentoAuxiliarAberturaExercicioOrcamento();
                $lancamentoAuxiliar->setObservacaoHistorico($observacao);
                $lancamentoAuxiliar->setAberturaExercicioOrcamento($this->abertura->getCodigo());
                $lancamentoAuxiliar->setValorTotal($dados->valor);
                $lancamentoAuxiliar->setContaCredito($dados->conta_credito);
                $lancamentoAuxiliar->setContaDebito($dados->conta_debito);
                if ($dados->inverter_lancamento == 't') {
                    $lancamentoAuxiliar->setInversaoContas(true);
                }
                if (!empty($dados->empenho)) {
                    $empenhoFinanceiro = \EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($dados->empenho);
                    $lancamentoAuxiliar->setCodigoElemento($empenhoFinanceiro->getDesdobramentoEmpenho());
                    $lancamentoAuxiliar->setFavorecido($empenhoFinanceiro->getCgm()->getCodigo());
                    $lancamentoAuxiliar->setNumeroEmpenho($dados->empenho);
                }
                return $lancamentoAuxiliar;
                break;
        }
        return null;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function possuiAberturaExercicioNoAnoInstituicao()
    {

        $oDaoAberturaExercicio = new \cl_aberturaexercicioorcamento();
        $where = "c104_ano = {$this->ano}
            and c104_instit = {$this->instituicao->getCodigo()}
            and c104_processado is true
        ";
        $sqlAberturaProcessada = $oDaoAberturaExercicio->sql_query_file(null, '*', null, $where);
        $buscaAbertura = db_query($sqlAberturaProcessada);
        if (!$buscaAbertura) {
            throw new \Exception("Ocorreu um erro para consultar a existência de abertura processada.");
        }
        return pg_num_rows($buscaAbertura) > 0;
    }
}
