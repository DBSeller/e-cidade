<?php


namespace App\Domain\Patrimonial\Ouvidoria\Controller\ProcessoEletronico;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Controllers\Controller;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Repository\TipoProcesso;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Repository\TipoProcesso as TipoProcessoRepository;
use Illuminate\Http\Request;
use App\Domain\Patrimonial\Ouvidoria\Services\ProcessoEletronicoService;
use \ECidade\Patrimonial\Ouvidoria\Externa\WebService\ProcessoEletronico\Solicitacao;
use App\Domain\Patrimonial\Ouvidoria\Services\RecadastramentoService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessoEletronicoController extends Controller
{
    private $processoEletronicoService;

    public function __construct(ProcessoEletronicoService $processoEletronicoService)
    {
        $this->processoEletronicoService = $processoEletronicoService;
    }

    public function mensagens($numero_processo)
    {
        return response($this->processoEletronicoService->getMensagens($numero_processo));
    }

    public function saveVisualizacao($p78_sequencial)
    {
        return response()->json($this->processoEletronicoService->saveVisualizacao($p78_sequencial));
    }


    public function menu(Request $request)
    {
        $this->validate($request, [
            'instituicoes' => 'required',
            'formas_reclamacao' => 'required'
        ]);

        return response($this->processoEletronicoService->getMenu(
            $request->get("instituicoes"),
            $request->get('formas_reclamacao'),
            $request->get("cpf_cnpj")
        ));
    }

    public function solicitacaoDeAtendimento(Request $request)
    {

        try {
            $this->validate($request, [
                'metadados' => 'required',
                'departamento' => 'required|integer',
                'tipoprocesso' => 'required|integer',
                'requerente_nome' => 'required',
                'client_atendimento_id' => 'required|integer'
            ]);

            if ($request->get('requerente_nome') == "ANONIMO" || empty($request->get('requerente_nome'))) {
                $tipoProcesso = TipoProcessoRepository::getInstancia()->getByCodigo($request->get('tipoprocesso'));
                if ($tipoProcesso->isIdentificado()) {
                    throw new \Exception("Não foi identificado o nome do requerente");
                }
            }

            $solicitacao = new Solicitacao();
            $solicitacao->setMetadados($request->input('metadados'));
            $solicitacao->setCodigoDepartamento($request->get('departamento'));
            $solicitacao->setTipoProcesso($request->get('tipoprocesso'));
            $solicitacao->setRequerenteNome($request->get('requerente_nome'));
            $solicitacao->setRequerenteCpf($request->get('requerente_cpf'));
            $solicitacao->setCodigoAtendimentoAnterior($request->get('codigo_atendimento_anterior'));
            $solicitacao->setClientAPPAtendimentoID($request->get('client_atendimento_id'));
            return (array)$solicitacao->salvar();
        } catch (\Exception $ex) {
            return response(utf8_encode(urldecode($ex->getMessage())), 400);
        }
    }

    public function consultaServidor(Request $request)
    {
        $this->validate($request, ['cpf' => 'required']);
        $recadastramento = new RecadastramentoService();
        return new DBJsonResponse(
            collect($recadastramento->getDadosServidorCpf($request->get('cpf')))->first(),
            ""
        );
    }

    public function consultarDependentesServidor(Request $request)
    {
        $this->validate($request, ['cpf' => 'required']);
        $recadastramento = new RecadastramentoService();
        return new DBJsonResponse(
            $recadastramento->getDependentesServidorCpf($request->get('cpf')),
            ""
        );
    }

    public function verificaServidorPossuiPermissaoRecadastramento($cpf, $tipoProcesso)
    {
        $recadastramento = new RecadastramentoService();
        return new DBJsonResponse(
            collect(
                $recadastramento->verificaServidorPossuiPermissaoRecadastramento($cpf, $tipoProcesso)
            )->first()
        );
    }

    public function consultaAtendimentosIds(Request $request)
    {
        $this->validate($request, ['ids' => 'required']);
        return new DBJsonResponse($this->processoEletronicoService->getAtendimentos($request->get('ids')));
    }

    public function detalheProcesso($codigoProcesso)
    {
        return new DBJsonResponse(
            $this->processoEletronicoService->getDetalheProcesso($codigoProcesso)
        );
    }
}
