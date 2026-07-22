<?php

namespace App\Domain\Configuracao\DocumentosAssinatura\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Configuracao\DocumentosAssinatura\Transform\DocumentosAssinarTransformer;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use ECidade\Lib\File\FileEstorage;
use ECidade\Lib\Request\Storage\File;
use ECidade\Lib\Request\Storage\Curl\Get;
use ECidade\Lib\Request\Storage\Curl\Post;
use ECidade\Lib\Request\Storage\Curl\Put;
use ECidade\Lib\Request\Storage\Curl\Autenticacao;
use \Exception;
use \JSON;

class DocumentosAssinatura extends Controller
{
    public function toSign(Request $request)
    {
        $cpf_cnpj = $request->query('cpf_cnpj', null);

        if (empty($cpf_cnpj)) {
            throw new Exception("Informe o CPF/CNPJ");
        }

        $storageResponse = new Get(Autenticacao::getInstance()->execute());
        $storageResponse->setRoute('/files/sign?cpf_cnpj=' . $cpf_cnpj);
        $storageResponse->execute();

        $infoRequest = $storageResponse->getInfo();
        $response    = $storageResponse->getResponse();

        if ($infoRequest['http_code'] != 200) {
            $response = JSON::create()->parse($response);
            $msg = $response->message;

            if (!empty($response->errors) && !empty($response->errors->permission)) {
                $msg .= "\n";
                $msg .= implode("\n", $response->errors->permission);
            }
            throw new Exception($msg);
        }

        return new DBJsonResponse(
            (new DocumentosAssinarTransformer())->transform(JSON::create()->parse($response)->data)
        );
    }

    public function getFile(Request $request, $file_id)
    {
        if (empty($file_id)) {
            throw new Exception("Informe o ID do arquivo.");
        }

        $older_revision = $request->query('older_revision', null);

        $storageResponse = new Get(Autenticacao::getInstance()->execute());

        if (!empty($older_revision)) {
            $storageResponse->setRoute('/files/' . $file_id . '?older=1');
        } else {
            $storageResponse->setRoute('/files/' . $file_id);
        }
        
        $storageResponse->execute();

        $infoRequest = $storageResponse->getInfo();
        $response    = $storageResponse->getResponse();

        if ($infoRequest['http_code'] != 200) {
            $response = JSON::create()->parse($response);
            $msg = $response->message;

            if (!empty($response->errors) && !empty($response->errors->permission)) {
                $msg .= "\n";
                $msg .= implode("\n", $response->errors->permission);
            }
            throw new Exception($msg);
        }

        $fileExtension = $this->getFileExtension($infoRequest['content_type']);

        return new DBJsonResponse((object) [
            'content'  => base64_encode($response),
            'filename' => $file_id . $fileExtension,
            'type'     => $infoRequest['content_type']
        ]);
    }

    private function getFileExtension($mime_type)
    {
        switch ($mime_type) {
            case 'application/x-abiword':
                $extension = 'abw';
                break;

            case 'application/octet-stream':
                $extension = 'arc';
                break;

            case 'video/x-msvideo':
                $extension = 'avi';
                break;

            case 'application/vnd.amazon.ebook':
                $extension = 'azw';
                break;

            case 'application/octet-stream':
                $extension = 'bin';
                break;

            case 'application/x-bzip':
                $extension = 'bz';
                break;

            case 'application/x-bzip2':
                $extension = 'bz2';
                break;

            case 'application/x-csh':
                $extension = 'csh';
                break;

            case 'application/vnd.ms-fontobject':
                $extension = 'eot';
                break;

            case 'application/epub+zip':
                $extension = 'epub';
                break;

            case 'image/x-icon':
                $extension = 'ico';
                break;

            case 'application/java-archive':
                $extension = 'jar';
                break;

            case 'application/vnd.apple.installer+xml':
                $extension = 'mpkg';
                break;

            case 'application/vnd.oasis.opendocument.presentation':
                $extension = 'odp';
                break;

            case 'application/vnd.oasis.opendocument.spreadsheet':
                $extension = 'ods';
                break;

            case 'application/vnd.oasis.opendocument.text':
                $extension = 'odt';
                break;

            case 'application/vnd.ms-powerpoint':
                $extension = 'ppt';
                break;

            case 'application/x-rar-compressed':
                $extension = 'rar';
                break;

            case 'application/x-sh':
                $extension = 'sh';
                break;

            case 'image/svg+xml':
                $extension = 'svg';
                break;

            case 'application/x-shockwave-flash':
                $extension = 'swf';
                break;

            case 'application/x-tar':
                $extension = 'tar';
                break;

            case 'application/vnd.visio':
                $extension = 'vsd';
                break;

            case 'audio/x-wav':
                $extension = 'wav';
                break;

            case 'application/xhtml+xml':
                $extension = 'xhtml';
                break;

            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $extension = 'xlsx';
                break;
            case 'application/vnd.mozilla.xul+xml':
                $extension = 'xul';
                break;

            case 'audio/3gpp':
                $extension = '3gp';
                break;

            case 'audio/3gpp2':
                $extension = '3g2';
                break;

            case 'application/x-7z-compressed':
                $extension = '7z';
                break;


            case 'application/typescript':
                $extension = 'ts';
                break;

            case 'audio/webm':
                $extension = 'weba';
                break;

            case 'audio/ogg':
                $extension = 'oga';
                break;

            case 'video/ogg':
                $extension = 'ogv';
                break;

            case 'application/ogg':
                $extension = 'ogx';
                break;

            case 'application/javascript':
                $extension = 'js';
                break;

            case 'text/calendar':
                $extension = 'ics';
                break;

            case 'text\/plain':
            case (preg_match('/text\/plain.*/', $mime_type, $searched) && $searched):
                $extension = 'txt';
                break;

            case 'application/msword':
                $extension = 'doc';
                break;
            
            default:
                $extension = preg_replace('/^.*\/(.*)$/', "$1", $mime_type);
                break;
        }

        return '.' . $extension;
    }

    public function newSignFile(Request $request)
    {
        $signers        = $request->input('signers', null);
        $signers_signed = $request->input('signers_signed', null);
        $files          = $request->file('files');
        $file_father    = $request->input('file_father', null);

        if (empty($files)) {
            throw new Exception("Pelo menos um arquivo deve ser informado.");
        }

        $fileStorage = new Post(Autenticacao::getInstance()->execute());

        foreach ($files as $f) {
            $file = new File();
            $file->realPath($f->path());
            $file->clientOriginalName($f->getClientOriginalName());
            $file->visibility('private');

            if (!empty($signers)) {
                $file->signers(JSON::create()->parse(str_replace('\"', '"', $signers)));
            }

            if (!empty($signers_signed)) {
                $file->signersSigned(JSON::create()->parse(str_replace('\"', '"', $signers_signed)));
            }

            if (!empty($file_father)) {
                $file->fileFather($file_father);
            }
        
            $postFile = $fileStorage->execute($file);
        }

        return new DBJsonResponse($postFile->data);
    }
    
    public function updateSigners(Request $request)
    {
        $signers = $request->input('signers', []);
        $file_id = $request->input('file_id');
        
        if (empty($file_id)) {
            throw new Exception('O ID do arquivo deve ser informado.');
        }

        if (!empty($signers)) {
            $signers = JSON::create()->parse(str_replace('\"', '"', $signers));

            if (!is_array($signers)) {
                throw new Exception('O atributo assinantes deve ser um array.');
            }
        }
        
        $attr = [];

        if (!empty($signers)) {
            $signers = array_map(function ($signer) {
                return json_encode($signer);
            }, $signers);

            $attr = [
                "signers" => $signers,
                "sign_required" => 1
            ];
        } else {
            $attr["sign_required"] = 0;
        }

        $fileStorage = new Put(Autenticacao::getInstance()->execute());
        $response = $fileStorage->setFileId($file_id)->update($attr);

        return new DBJsonResponse(!empty($response->data) ? ($response->data) : $response);
    }

    public function getSignersFromFile(Request $request, $file_id)
    {
        if (empty($file_id)) {
            throw new Exception("Informe o ID do arquivo.");
        }

        $all = $request->query('all', null);
        $storageResponse = new Get(Autenticacao::getInstance()->execute());

        if (empty($all)) {
            $storageResponse->setRoute('/signers/' . $file_id);
        } else {
            $storageResponse->setRoute('/signers/' . $file_id . "?all={$all}");
        }

        $storageResponse->execute();

        $infoRequest = $storageResponse->getInfo();
        $response    = $storageResponse->getResponse();

        if ($infoRequest['http_code'] != 200) {
            $response = JSON::create()->parse($response);
            $msg = $response->message;

            if (!empty($response->errors) && !empty($response->errors->permission)) {
                $msg .= "\n";
                $msg .= implode("\n", $response->errors->permission);
            }
            throw new Exception($msg);
        }

        return $response;
    }

    public function getSignersSignedFromFile($file_id)
    {
        if (empty($file_id)) {
            throw new Exception("Informe o ID do arquivo.");
        }

        $storageResponse = new Get(Autenticacao::getInstance()->execute());
        $storageResponse->setRoute('/signers-signed/' . $file_id);
        $storageResponse->execute();

        $infoRequest = $storageResponse->getInfo();
        $response    = $storageResponse->getResponse();

        if ($infoRequest['http_code'] != 200) {
            $response = JSON::create()->parse($response);
            $msg = $response->message;

            if (!empty($response->errors) && !empty($response->errors->permission)) {
                $msg .= "\n";
                $msg .= implode("\n", $response->errors->permission);
            }
            throw new Exception($msg);
        }

        return $response;
    }
}
