<?php

namespace App\Domain\Integracoes\EFDReinf\Services;

use App\Domain\Integracoes\EFDReinf\Models\EFDReinfConfiguracao;
use stdClass;

/**
 * Classe de servico para manipulacao
 * das configuracoes do EFD REINF
 */
class ConfiguracaoService
{
    /**
     * Model de configuracao
     *
     * @var EFDReinfConfiguracao|null
     */
    private $config;

    /**
     * Instancia de servico
     *
     * @var ConfiguracaoService
     */
    private static $instance;

    /**
     * Instituicao de manipulacao
     *
     * @var int
     */
    private $instit;

    /**
     * Singleton Construtor
     */
    private function __construct()
    {
    }

    /**
     * Controla acesso a instancia
     *
     * @param int $instit
     * @return ConfiguracaoService
     */
    public static function getInstance($instit)
    {
        if (empty($instance)) {
            self::$instance = new ConfiguracaoService;
            self::$instance->instit = $instit;
            self::$instance->config = EFDReinfConfiguracao::where('efd07_instit', '=', $instit)->first();
        }

        return self::$instance;
    }

    /**
     * Persiste configuracoes
     *
     * caso nao exista configurucao para essa instituicao
     * a mesma sera criada
     *
     * @param stdClass $data dados da configuracao
     * @param int $instit Instituicao
     * @return void
     */
    public function save(stdClass $data)
    {
        if (empty($this->config)) {
            $this->config = new EFDReinfConfiguracao;
            $this->config->efd07_instit = $this->instit;
        }

        $this->config->efd07_filtraorgaounidade = $data->efd07_filtraorgaounidade;
        $this->config->save();
    }

    /**
     * Configuracoes do EFDREINF
     *
     * @return EFDReinfConfiguracao|null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Checar se instituicao possui o filtro orgao unidade
     *
     * @return bool
     */
    public function filtraOrgaoUnidade()
    {
        if ($this->config) {
            return $this->config->efd07_filtraorgaounidade;
        }

        return false;
    }
}
