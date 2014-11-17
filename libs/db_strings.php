<?php
/**
 * Classe para Tratamento de Strings
 * @author dbseller
 * @deprecated
 */
class db_strings {
  
  /**
   * 
   */
  function __construct() {

  }
  
  
  /**
   * Quebra uma string passando por parâmetro o número máximo de caracteres retornando um array 
   *
   * @deprecated    
   * @see DBString::quebraLinha()
   * @param string  $sString
   * @param integer $iNroMaxCaract
   * @param string  $sValorCompl
   * @return array
   */
  function quebraLinha($sString,$iNroMaxCaract,$sValorCompl=' '){
    
    require_once "std/DBString.php";
    return DBString::quebraLinha($sString,$iNroMaxCaract,$sValorCompl);
  }
  
  
}

?>
