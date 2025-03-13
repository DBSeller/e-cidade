<?php

namespace App\Domain\Patrimonial\Providers;

use App\Domain\Core\Base\Providers\BaseDeferredProvider;
use App\Domain\Patrimonial\Ouvidoria\Repository\Atendimento\AtendimentoRepository;

class PatrimonialServiceProvider extends BaseDeferredProvider
{
    /**
     * Registra o service provider.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'AtendimentoRepository',
            function () {
                return new AtendimentoRepository();
            }
        );
    }

    /**
     * Retorna os Services registrados no provider.
     * @return array
     */
    public function provides()
    {
        return array(
            'AtendimentoRepository',
        );
    }
}
