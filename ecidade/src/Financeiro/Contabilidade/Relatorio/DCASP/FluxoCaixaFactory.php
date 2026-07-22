<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP;

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\FluxoCaixaIPC82020;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\FluxoCaixaMCASP2020;
use FluxoCaixaDCASP;
use FluxoCaixaDCASP2015;
use FluxoCaixaDCASP2017;
use FluxoCaixaDCASP2017IPC8;

// phpcs:disable
require_once(modification('libs/db_sessoes.php')); // @codingStandardsIgnoreLine
// phpcs:enable


class FluxoCaixaFactory
{
    private $processador;
    private $codigoRelatorio;
    private $fluxoCaixa;
    private $ano;
    private $periodo;

    public function __construct($periodo = null)
    {
        $this->periodo = $periodo;
        $this->ano = db_getsession('DB_anousu');
        $this->processador = 'con2_fluxocaixaDCASP002.php';
        $this->configurar();
    }

    private function configurar()
    {
        if ($this->ano < 2017) {
            $this->configurar2015();
        } elseif ($this->ano < 2020) {
            $this->configurar2017();
        } else {
            $this->configurar2020();
        }
    }

    private function configurar2015()
    {
        $this->codigoRelatorio = FluxoCaixaDCASP2015::CODIGO_RELATORIO;

        if (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 2) {
            $this->codigoRelatorio = FluxoCaixaDCASP2017IPC8::CODIGO_RELATORIO_IPC8;
        }

        if (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 1) {
            $this->codigoRelatorio = FluxoCaixaDCASP2017::CODIGO_RELATORIO;
        }

        if ($this->periodo) {
            $this->fluxoCaixa = new FluxoCaixaDCASP2015($this->ano, $this->codigoRelatorio, $this->periodo);
        }
    }

    private function configurar2017()
    {
        if (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 1) {
            $this->codigoRelatorio = FluxoCaixaDCASP2017::CODIGO_RELATORIO;
        }

        if (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 2) {
            $this->codigoRelatorio = FluxoCaixaDCASP2017IPC8::CODIGO_RELATORIO_IPC8;
        }

        if ($this->periodo && (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 1)) {
            $this->fluxoCaixa = new FluxoCaixaDCASP2017($this->ano, $this->codigoRelatorio, $this->periodo);
        }

        if ($this->periodo && (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 2)) {
            $this->fluxoCaixa = new FluxoCaixaDCASP2017IPC8($this->ano, $this->codigoRelatorio, $this->periodo);
        }
    }

    public static function isRelarorioFluxoCaixa($codigoRelatorio)
    {
        $relatorios = [
            FluxoCaixaDCASP::CODIGO_RELATORIO,
            FluxoCaixaDCASP2015::CODIGO_RELATORIO,
            FluxoCaixaDCASP2017::CODIGO_RELATORIO,
            FluxoCaixaDCASP2017IPC8::CODIGO_RELATORIO_IPC8,
            FluxoCaixaMCASP2020::CODIGO_RELATORIO,
            FluxoCaixaIPC82020::CODIGO_RELATORIO,
        ];

        return in_array($codigoRelatorio, $relatorios);
    }

    public function obterProcessador()
    {
        return $this->processador;
    }

    public function obterCodigoRelatorio()
    {
        if ($this->ano >= 2017) {
            $this->codigoRelatorio = FluxoCaixaDCASP2017::CODIGO_RELATORIO;

            if (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 2) {
                $this->codigoRelatorio = FluxoCaixaDCASP2017IPC8::CODIGO_RELATORIO_IPC8;
            }
        }

        if ($this->ano >= 2020) {
            $this->codigoRelatorio = FluxoCaixaMCASP2020::CODIGO_RELATORIO;

            if (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 2) {
                $this->codigoRelatorio = FluxoCaixaIPC82020::CODIGO_RELATORIO;
            }
        }

        return $this->codigoRelatorio;
    }

    /**
     * @return mixed
     */
    public function obterFluxoCaixa()
    {
        return $this->fluxoCaixa;
    }

    private function configurar2020()
    {
        $this->processador = 'con2_fluxocaixaDCASP2020.php';
        $this->codigoRelatorio = $this->obterCodigoRelatorio();

        /**
         * @todo quando implementar modelo IPC8 validar código aqui.
         */
        if ($this->periodo) {
            $modelo = 1;
            if (!empty($_SESSION['modelo_dcasp']) && $_SESSION['modelo_dcasp'] == 2) {
                $modelo = 2;
            }
            $fluxoCaixa = Factories\FluxoCaixaFactory::getModel($modelo, $this->ano, $this->periodo);
            $this->fluxoCaixa = $fluxoCaixa;
        }
    }
}
