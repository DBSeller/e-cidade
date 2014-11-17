<?php

if (!defined('PATH')) {
  define('PATH', dirname(__DIR__).'/');
}

define('PATH_MODIFICATION', PATH . 'modification/');
define('PATH_MODIFICATION_XML', PATH_MODIFICATION . 'xml/');
define('PATH_MODIFICATION_CACHE', PATH_MODIFICATION . 'cache/');
define('PATH_MODIFICATION_LOG', PATH_MODIFICATION . 'log/');

require_once PATH . 'std/ModificationFile.php';

/**
 * Modificacoes
 */
class Modification {

  /**
   * Arquivos xml com as modificacoes
   * @var array
   */
  private $xmlFiles = array();

  /**
   * Arquivos modificados
   * @var array
   */
  private $files = array();

  /**
   * Carrega os arquivos xml
   * @throws Exception
   * @return Modification
   */
  public function load($glob = '{*.xml}') {

    if (!is_dir(PATH_MODIFICATION)) {
      throw new Exception("Diretorio 'modification' nao criado: " . PATH_MODIFICATION);
    }

    $clearCache = false;
    $lastParseTime = 0;
    $xmlFiles = glob(PATH_MODIFICATION_XML . $glob, GLOB_BRACE);
    $lastCacheTime = filemtime(PATH_MODIFICATION_CACHE);
    $lastXmlTime = filemtime(PATH_MODIFICATION_XML);

    if (file_exists(PATH_MODIFICATION_CACHE . '.last-parse')) {
      $lastParseTime = filemtime(PATH_MODIFICATION_CACHE . '.last-parse');
    }

    /**
     * Pasta dos xml modificada, remove os cache
     */
    if ($lastXmlTime > $lastCacheTime) {
      $clearCache = true;
    }

    foreach ($xmlFiles as $xmlFile) {
      if (filemtime($xmlFile) > $lastParseTime) {
        $clearCache = true;
      }
      $this->xmlFiles[] = $xmlFile;
    }

    if ($clearCache) {
      foreach(glob(PATH_MODIFICATION_CACHE . '*') as $cacheFile) {
        if (!is_dir($cacheFile) && !unlink($cacheFile)) {
          throw new Exception("Nao foi possivel remover arquivo de cache: $cacheFile");
        }
      }
    }

    return $this;
  }

  /**
   * Abre os arquivos xml e modifica os arquivos nele declarados
   * @return Modification
   */
  public function parse() {

    foreach($this->xmlFiles as $xmlFile) {

      $dom = new DOMDocument('1.0');
      $dom->preserveWhiteSpace = false;

      if (!(@$dom->load($xmlFile))) {

        static::log("Documento XML inválido: $xmlFile.");
        continue;
      }

      $nodeModification = $dom->getElementsByTagName('modification')->item(0);
      $files = $nodeModification->getElementsByTagName('file');

      foreach ($files as $file) {
        $this->parseFile($file, filemtime($xmlFile));
      }
    }

    return $this;
  }

  /**
   * Faz parse de um arquivo, tag <file>
   * @param DOMDocument $nodeFile
   * @throws Exception
   * @return void
   */
  private function parseFile(DOMElement $nodeFile, $xmlFileTime) {

    $path = $nodeFile->getAttribute('path');

    if (empty($path)) {
      throw new Exception('Tag <file>: Path do arquivo nao informado.');
    }

    $files = glob(PATH . $path, GLOB_BRACE);

    foreach($files as $file) {

      $modificationFile = ModificationFile::getInstance($file);

      if ($modificationFile->hasCache()) {

        $timeFileCache = filemtime(PATH_MODIFICATION_CACHE . $modificationFile->getKey());

        /**
         * Arquivo de cache foi criado depois da ultima modificacao do seu arquivo
         */
        if ($timeFileCache > filemtime($file) && $xmlFileTime < $timeFileCache) {
          continue;
        }
      }

      $operations = $nodeFile->getElementsByTagName('operation');

      if ($operations->length == 0) {
        throw new Exception("Nenhuma operacao para o arquivo, tag <operation>.");
      }

      foreach ($operations as $operation) {
        $modificationFile->addOperation($this->parseOperation($operation));
      }

      $this->files[$modificationFile->getKey()] = $modificationFile;
      $modificationFile = null;
    }
  }

  /**
   * Parse na tag <operation>
   * @param DOMElement $operation
   * @return StdClass
   */
  private function parseOperation(DOMElement $operation) {

    $search = $operation->getElementsByTagName('search')->item(0);
    $add = $operation->getElementsByTagName('add')->item(0);

    $search = $this->parseOperationSearch($search);
    $add = $this->parseOperationAdd($add);

    return (object) array('search' => $search, 'add' => $add);
  }

  /**
   * Parse da tag <search>
   * @param DOMElement $nodeSearch
   * @return StdClass
   */
  private function parseOperationSearch($nodeSearch) {

    $search = new StdClass();
    $search->regex = false;
    $search->trim = false;
    $search->offset = 0;
    $search->limit = 0;
    $search->content = null;

    if (empty($nodeSearch)) {
      return $search;
    }

    $search->regex = $nodeSearch->getAttribute('regex') == 'true';
    $search->trim = $nodeSearch->getAttribute('trim') == 'true';
    $search->offset = $nodeSearch->getAttribute('offset');
    $search->limit = $nodeSearch->getAttribute('limit');

    /**
     * Converte para latin1
     */
    $search->content = mb_convert_encoding(
      $nodeSearch->textContent,
      "ISO-8859-1",
      mb_detect_encoding($nodeSearch->textContent, "UTF-8, ISO-8859-1, ISO-8859-15", true)
    );

    return $search;
  }

  /**
   * Parse da tag <add>
   * @param DOMElement $nodeAdd
   * @return StdClass
   */
  private function parseOperationAdd(DOMElement $nodeAdd) {

    $add = new StdClass();
    $add->position = $nodeAdd->getAttribute('position');
    $add->trim = $nodeAdd->getAttribute('trim') == 'true';
    $add->newLineAfter = $nodeAdd->getAttribute('new-line-after');
    $add->newLineBefore = $nodeAdd->getAttribute('new-line-before');
    $add->ident = $nodeAdd->getAttribute('ident');

    /**
     * Converte para latin1
     */
    $add->content = mb_convert_encoding(
      $nodeAdd->textContent,
      "ISO-8859-1",
      mb_detect_encoding($nodeAdd->textContent, "UTF-8, ISO-8859-1, ISO-8859-15", true)
    );

    return $add;
  }

  /**
   * @return Modification
   */
  public function save() {

    foreach($this->files as $file) {
      $file->parse()->save();
    }

    return $this;
  }

  /**
   * Retorna o arquivo modificado, caso exista
   * @param string $file
   * @return string
   */
  public static function getFile($file) {

    $path = PATH_MODIFICATION_CACHE . ModificationFile::createKey($file);

    if (file_exists($path) && !is_dir($path)) {

      if (dirname($file) != '.') {
        set_include_path("./" . dirname($file) . PATH_SEPARATOR . get_include_path());
      }

      return $path;
    }

    return $file;
  }

  /**
   * @throws Exception
   * @return bool
   */
  public static function buildStructure() {

    if (!is_dir(PATH_MODIFICATION) && !mkdir(PATH_MODIFICATION)) {
      throw new Exception("Nao foi possivel criar diretorio: " . PATH_MODIFICATION);
    }

    if (!is_dir(PATH_MODIFICATION_LOG) && !mkdir(PATH_MODIFICATION_LOG)) {
      throw new Exception("Nao foi possivel criar diretorio: " . PATH_MODIFICATION_LOG);
    }

    if (!is_dir(PATH_MODIFICATION_XML) && !mkdir(PATH_MODIFICATION_XML)) {
      throw new Exception("Nao foi possivel criar diretorio: " . PATH_MODIFICATION_XML);
    }

    if (!is_dir(PATH_MODIFICATION_CACHE) && !mkdir(PATH_MODIFICATION_CACHE)) {
      throw new Exception("Nao foi possivel criar diretorio: " . PATH_MODIFICATION_CACHE);
    }

    return true;
  }

  /**
   * @return void
   */
  public static function find() {

    try {

      static::buildStructure();

      $oModificacao = new Modification();
      $oModificacao->load()->parse()->save();

      /**
       * Cria/modifica arquivo com time da ultima modificacao
       */
      touch(PATH_MODIFICATION_CACHE . '.last-parse');

    } catch(Exception $oErro) {
      static::log($oErro->getMessage());
    }
  }

  /**
   * @param string $message
   * @return bool
   */
  public static function log($message) {
    return file_put_contents(PATH_MODIFICATION_LOG . 'error.log', $message . PHP_EOL, FILE_APPEND);
  }

}
