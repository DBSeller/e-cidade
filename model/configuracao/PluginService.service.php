<?php
/*
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


/**
 * Manipulador do model Plugin, utilizado para realizar comportamento do Plugin
 * @author Renan Melo <renan@dbseller.com.br>
 * @author Vitor ROcha <vitor@dbseller.com.br>
 */
class PluginService {

  const MENSAGEM = 'configuracao.configuracao.pluginService.';

  /**
   * Instala o plugin enviado pelo formulario
   * @param  string $sCaminhoArquivo Caminho do arquivo que foi feito upload
   * @return boolean                  True se plugin instalado com sucesso
   * @throws Se plugin não está valido
   */
  public function instalar($sCaminhoArquivo) {

    $sExt = pathinfo($sCaminhoArquivo, PATHINFO_EXTENSION);

    if ($sExt != "gz") {
      throw new Exception("Formato de arquivo inválido.");
    }

    $sNomePlugin = $this->descompactar($sCaminhoArquivo);
    $sCaminhoPlugin = "tmp/{$sNomePlugin}";

    $oDataManifest = $this->loadManifest($sCaminhoPlugin."/Manifest.xml");

    $sNomePlugin = $oDataManifest->plugin->attributes()->name;

    $oPlugin = new Plugin(null, $sNomePlugin);

    if ( $oPlugin->getCodigo() && is_dir("plugins/{$sNomePlugin}") ) {
      throw new Exception( _M( self::MENSAGEM . "plugin_ja_instalado") );
    }

    if (is_dir("plugins/{$sNomePlugin}")) {

      $oPluginDesinstalar = new Plugin();
      $oPluginDesinstalar->setSituacao(true);
      $oPluginDesinstalar->setNome($sNomePlugin);

      $this->desinstalar($oPluginDesinstalar, false);
    }

    if ( !$this->validar($sCaminhoPlugin."/") ) {
      throw new Exception( _M(self::MENSAGEM . "xml_inconsistente") );
    }

    rename($sCaminhoPlugin, "plugins/{$sNomePlugin}");

    $oDataManifest = $this->loadManifest("plugins/{$sNomePlugin}/Manifest.xml");

    /**
     * Insere plugin no banco
     */
    $oPlugin = new Plugin(null, $oDataManifest->plugin->attributes()->name);

    if ($oPlugin->getCodigo()) {

      if ($oPlugin->isAtivo()) {
        $this->ativar($oPlugin, false);
      }

      return true;
    }

    $oPlugin->setNome($oDataManifest->plugin->attributes()->name);
    $oPlugin->setLabel($oDataManifest->plugin->attributes()->label);
    $oPlugin->setSituacao(false);

    try {
      $oPlugin->salvar();
    } catch (DBException $e) {
      unlink("plugins/{$sNomePlugin}/Manifest.xml");
      unlink("plugins/{$sNomePlugin}/fontes.tar.gz");
      unlink("plugins/{$sNomePlugin}/estrutura.tar.gz");
      rmdir("plugins/{$sNomePlugin}");
      throw $e;
    }

    return true;
  }

  /**
   * Descompacta um arquivo tar.gz no temp do projeto
   * @param string $sCaminhoArquivo Caminho do arquivo tar.gz
   * @param string $sDestino Caminho destino
   * @return string                  Nome do arquivo descompactado
   */
  private function descompactar($sCaminhoArquivo, $sDestino = '') {

    $oArquivo = new PharData($sCaminhoArquivo);

    $sDestino = empty($sDestino) ? basename($sCaminhoArquivo, ".tar.gz") : $sDestino;

    if (!$oArquivo->extractTo("tmp/{$sDestino}", null, true)) {
      throw new Exception(_M( self::MENSAGEM . "falha_descompactar" ) );
    }

    return $sDestino;
  }

  /**
   * Metodo responsavel por fazer a validação do Plugin.
   *  -Todos os arquivos especificados no Manifest.XML devem existir no plugin.
   *  -Todos os arquivos que existem no plugin devem estar especificados no Manifest.XML.
   *  -Os arquivos especificados no Manifest.XML não podem existir no e-cidade.
   *  -A versão especificada no Manifest.XML deve ser <= que a versão atual do e-cidade.
   * @param  string $sPlugin caminho temporário do plugin.
   * @return mixed  - Retorna TRUE se o plugin for válido.
   *                - Retorna Exception com a descrição do erro, caso o plugin seja inválido.
   */
  private function validar($sPlugin){

    if (empty($sPlugin)){
      throw new BusinessException(_M(self::MENSAGEM . 'manifest_nao_informado'));
    }

    /**
     * Carrega o Manifest.XML
     * @var [type]
     */
    $sCaminhosManifest = $sPlugin . '/Manifest.xml';
    $oPluginManifest   = $this->loadManifest($sCaminhosManifest);
    $oPlugin           = $oPluginManifest->plugin;

    /**
     * Verifica se o módulo informado é valido
     */
    if (!empty($oPlugin->menus)) {

      $oDBModulo = new cl_db_modulos();

      $sSqlModulo = $oDBModulo->sql_query_file($oPlugin['id-modulo']);
      $rsModulo   = $oDBModulo->sql_record( $sSqlModulo );

      if (!$rsModulo || !pg_num_rows($rsModulo)) {
        throw new Exception( _M(self::MENSAGEM . 'id_modulo_invalido') );
      }
    }

    /**
     * Array com todos os arquivos especificados no XML.
     */
    $aFilesXML = array();

    /**
     * Array com todos o arquivos fontes do pluguin.
     */
    $sPathFontes     = $sPlugin . "fontes.tar.gz";
    $aArquivosPlugin = $this->getArquivosPlugin($sPathFontes);

    /**
     * Verifica se todos os arquivos especificados no
     * XML existem no diretorio do plugin.
     */
    $oFiles = $oPlugin->files;

    foreach ($oFiles->file as $aFile) {

      $aFilesXML[] = $aFile['path'];

      if (!in_array($aFile['path'], $aArquivosPlugin)) {
        throw new BusinessException(_M(self::MENSAGEM . 'arquivo_nao_encontrado', (object) array('sPath'=>$aFile['path'])));
      }
    }

    /**
     * Verifica se todos os arquivos contidos no diretorio do
     * plugin estão especificados no arquivo XML
     */
    foreach ($aArquivosPlugin as $sArquivo) {

      if (!in_array($sArquivo, $aFilesXML)) {
        throw new BusinessException(_M(self::MENSAGEM . 'arquivo_nao_especificado', (object) array('sPath'=>$sArquivo)));
      }
    }

    /**
     * Verifica se os arquivos informados no plugin já existem no e-cidade e se
     * não esta sendo incluido nos fontes o arquivo db_conecta.php
     */
    foreach ($aArquivosPlugin as $sArquivo) {

      if (preg_match('/db_conecta\.php/', file_get_contents("phar://{$sPlugin}fontes.tar.gz{$sArquivo}"))) {
        throw new BusinessException( _M( self::MENSAGEM . 'db_conecta_incluido', (object) array('sPath' => $sArquivo)) );
      }

      if (file_exists("./$sArquivo")) {
        throw new BusinessException(_M(self::MENSAGEM . 'arquivo_ja_existe', (object) array('sPath'=>$sArquivo)));
      }
    }

    /**
     * Verifica se a versão especificada no XML é menor ou igual a do e-cidade.
     */
    $iVersao  = $GLOBALS['db_fonte_codversao'];
    $iRelease = $GLOBALS['db_fonte_codrelease'];
    $sVersao  = "2.{$iVersao}.{$iRelease}";

    if ( $oPlugin['ecidade-version'] > $sVersao){
      throw new BusinessException(_M(self::MENSAGEM . 'versao_invalida'));
    }

    /**
     * Valida os itens de menus. Todos os itens de menu 'FOLHA' devem possuir
     * uma função cadastrada, e todos as funções devem ter sido informada no manifest como file.
     */
    $oMenus      = $oPlugin->menus;

    if ( !empty($oMenus->menu) ) {
      $aFilesMenus = $this->getMenus($oMenus->menu);

      foreach ($aFilesMenus as $sFileMenu) {

        if (!in_array($sFileMenu, $aArquivosPlugin)) {
          throw new BusinessException(_M(self::MENSAGEM . 'arquivo_nao_especificado', (object) array('sPath'=>$sFileMenu)));
        }
      }
    }

    /**
     * Valida os arquivos que irão criar a estrutura no banco de dados
     */
    if (property_exists($oPlugin, "estrutura")) {

      /**
       * Array com todos o arquivos de estrutura do pluguin.
       */
      $sPathEstrutura     = $sPlugin . "estrutura.tar.gz";

      if (!file_exists($sPathEstrutura)) {
        throw new BusinessException( _M( self::MENSAGEM . 'arquivo_nao_encontrado',
                                         (object) array('sPath' => 'estrutura.tar.gz')) );
      }

      $aArquivosEstrutura = $this->getArquivosPlugin($sPathEstrutura);

      if (!isset($oPlugin->estrutura['install'])) {
        throw new BusinessException( _M( self::MENSAGEM . 'estrutura_install_nao_informado') );
      }

      if (!isset($oPlugin->estrutura['uninstall'])) {
        throw new BusinessException( _M( self::MENSAGEM . 'estrutura_uninstall_nao_informado') );
      }

      if (!in_array($oPlugin->estrutura['install'], $aArquivosEstrutura)) {

        throw new BusinessException( _M( self::MENSAGEM . 'arquivo_nao_encontrado',
                                         (object) array('sPath' => $oPlugin->estrutura['install'])) );
      }

      if (!in_array($oPlugin->estrutura['uninstall'], $aArquivosEstrutura)) {

        throw new BusinessException( _M( self::MENSAGEM . 'arquivo_nao_encontrado',
                                         (object) array('sPath' => $oPlugin->estrutura['uninstall'])) );
      }

      if (in_array("/EstruturaCallback.php", $aArquivosEstrutura)) {

        $this->requireEstruturaCallback( $sPathEstrutura, basename($sPlugin) . "/estrutura" );

        if (!class_exists("EstruturaCallback")) {
          throw new BusinessException( _M( self::MENSAGEM . 'classe_estrutura_nao_encontrada' ) );
        }

        if (!in_array("EstruturaPluginCallback", class_implements("EstruturaCallback"))) {
          throw new BusinessException( _M( self::MENSAGEM . 'classe_estrutura_sem_interface' ) );
        }
      }

    }

    return true;
  }

  /**
   * Descompacta os arquivos da estrutura e inclui o fonte dos callbacks
   * @param  string $sPathEstrutura
   * @param  string $sPathDestino
   */
  private function requireEstruturaCallback($sPathEstrutura, $sPathDestino) {

    $sPathEstruturaTmp = $this->descompactar($sPathEstrutura, $sPathDestino);

    require_once("interfaces/iEstruturaPluginCallback.interface.php");
    require_once("tmp/{$sPathEstruturaTmp}/EstruturaCallback.php");
  }

  /**
   * Retorna array com os itens de menu
   * @param  object $oMenus
   * @return array  $aMenus
   */
  private function getMenus($oMenus) {

    $aFiles = array();

    foreach ($oMenus->item as $oItem) {

      if (isset($oItem->item)) {

        $aRetorno = $this->getMenus($oItem);
        $aFiles   = array_merge($aFiles, $aRetorno);
      } else {

        if ($oItem['file'] == '') {

          throw new BusinessException(_M(self::MENSAGEM . 'item_menu_vazio', (object) array('sMenu' => $oItem['name'])));
        }

        $aFiles[] = "/" . preg_replace('/(\?.*)/', '', $oItem['file']);
      }
    }

    return $aFiles;
  }

  /**
   * Retorna um array com a arvore do Plugin.
   *
   * @param  string $sCaminho Caminho do diretorio
   * @param  string $sFolder  Diretorio a ser percorrido
   * @return array  $aRetorno caminho dos fontes.
   */
  private function getArquivosPlugin($sCaminho, $sFolder = '') {

    $sPathFontes     = 'phar://' . $sCaminho . $sFolder;
    $aRetorno        = array();
    $aArquivosPlugin = scandir($sPathFontes);

    foreach ($aArquivosPlugin as $sArquivo) {

      if ( is_dir($sPathFontes . '/' . $sArquivo ) ) {

       $aRetornoDiretorio = $this->getArquivosPlugin($sCaminho, $sFolder.'/'.$sArquivo);
       $aRetorno = array_merge($aRetorno, $aRetornoDiretorio);
      } else {
        $aRetorno[] = $sFolder . '/' . $sArquivo;
      }
    }

    return $aRetorno;
  }

  /**
   * Carrega o arquivo manifest.xml
   * @param  string $sCaminhosManifest
   * @return SimpleXml
   */
  private function loadManifest($sCaminhosManifest) {

    if (!file_exists($sCaminhosManifest)) {
      throw new Exception(_M(self::MENSAGEM . 'manifest_nao_existe'));
    }

    return simplexml_load_file($sCaminhosManifest);
  }

  /**
   * Desinstala um plugin do sistema
   * @param  Plugin $oPlugin instância do plugin a ser desinstalado
   * @return boolean          True se desinstalado com sucesso
   */
  public function desinstalar(Plugin $oPlugin) {

    if ($oPlugin->getSituacao()) {
      $this->desativar($oPlugin);
    }

    if ($oPlugin->getCodigo()) {
      $oPlugin->excluir();
    }

    $sNomePlugin = $oPlugin->getNome();

    if ( is_dir("plugins/{$sNomePlugin}") && !is_dir("tmp/" . date("YmdHis") . $sNomePlugin)) {
      rename("plugins/{$sNomePlugin}", "tmp/" . date("YmdHis") . $sNomePlugin);
    }

    return true;
  }

  /**
   * Ativa um plugin para uso
   * @param  Plugin $oPlugin instancia do Plugin que será ativado
   * @return boolean          Situação alterada
   */
  public function ativar(Plugin $oPlugin, $lCriarMenus = true) {

    if (!$oPlugin->isAtivo()) {

      $oDataManifest = $this->loadManifest("plugins/". $oPlugin->getNome() . "/Manifest.xml");

      /**
       * Cria a estrutura do banco de dados
       */
      $this->rodaEstrutura($oPlugin, "install");



      /**
       * Cria os menus do plugin
       */
      if (!empty($oDataManifest->plugin->menus->menu)) {

        foreach ($oDataManifest->plugin->menus->menu as $oMenu) {
          $this->criaMenus($oMenu, $oPlugin->getCodigo(), $oDataManifest->plugin["id-modulo"]);
        }
      }
  	}

    /**
     * Move os arquivos para o ecidade
     */
    $sFontes = "plugins/". $oPlugin->getNome() . "/fontes.tar.gz";
    $oArquivo = new PharData($sFontes);
    $oArquivo->extractTo(".", null, true);

    $oPlugin->setSituacao(true);
    return $oPlugin->alterarSituacao();
  }

  /**
   * Desativa um plugin para uso
   * @param  Plugin $oPlugin instancia do Plugin que será desativado
   * @return boolean          Situação alterada
   */
  public function desativar(Plugin $oPlugin) {

    if (!$oPlugin->isAtivo()) {
      return false;
    }

    $oPlugin->setSituacao(false);

    if ($oPlugin->getCodigo()) {

      $oPlugin->alterarSituacao();
      $this->apagaMenus($oPlugin);
    }

    $oDataManifest = $this->loadManifest("plugins/". $oPlugin->getNome()."/Manifest.xml");
    $oFiles = $oDataManifest->plugin->files;

    /**
     * Limpa o cache dos menus
     */
    DBMenu::limpaCache('', '', $oDataManifest->plugin["id-modulo"]);

    foreach ($oFiles->file as $oFile) {
      if ($oPlugin->getCodigo() && !file_exists(".".$oFile["path"] )) {
        throw new Exception( _M( self::MENSAGEM . "arquivo_nao_encontrado", (object) array('sPath' => $oFile["path"]) ));
      }
    }

    /**
     * Remove os arquivos do ecidade
     */
    foreach ($oFiles->file as $oFile) {
      $sPath = $oFile["path"];
      if ( file_exists(".".$sPath) ) {
        unlink(".".$sPath);
      }
    }

    /**
     * Remove a estrutura do banco de dados
     */
    $this->rodaEstrutura($oPlugin, "uninstall");

    return true;
  }

  /**
   * Roda a estrutura do plugin na base de dados
   * @param  Plugin $oPlugin
   * @param  string $sEstrutura - Tipo do arquivo de estrutura
   * @throws Exception
   * @return boolean
   */
  private function rodaEstrutura(Plugin $oPlugin, $sEstrutura) {

    $oConfiguracao = $this->getConfig()->AcessoBase;
    $lCallback = false;

    $sPathEstrutura = "plugins/" . $oPlugin->getNome() . "/estrutura.tar.gz";
    $oDataManifest  = $this->loadManifest("plugins/" . $oPlugin->getNome() . "/Manifest.xml");

    if (!property_exists($oDataManifest->plugin, 'estrutura')) {
      return false;
    }

    $sSql = file_get_contents( "phar://{$sPathEstrutura}{$oDataManifest->plugin->estrutura[$sEstrutura]}" );

    if ( file_exists("phar://{$sPathEstrutura}/EstruturaCallback.php") ) {

      $this->requireEstruturaCallback($sPathEstrutura, $oPlugin->getNome() . "/estrutura");
      $lCallback = true;

      $oEstruturaCallback = new EstruturaCallback();
    }

    $oDatabase = new Database();

    $oDatabase->setBase( pg_dbname() );
    $oDatabase->setServidor( pg_host() );
    $oDatabase->setPorta( pg_port() );
    $oDatabase->setUsuario( $oConfiguracao->usuario );
    $oDatabase->setSenha( $oConfiguracao->senha );

    try {

      $oDatabase->connect();

      $oDatabase->execute("select fc_startsession()");
      $oDatabase->execute("begin");

      $rsSearchPath = $oDatabase->execute("show search_path");

      if ($lCallback) {

        if ($sEstrutura == 'install') {
          $oEstruturaCallback->beforeInstall($oDatabase);
        } else {
          $oEstruturaCallbacj->beforeUninstall($oDatabase);
        }
      }

      $oDatabase->execute("set search_path to plugins");
      $oDatabase->execute($sSql);
      $oDatabase->execute("set search_path to " . Database::fetchRow($rsSearchPath, 0)->search_path);

      if ($lCallback) {

        if ($sEstrutura == 'install') {
          $oEstruturaCallback->afterInstall($oDatabase);
        } else {
          $oEstruturaCallbacj->afterUninstall($oDatabase);
        }
      }

      $oDatabase->execute("commit");
      $oDatabase->disconnect();

    } catch (Exception $oException) {
      throw new Exception( "Estrutura:\n " . $oException->getMessage() );
    }

    /**
     * Retomando a conexão antiga
     * O PHP irá retomar a conexão antiga ativa e não criar uma nova
     */
    $GLOBALS['conn'] = pg_connect(  "host=" . db_getsession("DB_servidor")
                                   ." dbname=" . db_getsession("DB_base")
                                   ." port=" . db_getsession("DB_porta")
                                   ." user=" . db_getsession("DB_user")
                                   ." password=" . db_getsession("DB_senha") );
  }

  /**
   * Lê o arquivo de configuração "config/plugins.json" e retorna seu conteúdo
   * @throws Exception
   * @return JSON
   */
  public function getConfig() {

    $sPathConfigFile = "config/plugins.json";

    if (!file_exists($sPathConfigFile)) {
      throw new Exception( _M(self::MENSAGEM . "arquivo_config_nao_encontrado") );
    }

    $oConfiguracao = json_decode( file_get_contents($sPathConfigFile) );

    if (!property_exists($oConfiguracao, "AcessoBase")) {
      throw new Exception( _M(self::MENSAGEM . "acesso_base_nao_informado") );
    }

    if (!property_exists($oConfiguracao->AcessoBase, "usuario") || empty($oConfiguracao->AcessoBase->usuario)) {
      throw new Exception( _M(self::MENSAGEM . "usuario_base_nao_informado") );
    }

    if (!property_exists($oConfiguracao->AcessoBase, "senha") || empty($oConfiguracao->AcessoBase->senha)) {
      throw new Exception( _M(self::MENSAGEM . "senha_base_nao_informado") );
    }

    return $oConfiguracao;
  }

  /**
   * Cria os menus do plugin
   * @param  SimpleXMLElement $oMenu    Nó menu do xml Manifest
   * @param  integer          $iPlugin  Id do plugin
   * @param  integer          $iModulo  Id do módulo, especificado no xml Manifest
   * @param  integer          $iMenuPai Item de menu pai (utilizado para recursão da arvore)
   *                                    Caso não passe o pai, o método pega o pai de acordo com o xml Manifest e o módulo
   * @throws Exception
   * @return void
   */
  private function criaMenus(SimpleXMLElement $oMenu, $iPlugin, $iModulo, $iMenuPai = null) {

    $oDaoDbitensmenu = new cl_db_itensmenu();
    $oDaoDbpermissao = new cl_db_permissao();
    $oDaoDbmenu      = new cl_db_menu();
    $oDaoPluginMenu  = new cl_db_pluginitensmenu();

    if (empty($iMenuPai)) {
      switch ($oMenu["type"]) {
        case 1:
          $sTipoDescricao = "Cadastros";
        break;
        case 2:
          $sTipoDescricao = "Consultas";
        break;
        case 3:
          $sTipoDescricao = "Relatórios";
        break;
        case 4:
          $sTipoDescricao = "Procedimentos";
        break;
        default:
          throw new Exception( _M( self::MENSAGEM . "tipo_menu_desconhecido", (object) array("sTipo" => $oMenu["type"])));
      }

      $sSqlItenMenu = $oDaoDbitensmenu->sql_query_menus( null,
                                                         "i.id_item",
                                                         null,
                                                         "descricao = '{$sTipoDescricao}' and modulo = {$iModulo}" );

      $rsItenMenu = $oDaoDbitensmenu->sql_record($sSqlItenMenu);

      $oItemPai = db_utils::fieldsMemory($rsItenMenu, 0);

      $iMenuPai = $oItemPai->id_item;
    }


    foreach ($oMenu->item as $oItemMenu) {

      /**
       * Insere item de menu no sistema
       */
      $oDaoDbitensmenu->id_item    = null;
      $oDaoDbitensmenu->descricao  = utf8_decode($oItemMenu["name"]);
      $oDaoDbitensmenu->help       = utf8_decode($oItemMenu["name"]);
      $oDaoDbitensmenu->funcao     = $oItemMenu["file"];
      $oDaoDbitensmenu->itemativo  = "1";
      $oDaoDbitensmenu->manutencao = "1";
      $oDaoDbitensmenu->desctec    = utf8_decode($oItemMenu["name"]);
      $oDaoDbitensmenu->libcliente = $oItemMenu["liberado-cliente"];
      $oDaoDbitensmenu->incluir(null);

      if ($oDaoDbitensmenu->erro_status == '0') {
        throw new DBException($oDaoDbitensmenu->erro_msg);
      }

      $oDaoPluginMenu->db146_sequencial   = null;
      $oDaoPluginMenu->db146_db_plugin    = $iPlugin;
      $oDaoPluginMenu->db146_db_itensmenu = $oDaoDbitensmenu->id_item;
      $oDaoPluginMenu->incluir(null);

      if ($oDaoPluginMenu->erro_status == "0") {
        throw new DBException( _M( self::MENSAGEM . "falha_vinculacao_menu" ) );
      }

      /**
       * Busca o lugar certo na arvore de menus
       */
      $rsSequenciaMenu = $oDaoDbmenu->sql_record( $oDaoDbmenu->sql_query_file( null,
                                                                               "(max(menusequencia)+1) as menusequencia",
                                                                               null,
                                                                               "id_item = {$iMenuPai}") );

      if (!$rsSequenciaMenu) {
        throw new DBException( _M( self::MENSAGEM . "falha_organizar_menu", (object) array('sMenu' => $oItemMenu["name"]) ));
      }

      $oMenuSequencia = db_utils::fieldsMemory($rsSequenciaMenu,0);

      /**
       * Organizando o item de menu abaixo do item selecionado
       */
      $oDaoDbmenu->id_item        = $iMenuPai;
      $oDaoDbmenu->id_item_filho  = $oDaoDbitensmenu->id_item;
      $oDaoDbmenu->menusequencia  = $oMenuSequencia->menusequencia == NULL ? 1 : $oMenuSequencia->menusequencia;
      $oDaoDbmenu->modulo         = $iModulo;
      $oDaoDbmenu->incluir(null);

      if ($oDaoDbmenu->erro_status == '0') {
        throw new DBException($oDaoDbmenu->erro_msg);
      }


      /**
       * Liberando permissao de menu para o usuario que criou o relatorio
       */
      $oDaoDbpermissao->id_item        = $oDaoDbitensmenu->id_item;
      $oDaoDbpermissao->id_usuario     = db_getsession('DB_id_usuario');
      $oDaoDbpermissao->permissaoativa = '1';
      $oDaoDbpermissao->anousu         = db_getsession('DB_anousu');
      $oDaoDbpermissao->id_instit      = db_getsession('DB_instit');
      $oDaoDbpermissao->id_modulo      = $iModulo;

      $oDaoDbpermissao->incluir( db_getsession('DB_id_usuario'),
                                 $oDaoDbitensmenu->id_item,
                                 db_getsession('DB_anousu'),
                                 db_getsession('DB_instit'),
                                 $iModulo );

      if ($oDaoDbpermissao->erro_status == '0') {
        throw new DBException($oDaoDbpermissao->erro_msg);
      }

      if ( isset($oItemMenu->item) ) {
        $this->criaMenus($oItemMenu, $iPlugin, $iModulo, $oDaoDbitensmenu->id_item);
      }

      /**
       * Limpa o cache dos menus
       */
      DBMenu::limpaCache('', '', $iModulo);
    }

  }

  /**
   * Apaga os menus vinculados ao plugin
   * @param  Plugin $oPlugin instancia do plugin
   * @return void
   */
  private function apagaMenus(Plugin $oPlugin) {

    $oDaoDbitensmenu = new cl_db_itensmenu();
    $oDaoDbpermissao = new cl_db_permissao();
    $oDaoDbmenu      = new cl_db_menu();
    $oDaoPluginMenu  = new cl_db_pluginitensmenu();

    $sSqlPluginMenu = $oDaoPluginMenu->sql_query_file( null,
                                                       "db146_sequencial, db146_db_itensmenu",
                                                       null,
                                                       "db146_db_plugin = " . $oPlugin->getCodigo() );

    $rsPluginMenu = $oDaoPluginMenu->sql_record($sSqlPluginMenu);

    if ($oDaoPluginMenu->numrows > 0) {

      foreach (db_utils::getCollectionByRecord($rsPluginMenu) as $oPluginMenu) {
        $oDaoDbpermissao->excluir(null, null, null, null, null, "id_item = " . $oPluginMenu->db146_db_itensmenu );
        $oDaoDbmenu->excluir(null, "id_item_filho = " . $oPluginMenu->db146_db_itensmenu);
        $oDaoPluginMenu->excluir($oPluginMenu->db146_sequencial);
        $oDaoDbitensmenu->excluir($oPluginMenu->db146_db_itensmenu);
      }
    }

    return;
  }

  /**
   * Retorna todos os plugins instalados no sistema
   * @return Plugin[] Coleção de plugins
   */
  public function getPlugins() {

    $oDaoPlugin = db_utils::getDao("db_plugin");

    $sSqlPlugins = $oDaoPlugin->sql_query();
    $rsPlugins   = $oDaoPlugin->sql_record($sSqlPlugins);

    if ($oDaoPlugin->numrows == 0) {
      return array();
    }

    $aPlugins = array();

    foreach (db_utils::getCollectionByRecord($rsPlugins) as $oDadoPlugin) {

      if (!is_dir("plugins/{$oDadoPlugin->db145_nome}")) {
        continue;
      }

      $oNovoPlugin = new Plugin();
      $oNovoPlugin->setSituacao($oDadoPlugin->db145_situacao == "t");
      $oNovoPlugin->setNome($oDadoPlugin->db145_nome);

      $oPlugin = new stdClass();
      $oPlugin->iCodigo       = $oDadoPlugin->db145_sequencial;
      $oPlugin->sNome         = $oDadoPlugin->db145_nome;
      $oPlugin->sLabel        = $oDadoPlugin->db145_label;
      $oPlugin->lConfiguracao = (boolean) $this->getPluginConfig($oNovoPlugin);
      $oPlugin->lSituacao     = $this->isAtivo($oNovoPlugin);

      $aPlugins[] = $oPlugin;
    }

    return $aPlugins;
  }

  /**
   * Retorna a configuração de um plugin, caso exista o arquivo de configuração
   *
   * @param  Plugin $oPlugin
   * @return mixed array|null
   */
  public static function getPluginConfig(Plugin $oPlugin) {

    $sPathConfig = "plugins/{$oPlugin->getNome()}/config.ini";

    if (!is_file($sPathConfig)) {
      return null;
    }

    $aConfiguracao = parse_ini_file($sPathConfig);
    return $aConfiguracao;
  }

  /**
   * Grava o arquivo de configuração do plugin
   *
   * @param Plugin $oPlugin
   * @param array $aConfig
   * @return boolean
   */
  public static function setPluginConfig(Plugin $oPlugin, $aConfig) {

    $sPathConfig = "plugins/{$oPlugin->getNome()}/config.ini";

    if (!is_file($sPathConfig) || !is_writable($sPathConfig)) {
      return false;
    }

    $sContent = "";
    foreach ($aConfig as $sProperty => $sValue) {
      $sContent .= "{$sProperty}={$sValue}\n";
    }

    return (boolean) file_put_contents($sPathConfig, $sContent);
  }

  /**
   * Verifica se o Plugin esta ativo
   * @param  Plugin  $oPlugin
   * @return boolean
   */
  public function isAtivo(Plugin $oPlugin) {

    $lAtivoPlugin  = $oPlugin->isAtivo();
    $oDataManifest = $this->loadManifest("plugins/" . $oPlugin->getNome() . "/Manifest.xml");

    $oFiles = $oDataManifest->plugin->files;

    foreach ($oFiles->file as $oFile) {
      $lAtivoArquivos = file_exists("." . $oFile["path"] );
      break;
    }

    return $lAtivoPlugin && $lAtivoArquivos;
  }
}