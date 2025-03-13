<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers;

use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentConfig;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentForm;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\TipoAsse;
use funcao;
use Illuminate\Support\Facades\DB;

class ConcessaoConfig
{

    public static function gravarAssentConfig($data)
    {
        if (empty($data['rh500_selecao'])) {
            $data['rh500_selecao'] = null;
        }
        if (empty($data['rh500_naoconcede'])) {
            $data['rh500_naoconcede'] = null;
        }
        if ($data['rh500_sequencial'] == '') {
            return AssentConfig::create([
                'rh500_assentamento' =>  $data['rh500_assentamento'],
                'rh500_datalimite' =>  $data['rh500_datalimite'],
                'rh500_condede' =>   $data['rh500_condede'],
                'rh500_naoconcede' =>  $data['rh500_naoconcede'],
                'rh500_selecao' =>  $data['rh500_selecao'],
            ]);
        } else {
            $AssentConfig =  AssentConfig::where('rh500_sequencial', $data['rh500_sequencial'])
                ->update([
                    'rh500_assentamento' =>  $data['rh500_assentamento'],
                    'rh500_datalimite' =>  $data['rh500_datalimite'],
                    'rh500_condede' =>   $data['rh500_condede'],
                    'rh500_naoconcede' =>  $data['rh500_naoconcede'],
                    'rh500_selecao' =>  $data['rh500_selecao'],
                ]);
            if ($AssentConfig == 1) {
                return AssentConfig::where('rh500_sequencial', $data['rh500_sequencial'])->first();
            } else {
                return $AssentConfig;
            }
        }
    }

    public static function show($rh500_sequencial)
    {
        $dados = AssentConfig::where('rh500_sequencial', $rh500_sequencial)->first();
        if ($dados) {
            $dados->rh500_condededescr = TipoAsse::select('h12_descr')
                ->where('h12_codigo', $dados->rh500_condede)->first()->h12_descr;

            if ($dados->rh500_naoconcede) {
                $dados->rh500_noacondededescr = TipoAsse::select('h12_descr')
                    ->where('h12_codigo', $dados->rh500_naoconcede)->first()->h12_descr;
            } else {
                $dados->rh500_noacondededescr = null;
            }
            return $dados;
        } else {
            return $dados;
        }
    }
}
