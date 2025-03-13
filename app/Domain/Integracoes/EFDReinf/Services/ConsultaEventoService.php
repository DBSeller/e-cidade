<?php

namespace App\Domain\Integracoes\EFDReinf\Services;

use Illuminate\Database\Capsule\Manager as DB;

class ConsultaEventoService
{
    /**
     * Verifica se existe evento(s) processado com sucesso
     *
     * @param string $event Tipo do evento R-XXXX
     * @param string $cgm contribuinte
     * @param string $referencia Identificador do evento
     * @return int
     */
    public static function hasProcessadoSucesso($event, $cgm, $referencia)
    {
        $result = DB::table('esocialenvio')
        ->join('esocialenviostatus', 'rh214_esocialenvio', 'rh213_sequencial')
        ->where('rh213_evento', '=', $event)
        ->where('rh213_empregador', '=', $cgm)
        ->whereRaw('rh213_responsavelpreenchimento ilike ?', [$referencia])
        ->where('rh214_situacao', '=', true)
        ->count();

        return $result;
    }
}
