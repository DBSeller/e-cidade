<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once ("std/DBDate.php");


class ArquivoTransmissao {

  /**
   * Código da Remessa (codgera)
   * @var integer
   */
  private $iCodigoRemessa;

  /**
   * Data da geração do arquivo
   * @var DBDate
   */
  private $dtDataGeracaoArquivo;

  /**
   * Data da autorização do pagamento
   * @var DBDate
   */
  private $dtDataAutorizacaoPagamento;

  /**
   * Hora da geração do arquivo
   * @var DBDate
   */
  private $sHoraGeracaoArquivo;

  /**
   * Array de movimentos vinculados ao arquivo
   * @var array<MovimentoArquivoTransmissao>
   */
  private $aMovimentos;

  /**
   * Descrição na Geração do arquivo
   * @var String
   */
  private $sDescricaoGeracao;


  /**
   * Instituição que gerou o arquivo
   * @var Instituicao
   */
  private $oInstituicao;


  public function getCodigoRemessa() {
    return $this->iCodigoRemessa;
  }

  public function setCodigoRemessa($iCodigoRemessa) {
    $this->iCodigoRemessa = $iCodigoRemessa;
  }

  public function getDataGeracaoArquivo() {
    return $this->dtDataGeracaoArquivo;
  }

  public function setDataGeracaoArquivo($dtGeracaoArquivo) {
    $this->dtDataGeracaoArquivo = $dtGeracaoArquivo;
  }

  public function getDataAutorizacaoPagamento(){
    return $this->dtDataAutorizacaoPagamento;
  }

  public function setDataAutorizacaoPagamento($dtAutorizacaoPagamento){
    $this->dtDataAutorizacaoPagamento = $dtAutorizacaoPagamento;
  }

  public function getHoraGeracaoArquivo(){
    return $this->sHoraGeracaoArquivo;
  }

  public function setHoraGeracaoArquivo($sHoraGeracaoArquivo) {
    $this->sHoraGeracaoArquivo = $sHoraGeracaoArquivo;
  }

  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  public function getInstituicao() {
    return $this->oInstituicao;
  }


  public function getDescricaoGeracao() {
    return $this->sDescricaoGeracao;
  }

  public function setDescricaoGeracao($sDescricao) {
    $this->sDescricaoGeracao = $sDescricao;
  }

  /**
   * Cria uma instância da classe, de acordo com os dados do arquivo de transmissão de codigo $iCodigoRemesssa
   * @param integer $iCodigoRemessa
   * @throws BusinessException
   * @return ArquivoTransmissao
   */
  public static function getInstance($iCodigoRemessa) {

    $oDaoEmpagegera = db_utils::getDao("empageconfgera");
    $sCampos        = "distinct e80_instit, empagegera.*";
    $sWhere         = "e87_codgera  = {$iCodigoRemessa}";
    $sSql           = $oDaoEmpagegera->sql_query_arq(null, null, $sCampos, null, $sWhere);
    $rsResultado    = $oDaoEmpagegera->sql_record($sSql);

    if ($oDaoEmpagegera->numrows > 1 ||  $oDaoEmpagegera->numrows == 0) {
      throw new BusinessException("Não foi possível buscar os dados da remessa.");
    }

    $oStdDadosGeracao    = db_utils::fieldsMemory($rsResultado, 0);
    $oArquivoTransmissao = new ArquivoTransmissao();
    $oArquivoTransmissao->setCodigoRemessa($oStdDadosGeracao->e87_codgera);
    $oArquivoTransmissao->setDataAutorizacaoPagamento(new DBDate($oStdDadosGeracao->e87_data));
    $oArquivoTransmissao->setDataGeracaoArquivo(new DBDate($oStdDadosGeracao->e87_dataproc));
    $oArquivoTransmissao->setHoraGeracaoArquivo($oStdDadosGeracao->e87_hora);
    $oArquivoTransmissao->setInstituicao(new Instituicao($oStdDadosGeracao->e80_instit));
    $oArquivoTransmissao->setDescricaoGeracao($oStdDadosGeracao->e87_descgera);
    return $oArquivoTransmissao;
  }

  /**
   * Lazy load para buscar os movimentos
   * @return array MovimentoArquivoTransmissao
   */
  public function getMovimentos () {

    if (!empty($this->aMovimentos)) {
      return $this->aMovimentos;
    }

    $iRemessa          = $this->iCodigoRemessa;
    $iInstituicao      = $this->oInstituicao->getSequencial();
    $iAno              = $this->dtDataGeracaoArquivo->getAno();

    $sSqlMovimentacao  = MovimentoArquivoTransmissao::getSqlDadosMovimentacao($iRemessa, $iInstituicao, $iAno);
    $rsMovimentacao    = db_query($sSqlMovimentacao);

    if (!$rsMovimentacao) {
      throw new DBException("Impossível buscar os movimentos do arquivo.");
    }

    $iQtdMovimentacoes = pg_num_rows($rsMovimentacao);
    $aMovimentos       = array();

    /**
     * Percorre as movimentações associadas ao arquivo de transmissão
     */
    for ($iMovimentacao = 0; $iMovimentacao < $iQtdMovimentacoes; $iMovimentacao++) {

      $oStdMovimento = db_utils::fieldsMemory($rsMovimentacao, $iMovimentacao);
      $oMovimento    = MovimentoArquivoTransmissao::montaObjetoLinha($oStdMovimento);
      $aMovimentos[$oMovimento->getCodigoMovimento()] = $oMovimento;
    }

    $this->aMovimentos = $aMovimentos;
    return $this->aMovimentos;
  }

  /**
   * Desvincula um movimento do arquivo de transmissão
   * @param  MovimentoArquivoTransmissao $oMovimento
   * @throws BusinessException
   * @throws DBException
   */
  public function desvincularMovimento (MovimentoArquivoTransmissao $oMovimento) {

    $aMovimentos      = $this->getMovimentos();
    $iCodigoMovimento = $oMovimento->getCodigoMovimento();

    if ( empty($aMovimentos[$iCodigoMovimento]) ) {
      throw new BusinessException("Movimento não associado ao arquivo");
    }

    $oDaoEmpageconfgera                = db_utils::getDao("empageconfgera");
    $oDaoEmpageconfgera->e90_codmov    = $oMovimento->getCodigoMovimento();
    $oDaoEmpageconfgera->e90_codgera   = $this->iCodigoRemessa;
    $oDaoEmpageconfgera->e90_cancelado = 'true';
    $oDaoEmpageconfgera->alterar($oDaoEmpageconfgera->e90_codmov, $oDaoEmpageconfgera->e90_codgera);

    if ($oDaoEmpageconfgera->erro_status == 0) {
      throw new DBException("Erro técnico: Erro ao desvincular movimento do arquivo de transmissão ");
    }
  }


  /**
   * Salva os atributos do arquivo no banco
   * @throws BusinessException
   * @return boolean
   */
  public function salvar() {

    $oDaoEmpAgeGera               = db_utils::getDao("empagegera");
    $oDaoEmpAgeGera->e87_codgera  = $this->iCodigoRemessa;
    $oDaoEmpAgeGera->e87_data     = $this->dtDataGeracaoArquivo->getDate(DBDate::DATA_EN);
    $oDaoEmpAgeGera->e87_hora     = $this->sHoraGeracaoArquivo;
    $oDaoEmpAgeGera->e87_dataproc = $this->dtDataAutorizacaoPagamento->getDate(DBDate::DATA_EN);
    $oDaoEmpAgeGera->e87_descgera = $this->sDescricaoGeracao;

    if (empty($this->iCodigoRemessa)) {
      $oDaoEmpAgeGera->incluir(null);
    } else {
      $oDaoEmpAgeGera->alterar($this->iCodigoRemessa);
    }

    if($oDaoEmpAgeGera->erro_status == 0){
      throw new BusinessException("Não foi possível incluir o cabeçalho da geração do arquivo. {$oDaoEmpAgeGera->erro_msg}");
    }

    $this->iCodigoRemessa = $oDaoEmpAgeGera->e87_codgera;
    return true;
  }

  /**
   * Vincula um movimento à um arquivo de transmissão
   * @param MovimentoArquivoTransmissao $oMovimento
   * @throws BusinessException
   */
  public function vinculaMovimento($iCodigoMovimento) {

    $oDaoEmpAgeConfGera                = db_utils::getDao("empageconfgera");
    $oDaoEmpAgeConfGera->e90_codmov    = $iCodigoMovimento;
    $oDaoEmpAgeConfGera->e90_codgera   = $this->iCodigoRemessa;
    $oDaoEmpAgeConfGera->e90_correto   = "true";
    $oDaoEmpAgeConfGera->e90_cancelado = "false";
    $oDaoEmpAgeConfGera->incluir($iCodigoMovimento, $this->iCodigoRemessa);

    if ($oDaoEmpAgeConfGera->erro_status == 0) {
      throw new BusinessException("Não foi possível vincular os movimentos na geração do arquivo.");
    }

    return true;
  }

}

?>