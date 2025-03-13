<?php

namespace App\Domain\Assinador\Controllers;

use App\Domain\Assinador\Service\CertificadoPfxService;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Controllers\Controller;
use ECidade\Lib\Request\Storage\Curl\Autenticacao;
use ECidade\Lib\Request\Storage\Curl\Get;
use ECidade\Lib\Request\Storage\Curl\Post;
use ECidade\Lib\Request\Storage\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use JSON;
use Illuminate\Support\Facades\Response;
use InstituicaoRepository;
use UsuarioSistema;
use UsuarioSistemaRepository;

class AssinadorAPI extends Controller
{
    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function recuperarPFX(Request $request)
    {
        $usuario = new UsuarioSistema($request->get('DB_id_usuario'));

        $certificadoService = new CertificadoPfxService();
        $arquivoCertificadoPFX = $certificadoService->buscarCertificadoPfxUsuario($usuario);

        return new DBJsonResponse($arquivoCertificadoPFX);
    }

    public function arquivoSalvarAssinado(Request $request)
    {

        $processedFiles = [];


        $tmpData = [
            'eid' => $request->id,
            'fileB64' => $request->contentB64,
            'fileLaravel' => $this->convertBase64FileToLaravelFile($request->contentB64),
            'eid_father' => $request->id,
            'sign_required' => true,
            'signers' => [
                ['name' => 'Luis Justin', 'cpf' => '01386292001'],
                ['name' => 'Bruno Henrique', 'cpf' => '01386292002']
            ],
            'signers_signed' => []
        ];

        array_push($processedFiles, $tmpData);

        $fileStorage = new Post(Autenticacao::getInstance()->execute());

        $postFile = null;
        $oldID = null;
        $newID = null;

        foreach ($processedFiles as $file) {
            $fileTMP = new File();

            $fileTMP->realPath($file['fileLaravel']->path());
            $fileTMP->clientOriginalName($file['fileLaravel']->getClientOriginalName());
            $fileTMP->visibility('private');
            $fileTMP->signersRequired($file['sign_required']);

            if (!empty($file['signers'])) {
                $fileTMP->signers($file['signers']);
            }

            if (!empty($file['signers_signed'])) {
                $fileTMP->signersSigned($file['signers_signed']);
            }

            if (!empty($file['eid_father'])) {
                $fileTMP->fileFather($file['eid_father']);
            }

            $postFile = $fileStorage->execute($fileTMP);

            $oldID = $file['eid'];
            $newID = $postFile->data->id;

            $updateFile = DB::table('protocolo.protprocessodocumento')
                ->where('p01_nomedocumento', $oldID)
                ->update(['p01_nomedocumento' => $newID]);
        }

        return json_encode([
            'newEID' => $newID,
            'oldEID' => $oldID,
            'contentB64' => $request->contentB64
        ]);
    }

    public function arquivoProcesso(Request $request)
    {
        $codigoProtocolo = $request->codigo;

        $files = DB::table('protocolo.protprocessodocumento')
            ->select(
                'p01_sequencial as id',
                'p01_descricao as descricao',
                'p01_nomedocumento as estorageid',
                'p01_descricao as descricao'
            )
            ->where('p01_protprocesso', $codigoProtocolo)
            ->orderBy('p01_ordem', 'desc')
            ->get();

        $eStorageFiles = [];

        $storageResponse = new Get(Autenticacao::getInstance()->execute());


        foreach ($files as $file) {
            $file_id = $file->estorageid;
            $older_revision = true;

            $storageResponse->setRoute('/files/' . $file_id);


            $storageResponse->execute();

            $infoRequest = $storageResponse->getInfo();
            $response = $storageResponse->getResponse();

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

            if ($fileExtension === ".pdf") {
                array_push($eStorageFiles, [
                    'estorage_id' => $file_id,
                    'docID' => $file->id,
                    'realname' => $file->descricao,
                    'content' => base64_encode($response),
                    'filename' => $file_id . $fileExtension,
                    'type' => $infoRequest['content_type']
                ]);
            }
        }

        return new DBJsonResponse($eStorageFiles);
    }

    public function obterArquivoBase64API($eid)
    {

        if (!($eid > 0)) {
            return new DBJsonResponse("ERROR NEED id_estorage");
        }

        if (!empty(Cache::get('estorage_properties'))) {
            $_SESSION['estorage_properties'] = unserialize(Cache::get('estorage_properties'));
        }
        $storageResponse = new Get(Autenticacao::getInstance()->execute());
        if (isset($_SESSION['estorage_properties'])) {
            Cache::put('estorage_properties', serialize($_SESSION['estorage_properties']), 5);
            unset($_SESSION['estorage_properties']);
        }

        $storageResponse->setRoute('/files/' . $eid);
        $storageResponse->execute();
        $infoRequest = $storageResponse->getInfo();
        $response = $storageResponse->getResponse();

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

        $retArray = [
            'id_estorage' => $eid,
            'content_estorage' => base64_encode($response),
            'filename_estorage' => $eid . $fileExtension,
            'type_estorage' => $infoRequest['content_type']
        ];

        return new DBJsonResponse($retArray);
    }

    public function salvarArquivoTMPeRetornarArquivo(Request $request)
    {

        $data = base64_decode($request->b64file);
        $filename = uniqid() . time() . ".pdf";

        $mountPath = ECIDADE_PATH . "tmp" . DS . $filename;
        file_put_contents($mountPath, $data);

        return Response::make(file_get_contents($mountPath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    private function convertBase64FileToLaravelFile($base64EncondedFile, $filename = null)
    {

        $data = base64_decode($base64EncondedFile);

        if ($filename === null) {
            $filename = uniqid() . time() . ".pdf";
        }

        $mountPath = ECIDADE_PATH . "tmp" . DS . $filename;
        file_put_contents($mountPath, $data);

        $tmpFile = new \Symfony\Component\HttpFoundation\File\File($mountPath);
        $file = new UploadedFile($tmpFile->getPathname(), $tmpFile->getFilename(), $tmpFile->getMimeType(), 0, true);

        return $file;
    }

    private function getFileExtension($mime_type)
    {
        switch ($mime_type) {
            case 'application/x-abiword':
                $extension = 'abw';
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

    public function getDataFromFile(Request $request)
    {

        $fileOldId = $request->fileID;
        $numPortaria = $request->numero;
        $storageResponse = new Get(Autenticacao::getInstance()->execute());
        $storageResponse->setRoute('/files/metadata?field=numero&value=' . $numPortaria);
        $storageResponse->execute();

        // $infoRequest = $storageResponse->getInfo();
        $response = $storageResponse->getResponse();

        dd($response);

        $data = json_decode($response, true)["data"];

        $filtered = array_filter($data, function ($key) use ($fileOldId) {
            return $key['id'] == $fileOldId;
        });
        $filtered = array_shift($filtered);

        foreach ($filtered['metadata'] as $key => $val) {
            $filtered['metadata'][$key]['data'] = json_decode($val['data'], true);
        }

        return Response::make($filtered);
    }

    public function getSignersFromID(Request $request)
    {

        $id_portaria = $request->id;
        $signersArray = [];
        $signers = DB::select("SELECT DISTINCT cgm.z01_cgccpf AS cpf_cnpj,
            cgm.z01_nome AS nome
            FROM portaria
            JOIN portariatipo ON portariatipo.h30_sequencial = portaria.h31_portariatipo
            JOIN assinaturadocumentodesignacao ON h30_sequencial = h31_portariatipo
            JOIN db_usuacgm ON db_usuacgm.id_usuario = assinaturadocumentodesignacao.db59_usuario
            JOIN cgm ON cgm.z01_numcgm = db_usuacgm.cgmlogin
            WHERE h31_sequencial = " . $id_portaria);

        return Response::make($signers);
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function getSignerConfig(Request $request)
    {
        $instituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
        $codigoUsuarioLogado = db_getsession('DB_id_usuario');
        $usuarioSistema = UsuarioSistemaRepository::getPorCodigo($codigoUsuarioLogado);

        if ($usuarioSistema->getCGM()->isJuridico()) {
            $cpfCnpj = $usuarioSistema->getCGM()->getCnpj();
        } else {
            $cpfCnpj = $usuarioSistema->getCGM()->getCpf();
        }

        $pathLogo = "imagens/files/{$instituicao->getImagemLogo()}";
        if (file_exists($pathLogo)) {
            $logoPrefeitura = Image::make($pathLogo);
        } else {
            $logoPrefeitura = Image::canvas(60, 92);
            $logoPrefeitura->fill('#F0F0F0');
        }

        $logoPrefeitura->resize(60, 92, function ($constraint) {
            $constraint->aspectRatio();
        });
        $logoBase64 = $logoPrefeitura->encode('data-url')->getEncoded();
        $pos = strpos($logoBase64, ',');
        $logoBase64 = substr($logoBase64, $pos + 1);

        $config = (object)[
            'fileID' => null,
            'fileB64' => '',
            'fileCertificate' => '',
            'isCertBase64' => false,
            'qrcode_link' => '',
            'qrcode_hash' => '',
            'nameSigner' => $usuarioSistema->getCGM()->getNome(),
            'userDocument' => $cpfCnpj,
            'logoBase64' => $logoBase64,
            'header_1' => $instituicao->getDescricao(),
            'header_2' => '',
            'header_3' => '',
        ];

        return new DBJsonResponse($config);
    }
}
