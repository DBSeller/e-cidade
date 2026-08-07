<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));
/**
 * Model que executa os lancamentos auxiliares de depreciação.
 * @author     raphael lopes
 * @package    contabilidade
 * @subpackage lancamento
 * @version    1.0 $
 */
class LancamentoAuxiliarDepreciacao extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  private $oClassificacao;

  /**
   * chave bensdepreciacaolancamento
   * @var integer
   */
  private $iBensDepreciacaoLancamento;

  /**
   * Codigo do histórico
   * @var integer
   */
  private $iHistorico;

  /**
   * Valor total
   * @var float
   */
  private $nValorTotal;

  /**
   * Variável de controle para sabermos se o lançamento auxiliar é um estorno ou execução
   * @var boolean
   */
  private $lEstorno;

  /**
   * Código da conta
   * @var integer
   */
  private $iCodigoConta;

  /**
   * Seta o codigo chave bensdepreciacaolancamento
   * @param integer $iCodigoLancamentoDepreciacao
   */
  public function setBensDepreciacaoLancamento($iBensDepreciacaoLancamento) {
    $this->iBensDepreciacaoLancamento = $iBensDepreciacaoLancamento;
  }

  public function setClassificacao(BemClassificacao $oClassificacao)
  {
     $this->oClassificacao = $oClassificacao;
  }

  public function getClassificacao()
  {
      return $this->oClassificacao;
  }

  /**
   * Seta se o lançamento é um estorno
   * @param boolean $lEstorno
   */
  public function setEstorno($lEstorno) {
    $this->lEstorno = $lEstorno;
  }

  /**
   * Retorna se o lançamento é um estorno
   * @return boolean
   */
  public function isEstorno() {
    return $this->lEstorno;
  }

  /**
   * Retorna o codigo do  chave bensdepreciacaolancamento
   * @return integer
   */
  public function getBensDepreciacaoLancamento() {
    return $this->iBensDepreciacaoLancamento;
  }


  /**
   * Executa vinculacao do lancamento
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento  ($dtLancamento);
    parent::salvarVinculoComplemento();
    $this->salvarVinculoDepreciacao();

    return true;
  }

  /**
   * Vinculo da depreciacao com o lancamento contabil
   * @throws DBException
   * @return boolean
   */
  protected function salvarVinculoDepreciacao() {

    $oDaoConLancamDepreciacao = db_utils::getDao('conlancamdepreciacao');
    $oDaoConLancamDepreciacao->c106_bensdepreciacaolancamento = $this->getBensDepreciacaoLancamento();
    $oDaoConLancamDepreciacao->c106_codlan                    = $this->iCodigoLancamento;
    $oDaoConLancamDepreciacao->c106_sequencial = null;
    $oDaoConLancamDepreciacao->incluir(null);
    if ($oDaoConLancamDepreciacao->erro_status == "0") {
      throw new DBException("Não foi possível salvar o vínculo da depreciação com o lançamento.");
    }
    return true;
  }

  /**
   * Seta o valor total
   * @param float $nValorTotal
   * @see ILancamentoAuxiliar::setValorTotal()
   */
  public function setValorTotal($nValorTotal){
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * Retorna o valor total
   * @return float $nValorTotal
   */
  public function getValorTotal(){
    return $this->nValorTotal;
  }

  /**
   * Retorna o histórico da operação
   * @return integer
   */
  public function getHistorico(){
    return $this->iHistorico;
  }


  /**
   * Seta o histórico da operação
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico){
    $this->iHistorico = $iHistorico;
  }

  /**
   * Retorna o código da conta
   * @return integer
   */
  public function getCodigoConta() {
    return $this->iCodigoConta;
  }
  /**
   * Seta valor para a conta
   * @param integer $iCodigoConta
   */
  public function setCodigoConta($iCodigoConta) {
    $this->iCodigoConta = $iCodigoConta;
  }
}
?>
