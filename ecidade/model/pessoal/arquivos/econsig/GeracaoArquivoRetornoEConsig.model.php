<?php

/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (c) 2014  DBSeller Servicos de Informatica             
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

/**
 * GeracaoArquivoRetornoEConsig
 * 
 * @package    Pessoal
 * @subpackage Arquivos/e-Consig
 * @version $Id: GeracaoArquivoRetornoEConsig.model.php,v 1.12 2014/12/02 17:58:43 dbrenan Exp $
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 */
class GeracaoArquivoRetornoEConsig {

  private $oLayoutArquivo;
  private $oCompetencia;
  private $oArquivoMovimentacao;
  private $oInstituicao;
  private $sArquivo;

  const MENSAGEM      = 'recursoshumanos.pessoal.GeracaoArquivoRetornoEConsig.';
  const CODIGO_LAYOUT = 220;

  /**
   * @param Instituicao   $oInstituicao
   * @param DBCompetencia $oCompetencia
   * @throws DBException
   */
  public function __construct( Instituicao $oInstituicao, DBCompetencia $oCompetencia = null) {

    $this->oArquivoMovimentacao = ArquivoEConsigRepository::getUltimoArquivo($oInstituicao, $oCompetencia);
    $this->oArquivoMovimentacao->carregarRegistros();

    $this->oInstituicao         = $oInstituicao;
    $this->oCompetencia         = $oCompetencia;

    $sSufixoArquivo             = '';
    $this->sArquivo             = "tmp/ArquivoRetornoEConsig{$sSufixoArquivo}.txt";
    $this->oLayoutArquivo       = new db_layouttxt(self::CODIGO_LAYOUT, $this->sArquivo );
  }

  /**
   * Processa os dados do e-consig para gerar o arquivo de retorno
   */
  public function processar() {

    $aRegistros = $this->getRegistros();

    if (count($aRegistros) == 0) {
      throw new BusinessException( _M(self::MENSAGEM . 'nenhum_registro'));
    }

    foreach ($aRegistros as $oRegistro ) {
      $this->oLayoutArquivo->setByLineOfDBUtils($oRegistro,3);
    }
  }

  /**
   * Retorna os registros da e-consig importados
   *
   * @return array
   * @throws BusinessException
   * @throws DBException
   */
  private function getRegistros() {
    
    $aRegistros = $this->oArquivoMovimentacao->getRegistros();
    $aRetorno   = array();

    $this->oArquivoMovimentacao->limparRegistros();

    foreach ( $aRegistros as $oRegistro ) {

      /**
       * Valida se o servidor teve 100% do seu desconto 
       * previsto realizado
       */
      $this->validarSituacaoDesconto($oRegistro);

      /**
       * Valida se o Servidor possuí algum afastamento, se possuí afastamento 
       * sem remuneração não pode ser realizado o desconto previsto
       */
      $this->validarAfastamento($oRegistro);

      /**
       * Valida se o servidor esta rescindido, se estiver insere na tabela econsigmovimentoservidor 
       * com o campo movtivo preenchido com o código.
       */
      $this->validarRescisao($oRegistro);

      /**
       * Valida se o servidor esta rescindido e o tipo de rescisao é falescimento, 
       * caso positivo cadastra o motivo do tipo 'Falecimento'.
       */
      $this->validaServidorFalecido($oRegistro);

      $oRetorno            = new stdClass();
      $oRetorno->matricula = "00000";
      $oRetorno->cpf       = "00000000000";
      $oRetorno->nome      = DBString::removerAcentuacao($oRegistro->getNome());

      if ( $oRegistro ) {

        $oRetorno->matricula = $oRegistro->getServidor()->getMatricula();
        $oRetorno->cpf       = $oRegistro->getServidor()->getCgm()->getCpf();
      }

      $oRetorno->estabelecimento    = $this->oInstituicao->getSequencial();
      $oRetorno->orgao              = $this->oInstituicao->getSequencial();
      $oRetorno->rubrica            = $oRegistro->getRubrica()->getCodigo();
      $oRetorno->desconto_previsto  = db_formatar($oRegistro->getValor(), "p", "0", 10);
      $oRetorno->desconto_realizado = db_formatar($oRegistro->getValorDescontado(),"p", "0", 10); ///Valor realmente descontado
      $oRetorno->competencia        = $this->oCompetencia->getMes().$this->oCompetencia->getAno();
      $oRetorno->situacao           = DBString::removerAcentuacao($this->getDescricaoSituacao($oRegistro->getMotivo())); //Situação
      $aRetorno[]                   = $oRetorno;

      $this->oArquivoMovimentacao->adicionarRegistro($oRegistro);
    }

    ArquivoEConsigRepository::persist($this->oArquivoMovimentacao);

    return $aRetorno;
  }

  /**
   * Valida a situação do desconto do Servidor, para verificar se o mesmo obteve todo o
   * desconto previsto, ou se recebeu um desconto parcial.
   *
   * @param RegistroPontoEconsig $oRegistro
   * @throws BusinessException
   * @throws DBException
   */
  private function validarSituacaoDesconto (RegistroPontoEconsig $oRegistro) {

    $oRubrica            = $oRegistro->getRubrica();
    $oServidor           = $oRegistro->getServidor();
    $oCalculoFinanceiro  = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
    $aEventosFinanceiros = $oCalculoFinanceiro->getEventosFinanceiros(0, $oRubrica->getCodigo());
        
    if (empty($aEventosFinanceiros)) {
        $oCalculoRescisao    = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_RESCISAO);
        $aEventosFinanceiros = $oCalculoRescisao->getEventosFinanceiros(0, $oRubrica->getCodigo());
    }


    if (!$aEventosFinanceiros) {
      $oRegistro->setMotivo(ArquivoEConsig::MOTIVO_MARGEM_EXCEDIDO);
    }

    /**
     * Verifica se existe desconto
     */
    if (isset($aEventosFinanceiros[0])) {

      /**
       * Seta o valor que realmente foi descontado do servidor
       */
      $oRegistro->setValorDescontado($aEventosFinanceiros[0]->getValor());

      /**
       * Verifica se o valor descontado é o mesmo que o valor previsto, caso não seja é porque o
       * servidor teve sua margem consignável excedida.
       */
      if ($aEventosFinanceiros[0]->getValor() != $oRegistro->getValor()) {

        $oRegistro->setMotivo(ArquivoEConsig::MOTIVO_MARGEM_EXCEDIDO);

        $oDaoMovimentoServidor                      = new cl_econsigmovimentoservidor();
        $oDaoMovimentoServidor->rh134_econsigmotivo = ArquivoEConsig::MOTIVO_MARGEM_EXCEDIDO;
        $oDaoMovimentoServidor->rh134_sequencial    = $oRegistro->getSequencial();

        $oDaoMovimentoServidor->alterar($oRegistro->getSequencial());

        if ($oDaoMovimentoServidor->erro_status == "0") {
          throw new BusinessException(_M(self::MENSAGEM . 'erro_alterar_econsigmovimentoservidor'));
        }
      }
    }
  }

  /**
   * Válida se o registro esta afastado
   *
   * @param RegistroPontoEconsig $oRegistro
   * @return RegistroPontoEconsig
   * @throws BusinessException
   */
  private function validarAfastamento(RegistroPontoEconsig $oRegistro) {
    
    $oDaoAfasta            = new cl_afasta();
    $oDaoMovimentoServidor = new cl_econsigmovimentoservidor();
    
    /**
     * Para a verificação do afastamento, o registro não pode ter nenhum motivo e
     * o valor do registro deve ser maior que o valor que foi desocntado no cálculo
     * 
     */
    if ($oRegistro->getValor() > $oRegistro->getValorDescontado()) {
        
      /**
       * O filtro está baseado na data do afastamento e não na competência no qual foi cadastrada
       */
      $sWhere = "date_part('year', r45_dtafas)  = {$this->oCompetencia->getAno()}
             AND date_part('month', r45_dtafas) = {$this->oCompetencia->getMes()}
             AND r45_regist                     = {$oRegistro->getServidor()->getMatricula()}";
              
      $sSqlAfastamento = $oDaoAfasta->sql_query_file(null, "r45_codigo", null, $sWhere);
      $rsAfastamento   = db_query($sSqlAfastamento);

      if (!$rsAfastamento) {
        throw new BusinessException(_M(self::MENSAGEM . 'erro_alterar_econsigmovimentoservidor'));
      }
      
      if (pg_num_rows($rsAfastamento) > 0) {
        
        $oRegistro->setMotivo(ArquivoEConsig::MOTIVO_SERVIDOR_AFASTADO);
        $oDaoMovimentoServidor->rh134_econsigmotivo = ArquivoEConsig::MOTIVO_SERVIDOR_AFASTADO;         
        $oDaoMovimentoServidor->rh134_sequencial    = $oRegistro->getSequencial();
        $oDaoMovimentoServidor->alterar($oRegistro->getSequencial());
        
        if ($oDaoMovimentoServidor->erro_status == '0') {
          throw new BusinessException(_M(self::MENSAGEM . 'erro_alterar_econsigmovimentoservidor'));
        }
      } 
    }
    
    return $oRegistro;
  }

  private function validarRescisao($oRegistro) {

    $oServidor = $oRegistro->getServidor();


    if ($oServidor->isRescindido()) {
      $oRegistro->setMotivo(ArquivoEConsig::MOTIVO_SERVIDOR_DESLIGADO);
    }
  }

  /**
   * Valida se o servidor informado, esta falecido, se estiver retorna true senão retorna false.
   *
   * @param integer $iMatricula
   * @return bool
   * @throws DBException
   */
  private function validaServidorFalecido($oRegistro) {

    $oDaoRhPesRescisao = new cl_rhpesrescisao();

    /**
     * Valida se o servidor possui uma das causas(60,62,64) 
     * se posusir é porque o mesmo esta falecido.
     */
    $sWherePesRescisao = "rh02_regist = {$oRegistro->getServidor()->getMatricula()} and r59_causa in (60, 62, 64)";
    $sSqlRhPesRescisao = $oDaoRhPesRescisao->sql_query_rescisao(null, '*', null, $sWherePesRescisao);
    $rsRhPesRescisao   = db_query($sSqlRhPesRescisao);

    if (!$rsRhPesRescisao) {
      throw new DBException( _M(self::MENSAGEM . 'erro_consultar_recisao') . $sSqlRhPesRescisao );
    }

    if (pg_num_rows($rsRhPesRescisao)) {
      $oRegistro->setMotivo(ArquivoEConsig::MOTIVO_FALECIMENTO);
    }
  }

  /**
   * Retorna a descrição do motivo do econsig a partir do sequencial informado como parâmetro
   *
   * @param integer $iMotivo
   * @return mixed
   * @throws DBException
   */
  private function getDescricaoSituacao($iMotivo){

    if (empty($iMotivo)){
      return '';
    }

    $oDaoEconsigMotivo = new cl_econsigmotivo();
    $sSqlEconsigMotivo = $oDaoEconsigMotivo->sql_query($iMotivo, 'rh147_motivo');
    $rsEconsigMotivo   = db_query($sSqlEconsigMotivo);

    if (!$rsEconsigMotivo) {
      throw new DBException(_M(self::MENSAGEM . 'erro_econsigmotivo'));
    }

    return db_utils::fieldsMemory($rsEconsigMotivo, 0)->rh147_motivo;
  }

  /**
   * Retorna o caminho do arquivo
   * @return string
   */
  public function getCaminhoArquivo() {
    return $this->sArquivo;
  }
}
