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
 * Classe para escrita de logs em xml
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbrenan $
 * @version $Revision: 1.4 $
 */
require_once("interfaces/iLog.interface.php");
class DBLogXML implements iLog {
  
  private $sCaminhoArquivo = null;
  
  /**
   * Construtor da Classe
   * @param integer $sCaminhoArquivo
   */
  public function __construct($sCaminhoArquivo) {
    
    $this->sCaminhoArquivo = $sCaminhoArquivo;
    $this->abrirNovoLog();

  }
  
  /**
   * Escreve Log
   * @see iLog::log()
   */
  public function log($sTextoLog, $iTipoLog = DBLog::LOG_INFO) {
    
    $oXML  = new DOMDocument('1.0', 'ISO-8859-1');
    $oXML  ->formatOutput = true;
    $oXML  ->load($this->sCaminhoArquivo);
    $oLogs = $oXML->getElementsByTagName("Logs")->item(0);
    
    $oLog  = $oXML->createElement("Log");
    $oLog  ->setAttribute( "InstanteLog", time() );
    $oLog  ->setAttribute( "TextoLog"   , urlencode($sTextoLog) );
    $oLog  ->setAttribute( "TipoLog"    , $iTipoLog);
    $oLogs ->appendChild($oLog);
    $oXML  ->save($this->sCaminhoArquivo);
  }
  
  public function abrirNovoLog() {
    
    $oXML  = new DOMDocument('1.0', 'ISO-8859-1');
    $oXML->formatOutput = true;
    $oLogs = $oXML->createElement('Logs'); //ROOT
    $oLogs = $oXML->appendChild($oLogs);
    $oXML->save($this->sCaminhoArquivo);
  }

  /**
   * Retorna o conteudo salvo no arquivo.
   * @param  string $sCaminhoArquivo
   * @return string                 
   */
  public function getConteudo($sCaminhoArquivo){

    $sArquivo = file_get_contents($sCaminhoArquivo);
    
    return $sArquivo;
  }
}