<?php

namespace ECidade\Financeiro\Empenho;

class Autenticacao
{

    const AUTENTICACAO_EMPENHO = 1;

    const AUTENTICACAO_RETENCAO = 2;

    const ESTORNO_RETENCAO = 5;

    /**
     * @var \EmpenhoFinanceiro
     */
    private $empenhoFinanceiro;

    /**
     *  valor que deve ser Autenticado
     * @var float
     */
    private $valor;

    /**
     * Grupo de autenticacao
     * @var integer
     */
    private $grupoAutenticacao = null;

    /**
     * @var \Instituicao
     */
    private $instituicao = null;

    /**
     * movimento da agenda
     */
    private $movimento;
    /**
     * @var \DateTime
     */
    private $data;

    private $cheque = 0;

    private $chequeAgenda = 0;

    /**
     * @var integer
     */
    private $ordemPagamento;

    /**
     * Contrapartida Extra-Orcamentario
     * @var integer
     */
    private $contaExtraOrcamentaria;

    /**
     * @var
     */
    private $contaPagadora;


    /**
     * Tipo da Autenciacao
     * @var integer
     */
    private $tipoAutenticacao;

    /**
     * Autenticacao constructor.
     * @param \EmpenhoFinanceiro $empenhoFinanceiro
     * @param $movimento
     * @param $valor
     * @param \DateTime $data
     */
    public function __construct(\EmpenhoFinanceiro $empenhoFinanceiro, $movimento, $valor, \DateTime $data)
    {

        $this->empenhoFinanceiro = $empenhoFinanceiro;
        $this->valor = $valor;
        $this->movimento = $movimento;
        $this->data = $data;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function autenticar()
    {
        return $this->processar(1);
    }

    /**
     * Realiza o externo da o valor do empenho
     * @return bool
     * @throws \Exception
     */
    public function estornar()
    {
        return $this->processar(2);
    }

    /**
     * @return int
     */
    public function getContaExtraOrcamentaria()
    {
        return $this->contaExtraOrcamentaria;
    }

    /**
     * @param int $contaExtraOrcamentaria
     */
    public function setContaExtraOrcamentaria($contaExtraOrcamentaria)
    {
        $this->contaExtraOrcamentaria = $contaExtraOrcamentaria;
    }

    /**
     * @return mixed
     */
    public function getMovimento()
    {
        return $this->movimento;
    }

    /**
     * @param mixed $movimento
     */
    public function setMovimento($movimento)
    {
        $this->movimento = $movimento;
    }

    /**
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DateTime $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getCheque()
    {
        return $this->cheque;
    }

    /**
     * @param mixed $cheque
     */
    public function setCheque($cheque)
    {
        $this->cheque = $cheque;
    }

    /**
     * @return mixed
     */
    public function getChequeAgenda()
    {
        return $this->chequeAgenda;
    }

    /**
     * @param mixed $chequeAgenda
     */
    public function setChequeAgenda($chequeAgenda)
    {
        $this->chequeAgenda = $chequeAgenda;
    }

    /**
     * @return mixed
     */
    public function getContaPagadora()
    {
        return $this->contaPagadora;
    }

    /**
     * @param mixed $contaPagadora
     */
    public function setContaPagadora($contaPagadora)
    {
        $this->contaPagadora = $contaPagadora;
    }

    /**
     * @return int
     */
    public function getOrdemPagamento()
    {
        return $this->ordemPagamento;
    }

    /**
     * @param int $ordemPagamento
     */
    public function setOrdemPagamento($ordemPagamento)
    {
        $this->ordemPagamento = $ordemPagamento;
    }

    /**
     * @return int
     */
    public function getTipoAutenticacao()
    {
        return $this->tipoAutenticacao;
    }

    /**
     * @param int $tipoAutenticacao
     */
    public function setTipoAutenticacao($tipoAutenticacao)
    {
        $this->tipoAutenticacao = $tipoAutenticacao;
    }


    /**
     * Realiza o processo da autenticacao
     * @param $tipo
     * @param $contaPagadora
     * @return bool
     * @throws \Exception
     */
    private function processar($tipo)
    {
        $codigoGrupoAutenticacao = $this->grupoAutenticacao;
        if ($codigoGrupoAutenticacao == null) {
            throw new \Exception("Grupo da autenticação nao informado.\nProcessamento Cancelado");
        }

        $iCodigoAgenda = $this->movimento;
        $sIpUsuario = db_getsession("DB_ip");
        $iCodigoTipoGrupo = $this->getTipoAutenticacao();
        $nValorAutenticar = $this->valor;


        $oDaoAutentica = \db_utils::getDao("cfautent");
        $rsTipoAutentica = $oDaoAutentica->sql_record($oDaoAutentica->sql_query_file(null, "k11_tipautent",
            '',
            "k11_ipterm = '{$sIpUsuario}'
            and k11_instit = " . db_getsession("DB_instit")));
        if ($oDaoAutentica->numrows == 0) {

            $sErroMsg = "Cadastre o ip {$sIpUsuario} como um caixa na instituicao ".db_getsession("DB_instit")." antes de realizar o pagamento.";
            throw new \Exception($sErroMsg);
        }

        $dadosAutenticadora = \db_utils::fieldsMemory($rsTipoAutentica, 0);
        $sExecFuncao = "fc_autentemp";
        if ($tipo == 2) {

            $sExecFuncao = "fc_estornaemp";
            $nValorAutenticar *= -1;
        }

        $empenho = $this->empenhoFinanceiro;
        $ano = $this->data->format("Y");
        $dataAutenticacao = $this->data->format("Y-m-d");
        if ($dadosAutenticadora->k11_tipautent != 3) {

            if ($empenho->getAno() < $ano) {

                /*RESTO A PAGAR*/
                if ($this->getContaExtraOrcamentaria() == "") {
                    throw new \Exception("Verifique o tipo do RP e o cadastro das transações !");
                }
                $sSqlAut = "select {$sExecFuncao}({$empenho->getNumero()},";
                $sSqlAut .= $this->getContaPagadora() . ", ";
                $sSqlAut .= "{$this->getContaExtraOrcamentaria()},";
                $sSqlAut .= "'{$dataAutenticacao}',";
                $sSqlAut .= "{$this->valor},";
                $sSqlAut .= $this->getCheque() . ",";
                $sSqlAut .= "'{$sIpUsuario}',";
                $sSqlAut .= $this->getChequeAgenda() . ",";
                $sSqlAut .= "{$this->getOrdemPagamento()},";
                $sSqlAut .= db_getsession("DB_instit") . ", {$iCodigoAgenda}, {$codigoGrupoAutenticacao}, {$iCodigoTipoGrupo}) as retorno";


            } else {

                $sSqlAut = "select {$sExecFuncao}({$empenho->getNumero()},";
                $sSqlAut .= $this->getContaPagadora() . ", ";
                $sSqlAut .= "0,";
                $sSqlAut .= "'{$dataAutenticacao}',";
                $sSqlAut .= "{$nValorAutenticar},";
                $sSqlAut .= $this->getCheque() . ",";
                $sSqlAut .= "'{$sIpUsuario}',";
                $sSqlAut .= $this->getChequeAgenda() . ",";
                $sSqlAut .= "{$this->getOrdemPagamento()},";
                $sSqlAut .= db_getsession("DB_instit") . ", {$iCodigoAgenda}, $codigoGrupoAutenticacao,{$iCodigoTipoGrupo}) as retorno";
            }
            $rsAut = db_query($sSqlAut);
            if (!$rsAut) {

                $sErroMsg = "Erro na autenticação do empenho {$empenho->getCodigo()}/{$empenho->getAno()}.\n";
                $sErroMsg .= "Contate suporte." . pg_last_error();
                throw new \Exception($sErroMsg);
            }

            $oAut = \db_utils::fieldsMemory($rsAut, 0);
            $retornoAutentica = $oAut->retorno;

            if (substr($oAut->retorno, 0, 1) !== '1') {

                $sErroMsg = $oAut->retorno;
                throw new \Exception($sErroMsg);
            }
        }
        return $retornoAutentica;
    }

    /**
     * @return int
     */
    public function getGrupoAutenticacao()
    {
        return $this->grupoAutenticacao;
    }

    /**
     * @param int $grupoAutenticacao
     */
    public function setGrupoAutenticacao($grupoAutenticacao)
    {
        $this->grupoAutenticacao = $grupoAutenticacao;
    }




}