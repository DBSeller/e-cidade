<?php


namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout;

/**
 * Class FluxoCaixaMCASP2020Layout
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout
 */
class FluxoCaixaMCASP2020Layout extends FluxoCaixa2020Layout
{
    const QUADRO_PRINCIPAL_INICIAL = 1;
    const QUADRO_PRINCIPAL_FINAL = 38;
    const QUADRO_TRANSFERENCIAS_INICIAL = 39;
    const QUADRO_TRANSFERENCIAS_FINAL = 54;
    const QUADRO_DESEMBOLSOS_PESSOAL_INICIAL = 55;
    const QUADRO_DESEMBOLSOS_PESSOAL_FINAL = 83;
    const QUADRO_DIVIDA_INICIAL = 84;
    const QUADRO_DIVIDA_FINAL = 87;

    protected $linhasComBordas = [16, 26, 36, 83, 87];

    protected $linhasSemValorQuadroPrincipal = [1, 17, 27];
}
