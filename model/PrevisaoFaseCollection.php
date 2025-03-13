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


class PrevisaoFaseCollection {
  
	private $aPrevisaoFase;
	
	public function __construct() {
		
		$this->aPrevisaoFase = array();
	}
	
	/*
	 * SIMPLESMENTE ADICIONA UMA PREVISAO AO OBJETO
	 */
	public function addPrevisaoFase($oPrevFase) {
    
		array_push($this->aPrevisaoFase, $oPrevFase);
	}	
	
	/*
	 * VERIFICA AS PREVISOES ANTES DE INSERIR NO OBJETO
	 */
	public function insertPrevisao($oPrevisaoFase) {
		
		for ($i=0; $i<count($this->aPrevisaoFase); $i++) { // PERCORRE O ARRAY DE PREVISOES DO OBJETO
			
			if ($oPrevisaoFase->getDtIni() != "" && $oPrevisaoFase->getDtFim() != "") { // SE AS DATAS NAO FOREM SETADAS IGNORA ESTA FASE

				if ($this->aPrevisaoFase[$i]->getStatus() != "E") { // SE O OBJETO A SER COMPARADO ESTIVER COM STATUS 'E' DE EXCLUISO, IGNORA ESTA FASE
				
					if (($oPrevisaoFase->getCodFase() != $this->aPrevisaoFase[$i]->getCodFase())) { // SE AS FASES FOREM IGUAIS, É O MESMO OBJETO, SIMPLESMENTE TRANSFORMA O STATUS DO ANTERIOR PARA 'E'
					
						if ($this->aPrevisaoFase[$i]->getDtIni() > 0 && $this->aPrevisaoFase[$i]->getDtFim() > 0) { // SE AS DATAS FOREM MAIOR QUE ZERO EXECUTA
							
		  				if ($oPrevisaoFase->getCodUsuario() == $this->aPrevisaoFase[$i]->getCodUsuario()) { // VERIFICA SE É O MESMO USUARIO 
					  
					      if (DBTime::conflitoDatas($oPrevisaoFase->getDtIni(),$oPrevisaoFase->getDtFim(), 
					                               $this->aPrevisaoFase[$i]->getDtIni(), $this->aPrevisaoFase[$i]->getDtFim())) { // VERIFICA SE EXISTE CONFLITO NAS DATAS UTILIZANDO METODO DA CLASSE DBTime  
		
					      	$this->aPrevisaoFase[$i]->setStatus("E"); // SETA O STATUS DA PREVISAO ANTERIOR PARA EXCLUIDA
					        
					      	$oNewPrev = new PrevisaoFase(); // CRIA UM NOVO OBJETO PrevisaoFase E SETA OS VALORES
					        $oNewPrev->setCodFase($this->aPrevisaoFase[$i]->getCodFase());
					        $oNewPrev->setCodSituacao($this->aPrevisaoFase[$i]->getCodSituacao());
					        $oNewPrev->setCodTarefa($this->aPrevisaoFase[$i]->getCodTarefa());
					        $oNewPrev->setCodTarefaPrevisao($this->aPrevisaoFase[$i]->getCodTarefaPrevisao());
					        $oNewPrev->setCodUsuario($this->aPrevisaoFase[$i]->getCodUsuario());
					        $oNewPrev->setQtdHoras($this->aPrevisaoFase[$i]->getQtdHoras());
					        $oNewPrev->setDtIni(DBTime::verifData($oPrevisaoFase->getDtFim()+(600))); //UTILIZA O METODO DE VERIFICAÇÃO DA DATA (FERIADO, SABADOS, DOMINGOS E HORARIOS 
					        $oNewPrev->setDtFim(
					        DBTime::verifIntervalo($oNewPrev->getDtIni(), 
					                               (DBTime::verifData($oNewPrev->getDtIni()+($oNewPrev->getQtdHoras()*(3600)))),
					                               $oNewPrev->getQtdHoras())); // VERIFICA A DATA E O INTERVALOR (CASO DE EXISTIR MEIO DIA ENTRE AS DATAS)
	                $oNewPrev->setStatus("N"); // SETA O STATUS DO OBJETO COMO NOVO
	                
	                
                  $this->empurraDependencias($oNewPrev); // VERIFICA AS DEPENDENCIAS					        
					        $this->insertPrevisao($oNewPrev); // INSERE O NOVO OBJETO NA PREVISAO
							  }
							} else {
            
		            if ($this->aPrevisaoFase[$i]->getCodFase() != 0 && ($oPrevisaoFase->getCodUsuario() == $this->aPrevisaoFase[$i]->getCodUsuario()))
		              $this->aPrevisaoFase[$i]->setStatus("E");
		          }
						} else {
            
	            if ($this->aPrevisaoFase[$i]->getCodFase() != 0 && ($oPrevisaoFase->getCodUsuario() == $this->aPrevisaoFase[$i]->getCodUsuario()))
	              $this->aPrevisaoFase[$i]->setStatus("E");
	          }
					} else {
						
						if ($this->aPrevisaoFase[$i]->getCodFase() != 0)
						  $this->aPrevisaoFase[$i]->setStatus("E");
					}
				}
			}
		}
		
		$this->addPrevisaoFase($oPrevisaoFase); // SE NAO EXISTIU NENHUM CONFLITO ADICIONA O OBJETO SIMPLESMENTE
	}
	
	public function empurraDependencias ($oPrevisaoFase) {
		
		/*
		 * VERIFICA SE QUAL FASE É DEPENDENTE DA FASE DO OBJETO ADICIONADO
		 */
		$oPrevCadFase    = new cl_tarefaprevisaocadfase();
		$sSql            = $oPrevCadFase->sql_query_file(null, "at84_sequencial", "", "at84_dependencia={$oPrevisaoFase->getCodSituacao()}");
		$rsSql           = $oPrevCadFase->sql_record($sSql);
		
		if ($oPrevCadFase->numrows > 0) {
		  
			$at84_sequencial = db_utils::fieldsMemory($rsSql,0)->at84_sequencial;
			
			//VERIFICA SE EXISTE ALGUMA PREVISAO QUE SEJA DEPENDENTE UTILIZANDO O CODIGO DA FASE 
			$oPrevFaseRec = new cl_tarefaprevisaofaserecurso();
			$sSqlPrevFaseRec = $oPrevFaseRec->sql_query(null, "*", null, "tarefaprevisaofase.at82_tarefaprevisaocadfase = {$at84_sequencial} AND 
			                                                               tarefaprevisaofase.at82_tarefaprevisao = {$oPrevisaoFase->getCodTarefaPrevisao()} AND 
			                                                               tarefaprevisaofase.at82_ativo = 't' ");
	    $rsPrevFaseRec = $oPrevFaseRec->sql_record($sSqlPrevFaseRec);
	    
	    if ($oPrevFaseRec->numrows > 0) {
		    $obj = db_utils::fieldsMemory($rsPrevFaseRec, 0);
		
		    $oNewPrevFase = new PrevisaoFase();
		    
		    // VERIFICA SE A DATA DA TAREFA É MAIOR QUE A FASE DA DEPENDENCIA, SE FOR ALTERA A DEPENDENCIA
		    if ($oPrevisaoFase->getDtIni() > strtotime("{$obj->at82_datafim} {$obj->at82_horafim}") ) {
		    
			    $oNewPrevFase->setCodSituacao($at84_sequencial);
			    $oNewPrevFase->setCodTarefa($oPrevisaoFase->getCodTarefa());
			    $oNewPrevFase->setCodTarefaPrevisao($oPrevisaoFase->getCodTarefaPrevisao());
			    $oNewPrevFase->setCodUsuario($obj->at83_usuario);
			    $oNewPrevFase->setDtIni(DBTime::verifData($oPrevisaoFase->getDtFim()+(600)));
			    $oNewPrevFase->setQtdHoras($obj->at82_qtdhoras);
			    $oNewPrevFase->setDtFim(
			                  DBTime::verifIntervalo($oNewPrevFase->getDtIni(), 
			                                         (DBTime::verifData($oNewPrevFase->getDtIni()+($oNewPrevFase->getQtdHoras()*(3600)))),
			                                         $oNewPrevFase->getQtdHoras()));
			    $oNewPrevFase->setStatus("N");
			    
			    $this->insertPrevisao($oNewPrevFase);
		    }
	    }
		}
	}

	/*
	 * METODO QUE MOSTRA AS PREVISOES NOVAS
	 */
	
	public function showDates() {
		
		foreach ($this->aPrevisaoFase as $oPrev) {
			
			if ($oPrev->getStatus() != "E") {
			
				if ($oPrev->getDtIni() > 0 && $oPrev->getDtFim() > 0) {
					
					$str  = "                                                     \n";
					$str .= "Tarefa:   {$oPrev->getCodTarefa()}                   \n";
					$str .= "Fase:     {$oPrev->getCodFase()}                     \n";
					$str .= "Situacao: {$oPrev->getCodSituacao()}                 \n";
					$str .= "Horas:    {$oPrev->getQtdHoras()}                    \n";
					$str .= "Data Ini: ".date("d/m/Y H:i", $oPrev->getDtIni())."  \n";
					$str .= "Data Fim: ".date("d/m/Y H:i", $oPrev->getDtFim())."  \n";
					$str .= "Status:   ".$oPrev->getStatus()."                    \n";
					$str .= "-----------------------------------------------------\n";
					
					echo $str;
				}			
			}
		}
	}

	/*
	 * METODO QUE PERSISTE TODAS AS PREVISOES, UTILIZANDO O METODO PERSIST DO OBJETO TarefaPrevisao
	 */
	public function persist() {
		
		foreach ($this->aPrevisaoFase as $oPrevisaoFase) {
			if (!$oPrevisaoFase->persist())
			  return false;
		}
		
		return true;
	}
}

?>