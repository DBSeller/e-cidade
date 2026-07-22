<?php

class ConfiguracaoIni  {

  private static $aIgnorePath = array(
    'dbagata/',
    'plugins/'
  );

  /**
   * Implementação da funcção glob do php de forma recursiva
   * @author Jeferson Belmiro
   * @see http://php.net/manual/pt_BR/function.glob.php   para saber mais sobre a função glob
   * @param  string  $pattern
   * @param  integer $flags
   * @param  string  $path
   * @param  boolean $recursive
   * @return array
   */
  static public function glob($pattern='*', $flags = 0, $path='', $recursive = false) {

    $files = glob($path . $pattern, $flags);
    if (!$recursive) {
      return $files;
    }

    $paths = glob($path . '*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT|$flags);
    foreach ($paths as $path) {
      $files = array_merge($files, self::glob($pattern, $flags, $path, $recursive));
    }

    return $files;
  }

  /**
   * Retorna os arquivos ini que serão editaveis
   * @param  string $sPath diretorio raiz do e-cidade
   * @return aFilterFiles[]
   */
  static public function getFiles( $sPath ) {

    $aIni = ConfiguracaoIni::glob('*ini', GLOB_NOSORT, "{$sPath}/", true);

    $aFilterFiles = array();
    foreach ($aIni as $iIndice => $sFile) {

      $sFilterFile = str_replace("{$sPath}/", "", $sFile);

      foreach( self::$aIgnorePath as $sIgnore ) {

        if (  is_numeric( strpos($sFilterFile, $sIgnore) ) ) {
          continue 2;
        }
      }
      $aFilterFiles[] = $sFilterFile;
    }

    return $aFilterFiles;
  }

  /**
   * retorna os parâmetros de um arquivo .ini presente no e-cidade
   * @param  string    $sFile nome do arquivo o diretório a partir da raiz do e-cidade
   * @throws Exception        Somente se não enconstra o arquivo
   * @return array
   */
  static public function getConfigIniFile($sFile) {


    if (!is_file($sFile)) {
      throw new Exception($sFile . " não é um arquivo.");
    }

    return parse_ini_file($sFile, true);
  }

  static public function saveConfig($sFile, $aParameters) {

    if (!is_file($sFile) || !is_writable($sFile)) {
      return false;
    }

    $sContent = "";
    foreach ($aParameters as $sIndex => $mDados) {

      if ( is_array($mDados) ) {

        $sContent .= "[{$sIndex}]\n";
        foreach ($mDados as $oDados) {
          $sContent .= "{$oDados->name}={$oDados->value}\n";
        }

      } else {
        $sContent .= "{$mDados->name}={$mDados->value}\n";
      }
    }


    return (boolean) file_put_contents($sFile, $sContent);
  }

}