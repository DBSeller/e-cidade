<?php


namespace App\Domain\Patrimonial\Material\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Material\Factories\RastreabilidadeMaterialFactory;
use App\Domain\Patrimonial\Material\Models\Deposito;
use App\Domain\Patrimonial\Material\Models\Material;
use App\Domain\Patrimonial\Material\Relatorios\ControleEstoquePDF;
use App\Domain\Patrimonial\Material\Relatorios\ResumoEstoquePDF;
use App\Domain\Patrimonial\Material\Requests\RelatorioControleEstoqueRequest;
use App\Domain\Patrimonial\Material\Requests\RelatorioResumoEstoqueRequest;
use App\Domain\Patrimonial\Material\Requests\RelatorioRastreabilidadeMaterialRequest;
use App\Domain\Patrimonial\Material\Services\ControleEstoqueService;
use App\Domain\Patrimonial\Material\Services\ResumoContabilEstoqueService;
use DBDate;
use Exception;
use App\Http\Controllers\Controller;

class RelatoriosController extends Controller
{
    /**
     * @param RelatorioControleEstoqueRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function controleEstoque(RelatorioControleEstoqueRequest $request)
    {
        $dataInicial = $request->get('dataInicial');
        $dataFinal = $request->get('dataFinal');
        $codigoMaterial = $request->get('materialCodigo');

        $material = Material::find($codigoMaterial);
        $dadosCabecalho = (object)[
            "material" => $material,
            "dataInicial" => $dataInicial,
            "dataFinal" => $dataFinal
        ];
        $controleEstoqueService = new ControleEstoqueService();
        $controleEstoqueService->setDataInicial($dataInicial);
        $controleEstoqueService->setDataFinal($dataFinal);
        if (!empty($request->get('depositoCodigo'))) {
            $controleEstoqueService->setDeposito(Deposito::find($request->get('depositoCodigo')));
        }
        $movimentacoes = $controleEstoqueService->buscarDadosRelatorio($material);
        if (count($movimentacoes) == 0) {
            throw new Exception("Nenhum lançamento encontrado para os filtros selecionados.");
        }
        $relatorio = new ControleEstoquePDF($movimentacoes, $dadosCabecalho);
        return new DBJsonResponse($relatorio->emitirPdf(), "Emitindo relatório em PDF");
    }

    /**
     * @throws Exception
     */
    public function resumoContabilEstoque(RelatorioResumoEstoqueRequest $request)
    {
        $service = new ResumoContabilEstoqueService();
        if (!empty($request->get('dataInicial'))) {
            $dataInicial = DBDate::create($request->get('dataInicial'));
            $service->setDataInicial($dataInicial);
        }
        if (!empty($request->get('dataFinal'))) {
            $dataFinal = DBDate::create($request->get('dataFinal'));
            $service->setDataFinal($dataFinal);
        }
        $service->setDepositos($request->get('depositos'));
        $service->setContas($request->get('contas'));
        $service->setGrupos($request->get('grupos'));

        $service->agruparPorConta($request->has('conta_patrimonial'));
        $service->agruparPorGrupo($request->has('grupo'));
        $service->setTipoImpressao($request->get('tipo_impressao'));
        $service->setOrdem($request->get('ordem'));

        $service->exibirTransferencias($request->get('transferencias') == 't');
        $service->exibirMateriaisSemEstoque($request->get('somente_com_saldo') == 'f');
        $service->exibirSomenteInconsistencias($request->get('inconsistencias') == 'true');
        $pdf = $service->emitir();
        return new DBJsonResponse($pdf, "Emitindo relatório em PDF");
    }

    /**
     * @param RelatorioRastreabilidadeMaterialRequest $request
     * @return DBJsonResponse
     */
    public function rastreabilidadeMaterial(RelatorioRastreabilidadeMaterialRequest $request)
    {
        $service = RastreabilidadeMaterialFactory::getService($request->tipo)
            ->setFiltros($request);
        $dados = $service->buscarDados();
        $pdf = $service->getRelatorio($dados);


        return new DBJsonResponse($pdf->emitir(), 'Emitindo relatório');
    }
}
