<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers;

use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentConfig;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentForm;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentPerc;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\TipoAsse;
use Illuminate\Support\Facades\DB;

class Concessao
{

    

    public static function assentServidor(
        $assent_inicio,
        $assent_antesdoinicio,
        $assent_meio,
        $assent_final,
        $assent_interrompe,
        $matricula
    ) {
        $sql = "";
        $tipos = ['inicio', 'antesdoinicio', 'meio', 'final', 'interrompe'];
        for ($i = 0; $i < count($tipos); $i++) {
            $assent = [];
            switch ($i) {
                case 0:
                    $assent = $assent_inicio;
                    break;
                case 1:
                    $assent = $assent_antesdoinicio;
                    break;
                case 2:
                    $assent = $assent_meio;
                    break;
                case 3:
                    $assent = $assent_final;
                    break;
                case 4:
                    $assent = $assent_interrompe;
                    break;
            }
            if (count($assent) != 0) {
                for ($x = 0; $x < count($assent); $x++) {
                    $sql .= ($sql == "" ? "" : " union all ");
                    $sql     .= " select " . ($i + 1) . " as ordem, 
                                     '" . $tipos[$i] . "' as tipo, 
                                    h16_codigo,
                                    h16_regist,
                                    h12_assent,
                                    h16_assent,
                                    h12_descr, 
                                    h16_dtconc as h16_dtconc,
                                    h16_dtterm,
                                    (h16_dtterm-h16_dtconc)+1 as dias  
                                from assenta 
                                inner join tipoasse on  h12_codigo = h16_assent 
                                    where h16_regist = $matricula
                                        and h16_assent in ( " . ($assent[$x]) . ")";
                }
            }
        }
        return DB::select(DB::raw(" select * from ($sql) as x order by ordem, h16_dtconc "));
    }


    public static function inicioProcessamento($data_inicial, $data_intervalo)
    {
        $sql = "select *  
                    from (select generate_series ('$data_inicial
                    '::date,'2999-01-01'::date ,'1 day')::date as dia) as data
                    inner join (select '$data_inicial'::date + interval '" .
            $data_intervalo[0] . "' as dia_recebe ";
        for ($di = 1; $di < count($data_intervalo); $di++) {
            $sql .= "   union all 
                        select ('$data_inicial'::date + interval '" . $data_intervalo[$di] . "')::date ";
        }
        $sql .= " ) as dataevento on data.dia = dataevento.dia_recebe";

        return DB::select(DB::raw($sql));
    }

    public static function assentamentosenvolvidos($matricula, $assentamentos_envolvidos)
    {
        return DB::select(DB::raw(
            "select h16_codigo,
                h16_regist,
                h12_assent,
                h16_assent,
                h12_descr,
                h16_dtconc,
                h16_dtterm,
                (h16_dtterm-h16_dtconc)+1 as dias  
            from assenta 
            inner join tipoasse on  h12_codigo = h16_assent 
            where h16_regist = $matricula 
                and h12_codigo in ($assentamentos_envolvidos)
            order by h16_dtconc"
        ));
    }

    public static function pagamentosFolha($matricula, $rubrica)
    {
        $sql = "select * from (select r14_anousu,r14_mesusu,r14_rubric, rh27_descr, r14_quant, r14_valor 
                    from gerfsal
                inner join rhrubricas on rh27_rubric = r14_rubric
                    where r14_regist = $matricula and r14_rubric in ('$rubrica')
                union all 
                select r48_anousu,r48_mesusu,r48_rubric, rh27_descr, r48_quant, r48_valor 
                    from gerfcom
                inner join rhrubricas on rh27_rubric = r48_rubric
                    where r48_regist = $matricula and r48_rubric in ('$rubrica')     
                union all 
                select r20_anousu,r20_mesusu,r20_rubric, rh27_descr, r20_quant, r20_valor 
                    from gerfres
                inner join rhrubricas on rh27_rubric = r20_rubric
                    where r20_regist = $matricula and r20_rubric in ('$rubrica')) as x  
                order by 1,2";
        return DB::select(DB::raw($sql));
    }

    public static function assentamentoInicio($rh500_sequencial)
    {
        return AssentForm::select('rh502_codigo')
            ->where('rh502_condicao', 'inicio')
            ->where('rh502_seqassentconf', $rh500_sequencial)->count();
    }

    public static function todosAssentamntosEnvolvidos($rh500_sequencial)
    {
        return AssentForm::select('rh502_codigo', 'rh502_condicao')
            ->where('rh502_seqassentconf', $rh500_sequencial)->get();
    }

    public static function periodos($rh500_sequencial)
    {
        return AssentPerc::where('rh501_seqasentconf', $rh500_sequencial)
            ->orderBy('rh501_ordem')->get();
    }

    public static function assentForm($select, $rh500_sequencial, $h16_codigo)
    {
        return AssentForm::select($select)
            ->where('rh502_codigo', $h16_codigo)
            ->where('rh502_seqassentconf', $rh500_sequencial)->get();
    }
}
