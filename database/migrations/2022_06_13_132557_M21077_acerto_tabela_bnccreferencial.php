<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21077AcertoTabelaBnccreferencial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ocorrencias = 0;
        $objetosDeConhecimentoPorCodigo = [];
        $codigos = DB::table('bnccreferencial')
            ->select('ed168_codigohabilidade')
            ->distinct()
            ->whereNull('ed168_objeto_conhecimento')
            ->get()->map(function ($codigo) {
                return $codigo->ed168_codigohabilidade;
            });
        
        foreach ($codigos as $codigo) {
            $objetosDeConhecimentoPorCodigo[$codigo] = DB::table('bnccensinofundamental')
                ->where('ed148_codigo', $codigo)
                ->where('ed148_ano', 2022)
                ->get()
                ->toArray();
        }

        foreach ($objetosDeConhecimentoPorCodigo as $codigoHabilidade => $objetosConhecimento) { 
            $ocorrencias = DB::table('bnccreferencial')
                ->select('ed168_codigo')
                ->where('ed168_codigohabilidade', $codigoHabilidade)
                ->where('ed168_ano', 2022)
                ->get()
                ->map( function ($objeto, $key) use($objetosConhecimento, $codigoHabilidade) {
                    if (isset($objetosConhecimento[$key])) {
                        DB::table('bnccreferencial')->where('ed168_codigo', $objeto->ed168_codigo)->update([
                            'ed168_objeto_conhecimento' => $objetosConhecimento[$key]->ed148_objeto_conhecimento
                        ]);
                    }
                    return $key;
                });

            if (count($objetosConhecimento) > count($ocorrencias)) {
                for ($cont = count($ocorrencias); $cont <= count($objetosConhecimento); $cont++) {
                    if (isset($objetosConhecimento[$cont])) {
                        $id = "nextval('bnccreferencial_ed168_codigo_seq') as id";
                        $id = DB::table('bnccreferencial')->selectRaw($id)->value('id');
                        DB::table('bnccreferencial')->insert([
                            'ed168_codigo' => $id,
                            'ed168_ensino' =>  'EF',
                            'ed168_etapa' => $objetosConhecimento[$cont]->ed148_etapa,
                            'ed168_codigohabilidade' => $codigoHabilidade,
                            'ed168_codigoreferencial' => $codigoHabilidade,
                            'ed168_habilidade' => $objetosConhecimento[$cont]->ed148_habilidade,
                            'ed168_ano' => $objetosConhecimento[$cont]->ed148_ano,
                            'ed168_objeto_conhecimento' => $objetosConhecimento[$cont]->ed148_objeto_conhecimento
                        ]);             
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
