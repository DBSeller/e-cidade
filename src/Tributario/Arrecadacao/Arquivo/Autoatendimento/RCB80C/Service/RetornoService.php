<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Service;

use ECidade\Tributario\Library\Service;

class RetornoService extends Service
{
    private $detalheService;

    public function __construct(DetalheService $detalheService)
    {
        $this->detalheService = $detalheService;
    }

    public function execute($path)
    {
        $this->detalheService->execute($path);
    }
}