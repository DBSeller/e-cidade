<?php

namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoVII;

/**
 * Class RelatorioAnexoVIIService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class RelatorioAnexoVIIService extends AnexosLDOService
{
    private $colunasRelatorio = [
        'tributo' => '',
        'modalidade' => '',
        'programas' => '',
        'ano_referencia' => 0,
        'ano_mais_um' => 0,
        'ano_mais_dois' => 0,
        'compensacao' => '',
    ];

    private $linhasImprimir = [];

    public function __construct(array $filtros)
    {
        parent::__construct($filtros);

        $this->processar();
        $this->parser = new XlsAnexoVII();
    }

    public function emitir()
    {
        $this->parser->setEnteFederativo($this->enteFederativo);
        $this->parser->setEmissor($this->emissor);
        $this->parser->setAnoReferencia($this->plano->pl2_ano_inicial);
        $this->parser->setNotaExplicativa($this->getNotaExplicativa());

        $this->parser->addCollection('iterar_dados', $this->linhasImprimir);

        $filename = $this->parser->gerar();

        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    public function processar()
    {
        parent::processar();
        $this->getLinhas();

        $this->processaLinhasManuais();
    }

    /**
     * @return object
     */
    private function criaLinha()
    {
        return (object)$this->colunasRelatorio;
    }

    /**
     * Cria as linhas manuais
     */
    private function processaLinhasManuais()
    {
        foreach ($this->linhas as $linha) {
            foreach ($linha->oLinhaRelatorio->getValoresColunas() as $index => $valoresManuais) {
                $this->linhasImprimir[$index] = $this->criaLinha();
                foreach ($valoresManuais->colunas as $coluna) {
                    $nomeColuna = $coluna->o115_nomecoluna;
                    if (isset($this->colunasRelatorio[$nomeColuna])) {
                        if (is_string($this->colunasRelatorio[$nomeColuna])) {
                            $this->linhasImprimir[$index]->{$nomeColuna} = $coluna->o117_valor;
                        } else {
                            $this->linhasImprimir[$index]->{$nomeColuna} += $coluna->o117_valor;
                        }
                    }
                }
            }
        }
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
