<?php

namespace App\Domain\Educacao\CentralMatriculas\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Controllers\Controller;
use DBString;
use ECidade\Configuracao\Api\Generator\HashGenerator;
use ECidade\Configuracao\Api\Repository\ApiClienteRepository;
use ECidade\Educacao\MatriculaOnline\Model\Inscricao;
use ECidade\Educacao\MatriculaOnline\Pdf\ComprovanteInscricao;
use ECidade\Educacao\MatriculaOnline\Registry\ConfiguracaoRegistry;
use ECidade\Educacao\MatriculaOnline\Repository\AlteracaoInscricaoRepository;
use ECidade\Educacao\MatriculaOnline\Request\InscricaoRequest;
use ECidade\Educacao\MatriculaOnline\Service\InscricaoService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

class InscricaoController extends Controller
{
    public function __construct()
    {
        require_once(modification("dbforms/db_funcoes.php"));
    }

    public function inscricao(Request $request)
    {
        db_inicio_transacao();
        try {
            $dados = $this->decriptLegacyApiV1($request);

            $dados = DBString::urldecode_all($dados);
            $dados = DBString::utf8_decode_all($dados);

            $inscricaoRequest = new InscricaoRequest($dados);
            $inscricarService = new InscricaoService();

            $configuracao = ConfiguracaoRegistry::get();
            if ($configuracao->isValidaAlunoMatriculado()) {
                $candidatoJaEstaMatriculado = $inscricarService->verificaAlunoMatriculado($inscricaoRequest);
                if ($candidatoJaEstaMatriculado) {
                    $response = ['erro' => 'candidato_ja_matriculado'];
                    db_fim_transacao(true);
                    return new DBJsonResponse($response, "Candidato já está matriculado em uma escola da Rede!");
                }
            }

            $inscricao = $inscricarService->saveFromRequest($inscricaoRequest);

            $listaEsperaService = new \ECidade\Educacao\MatriculaOnline\Service\ListaEsperaService();
            $listaEsperaService->setFase($inscricao->getFase());

            $opcoesLista = $inscricao->getOpcoesListaEspera();
            foreach ($opcoesLista as $opcaoListaEspera) {
                $listaEsperaService->setEscola($opcaoListaEspera->getEscola())
                    ->setEtapa($opcaoListaEspera->getEtapa())
                    ->setTurno($opcaoListaEspera->getTurno());

                $listaEsperaService->classificar();
            }

            $comprovante = $this->emitirComprovante($inscricao);

            $response = [
                'protocolo' => $inscricao->getProtocolo(),
                'path' => $comprovante
            ];

            db_fim_transacao(false);
            return new DBJsonResponse($response, "Inscrição efetuada com sucesso!");
        } catch (Exception $exception) {
            db_fim_transacao(true);
            return new DBJsonResponse([], $exception->getMessage(), 400);
        }
    }

    public function emissaoProtocolo(Request $request)
    {
        try {
            $dados = $this->decriptLegacyApiV1($request);

            if (!isset($dados['protocolo'])) {
                throw new Exception("Protocolo não foi informado.");
            }

            $inscricarService = new InscricaoService();
            $inscricarService->setProtocolo($dados['protocolo']);
            $inscricao = $inscricarService->getInscricao();

            $comprovante = $this->emitirComprovante($inscricao);

            return new DBJsonResponse([
                'success' => true,
                'message' => utf8_encode("Comprovante gerado com sucesso!"),
                'body' => [
                    'protocolo' => $inscricao->getProtocolo(),
                    'path' => $comprovante
                ]
            ], '', 200);
        } catch (Exception $e) {
            return new DBJsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], '', 400);
        }
    }

    private function emitirComprovante(Inscricao $inscricao)
    {
        $alteracaoInscricaoRepository = new AlteracaoInscricaoRepository();
        $alteracoesInscricao = $alteracaoInscricaoRepository->getLastByType($inscricao);

        $inscricao->setAlteracoesInscricao($alteracoesInscricao);
        $comprovanteInscricao = new ComprovanteInscricao($inscricao);

        return $comprovanteInscricao->imprimir();
    }

    /**
     * @param Request $request
     * @return array|bool
     * @throws Exception
     */
    private function decriptLegacyApiV1(Request $request)
    {
        if (!$request->has('hash') || !$request->has('id')) {
            throw new AccessDeniedException('Sem permissão para acessar a API.', 401);
        }

        $id = $request->get('id');

        if (!is_numeric($id)) {
            throw new AccessDeniedException('Sem permissão para acessar a API.', 401);
        }

        $hash = $request->get('hash');

        $cliente = ApiClienteRepository::find($id);

        if ($cliente === false) {
            throw new AccessDeniedException('Cliente não encontrado.', 401);
        }

        $secret = $cliente->getChave();

        $decrypted = HashGenerator::decrypt($hash, $secret);

        if ($decrypted === false) {
            throw new AccessDeniedException('Sem permissão para acessar a API.', 401);
        }

        return $decrypted;
    }
}
