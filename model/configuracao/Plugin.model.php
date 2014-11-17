<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


/**
 * Classe responsável por gerenciar a criação e alteração do plugin.
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package configuracao
 */
class Plugin{

  private $iCodigo;

  private $sNome;

  private $sLabel;

  private $lSituacao;

  const MENSAGEM = 'configuracao.configuracao.plugin.';

  /**
   * Função construtora, instancia os atributos da classe, 
   * se for informado um plugin existente.
   * 
   * @var integer Codigo do plugin.
   */
  public function __construct($iPlugin = null, $sNome = null) {

    if (!$iPlugin && !$sNome) {
      return true;
    }

    $oDaoPlugin = db_utils::getDao('db_plugin');
    $sSqlPlugin = $oDaoPlugin->sql_query_file($iPlugin, "*", null, ($sNome ? " db145_nome = '{$sNome}' " : ""));
    $rsPlugin   = db_query($sSqlPlugin);

    if ($iPlugin && pg_num_rows($rsPlugin) == 0) {
      throw new DBException(_M(self::MENSAGEM . 'erro_plugin'));
    }

    if ($sNome && pg_num_rows($rsPlugin) == 0) {
      return true;
    }

    $oPlugin = db_utils::fieldsMemory($rsPlugin, 0);

    $this->iCodigo   = $oPlugin->db145_sequencial;  
    $this->sNome     = $oPlugin->db145_nome;  
    $this->sLabel    = $oPlugin->db145_label;  
    $this->lSituacao = $oPlugin->db145_situacao == "t";

    return;
  }

  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  public function getCodigo() {
    return $this->iCodigo;
  }

  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  public function getNome(){
    return $this->sNome;
  }

  public function setLabel($sLabel) {
    $this->sLabel = $sLabel;
  }

  public function getLabel(){
    return $this->sLabel;
  }

  public function setSituacao($lSituacao) {
    $this->lSituacao = $lSituacao;
  }

  public function getSituacao(){
    return $this->lSituacao;
  }

  public function isAtivo(){
    return $this->lSituacao;
  }

  /**
   * Salva o plugin na tabela db_plugin
   * @return boolean
   */
  public function salvar() {

    $oDaoPlugin = db_utils::getDao('db_plugin');
    $oDaoPlugin->db145_nome       = $this->sNome;
    $oDaoPlugin->db145_label      = $this->sLabel;
    $oDaoPlugin->db145_situacao   = ($this->lSituacao) ? 'true' : 'false';
    $oDaoPlugin->incluir(null);

    if ($oDaoPlugin->erro_status == "0") {
      throw new DBException(_M(self::MENSAGEM . 'erro_inserir'));
    }

    return true;
  }

  /**
   * Altera a situação do plugin
   * @return boolean
   */
  public function alterarSituacao() {

    $oDaoPlugin = db_utils::getDao('db_plugin');
    $oDaoPlugin->db145_situacao   = ($this->lSituacao) ? 'true' : 'false';
    $oDaoPlugin->db145_sequencial = $this->iCodigo;
    $oDaoPlugin->alterar($this->iCodigo);

    if ($oDaoPlugin->erro_status == '0') {
      throw new DBException(_M(self::MENSAGEM . 'erro_situacao'));
    }

    return true;
  }

  /**
   * Exclui o plugin.
   * @return boolean.
   */
  public function excluir(){

    $oDaoPlugin = db_utils::getDao('db_plugin');
    $oDaoPlugin->excluir($this->iCodigo);

    if ($oDaoPlugin->erro_status == '0') {
      throw new DBException(_M(self::MENSAGEM . 'erro_excluir'));
    }

    return true;
  }
}
?>