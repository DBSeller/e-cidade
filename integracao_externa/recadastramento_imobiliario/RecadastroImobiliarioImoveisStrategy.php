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

require_once(PATH_IMPORTACAO . 'libs/RecadastramentoImobiliarioSQLUtils.php');
/**
 * RecadastroImobiliarioImoveisStrategy
 *
 * @package Recadastramento Imobiliario
 * @author  Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 */
class RecadastroImobiliarioImoveisStrategy {

  /**
   * Ocorrencia Inclusao
   */
  const OCORRENCIA_INCLUSAO       = 1;
  /**
   * Ocorrencia Alteração
   */  
  const OCORRENCIA_ALTERACAO      = 2;
  /**
   * Ocorrencia Exclusão
   */
  const OCORRENCIA_EXCLUSAO       = 3;
  /**
   * Ocorrencia Reinclusao
   */
  const OCORRENCIA_REINCLUSAO     = 5;
  /**
   * Ocorrencia Fora da OrtoFoto
   */
  const OCORRENCIA_FORA_ORTOFOTO  = 7;

  /**
   * Instancia de Processamento
   */
  public $oInstanciaProcessamento; 

  /**
   * Construtor da Classe
   * @param stdClass $oRegistro
   */
  function __construct( $oRegistro ) {


    switch ( $oRegistro->iTipoOcorrencia ) {
      
      case self::OCORRENCIA_INCLUSAO     : //1
        require_once(PATH_IMPORTACAO . 'RecadastroImobiliarioImoveisInclusao.php');
        RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog("#-Tipo de Ocorrencia-# - OCORRENCIA: INCLUSAO.");
        $this->oInstanciaProcessamento = new RecadastroImobiliarioImoveisInclusao( $oRegistro );
      break;
 
      case self::OCORRENCIA_ALTERACAO    : //2
      case 8: //NOVA OCORRENCIA, INDEFINIDA
 
        RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog("#-Tipo de Ocorrencia-# - OCORRENCIA: ALTERACAO.- Matricula {$oRegistro->iMatricula}."); 
        require_once(PATH_IMPORTACAO . 'RecadastroImobiliarioImoveisAlteracao.php');
        $this->oInstanciaProcessamento = new RecadastroImobiliarioImoveisAlteracao( $oRegistro );
      break;
      
      case self::OCORRENCIA_FORA_ORTOFOTO: //7
      
        RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog("#-Tipo de Ocorrencia-# - OCORRENCIA: FORA DA ORTOFOTO - Matricula {$oRegistro->iMatricula}.");
        require_once(PATH_IMPORTACAO . 'RecadastroImobiliarioImoveisAlteracao.php');
        $this->oInstanciaProcessamento = new RecadastroImobiliarioImoveisAlteracao( $oRegistro );
      break;

      case self::OCORRENCIA_EXCLUSAO     : //3
        
        require_once(PATH_IMPORTACAO . 'RecadastroImobiliarioImoveisExclusao.php');
        RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog("#-Tipo de Ocorrencia-# - OCORRENCIA: BAIXA.- Matricula {$oRegistro->iMatricula}.");
        $this->oInstanciaProcessamento = new RecadastroImobiliarioImoveisExclusao( $oRegistro );

      break;
      
      default:
        RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog("#-Tipo de Ocorrencia-# - Sem Tratamento para Ocorrencias do Tipo: {$oRegistro->iTipoOcorrencia}.");
      break;
     }
     $this->iTipoProcessamento =  $oRegistro->iTipoOcorrencia;
  } 

  /**
   * Processa a Ocorrência do Arquivo
   * @return boolean
   */
  public function processar(){

    if (empty($this->oInstanciaProcessamento)) {
     return false;
    }
    $lProcessou = $this->oInstanciaProcessamento->processar();
    return $lProcessou;
  }
  public function getLog() {
    
    if (empty($this->oInstanciaProcessamento)) {
      return ' SEM REGRA DEFINIDA';
    }
   return $this->oInstanciaProcessamento->getLog();
  }
}