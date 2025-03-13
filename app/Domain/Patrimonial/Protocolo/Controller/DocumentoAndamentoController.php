<?php

namespace App\Domain\Patrimonial\Protocolo\Controller;

use App\Domain\Configuracao\Helpers\StorageHelper;
use App\Domain\Configuracao\Usuario\Models\Usuario;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Protocolo\Factories\DocumentoAndamentoFactory;
use App\Domain\Patrimonial\Protocolo\Model\DocumentoAndamento;
use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoDocumento;
use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoDocumentoAssinatura;
use App\Domain\Patrimonial\Protocolo\Model\ProcessoAtividadeExecucao;
use App\Domain\Patrimonial\Protocolo\Repository\DocumentosAndamentoRepository;
use App\Domain\RecursosHumanos\Pessoal\Services\ContraChequeService;
use ECidade\Lib\Session\DefaultSession;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DocumentoAndamentoController extends Controller
{
    /**
     * @return DBJsonResponse
     * @throws Exception
     */
    public function index()
    {
        $usuario = Usuario::find(db_getsession('DB_id_usuario'));
        $documentosAndamentoRepository = new DocumentosAndamentoRepository();
        $documentosAndamento = $documentosAndamentoRepository->buscarDocumentosPorUsuario($usuario);

        $objetos = [];
        foreach ($documentosAndamento as $documentoAndamento) {
            $documentoService = DocumentoAndamentoFactory::getService($documentoAndamento);
            $objetos[] = $documentoService->montarObjetoTela();
        }
        return new DBJsonResponse($objetos);
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function conferir(Request $request)
    {
        $documentoAndamento = DocumentoAndamento::find($request->get('codigo_documento'));
        $documentoAndamentoService = DocumentoAndamentoFactory::getService($documentoAndamento);
        $documentoAndamentoService->conferir();
        return new DBJsonResponse([], "Documento conferido com sucesso");
    }

    /**
     * @throws Exception
     */
    public function conferirLote(Request $request)
    {
        if (!$request->has('documentos') || empty($request->get('documentos'))) {
            return new DBJsonResponse([], "Nenhum documento informado.");
        }

        $documentos = explode(',', $request->get('documentos'));
        foreach ($documentos as $codigo_documento) {
            $documentoAndamento = DocumentoAndamento::find($codigo_documento);
            $documentoAndamentoService = DocumentoAndamentoFactory::getService($documentoAndamento);
            $documentoAndamentoService->conferir();
        }
        return new DBJsonResponse([], "Documento conferido com sucesso");
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function devolver(Request $request)
    {
        if (empty($request->get('atividade_destino'))) {
            throw new Exception('Atividade de Destino não informada para devolução');
        }
        $documentoAndamento = DocumentoAndamento::find($request->get('codigo_documento'));
        $documentoAndamentoService = DocumentoAndamentoFactory::getService($documentoAndamento);
        $processoAtividadeExecucao = ProcessoAtividadeExecucao::find($request->get('atividade_destino'));
        $documentoAndamentoService->devolverAtividade($processoAtividadeExecucao);
        return new DBJsonResponse([], "Documento devolvido para atividade selecionada");
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function arquivar(Request $request)
    {
        $documentoAndamento = DocumentoAndamento::find($request->get('codigo_documento'));
        $documentoAndamentoService = DocumentoAndamentoFactory::getService($documentoAndamento);
        $documentoAndamentoService->arquivar();
        return new DBJsonResponse([], "Documento arquivado com sucesso");
    }

    /**
     * @throws Exception
     */
    public function salvarDocumentoAssinado(Request $request)
    {
        $base64 = $request->get('base64');
        $file = base64_decode($base64);
        $nomeArquivo = "tmp/doc_assinado_" . time() . ".pdf";
        file_put_contents($nomeArquivo, $file);

        if (!empty(Cache::get('estorage_properties'))) {
            $_SESSION['estorage_properties'] = unserialize(Cache::get('estorage_properties'));
        }
        $arquivoAssinado = StorageHelper::uploadArquivo($nomeArquivo);

        if (isset($_SESSION['estorage_properties'])) {
            Cache::put('estorage_properties', serialize($_SESSION['estorage_properties']), 5);
            unset($_SESSION['estorage_properties']);
        }

        $documentoAndamento = DocumentoAndamento::find($request->get('codigoDocumentoAndamento'));
        $documentoAndamentoService = DocumentoAndamentoFactory::getService($documentoAndamento);
        $documentoAndamentoService->assinar($arquivoAssinado);

        return new DBJsonResponse([], "Documento assinado com sucesso");
    }

    /**
     * @throws Exception
     */
    public function atualizarDocumentoAssinado(Request $request)
    {
        $this->validate($request, [
            'sequencial' => 'required',
            'base64' => 'required',
            'id_estorage' => 'required',
            'qrcode_hash' => 'required'
        ]);

        $base64 = $request->get('base64');
        $file = base64_decode($base64);
        $nomeArquivo = "tmp/doc_assinado_" . time() . ".pdf";
        file_put_contents($nomeArquivo, $file);

        if (!empty(Cache::get('estorage_properties'))) {
            $_SESSION['estorage_properties'] = unserialize(Cache::get('estorage_properties'));
        }

        $storageConfig = StorageHelper::getStorageConfig();
        $allowed = array();
        if (isset($storageConfig->client_id_ouvidoria) && !empty($storageConfig->client_id_ouvidoria)) {
            $allowed[] = $storageConfig->client_id_ouvidoria;
        }
        $idFile = StorageHelper::uploadArquivo(
            $nomeArquivo,
            $allowed,
            true,
            null,
            $request->get("id_estorage")
        );

        if (isset($_SESSION['estorage_properties'])) {
            Cache::put('estorage_properties', serialize($_SESSION['estorage_properties']), 5);
            unset($_SESSION['estorage_properties']);
        }

        $processoDocumento = new \ProcessoDocumento($request->get("sequencial"));
        $processoDocumento->setAssinado(true);
        $defaultSession = DefaultSession::getInstance();
        $processoDocumento->setAssinadoPor($defaultSession->get(DefaultSession::DB_ID_USUARIO));
        $processoDocumento->setOID($idFile);
        $processoDocumento->setOrdem($processoDocumento->getOrdem());
        $processoDocumento->setHash($request->get("qrcode_hash"));
        $processoDocumento->salvar();

        $processoDocumentoAssinatura = new ProcessoDocumentoAssinatura();
        $processoDocumentoAssinatura->p122_protprocessodocumento = $request->get("sequencial");
        $processoDocumentoAssinatura->p122_documento_origem_estorage = $request->get("id_estorage");
        $processoDocumentoAssinatura->p122_documento_assinado_estorage = $idFile;
        $processoDocumentoAssinatura->p122_usuario = $defaultSession->get(DefaultSession::DB_ID_USUARIO);
        if (!$processoDocumentoAssinatura->save()) {
            throw new \Exception("Erro ao salvar histórico");
        }
        return new DBJsonResponse([], "Documento assinado com sucesso");
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function buscarAtividadesExecutadas(Request $request)
    {
        $documentoAndamento = DocumentoAndamento::find($request->get('codigo_documento'));
        $documentoAndamentoService = DocumentoAndamentoFactory::getService($documentoAndamento);
        $atividades = $documentoAndamentoService->buscarAtividadesExecutadas();

        foreach ($atividades as $atividade) {
            $atividade->atividade;
        }
        return new DBJsonResponse($atividades, "Lista de Atividades Executadas");
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function buscarPorIdentificador(Request $request)
    {
        $request->get('qrcode');
        if (!$request->has('qrcode')) {
            return new DBJsonResponse([], "Informe o código identificador do arquivo.", 500);
        }
        if (!$request->has('tipoDocumento') || empty($request->get('tipoDocumento'))) {
            return new DBJsonResponse([], 'Tipo de documento não informado.', 500);
        }

        switch ($request->get('tipoDocumento')) {
            case 'documento':
                $documentosAndamentoRepository = new DocumentosAndamentoRepository();
                $documentoAndamento = $documentosAndamentoRepository->scopeQRCode($request->get('qrcode'))->first();

                if (!empty($documentoAndamento)) {
                    return new DBJsonResponse(
                        StorageHelper::getContentsBase64(
                            $documentoAndamento->processoDocumento->p01_documento
                        )
                    );
                }

                $processoDocumento = new ProcessoDocumento();
                $documento = $processoDocumento->hash($request->get('qrcode'))->first();
                if (!empty($documento)) {
                    return new DBJsonResponse(
                        StorageHelper::getContentsBase64($documento->p01_documento)
                    );
                }

                return new DBJsonResponse([], 'Não foi possível encontrar um documento com esse identificador', 500);
            case 'contracheque':
                $contraChequeService = new ContraChequeService();
                $base64 = $contraChequeService->getByCodigoAutenticacao($request->get('qrcode'));
                if (!$base64) {
                    $invalido = 'Contra-cheque não encontrado, o código de autenticidade não é válido! ';
                    $verifique = 'Verifique o código digitado e tente novamente.';
                    return new DBJsonResponse([], $invalido.$verifique, 500);
                }

                return new DBJsonResponse($base64);
            default:
                return new DBJsonResponse(
                    [],
                    "O Tipo de documento inválido, verifique o endereço digitado ou tente novamente mais tarde.",
                    500
                );
        }
    }
}
