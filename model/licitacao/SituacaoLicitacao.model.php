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
 * Class SituacaoLicitacao
 * Controla as situações da licitação
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package licitacao
 * @version $Revision: 1.1 $
 */
class SituacaoLicitacao {

  /**
   * Código Sequencial da Situação
   * @var integer
   */
  private $iCodigo;

  /**
   * Descrição
   * @var string
   */
  private $sDescricao;

  /**
   * Define se a situação permite alterar os dados da licitação
   * @var bool
   */
  private $lPermiteAlterar;

  /**
   * Define o caminho das mensagens utilizadas pelo objeto
   * @const string
   */
  const URL_MENSAGEM = "patrimonial.licitacao.SituacaoLicitacao.";

  /**
   * Constrói o objeto
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo) {

    $oDaoLicSituacao   = new cl_licsituacao();
    $sSqlBuscaSituacao = $oDaoLicSituacao->sql_query_file($iCodigo);
    $rsBuscaSituacao   = $oDaoLicSituacao->sql_record($sSqlBuscaSituacao);
    if ($oDaoLicSituacao->erro_status == "0") {
      throw new BusinessException(_M(self::URL_MENSAGEM."licitacao_nao_encontrada"));
    }

    $oStdSituacao          = db_utils::fieldsMemory($rsBuscaSituacao, 0);
    $this->iCodigo         = $iCodigo;
    $this->sDescricao      = $oStdSituacao->l08_descr;
    $this->lPermiteAlterar = $oStdSituacao->l08_altera == "t" ? true : false;
    unset($oStdSituacao);
  }

  /**
   * Retorna o código sequencial
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna se é permitido alterar
   * @return boolean
   */
  public function permiteAlterar() {
    return $this->lPermiteAlterar;
  }

  /**
   * Descrição
   * @return string
   */
  public function getSDescricao() {
    return $this->sDescricao;
  }
}