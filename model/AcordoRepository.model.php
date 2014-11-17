<?php

/**
 * Classe repository para classes Acordo
 *
 * @author
 * @package
 */
 class AcordoRepository {

  /**
   * Collection de Acordo
   *
   * @var array
   */
  private $aItens = array();

  /**
   * Instancia da classe
   *
   * @var AcordoRepository
   */
  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna uma instancia do Acordo pelo Codigo
   *
   * @param integer $iCodigo Codigo do Acordo
   * @return Acordo
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, AcordoRepository::getInstance()->aItens)) {
      AcordoRepository::getInstance()->aItens[$iCodigo] = new Acordo($iCodigo);
    }
    return AcordoRepository::getInstance()->aItens[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   *
   * @return AcordoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new AcordoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona uma instancia de Acordo ao repositorio
   *
   * @param Acordo $oAcordo Instancia de Acordo
   * @return boolean
   */
  public static function adicionarAcordo(Acordo $oAcordo) {

    if (!array_key_exists($oAcordo->getCodigoAcordo(), AcordoRepository::getInstance()->aItens)) {
      AcordoRepository::getInstance()->aItens[$oAcordo->getCodigoAcordo()] = $oAcordo;
    }
    return true;
  }

  /**
   * Remove a instancia passada como parametro do repository
   *
   * @param Acordo $oAcordo
   * @return boolean
   */
  public static function remover(Acordo $oAcordo) {
    /**
     *
     */
    if (array_key_exists($oAcordo->getCodigoAcordo(), AcordoRepository::getInstance()->aItens)) {
      unset(AcordoRepository::getInstance()->aItens[$oAcordo->getCodigoAcordo()]);
    }
    return true;
  }

  /**
   * Retorna o total de itens existentes no repositorio;
   *
   * @return integer;
   */
  public static function getTotalAcordo() {
    return count(AcordoRepository::getInstance()->aItens);
  }

}