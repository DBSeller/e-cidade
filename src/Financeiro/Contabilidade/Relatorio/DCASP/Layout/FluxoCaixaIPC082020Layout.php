<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout;

/**
 * Class FluxoCaixaMCASP2020Layout
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout
 */
class FluxoCaixaIPC082020Layout extends FluxoCaixa2020Layout
{
    const QUADRO_PRINCIPAL_INICIAL = 1;
    const QUADRO_PRINCIPAL_FINAL = 41;
    const QUADRO_TRANSFERENCIAS_INICIAL = 42;
    const QUADRO_TRANSFERENCIAS_FINAL = 57;
    const QUADRO_DESEMBOLSOS_PESSOAL_INICIAL = 58;
    const QUADRO_DESEMBOLSOS_PESSOAL_FINAL = 86;
    const QUADRO_DIVIDA_INICIAL = 87;
    const QUADRO_DIVIDA_FINAL = 90;

    protected $linhasComBordas = [19, 29, 38, 86, 90];

    protected $linhasSemValorQuadroPrincipal = [1, 20, 30, 42, 50];

    protected $totalizadorasSemNegrito = [11, 15, 16, 17, 41];
}
