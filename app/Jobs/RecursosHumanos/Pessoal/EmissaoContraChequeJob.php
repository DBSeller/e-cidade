<?php

namespace App\Jobs\RecursosHumanos\Pessoal;

use App\Domain\Core\Models\QueuedJob;
use App\Domain\Core\Services\QueueService;
use App\Domain\RecursosHumanos\Pessoal\Relatorios\ContraChequePdf;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EmissaoContraChequeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ContraChequePdf
     */
    private $contraChequePdf;
    /**
     * @var QueuedJob
     */
    private $queuedJob;

    /**
     * @var integer
     */
    public $tries = 3;
    /**
     * @var QueueService
     */
    private $queueService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ContraChequePdf $contraChequePdf, QueueService $queueService)
    {
        $this->contraChequePdf = $contraChequePdf;
        $this->queueService = $queueService;
        $this->queuedJob = $queueService->next();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->queuedJob->refresh();
        if ($this->queuedJob->batch->cancelled) {
            return;
        }

        try {
            $this->dependencias();
            DB::beginTransaction();
            db_inicio_transacao();
            $this->verificaCacheAutenticacaoEstorage();
            $this->contraChequePdf->emitir();
            $this->gerarCacheAutenticacaoEstorage();
            $inQueue = $this->queueService->terminate($this->queuedJob);
            if ($inQueue === 0) {
                Artisan::call(
                    'payroll:clear',
                    ['year' => $this->contraChequePdf->getAno(), 'month' => $this->contraChequePdf->getMes()]
                );
                Artisan::call(
                    'payroll:load',
                    ['year' => $this->contraChequePdf->getAno(), 'month' => $this->contraChequePdf->getMes()]
                );
            }
            db_fim_transacao();
            DB::commit();
        } catch (Exception $exception) {
            db_fim_transacao(true);
            DB::rollback();
            if ($this->attempts() >= $this->tries || env('QUEUE_DRIVER') == 'sync') {
                throw $exception;
            }
            $this->release($this->attempts() * 60);
        }
    }

    private function verificaCacheAutenticacaoEstorage()
    {
        if (!empty(Cache::get('estorage_properties'))) {
            $_SESSION['estorage_properties'] = unserialize(Cache::get('estorage_properties'));
        }
    }

    private function gerarCacheAutenticacaoEstorage()
    {
        if (isset($_SESSION['estorage_properties'])) {
            Cache::put('estorage_properties', serialize($_SESSION['estorage_properties']), 10);
            unset($_SESSION['estorage_properties']);
        }
    }

    private function dependencias()
    {
        if (!defined('DB_BIBLIOT')) {
            define('DB_BIBLIOT', 1);
        }
        if (!defined('FPDF_FONTPATH')) {
            define('FPDF_FONTPATH', 'fpdf151/font/');
        }
        require_once(modification('fpdf151/fpdf.php'));
        require_once(modification("dbforms/db_funcoes.php"));
        $_SESSION["DB_ip"] = '127.0.0.1';
        $_SESSION["DB_datausu"] = time();
        $_SESSION["DB_id_usuario"] = "1";
        $_SESSION["DB_acessado"] = 9433155;
    }
}
