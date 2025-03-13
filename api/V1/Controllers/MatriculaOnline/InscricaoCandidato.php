<?php

namespace ECidade\Api\V1\Controllers\MatriculaOnline;

use DBString;
use ECidade\Api\V1\Controllers\GenericController;
use ECidade\Api\V1\ResourceInterface;
use ECidade\Educacao\MatriculaOnline\Model\Inscricao;
use ECidade\Educacao\MatriculaOnline\Pdf\ComprovanteInscricao;
use ECidade\Educacao\MatriculaOnline\Registry\ConfiguracaoRegistry;
use ECidade\Educacao\MatriculaOnline\Repository\AlteracaoInscricaoRepository;
use ECidade\Educacao\MatriculaOnline\Request\InscricaoRequest;
use ECidade\Educacao\MatriculaOnline\Service\InscricaoService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class InscricaoCandidato extends GenericController implements ResourceInterface
{
    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function inscricao()
    {
        db_inicio_transacao();
        try {
            $dados = $this->request->request->all();

            $dados = DBString::urldecode_all($dados);
            $dados = DBString::utf8_decode_all($dados);

            $inscricaoRequest = new InscricaoRequest($dados);
            $inscricarService = new InscricaoService();

            $configuracao = ConfiguracaoRegistry::get();
            if ($configuracao->isValidaAlunoMatriculado()) {
                $candidatoJaEstaMatriculado = $inscricarService->verificaAlunoMatriculado($inscricaoRequest);
                if ($candidatoJaEstaMatriculado) {
                    $response = $this->response(
                        "Candidato já está matriculado em uma escola da Rede!",
                        array('erro' => 'candidato_ja_matriculado')
                    );
                    db_fim_transacao(true);
                    return $response;
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

            $response = $this->response(
                "Inscrição efetuada com sucesso!",
                array(
                    'protocolo' => $inscricao->getProtocolo(),
                    'path' => $comprovante
                )
            );

            db_fim_transacao(false);
            return $response;
        } catch (Exception $e) {
            db_fim_transacao(true);
            return $this->response($e->getMessage(), array(), false, 400);
        }
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function emissaoProtocolo()
    {
        try {
            $dados = $this->request->request->all();
            if (!isset($dados['protocolo'])) {
                throw new Exception("Protocolo não foi informado.");
            }

            $inscricarService = new InscricaoService();
            $inscricarService->setProtocolo($dados['protocolo']);
            $inscricao = $inscricarService->getInscricao();

            $comprovante = $this->emitirComprovante($inscricao);

            return new JsonResponse(array(
                'success' => true,
                'message' => utf8_encode("Comprovante gerado com sucesso!"),
                'body' => array(
                    'protocolo' => $inscricao->getProtocolo(),
                    'path' => $comprovante
                )
            ), 200);
        } catch (Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'message' => $e->getMessage()
            ), 400);
        }
    }

    /**
     * @param Inscricao $inscricao
     * @return string
     * @throws Exception
     */
    private function emitirComprovante(Inscricao $inscricao)
    {
        $alteracaoInscricaoRepository = new AlteracaoInscricaoRepository();
        $alteracoesInscricao = $alteracaoInscricaoRepository->getLastByType($inscricao);

        $inscricao->setAlteracoesInscricao($alteracoesInscricao);
        $comprovanteInscricao = new ComprovanteInscricao($inscricao);

        return $comprovanteInscricao->imprimir();
    }
}
