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
 * Tipo de movimentacao do estque
 *
 * @package estoque
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class TipoMovimentacaoEstoque {

  const ENTRADA          = 1;
  const SAIDA            = 2;
  const EM_TRANSFERENCIA = 4;

  /**
   * Código do tipo de movimentação
   *
   * @var mixed
   * @access private
   */
  private $iCodigo;

  /**
   * Descricao do tipo de movimentacao
   *
   * @var mixed
   * @access private
   */
  private $sDescricao;

  /**
   * Classificao do tipo
   * entrada, saida ou em transferencia
   *
   * @var integer
   * @access private
   */
  private $iClassificacao;

  /**
   * Constrói o objeto com os dados
   * @param integer $iCodigo
   * @throws Exception
   */
  public function __construct($iCodigo = null) {

    /**
     * Código do tipo de movimentação não informado
     */
    if (empty($iCodigo)) {
      return false;
    }

    $oDaoMatestoquetipo   = db_utils::getDao('matestoquetipo');
    $sSqlTipoMovimentacao = $oDaoMatestoquetipo->sql_query_file($iCodigo);
    $rsTipoMovimentacao   = $oDaoMatestoquetipo->sql_record($sSqlTipoMovimentacao);

    if ($oDaoMatestoquetipo->erro_status == '0') {
      throw new Exception($oDaoMatestoquetipo->erro_msg);
    }

    $oDadosTipoMovimentacao = db_utils::fieldsMemory($rsTipoMovimentacao, 0);

    $this->iCodigo        = (int) $iCodigo;
    $this->sDescricao     = $oDadosTipoMovimentacao->m81_descr;
    $this->iClassificacao = (int) $oDadosTipoMovimentacao->m81_tipo;
  }

  /**
   * Retorna o codigo do tipo de movimentação *
   * @access public
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descricao do tipo de movimentação
   *
   * @access public
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna a classificação do tipo de movimentação
   * entrada, saida ou em tranferencia
   *
   * @access public
   * @return integer
   */
  public function getClassificacao() {
    return $this->iClassificacao;
  }

}