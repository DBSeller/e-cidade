<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22670AjusteAnexoIRgf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $codigo = DB::select("select nextval('orcparamseqcoluna_o115_sequencial_seq')")[0]->nextval;

        $data = [
            "o115_sequencial" => $codigo,
            "o115_anousu" =>2022 ,
            "o115_descricao" => 'INSCRITAS EM RP NÃO PROCESSADOS - Anulação do exercício',
            "o115_tipo" => 1,
            "o115_valoresdefault" => null,
            "o115_nomecoluna" => 'inscricao_menos_anulacao_rp_nao_processado',
            "o115_formula" => null,
            "o115_origem" => 0,
            "o115_relatorio" => 0
        ];

        DB::table('orcparamseqcoluna')->insert($data);


        DB::connection()->getPdo()->exec(<<<SQL
create temp table w_despesa_ajuste_anexo_1_rgf as
select  nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
        o69_codseq ,
        o69_codparamrel,
        $codigo as coluna,
        15 as ordem,
        o113_periodo,
        ''::varchar as formula
  from orcparamseq
  join orcparamrelperiodos on o113_orcparamrel = o69_codparamrel
 where o69_codparamrel = 260
   and o69_ordem in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

insert into orcparamseqorcparamseqcoluna
select * from w_despesa_ajuste_anexo_1_rgf;
SQL
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        delete from orcparamseqorcparamseqcoluna
        using orcparamseqcoluna
        where o116_orcparamseqcoluna = o115_sequencial
          and o115_nomecoluna = 'inscricao_menos_anulacao_rp_nao_processado';

        delete from orcparamseqcoluna where o115_nomecoluna = 'inscricao_menos_anulacao_rp_nao_processado';
SQL
        );
    }
}
