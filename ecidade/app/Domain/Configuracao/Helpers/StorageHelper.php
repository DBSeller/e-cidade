<?php
namespace App\Domain\Configuracao\Helpers;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Configuracao\Instituicao\Model\DBConfig as Instituicao;
use App\Domain\Configuracao\Instituicao\Repository\Contracts\InstituicaoRepository as RepositoryInterface;
use ECidade\Lib\Request\Storage\Curl\Autenticacao;
use ECidade\Lib\Request\Storage\Curl\Get;
use ECidade\Lib\Request\Storage\Curl\Post;
use ECidade\Lib\Request\Storage\File;
use ECidade\Lib\File\FileEstorage;
use ECidade\V3\Extension\Registry;
use Illuminate\Support\Facades\Log;
use ParameterException;
use Exception;

/**
* Classe helper que reune as funções para trabalhar com o storage
*
* @var string
*/
class StorageHelper
{
    /**
     * Realiza upload de um arquivo para o E-Storage.
     *
     * @param string $caminho
     * @param boolean $onlyId
     * @param \stdClass $metadata
     * @param  $fileFather
     * @return \stdclass|integer $data|$id9
     */
    public static function uploadArquivo(
        $caminho,
        array $allowed = null,
        $onlyId = false,
        \stdClass $metadata = null,
        $fileFather = null
    ) {
        $arquivo = explode("/", $caminho);
        $post = new Post(Autenticacao::getInstance());
        $file = new File();
        $file->realPath($caminho)
            ->clientOriginalName($arquivo[count($arquivo) - 1]);
        if (!empty($metadata)) {
            $file->metadata($metadata);
        }
        $file->visibility("private");
        $file->allowed($allowed);
        if (!empty($fileFather)) {
            $file->fileFather($fileFather);
        }
        $retorno = $post->execute($file);
        if ($onlyId) {
            return $retorno->data->id;
        }
        return $retorno->data;
    }


    public static function atualizarArquivo(
        $id,
        $caminho,
        Array $allowed = null,
        $onlyId = false,
        \stdClass $metadata = null
    ) {
        $arquivo = explode("/", $caminho);
        $post = new Post(Autenticacao::getInstance());
        $file = new File();
        $file->realPath($caminho)
            ->clientOriginalName($arquivo[count($arquivo) - 1]);
        if (!empty($metadata)) {
            $file->metadata($metadata);
        }
        $file->visibility("private");
        $file->allowed($allowed);
        $retorno = $post->change($id, $file);

        if ($onlyId) {
            return $retorno->data->id;
        }

        return $retorno->data;
    }

    /**
     * Realiza download de um arquivo do E-Storage.
     *
     * @param string $idStorage
     * @return string $caminhoArquivo
     */
    public static function downloadArquivo($idStorage)
    {
        return (new FileEstorage())->getPath($idStorage);
    }

    /**
     * Busca as configurações do e-storage
     *
     * @return \stdclass
     */
    public static function getStorageConfig()
    {
        if (!Registry::get('app.config')->has('app.api')) {
            $msg  = "Erro ao buscar as credencias das api's";
            $msg .= "\nVerifique o arquivo de configuração (application).";
            throw new ParameterException($msg);
        }

        $configApi = (object)Registry::get('app.config')->get('app.api');

        if (empty($configApi) || empty($configApi->estorage)) {
            $msg  = "Erro ao buscar as credencias do e-Storage";
            $msg .= "\nVerifique o arquivo de configuração (application).";
            throw new ParameterException($msg);
        }

        return (object)$configApi->estorage;
    }


    /**
     * @param $idStorage
     * @return array
     * @throws Exception
     */
    public static function getContentsBase64($idStorage)
    {
        return (new FileEstorage())->getBase64($idStorage);
    }
}
