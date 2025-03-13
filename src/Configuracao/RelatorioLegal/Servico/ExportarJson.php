<?php

namespace ECidade\Configuracao\RelatorioLegal\Servico;

use Exception;
use JSON;

class ExportarJson extends Exportar
{
    /**
     * @throws Exception
     */
    public function exportar()
    {
        $this->getDados();
        $this->processar();

        $this->arquivo = "tmp/relatorio_legal_{$this->relatorio->getSequencial()}_" . time() . ".json";
        file_put_contents($this->arquivo, JSON::create()->stringify($this->dadosProcessados));
    }
}
