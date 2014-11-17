<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe VO representando um item na cotação
 * Class CotacaoItem
 * @author $Author: dbiuri $
 * @version $Revision: 1.1 $
 */

class CotacaoItem {

  /**
   * Item Cotação
   *
   * @access private
   * @var ItemOrcamento
   */
  private $oItem;

  /**
   * Fornecedor que cotou o item
   *
   * @access priavate
   * @var CgmFisico|CgmJuridico
   */
  private $oFornecedor;

  /**
   * Valor unitário do item
   *
   * @access private
   * @var float
   */
  private $nValorUnitario = 0;

  /**
   * Quantidade Cotada
   *
   * @var float
   */
  private $nQuantidade = 0;

  /**
   * Construtor da classe
   */
  function __construct() {

  }

  /**
   * Retorna o valor unitário da cotação do item
   *
   * @access public
   * @return float
   */
  public function getValorUnitario() {
    return $this->nValorUnitario;
  }

  /**
   * Seta o valor unitário da cotação do item
   *
   * @access public
   * @param float $nValorUnitario
   */
  public function setValorUnitario($nValorUnitario) {
    $this->nValorUnitario = $nValorUnitario;
  }

  /**
   * Retorna o fornecedor
   *
   * @access public
   * @return CgmFisico|CgmJuridico
   */
  public function getFornecedor() {
    return $this->oFornecedor;
  }

  /**
   * Seta o fornecedor
   *
   * @access public
   * @param CgmBase $oFornecedor
   */
  public function setFornecedor(CgmBase $oFornecedor) {
    $this->oFornecedor = $oFornecedor;
  }

  /**
   * Retorna o item
   *
   * @access public
   * @return ItemOrcamento
   */
  public function getItem() {
    return $this->oItem;
  }

  /**
   * Seta o item para ser cotado
   *
   * @access public
   * @param ItemOrcamento $oItem
   */
  public function setItem(ItemOrcamento $oItem) {
    $this->oItem = $oItem;
  }

  /**
   * Retorna a quantidade cotada
   *
   * @return float
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * Seta a quantidade cotada
   *
   * @param float $nQuantidade
   */
  public function setQuantidade($nQuantidade) {
    $this->nQuantidade = $nQuantidade;
  }

  /**
   * Retorna a valor total cotada do item
   *
   * @return float
   */
  public function getValorTotal() {
    return round($this->getValorUnitario() * $this->getQuantidade(), 2);
  }

}