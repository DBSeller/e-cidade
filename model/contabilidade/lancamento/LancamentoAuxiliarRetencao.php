<?php
/**
 * Created by PhpStorm.
 * User: iuriag
 * Date: 19/12/18
 * Time: 13:39
 */

class LancamentoAuxiliarRetencao  extends LancamentoAuxiliarBase implements ILancamentoAuxiliar
{

    /**
     * @var EmpenhoFinanceiro
     */
    protected $empenho;

    /***
     * @var
     */
    protected $dotacao;


    /**
     * @var integer
     */
    protected $contaReduzida;

    /**
     *
     * @var bool
     */
    protected $estorno = false;


    /**
     * caracteristica Peculiar
     * @var string
     */
    protected $caracteristicaPecular = '000';

    /**
     * @var integer
     */
    protected $retencao;

    /**
     * Codigo da recita
     * @var integer
     */

    protected $receita;

    /**
     * @param int $iCodigoLancamento
     * @param string $dtLancamento
     */
    public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)
    {

        $this->salvarVinculoEmpenho();
        parent::setCodigoLancamento($iCodigoLancamento);
        parent::setDataLancamento($dtLancamento);
        parent::salvarVinculoComplemento();
        parent::salvarVinculoCgm();
        parent::salvarVinculoElemento();
        parent::salvarVinculoEmpenho();
        if (!empty($this->dotacao )) {
            parent::salvarVinculoDotacao();
        }


    }

    /**
     * @see ILancamentoAuxiliar::setHistorico()
     */
    public function setHistorico($iHistorico) {
        $this->iHistorico = $iHistorico;
    }

    /**
     * @see ILancamentoAuxiliar::getHistorico()
     */
    public function getHistorico() {
        return $this->iHistorico;
    }

    /**
     * @see ILancamentoAuxiliar::setValorTotal()
     */
    public function setValorTotal($nValorTotal) {
        $this->nValorTotal = $nValorTotal;
    }

    /**
     * @see ILancamentoAuxiliar::getValorTotal()
     */
    public function getValorTotal() {
        return $this->nValorTotal;
    }

    /**
     *  Incluindo vinculo do Lançamento com Empenho [conlancamemp]
     */
    protected function salvarVinculoEmpenho() {

        if (empty($this->empenho)) {
            return;
        }
        $oDaoConLanCamEmp = new cl_conlancamemp();
        $oDaoConLanCamEmp->c75_numemp = $this->empenho->getNumero();
        $oDaoConLanCamEmp->c75_data   = $this->dtLancamento;
        $oDaoConLanCamEmp->incluir($this->iCodigoLancamento);

        if ($oDaoConLanCamEmp->erro_status == 0) {

            $sErroMsg  = "Não foi possível vincular Lançamento e Empenho.\n\n";
            $sErroMsg .= "Erro Técnico : {$oDaoConLanCamEmp->erro_msg}";
            throw new BusinessException($sErroMsg);
        }
        unset($oDaoConLanCamEmp);
    }

    /**
     * @return int
     */
    public function getContaReduzida()
    {
        return $this->contaReduzida;
    }

    /**
     * @param int $contaReduzida
     */
    public function setContaReduzida($contaReduzida)
    {
        $this->contaReduzida = $contaReduzida;
    }



}