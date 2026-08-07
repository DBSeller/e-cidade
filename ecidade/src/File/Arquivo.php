<?php

namespace ECidade\File;

use Exception;
use FilesystemIterator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class Filesystem
 * @package ECidade\File
 */
class Arquivo
{
    /**
     * @return Arquivo
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Obtenha o valor retornado de um arquivo.
     *
     * @param $path
     * @return mixed
     * @throws Exception
     */
    public function getRequire($path)
    {
        if ($this->isFile($path)) {
            return require $path;
        }

        throw new Exception("Arquivo não existe no caminho {$path}");
    }

    /**
     * Determine se o caminho fornecido é um arquivo.
     *
     * @param $file
     * @return bool
     */
    public function isFile($file)
    {
        return is_file($file);
    }

    /**
     * Exigir o arquivo fornecido uma vez.
     *
     * @param $file
     */
    public function requireOnce($file)
    {
        require_once $file;
    }

    /**
     * Obtenha o hash MD5 do arquivo no caminho fornecido.
     *
     * @param $path
     * @return string
     */
    public function hash($path)
    {
        return md5_file($path);
    }

    /**
     * Escreva o conteúdo de um arquivo, substituindo-o atomicamente, se já existir.
     *
     * @param $path
     * @param $content
     */
    public function replace($path, $content)
    {
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        chmod($tempPath, 0777 - umask());

        file_put_contents($tempPath, $content);

        rename($tempPath, $path);
    }

    /**
     * Prefira a um arquivo.
     *
     * @param $path
     * @param $data
     * @return bool|int
     * @throws Exception
     */
    public function prepend($path, $data)
    {
        if ($this->exists($path)) {
            return $this->put($path, $data . $this->get($path));
        }

        return $this->put($path, $data);
    }

    /**
     * Determine se existe um arquivo ou diretório.
     *
     * @param $path
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Escreva o conteúdo de um arquivo.
     *
     * @param $path
     * @param $contents
     * @param bool $lock
     * @return bool|int
     */
    public function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Obtenha o conteúdo de um arquivo.
     *
     * @param $path
     * @param bool $lock
     * @return bool|false|string
     * @throws Exception
     */
    public function get($path, $lock = false)
    {
        if ($this->isFile($path)) {
            return $lock ? $this->sharedGet($path) : file_get_contents($path);
        }

        throw new Exception("Arquivo não existe no caminho {$path}");
    }

    /**
     * Obtenha o conteúdo de um arquivo com acesso compartilhado.
     *
     * @param $path
     * @return bool|string
     */
    public function sharedGet($path)
    {
        $contents = '';

        $handle = fopen($path, 'rb');

        if ($handle) {
            if (flock($handle, LOCK_SH)) {
                clearstatcache(true, $path);

                $contents = fread($handle, $this->size($path) ?: 1);

                flock($handle, LOCK_UN);
            }

            fclose($handle);
        }

        return $contents;
    }

    /**
     * Obter o tamanho do arquivo de um determinado arquivo.
     *
     * @param $path
     * @return int
     */
    public function size($path)
    {
        return filesize($path);
    }

    /**
     * Anexar a um arquivo.
     *
     * @param $path
     * @param $data
     * @return bool|int
     */
    public function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * Obter ou definir o modo UNIX de um arquivo ou diretório.
     *
     * @param $path
     * @param null $mode
     * @return bool|string
     */
    public function chmod($path, $mode = null)
    {
        if ($mode) {
            return chmod($path, $mode);
        }

        return substr(sprintf('%o', fileperms($path)), -4);
    }

    /**
     * Mova um arquivo para um novo local.
     *
     * @param $path
     * @param $target
     * @return bool
     */
    public function move($path, $target)
    {
        return rename($path, $target);
    }

    /**
     * Crie um link físico para o arquivo ou diretório de destino.
     *
     * @param $target
     * @param $link
     * @return bool
     */
    public function link($target, $link)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return symlink($target, $link);
        }

        $mode = $this->isDirectory($target) ? 'J' : 'H';

        exec("mklink /{$mode} \"{$link}\" \"{$target}\"");

        return true;
    }

    /**
     * Determine se o caminho fornecido é um diretório.
     *
     * @param $directory
     * @return bool
     */
    public function isDirectory($directory)
    {
        return is_dir($directory);
    }

    /**
     * Extraia o nome do arquivo de um caminho de arquivo.
     *
     * @param $path
     * @return mixed
     */
    public function name($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Extraia o componente de nome à direita de um caminho de arquivo.
     *
     * @param $path
     * @return mixed
     */
    public function basename($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * Extraia o diretório pai de um caminho de arquivo.
     *
     * @param $path
     * @return mixed
     */
    public function dirname($path)
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    /**
     * Extraia a extensão do arquivo de um caminho de arquivo.
     *
     * @param $path
     * @return mixed
     */
    public function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Obter o tipo de arquivo de um determinado arquivo.
     *
     * @param $path
     * @return string
     */
    public function type($path)
    {
        return filetype($path);
    }

    /**
     * Obtenha o tipo mime de um determinado arquivo.
     *
     * @param $path
     * @return mixed
     */
    public function mimeType($path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }

    /**
     * Obtenha o horário da última modificação do arquivo.
     *
     * @param $path
     * @return bool|int
     */
    public function lastModified($path)
    {
        return filemtime($path);
    }

    /**
     * Determine se o caminho fornecido é legível.
     *
     * @param $path
     * @return bool
     */
    public function isReadable($path)
    {
        return is_readable($path);
    }

    /**
     * Determine se o caminho fornecido é gravável.
     *
     * @param $path
     * @return bool
     */
    public function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * Encontre nomes de caminho correspondentes a um determinado padrão.
     *
     * @param $pattern
     * @param int $flags
     * @return array|false
     */
    public function glob($pattern, $flags = 0)
    {
        return glob($pattern, $flags);
    }

    /**
     * Obter uma matriz de todos os arquivos em um diretório.
     *
     * @param $directory
     * @param bool $hidden
     * @return SplFileInfo[]
     */
    public function files($directory, $hidden = false)
    {
        return iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(!$hidden)->in($directory)->depth(0)->sortByName(),
            false
        );
    }

    /**
     * Obtenha todos os arquivos do diretório fornecido (recursivo).
     *
     * @param $directory
     * @param bool $hidden
     * @return SplFileInfo[]
     */
    public function allFiles($directory, $hidden = false)
    {
        return iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(!$hidden)->in($directory)->sortByName(),
            false
        );
    }

    /**
     * Mova um diretório.
     *
     * @param $from
     * @param $to
     * @param bool $overwrite
     * @return bool
     */
    public function moveDirectory($from, $to, $overwrite = false)
    {
        if ($overwrite && $this->isDirectory($to) && !$this->deleteDirectory($to)) {
            return false;
        }

        return @rename($from, $to) === true;
    }

    /**
     * Apagar recursivamente um diretório.
     *
     * @param $directory
     * @param bool $preserve
     * @return bool
     */
    public function deleteDirectory($directory, $preserve = false)
    {
        if (!$this->isDirectory($directory)) {
            return false;
        }

        $items = new FilesystemIterator($directory);

        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                $this->deleteDirectory($item->getPathname());
            } else {
                $this->delete($item->getPathname());
            }
        }

        if (!$preserve) {
            @rmdir($directory);
        }

        return true;
    }

    /**
     * Exclua o arquivo em um determinado caminho.
     *
     * @param $paths
     * @return bool
     */
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;

        foreach ($paths as $path) {
            try {
                if (!@unlink($path)) {
                    $success = false;
                }
            } catch (Exception $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Copie um diretório de um local para outro.
     *
     * @param $directory
     * @param $destination
     * @param null $options
     * @return bool
     */
    public function copyDirectory($directory, $destination, $options = null)
    {
        if (!$this->isDirectory($directory)) {
            return false;
        }

        $options = $options ?: FilesystemIterator::SKIP_DOTS;

        if (!$this->isDirectory($destination)) {
            $this->makeDirectory($destination, 0777, true);
        }

        $items = new FilesystemIterator($directory, $options);

        foreach ($items as $item) {
            $target = $destination . '/' . $item->getBasename();

            if ($item->isDir()) {
                $path = $item->getPathname();

                if (!$this->copyDirectory($path, $target, $options)) {
                    return false;
                }
            } else {
                if (!$this->copy($item->getPathname(), $target)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Crie um diretório.
     *
     * @param $path
     * @param int $mode
     * @param bool $recursive
     * @param bool $force
     * @return bool
     */
    public function makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }

        return mkdir($path, $mode, $recursive);
    }

    /**
     * Copie um arquivo para um novo local.
     *
     * @param $path
     * @param $target
     * @return bool
     */
    public function copy($path, $target)
    {
        return copy($path, $target);
    }

    /**
     * Remova todos os diretórios dentro de um determinado diretório.
     *
     * @param $directory
     * @return bool
     */
    public function deleteDirectories($directory)
    {
        $allDirectories = $this->directories($directory);

        if (!empty($allDirectories)) {
            foreach ($allDirectories as $directoryName) {
                $this->deleteDirectory($directoryName);
            }

            return true;
        }

        return false;
    }

    /**
     * Obter todos os diretórios dentro de um determinado diretório.
     *
     * @param $directory
     * @return array
     */
    public function directories($directory)
    {
        $directories = array();

        foreach (Finder::create()->in($directory)->directories()->depth(0)->sortByName() as $dir) {
            $directories[] = $dir->getPathname();
        }

        return $directories;
    }

    /**
     * Esvazie o diretório especificado de todos os arquivos e pastas.
     *
     * @param $directory
     * @return bool
     */
    public function cleanDirectory($directory)
    {
        return $this->deleteDirectory($directory, true);
    }
}
