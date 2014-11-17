<?php

/**
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

require_once("model/configuracao/consulta_dados/ConsultaDados.model.php");
require_once("model/webservices/Processamento.model.php");
require_once("model/webservices/Autenticacao.model.php");
require_once("model/configuracao/DBLog.model.php");

/**
 * Classe Responsável pelo gerenciamento das conexões via WebService
 *
 * @package WebServices
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo  <renan@dbseller.com.br>
 */
class DBWebService {

  static private $aInstancia = array();

  public function __construct() {

    if ( isset($_SESSION['DB_debugon'] ) ) {
      set_error_handler(array($this, "handlerError"));
    }
  }
  /**
   * Retorna a Instancia do Método do Webservice
   * @param  string $sMetodo
   * @throws SoapFault - Caso método do webservice for esperado.
   */
  static public function getInstance( $sMetodo ) {

    if ( !isset(DBWebService::$aInstancia[$sMetodo]) ) {
      switch ( $sMetodo ) {

        case "consultar":
          DBWebService::$aInstancia[$sMetodo] = new ConsultaDados();
        break;

        case "processar":
          DBWebService::$aInstancia[$sMetodo] = new Processamento();
        break;

        default:
          throw new SoapFault( "e-Cidade", utf8_encode("Metodo '{$sMetodo}' nao existe.") );
        break;
      }
    }
    return DBWebService::$aInstancia[$sMetodo];
  }

  /**
   * Responsavel pela tomada de decisao do webService
   * @param string $sMetodo
   * @param array  $aArgumentos
   */
  public function __call( $sMetodo, $aArgumentos ) {

    try {

      if (array_key_exists('webservice', $aArgumentos[1][1])) {

        $aParametrosGlobais = $aArgumentos[1][1];
        $aArgumentos[1] = $aArgumentos[1][0];

        $this->setParametrosGlobais($aParametrosGlobais);

      }

      Autenticacao::validaConexao($aArgumentos[0]);

      $oRequisicao = DBWebService::getInstance( $sMetodo );
      $oResposta   = call_user_func_array( array( $oRequisicao, $sMetodo ), $aArgumentos );
      return $oResposta;
    } catch ( Exception $oExcecao ){
      throw new SoapFault( "e-Cidade", utf8_encode($oExcecao->getMessage()) );
    }
  }

  /**
   * Tratamento de Erros
   * @param  ineteger  $errno
   * @param  string    $errstr
   * @param  integer   $errfile
   * @param  integer   $errline
   * @throws SoapFault
   */
  public function handlerError($errno, $errstr, $errfile, $errline) {

    $aTiposErro = array(
      E_ERROR             => 'E_ERROR',
      E_WARNING           => 'E_WARNING',
      E_PARSE             => 'E_PARSE',
      E_NOTICE            => 'E_NOTICE',
      E_CORE_ERROR        => 'E_CORE_ERROR',
      E_CORE_WARNING      => 'E_CORE_WARNING',
      E_CORE_ERROR        => 'E_COMPILE_ERROR',
      E_CORE_WARNING      => 'E_COMPILE_WARNING',
      E_USER_ERROR        => 'E_USER_ERROR',
      E_USER_WARNING      => 'E_USER_WARNING',
      E_USER_NOTICE       => 'E_USER_NOTICE',
      E_STRICT            => 'E_STRICT',
      E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
      E_DEPRECATED        => 'E_DEPRECATED',
      E_USER_DEPRECATED   => 'E_USER_DEPRECATED'
    );

     if ( $errno == E_DEPRECATED ) return;
     if ( $errno == E_NOTICE     ) return;

     throw new SoapFault("e-Cidade", "\n\n" .
       "Erro   : " . $aTiposErro[$errno] . " - " .$errstr ."\n".
       "Arquivo: " . $errfile ."\n".
       "Linha  : " . $errline ."\n".
       "DEBUG  : " . print_r(debug_backtrace(), 1 )
     );
  }

  /**
   * Adiciona os valores nas variaveis de ambiente caso elas sejam informadas
   *
   * @param array $aParametros
   */
  private function setParametrosGlobais($aParametros) {

    if (!empty($aParametros['webservice'])) {

      $aParametros = $aParametros['webservice'];

      if (isset($aParametros['DB_id_usuario'])) {
        $_SESSION['DB_id_usuario'       ] = $aParametros['DB_id_usuario'];
      }
      if (isset($aParametros['DB_login'])) {
        $_SESSION['DB_login'            ] = $aParametros['DB_login'];
      }
      if (isset($aParametros['DB_administrador'])) {
        $_SESSION['DB_administrador'    ] = $aParametros['DB_administrador'];
      }
      if (isset($aParametros['DB_ip'])) {
        $_SESSION['DB_ip'               ] = $aParametros['DB_ip'];
      }
      if (isset($aParametros['REQUEST_URI'])) {
        $_SESSION['REQUEST_URI'         ] = $aParametros['REQUEST_URI'];
      }
      if (isset($aParametros['DB_configuracao_ok'])) {
        $_SESSION['DB_configuracao_ok'  ] = $aParametros['DB_configuracao_ok'];
      }
      if (isset($aParametros['DB_acessado'])) {
        $_SESSION['DB_acessado'         ] = $aParametros['DB_acessado'];
      }
      if (isset($aParametros['DB_instit'])) {
        $_SESSION['DB_instit'           ] = $aParametros['DB_instit'];
      }
      if (isset($aParametros['DB_totalmodulos'])) {
        $_SESSION['DB_totalmodulos'     ] = $aParametros['DB_totalmodulos'];
      }
      if (isset($aParametros['DB_use_pcasp'])) {
        $_SESSION['DB_use_pcasp'        ] = $aParametros['DB_use_pcasp'];
      }
      if (isset($aParametros['DB_Area'])) {
        $_SESSION['DB_Area'             ] = $aParametros['DB_Area'];
      }
      if (isset($aParametros['DB_modulo'])) {
        $_SESSION['DB_modulo'           ] = $aParametros['DB_modulo'];
      }
      if (isset($aParametros['DB_nome_modulo'])) {
        $_SESSION['DB_nome_modulo'      ] = $aParametros['DB_nome_modulo'];
      }
      if (isset($aParametros['DB_coddepto'])) {
        $_SESSION['DB_coddepto'         ] = $aParametros['DB_coddepto'];
      }
      if (isset($aParametros['DB_nomedepto'])) {
        $_SESSION['DB_nomedepto'        ] = $aParametros['DB_nomedepto'];
      }
      if (isset($aParametros['DB_itemmenu_acessado'])) {
        $_SESSION['DB_itemmenu_acessado'] = $aParametros['DB_itemmenu_acessado'];
      }
      if (isset($aParametros['SERVER_ADDR'])) {
        $_SERVER['SERVER_ADDR']           = $aParametros['SERVER_ADDR'];
      }
      if (isset($aParametros['SERVER_PORT'])) {
        $_SERVER['SERVER_PORT']           = $aParametros['SERVER_PORT'];
      }
      if (isset($aParametros['DOCUMENT_ROOT'])) {
        $_SERVER['DOCUMENT_ROOT']         = $aParametros['DOCUMENT_ROOT'];
      }
      if (isset($aParametros['SERVER_ADMIN'])) {
        $_SERVER['SERVER_ADMIN']          = $aParametros['SERVER_ADMIN'];
      }
      if (isset($aParametros['PHP_SELF'])) {
        $_SERVER['PHP_SELF']              = $aParametros['PHP_SELF'];
      }
      if (isset($aParametros['REQUEST_URI'])) {
        $_SERVER["REQUEST_URI"]           = $aParametros['REQUEST_URI'];
      }
      if (isset($aParametros['HTTP_HOST'])) {
        $_SERVER['HTTP_HOST']             = $aParametros['HTTP_HOST'];
      }
    }
  }
}