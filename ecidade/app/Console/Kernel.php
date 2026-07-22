<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AlteracaoSituacaoInscricaoTask::class,
        Commands\ControleParcelamentoVencidoTask::class,
        Commands\InativarVinculoProfessor::class,
        Commands\ControleParcelamentoVencidoTask::class,
        Commands\ClearPayrollData::class,
        Commands\GenerateDataPayrolls::class,
    ];

    public function bootstrap()
    {
        // Don't forget to call parent bootstrap
        parent::bootstrap();

        // Do your own bootstrapping stuff here
    }

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('matriculaonline:alteracaoinscricao')->dailyAt('21:00');
         $schedule->command('tributario:parcelamentovencido')->hourly();
         $schedule->command('agendamento:inativarvinculoprofessor')->dailyAt('21:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
