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
 * Model utilizado para indentificação de um campo dentro de uma linha
 * @package configuracao
 * @author Felipe Nunes Ribeiro 
 * @revision $Author: dbandre.mello $
 * @version $Revision: 1.12 $    
 */
class DBLayoutLinha {
  
  private $sLinha;
  
  private $aPropriedadesCampos;
 	/**
   * Identifica se usa ou não o separador para quebrar a linha e determinar o valor do campo solicitado.
   * Se estiver setado para true e o separador for vazio, pega o valor pelas posição de início do campo.
   * @var boolean
   */
  private $lUsaSeparador;
  
  private $lUsaChr;
  
  private $sNomeCampo = ''; 
  /**
   * @param string $sLinha              // String da linha
   * @param array  $aPropriedadesCampos // Array com as propriedades do campo 
   *                                       Ex:  aPropriedadesCampos[nome_campo_layout][posicao_inicial_linha]
   *                                            aPropriedadesCampos[nome_campo_layout][posicao_final_linha]
   *                                            aPropriedadesCampos[nome_campo_layout][separador_campos]
   *                                            aPropriedadesCampos[nome_campo_layout][indice_campo] 
   *                                            (indice_campo somente para quando se utilizar o separador)
   * @param boolean $lUsaSeparador Identifica se usa ou não o separador para quebrar a linha e determinar o valor 
   * do campo solicitado. Se estiver setado para true e o separador for vazio, pega o valor pelas posição de 
   * início do campo.
   */
  function __construct($sLinha,$aPropriedadesCampos, $lUsaSeparador = false, $lUsaChr = false) {
    
    $this->sLinha              = $sLinha;
    $this->aPropriedadesCampos = $aPropriedadesCampos;
    $this->lUsaSeparador       = $lUsaSeparador;
    $this->lUsaChr             = $lUsaChr;
    $this->sNomeCampo          =  key($aPropriedadesCampos);
    
  }

  /**
   * Método mágico utilizado para retornar o valor do campo dentro da linha
   *
   * @param  string $sName // Nome do Campo
   * @return string        // Conteúdo do campo dentro da linha    
   */
  public function __get($sName){

    /**
    * Se estiver setado para usar o separador e o separador não for vazio.
    */  	
    if ($this->lUsaSeparador 
        && isset($this->aPropriedadesCampos[$sName][2]) 
        && !empty($this->aPropriedadesCampos[$sName][2])) {
      
      if ($this->lUsaChr) {
        eval('$s = '.$this->aPropriedadesCampos[$sName][2].';');
      }else{
        $s = $this->aPropriedadesCampos[$sName][2];
      }
      
      $aTmp = explode($s , $this->sLinha);
      $sValorRetorno = '';
      if (isset($aTmp[$this->aPropriedadesCampos[$sName][3]])) {
        $sValorRetorno = $aTmp[$this->aPropriedadesCampos[$sName][3]];
      }
      return $sValorRetorno;
           
    }
    
    $this->sNameTemp = $sName;
    $iPosIni = $this->aPropriedadesCampos[$sName][0];
    $iPosFim = $this->aPropriedadesCampos[$sName][1];
    
    return trim(substr($this->sLinha, ($iPosIni-1), $iPosFim));
    
  }

  /**
   * Método mágico utizado para determinar se existe ou não um campo do layout
   *
   * @param  string $sName // Nome do Campo
   * @return boolean       // Existe ou não o campo no layout
   */
  public function __isset($sName){

    return isset($this->aPropriedadesCampos[$sName]);

  }

  /**
   * Retorna o nome do campo 
   *
   * @return unknown
   */
  function getNomeLinha() {
  	return $this->sNomeCampo;
  }
  
  public function getProperties() {
    return $this->aPropriedadesCampos;
  }
  
  public function getLinha() {
    return $this->sLinha;
  }
}

?>