<?php

namespace App\Console\Commands;

use App\Jobs\RecursosHumanos\Pessoal\ProcessPayrollDataJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateDataPayrolls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:load {year?} {month?} {payroll?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrega as informações por ano e mês.';

    protected static $payrollsAvailable = [
        'salario',
        'ferias',
        'rescisao',
        'adiantamento',
        '13salario',
        'complementar',
        'fixo',
//        'previden',
//        'irf',
//        'suplementar',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now();
        $year = empty($this->argument('year')) ? $today->year : $this->argument('year');
        $month = empty($this->argument('month')) ? $today->month : $this->argument('month');
        $payrollTypes = empty($this->argument('payroll')) ? null : $this->argument('payroll');

        $payrollTypesExploded = [];
        if (!empty($payrollTypes)) {
            $payrollTypesExploded = explode(',', $payrollTypes);
            foreach ($payrollTypesExploded as $payrollType) {
                if (!in_array(trim($payrollType), self::$payrollsAvailable)) {
                    $this->error("A folha {$payrollType} não existe.");
                    exit;
                }
            }
        }

        $descriptionHelp = empty($payrollTypes) ? 'TODOS' : $payrollTypes;

        $this->info('Processando...');
        $this->warn("Ano: {$year}");
        $this->warn("Mês: {$month}");
        $this->warn("Tipo de Folha: {$descriptionHelp}");

        Log::info('Processando...');
        Log::info("Ano: {$year}");
        Log::info("Mês: {$month}");
        Log::info("Tipo de Folha: {$descriptionHelp}");

        ProcessPayrollDataJob::dispatch($year, $month, $payrollTypesExploded);
        $this->info("O job foi disparado.");
    }
}
