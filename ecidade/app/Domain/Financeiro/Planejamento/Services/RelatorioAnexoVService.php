<?php


namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoV;
use stdClass;

/**
 * Class RelatorioAnexoVService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class RelatorioAnexoVService extends AnexosLDOService
{

    public function __construct(array $filtros)
    {
        parent::__construct($filtros);

        $this->processar();
        $this->parser = new XlsAnexoV();
    }

    public function emitir()
    {
        $this->parser->setDados($this->getLinhas());
        $this->parser->setEnteFederativo($this->enteFederativo);
        $this->parser->setEmissor($this->emissor);
        $this->parser->setAnoReferencia($this->plano->pl2_ano_inicial);
        $this->parser->setNotaExplicativa($this->getNotaExplicativa());

        $filename = $this->parser->gerar();

        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    protected function processaLinhas()
    {
        $this->getLinhas();
        $this->processaValorManual();
    }

    protected function processar()
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
