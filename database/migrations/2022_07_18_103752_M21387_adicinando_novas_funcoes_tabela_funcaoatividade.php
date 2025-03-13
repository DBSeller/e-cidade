<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21387AdicinandoNovasFuncoesTabelaFuncaoatividade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $funcoes = [
            [
                "ed119_sequencial" => 7,
                "ed119_descricao" =>  "Guia-Intérprete",
                "ed119_docente" => false 
            ],
            [
                "ed119_sequencial" => 8,
                "ed119_descricao" =>  "Profissional de apoio escolar para aluno(a)s com deficiência (Lei 13.146/2015)",
                "ed119_docente" => false
            ],
            [
                "ed119_sequencial" => 9,
                "ed119_descricao" =>  "Instrutor da Educação Profissional",
                "ed119_docente" => false
            ]
        ];

        foreach ($funcoes as $funcao) {
            DB::table('escola.funcaoatividade')->insert($funcao);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('escola.funcaoatividade')->whereIn("ed119_sequencial", [7, 8, 9])->delete();
    }
}
