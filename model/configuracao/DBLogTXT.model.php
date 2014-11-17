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

require_once("interfaces/iLog.interface.php");
/**
 * Classe para escrita de logs em TXT
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbrenan $
 * @version $Revision: 1.3 $
 */
class DBLogTXT implements iLog {

  private $sCaminhoArquivo = null;
  private $pArquivo;
  private $lMostraDataHora = true;
  /**
   * Construtor da Classe
   * @param integer $sCaminhoArquivo
   */
  public function __construct($sCaminhoArquivo) {
    $this->pArquivo = fopen($sCaminhoArquivo, 'w');
  }

  
  /**
   * Escreve Log
   * @see iLog::log()
   */
  public function log($sTextoLog, $iTipoLog = DBLog::LOG_INFO) {

    $oDataHora 	        = (object)getdate();
    $sOutrasInformacoes = "";
    
    switch ( $iTipoLog ) {

      case DBLog::LOG_INFO:
        $sTipo = "INFO ";
        break;
      case DBLog::LOG_NOTICE:
        $sTipo = "AVISO";
        break;
      case DBLog::LOG_ERROR:
        $sTipo = "ERRO ";
        break;
    }
    $sMensagem = sprintf("[ %s ] %s", $sTipo,
                                      $sTextoLog."\n");
    
    if ($this->lMostraDataHora) {
      
     $sMensagem = sprintf("[ %s - %02d/%02d/%04d - %02d:%02d:%02d] %s", $sTipo, 
                                                                        $oDataHora->mday, 
                                                                        $oDataHora->mon, 
                                                                        $oDataHora->year, 
                                                                        $oDataHora->hours, 
                                                                        $oDataHora->minutes, 
                                                                        $oDataHora->seconds, 
                                                                        $sTextoLog."\n");
     
    } 
    
    

    return fputs($this->pArquivo, $sMensagem);
  }

  public function finalizarLog() {
    fclose($this->pArquivo);
  }

  public function __destruct() {
    $this->finalizarLog();
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