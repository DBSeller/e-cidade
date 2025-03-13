<?php
/**
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
 * Classe responsável por executar os acertos em base no banco de dados
 */
class IntegracaoAcerto {

  /**
   * Variável que guarda a query a ser executada
   * @var string
   */
  private $sQuery;

  /**
   * Alteramos a query a ser executada
   * @param string $sQuery
   */
  public function setQuery( $sQuery ){
    $this->sQuery = $sQuery;
  }

  /**
   * Buscamos a query
   * @return string
   */
  public function getQuery() {
    return $this->sQuery;
  }

  /**
   * Executamos a query definida no atributo sQuery
   */
  public function executaQuery() {

    if ( empty($this->sQuery) ) {
      throw new Exception( "Query para execução não foi definida!" );
    }

    $rsRetorno = db_query($this->sQuery);

    if ( empty($rsRetorno) ) {
      throw new Exception("Erro na execução do acerto em base." , 1);
    }
  }
}