<?php

namespace App\Domain\Patrimonial\Patrimonio\Relatorios;

use App\Domain\Patrimonial\Patrimonio\Contracts\Etiqueta;
use Proner\PhpPimaco\Pimaco;
use Proner\PhpPimaco\Tag;

class EtiquetasPimacoPDF extends Pimaco implements Etiqueta
{
    private $dados = [];

    public function __construct($dados, $template, $path_template = null)
    {
        parent::__construct($template, $path_template);
        $this->dados = $dados;
    }

    public function gerar()
    {
        foreach ($this->dados as $tag) {
            $this->addTag($tag);
        }

        $fileName = 'tmp/emitir_etiqueta' . time() . '.pdf';
        $this->pdf->WriteHTML($this->render());
        $this->pdf->Output($fileName, 'F');

        return $fileName;
    }
}
