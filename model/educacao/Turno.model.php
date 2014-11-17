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

/**
 * Turno
 * @package educacao
 * @author  Fabio Esteves - fabio.esteves@dbseller.com.br
 * @version $Revision: 1.6 $
 */
class Turno {

  /**
   * Constantes do turno referente
   */
  const TURNO_REFERENTE_MANHA = 1;
  const TURNO_REFERENTE_TARDE = 2;
  const TURNO_REFERENTE_NOITE = 3;

  /**
   * Codigo do turno
   * @var integer
   */
  private $iCodigoTurno;
  
  /**
   * Descricao do turno
   * @var string
   */
  private $sDescricao;
  
  public function __construct($iCodigoTurno = null) {
    
    if (!empty($iCodigoTurno)) {
      
      $oDaoTurno   = db_utils::getDao("turno");
      $sSqlTurno   = $oDaoTurno->sql_query_file($iCodigoTurno);
      $rsTurno     = $oDaoTurno->sql_record($sSqlTurno);
      $iTotalTurno = $oDaoTurno->numrows;
      
      if ($iTotalTurno > 0) {
        
        for ($iContadorTurno = 0; $iContadorTurno < $iTotalTurno; $iContadorTurno++) {
          
          $oDadosTurno = db_utils::fieldsMemory($rsTurno, $iContadorTurno);
          $this->iCodigoTurno = $oDadosTurno->ed15_i_codigo;
          $this->sDescricao   = $oDadosTurno->ed15_c_nome;
        }
      }
    }
  }
  
  /**
   * Retorna o codigo do turno
   * @return integer
   */
  public function getCodigoTurno() {
    return $this->iCodigoTurno;
  }
  
  /**
   * Retorna a descricao do turno
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Método que verifica se turno é integral
   * @return boolean
   */
  public function isIntegral() {

    $sWhere = " ed231_i_turno = {$this->iCodigoTurno}";

    $oDaoTurnoReferente = new cl_turnoreferente();
    $sSqlIsIntegral     = $oDaoTurnoReferente->sql_query_file(null, "count(*)", null, $sWhere);
    $rsIsIntegral       = db_query($sSqlIsIntegral);
    
    if ($rsIsIntegral && pg_num_rows($rsIsIntegral) > 0) {

      if ( (int)db_utils::fieldsMemory($rsIsIntegral, 0)->count > 1 ) {
        return true;
      }
    }
    return false;
  }

  /**
   * Retorna as referências do turno
   * @return array
   */
  public function getTurnoReferente() {

    $oDaoTurnoReferente   = new cl_turnoreferente();
    $sWhereTurnoReferente = "ed231_i_turno = {$this->getCodigoTurno()}";
    $sSqlTurnoReferente   = $oDaoTurnoReferente->sql_query_file( null, "ed231_i_referencia", null, $sWhereTurnoReferente );
    $rsTurnoReferente     = db_query( $sSqlTurnoReferente );

    $aReferencias = array();
    if( $rsTurnoReferente && pg_num_rows( $rsTurnoReferente ) > 0 ) {

      $iLinha = pg_num_rows( $rsTurnoReferente );
      for  ($i = 0; $i < $iLinha; $i++) {
        $aReferencias[] = db_utils::fieldsMemory( $rsTurnoReferente, $i )->ed231_i_referencia;
      }
    }
    return $aReferencias;
  }
}

?>