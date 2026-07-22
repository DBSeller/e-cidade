<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use App\Domain\Tributario\Arrecadacao\Models\Disarq;
use App\Domain\Tributario\Arrecadacao\Models\Disbanco;
use App\Domain\Tributario\Arrecadacao\Repositories\CadbanRepository;
use App\Domain\Tributario\Arrecadacao\Repositories\DisarqRepository;
use App\Domain\Tributario\Arrecadacao\Repositories\DisbancoRepository;
use App\Domain\Tributario\Arrecadacao\Repositories\OperacoesrealizadastefRepository;
use AutenticacaoBaixaBanco;
use LancamentoAuxiliarTef;
use LancamentoAuxiliarContaTef;
use LancamentoAuxiliarArrecadacaoReceita;
use EventoContabil;
use DBDate;
use ECidade\Lib\Session\DefaultSession;

class TEFBaixaBancoService
{
    /**
     * @var
     */
    private $codRet;

    /**
     * @var DefaultSession
     */
    private $defaultSession;

    /**
     * @var false|string
     */
    private $data;

    /**
     * @var integer
     */
    private $numpre;

    /**
     * @var string
     */
    private $valor;

    /**
     * @var integer
     */
    private $conta;

    /**
     * @var integer
     */
    private $banco;

    /**
     * @var integer
     */
    private $agencia;

    /**
     * TEFBaixaBancoService constructor.
     */
    public function __construct()
    {
        $this->defaultSession = DefaultSession::getInstance();

        $this->data = date("Y-m-d", $this->defaultSession->get(DefaultSession::DB_DATAUSU));
    }

    /**
     * @param int $numpre
     * @return TEFBaixaBancoService
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
        return $this;
    }

    /**
     * @param string $valor
     * @return TEFBaixaBancoService
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @param int $conta
     * @return TEFBaixaBancoService
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    /**
     * Executa a baixa de banco automatica para o TEF
     * @throws \Exception
     */
    public function baixaAutomaticaDebito()
    {
        $this->buscarBancoAgencia();
        $this->salvarDisarq();
        $this->salvarDisbanco();
        $this->executaBaixaBanco();
        $this->autenticar();
        $this->executaLancamentoContabilTef();
        $this->confirmaBaixaOperacoes();
    }

    /**
     * Busca o banco e agência que os débitos pagos por TEF vão ficar vinculados
     */
    private function buscarBancoAgencia()
    {
        $cadbanRepository = new CadbanRepository();
        $oCadban = $cadbanRepository->getBancoAgenciaTef();

        if (!$oCadban) {
            throw new \Exception("Nenhum banco configurado para TEF.");
        }

        $this->banco = $oCadban->k15_codbco;
        $this->agencia = $oCadban->k15_codage;
    }

    /**
     * Salva os dados na tabela disarq
     * @throws \Exception
     */
    private function salvarDisarq()
    {
        $disarqRepository = new DisarqRepository();
        $disarq = new Disarq();

        $disarq->setCodbco($this->banco);
        $disarq->setCodage($this->agencia);
        $disarq->setArqret("BAIXA AUTOMÁTICA POR TEF");
        $disarq->setDtretorno($this->data);
        $disarq->setDtarquivo($this->data);
        $disarq->setConta($this->conta);
        $disarq->setAutent("false");
        $disarq->setIdUsuario($this->defaultSession->get(DefaultSession::DB_ID_USUARIO));
        $disarq->setInstit($this->defaultSession->get(DefaultSession::DB_INSTIT));

        $this->codRet = $disarqRepository->salvar($disarq);
    }

    /**
     * Salva os dados na tabela disbanco
     * @throws \Exception
     */
    private function salvarDisbanco()
    {
        $disbancoRepository = new DisbancoRepository();
        $disbanco = new Disbanco();

        $disbanco->setCodbco($this->banco);
        $disbanco->setCodage(trim($this->agencia));
        $disbanco->setCodret($this->codRet);
        $disbanco->setDtarq($this->data);
        $disbanco->setDtpago($this->data);
        $disbanco->setDtcredito($this->data);
        $disbanco->setVlrpago($this->valor);
        $disbanco->setVlrtot($this->valor);
        $disbanco->setVlrcalc("0");
        $disbanco->setNumpre($this->numpre);
        $disbanco->setNumpar("0");
        $disbanco->setClassi("false");
        $disbanco->setInstit($this->defaultSession->get(DefaultSession::DB_INSTIT));
        $disbanco->setBancopagamento(trim($this->banco));
        $disbanco->setAgenciapagamento(trim($this->agencia));

        $disbancoRepository->salvar($disbanco);
    }

    /**
     * Executa a baixa do débito
     * @throws \Exception
     */
    private function executaBaixaBanco()
    {
        $oRetorno = fc_executa_baixa_banco($this->codRet, $this->data);
        if ($oRetorno->processado == "f") {
            throw new \Exception("Erro na baixa de banco: {$oRetorno->descricao}");
        }
    }

    /**
     * Faz a autenticação da baixa
     * @throws \BusinessException
     * @throws \ParameterException
     */
    private function autenticar()
    {
        $disarqRepository = new DisarqRepository();
        $oDisarq = $disarqRepository->getCodclaByCodretAndInstit(
            $this->codRet,
            $this->defaultSession->get(DefaultSession::DB_INSTIT),
            ["codcla"]
        );

        $autenticacaoBaixaBanco = new AutenticacaoBaixaBanco($oDisarq->codcla);
        $autenticacaoBaixaBanco->setTef(true); // set para executar os lançamentos de doc 165
        $autenticacaoBaixaBanco->autenticar();
    }

    private function confirmaBaixaOperacoes()
    {
        $operacoesrealizadastefRepository = new OperacoesrealizadastefRepository();
        $aOperacoesconfirmadastef = $operacoesrealizadastefRepository->getAllConfirmadasAutorizadoraByNumnov(
            $this->numpre
        );

        foreach ($aOperacoesconfirmadastef as $oOperacaoconfirmadatef) {
            $oOperacaoconfirmadatef->setConcluidobaixabanco(true)->save();
        }
    }

    private function executaLancamentoContabilTef()
    {
        $operacoesrealizadastefRepository = new OperacoesrealizadastefRepository();
        $aOperacoesconfirmadastef = $operacoesrealizadastefRepository->getAllConfirmadasAutorizadoraByNumnov(
            $this->numpre
        );
        $iCodigoDocumento = 167;
        $lEstorno = false;
        $iAnoSessao = db_getsession('DB_anousu');
        $data = date("Y-m-d", db_getsession("DB_datausu"));
        foreach ($aOperacoesconfirmadastef as $oOperacaoconfirmadatef) {
            $nValor = $oOperacaoconfirmadatef->getValor();
            $sObservacaoHistorico = "Arrecadação de receita via TEF.";

            $oLancamentoAuxiliar = new LancamentoAuxiliarTef();
            $oLancamentoAuxiliar->setObservacaoHistorico($sObservacaoHistorico);
            $oLancamentoAuxiliar->setValorTotal($nValor);
            $oLancamentoAuxiliar->setHistorico(9800);
            $oLancamentoAuxiliar->setEstorno($lEstorno);
            $oLancamentoAuxiliar->setOperacoesrealizadastef($oOperacaoconfirmadatef->getSequencial());

            $oEventoContabil = new EventoContabil($iCodigoDocumento, $iAnoSessao);
            $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $data);
        }
    }
}
