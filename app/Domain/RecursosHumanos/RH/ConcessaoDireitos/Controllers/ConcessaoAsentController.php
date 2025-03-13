<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\Assenta;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentConcedeConf;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentConfig;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\ConcessaoAssent;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\ConcessaoCalculo;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers\ConcessaoCalculo as ProvidersConcessaoCalculo;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers\ConcessaoCalculoProviders;
use DBPessoal;
use ECidade\RecursosHumanos\RH\ConcessaoDireitos\Controllers\ConcessaoAssent as ControllersConcessaoAssent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConcessaoAsentController extends Controller
{
    public function __construct()
    {
        require_once(modification("libs/db_stdlib.php"));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $matricula = $data['matricula'];
        $inst = $data['DB_instit'];
        try {
            $n = $this->concassaoAssent(
                $data['matricula'],
                $inst,
                $data['rh500_sequencial'],
                $data['h31_numero'],
                $data['h31_anousu'],
                $data['datainicio'],
                $data['datafinal']
            );
            if (empty($matricula)) {
                $result = $n;
            } else {
                $result = ConcessaoCalculoProviders::buscarconcessaocaluclo($matricula, $data['rh500_sequencial']);
            }
            
            return new DBJsonResponse($result, 'Processado com Sucesso!', 200);
        } catch (\Throwable $th) {
            return new DBJsonResponse('', 'Erro!', 400);
        }
    }

    public function concassaoAssent(
        $matricula,
        $inst,
        $rh504_seqassentconf,
        $h31_numero,
        $h31_anousu,
        $datainicial,
        $datafinal
    ) {
        $assentconcedeconfig = AssentConcedeConf::where('rh503_seqassentconf', $rh504_seqassentconf)
            ->get()->toArray();

        $assentamentos = array_map(function ($assenta) {
            if ($assenta['rh503_acao'] == 3) {
                return $assenta['rh503_codigo'];
            }
        }, $assentconcedeconfig);
        
        $datainicial = explode('/', $datainicial);
        $datainicial = $datainicial[2] . '-' . $datainicial[1] . '-' . $datainicial[0];

        $datafinal = explode('/', $datafinal);
        $datafinal = $datafinal[2] . '-' . $datafinal[1] . '-' . $datafinal[0];

        $concessao = ProvidersConcessaoCalculo::concessao(
            $matricula,
            $rh504_seqassentconf,
            $datainicial,
            $datafinal,
            array_filter($assentamentos)
        );

        $assent = AssentConfig::where('rh500_sequencial', $rh504_seqassentconf)->first();
        $anofolha = DBPessoal::getAnoFolha();
        $mesfolha = DBPessoal::getMesFolha();
        $h31_dtlanc = date('Y-m-d', db_getsession("DB_datausu"));
        $n = 0;

        foreach ($concessao as $key => $value) {
            $matricula = $value->rh504_regist;
            $dataportaria = $value->rh504_data;
            $dataassentamento = $value->rh504_data;
            /** verifica se concede ou nao concede */
            $assentam = $assent->rh500_condede;
            $concede = false;
            $naoconcede = false;
            try {
                foreach ($assentconcedeconfig as $key => $assentconcedeconf) {
                    if ($value->rh501_ordem == 1) { // se for primeiro pega
                        $assenta = Assenta::where('h16_assent', $assentconcedeconf['rh503_codigo'])
                            ->where('h16_regist', $matricula)
                            ->where('h16_dtterm', '<', $value->rh504_data) // ate
                            ->get();
                    } else { //pegar todos os assentamentos entre a data anterior e data da concessao
                        $concessao_anterior = ConcessaoCalculo::where('rh504_data', '<', $value->rh504_data)
                            ->where('rh504_regist', $matricula)
                            ->where('rh504_seqassentconf', $rh504_seqassentconf)
                            ->select('rh504_data')
                            ->orderBy('rh504_data', 'DESC')
                            ->first();
                        $assenta = Assenta::where('h16_assent', $assentconcedeconf['rh503_codigo'])
                            ->where('h16_regist', $matricula)
                            ->where('h16_dtconc', '>', $concessao_anterior->rh504_data) //de
                            ->where('h16_dtterm', '<', $value->rh504_data) // ate
                            ->get();
                    }

                    $dias = 0;
                    foreach ($assenta as $key => $ass) {
                        if ($assentconcedeconf['rh503_tipo'] == 1) { // se acumula
                            $dias += $ass->h16_quant; // soma os dias
                            if ($assentconcedeconf['rh503_acao'] == 1) { // concede
                                if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                                    $condicao = $dias . $assentconcedeconf['rh503_condicao'];
                                } else { // + Meses
                                    //converter pra meses
                                    $condicao = intdiv($dias, 30) . $assentconcedeconf['rh503_condicao'];
                                }
                                eval('if(' . $condicao . '){ $concede = true;}');
                            } elseif ($assentconcedeconf['rh503_acao'] == 2) { // nao concede
                                if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                                    $condicao = $dias . $assentconcedeconf['rh503_condicao'];
                                } else { // + Meses
                                    //converter pra meses
                                    $condicao = (intdiv($dias, 30)) . $assentconcedeconf['rh503_condicao'];
                                }
                                eval('if(' . $condicao . '){ $naoconcede = true;}');
                            }
                        } elseif ($assentconcedeconf['rh503_tipo'] == 2) { // se nao acumula
                            $dias = $ass->h16_quant;
                            if ($assentconcedeconf['rh503_acao'] == 1) { // concede
                                if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                                    $condicao = $dias . $assentconcedeconf['rh503_condicao'];
                                } else { // + Meses
                                    //converter pra meses
                                    $condicao = (intdiv($dias, 30)) . $assentconcedeconf['rh503_condicao'];
                                }
                                eval('if(' . $condicao . '){ $concede = true;}');
                            } elseif ($assentconcedeconf['rh503_acao'] == 2) { // nao concede
                                if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                                    $condicao = $dias . $assentconcedeconf['rh503_condicao'];
                                } else { // + Meses
                                    //converter pra meses
                                    $condicao = intdiv($dias, 30) . $assentconcedeconf['rh503_condicao'];
                                }
                                eval('if(' . $condicao . '){ $naoconcede = true;}');
                            }
                        } elseif ($assentconcedeconf['rh503_tipo'] == 3) {
                            if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                                $dias += $ass->h16_quant; // soma os dias
                                $calculo = ($dias) . $assentconcedeconf['rh503_condicao'];
                                $c = 0;
                                eval('$c = ' . $calculo . ';');
                                $dataassentamento = date(
                                    'd/m/Y',
                                    strtotime('+' . $c . ' days', strtotime($dataportaria))
                                );
                            } else { // + Meses
                                $dias += $ass->h16_quant; // soma os dias
                                $calculo = ($dias * 30) . $assentconcedeconf['rh503_condicao'];
                                $c = 0;
                                eval('$c = ' . $calculo . ';');
                                $dataassentamento = date(
                                    'd/m/Y',
                                    strtotime('+' . $c . ' days', strtotime($dataportaria))
                                );
                            }
                        }
                    }
                }

                if ($concede == false && $naoconcede == false || $concede == true) {
                    $assentam = $assent->rh500_condede;
                } elseif ($concede == false && $naoconcede == true) {
                    $assentam = $assent->rh500_naoconcede;
                }
                /** se concede ou nao concede faz isso */

                $portariatipo = DB::table('portariatipo')
                    ->join('tipoasse', 'h12_codigo', '=', 'h30_tipoasse')
                    ->join('portariatipoato', 'h41_sequencial', '=', 'h30_portariatipoato')
                    ->select('portariatipo.h30_sequencial', 'portariatipo.h30_amparolegal', 'h41_descr')
                    ->where('h12_codigo', $assentam)
                    ->first();

                $portaria =  ControllersConcessaoAssent::save(
                    '',
                    $portariatipo->h30_sequencial,
                    db_getsession("DB_id_usuario"),
                    $h31_numero,
                    $h31_anousu,
                    $dataportaria, //h31_dtportaria
                    $dataportaria, //h31_dtinicio
                    $h31_dtlanc,
                    $portariatipo->h30_amparolegal, //h31_amparolegal
                    $matricula,
                    $dataassentamento, //h16_dtconc
                    $dataassentamento, //h16_dtterm
                    $portariatipo->h41_descr //h16_atofic
                );
                if (isset($portaria) && !empty($portaria)) {
                    ConcessaoAssent::create([
                        'rh505_concessaocalculo' =>  $value->rh504_sequencial,
                        'rh505_codigo' =>  $portaria,
                        'rh505_anousu' =>  intval($anofolha),
                        'rh505_mesusu' =>  intval($mesfolha),
                        'rh505_data' => $value->rh504_dtproc,
                    ]);
                }
                $n++;
            } catch (\Throwable $th) {
                continue;
            }
        }

        return $n;
    }
}
