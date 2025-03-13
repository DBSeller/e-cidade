<?php

namespace App\Domain\Patrimonial\Patrimonio\Factories;

use ECidade\Enum\Patrimonial\Patrimonio\ModeloEtiquetaEnum;
use Exception;
use App\Domain\Patrimonial\Patrimonio\Relatorios\EtiquetasPimacoPDF;
use App\Domain\Patrimonial\Patrimonio\Relatorios\EtiquetasPimaco02PDF;

class EmitirEtiquetasFactory
{
    const PIMACO = 1;

    /**
     * Retorna o relatório de acordo com o tipo passado por parametro(1 = MODELO01, 2 = MODELO02)
     * @param integer $tipo
     * @param array $dados
     * @return EtiquetasPimacoPDF|EtiquetasPimaco02PDF
     *@throws Exception
     */
    public static function getPdf($tipo, $modeloEtiqueta, array $dados)
    {
        $template = (new ModeloEtiquetaEnum((int)$modeloEtiqueta))->name();
        switch ($tipo) {
            case self::PIMACO:
                return new EtiquetasPimacoPDF($dados, $template, __DIR__ . '/../Relatorios/TemplatesEtiquetas/');
            default:
                throw new Exception('Erro ao gerar Relatório! Selecione um tipo válido.');
                break;
        }
    }
}
