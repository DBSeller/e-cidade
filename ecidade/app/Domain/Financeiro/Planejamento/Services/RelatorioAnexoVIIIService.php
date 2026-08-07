<?php


namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoVIII;

class RelatorioAnexoVIIIService extends AnexosLDOService
{
    public function __construct(array $filtros)
    {
        parent::__construct($filtros);

        $this->processar();
        $this->parser = new XlsAnexoVIII();
    }

    public function emitir()
    {
        $this->parser->setEnteFederativo($this->enteFederativo);
        $this->parser->setEmissor($this->emissor);
        $this->parser->setAnoReferencia($this->plano->pl2_ano_inicial);
        $this->parser->setNotaExplicativa($this->getNotaExplicativa());
        $this->parser->addCollection('itera_dados', $this->linhas);

        $filename = $this->parser->gerar();

        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    public function processar()
    {
        parent::processar();
        $this->processaLinhas();
    }


    protected function processaReceita($linha)
    {
        // TODO: Implement processaReceita() method.
    }

    protected function processaDespesa($linha)
    {
        // TODO: Implement processaDespesa() method.
    }
}
