<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentConfig;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\ConcessaoCalculo;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\ConcessaoCalculoLog;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\ConcessaoCalculoNovaData;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\ConcessaoCalculoNovaDataLog;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers\Concessao;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers\ConcessaoCalculoProviders;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers\Rhpessoal as ProvidersRhpessoal;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessamentoConcessaoController extends Controller
{
    public function show()
    {
        $assenta = DB::table('assentconf')
            ->join('tipoasse', 'h12_codigo', '=', 'rh500_assentamento')
            ->select('h12_codigo', 'h12_descr', 'rh500_sequencial', 'rh500_datalimite')
            ->get();
        return new DBJsonResponse($assenta);
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $matricula = $data['matricula'];
        $inst = $data['DB_instit'];
        $rh500_sequencial = $data['rh500_sequencial'];
        $data_processamento = substr($data['dataprocessamento'], 6, 4) .
            '-' . substr($data['dataprocessamento'], 3, 2) .
            "-" . substr($data['dataprocessamento'], 0, 2);
        try {
            $assentconfig = AssentConfig::where('rh500_sequencial', $rh500_sequencial)
                ->leftJoin('pessoal.selecao', 'r44_selec', '=', 'rh500_selecao')
                ->first();

            if (!empty($matricula)) {
                if (!ProvidersRhpessoal::verificarMatriculaSelecao($assentconfig->r44_where, $matricula)) {
                    return new DBJsonResponse('', 'Funcionário sem configuração para esta Concessão', 400);
                }
                $this->processamento($matricula, $inst, $rh500_sequencial, $data_processamento, $data['DB_datausu']);
                $result = ConcessaoCalculoProviders::buscarconcessaocaluclo($matricula, $rh500_sequencial);
                return new DBJsonResponse($result, 'Processado com Sucesso!', 200);
            } else {
                $matriculas = ProvidersRhpessoal::matriculas(
                    $inst,
                    $data['DB_datausu'],
                    $assentconfig->r44_where,
                    null
                );

                foreach ($matriculas as $key => $value) {
                    $this->processamento($value->rh01_regist, $inst, $rh500_sequencial, $data_processamento);
                }
            }
            return new DBJsonResponse('', 'Processado com Sucesso!', 200);
        } catch (\Throwable $th) {
            return new DBJsonResponse('', 'Erro!', 400);
        }
    }

    public function processamento($matricula, $inst, $rh500_sequencial, $data_processamento)
    {
        $assentconfig = AssentConfig::where('rh500_sequencial', $rh500_sequencial)->first();

        if (strtotime($data_processamento) > strtotime($assentconfig->rh500_datalimite)) {
            $data_processamento = $assentconfig->rh500_datalimite;
        }

        $rhpessoalmov = ProvidersRhpessoal::admissrecis($inst, $matricula);

        if (count($rhpessoalmov) == 0) {
            return new DBJsonResponse(null, "Funcionario nao encontrado " . $matricula, 400);
        }

        //admissao do funcionario
        $data_admissao = $rhpessoalmov[0]->rh01_admiss;
        $data_recisao = $rhpessoalmov[0]->rh05_recis;
        //data final - rescisao, data de processamento ou assentamento final
        $data_final = '';
        $assentamentos_envolvidos = "";
        // busca assentamento de inicio
        $n_assent = Concessao::assentamentoInicio($rh500_sequencial);

        if ($n_assent == 0) {
            $assent_inicio[] = $data_admissao;
        }

        $assents = Concessao::todosAssentamntosEnvolvidos($rh500_sequencial);
        $assent_inicio = array();
        $assent_antesdoinicio = array();
        $assent_meio = array();
        $assent_final = array();
        $assent_interrompe = array();

        foreach ($assents as $key => $assent) {
            switch ($assent->rh502_condicao) {
                case 'inicio':
                    $assent_inicio[]  =  $assent->rh502_codigo;
                    $assentamentos_envolvidos .= ($assentamentos_envolvidos == "" ? "" : ",") . $assent->rh502_codigo;
                    break;
                case 'antesdoinicio':
                    $assent_antesdoinicio[] = $assent->rh502_codigo;
                    $assentamentos_envolvidos .= ($assentamentos_envolvidos == "" ? "" : ",") . $assent->rh502_codigo;
                    break;
                case 'meio':
                    $assent_meio[] = $assent->rh502_codigo;
                    $assentamentos_envolvidos .= ($assentamentos_envolvidos == "" ? "" : ",") . $assent->rh502_codigo;
                    break;
                case 'final':
                    $assent_final[] = $assent->rh502_codigo;
                    $assentamentos_envolvidos .= ($assentamentos_envolvidos == "" ? "" : ",") . $assent->rh502_codigo;
                    break;
                case 'interrompe':
                    $assent_interrompe[] = $assent->rh502_codigo;
                    $assentamentos_envolvidos .= ($assentamentos_envolvidos == "" ? "" : ",") . $assent->rh502_codigo;
                    break;
            }
        }
        // gera o sql para buscar todos os assentamentos
        $res = Concessao::assentServidor(
            $assent_inicio,
            $assent_antesdoinicio,
            $assent_meio,
            $assent_final,
            $assent_interrompe,
            $matricula
        );

        if (count($res) == 0) {
            return new DBJsonResponse(null, "Não tem assentamentos para este funcionario " . $matricula, 400);
        }

        $data_inicial = $res[0]->h16_dtconc;
        // Fixo Remover Futuramente
        $periodos = Concessao::periodos($rh500_sequencial);

        for ($i = 0; $i < count($periodos); $i++) {
            $data_intervalo[] = "+" . ($periodos[$i]->rh501_valor) . ' ' . $periodos[$i]->rh501_unidade;
        }
        $array = [];

        for ($i = 0; $i < count($res); $i++) {
            $tipo   = $res[$i]->tipo;
            switch ($tipo) {
                case 'inicio':
                    $data_inicial = $res[$i]->h16_dtconc;
                    $array[] = [$res[$i]->h16_codigo, $data_inicial];
                    $resa = Concessao::inicioProcessamento($data_inicial, $data_intervalo);
                    for ($a = 0; $a < count($resa); $a++) {
                        $dias_processamento[$resa[$a]->dia] = ($resa[$a]->dia_recebe == "" ? 0 : $resa[$a]->dia_recebe);
                    }
                    break;
                case 'antesdoinicio':
                    $dias_meio  = $res[$i]->dias;
                    $h16_assent = $res[$i]->h16_assent;
                    $h16_codigo = $res[$i]->h16_codigo;
                    $h16_dtconc = $res[$i]->h16_dtconc;
                    $resa = Concessao::assentForm(
                        [
                            'rh502_condicao',
                            'rh502_resultado'
                        ],
                        $rh500_sequencial,
                        $h16_assent
                    );

                    // processa todos registros para acessar as condicoes
                    $soma_dias = 0;
                    for ($x = 0; $x < count($resa); $x++) {
                        // null as rh502_condicao
                        $resa[$x]->rh502_condicao = null;
                        // condicao Ã© para ver se este assentamento sera processado ou nao dependendo dos dias dele
                        $condicao = $resa[$x]->rh502_condicao;
                        if ($condicao == "") {
                            $codform = 1;
                        } else {
                            $codform = ($dias_meio . $resa[$x]->rh502_condicao . '?1:2');
                        }
                        $operacao = "+";
                        if ($codform == 1) {
                            $resultado = $resa[$x]->rh502_resultado;
                            if ($resultado == "+dias" || $resultado == "") {
                                $soma_dias = $dias_meio;
                            } elseif ($resultado == "-dias") {
                                $soma_dias = $dias_meio;
                                $operacao = "-";
                            } elseif ($resultado != "") {
                                $soma_dias = $resultado;
                            }
                        }
                    }
                    reset($dias_processamento);
                    $data = '';
                    for ($a = 0; $a < count($dias_processamento); $a++) {
                        if (strtotime($dias_processamento[key($dias_processamento)]) >= strtotime($h16_dtconc)) {
                            $dias = ($operacao == '+' ? $soma_dias : $soma_dias * -1);
                            $novadata =  date('Y-m-d', strtotime(
                                '+' . $dias . ' days',
                                strtotime($dias_processamento[key($dias_processamento)])
                            ));
                            if ($data == '') {
                                $data = $novadata;
                            }
                            $dias_processamento[key($dias_processamento)] = $novadata;
                        }
                        next($dias_processamento);
                    }
                    if ($data != '') {
                        $array[] = [$h16_codigo, $h16_dtconc];
                        $data = '';
                    }
                    break;
                case 'meio':
                    $dias_meio  = $res[$i]->dias;
                    $h16_assent = $res[$i]->h16_assent;
                    $h16_codigo = $res[$i]->h16_codigo;
                    $h16_dtconc = $res[$i]->h16_dtconc;
                    $resa = Concessao::assentForm(
                        [
                            'rh502_condicao',
                            'rh502_resultado',
                            'rh502_operador',
                            'rh502_multiplicador'
                        ],
                        $rh500_sequencial,
                        $h16_assent
                    );
                    // processa todos registros para acessar as condicoes
                    $soma_dias = 0;
                    for ($x = 0; $x < count($resa); $x++) {
                        // condicao Ã© para ver se este assentamento sera processado ou nao dependendo dos dias dele
                        $condicao = $resa[$x]->rh502_condicao;
                        $operador = $resa[$x]->rh502_operador;
                        $multiplicador = $resa[$x]->rh502_multiplicador;
                        if ($condicao == "") {
                            $codform = 1;
                        } else {
                            $codform = ($dias_meio . $resa[$x]->rh502_condicao . '?1:2');
                        }
                        $operacao = "+";
                        if ($codform == 1) {
                            $resultado = $resa[$x]->rh502_resultado;
                            if ($resultado == "+dias") {
                                $soma_dias = $dias_meio;
                            } elseif ($resultado == "-dias") {
                                $soma_dias = $dias_meio;
                                $operacao = "-";
                            } else {
                                $valorresultado = $resultado;
                                $soma_dias = $valorresultado;
                            }
                        }
                        if ($operador == "+") {
                            $soma_dias = $soma_dias + $multiplicador;
                        } elseif ($operador == "-") {
                            $soma_dias = - ($soma_dias + $multiplicador);
                        } elseif ($operador == "*") {
                            $soma_dias = $soma_dias * $multiplicador;
                        } elseif ($operador == "m+") {
                            $soma_dias = $soma_dias + ($multiplicador * 30);
                        } elseif ($operador == "m-") {
                            $soma_dias = - ($soma_dias + ($multiplicador * 30));
                        } elseif ($operador == "m*") {
                            $soma_dias = $soma_dias * ($multiplicador * 30);
                        } else {
                            $soma_dias = $soma_dias;
                        }
                    }
                    $data = '';
                    // primeiro processamento de assentamento de meio
                    reset($dias_processamento);
                    for ($a = 0; $a < count($dias_processamento); $a++) {
                        if (strtotime($dias_processamento[key($dias_processamento)]) >= strtotime($h16_dtconc)) {
                            // aqui protela ou antecipa assentamento de meio
                            $dias = ($operacao == '+' ? $soma_dias : $soma_dias * -1);
                            $novadata =  date('Y-m-d', strtotime(
                                '+' . $dias . ' days',
                                strtotime($dias_processamento[key($dias_processamento)])
                            ));
                            if ($data == '') {
                                $data = $novadata;
                            }
                            $dias_processamento[key($dias_processamento)] = $novadata;
                        }
                        next($dias_processamento);
                    }
                    if ($data != '') {
                        $array[] = [$h16_codigo, $h16_dtconc];
                        $data = '';
                    }
                    break;
                case 'interrompe':
                    $dias_meio  = $res[$i]->dias;
                    $h16_assent = $res[$i]->h16_assent;
                    $h16_codigo = $res[$i]->h16_codigo;
                    $h16_dtconc = $res[$i]->h16_dtconc;
                    $h16_dtterm = $res[$i]->h16_dtterm;
                    $resa = Concessao::assentForm(
                        ['rh502_condicao', 'rh502_resultado'],
                        $rh500_sequencial,
                        $h16_assent
                    );

                    // processa todos registros para acessar as condicoes
                    $soma_dias = 0;
                    for ($x = 0; $x < count($resa); $x++) {
                        // null as rh502_condicao
                        $resa[$x]->rh502_condicao = null;
                        // condicao Ã© para ver se este assentamento sera processado ou nao dependendo dos dias dele
                        $condicao = $resa[$x]->rh502_condicao;
                        if ($condicao == "") {
                            $codform = 1;
                        } else {
                            $codform = ($dias_meio . $resa[$x]->rh502_condicao . '?1:2');
                        }
                        $operacao = "+";
                        if ($codform == 1) {
                            $resultado = $resa[$x]->rh502_resultado;
                            if ($resultado == "+dias" || $resultado == "") {
                            } elseif ($resultado == "-dias") {
                                $operacao = "-";
                            }
                        }
                    }

                    reset($dias_processamento);
                    $n = 0;
                    $soma_dias;
                    $data = '';
                    for ($a = 0; $a < count($dias_processamento); $a++) {
                        if (strtotime($dias_processamento[key($dias_processamento)]) >= strtotime($h16_dtconc)) {
                            if ($n == 0) {
                                prev($dias_processamento);
                                $ultimaconessao = new DateTime($dias_processamento[key($dias_processamento)]);
                                $data_fim = new DateTime($h16_dtterm);
                                $dateInterval = $ultimaconessao->diff($data_fim);
                                $soma_dias = $dateInterval->days; //1083  2,97 anos
                                next($dias_processamento);
                                $n++;
                            }
                            $dias = ($operacao == '+' ? $soma_dias : $soma_dias * -1);
                            $novadata =  date('Y-m-d', strtotime(
                                '+' . $dias . ' days',
                                strtotime($dias_processamento[key($dias_processamento)])
                            ));
                            if ($data == '') {
                                $data = $novadata;
                            }
                            $dias_processamento[key($dias_processamento)] = $novadata;
                        }
                        next($dias_processamento);
                    }
                    if ($data != '') {
                        $array[] = [$h16_codigo, $h16_dtconc];
                        $data = '';
                    }
                    break;
                case 'final':
                    $data_final = $res[$i]->h16_dtconc;
                    break;
            }
        }
        // quando data final for nula, significa que a data de processamento deve ser a final
        // para funcionarios em atividade
        if ($data_final == "") {
            if ($data_recisao != "") {
                $data_final = $data_recisao;
            } else {
                $data_final = $data_processamento;
            }
        }
        // contam das concessoes
        $qual_consessao = 1;
        // numero de concessoes apos data atual
        $quantos_apos = 1;
        $datas_concessoes = array();

        reset($dias_processamento);
        for ($x = 0; $x < count($dias_processamento); $x++) {
            if (strtotime($dias_processamento[key($dias_processamento)]) <= strtotime($data_final) ||
                strtotime($dias_processamento[key($dias_processamento)]) <= strtotime($data_processamento)
            ) {
                $datas_concessoes[$qual_consessao] = $dias_processamento[key($dias_processamento)];
                $qual_consessao++;
            }
            next($dias_processamento);
        }

        //ALL ConcessaoCalculo
        $concessaoCalculo = ConcessaoCalculo::where('rh504_regist', $matricula)
            ->where('rh504_seqassentconf', $rh500_sequencial)->get();

        //VERIFICA SE EXISTE COM ESSA MATRICULA
        if (count($concessaoCalculo) != 0) {
            //VERIFICA TODOS AS CONCESSOES
            for ($i = 1; $i <= count($datas_concessoes); $i++) {
                $ordem = $i;

                $where = [
                    ['rh504_regist', $matricula],
                    ['rh504_seqassentconf', $rh500_sequencial],
                    ['rh501_ordem', $ordem]
                ];
                $concessaosCalculo = ConcessaoCalculo::select(
                    'rh504_data',
                    'rh501_sequencial',
                    'rh505_sequencial',
                    'rh504_sequencial'
                )
                    ->where($where)
                    ->leftJoin('concessaoassent', 'rh505_concessaocalculo', '=', 'rh504_sequencial')
                    ->join('assentperc', 'rh504_seqassentperc', '=', 'rh501_sequencial')
                    ->get();

                foreach ($concessaosCalculo as $key => $concessaoCalculo) {
                    if ($concessaoCalculo->rh505_sequencial != null) {
                        $data = $concessaoCalculo->rh504_data;
                        if (strtotime($data) != strtotime($datas_concessoes[$i])) {
                            ConcessaoCalculoNovaDataLog::where(
                                'rh508_concessaocalculo',
                                $concessaoCalculo->rh504_sequencial
                            )->delete();
                            ConcessaoCalculoNovaData::where(
                                'rh506_concessaocalculo',
                                $concessaoCalculo->rh504_sequencial
                            )->delete();
                            $data = ConcessaoCalculoNovaData::create([
                                'rh506_concessaocalculo' => $concessaoCalculo->rh504_sequencial,
                                'rh506_datanova' => $datas_concessoes[$i],
                            ]);

                            foreach ($array as $key => $value) {
                                if (strtotime($value[1]) <= strtotime($datas_concessoes[$i])) {
                                    ConcessaoCalculoNovaDataLog::create([
                                        'rh508_concessaocalculo' => $concessaoCalculo->rh504_sequencial,
                                        'rh508_codigo' => $value[0],
                                    ]);
                                }
                            }
                        }
                    } else {
                        ConcessaoCalculoLog::where(
                            'rh507_concessaocalculo',
                            $concessaoCalculo->rh504_sequencial
                        )->delete();
                        ConcessaoCalculo::where('rh504_sequencial', $concessaoCalculo->rh504_sequencial)->delete();
                    }
                }
            }
        }
        $this->gravaConcessaoCalculo($matricula, $rh500_sequencial, $datas_concessoes, $periodos, $array);
    }


    private function gravaConcessaoCalculo($matricula, $rh500_sequencial, $datas_concessoes, $periodos, $array)
    {
        for ($i = 0; $i < count($datas_concessoes); $i++) {
            if (ConcessaoCalculo::where('rh504_seqassentperc', $periodos[$i]->rh501_sequencial)
                ->where('rh504_regist', $matricula)
                ->where('rh504_seqassentconf', $rh500_sequencial)
                ->count() == 0
            ) {
                $data = ConcessaoCalculo::create([
                    'rh504_regist' =>  $matricula,
                    'rh504_seqassentconf' => $rh500_sequencial,
                    'rh504_seqassentperc' => $periodos[$i]->rh501_sequencial,
                    'rh504_dtproc' => date("Y-m-d"),
                    'rh504_data' => $datas_concessoes[$i + 1],
                ]);
                foreach ($array as $key => $value) {
                    if (strtotime($value[1]) <= strtotime($datas_concessoes[$i + 1])) {
                        ConcessaoCalculoLog::create([
                            'rh507_concessaocalculo' => $data->rh504_sequencial,
                            'rh507_assent' => $value[0],
                        ]);
                    }
                }
            }
        }
    }
}
