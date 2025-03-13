<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;

class ClearPayrollData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:clear {year} {month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove os dados de folha de pagamento de um determinado ano e mês';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $token = env('API_DATA_AVAILABLE_TOKEN');
        $url = env('API_DATA_AVAILABLE_URL');
        if (empty($token) || empty($url)) {
            die("Não foram configurados TOKEN e URL no .env para acesso a API de Débitos.");
        }

        $today = Carbon::now();
        $year = empty($this->argument('year')) ? $today->year : $this->argument('year');
        $month = empty($this->argument('month')) ? $today->month : $this->argument('month');

        $client = new \GuzzleHttp\Client([
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Content-type' => "application/json"
            ]
        ]);

        $data = [
            'year' => $year,
            'month' => $month,
        ];
        $response = $client->post(
            "{$url}/api/payrolls/clear",
            [
                RequestOptions::JSON => $data
            ]
        );

        if ($response->getStatusCode() !== 200) {
            die("A API não retornou status 200.");
        }
    }
}
