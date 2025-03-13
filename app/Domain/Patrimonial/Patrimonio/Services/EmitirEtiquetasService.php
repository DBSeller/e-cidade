<?php

namespace App\Domain\Patrimonial\Patrimonio\Services;

use App\Domain\Patrimonial\Patrimonio\Services\EtiquetaService;
use App\Domain\Patrimonial\Patrimonio\Factories\EmitirEtiquetasFactory;
use App\Domain\Patrimonial\Patrimonio\Factories\EtiquetaFactory;
use App\Domain\Patrimonial\Patrimonio\Relatorios\EtiquetasPimacoPDF;
use Illuminate\Database\Eloquent\Collection;

class EmitirEtiquetasService
{

    public function gerarEmicaoEtiquetas(Collection $bens, $modelo, $impressora = 1)
    {
        $dados = [];
        $service= new EtiquetaService();
        $tag = EtiquetaFactory::getEtiqueta($modelo);
        foreach ($bens as $bem) {
            $dados[] = $service->build($tag, $bem);
        }
        return EmitirEtiquetasFactory::getPdf($impressora, $modelo, $dados);
    }
}
