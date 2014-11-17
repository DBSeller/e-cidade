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

require_once ('ArquivoSiprevBase.model.php');

class ArquivoSiprevDependentes extends  ArquivoSiprevBase {
  
  protected $sNomeArquivo = "dependentes";
  
  public function getDados() {

  	$sSqlDados    = " SELECT distinct                                                                         \n";
    $sSqlDados   .= "        z01_nome,                                                                        \n"; 
    $sSqlDados   .= "        rh16_pis,                                                                        \n";
    $sSqlDados   .= "        z01_numcgm,                                                                      \n";
    $sSqlDados   .= "        rh31_nome,                                                                       \n";
    $sSqlDados   .= "        z01_sexo,                                                                        \n";
    $sSqlDados   .= "        rh31_irf,                                                                        \n";
    $sSqlDados   .= "        rh01_admiss,                                                                     \n";
    $sSqlDados   .= "        rh31_dtnasc,                                                                     \n";
    $sSqlDados   .= "        fc_idade(rh31_dtnasc, '".date('Y-m-d', db_getsession('DB_datausu'))."') as idade,\n";
    $sSqlDados   .= "        rh31_depend,                                                                     \n";
    $sSqlDados   .= "        z01_cgccpf,                                                                      \n";
    $sSqlDados   .= "        case when rh31_dtnasc > rh01_admiss                                              \n";
    $sSqlDados   .= "             then rh31_dtnasc                                                            \n";
    $sSqlDados   .= "             else rh01_admiss                                                            \n";
    $sSqlDados   .= "        end as inicio_depencia                                                           \n";
    $sSqlDados   .= "   from rhpessoal                                                                        \n";
    $sSqlDados   .= "        inner join rhdepend     on rh31_regist = rh01_regist                             \n";
    $sSqlDados   .= "        inner join rhpessoalmov on rh02_regist = rh01_regist                             \n";
    $sSqlDados   .= "        inner join cgm          on z01_numcgm  = rh01_numcgm                             \n";
    $sSqlDados   .= "        inner join rhpesdoc     on rh16_regist = rh01_regist                             \n";
    $sSqlDados   .= "        inner join rhregime     on rh02_codreg = rh30_codreg                             \n";
    $sSqlDados   .= "  where (                                                                                \n";
    $sSqlDados   .= "          (rh02_mesusu >= {$this->iMesInicial} and rh02_anousu >= {$this->iAnoInicial})  \n";
    $sSqlDados   .= "          and                                                                            \n";
    $sSqlDados   .= "          (rh02_mesusu <=  {$this->iMesFinal}  and rh02_anousu <= {$this->iAnoFinal})    \n";
    $sSqlDados   .= "        )                                                                                \n";
    $sSqlDados   .= "        and rh30_regime = 1                                                              \n";
    $sSqlDados   .= "        and (rh31_irf in('1','2','4','5'))                                               \n";
    $sSqlDados   .= "order by z01_nome ;                                                                      \n";

    $rsDados      = db_query($sSqlDados); 
    $aListaDados  = db_utils::getCollectionByRecord($rsDados);

    $aDados       = array();
    $aErros       = array();

    $sErro        = "";
    $sPis         = "";
    
    foreach ( $aListaDados as $oIndiceDados => $oValorDados ) {
    	
      $oValorDados->z01_sexo = strtoupper($oValorDados->z01_sexo);
      $oLinha                = new stdClass();
      $oLinha->dependencias  = new stdClass();
      $oLinha->servidor      = new stdClass();
      $oLinha->dadosPessoais = new stdClass();  
      
			switch($oValorDados->rh31_irf) {
			
			  case '1' :
			    $iTipoDependencia = 1;  
			  break;
			  			
			  case '2' :
			    $iTipoDependencia = 3;
			  break;   
			   
        case '5' :
          $iTipoDependencia = 4;
        break;    
        
        case '4' :
          $iTipoDependencia = 5;
        break;                			    		    
			} 
			
      // Validação Numero PIS
      $lPisValido = DBString::isPIS($oValorDados->rh16_pis);
      
      /**
       * Campo PIS não é obrigatorio, logo se o número do 
       * PIS for inválido o valor do PIS recebe 11;
       */
      if (!$lPisValido) {
        $oValorDados->rh16_pis = '';
      }
     
			//verifica a idade
			$iIdade = $oValorDados->idade;

      $iFinsPrevidenciarios = null;

      if ( ($oValorDados->rh31_depend == 'C' && $iIdade <= 14 ) || $oValorDados->rh31_depend == 'S' ) {
      	$iFinsPrevidenciarios = 0 ;
      }
      
      if ( ($oValorDados->rh31_depend == 'C' && $iIdade > 14 ) || $oValorDados->rh31_depend == 'N' ) {
      	$iFinsPrevidenciarios = 1 ;
      }
			
      /*
       * Verifica se o CPF é válido para Importação
       * Se não for valido, irá gerar arquivo de Log de Erros
       */
    
      $lCPFValido  = DBString::isCPF($oValorDados->z01_cgccpf);
      $lSexoValido = $oValorDados->z01_sexo == "M" || $oValorDados->z01_sexo == "F";

      if ( !$lCPFValido || !$lSexoValido) {
        
        $sPis  = $oValorDados->rh16_pis;

        if ( !$lCPFValido && !$lSexoValido) {
          $sErro = "PIS, Sexo e CPF Inválido";
        } else if ( !$lCPFValido && !$lSexoValido ) {
          $sErro = "Sexo e CPF Inválido";
        } else if ( !$lSexoValido ) {
        	$sErro = "Sexo Inválido";
        } else if ( !$lCPFValido ) {
          $sErro = "CPF Inválido";
        }

        $aErro    = array( "Dependentes",
                           $sErro,
                           $oValorDados->z01_numcgm,
                           $oValorDados->z01_nome,
                           $oValorDados->z01_cgccpf,
                           $sPis,
                           $oValorDados->z01_sexo );
        $aErros[] = $aErro;                      	
      	
      } else { 

        //Dependencias
		    $oLinha->dependencias->tipoDependencia            = $iTipoDependencia;

        if ( $iFinsPrevidenciarios ){
  		    $oLinha->dependencias->finsPrevidenciarios      = $iFinsPrevidenciarios;          
        } 
        
		    $oLinha->dependencias->dataInicioDependencia      = $oValorDados->inicio_depencia;
		          
		    // Servidor Vinculo
		    $oLinha->dependencias->servidor->nome             = DBString::removerCaracteresEspeciais($oValorDados->z01_nome);           
		    $oLinha->dependencias->servidor->numeroCPF        = $oValorDados->z01_cgccpf;
		    
        // PIS nulo sera aceito, pois podem existir servidores sem pis
        if ( $sPis == null || $oValorDados->rh16_pis == '00000000000' ) {
          $oLinha->dependencias->servidor->numeroNIT  = "";
        } else {
          $oLinha->dependencias->servidor->numeroNIT  = $sPis;
        }		     
        
        // Dados Pessoais do Dependente
		    $oLinha->dadosPessoais->nome                      = DBString::removerCaracteresEspeciais($oValorDados->rh31_nome);
		    $oLinha->dadosPessoais->dataNascimento            = $oValorDados->rh31_dtnasc;
		    $oLinha->dadosPessoais->nomeMae                   = DBString::removerCaracteresEspeciais($oValorDados->rh31_nome);
		         
		    $aDados[]                                         = $oLinha;      	
      }
    }
    $_SESSION['erro_dependentes'] = $aErros;  
   /*    
      echo "<pre>";
      print_r($_SESSION['erro_servidores']);
      echo "</pre>";   
      die();
    */  
    
  	return $aDados;
  }

  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado.
   */  
  public  function getElementos(){
     
    $aDados            = array();
    $aDadosDependentes = array("nome"         => "dependencias",
                               "propriedades" => Array( "tipoDependencia",
                                                        "finsPrevidenciarios",
                                                        "dataInicioDependencia",
                                                        array("nome"=>"servidor",
                                                              "propriedades"=>array("nome",
                                                                                    "numeroCPF",
                                                                                    "numeroNIT"
                                                                                    )
                                                              )
                                                       )
                               );
    $aDados[]          = $aDadosDependentes;             
    $aDadosPessoais    = array("nome"         => "dadosPessoais",
                               "propriedades" => Array( "nome",
                                                        "dataNascimento",
                                                        "nomeMae"
                                                       )
                               );                                  
    $aDados[]          = $aDadosPessoais; 
  	
    return $aDados;
  }  
  
}  
?>
