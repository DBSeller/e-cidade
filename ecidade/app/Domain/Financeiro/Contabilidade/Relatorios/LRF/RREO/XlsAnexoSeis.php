<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RREO;

class XlsAnexoSeis extends XlsRREO
{
    protected $nomeArquivo = "Anexo VI";
    protected $saveAs = 'tmp/Anexo_VI.xlsx';

    public function setExercicio($ano)
    {
        $this->setVariavel('exercicio', $ano);
    }
}
