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

/**
 * Factory para controle do tipo de cálculo do item de um contrato
 * @author  matheus.felini@dbseller.com.br
 * @package contrato
 * @version $Revision: 1.6 $
 */
class AcordoItemTipoCalculoFactory {
  
  public function __construct() {
    
  }
  
  /**
   * Retorna a instâcia de um tipo de cálculo
   * @param  integer $iCodigoTipoCalculo - Tipo de Cálculo
   * @throws Exception
   */
  static function getInstance($iCodigoTipoCalculo) {
    
    switch ($iCodigoTipoCalculo) {
      
      /*
       * Divisão Mensal das Quantidades
       */
      case 1:

        require_once(modification("model/contrato/AcordoItemTipoCalculoMesQuantidade.model.php"));
        $oTipoCalculo = new AcordoItemTipoCalculoMesQuantidade();
      break;

      /*
       * Divisão Mensal de Valores (dias) 
       */
      case 2:

        require_once(modification("model/contrato/AcordoItemTipoCalculoMesDia.model.php"));
        $oTipoCalculo = new AcordoItemTipoCalculoMesDia();
      break;

      /*
       * Divisão Mensal de Valores (mês) 
       */
      case 3:
        
        require_once(modification("model/contrato/AcordoItemTipoCalculoMesComercial.model.php"));
        $oTipoCalculo = new AcordoItemTipoCalculoMesComercial();
      break;

      /*
       * Por Valor
       */
      case 4:
        
        require_once(modification("model/contrato/AcordoItemTipoCalculoValor.model.php"));
        $oTipoCalculo = new AcordoItemTipoCalculoValor();
        
      break;
        
      /*
       * Por Quantidade
       */
      case 5:
        
        require_once(modification("model/contrato/AcordoItemTipoCalculoQuantidade.model.php"));
        $oTipoCalculo = new AcordoItemTipoCalculoQuantidade();
      break;
      
      default:
        throw new Exception("Tipo de cálculo informado não existe.");
    }
    
    return $oTipoCalculo;
  }
}
?>