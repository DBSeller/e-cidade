<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Exception;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Validator;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoServidor;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoFuncao;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('ativos', function ($attribute, $value, $parameters) {

            $comissoesAtivas  = $parameters[0];
            $limiteDaComissao = $parameters[1];

            return $comissoesAtivas < $limiteDaComissao;
        });
    }

    /**
     * @throws Exception
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
