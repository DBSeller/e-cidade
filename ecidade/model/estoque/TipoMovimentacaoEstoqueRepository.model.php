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

/**
 * Repositório para o Tipo de Movimentação do Estoque
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package patrimonio
 * @subpackage material
 * @version $Revision: 1.3 $
 */
class TipoMovimentacaoEstoqueRepository {

  /**
   * Coleção com os tipos de movimentação do estoque
   * @var TipoMovimentacaoEstoque[]
   */
  private $aTipoMovimentacao = array();

  /**
   * Instancia de TipoMovimentacaoEstoqueRepository
   * @var TipoMovimentacaoEstoqueRepository
   */
  private static $oInstancia;

  /**
   * Método privado construtor
   */
  private function __construct() {}

  /**
   * Método mágico privado __clone
   */
  private function __clone() {}


  /**
   * Retorna a instancia de TipoMovimentacaoEstoqueRepository
   * @return TipoMovimentacaoEstoqueRepository
   */
  protected function getInstancia() {

    if(self::$oInstancia == null) {
      self::$oInstancia = new TipoMovimentacaoEstoqueRepository();
    }
    return self::$oInstancia;
  }

  /**
   * Retorna o tipo de movimentação do estoque de acordo com o código informado
   * @param $iCodigoMovimentacao
   * @return TipoMovimentacaoEstoque
   */
  public static function getTipoMovimentaoPorCodigo($iCodigoMovimentacao) {

    if ( ! array_key_exists($iCodigoMovimentacao, TipoMovimentacaoEstoqueRepository::getInstancia()->aTipoMovimentacao)) {
      TipoMovimentacaoEstoqueRepository::getInstancia()->aTipoMovimentacao[$iCodigoMovimentacao] = new TipoMovimentacaoEstoque($iCodigoMovimentacao);
    }
    return TipoMovimentacaoEstoqueRepository::getInstancia()->aTipoMovimentacao[$iCodigoMovimentacao];
  }
}