<?php

namespace ECidade\Api\V1\Controllers\Tributario;

use ECidade\Api\V1\Controllers\GenericController;
use ECidade\Api\V1\ResourceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ECidade\Tributario\Arrecadacao\Custas\Service\EmissaoExterna;
use Exception;
use ECidade\Lib\Session\DefaultSession;
use ECidade\Tributario\Arrecadacao\Custas\Relatorio\RelatorioRecibo;

class Recibo extends GenericController implements ResourceInterface
{
    public function geraRecibo()
    {
        try {
            $request = $this->request->request;
            $descontoQuitarTudo = $request->get('descontoQuitarTudo', false);
            $isCarne = $request->get('isCarne', false);
            $iniciais = $request->get('iniciais');
            $numpres = $request->get('numpres');
            $matricula = $this->request->get('matricula');
            $inscricao = $this->request->get('inscricao');
            $numCgm = null;
            // Caso nao tenha matricula e inscricao deve receber o cgm, caso contrario gera exception no processamento
            if (empty($matricula) && empty($inscricao)) {
            	$numCgm = $this->request->get('cgm');
            }

            $emissao = new EmissaoExterna();

            $emissao->setDescontoQuitarTudo($descontoQuitarTudo);
            $emissao->setCarne($isCarne);

            if (!empty($iniciais)) {
                $emissao->setIniciais($iniciais);
            }

            if (!empty($numpres)) {
                $emissao->setNumpres($numpres);
            }

            if (!empty($matricula)) {
                $emissao->setMatricula($matricula);
            }

            if (!empty($inscricao)) {
                $emissao->setInscricao($inscricao);
            }

            if (!empty($numCgm)) {
                $emissao->setCgm($numCgm);
            }

            $codigoInstituicao = DefaultSession::getInstance()->get('DB_instit');
            $emissao->setCodigoInstituicao($codigoInstituicao);

            $recibos = $emissao->processar();
            $pdf = new RelatorioRecibo();

            $url = $pdf->imprimir($recibos);
            /** @todo Emitir pdfs e responder com as urls de download */

            return new JsonResponse(array(
                'success' => true,
                'message' => "Recibos gerados com sucesso!",
                'path' => $url
            ), 200);

        } catch (Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'message' => utf8_encode_all($e->getMessage())
            ), 500);
        }
    }
}
