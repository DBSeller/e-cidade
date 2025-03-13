<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020;

/**
 * Class AnexoVI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020
 */
class AnexoVI extends \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\AnexoVI
{

    /**
     * @var integer
     */
    const CODIGO_RELATORIO = 216;

    /**
     * @return \stdClass[]
     * @throws \ParameterException
     * @throws \Exception
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        $this->carregarDadosRelatorio();
        $this->processarRestosAPagar();
        $this->processarLinhasComIndicadorDeSuperavit();
        $this->processarTotalizadoresCalculoResultadoNominal();
        foreach (array(56, 60, 77, 78) as $linha) {
            $this->processarFormulaDaLinha($linha);
        }
        return $this->aLinhasConsistencia;
    }
}
