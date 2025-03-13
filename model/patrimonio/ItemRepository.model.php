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

require_once modification("model/patrimonio/Item.model.php");
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\Autorizacao;

/**
 * Item
 *
 * @package patrimonio
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class ItemRepository {

  /**
   * Itens instanciados
   *
   * @var array
   * @access private
   */
  private $aItens = array();

  /**
   * Instancia do repository
   *
   * @static
   * @var ItemRepository
   * @access private
   */
  private static $oInstancia;

  /**
   * Bloqueia instanciar e clonar objeto externamente
   */
  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna instancia do repository
   *
   * @access public
   * @return ItemRepository
   */
  public function getInstancia() {

    if(self::$oInstancia == null) {
      self::$oInstancia = new ItemRepository();
    }

    return self::$oInstancia;
  }

  /**
   * Retorna item pelo codigo
   *
   * @param integer $iCodigo
   * @static
   * @access public
   * @return Item
   */
  public static function getItemByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, ItemRepository::getInstancia()->aItens)) {
      ItemRepository::getInstancia()->aItens[$iCodigo] = new Item($iCodigo);
    }

    return ItemRepository::getInstancia()->aItens[$iCodigo];
  }

}
