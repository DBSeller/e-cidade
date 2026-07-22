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
 * Salva os lançamentos auxiliares do Tef
 * @author rafael.lopes <rafael.lopes@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.0 $
 */
class LancamentoAuxiliarTef extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {


    public $iDocumento;

    public function setDocumento($iDocumento)
    {
      $this->iDocumento = $iDocumento;
    }

    public function getDocumento()
    {
        return $this->iDocumento;
    }

	/**
	 * Complemento para o lançamento contábil
	 * @var string
	 */
	protected $sComplemento;
   // protected $dtLancamento;


  /**
   * Dados da tabela conhist
   * @var integer
   */
  private $iHistorico;

  /**
   * Operacoesrealizadastef
   * @var Operacoesrealizadastef
   */
  private $oOperacoesrealizadastef;
  private $iOperacoesrealizadastef;
  /**
   * Variável de controle para sabermos se o lançamento é um estorno
   * @var boolean
   */
  protected $lEstorno = false;

  /**
   * Executa os lançamentos auxiliares dos Movimentos de uma liquidacao
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   * @param integer $iCodigoLancamento - Código do Lancamento (conlancam)
   * @param date    $dtLancamento      - data do lancamento
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)  {

    $this->setCodigoLancamento($iCodigoLancamento);
    $this->setDataLancamento($dtLancamento);

    parent::salvarVinculoComplemento();
    $this->salvarVinculoTef();
    return true;
  }

  /**
   * Salva o vínculo do lançamento com a Transacao TEF
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoTef() {

    $oDaoConLancamTef                  = new cl_conlancamtef;
    $oDaoConLancamTef->c137_sequencial = null;
    $oDaoConLancamTef->c137_codlan     = $this->iCodigoLancamento;
    $oDaoConLancamTef->c137_operacoesrealizadastef = $this->getOperacoesrealizadastef();
    $oDaoConLancamTef->incluir($oDaoConLancamTef->c137_sequencial);
    if ($oDaoConLancamTef->erro_status == "0") {

      $sMsgErro  = "Não foi possível salvar o vínculo da Transação TEF {$this->getOperacoesrealizadastef()} com o lançamento. ";
      $sMsgErro .= $oDaoConLancamTef->erro_msg;
      throw new BusinessException($sMsgErro);
    }
    $iDocumento = $this->getDocumento();
    $this->vincularAutenticacao($iDocumento);
    return true;
  }


  protected function vincularAutenticacao($iDocumento = null)
  {
    // executa somente para o documento 169
    if ($iDocumento != 169) {
      return;
    }

    $oDaoCorrente = new cl_corrente;
    $oDaoCorlanc = new cl_corlanc;
    $oDaoCorAutent = new cl_corautent;
    $oDadoCorHist = new cl_corhist;
    $oDaoConlancamCorrente = new cl_conlancamcorrente;
    $oDaocfautent = new cl_cfautent;
    $oDaoConlancamval = new cl_conlancamval;

    $hora = date("h:i");

    $sCampos  = "c69_debito,";
    $sCampos .= "c69_credito,";
    $sCampos .= "c69_data,";
    $sCampos .= "c69_valor,";
    $sCampos .= "c72_complem,";
    $sCampos .= "c69_codlan,";
    $sCampos .= "c02_instit";

    $aWhereLancamento = array();
    $aWhereLancamento[] = "c71_coddoc = {$iDocumento}";
    $aWhereLancamento[] = "c69_ordem = 1";
    $aWhereLancamento[] = "c86_sequencial is null";

    $sWhereLancamento = implode(" and ", $aWhereLancamento);
    $sqlLancamento = $oDaoConlancamval->sql_queryComplemento(null, $sCampos, null, "$sWhereLancamento");
    $rsLancamento = $oDaoConlancamval->sql_record( $sqlLancamento);

    if ($oDaoConlancamval->numrows > 0) {

      for ($i = 0; $i < $oDaoConlancamval->numrows; $i++) {

        $oDadosLancamento = db_utils::fieldsMemory($rsLancamento, $i);

        $contaDebito = $oDadosLancamento->c69_debito;
        $contaCredito = $oDadosLancamento->c69_credito;
        $data = $oDadosLancamento->c69_data;
        $valor = $oDadosLancamento->c69_valor;
        $instituicao = $oDadosLancamento->c02_instit;//db_getsession("DB_instit");
        $txtAutenticacao = "{$data}PMS CRED: {$contaCredito}DEB: {$contaDebito}     {$valor}";
        $complemento = $oDadosLancamento->c72_complem;
        $iLancamento = $oDadosLancamento->c69_codlan;


        /**
        * valida a autenticadora
        * separar essa logica em um model de autenticadora...
        * deve saber o seu ID e seu Proximo K12_AUTENT
        */
        $iIp = db_getsession("DB_ip");
        $where = "k11_ipterm = '{$iIp}' and k11_instit = {$instituicao}";
        $sSqlAutenticadora = $oDaocfautent->sql_query_file(null, "k11_id,k11_tipautent", '', $where);
        $rsAutenticador = $oDaocfautent->sql_record($sSqlAutenticadora);

        if ($oDaocfautent->numrows == '0') {
            throw new Exception("Cadastre o ip {$iIp} como um caixa.");
        }
        $iCodigoTerminal = db_utils::fieldsMemory($rsAutenticador, 0)->k11_id;

        $sqlAutent = "
                        SELECT MAX(K12_AUTENT)  as autent
                          FROM CORRENTE
                         WHERE K12_ID = {$iCodigoTerminal}
                           AND K12_DATA = '{$data}';
        ";
        $rsAutent = db_query($sqlAutent);
        $autent = db_utils::fieldsMemory($rsAutent, 0)->autent + 1;

        // cria a corrente
        $oDaoCorrente->k12_id = $iCodigoTerminal;
        $oDaoCorrente->k12_data = $data;
        $oDaoCorrente->k12_autent = $autent ;
        $oDaoCorrente->k12_hora = $hora;
        $oDaoCorrente->k12_conta  = $contaDebito;
        $oDaoCorrente->k12_valor  =  $valor;
        $oDaoCorrente->k12_estorn = "false";
        $oDaoCorrente->k12_instit = $instituicao;
        $oDaoCorrente->incluir($iCodigoTerminal, $data, $autent );
        if ($oDaoCorrente->erro_status == "0") {

            throw new Exception("[1] - Erro ao Criar Autenticação: " . $oDaoCorrente->erro_msg);
        }

        // cria corlanc
        $oDaoCorlanc->k12_id   = $iCodigoTerminal;
        $oDaoCorlanc->k12_data  = $data;
        $oDaoCorlanc->k12_autent = $autent;
        $oDaoCorlanc->k12_conta = $contaCredito;
        $oDaoCorlanc->k12_codigo = "0";
        $oDaoCorlanc->incluir( $iCodigoTerminal, $data, $autent);
        if ($oDaoCorlanc->erro_status == "0") {
            throw new Exception("[2] - Erro ao Criar Autenticação");
        }

        // cria corautent
        $oDaoCorAutent->k12_id = $iCodigoTerminal;
        $oDaoCorAutent->k12_data = $data;
        $oDaoCorAutent->k12_autent = $autent;
        $oDaoCorAutent->k12_codautent = $txtAutenticacao;
        $oDaoCorAutent->incluir($iCodigoTerminal, $data, $autent);
        if ($oDaoCorAutent->erro_status == "0") {
            throw new Exception("[3] - Erro ao Criar Autenticação");
        }

        // cria corhist
        $oDadoCorHist->k12_id = $iCodigoTerminal;
        $oDadoCorHist->k12_data = $data;
        $oDadoCorHist->k12_autent = $autent;
        $oDadoCorHist->k12_histcor = $complemento;
        $oDadoCorHist->incluir($iCodigoTerminal, $data, $autent);
        if ($oDadoCorHist->erro_status == "0") {
            throw new Exception("[4] - Erro ao Criar Autenticação");
        }

        // cria conlancamcorrente
        $oDaoConlancamCorrente->c86_sequencial = null;
        $oDaoConlancamCorrente->c86_id = $iCodigoTerminal;
        $oDaoConlancamCorrente->c86_data = $data;
        $oDaoConlancamCorrente->c86_autent = $autent;
        $oDaoConlancamCorrente->c86_conlancam = $iLancamento;
        $oDaoConlancamCorrente->incluir(null);
        if ($oDaoConlancamCorrente->erro_status == "0") {
            throw new Exception("[5] - Erro ao Criar Autenticação");
        }

      }

    }

  }





  public function seDataLancamento($dtLancamento)
  {
    $this->dtLancamento = $dtLancamento;
    //parent::setDataLancamento($dtLancamento);
  }

  public function getDataLancamento()
  {
    return $this->dtLancamento;
    //return parent::getDataLancamento();
  }

  /**
   *
   * @param Operacoesrealizadastef $oOperacoesrealizadastef
   * Operacoesrealizadastef
   */
  public function setOperacoesrealizadastef($iOperacoesrealizadastef) {
    $this->iOperacoesrealizadastef = $iOperacoesrealizadastef;
  }

  /**
   * Retorna o objeto Operacoesrealizadastef
   * @return Operacoesrealizadastef
   */
  public function getOperacoesrealizadastef() {
    return $this->iOperacoesrealizadastef;
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
   * Seta valor para o complemento do lançamento contábil
   * @see LancamentoAuxiliarBase::setObservacaoHistorico()
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
  	$this->sComplemento = $sObservacaoHistorico;
  }

  /**
   * Retorna o complemento do lançamento contábil
   * @see LancamentoAuxiliarBase::getObservacaoHistorico()
   */
  public function getObservacaoHistorico() {
  	return $this->sComplemento;
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
   * irá construir um lançamento auxiliar do tipo tef
   * @param integer $iCodigoLancamento (codlan)
   * @return object $oLancamentoAuxiliar
   */
  public static function getInstance($iCodigoLancamento){

  	$oDaoConlancamTef  = new cl_conlancamtef;
  	$sCampos             = "operacoesrealizadastef.*, conlancam.*, conlancamcompl.*";

  	// buscamos o historico do lancamento
  	$sSqlConLancamTef = $oDaoConlancamTef->sql_query_dadoslancamento(null, "*", null, "c72_codlan = {$iCodigoLancamento}");
  	$rsConLancamTef   = $oDaoConlancamTef->sql_record($sSqlConLancamTef);

  	if ($oDaoConlancamTef->numrows == 0) {
  		throw new BusinessException("Vinculo do lançamento {$iCodigoLancamento} com a Operação TEF não encontrado.");
  	}
  	$oConLancamTef          = db_utils::fieldsMemory($rsConLancamTef, 0);
  	//$oTef                   = new Operacoesrealizadastef($oConLancamTef->k198_sequencial);
  	$oLancamentoauxiliarTef = new LancamentoAuxiliarTef();
  	$oLancamentoauxiliarTef->setValorTotal($oConLancamTef->c70_valor);
  	$oLancamentoauxiliarTef->setOperacoesrealizadastef($oConLancamTef->k198_sequencial);
  	$oLancamentoauxiliarTef->setObservacaoHistorico($oConLancamTef->c72_complem);

  	return $oLancamentoauxiliarTef;
  }

}
?>
