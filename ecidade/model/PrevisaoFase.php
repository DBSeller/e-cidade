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


class PrevisaoFase {
	
	private $iCodTarefa; // tarefa.at40_sequencial  
	private $iCodTarefaPrevisao; // tarefaprevisao.at81_sequencial
	private $iCodFase; // tarefaprevisaofase.at82_sequencial
	private $iCodSituacao; // tarefaprevisaocadfase.at84_sequencial
	private $iCodUsuario; // tarefaprevisaofaserecurso.at83_usuario
	private $iQtdHoras;
	private $iDtIni;
	private $iDtFim;
	private $sStatus;

  public function __construct() {
  	
  	$this->iCodTarefa = 0;
  }
  
  public function persist() {
  	
  	if ($this->getDtIni() != "" && $this->getDtIni() != "") { 
  	
	  	$sqlerro = false;
	  	
	  	if ($this->getStatus() != "A") {
	  	
	  		$oPrevisao = new cl_tarefaprevisao();
	  		$oPrevFase = new cl_tarefaprevisaofase();
	  		$oPrevRecu = new cl_tarefaprevisaofaserecurso();
	      
		  	if ($this->getStatus() == "E") {
		  		
		  		  if ($this->getCodFase() != "") {
		  		
			  		  $sSql     = "UPDATE tarefaprevisaofase SET at82_ativo = 'f' WHERE at82_sequencial = {$this->getCodFase()}";
			  			$rsUpdate = db_query($sSql); 
			  			//echo $sSql."\n -----------------------------------------\n\n";	  			
		  		  }
		  			
		  	} else if ($this->getStatus() == "N") {
		  		
	        $oPrevFase->at82_tarefaprevisaocadfase = $this->getCodSituacao();
	        $oPrevFase->at82_tarefaprevisao        = $this->getCodTarefaPrevisao();
	        $oPrevFase->at82_qtdhoras              = $this->getQtdHoras();
	        $oPrevFase->at82_dataini               = date('Y-m-d', $this->getDtIni());
	        $oPrevFase->at82_horaini               = date('H:i', $this->getDtIni());
	        $oPrevFase->at82_datafim               = date('Y-m-d', $this->getDtFim());
	        $oPrevFase->at82_horafim               = date('H:i', $this->getDtFim());
		  
		  		$oPrevFase->incluir(null);
		  		
		  		if ($oPrevFase->erro_status == "1") {
		  			
		  			$oPrevRecu->at83_usuario            = $this->getCodUsuario()?$this->getCodUsuario():163;
		  			$oPrevRecu->at83_tarefaprevisaofase = $oPrevFase->at82_sequencial;
		  			$oPrevRecu->incluir(null);
		  			
		  			if ($oPrevRecu->erro_status == "0")
		  			  return false;
		  			 
		  		} else {
		  			
		  			return false;
		  		}      	      
		  	}	  	
	    }
  	}
  	
    return true;
  }
  
  
/**
   * @return Integer
   */
  public function getCodFase() {

    return $this->iCodFase;
  }
  
  /**
   * @return Integer
   */
  public function getCodSituacao() {

    return $this->iCodSituacao;
  }
  
  /**
   * @return Integer
   */
  public function getCodTarefa() {

    return $this->iCodTarefa;
  }
  
  /**
   * @return Integer
   */
  public function getCodTarefaPrevisao() {

    return $this->iCodTarefaPrevisao;
  }
  
  /**
   * @return Integer
   */
  public function getCodUsuario() {

    return $this->iCodUsuario;
  }
  
  /**
   * @return Integer
   */
  public function getQtdHoras() {

    return $this->iQtdHoras;
  }
  
  /**
   * @return String
   */
  public function getStatus() {

    return $this->sStatus;
  }
  
  /**
   * @return Integer
   */
  public function getDtFim() {

    return $this->iDtFim;
  }
  
  /**
   * @return Integer
   */
  public function getDtIni() {

    return $this->iDtIni;
  }
  
  /**
   * @param Integer $iCodFase
   */
  public function setCodFase($iCodFase) {

    $this->iCodFase = $iCodFase;
  }
  
  /**
   * @param Integer $iCodSituacao
   */
  public function setCodSituacao($iCodSituacao) {

    $this->iCodSituacao = $iCodSituacao;
  }
  
  /**
   * @param Integer $iCodTarefa
   */
  public function setCodTarefa($iCodTarefa) {

    $this->iCodTarefa = $iCodTarefa;
  }
  
  /**
   * @param Integer $iCodTarefaPrevisao
   */
  public function setCodTarefaPrevisao($iCodTarefaPrevisao) {

    $this->iCodTarefaPrevisao = $iCodTarefaPrevisao;
  }
  
  /**
   * @param Integer $iCodUsuario
   */
  public function setCodUsuario($iCodUsuario) {

    $this->iCodUsuario = $iCodUsuario;
  }
  
  /**
   * @param Integer $iQtdHoras
   */
  public function setQtdHoras($iQtdHoras) {

    $this->iQtdHoras = $iQtdHoras;
  }
  
  /**
   * @param String $sStatus
   */
  public function setStatus($sStatus) {

    $this->sStatus = $sStatus;
  }
  
  /**
   * @param Integer $iDtFim
   */
  public function setDtFim($iDtFim) {

    $this->iDtFim = $iDtFim;
  }
  
  /**
   * @param Integer $iDtIni
   */
  public function setDtIni($iDtIni) {

    $this->iDtIni = $iDtIni;
  } 
}

?>