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


require_once ('model/tceEstruturaBasica.php');

class tceFolhaTabelaTotalizadores extends tceEstruturaBasica {
  
  const  NOME_ARQUIVO   = 'TCE_4960.TXT';
  const  CODIGO_ARQUIVO = 37;
  
  public  $iInstit       = "";
  public  $sInstituicoes = "";
  public  $sDataIni      = "";
  public  $sDataFim      = "";
  public  $sCodRemessa   = "";

  protected $oDadosArquivo = null;

  private $oLeiaute = null;
  /**
   * 
   */
  function __construct($iInstit,$sCodRemessa,$sDataIni,$sDataFim,$oData, $oLeiaute = null, $sInstituicoes) {
  	
    try {
      parent::__construct(self::CODIGO_ARQUIVO,self::NOME_ARQUIVO);
    } catch (Exception $e) {
    	throw $e->getMessage();    	
    }
    $this->oDadosArquivo = $oData;
    $this->iInstit       = $iInstit;
    $this->sInstituicoes = $sInstituicoes;
    $this->sDataIni      = $sDataIni;
    $this->sDataFim      = $sDataFim;
    $this->sCodRemessa   = $sCodRemessa;
    if ($oLeiaute != null) {
      $this->oLeiaute = $oLeiaute;
    }
    
    
  }
  
  function getNomeArquivo(){
    return self::NOME_ARQUIVO;
  }
  
  function geraArquivo() {

    // db_criatermometro('terTCE4960', 'Arquivo TCE4960...', 'blue', 1);
    $this->oTxtLayout->setByLineOfDBUtils($this->cabecalhoPadrao($this->iInstit, 
                                                                 $this->sDataIni, 
                                                                 $this->sDataFim, 
                                                                 $this->sCodRemessa), 1);
    $rsFolhaRubricas = db_query($this->sqlTabelaTotalizadores($this->sInstituicoes));
    $iNumRows        = pg_num_rows($rsFolhaRubricas);
    $iTotalRegistros = 0;
    
    
    for($i = 0; $i < $iNumRows; $i ++) {
      
      // db_atutermometro($i, $iNumRows, "terTCE4960");
      $oFolhaRubricas = db_utils::fieldsMemory($rsFolhaRubricas, $i);
      $this->oTxtLayout->setByLineOfDBUtils($oFolhaRubricas, 3);
      $iTotalRegistros ++;
    
    }
    
    $this->oTxtLayout->setByLineOfDBUtils($this->rodapePadrao($iTotalRegistros), 5);
    unset($rsFolhaRubricas);
  
  }
  
  /**
   * Funcao para montar uma string sql para busca do cadastro de rubricas
   *
   * @param  integer    $iInstit     codigo da instituicao 
   * @return string                  string sql com o cadastro de rubricas
   */
  function sqlTabelaTotalizadores($iInstit) {

    /**
     * pesquisamos os dados da geracao , para montar o texto da base legal da Rubrica
     */
    $oContcearquivoDAO  = new cl_contcearquivo;
    $sSqlDadosFiltro    = $oContcearquivoDAO->sql_query($this->oDadosArquivo->codigoremessa);
    $rsDadosFiltro      = $oContcearquivoDAO->sql_record($sSqlDadosFiltro);
    if (!$rsDadosFiltro || $oContcearquivoDAO->numrows == 0) {
      throw new BusinessException('Erro ao pesquisar dados do arquivo.');
    }

    $sTextoRubrica = str_replace(array("\n","\r"), " ", db_utils::fieldsMemory($rsDadosFiltro, 0)->c11_infleiame);

    $iInicioTextRubrica = strpos($sTextoRubrica, "#");
    $iFimTextoRubrica   = strpos($sTextoRubrica, "#", $iInicioTextRubrica + 1);

    $sParteTextoRubrica = substr($sTextoRubrica, $iInicioTextRubrica+1, ( $iFimTextoRubrica - 1) - $iInicioTextRubrica);


    $sSqlFolhaRubricas  = " select rh27_instit::char||rh27_rubric  as rubrica, ";
    $sSqlFolhaRubricas .= "        0            as codigovantagemdescontototalizador, ";
    $sSqlFolhaRubricas .= "        cast('{$this->sDataFim}' as date) as dataatualizacao, ";
    $sSqlFolhaRubricas .= "        rh27_descr   as nomevantagemdescontototalizador, ";
    $sSqlFolhaRubricas .= "        '{$sParteTextoRubrica}'           as baselegal ";
    $sSqlFolhaRubricas .= "   from rhrubricas ";
    $sSqlFolhaRubricas .= "  where rh27_instit in ({$this->sInstituicoes}) ";
    
    return $sSqlFolhaRubricas;
  
  }



}

?>