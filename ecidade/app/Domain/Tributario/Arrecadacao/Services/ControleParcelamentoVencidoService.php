<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use DateTime;
use Illuminate\Support\Facades\DB;
use App\Domain\Tributario\Arrecadacao\Factories\ControleParcelamentoVencidoFactory;
use App\Domain\Tributario\Arrecadacao\Models\AgendamentoControleParcelamento;

class ControleParcelamentoVencidoService
{
    /**
     * @var string
     */
    private $diaSemanaAtual;
    /**
     * @var string
     */
    private $horaAtual;
    /**
     * @var Illuminate\Database\Eloquent\Collection
     */
    private $agendamentos;

    public function __construct()
    {
        $diasSemana = ['DOMINGO', 'SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SABADO'];

        $data = new DateTime();
        $this->diaSemanaAtual = $diasSemana[date('w', $data->getTimestamp())];
        $this->horaAtual = $data->format('H');

        $this->agendamentos = AgendamentoControleParcelamento::where('ar49_dia_semana', $this->diaSemanaAtual)
            ->where(DB::raw('extract(hour from ar49_horario)'), $this->horaAtual)
            ->where('ar49_agendamento_ativo', true)
            ->get();
    }

    public function execute()
    {
        DB::beginTransaction();
        foreach ($this->agendamentos as $agendamento) {
            $acaoService = ControleParcelamentoVencidoFactory::getAcaoService($agendamento->ar49_acao);
            $acaoService->processar($agendamento);
        }
        DB::commit();
    }
}
