<?php

namespace ECidade\Configuracao\Workflow\Filter;

/**
 * Filtro para execução de transições de workflow de processos
 */
class Transicao
{
    private $processo;
    private $atividadeOrigem;
    private $atividadeDestino;

    public function setProcesso($processo)
    {
        $this->processo = $processo;
    }

    public function getProcesso()
    {
        return $this->processo;
    }

    public function setAtividadeOrigem($atividadeOrigem)
    {
        $this->atividadeOrigem = $atividadeOrigem;
    }

    public function getAtividadeOrigem()
    {
        return $this->atividadeOrigem;
    }

    public function setAtividadeDestino($atividadeDestino)
    {
        $this->atividadeDestino = $atividadeDestino;
    }

    public function getAtividadeDestino()
    {
        return $this->atividadeDestino;
    }
}
