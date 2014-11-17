<?php

class ModificationFile {

  /**
   * @var string
   */
  private $path;

  /**
   * @var string
   */
  private $content;

  /**
   * @var string
   */
  private $key;

  /**
   * @var StdClass[]
   */
  private $operations = array();

  /**
   * @var ModificationFile[]
   */
  private static $instances = array();

  /**
   * @param string $path
   */
  public function __construct($path) {

    if (!is_readable($path)) {
      throw new Exception("Sem permissão de leitura no arquivo: $path");
    }
    $this->key = ModificationFile::createKey($path);
    $this->path = $path;
    $this->content = file_get_contents($path);
  }

  /**
   * @return string
   */
  public function getKey() {
    return $this->key;
  } 
  
  /**
   * @return string
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * @param StdClass $operation
   * @return ModificationFile
   */
  public function addOperation($operation) {

    $this->operations[] = $operation;
    return $this;
  }

  /**
   * @return ModificationFile
   */
  public function parse() {

    foreach($this->operations as $operation) {

      $search = $operation->search->content;
      $limit = $operation->search->limit;
      $offset = $operation->search->offset;

      if ($operation->search->trim) {
        $search = trim($search);
      }

      $add = $operation->add->content;
      $position = $operation->add->position;
      $newLineAfter = $operation->add->newLineAfter;
      $newLineBefore = $operation->add->newLineBefore;
      $ident = $operation->add->ident;
      $replace = null;

      if ($operation->add->trim) {
        $add = trim($add);
      }

      if ($ident) {
        $add = str_repeat(" ", $ident) . $add;
      }

      if ($newLineAfter) {
        $add .= str_repeat("\n", $newLineAfter);
      }

      if ($newLineBefore) {
        $add = str_repeat("\n", $newLineBefore) . $add;
      }

      switch ($position) {

        default:
        case 'replace':
          $replace = $add;
        break;

        case 'before':
          $replace = $add . $search;
        break;

        case 'after':
          $replace = $search . $add;
        break;

        /**
         * final do arquivo
         */
        case 'bottom':

          $this->content = $this->content . $add;
          continue;
        break;

        /**
         * inicio do arquivo
         */
        case 'top':

          $this->content = $add . $this->content;
          continue;
        break;
      }

      if ($operation->search->regex) {

        if (!$limit) {
          $limit = -1;
        }

        $this->content = preg_replace($search, $replace, $this->content, $limit);							
        continue;
      } 

      $pos = -1;
      $currentMatch = 0;
      $match = array();

      /**
       * Busca conteudo da tag <search> e guarda posicao
       */
      while (($pos = strpos($this->content, $search, $pos + 1)) !== false) {
        $match[$currentMatch++] = $pos;
      }

      /** 
       * Offset
       */
      if (!$offset) {
        $offset = 0;
      }

      /**
       * Limit
       */
      if (!$limit) {
        $limit = count($match);
      } else {
        $limit = $offset + $limit;
      }	

      /**
       * Percorre as ocorrencias encontradas, entre offset e limit
       */
      for ($iOffset = $offset; $iOffset < $limit; $iOffset++) {

        if (!isset($match[$iOffset])) {
          continue;
        }

        /**
         * Altera arquivo
         */
        $this->content = substr_replace($this->content, $replace, $match[$iOffset], mb_strlen($search));

        /**
         * Corrige posicao das proximas ocorrencias
         */
        $posFix = mb_strlen($search) - mb_strlen($replace); 
        for ($iFix = $iOffset; $iFix < $limit; $iFix++) {
          $match[$iFix] -= $posFix;
        }
      }

    } 

    return $this;
  }

  /**
   * @return bool
   */
  public function hasCache() {

    $fileCache = PATH_MODIFICATION_CACHE . $this->getKey();
    if (file_exists($fileCache) && !is_dir($fileCache)) {
      return true;
    }

    return false;
  }

  /**
   * @return ModificationFile
   */
  public function clearCache() {

    if (!unlink(PATH_MODIFICATION_CACHE . $this->getKey())) {
      throw new Exception("Não foi possivle remover cache: " . PATH_MODIFICATION_CACHE . $this->getKey());
    } 

    return $this;
  }

  /**
   * @throws Exception
   * @return ModificationFile
   */
  public function save() {

    if (!file_put_contents(PATH_MODIFICATION_CACHE . $this->getKey(), $this->getContent())) {
      throw new Exception("Erro ao salvar arquivo de cache.");
    }

    return $this;
  }

  /**
   * Cria uma chave pelo caminho do arquivo
   * - usado para criar arquivo de cache
   * @param string $file
   * @return string
   */
  public static function createKey($file) {
    return str_replace('/', '-', str_replace(PATH, '', $file));
  }

  /**
   * Cria instancia pelo path do arquivo
   * @param string $file
   * @return ModificationFile
   */
  public static function getInstance($file) {

    if (empty(self::$instances[$file])) {
      self::$instances[$file] = new ModificationFile($file);
    }

    return self::$instances[$file];
  }

}
