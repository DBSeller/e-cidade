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


class BarraProgressoCli {
  
  private $aCaracteres    = array(0 =>'\\', 1 => '|', 2 => '/', 3 => '-'); 
  private $iPonteiro      = 0;
  private $iContador      = 0;
  private $nTotalRegistros;
  private $iLarguraBarra;
  private $sSimboloProgresso;
  function __construct($nTotalRegistros, $iLarguraBarra = 70, $sSimbolo = "=") {
    
    $this->nTotalRegistros   = $nTotalRegistros;
    $this->iLarguraBarra     = $iLarguraBarra;
    $this->sSimboloProgresso = $sSimbolo;
  }
  
  function atualizar() {
    
    $this->iPonteiro++;
    $nPercentual     = round( $this->iPonteiro   * 100 / $this->nTotalRegistros, 2 );
    $sPercentual     = str_pad($nPercentual, 5, ' ', STR_PAD_LEFT);
    $iRegistro       = $this->iPonteiro;
    $nTotal          = $this->nTotalRegistros;
    $sSimbolo        = $this->aCaracteres[$this->iContador];
    $sFrase          = str_pad("$iRegistro de $nTotal ", 20, ' ',STR_PAD_RIGHT);
    
    $nTamanhoEspaco    = 100 / $this->iLarguraBarra;
    $nTamanhoProgreso  = (int)$nPercentual / $nTamanhoEspaco;
    $sEspacosProgresso = (int)$nPercentual == 0 ? '' : str_repeat( $this->sSimboloProgresso, $nTamanhoProgreso - 1) . $sSimbolo;
    $sBarra            = "[".str_pad($sEspacosProgresso, $this->iLarguraBarra, " ", STR_PAD_RIGHT)."]";
    
    echo "Processando Registro: $sFrase $sBarra [$sPercentual%]        \r";
    
    $this->iContador = ($this->iContador < 3) ? ( $this->iContador + 1 ) : 0;
    
    if ($this->nTotalRegistros == $this->iPonteiro) {
      
      $sEspacosProgresso = (int)$nPercentual == 0 ? '' : str_repeat( $this->sSimboloProgresso , $nTamanhoProgreso);
      $sBarra            = "[".str_pad($sEspacosProgresso, $this->iLarguraBarra, " ", STR_PAD_RIGHT)."]";
      
      echo "Completo            : $sFrase $sBarra [$sPercentual%]        \r";
      echo "\n\n";
    }
  }
}