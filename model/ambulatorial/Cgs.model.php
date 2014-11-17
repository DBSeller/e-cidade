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

  define("ARQUIVO_MENSAGEM_CGS", "saude.ambulatorial.Cgs.");

/**
 * Classe para controle geral dos pacientes da
 * area DB:Saude
 * @package Ambulatorial
 * @version $Revision: 1.7 $
 */
class Cgs {
  
  /**
   * Codigo do cgs
   * @var integer
   */  
  protected $iCodigo;
  
  /**
   * Nome do CGS
   * @var string
   */
  protected $sNome;

  /**
   * Sexo do paciente
   * @var string
   */
  protected $sSexo;

  /**
   * Data de Nascimento
   * @var DBDate
   */
  protected $oDataNascimento;
  
  /**
   * Instancia um novo CGs
   */
  function __construct($iCgs = null) {

    if (!empty($iCgs)) {
      
      $oDaoCgs      = db_utils::getDao("cgs_und");
      $sSqlDadosCGS = $oDaoCgs->sql_query_file($iCgs);
      $rsDadosCGS   = $oDaoCgs->sql_record($sSqlDadosCGS);
      if ($oDaoCgs->numrows > 0) {

        $oDadosCGS = db_utils::fieldsMemory($rsDadosCGS, 0);
        $this->setCodigo($iCgs);
        $this->setNome($oDadosCGS->z01_v_nome);
        $this->setSexo($oDadosCGS->z01_v_sexo);
        if (!empty($oDadosCGS->z01_d_nasc)) {
          $this->setDataNascimento(new DBDate($oDadosCGS->z01_d_nasc));
        }
      }
    }  
  }
  /**
   * Retorna o codigo de cadastro do paciente
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Código do paciente
   * @param integer $iCodigo
   */
  protected function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Retorna o nome do paciente
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
  
  
  /**
   * seta o nome do paciente
   * @param string $sNome define o nome do paciente
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Retorna o sexo do paciente
   * @return string
   */
  public function getSexo() {
    return $this->sSexo;
  }

  /**
   * Define o sexo do paciente
   * @param $sSexo sexo do paciente
   */
  public function setSexo($sSexo) {
    $this->sSexo = $sSexo;
  }

  /**
   * Define a Data de nascimento
   * @param DBDate $oDataNascimento
   */
  public function setDataNascimento( DBDate $oDataNascimento) {
    $this->oDataNascimento = $oDataNascimento;
  }

  /**
   * Retorna a data de nascimento do paciente
   * @return DBDate
   */
  public function getDataNascimento() {
    return $this->oDataNascimento;
  }

  /**
   * Retorna o cartão sus do CGS
   * @return string
   */
  public function getCartaoSus() {

    $sCartaoSus        = '';
    $sWhereCartaoSus   = "     s115_i_cgs = {$this->iCodigo}";
    $oDaoCgsCartaoSus  = new cl_cgs_cartaosus();
    $sSqlCartaoSus     = $oDaoCgsCartaoSus->sql_query_file(null, "s115_c_cartaosus, s115_c_tipo", null, $sWhereCartaoSus);
    $rsCartaoSus       = db_query( $sSqlCartaoSus );

    if ( !$rsCartaoSus ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoUnidadeMedicos->erro_msg;
      throw new DBException( _M(ARQUIVO_MENSAGEM_CGS . "erro_buscar_cns", $oErro) );
    }

    $iLinhasCartaoSus = pg_num_rows( $rsCartaoSus );

    $sCartaoSus           = '';
    
    if ( $iLinhasCartaoSus > 0 ) {

      for( $iContador = 0; $iContador < $iLinhasCartaoSus; $iContador++ ) {

        $oDadosCartaoSus = db_utils::fieldsMemory( $rsCartaoSus, $iContador ); 
        $sCartaoSus      = $oDadosCartaoSus->s115_c_cartaosus;

        if ( $oDadosCartaoSus->s115_c_tipo == 'D') {
          break;
        }
      }
    }

    return $sCartaoSus;
  }
}
