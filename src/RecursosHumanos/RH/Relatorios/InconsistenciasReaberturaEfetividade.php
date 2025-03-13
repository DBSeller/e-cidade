<?php

namespace ECidade\RecursosHumanos\RH\Relatorios;

use DBCompetencia;
use PDFDocument;
use Servidor;

/**
 * Class InconsistenciasReaberturaEfetividade
 * @package ECidade\RecursosHumanos\RH\Relatorios
 */
class InconsistenciasReaberturaEfetividade extends Layout
{
    /**
     *
     */
    const TAMANHO_FONTE = 8;
    /**
     *
     */
    const ALTURA_LINHA = 5;

    /**
     * @var array
     */
    private $inconsistencias = array();

    /**
     * @var DBCompetencia
     */
    private $competencia;

    /**
     * @param array $inconsistencias
     */
    public function setInconsistencias(array $inconsistencias)
    {
        $this->inconsistencias = $inconsistencias;
    }

    /**
     *
     */
    protected function montar()
    {
        /** @var string $assentamento */
        foreach ($this->inconsistencias as $assentamento => $servidores) {
            $x = $this->montarCabecalho($assentamento);

            /** @var Servidor[] $servidores */
            foreach ($servidores as $servidor) {
                if (static::ALTURA_LINHA * 4 > $this->pdf->getAvailHeight()) {
                    $this->pdf->AddPage();
                    $x = $this->montarCabecalho($assentamento);
                }

                $x = $this->montarLinha(
                    $this->largura * 0.15,
                    static::ALTURA_LINHA,
                    $servidor->getMatricula(),
                    $x
                );
                $this->montarLinha(
                    $this->largura * 0.85,
                    static::ALTURA_LINHA,
                    $servidor->getCgm()->getNome(),
                    $x
                );
                $x = $this->quebrarLinha();
            }
        }
    }

    /**
     * @param string $assentamento
     * @return int
     */
    private function montarCabecalho($assentamento)
    {
        $this->montarLinha(
            $this->largura,
            static::ALTURA_LINHA,
            $assentamento,
            10,
            true,
            PDFDocument::ALIGN_LEFT,
            true
        );
        return $this->quebrarLinha();
    }

    /**
     *
     */
    protected function montarDescricao()
    {
        $this->pdf->addHeaderDescription('INCONSISTÊNCIAS DA REABERTURA DA EFETIVIDADE');

        $competencia = "{$this->competencia->getMes()}/{$this->competencia->getAno()}";

        $this->pdf->addHeaderDescription("FOLHA VIGENTE: {$competencia}");
    }

    /**
     * @param DBCompetencia $competencia
     */
    public function setCompetencia(DBCompetencia $competencia)
    {
        $this->competencia = $competencia;
    }
}
