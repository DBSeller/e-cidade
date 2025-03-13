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
 * Classe repository para Exame
 */

/**
 * Coleção de Exame
 * @package saude
 * @author  Andrio Costa   <andrio.costa@dbseller.com.br>
 * @author  Gilnei Freitas <gilnei@dbseller.com.br>
 * @version $Revision: 1.1 $
 *
 */
class ExameRepository {

  /**
   * Array com os Exame
   * @var array
   */
  private $aExame = array();

  /**
   * Instância da classe
   * @var ExameRepository
   */
  private static $oInstance;

    /**
     * @var cl_lab_exame
     */
  private $dao;

  /**
   * ExameRepository constructor.
   * @param null $dao
   */
  public function __construct($dao = null)
  {
    $this->dao = $dao;
  }

  private function __clone() {}

  /**
   * Retorna uma instância da classe
   * @return ExameRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new ExameRepository();
    }
    return self::$oInstance;
  }

  /**
   * @param $id
   * @param string $columns
   * @return bool|void
   */
  public function find($id, $columns = '*')
  {
    $sql = $this->dao->sql_query($id, $columns);
    $rs = db_query($sql);

    if (!$rs || pg_num_rows($rs) === 0) {
        return false;
    }

    return Exame::fromState(pg_fetch_array($rs));
  }

  public static function getByCodigo( $iCodigo ) {

    if ( !array_key_exists($iCodigo, self::getInstance()->aExame) ) {
      self::getInstance()->aExame[$iCodigo] = new Exame($iCodigo);
    }
    return self::getInstance()->aExame[$iCodigo];
  }
}
