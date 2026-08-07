<?php

namespace App\Domain\Patrimonial\Protocolo\Controller\Processo\AndamentoPadrao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao\CamposDinamicos;
use App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao\CamposDinamicosResposta;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\AndamentoPadrao\CamposDinamicosRepository;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\AndamentoPadrao\CamposDinamicosRespostaRepository;
use App\Domain\Patrimonial\Protocolo\Transformers\Processo\AndamentoPadrao
\CamposDinamicosResposta as CamposDinamicosRespostaTransform;

use \Exception;

class CamposDinamicosRespostaController extends Controller
{
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(
        CamposDinamicosRespostaRepository $camposDinamicosRespostaRepository
    ) {
        $this->repository = $camposDinamicosRespostaRepository;
    }

    public function salvar(Request $request)
    {
        $campos          = $request->input('campos', null);
        $codigoAndamento = $request->input('codigo_andamento', null);


        if (empty($campos)) {
            throw new Exception('Informe os campos que devem ser salvos');
        }

        if (empty($codigoAndamento)) {
            throw new Exception('Informe o código do andamento');
        }

        $campos = explode(",", $campos);

        foreach ($campos as $campo) {
            $codigoCampo = preg_replace('/campo_(\d+)/', "$1", $campo);
            $resposta    = $request->input($campo, null);

            $campoResposta = new CamposDinamicosResposta;
            $campoResposta->setCamposandpadrao($codigoCampo);
            $campoResposta->setResposta($resposta);
            $campoResposta->setCodandam($codigoAndamento);

            $this->repository->persist($campoResposta);
        }

        $msgSucesso = "Salvo com sucesso";

        return new DBJsonResponse(null, $msgSucesso, 200, false);
    }

    public function getUltimaResposta(Request $request)
    {
        $codigoVinculoCampo = $request->input('codigo_camposandpadrao', null);
        $codigoAndamento    = $request->input('codigo_codandam', null);

        if (empty($codigoVinculoCampo)) {
            throw new Exception('Informe o código do vínculo do campo');
        }
        
        if (empty($codigoAndamento)) {
            throw new Exception('Informe o código do andamento');
        }

        $camposResposta = $this->repository->getUltimaResposta($codigoVinculoCampo, $codigoAndamento);
        
        return new DBJsonResponse((new CamposDinamicosRespostaTransform)->transform($camposResposta));
    }
}
